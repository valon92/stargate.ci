<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    /**
     * Get all videos with their interaction counts
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $videos = Video::active()
                ->when($request->has('type'), function ($query) use ($request) {
                    return $query->ofType($request->type);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $videos,
                'message' => 'Videos retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve videos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific video by content_id
     */
    public function show(string $contentId): JsonResponse
    {
        try {
            $video = Video::where('content_id', $contentId)
                ->active()
                ->first();

            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found'
                ], 404);
            }

            // Load relationships
            $video->load(['comments.subscriber', 'comments.replies.subscriber']);

            return response()->json([
                'success' => true,
                'data' => $video,
                'message' => 'Video retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve video',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create or update a video
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'content_id' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'youtube_id' => 'nullable|string|max:255',
                'youtube_url' => 'nullable|string|max:500',
                'content_type' => 'required|string|in:video,news,event',
                'metadata' => 'nullable|array'
            ]);

            $video = Video::updateOrCreate(
                ['content_id' => $request->content_id],
                $request->only([
                    'title', 'description', 'youtube_id', 'youtube_url',
                    'content_type', 'metadata'
                ])
            );

            return response()->json([
                'success' => true,
                'data' => $video,
                'message' => 'Video saved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save video',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update video interaction counts
     */
    public function updateCounts(string $contentId): JsonResponse
    {
        try {
            $video = Video::where('content_id', $contentId)->first();

            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found'
                ], 404);
            }

            $video->updateCounts();

            return response()->json([
                'success' => true,
                'data' => $video->fresh(),
                'message' => 'Video counts updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update video counts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get video statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_videos' => Video::active()->count(),
                'total_likes' => Video::active()->sum('likes_count'),
                'total_comments' => Video::active()->sum('comments_count'),
                'total_shares' => Video::active()->sum('shares_count'),
                'total_views' => Video::active()->sum('views_count'),
                'videos_by_type' => Video::active()
                    ->selectRaw('content_type, COUNT(*) as count')
                    ->groupBy('content_type')
                    ->pluck('count', 'content_type')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Video statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve video statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}