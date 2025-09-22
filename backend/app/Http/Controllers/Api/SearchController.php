<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SearchIndex;
use App\Models\SearchQuery;
use App\Models\SearchFilter;
use App\Models\SearchSuggestion;
use App\Models\SearchHistory;
use App\Models\SavedSearch;
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
     * Global search
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2|max:255',
            'type' => 'sometimes|string|in:all,content,community,users',
            'filters' => 'sometimes|array',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = $request->q;
        $type = $request->type ?? 'all';
        $filters = $request->filters ?? [];
        $perPage = $request->per_page ?? 20;
        $page = $request->page ?? 1;
        $offset = ($page - 1) * $perPage;

        try {
            $searchResults = $this->searchService->search($query, [
                'type' => $type,
                'filters' => $filters,
                'limit' => $perPage,
                'offset' => $offset
            ]);

            return response()->json([
                'success' => true,
                'data' => $searchResults
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get search suggestions
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => [
                    'suggestions' => []
                ]
            ]);
        }

        try {
            $suggestions = $this->searchService->getSuggestions($query, $type);

            return response()->json([
                'success' => true,
                'data' => [
                    'suggestions' => $suggestions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get suggestions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available search filters
     */
    public function getFilters(): JsonResponse
    {
        $filters = SearchFilter::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'filters' => $filters
            ]
        ]);
    }

    /**
     * Save search (authenticated)
     */
    public function saveSearch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'query' => 'required|string|max:255',
            'search_type' => 'required|string',
            'filters' => 'sometimes|array',
            'is_public' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $savedSearch = SavedSearch::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'query' => $request->query,
            'search_type' => $request->search_type,
            'filters' => $request->filters ?? [],
            'is_public' => $request->is_public ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Search saved successfully',
            'data' => $savedSearch
        ], 201);
    }

    /**
     * Get search history (authenticated)
     */
    public function getSearchHistory(Request $request): JsonResponse
    {
        $history = SearchHistory::where('user_id', $request->user()->id)
            ->orderBy('searched_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'history' => $history
            ]
        ]);
    }

    /**
     * Delete search history item (authenticated)
     */
    public function deleteSearchHistory(Request $request, $id): JsonResponse
    {
        $historyItem = SearchHistory::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $historyItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Search history item deleted successfully'
        ]);
    }

    /**
     * Get popular searches
     */
    public function getPopularSearches(): JsonResponse
    {
        $popularSearches = SearchQuery::selectRaw('query, COUNT(*) as search_count')
            ->where('searched_at', '>=', now()->subDays(30))
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'popular_searches' => $popularSearches
            ]
        ]);
    }

    /**
     * Get trending searches
     */
    public function getTrendingSearches(): JsonResponse
    {
        $trendingSearches = SearchQuery::selectRaw('query, COUNT(*) as search_count')
            ->where('searched_at', '>=', now()->subDays(7))
            ->groupBy('query')
            ->orderBy('search_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'trending_searches' => $trendingSearches
            ]
        ]);
    }

    /**
     * Get search suggestions based on query
     */
    private function getSearchSuggestions(string $query): array
    {
        $suggestions = SearchSuggestion::where('suggestion', 'like', '%' . $query . '%')
            ->where('is_active', true)
            ->orderBy('usage_count', 'desc')
            ->limit(5)
            ->get();

        return $suggestions->pluck('suggestion')->toArray();
    }
}