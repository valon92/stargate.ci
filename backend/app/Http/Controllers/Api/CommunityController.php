<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use App\Models\CommunityCategory;
use App\Models\CommunityComment;
use App\Models\CommunityLike;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CommunityController extends Controller
{
    /**
     * Get all community posts
     */
    public function getPosts(Request $request): JsonResponse
    {
        $query = CommunityPost::with(['author', 'category', 'tags'])
            ->where('status', 'published');

        // Apply filters
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('tags')) {
            $query->whereJsonContains('tags', $request->tags);
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $posts = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get community categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = CommunityCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get specific community post
     */
    public function showPost($id): JsonResponse
    {
        $post = CommunityPost::with(['author', 'category', 'tags', 'comments.author'])
            ->findOrFail($id);

        // Increment view count
        $post->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    /**
     * Create community post (authenticated)
     */
    public function createPost(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:community_categories,id',
            'tags' => 'sometimes|array',
            'is_anonymous' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = CommunityPost::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'author_id' => $request->user()->id,
            'tags' => $request->tags ?? [],
            'is_anonymous' => $request->is_anonymous ?? false,
            'status' => 'published',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    /**
     * Update community post (authenticated)
     */
    public function updatePost(Request $request, $id): JsonResponse
    {
        $post = CommunityPost::findOrFail($id);

        // Check if user can edit this post
        if ($post->author_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category_id' => 'sometimes|exists:community_categories,id',
            'tags' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $post->update($request->only(['title', 'content', 'category_id', 'tags']));

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ]);
    }

    /**
     * Delete community post (authenticated)
     */
    public function deletePost(Request $request, $id): JsonResponse
    {
        $post = CommunityPost::findOrFail($id);

        // Check if user can delete this post
        if ($post->author_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    /**
     * Add comment to post (authenticated)
     */
    public function addComment(Request $request, $id): JsonResponse
    {
        $post = CommunityPost::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
            'parent_id' => 'sometimes|exists:community_comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = CommunityComment::create([
            'post_id' => $post->id,
            'author_id' => $request->user()->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
            'status' => 'published',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ], 201);
    }

    /**
     * Like/unlike post (authenticated)
     */
    public function likePost(Request $request, $id): JsonResponse
    {
        $post = CommunityPost::findOrFail($id);
        $user = $request->user();

        $existingLike = CommunityLike::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $post->decrement('like_count');
            
            return response()->json([
                'success' => true,
                'message' => 'Post unliked',
                'data' => ['liked' => false]
            ]);
        } else {
            // Like
            CommunityLike::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            $post->increment('like_count');
            
            return response()->json([
                'success' => true,
                'message' => 'Post liked',
                'data' => ['liked' => true]
            ]);
        }
    }

    /**
     * Get all posts (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $query = CommunityPost::with(['author', 'category']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $posts = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Moderate post (admin only)
     */
    public function moderatePost(Request $request, $id): JsonResponse
    {
        $post = CommunityPost::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:published,hidden,deleted',
            'moderation_notes' => 'sometimes|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $post->update([
            'status' => $request->status,
            'moderation_notes' => $request->moderation_notes,
            'moderated_by' => $request->user()->id,
            'moderated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post moderated successfully',
            'data' => $post
        ]);
    }

    /**
     * Get community statistics
     */
    public function getStats(): JsonResponse
    {
        $stats = [
            'total_posts' => CommunityPost::where('status', 'published')->count(),
            'total_comments' => CommunityComment::where('status', 'published')->count(),
            'total_likes' => CommunityLike::count(),
            'active_users' => CommunityPost::distinct('author_id')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}