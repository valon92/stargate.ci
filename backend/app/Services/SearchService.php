<?php

namespace App\Services;

use App\Models\SearchIndex;
use App\Models\SearchQuery;
use App\Models\Tutorial;
use App\Models\CommunityPost;
use App\Models\User;
use App\Models\ContentCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchService
{
    /**
     * Perform advanced search with ranking and relevance
     */
    public function search(string $query, array $options = []): array
    {
        $type = $options['type'] ?? 'all';
        $filters = $options['filters'] ?? [];
        $limit = $options['limit'] ?? 20;
        $offset = $options['offset'] ?? 0;

        // Clean and prepare search query
        $cleanQuery = $this->cleanSearchQuery($query);
        $searchTerms = $this->extractSearchTerms($cleanQuery);

        $results = [];

        switch ($type) {
            case 'content':
                $results = $this->searchContent($searchTerms, $filters, $limit, $offset);
                break;
            case 'community':
                $results = $this->searchCommunity($searchTerms, $filters, $limit, $offset);
                break;
            case 'users':
                $results = $this->searchUsers($searchTerms, $filters, $limit, $offset);
                break;
            default:
                $results = $this->searchAll($searchTerms, $filters, $limit, $offset);
                break;
        }

        // Log search query for analytics
        $this->logSearchQuery($query, $type, count($results));

        return [
            'query' => $query,
            'type' => $type,
            'results' => $results,
            'total' => count($results),
            'suggestions' => $this->getSuggestions($query, $type),
            'filters' => $this->getAvailableFilters($type)
        ];
    }

    /**
     * Search content (tutorials, articles)
     */
    protected function searchContent(array $terms, array $filters, int $limit, int $offset): array
    {
        $query = Tutorial::query()
            ->select([
                'tutorials.*',
                DB::raw('
                    (
                        CASE WHEN title LIKE ? THEN 3 ELSE 0 END +
                        CASE WHEN description LIKE ? THEN 2 ELSE 0 END +
                        CASE WHEN overview LIKE ? THEN 1 ELSE 0 END
                    ) as relevance_score
                ')
            ])
            ->where('is_published', true);

        // Build search conditions
        $searchPattern = '%' . implode('%', $terms) . '%';
        $query->addBinding([$searchPattern, $searchPattern, $searchPattern], 'select');

        // Apply search where clause
        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where('title', 'like', '%' . $term . '%')
                  ->orWhere('description', 'like', '%' . $term . '%')
                  ->orWhere('overview', 'like', '%' . $term . '%')
                  ->orWhereJsonContains('learning_objectives', $term);
            }
        });

        // Apply filters
        if (isset($filters['category'])) {
            $query->where('content_id', $filters['category']);
        }

        if (isset($filters['difficulty'])) {
            $query->where('difficulty_level', $filters['difficulty']);
        }

        if (isset($filters['tags'])) {
            foreach ($filters['tags'] as $tag) {
                $query->whereJsonContains('learning_objectives', $tag);
            }
        }

        // Order by relevance and recency
        $results = $query
            ->orderByRaw('relevance_score DESC, updated_at DESC')
            ->limit($limit)
            ->offset($offset)
            ->with(['author', 'contentItem'])
            ->get();

        return $results->map(function ($tutorial) {
            return [
                'id' => $tutorial->id,
                'type' => 'content',
                'title' => $tutorial->title,
                'description' => $tutorial->description,
                'url' => "/learning/tutorials/{$tutorial->id}",
                'author' => $tutorial->author->name ?? 'Unknown',
                'category' => $tutorial->contentItem->name ?? 'Uncategorized',
                'tags' => $tutorial->learning_objectives ?? [],
                'difficulty' => $tutorial->difficulty_level,
                'duration' => $tutorial->estimated_duration,
                'updated_at' => $tutorial->updated_at,
                'relevance_score' => $tutorial->relevance_score ?? 0
            ];
        })->toArray();
    }

    /**
     * Search community posts
     */
    protected function searchCommunity(array $terms, array $filters, int $limit, int $offset): array
    {
        $query = CommunityPost::query()
            ->select([
                'community_posts.*',
                DB::raw('
                    (
                        CASE WHEN title LIKE ? THEN 4 ELSE 0 END +
                        CASE WHEN content LIKE ? THEN 2 ELSE 0 END +
                        CASE WHEN JSON_SEARCH(tags, "one", ?) IS NOT NULL THEN 3 ELSE 0 END
                    ) as relevance_score
                ')
            ])
            ->where('status', 'published');

        // Build search conditions
        $searchPattern = '%' . implode('%', $terms) . '%';
        $tagPattern = implode('|', $terms);
        $query->addBinding([$searchPattern, $searchPattern, $tagPattern], 'select');

        // Apply search where clause
        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where('title', 'like', '%' . $term . '%')
                  ->orWhere('content', 'like', '%' . $term . '%')
                  ->orWhereJsonContains('tags', $term);
            }
        });

        // Apply filters
        if (isset($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (isset($filters['solved']) && $filters['solved']) {
            $query->where('is_solved', true);
        }

        if (isset($filters['pinned']) && $filters['pinned']) {
            $query->where('pinned', true);
        }

        // Order by relevance and activity
        $results = $query
            ->orderByRaw('relevance_score DESC, updated_at DESC')
            ->limit($limit)
            ->offset($offset)
            ->with(['author', 'category'])
            ->get();

        return $results->map(function ($post) {
            return [
                'id' => $post->id,
                'type' => 'community',
                'title' => $post->title,
                'content' => Str::limit(strip_tags($post->content), 200),
                'url' => "/community/posts/{$post->id}",
                'author' => $post->author->name ?? 'Unknown',
                'category' => $post->category->name ?? 'General',
                'tags' => $post->tags ?? [],
                'views' => $post->view_count ?? 0,
                'likes' => $post->like_count ?? 0,
                'comments' => $post->comment_count ?? 0,
                'is_solved' => $post->is_solved ?? false,
                'is_pinned' => $post->pinned ?? false,
                'updated_at' => $post->updated_at,
                'relevance_score' => $post->relevance_score ?? 0
            ];
        })->toArray();
    }

    /**
     * Search users
     */
    protected function searchUsers(array $terms, array $filters, int $limit, int $offset): array
    {
        $query = User::query()
            ->select([
                'users.*',
                DB::raw('
                    (
                        CASE WHEN name LIKE ? THEN 3 ELSE 0 END +
                        CASE WHEN email LIKE ? THEN 2 ELSE 0 END
                    ) as relevance_score
                ')
            ]);

        // Build search conditions
        $searchPattern = '%' . implode('%', $terms) . '%';
        $query->addBinding([$searchPattern, $searchPattern], 'select');

        // Apply search where clause
        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('email', 'like', '%' . $term . '%');
            }
        });

        // Apply filters
        if (isset($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('slug', $filters['role']);
            });
        }

        // Order by relevance
        $results = $query
            ->orderByRaw('relevance_score DESC, created_at DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $results->map(function ($user) {
            return [
                'id' => $user->id,
                'type' => 'user',
                'name' => $user->name,
                'email' => $user->email,
                'url' => "/users/{$user->id}",
                'avatar' => $user->avatar ?? '/api/placeholder/40/40',
                'joined_at' => $user->created_at,
                'relevance_score' => $user->relevance_score ?? 0
            ];
        })->toArray();
    }

    /**
     * Search across all content types
     */
    protected function searchAll(array $terms, array $filters, int $limit, int $offset): array
    {
        $contentResults = $this->searchContent($terms, $filters, $limit / 3, 0);
        $communityResults = $this->searchCommunity($terms, $filters, $limit / 3, 0);
        $userResults = $this->searchUsers($terms, $filters, $limit / 3, 0);

        // Combine and sort by relevance
        $allResults = array_merge($contentResults, $communityResults, $userResults);
        
        // Sort by relevance score
        usort($allResults, function ($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });

        return array_slice($allResults, $offset, $limit);
    }

    /**
     * Get search suggestions based on query
     */
    public function getSuggestions(string $query, string $type = 'all'): array
    {
        $suggestions = [];

        // Get popular search terms
        $popularTerms = SearchQuery::select('query')
            ->where('query', 'like', $query . '%')
            ->groupBy('query')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->pluck('query')
            ->toArray();

        $suggestions = array_merge($suggestions, $popularTerms);

        // Get related terms based on content
        $relatedTerms = $this->getRelatedTerms($query, $type);
        $suggestions = array_merge($suggestions, $relatedTerms);

        // Remove duplicates and limit
        return array_unique(array_slice($suggestions, 0, 10));
    }

    /**
     * Get available filters for search type
     */
    protected function getAvailableFilters(string $type): array
    {
        $filters = [];

        switch ($type) {
            case 'content':
                $filters = [
                    'categories' => ContentCategory::select('id', 'name')->get()->toArray(),
                    'difficulties' => [
                        ['value' => 1, 'label' => 'Beginner'],
                        ['value' => 2, 'label' => 'Intermediate'],
                        ['value' => 3, 'label' => 'Advanced'],
                        ['value' => 4, 'label' => 'Expert'],
                        ['value' => 5, 'label' => 'Master']
                    ]
                ];
                break;
            case 'community':
                $filters = [
                    'categories' => DB::table('community_categories')
                        ->select('id', 'name')
                        ->where('is_active', true)
                        ->get()
                        ->toArray(),
                    'status' => [
                        ['value' => 'solved', 'label' => 'Solved'],
                        ['value' => 'pinned', 'label' => 'Pinned']
                    ]
                ];
                break;
            case 'users':
                $filters = [
                    'roles' => DB::table('user_roles')
                        ->select('slug as value', 'name as label')
                        ->get()
                        ->toArray()
                ];
                break;
        }

        return $filters;
    }

    /**
     * Clean and prepare search query
     */
    protected function cleanSearchQuery(string $query): string
    {
        // Remove special characters except spaces and hyphens
        $cleaned = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $query);
        
        // Normalize whitespace
        $cleaned = preg_replace('/\s+/', ' ', trim($cleaned));
        
        return $cleaned;
    }

    /**
     * Extract search terms from query
     */
    protected function extractSearchTerms(string $query): array
    {
        $terms = explode(' ', $query);
        
        // Filter out common stop words
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by'];
        
        $terms = array_filter($terms, function ($term) use ($stopWords) {
            return strlen($term) > 2 && !in_array(strtolower($term), $stopWords);
        });

        return array_values($terms);
    }

    /**
     * Get related terms based on content analysis
     */
    protected function getRelatedTerms(string $query, string $type): array
    {
        // This is a simplified version - in production you'd use more sophisticated NLP
        $relatedTerms = [];

        // Get tags from content that match the query
        if ($type === 'content' || $type === 'all') {
            $tags = Tutorial::where('title', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->whereNotNull('learning_objectives')
                ->pluck('learning_objectives')
                ->flatten()
                ->unique()
                ->take(5)
                ->toArray();

            $relatedTerms = array_merge($relatedTerms, $tags);
        }

        if ($type === 'community' || $type === 'all') {
            $tags = CommunityPost::where('title', 'like', '%' . $query . '%')
                ->whereNotNull('tags')
                ->pluck('tags')
                ->flatten()
                ->unique()
                ->take(5)
                ->toArray();

            $relatedTerms = array_merge($relatedTerms, $tags);
        }

        return array_filter($relatedTerms);
    }

    /**
     * Log search query for analytics
     */
    protected function logSearchQuery(string $query, string $type, int $resultsCount): void
    {
        try {
            SearchQuery::create([
                'user_id' => auth()->id(),
                'query' => $query,
                'search_type' => $type,
                'results_count' => $resultsCount,
                'filters_used' => [],
                'searched_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the search
            \Log::warning('Failed to log search query: ' . $e->getMessage());
        }
    }

    /**
     * Get popular searches
     */
    public function getPopularSearches(int $limit = 10): array
    {
        return SearchQuery::select('query')
            ->where('searched_at', '>=', now()->subDays(30))
            ->groupBy('query')
            ->orderByRaw('COUNT(*) DESC')
            ->limit($limit)
            ->pluck('query')
            ->toArray();
    }

    /**
     * Get recent searches for user
     */
    public function getUserRecentSearches(int $userId, int $limit = 10): array
    {
        return SearchQuery::where('user_id', $userId)
            ->orderBy('searched_at', 'desc')
            ->limit($limit)
            ->pluck('query')
            ->unique()
            ->values()
            ->toArray();
    }
}
