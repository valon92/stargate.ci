<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Perform global search across all content types
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:2|max:255',
                'type' => 'sometimes|string|in:all,articles,videos,faqs,users,comments',
                'category' => 'sometimes|string',
                'author' => 'sometimes|string',
                'date_from' => 'sometimes|date',
                'date_to' => 'sometimes|date',
                'sort' => 'sometimes|string|in:relevance,date,title,author',
                'limit' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $options = [
                'type' => $request->get('type', 'all'),
                'filters' => [
                    'category' => $request->get('category'),
                    'author' => $request->get('author'),
                    'date_from' => $request->get('date_from'),
                    'date_to' => $request->get('date_to'),
                ],
                'sort' => $request->get('sort', 'relevance'),
                'limit' => $request->get('limit', 20),
                'page' => $request->get('page', 1)
            ];

            // Remove null values from filters
            $options['filters'] = array_filter($options['filters'], function($value) {
                return $value !== null;
            });

            $results = $this->searchService->search($request->get('q'), $options);

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get search suggestions
     */
    public function suggestions(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'suggestions' => [],
                        'popular' => $this->searchService->getPopularSearches(),
                        'trending' => $this->searchService->getTrendingSearches()
                    ]
                ]);
            }

            $suggestions = $this->searchService->getSuggestions($query);

            return response()->json([
                'success' => true,
                'data' => [
                    'suggestions' => $suggestions,
                    'popular' => $this->searchService->getPopularSearches(),
                    'trending' => $this->searchService->getTrendingSearches()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get suggestions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get search filters and categories
     */
    public function filters(Request $request): JsonResponse
    {
        try {
            $type = $request->get('type', 'all');
            
            $filters = $this->searchService->getAvailableFilters($type);

            return response()->json([
                'success' => true,
                'data' => $filters
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get filters: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get search analytics and statistics
     */
    public function analytics(Request $request): JsonResponse
    {
        try {
            $analytics = $this->searchService->getSearchAnalytics();

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save search query for user
     */
    public function saveSearch(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|max:255',
                'filters' => 'sometimes|array',
                'name' => 'sometimes|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $savedSearch = $this->searchService->saveSearch(
                $request->user()->id,
                $request->get('query'),
                $request->get('filters', []),
                $request->get('name')
            );

            return response()->json([
                'success' => true,
                'data' => $savedSearch
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save search: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's saved searches
     */
    public function savedSearches(Request $request): JsonResponse
    {
        try {
            $savedSearches = $this->searchService->getUserSavedSearches($request->user()->id);

            return response()->json([
                'success' => true,
                'data' => $savedSearches
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get saved searches: ' . $e->getMessage()
            ], 500);
        }
    }
}

