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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    /**
     * Get all community posts
     */
    public function getPosts(Request $request): JsonResponse
    {
        try {
            $query = CommunityPost::with(['author', 'category', 'comments']);

            // Filter by category
            if ($request->has('category')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            } else {
                $query->where('status', 'published');
            }

            // Filter by author
            if ($request->has('author')) {
                $query->where('author_id', $request->author);
            }

            // Search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('content', 'LIKE', "%{$search}%")
                      ->orWhere('tags', 'LIKE', "%{$search}%");
                });
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSortFields = ['created_at', 'updated_at', 'view_count', 'like_count', 'comment_count', 'title'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $posts = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $posts->items(),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch community posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific community post
     */
    public function showPost($id): JsonResponse
    {
        try {
            $post = CommunityPost::with(['author', 'category', 'comments.author'])
                ->where('id', $id)
                ->orWhere('slug', $id)
                ->firstOrFail();

            // Increment view count
            $post->increment('view_count');

            return response()->json([
                'success' => true,
                'data' => $post
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Community post not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new community post (authenticated users)
     */
    public function createPost(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|exists:community_categories,id',
                'tags' => 'array',
                'status' => 'in:draft,published,archived',
                'featured_image' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $slug = $request->slug ?? Str::slug($request->title);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (CommunityPost::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $post = CommunityPost::create([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'category_id' => $request->category_id,
                'author_id' => auth()->id(),
                'tags' => $request->tags ?? [],
                'status' => $request->status ?? 'published',
                'featured_image' => $request->featured_image,
                'published_at' => $request->status === 'published' ? now() : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Community post created successfully',
                'data' => $post->load(['author', 'category'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create community post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a community post (authenticated users - own posts only)
     */
    public function updatePost(Request $request, $id): JsonResponse
    {
        try {
            $post = CommunityPost::findOrFail($id);
            
            // Check if user owns the post or is admin
            if ($post->author_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this post'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'category_id' => 'sometimes|exists:community_categories,id',
                'tags' => 'sometimes|array',
                'status' => 'sometimes|in:draft,published,archived',
                'featured_image' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only([
                'title', 'content', 'category_id', 'tags', 'status', 'featured_image'
            ]);

            // Handle slug update
            if ($request->has('title') && $request->title !== $post->title) {
                $slug = Str::slug($request->title);
                $originalSlug = $slug;
                $counter = 1;
                while (CommunityPost::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                $updateData['slug'] = $slug;
            }

            // Handle publish date
            if ($request->has('status') && $request->status === 'published' && $post->status !== 'published') {
                $updateData['published_at'] = now();
            }

            $post->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Community post updated successfully',
                'data' => $post->load(['author', 'category'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update community post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a community post (authenticated users - own posts only)
     */
    public function deletePost($id): JsonResponse
    {
        try {
            $post = CommunityPost::findOrFail($id);
            
            // Check if user owns the post or is admin
            if ($post->author_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this post'
                ], 403);
            }

            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Community post deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete community post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get community categories
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = CommunityCategory::where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'description', 'color']);

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch community categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add comment to a post
     */
    public function addComment(Request $request, $id): JsonResponse
    {
        try {
            $post = CommunityPost::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:1000',
                'parent_id' => 'nullable|exists:community_comments,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comment = CommunityComment::create([
                'post_id' => $id,
                'author_id' => auth()->id(),
                'content' => $request->content,
                'parent_id' => $request->parent_id,
                'status' => 'approved'
            ]);

            // Increment comment count
            $post->increment('comment_count');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $comment->load('author')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Like/Unlike a post
     */
    public function likePost($id): JsonResponse
    {
        try {
            $post = CommunityPost::findOrFail($id);
            $user = auth()->user();

            // Check if user already liked this post
            $existingLike = CommunityLike::where('post_id', $id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingLike) {
                // Unlike
                $existingLike->delete();
                $post->decrement('like_count');
                $liked = false;
            } else {
                // Like
                CommunityLike::create([
                    'post_id' => $id,
                    'user_id' => $user->id
                ]);
                $post->increment('like_count');
                $liked = true;
            }

            return response()->json([
                'success' => true,
                'message' => $liked ? 'Post liked' : 'Post unliked',
                'data' => [
                    'liked' => $liked,
                    'like_count' => $post->fresh()->like_count
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle like',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular posts
     */
    public function getPopular(): JsonResponse
    {
        try {
            $posts = CommunityPost::with(['author', 'category'])
                ->where('status', 'published')
                ->orderBy('like_count', 'desc')
                ->orderBy('view_count', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $posts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch popular posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent posts
     */
    public function getRecent(): JsonResponse
    {
        try {
            $posts = CommunityPost::with(['author', 'category'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $posts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recent posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Get all posts for moderation
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = CommunityPost::with(['author', 'category']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by moderation status
            if ($request->has('moderation_status')) {
                $query->where('moderation_status', $request->moderation_status);
            }

            $posts = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $posts->items(),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch posts for moderation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Moderate a post
     */
    public function moderatePost(Request $request, $id): JsonResponse
    {
        try {
            $post = CommunityPost::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'moderation_status' => 'required|in:approved,rejected,pending',
                'moderation_notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $post->update([
                'moderation_status' => $request->moderation_status,
                'moderation_notes' => $request->moderation_notes,
                'moderated_by' => auth()->id(),
                'moderated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post moderation updated successfully',
                'data' => $post
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to moderate post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}