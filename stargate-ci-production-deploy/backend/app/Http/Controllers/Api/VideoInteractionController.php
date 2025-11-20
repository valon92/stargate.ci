<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoInteraction;
use App\Models\Event;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VideoInteractionController extends Controller
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
     * Toggle like for a video
     */
    public function toggleLike(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'video_content_id' => 'required|string',
                'subscriber_id' => 'nullable|integer|exists:subscribers,id',
                'session_id' => 'nullable|string'
            ]);

            $videoContentId = $request->video_content_id;
            $subscriberId = $request->subscriber_id;
            $sessionId = $request->session_id;

            // Ensure video/event exists, create if not
            $video = $this->ensureContentExists($videoContentId);

            // Toggle like
            $isLiked = VideoInteraction::toggle(
                $videoContentId,
                'like',
                $subscriberId,
                $sessionId
            );

            // Update video counts
            $video->updateCounts();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_liked' => $isLiked,
                    'likes_count' => $video->fresh()->likes_count
                ],
                'message' => $isLiked ? 'Video liked successfully' : 'Video unliked successfully'
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
     * Add share for a video
     */
    public function addShare(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'video_content_id' => 'required|string',
                'subscriber_id' => 'nullable|integer|exists:subscribers,id',
                'session_id' => 'nullable|string',
                'platform' => 'nullable|string|in:facebook,twitter,whatsapp,messenger,linkedin,email'
            ]);

            $videoContentId = $request->video_content_id;
            $subscriberId = $request->subscriber_id;
            $sessionId = $request->session_id;
            $platform = $request->platform;

            // Ensure video/event exists, create if not
            $video = $this->ensureContentExists($videoContentId);

            // Add share (always create new share, don't toggle)
            try {
                VideoInteraction::create([
                    'video_content_id' => $videoContentId,
                    'subscriber_id' => $subscriberId,
                    'session_id' => $sessionId,
                    'interaction_type' => 'share',
                    'platform' => $platform,
                    'interacted_at' => now()
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // If duplicate, just continue - share already exists
                if (strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                    // Share already exists, continue
                } else {
                    throw $e;
                }
            }

            // Update video counts
            $video->updateCounts();

            return response()->json([
                'success' => true,
                'data' => [
                    'shares_count' => $video->fresh()->shares_count
                ],
                'message' => 'Share recorded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record share',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add view for a video
     */
    public function addView(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'video_content_id' => 'required|string',
                'subscriber_id' => 'nullable|integer|exists:subscribers,id',
                'session_id' => 'nullable|string'
            ]);

            $videoContentId = $request->video_content_id;
            $subscriberId = $request->subscriber_id;
            $sessionId = $request->session_id;

            // Ensure video/event exists, create if not
            $video = $this->ensureContentExists($videoContentId);

            // Add view (only if not already viewed by this user/session)
            $existingView = VideoInteraction::where('video_content_id', $videoContentId)
                ->where('interaction_type', 'view');

            if ($subscriberId) {
                $existingView->where('subscriber_id', $subscriberId);
            } elseif ($sessionId) {
                $existingView->where('session_id', $sessionId);
            }

            if (!$existingView->exists()) {
                VideoInteraction::create([
                    'video_content_id' => $videoContentId,
                    'subscriber_id' => $subscriberId,
                    'session_id' => $sessionId,
                    'interaction_type' => 'view',
                    'interacted_at' => now()
                ]);

                // Update video counts
                $video->updateCounts();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'views_count' => $video->fresh()->views_count
                ],
                'message' => 'View recorded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record view',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if user has interacted with video
     */
    public function checkInteraction(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'video_content_id' => 'required|string',
                'subscriber_id' => 'nullable|integer|exists:subscribers,id',
                'session_id' => 'nullable|string',
                'interaction_type' => 'required|string|in:like,share,view'
            ]);

            $videoContentId = $request->video_content_id;
            $subscriberId = $request->subscriber_id;
            $sessionId = $request->session_id;
            $interactionType = $request->interaction_type;

            $hasInteracted = VideoInteraction::exists(
                $videoContentId,
                $interactionType,
                $subscriberId,
                $sessionId
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'has_interacted' => $hasInteracted
                ],
                'message' => 'Interaction status retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check interaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's interactions for a video
     */
    public function getUserInteractions(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'video_content_id' => 'required|string',
                'subscriber_id' => 'nullable|integer|exists:subscribers,id',
                'session_id' => 'nullable|string'
            ]);

            $videoContentId = $request->video_content_id;
            $subscriberId = $request->subscriber_id;
            $sessionId = $request->session_id;

            $query = VideoInteraction::where('video_content_id', $videoContentId);

            if ($subscriberId) {
                $query->where('subscriber_id', $subscriberId);
            } elseif ($sessionId) {
                $query->where('session_id', $sessionId);
            }

            $interactions = $query->get()->pluck('interaction_type')->toArray();

            return response()->json([
                'success' => true,
                'data' => [
                    'interactions' => $interactions,
                    'is_liked' => in_array('like', $interactions),
                    'has_shared' => in_array('share', $interactions),
                    'has_viewed' => in_array('view', $interactions)
                ],
                'message' => 'User interactions retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user interactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}