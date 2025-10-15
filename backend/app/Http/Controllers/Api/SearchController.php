<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\FAQ;
use App\Models\Tutorial;
use App\Models\CommunityPost;
use App\Models\SearchQuery;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Perform global search across all content types
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:2|max:100',
                'type' => 'sometimes|in:all,articles,faqs,tutorials,community',
                'category' => 'sometimes|string',
                'limit' => 'sometimes|integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = $request->q;
            $type = $request->get('type', 'all');
            $category = $request->get('category');
            $limit = $request->get('limit', 20);

            // Log search query
            $this->logSearchQuery($query, $type, $category);

            $results = [];

            // Search Articles
            if ($type === 'all' || $type === 'articles') {
                $articles = $this->searchArticles($query, $category, $limit);
                $results['articles'] = $articles;
            }

            // Search FAQs
            if ($type === 'all' || $type === 'faqs') {
                $faqs = $this->searchFAQs($query, $category, $limit);
                $results['faqs'] = $faqs;
            }

            // Search Tutorials
            if ($type === 'all' || $type === 'tutorials') {
                $tutorials = $this->searchTutorials($query, $category, $limit);
                $results['tutorials'] = $tutorials;
            }

            // Search Community Posts
            if ($type === 'all' || $type === 'community') {
                $posts = $this->searchCommunityPosts($query, $category, $limit);
                $results['community_posts'] = $posts;
            }

            // Calculate total results
            $totalResults = array_sum(array_map('count', $results));

            return response()->json([
                'success' => true,
                'data' => $results,
                'meta' => [
                    'query' => $query,
                    'type' => $type,
                    'category' => $category,
                    'total_results' => $totalResults,
                    'searched_at' => now()->toISOString()
                ]
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
        try {
            $query = $request->get('q', '');
            
            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $suggestions = [];

            // Get popular search queries
            $popularQueries = SearchQuery::where('query', 'LIKE', "%{$query}%")
                ->orderBy('count', 'desc')
                ->limit(5)
                ->pluck('query')
                ->toArray();

            // Get article titles
            $articleTitles = Article::where('title', 'LIKE', "%{$query}%")
                ->where('status', 'published')
                ->limit(3)
                ->pluck('title')
                ->toArray();

            // Get FAQ questions
            $faqQuestions = FAQ::where('question', 'LIKE', "%{$query}%")
                ->where('status', 'active')
                ->limit(3)
                ->pluck('question')
                ->toArray();

            $suggestions = array_merge($popularQueries, $articleTitles, $faqQuestions);
            $suggestions = array_unique($suggestions);
            $suggestions = array_slice($suggestions, 0, 10);

            return response()->json([
                'success' => true,
                'data' => $suggestions
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
     * Get search filters
     */
    public function getFilters(): JsonResponse
    {
        try {
            $filters = [
                'types' => [
                    ['value' => 'all', 'label' => 'All Content'],
                    ['value' => 'articles', 'label' => 'Articles'],
                    ['value' => 'faqs', 'label' => 'FAQs'],
                    ['value' => 'tutorials', 'label' => 'Tutorials'],
                    ['value' => 'community', 'label' => 'Community']
                ],
                'categories' => $this->getSearchCategories(),
                'date_ranges' => [
                    ['value' => 'all', 'label' => 'All Time'],
                    ['value' => 'today', 'label' => 'Today'],
                    ['value' => 'week', 'label' => 'This Week'],
                    ['value' => 'month', 'label' => 'This Month'],
                    ['value' => 'year', 'label' => 'This Year']
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $filters
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get filters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save search query (authenticated users)
     */
    public function saveSearch(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string|max:100',
                'filters' => 'sometimes|array',
                'results_count' => 'sometimes|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = auth()->user();
            
            $searchQuery = SearchQuery::create([
                'user_id' => $user->id,
                'query' => $request->query,
                'filters' => $request->filters ?? [],
                'results_count' => $request->results_count ?? 0,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Search saved successfully',
                'data' => $searchQuery
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save search',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user search history
     */
    public function getSearchHistory(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $history = SearchQuery::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $history
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get search history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete search history item
     */
    public function deleteSearchHistory($id): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $searchQuery = SearchQuery::where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            
            $searchQuery->delete();

            return response()->json([
                'success' => true,
                'message' => 'Search history item deleted'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete search history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search articles
     */
    private function searchArticles(string $query, ?string $category, int $limit): array
    {
        $articlesQuery = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('tags', 'LIKE', "%{$query}%");
            });

        if ($category) {
            $articlesQuery->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        return $articlesQuery->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Search FAQs
     */
    private function searchFAQs(string $query, ?string $category, int $limit): array
    {
        $faqsQuery = FAQ::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('question', 'LIKE', "%{$query}%")
                  ->orWhere('answer', 'LIKE', "%{$query}%")
                  ->orWhere('tags', 'LIKE', "%{$query}%");
            });

        if ($category) {
            $faqsQuery->where('category', $category);
        }

        return $faqsQuery->orderBy('order', 'asc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Search tutorials
     */
    private function searchTutorials(string $query, ?string $category, int $limit): array
    {
        $tutorialsQuery = Tutorial::with(['author'])
            ->where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('overview', 'LIKE', "%{$query}%");
            });

        if ($category) {
            $tutorialsQuery->whereHas('content', function ($q) use ($category) {
                $q->whereHas('category', function ($q2) use ($category) {
                    $q2->where('slug', $category);
                });
            });
        }

        return $tutorialsQuery->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Search community posts
     */
    private function searchCommunityPosts(string $query, ?string $category, int $limit): array
    {
        $postsQuery = CommunityPost::with(['author', 'category'])
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('tags', 'LIKE', "%{$query}%");
            });

        if ($category) {
            $postsQuery->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        return $postsQuery->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get search categories
     */
    private function getSearchCategories(): array
    {
        $categories = [];

        // Article categories
        $articleCategories = DB::table('content_categories')
            ->where('status', 'active')
            ->select('name', 'slug')
            ->get();

        foreach ($articleCategories as $category) {
            $categories[] = [
                'value' => $category->slug,
                'label' => $category->name,
                'type' => 'articles'
            ];
        }

        // FAQ categories
        $faqCategories = FAQ::select('category')
            ->distinct()
            ->where('status', 'active')
            ->pluck('category');

        foreach ($faqCategories as $category) {
            $categories[] = [
                'value' => $category,
                'label' => $category,
                'type' => 'faqs'
            ];
        }

        return $categories;
    }

    /**
     * Log search query
     */
    private function logSearchQuery(string $query, string $type, ?string $category): void
    {
        try {
            $existingQuery = SearchQuery::where('query', $query)->first();
            
            if ($existingQuery) {
                $existingQuery->increment('count');
            } else {
                SearchQuery::create([
                    'query' => $query,
                    'type' => $type,
                    'category' => $category,
                    'count' => 1,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the search
            \Log::error('Failed to log search query: ' . $e->getMessage());
        }
    }
}