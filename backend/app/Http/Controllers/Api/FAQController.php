<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FAQController extends Controller
{
    /**
     * Get all FAQs
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = FAQ::query();

            // Filter by category if provided
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            } else {
                $query->where('status', 'active');
            }

            // Search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('question', 'LIKE', "%{$search}%")
                      ->orWhere('answer', 'LIKE', "%{$search}%")
                      ->orWhere('tags', 'LIKE', "%{$search}%");
                });
            }

            // Sort by order
            $query->orderBy('order', 'asc');

            $faqs = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $faqs->items(),
                'pagination' => [
                    'current_page' => $faqs->currentPage(),
                    'last_page' => $faqs->lastPage(),
                    'per_page' => $faqs->perPage(),
                    'total' => $faqs->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch FAQs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific FAQ
     */
    public function show($id): JsonResponse
    {
        try {
            $faq = FAQ::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $faq
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new FAQ (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'required|string|max:500',
                'answer' => 'required|string',
                'category' => 'required|string|max:100',
                'order' => 'integer|min:0',
                'tags' => 'array',
                'status' => 'in:active,inactive,draft'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $faq = FAQ::create([
                'question' => $request->question,
                'answer' => $request->answer,
                'category' => $request->category,
                'order' => $request->order ?? 0,
                'tags' => $request->tags ?? [],
                'status' => $request->status ?? 'active',
                'author_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'FAQ created successfully',
                'data' => $faq
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create FAQ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an FAQ (Admin only)
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $faq = FAQ::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'question' => 'sometimes|string|max:500',
                'answer' => 'sometimes|string',
                'category' => 'sometimes|string|max:100',
                'order' => 'sometimes|integer|min:0',
                'tags' => 'sometimes|array',
                'status' => 'sometimes|in:active,inactive,draft'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $faq->update($request->only([
                'question', 'answer', 'category', 'order', 'tags', 'status'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'FAQ updated successfully',
                'data' => $faq
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update FAQ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an FAQ (Admin only)
     */
    public function destroy($id): JsonResponse
    {
        try {
            $faq = FAQ::findOrFail($id);
            $faq->delete();

            return response()->json([
                'success' => true,
                'message' => 'FAQ deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete FAQ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get FAQ categories
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = FAQ::select('category')
                ->distinct()
                ->where('status', 'active')
                ->orderBy('category')
                ->pluck('category');

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular FAQs
     */
    public function getPopular(): JsonResponse
    {
        try {
            $faqs = FAQ::where('status', 'active')
                ->orderBy('view_count', 'desc')
                ->orderBy('order', 'asc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $faqs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch popular FAQs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Increment view count
     */
    public function incrementView($id): JsonResponse
    {
        try {
            $faq = FAQ::findOrFail($id);
            $faq->increment('view_count');

            return response()->json([
                'success' => true,
                'message' => 'View count updated'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update view count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}