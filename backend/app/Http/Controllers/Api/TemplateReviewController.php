<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateReview;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TemplateReviewController extends Controller
{
    /**
     * Get reviews for a specific template
     */
    public function index(Request $request, string $templateSlug): JsonResponse
    {
        try {
            $template = Template::where('slug', $templateSlug)->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            $query = $template->reviews()->with('user');

            // Filter by rating
            if ($request->has('rating')) {
                $query->where('rating', $request->rating);
            }

            // Filter by verified downloads
            if ($request->has('verified') && $request->verified) {
                $query->verified();
            }

            // Sort by helpful count or date
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            if ($sortBy === 'helpful') {
                $query->orderBy('helpful_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            $reviews = $query->paginate($request->get('per_page', 10));

            return response()->json([
                'success' => true,
                'data' => $reviews->items(),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ],
                'template' => [
                    'id' => $template->id,
                    'name' => $template->name,
                    'slug' => $template->slug,
                    'rating' => $template->rating,
                    'review_count' => $template->review_count
                ],
                'message' => 'Reviews retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new review
     */
    public function store(Request $request, string $templateSlug): JsonResponse
    {
        try {
            $template = Template::where('slug', $templateSlug)->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            // Check if user already reviewed this template
            $existingReview = TemplateReview::where('template_id', $template->id)
                ->where('user_id', Auth::id())
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this template'
                ], 409);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:2000',
                'pros' => 'nullable|array',
                'cons' => 'nullable|array',
                'pros.*' => 'string|max:255',
                'cons.*' => 'string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if user has downloaded this template
            $hasDownloaded = $template->downloads()
                ->where('user_id', Auth::id())
                ->exists();

            $review = TemplateReview::create([
                'template_id' => $template->id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'review' => $request->review,
                'pros' => $request->pros,
                'cons' => $request->cons,
                'is_verified_download' => $hasDownloaded
            ]);

            // Update template rating stats
            $template->updateRatingStats();

            return response()->json([
                'success' => true,
                'data' => $review->load('user'),
                'message' => 'Review created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a review
     */
    public function update(Request $request, string $templateSlug, int $reviewId): JsonResponse
    {
        try {
            $template = Template::where('slug', $templateSlug)->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            $review = TemplateReview::where('id', $reviewId)
                ->where('template_id', $template->id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or you are not authorized to update it'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'rating' => 'sometimes|integer|min:1|max:5',
                'review' => 'nullable|string|max:2000',
                'pros' => 'nullable|array',
                'cons' => 'nullable|array',
                'pros.*' => 'string|max:255',
                'cons.*' => 'string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review->update($request->only(['rating', 'review', 'pros', 'cons']));

            // Update template rating stats
            $template->updateRatingStats();

            return response()->json([
                'success' => true,
                'data' => $review->load('user'),
                'message' => 'Review updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a review
     */
    public function destroy(string $templateSlug, int $reviewId): JsonResponse
    {
        try {
            $template = Template::where('slug', $templateSlug)->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            $review = TemplateReview::where('id', $reviewId)
                ->where('template_id', $template->id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found or you are not authorized to delete it'
                ], 404);
            }

            $review->delete();

            // Update template rating stats
            $template->updateRatingStats();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a review as helpful
     */
    public function markHelpful(Request $request, string $templateSlug, int $reviewId): JsonResponse
    {
        try {
            $template = Template::where('slug', $templateSlug)->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            $review = TemplateReview::where('id', $reviewId)
                ->where('template_id', $template->id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found'
                ], 404);
            }

            // Check if user already marked this review as helpful
            // For simplicity, we'll just increment the helpful count
            // In a real app, you'd want to track which users marked it helpful
            $review->increment('helpful_count');

            return response()->json([
                'success' => true,
                'data' => [
                    'helpful_count' => $review->helpful_count
                ],
                'message' => 'Review marked as helpful'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark review as helpful',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}