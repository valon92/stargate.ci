<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SearchIndex;
use App\Models\SearchQuery;
use App\Models\SearchFilter;
use App\Models\SavedSearch;
use App\Models\SearchSuggestion;
use App\Models\SearchHistory;
use App\Models\User;

class SearchFilteringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $demoUser = User::where('email', 'demo@stargate.ci')->first();

        // Create search index entries
        SearchIndex::create([
            'searchable_type' => 'App\\Models\\ContentItem',
            'searchable_id' => 1,
            'title' => 'Introduction to Artificial Intelligence',
            'content' => 'Artificial Intelligence (AI) is revolutionizing the way we approach problem-solving and decision-making. This comprehensive guide covers the basics of AI concepts and applications.',
            'excerpt' => 'A comprehensive guide to understanding AI concepts and applications',
            'keywords' => json_encode(['AI', 'artificial intelligence', 'machine learning', 'technology', 'basics']),
            'tags' => json_encode(['AI', 'Introduction', 'Basics', 'Technology']),
            'category' => 'Artificial Intelligence',
            'author' => 'Admin User',
            'status' => 'active',
            'relevance_score' => 95,
            'view_count' => 150,
            'indexed_at' => now()
        ]);

        SearchIndex::create([
            'searchable_type' => 'App\\Models\\ContentItem',
            'searchable_id' => 2,
            'title' => 'Stargate Project: The Future of AI Computing',
            'content' => 'The Stargate project represents a $500 billion investment in next-generation AI computing infrastructure. Discover the groundbreaking project by OpenAI, SoftBank, and Arm.',
            'excerpt' => 'Discover the groundbreaking Stargate project by OpenAI, SoftBank, and Arm',
            'keywords' => json_encode(['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'AI infrastructure', 'investment']),
            'tags' => json_encode(['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'AI Infrastructure']),
            'category' => 'Stargate Project',
            'author' => 'Admin User',
            'status' => 'active',
            'relevance_score' => 100,
            'view_count' => 300,
            'indexed_at' => now()
        ]);

        SearchIndex::create([
            'searchable_type' => 'App\\Models\\CommunityPost',
            'searchable_id' => 1,
            'title' => 'Welcome to Stargate.ci Community!',
            'content' => 'Welcome to our community! This is a place where we discuss AI, technology, and the groundbreaking Stargate project. Feel free to introduce yourself and share your thoughts.',
            'excerpt' => 'Welcome to our community! This is a place where we discuss AI, technology, and the groundbreaking Stargate project.',
            'keywords' => json_encode(['welcome', 'community', 'AI', 'technology', 'Stargate', 'discussion']),
            'tags' => json_encode(['welcome', 'community', 'introduction']),
            'category' => 'General Discussion',
            'author' => 'Admin User',
            'status' => 'active',
            'relevance_score' => 80,
            'view_count' => 200,
            'indexed_at' => now()
        ]);

        // Create search filters
        SearchFilter::firstOrCreate(
            ['slug' => 'content-type'],
            [
            'name' => 'Content Type',
            'slug' => 'content-type',
            'type' => 'select',
            'description' => 'Filter by type of content',
            'options' => json_encode([
                ['value' => 'article', 'label' => 'Articles'],
                ['value' => 'tutorial', 'label' => 'Tutorials'],
                ['value' => 'video', 'label' => 'Videos'],
                ['value' => 'quiz', 'label' => 'Quizzes'],
                ['value' => 'post', 'label' => 'Community Posts']
            ]),
            'field_name' => 'type',
            'is_active' => true,
            'sort_order' => 1,
            'metadata' => json_encode(['multiple' => true])
            ]
        );

        SearchFilter::firstOrCreate(
            ['slug' => 'category'],
            [
            'name' => 'Category',
            'slug' => 'category',
            'type' => 'select',
            'description' => 'Filter by content category',
            'options' => json_encode([
                ['value' => 'artificial-intelligence', 'label' => 'Artificial Intelligence'],
                ['value' => 'cloud-computing', 'label' => 'Cloud Computing'],
                ['value' => 'stargate-project', 'label' => 'Stargate Project'],
                ['value' => 'general', 'label' => 'General Discussion']
            ]),
            'field_name' => 'category',
            'is_active' => true,
            'sort_order' => 2,
            'metadata' => json_encode(['multiple' => true])
            ]
        );

        SearchFilter::firstOrCreate(
            ['slug' => 'difficulty'],
            [
            'name' => 'Difficulty Level',
            'slug' => 'difficulty',
            'type' => 'range',
            'description' => 'Filter by difficulty level (1-5)',
            'options' => json_encode([
                'min' => 1,
                'max' => 5,
                'step' => 1,
                'labels' => ['Beginner', 'Intermediate', 'Advanced', 'Expert', 'Master']
            ]),
            'field_name' => 'difficulty_level',
            'is_active' => true,
            'sort_order' => 3,
            'metadata' => json_encode(['type' => 'range'])
            ]
        );

        SearchFilter::firstOrCreate(
            ['slug' => 'date-range'],
            [
            'name' => 'Date Range',
            'slug' => 'date-range',
            'type' => 'date',
            'description' => 'Filter by publication date',
            'options' => json_encode([
                'format' => 'Y-m-d',
                'ranges' => [
                    ['label' => 'Last 7 days', 'value' => '7d'],
                    ['label' => 'Last 30 days', 'value' => '30d'],
                    ['label' => 'Last 3 months', 'value' => '3m'],
                    ['label' => 'Last year', 'value' => '1y']
                ]
            ]),
            'field_name' => 'published_at',
            'is_active' => true,
            'sort_order' => 4,
            'metadata' => json_encode(['type' => 'date'])
            ]
        );

        // Create search suggestions
        SearchSuggestion::create([
            'suggestion' => 'artificial intelligence',
            'type' => 'popular',
            'usage_count' => 150,
            'click_count' => 45,
            'is_active' => true,
            'metadata' => json_encode(['category' => 'AI'])
        ]);

        SearchSuggestion::create([
            'suggestion' => 'stargate project',
            'type' => 'trending',
            'usage_count' => 200,
            'click_count' => 80,
            'is_active' => true,
            'metadata' => json_encode(['category' => 'Stargate'])
        ]);

        SearchSuggestion::create([
            'suggestion' => 'machine learning',
            'type' => 'related',
            'usage_count' => 120,
            'click_count' => 35,
            'is_active' => true,
            'metadata' => json_encode(['category' => 'AI', 'related_to' => 'artificial intelligence'])
        ]);

        SearchSuggestion::create([
            'suggestion' => 'tutorial',
            'type' => 'popular',
            'usage_count' => 100,
            'click_count' => 30,
            'is_active' => true,
            'metadata' => json_encode(['category' => 'Content'])
        ]);

        SearchSuggestion::create([
            'suggestion' => 'cloud computing',
            'type' => 'related',
            'usage_count' => 80,
            'click_count' => 25,
            'is_active' => true,
            'metadata' => json_encode(['category' => 'Technology'])
        ]);

        // Create search queries
        SearchQuery::create([
            'user_id' => $demoUser->id,
            'query' => 'artificial intelligence',
            'search_type' => 'global',
            'results_count' => 15,
            'clicked_result_position' => 2,
            'clicked_result_type' => 'ContentItem',
            'clicked_result_id' => 1,
            'result_clicked' => true,
            'time_to_click' => 3,
            'filters_used' => json_encode(['type' => 'article']),
            'suggestions_shown' => json_encode(['machine learning', 'AI basics']),
            'searched_at' => now()->subHours(2)
        ]);

        SearchQuery::create([
            'user_id' => $adminUser->id,
            'query' => 'stargate project',
            'search_type' => 'content',
            'results_count' => 8,
            'result_clicked' => false,
            'filters_used' => json_encode(['category' => 'stargate-project']),
            'suggestions_shown' => json_encode(['OpenAI', 'SoftBank', 'Arm']),
            'searched_at' => now()->subHours(1)
        ]);

        // Create saved searches
        SavedSearch::create([
            'user_id' => $demoUser->id,
            'name' => 'AI Tutorials',
            'description' => 'Search for AI-related tutorials',
            'query' => 'artificial intelligence tutorial',
            'search_type' => 'content',
            'filters' => json_encode(['type' => 'tutorial', 'category' => 'artificial-intelligence']),
            'sort_options' => json_encode(['sort_by' => 'relevance', 'order' => 'desc']),
            'is_public' => false,
            'usage_count' => 5,
            'last_used_at' => now()->subDays(1)
        ]);

        SavedSearch::create([
            'user_id' => $adminUser->id,
            'name' => 'Stargate Updates',
            'description' => 'Latest Stargate project updates',
            'query' => 'stargate project',
            'search_type' => 'global',
            'filters' => json_encode(['category' => 'stargate-project', 'type' => 'article']),
            'sort_options' => json_encode(['sort_by' => 'date', 'order' => 'desc']),
            'is_public' => true,
            'usage_count' => 12,
            'last_used_at' => now()->subHours(3)
        ]);

        // Create search history
        SearchHistory::create([
            'user_id' => $demoUser->id,
            'session_id' => 'demo_session_456',
            'query' => 'machine learning basics',
            'search_type' => 'content',
            'filters' => json_encode(['difficulty' => 'beginner']),
            'results_count' => 8,
            'searched_at' => now()->subHours(1)
        ]);

        SearchHistory::create([
            'user_id' => $adminUser->id,
            'session_id' => 'admin_session_123',
            'query' => 'community posts',
            'search_type' => 'community',
            'filters' => json_encode(['category' => 'general']),
            'results_count' => 12,
            'searched_at' => now()->subHours(2)
        ]);
    }
}