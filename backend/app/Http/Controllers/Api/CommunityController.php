<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use App\Models\CommunityComment;
use App\Models\CommunityLike;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    /**
     * Get all community posts
     */
    public function index(Request $request)
    {
        try {
            $query = CommunityPost::with(['subscriber', 'comments.subscriber'])
                ->published()
                ->orderBy('is_pinned', 'desc')
                ->orderBy('created_at', 'desc');

            // Filter by category
            if ($request->has('category') && $request->category) {
                $query->where('category', $request->category);
            }

            // Filter by search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // Get subscriber ID if authenticated
            $subscriberId = null;
            if ($request->user()) {
                $subscriber = Subscriber::where('email', $request->user()->email)->first();
                $subscriberId = $subscriber?->id;
            }

            $perPage = $request->get('per_page', 15);
            $posts = $query->paginate($perPage);

            // Add like status for each post
            $posts->getCollection()->transform(function ($post) use ($subscriberId) {
                $post->is_liked = $post->isLikedBy($subscriberId);
                return $post;
            });

            return response()->json([
                'success' => true,
                'data' => $posts->items(),
                'pagination' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load community posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new community post
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'category' => 'required|string|in:general,experience,question,idea,discussion',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            // Generate slug from title
            $slug = \Illuminate\Support\Str::slug($request->title);
            $count = CommunityPost::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            
            // Map category to category_id if needed
            $categoryIdMap = [
                'general' => 1,
                'experience' => 2,
                'question' => 3,
                'idea' => 4,
                'discussion' => 5
            ];
            $categoryId = $categoryIdMap[$request->category] ?? 1;
            
            // Get user_id from subscriber
            $userId = $user->id;
            
            $post = CommunityPost::create([
                'subscriber_id' => $subscriber->id,
                'user_id' => $userId, // Required by existing table structure
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'category' => $request->category,
                'category_id' => $categoryId,
                'type' => 'post', // Required by existing table structure
                'tags' => is_array($request->tags) ? json_encode($request->tags) : ($request->tags ?? null),
                'status' => 'published'
            ]);

            $post->load('subscriber');

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific post with comments
     */
    public function show(Request $request, string $id)
    {
        try {
            $post = CommunityPost::with(['subscriber', 'comments.subscriber', 'comments.replies.subscriber'])
                ->published()
                ->findOrFail($id);

            // Increment views using DB facade
            DB::table('community_posts')->where('id', $post->id)->increment('views_count');

            // Get subscriber ID if authenticated
            $subscriberId = null;
            if ($request->user()) {
                $subscriber = Subscriber::where('email', $request->user()->email)->first();
                $subscriberId = $subscriber?->id;
            }

            $post->is_liked = $post->isLikedBy($subscriberId);

            // Add like status for comments
            $post->comments->each(function ($comment) use ($subscriberId) {
                $comment->is_liked = $comment->isLikedBy($subscriberId);
                $comment->replies->each(function ($reply) use ($subscriberId) {
                    $reply->is_liked = $reply->isLikedBy($subscriberId);
                });
            });

            return response()->json([
                'success' => true,
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update a post
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string|min:10',
            'category' => 'sometimes|string|in:general,experience,question,idea,discussion',
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $post = CommunityPost::findOrFail($id);

            // Check if user owns the post
            if ($post->subscriber_id !== $subscriber->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $post->update($request->only(['title', 'content', 'category', 'tags']));
            $post->load('subscriber');

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully',
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a post
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $post = CommunityPost::findOrFail($id);

            // Check if user owns the post
            if ($post->subscriber_id !== $subscriber->id) {
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Like/Unlike a post
     */
    public function like(Request $request, string $id)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $post = CommunityPost::findOrFail($id);
            $like = CommunityLike::where('subscriber_id', $subscriber->id)
                ->where('likeable_id', $post->id)
                ->where('likeable_type', CommunityPost::class)
                ->first();

            if ($like) {
                // Unlike
                $like->delete();
                // Use DB facade to ensure column exists
                DB::table('community_posts')->where('id', $post->id)->decrement('likes_count');
                $isLiked = false;
            } else {
                // Like
                CommunityLike::create([
                    'subscriber_id' => $subscriber->id,
                    'user_id' => $user->id, // Required by existing table structure
                    'likeable_id' => $post->id,
                    'likeable_type' => CommunityPost::class
                ]);
                // Use DB facade to ensure column exists
                DB::table('community_posts')->where('id', $post->id)->increment('likes_count');
                $isLiked = true;
            }

            $post->refresh();
            return response()->json([
                'success' => true,
                'message' => $isLiked ? 'Post liked' : 'Post unliked',
                'data' => [
                    'is_liked' => $isLiked,
                    'likes_count' => $post->likes_count
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to like post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a comment to a post
     */
    public function addComment(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:1',
            'parent_id' => 'sometimes|nullable|exists:community_comments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $post = CommunityPost::findOrFail($id);

            $comment = CommunityComment::create([
                'community_post_id' => $post->id,
                'post_id' => $post->id, // Required by existing table structure
                'subscriber_id' => $subscriber->id,
                'user_id' => $user->id, // Required by existing table structure
                'parent_id' => $request->parent_id,
                'content' => $request->content,
                'status' => 'published'
            ]);

            DB::table('community_posts')->where('id', $post->id)->increment('comments_count');
            $comment->load('subscriber');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $comment
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
     * Share a post
     */
    public function share(Request $request, string $id)
    {
        try {
            $post = CommunityPost::findOrFail($id);
            DB::table('community_posts')->where('id', $post->id)->increment('shares_count');
            $post->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Post shared successfully',
                'data' => [
                    'shares_count' => $post->shares_count
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to share post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Like/Unlike a comment
     */
    public function likeComment(Request $request, string $id)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $comment = CommunityComment::findOrFail($id);
            $like = CommunityLike::where('subscriber_id', $subscriber->id)
                ->where('likeable_id', $comment->id)
                ->where('likeable_type', CommunityComment::class)
                ->first();

            if ($like) {
                // Unlike
                $like->delete();
                DB::table('community_comments')->where('id', $comment->id)->decrement('likes_count');
                $isLiked = false;
            } else {
                // Like
                CommunityLike::create([
                    'subscriber_id' => $subscriber->id,
                    'user_id' => $user->id, // Required by existing table structure
                    'likeable_id' => $comment->id,
                    'likeable_type' => CommunityComment::class
                ]);
                DB::table('community_comments')->where('id', $comment->id)->increment('likes_count');
                $isLiked = true;
            }

            $comment->refresh();
            return response()->json([
                'success' => true,
                'message' => $isLiked ? 'Comment liked' : 'Comment unliked',
                'data' => [
                    'is_liked' => $isLiked,
                    'likes_count' => $comment->likes_count
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to like comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories
     */
    public function categories()
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 'general', 'name' => 'General Discussion', 'icon' => '💬'],
                ['id' => 'experience', 'name' => 'Experiences', 'icon' => '✨'],
                ['id' => 'question', 'name' => 'Questions', 'icon' => '❓'],
                ['id' => 'idea', 'name' => 'Ideas & Suggestions', 'icon' => '💡'],
                ['id' => 'discussion', 'name' => 'Discussions', 'icon' => '🗣️']
            ]
        ]);
    }
}
