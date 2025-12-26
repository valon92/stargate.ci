<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Video;
use App\Models\FAQ;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\VideoComment;
use App\Models\ContactMessage;
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
            case 'articles':
                $results = $this->searchArticles($searchTerms, $filters, $limit, $offset);
                break;
            case 'videos':
                $results = $this->searchVideos($searchTerms, $filters, $limit, $offset);
                break;
            case 'faqs':
                $results = $this->searchFAQs($searchTerms, $filters, $limit, $offset);
                break;
            case 'users':
                $results = $this->searchUsers($searchTerms, $filters, $limit, $offset);
                break;
            case 'comments':
                $results = $this->searchComments($searchTerms, $filters, $limit, $offset);
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
     * Search articles
     */
    protected function searchArticles(array $terms, array $filters, int $limit, int $offset): array
    {
        $query = Article::query()
            ->select([
                'articles.*',
                DB::raw('
                    (
                        CASE WHEN title LIKE ? THEN 4 ELSE 0 END +
                        CASE WHEN excerpt LIKE ? THEN 2 ELSE 0 END +
                        CASE WHEN content LIKE ? THEN 1 ELSE 0 END
                    ) as relevance_score
                ')
            ])
            ->where('published', true);

        // Build search conditions
        $searchPattern = '%' . implode('%', $terms) . '%';
        $query->addBinding([$searchPattern, $searchPattern, $searchPattern], 'select');

        // Apply search where clause
        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where('title', 'like', '%' . $term . '%')
                  ->orWhere('excerpt', 'like', '%' . $term . '%')
                  ->orWhere('content', 'like', '%' . $term . '%');
            }
        });

        // Apply filters
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['author'])) {
            $query->where('author', $filters['author']);
        }

        if (isset($filters['date_from'])) {
            $query->where('published_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('published_at', '<=', $filters['date_to']);
        }

        // Order by relevance and recency
        $results = $query
            ->orderByRaw('relevance_score DESC, published_at DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $results->map(function ($article) {
            return [
                'id' => $article->id,
                'type' => 'article',
                'title' => $article->title,
                'excerpt' => $article->excerpt,
                'url' => "/articles/{$article->slug}",
                'author' => $article->author,
                'category' => $article->category,
                'read_time' => $article->read_time,
                'published_at' => $article->published_at,
                'relevance_score' => $article->relevance_score ?? 0
            ];
        })->toArray();
    }

    /**
     * Search videos
     */
    protected function searchVideos(array $terms, array $filters, int $limit, int $offset): array
    {
        $query = Video::query()
            ->select([
                'content_videos.*',
                DB::raw('
                    (
                        CASE WHEN title LIKE ? THEN 4 ELSE 0 END +
                        CASE WHEN description LIKE ? THEN 2 ELSE 0 END
                    ) as relevance_score
                ')
            ])
            ->where('is_active', true);

        // Build search conditions
        $searchPattern = '%' . implode('%', $terms) . '%';
        $query->addBinding([$searchPattern, $searchPattern], 'select');

        // Apply search where clause
        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where('title', 'like', '%' . $term . '%')
                  ->orWhere('description', 'like', '%' . $term . '%');
            }
        });

        // Apply filters
        if (isset($filters['content_type'])) {
            $query->where('content_type', $filters['content_type']);
        }

        // Order by relevance and activity
        $results = $query
            ->orderByRaw('relevance_score DESC, views_count DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $results->map(function ($video) {
            return [
                'id' => $video->content_id,
                'type' => 'video',
                'title' => $video->title,
                'description' => Str::limit($video->description, 200),
                'url' => "/videos/{$video->content_id}",
                'youtube_id' => $video->youtube_id,
                'content_type' => $video->content_type,
                'likes_count' => $video->likes_count,
                'comments_count' => $video->comments_count,
                'views_count' => $video->views_count,
                'relevance_score' => $video->relevance_score ?? 0
            ];
        })->toArray();
    }

    /**
     * Search FAQs
     */
    protected function searchFAQs(array $terms, array $filters, int $limit, int $offset): array
    {
        $query = FAQ::query()
            ->select([
                'f_a_q_s.*',
                DB::raw('
                    (
                        CASE WHEN question LIKE ? THEN 4 ELSE 0 END +
                        CASE WHEN answer LIKE ? THEN 2 ELSE 0 END
                    ) as relevance_score
                ')
            ])
            ->where('active', true);

        // Build search conditions
        $searchPattern = '%' . implode('%', $terms) . '%';
        $query->addBinding([$searchPattern, $searchPattern], 'select');

        // Apply search where clause
        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where('question', 'like', '%' . $term . '%')
                  ->orWhere('answer', 'like', '%' . $term . '%');
            }
        });

        // Order by relevance and order
        $results = $query
            ->orderByRaw('relevance_score DESC, `order` ASC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $results->map(function ($faq) {
            return [
                'id' => $faq->id,
                'type' => 'faq',
                'question' => $faq->question,
                'answer' => Str::limit($faq->answer, 300),
                'url' => "/faqs#{$faq->id}",
                'order' => $faq->order,
                'relevance_score' => $faq->relevance_score ?? 0
            ];
        })->toArray();
    }

    /**
     * Search comments
     */
    protected function searchComments(array $terms, array $filters, int $limit, int $offset): array
    {
        $query = VideoComment::query()
            ->select([
                'video_comments.*',
                DB::raw('
                    (
                        CASE WHEN comment_text LIKE ? THEN 3 ELSE 0 END +
                        CASE WHEN author_name LIKE ? THEN 2 ELSE 0 END
                    ) as relevance_score
                ')
            ])
            ->where('is_active', true);

        // Build search conditions
        $searchPattern = '%' . implode('%', $terms) . '%';
        $query->addBinding([$searchPattern, $searchPattern], 'select');

        // Apply search where clause
        $query->where(function ($q) use ($terms) {
            foreach ($terms as $term) {
                $q->where('comment_text', 'like', '%' . $term . '%')
                  ->orWhere('author_name', 'like', '%' . $term . '%');
            }
        });

        // Order by relevance and date
        $results = $query
            ->orderByRaw('relevance_score DESC, created_at DESC')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $results->map(function ($comment) {
            return [
                'id' => $comment->id,
                'type' => 'comment',
                'text' => Str::limit($comment->comment_text, 200),
                'author' => $comment->author_name,
                'video_id' => $comment->video_content_id,
                'url' => "/videos/{$comment->video_content_id}#comment-{$comment->id}",
                'likes_count' => $comment->likes_count ?? 0,
                'is_pinned' => $comment->is_pinned ?? false,
                'created_at' => $comment->created_at,
                'relevance_score' => $comment->relevance_score ?? 0
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
        $articleResults = $this->searchArticles($terms, $filters, $limit / 4, 0);
        $videoResults = $this->searchVideos($terms, $filters, $limit / 4, 0);
        $faqResults = $this->searchFAQs($terms, $filters, $limit / 4, 0);
        $userResults = $this->searchUsers($terms, $filters, $limit / 4, 0);

        // Combine and sort by relevance
        $allResults = array_merge($articleResults, $videoResults, $faqResults, $userResults);
        
        // Sort by relevance score
        usort($allResults, function ($a, $b) {
            return $b['relevance_score'] <=> $a['relevance_score'];
        });

        return array_slice($allResults, $offset, $limit);
    }

    /**
     * Get search suggestions based on query
     */
    public function getSuggestions(string $query): array
    {
        $suggestions = [];

        // Get suggestions from articles
        $articleTitles = Article::where('title', 'like', '%' . $query . '%')
            ->where('published', true)
            ->limit(3)
            ->pluck('title')
            ->toArray();

        // Get suggestions from videos
        $videoTitles = Video::where('title', 'like', '%' . $query . '%')
            ->where('is_active', true)
            ->limit(3)
            ->pluck('title')
            ->toArray();

        // Get suggestions from FAQs
        $faqQuestions = FAQ::where('question', 'like', '%' . $query . '%')
            ->where('active', true)
            ->limit(3)
            ->pluck('question')
            ->toArray();

        $suggestions = array_merge($articleTitles, $videoTitles, $faqQuestions);

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
            case 'articles':
                $filters = [
                    'categories' => Article::select('category')
                        ->distinct()
                        ->whereNotNull('category')
                        ->pluck('category')
                        ->map(function($cat) {
                            return ['value' => $cat, 'label' => ucfirst($cat)];
                        })
                        ->toArray(),
                    'authors' => Article::select('author')
                        ->distinct()
                        ->whereNotNull('author')
                        ->pluck('author')
                        ->map(function($author) {
                            return ['value' => $author, 'label' => $author];
                        })
                        ->toArray()
                ];
                break;
            case 'videos':
                $filters = [
                    'content_types' => Video::select('content_type')
                        ->distinct()
                        ->whereNotNull('content_type')
                        ->pluck('content_type')
                        ->map(function($type) {
                            return ['value' => $type, 'label' => ucfirst($type)];
                        })
                        ->toArray()
                ];
                break;
            case 'users':
                $filters = [
                    'roles' => [
                        ['value' => 'admin', 'label' => 'Admin'],
                        ['value' => 'user', 'label' => 'User'],
                        ['value' => 'moderator', 'label' => 'Moderator']
                    ]
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
            // Simple logging for now - can be enhanced with proper search analytics
            \Log::info('Search performed', [
                'query' => $query,
                'type' => $type,
                'results_count' => $resultsCount,
                'user_id' => auth()->id()
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
        // Return some popular search terms for now
        return [
            'stargate',
            'cristal intelligence',
            'AI',
            'machine learning',
            'artificial intelligence',
            'technology',
            'innovation',
            'research',
            'development',
            'future'
        ];
    }

    /**
     * Get trending searches
     */
    public function getTrendingSearches(int $limit = 10): array
    {
        // Return trending search terms
        return [
            'stargate project',
            'cristal AI',
            'openAI',
            'softbank',
            'ARM chips',
            'AI ethics',
            'transparency',
            'governance',
            'breakthrough',
            'summit'
        ];
    }

    /**
     * Get search analytics
     */
    public function getSearchAnalytics(): array
    {
        return [
            'total_searches' => 0,
            'popular_terms' => $this->getPopularSearches(5),
            'trending_terms' => $this->getTrendingSearches(5),
            'search_types' => [
                'articles' => 0,
                'videos' => 0,
                'faqs' => 0,
                'users' => 0
            ]
        ];
    }

    /**
     * Save search for user
     */
    public function saveSearch(int $userId, string $query, array $filters = [], ?string $name = null): array
    {
        // Simple implementation - can be enhanced with database storage
        return [
            'id' => uniqid(),
            'name' => $name ?: $query,
            'query' => $query,
            'filters' => $filters,
            'created_at' => now()
        ];
    }

    /**
     * Get user's saved searches
     */
    public function getUserSavedSearches(int $userId): array
    {
        // Return empty array for now - can be enhanced with database storage
        return [];
    }
}
