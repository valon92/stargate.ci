<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoComment;
use App\Models\Event;
use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VideoCommentController extends Controller
{
    /**
     * Ensure video or event exists, create Video record if needed
     */
    private function ensureContentExists(string $contentId): Video
    {
        $video = Video::where('content_id', $contentId)->first();
        
        if ($video) {
            return $video;
        }

        // Check if it's an event (contentId might be string or integer)
        $eventId = is_numeric($contentId) ? (int) $contentId : $contentId;
        $event = Event::find($eventId);
        
        if ($event) {
            // Create Video record for event
            return Video::create([
                'content_id' => (string) $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'youtube_id' => $event->video_url ?: '',
                'youtube_url' => $event->video_url ?: '',
                'content_type' => 'event',
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'views_count' => 0,
                'is_active' => $event->is_active,
                'metadata' => [
                    'event_id' => $event->id,
                    'event_category' => $event->category,
                    'event_type' => $event->type,
                    'event_date' => $event->event_date,
                    'event_source' => $event->source
                ]
            ]);
        }

        // Fallback: create generic video record
        return Video::create([
            'content_id' => $contentId,
            'title' => 'Content ' . $contentId,
            'description' => 'Description for ' . $contentId,
            'youtube_id' => $contentId,
            'youtube_url' => 'https://www.youtube.com/embed/' . $contentId,
            'content_type' => 'video',
            'likes_count' => 0,
            'comments_count' => 0,
            'shares_count' => 0,
            'views_count' => 0,
            'is_active' => true
        ]);
    }

    /**
     * Get comments for a video
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $videoContentId = $request->input('video_content_id');
            
            // Debug: Log to file directly
            file_put_contents(storage_path('logs/debug.log'), 'VideoCommentController@index - video_content_id: ' . $videoContentId . PHP_EOL, FILE_APPEND);
            
            if (!$videoContentId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video content ID is required'
                ], 400);
            }

            // Ensure video/event exists, create if not
            $video = $this->ensureContentExists($videoContentId);
            \Log::info('VideoCommentController@index - Content exists: ' . $videoContentId);

            $comments = VideoComment::where('video_content_id', $videoContentId)
                ->active()
                ->main()
                ->with(['subscriber', 'replies.subscriber'])
                ->orderBy('is_pinned', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Transform comments to include author info
            $transformedComments = $comments->map(function ($comment) {
                // Debug logging
                \Log::info('VideoCommentController@index - Comment ID: ' . $comment->id);
                \Log::info('VideoCommentController@index - Comment subscriber_id: ' . $comment->subscriber_id);
                \Log::info('VideoCommentController@index - Comment subscriber loaded: ' . ($comment->subscriber ? 'Yes' : 'No'));
                if ($comment->subscriber) {
                    \Log::info('VideoCommentController@index - Subscriber username: ' . $comment->subscriber->username);
                    \Log::info('VideoCommentController@index - Subscriber display_name: ' . $comment->subscriber->display_name);
                }
                \Log::info('VideoCommentController@index - Comment author_name: ' . $comment->author_name);
                
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'author_name' => $comment->author_name,
                    'author_avatar' => $comment->author_avatar,
                    'subscriber_id' => $comment->subscriber_id, // Add this for frontend debugging
                    'likes_count' => $comment->likes_count,
                    'is_pinned' => $comment->is_pinned,
                    'is_edited' => $comment->is_edited,
                    'created_at' => $comment->created_at,
                    'edited_at' => $comment->edited_at,
                    'replies' => $comment->replies->map(function ($reply) {
                        // Debug logging for replies
                        \Log::info('VideoCommentController@index - Reply ID: ' . $reply->id);
                        \Log::info('VideoCommentController@index - Reply subscriber_id: ' . $reply->subscriber_id);
                        \Log::info('VideoCommentController@index - Reply subscriber loaded: ' . ($reply->subscriber ? 'Yes' : 'No'));
                        if ($reply->subscriber) {
                            \Log::info('VideoCommentController@index - Reply Subscriber username: ' . $reply->subscriber->username);
                        }
                        \Log::info('VideoCommentController@index - Reply author_name: ' . $reply->author_name);
                        
                        return [
                            'id' => $reply->id,
                            'content' => $reply->content,
                            'author_name' => $reply->author_name,
                            'author_avatar' => $reply->author_avatar,
                            'subscriber_id' => $reply->subscriber_id, // Add this for frontend debugging
                            'likes_count' => $reply->likes_count,
                            'is_edited' => $reply->is_edited,
                            'created_at' => $reply->created_at,
                            'edited_at' => $reply->edited_at,
                        ];
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedComments,
                'message' => 'Comments retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve comments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new comment
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'video_content_id' => 'required|string',
                'content' => 'required|string|max:1000',
                'subscriber_id' => 'nullable|integer|exists:subscribers,id',
                'session_id' => 'nullable|string',
                'parent_id' => 'nullable|integer|exists:video_comments,id'
            ]);

            $videoContentId = $request->video_content_id;

            // Ensure video/event exists, create if not
            $video = $this->ensureContentExists($videoContentId);

            // Create comment
            $comment = VideoComment::create([
                'video_content_id' => $videoContentId,
                'subscriber_id' => $request->subscriber_id,
                'session_id' => $request->session_id,
                'parent_id' => $request->parent_id,
                'content' => $request->content,
                'is_active' => true
            ]);

            // Load subscriber info
            $comment->load('subscriber');

            // Update video counts
            $video->updateCounts();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'author_name' => $comment->author_name,
                    'author_avatar' => $comment->author_avatar,
                    'likes_count' => $comment->likes_count,
                    'is_pinned' => $comment->is_pinned,
                    'is_edited' => $comment->is_edited,
                    'created_at' => $comment->created_at,
                    'replies' => []
                ],
                'message' => 'Comment added successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a comment
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000'
            ]);

            $comment = VideoComment::find($id);
            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment not found'
                ], 404);
            }

            $comment->update([
                'content' => $request->content
            ]);

            $comment->markAsEdited();

            return response()->json([
                'success' => true,
                'data' => $comment,
                'message' => 'Comment updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a comment
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $comment = VideoComment::find($id);
            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment not found'
                ], 404);
            }

            $comment->update(['is_active' => false]);

            // Update video counts
            $video = Video::where('content_id', $comment->video_content_id)->first();
            if ($video) {
                $video->updateCounts();
            }

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle like for a comment
     */
    public function toggleLike(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'comment_id' => 'required|integer|exists:video_comments,id',
                'subscriber_id' => 'nullable|integer|exists:subscribers,id',
                'session_id' => 'nullable|string'
            ]);

            $commentId = $request->comment_id;
            $subscriberId = $request->subscriber_id;
            $sessionId = $request->session_id;

            // Toggle like
            $isLiked = CommentLike::toggle($commentId, $subscriberId, $sessionId);

            // Update comment likes count
            $comment = VideoComment::find($commentId);
            $comment->updateLikesCount();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_liked' => $isLiked,
                    'likes_count' => $comment->fresh()->likes_count
                ],
                'message' => $isLiked ? 'Comment liked successfully' : 'Comment unliked successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle comment like',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle pin for a comment
     */
    public function togglePin(int $id): JsonResponse
    {
        try {
            $comment = VideoComment::find($id);
            if (!$comment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Comment not found'
                ], 404);
            }

            $isPinned = $comment->togglePin();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_pinned' => $isPinned
                ],
                'message' => $isPinned ? 'Comment pinned successfully' : 'Comment unpinned successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle comment pin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if comment is liked by user
     */
    public function checkLike(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'comment_id' => 'required|integer|exists:video_comments,id',
                'subscriber_id' => 'nullable|integer|exists:subscribers,id',
                'session_id' => 'nullable|string'
            ]);

            $commentId = $request->comment_id;
            $subscriberId = $request->subscriber_id;
            $sessionId = $request->session_id;

            $isLiked = CommentLike::isLiked($commentId, $subscriberId, $sessionId);

            return response()->json([
                'success' => true,
                'data' => [
                    'is_liked' => $isLiked
                ],
                'message' => 'Like status retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check like status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}