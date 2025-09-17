<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnalyticsEvent;
use App\Models\PerformanceMetric;
use App\Models\ErrorLog;
use App\Models\UserActivityLog;
use App\Models\SessionAnalytics;
use App\Models\ContentAnalytics;
use App\Models\SearchAnalytics;
use App\Models\User;

class AnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $demoUser = User::where('email', 'demo@stargate.ci')->first();

        // Create sample analytics events
        AnalyticsEvent::create([
            'event_name' => 'page_view',
            'event_type' => 'page_view',
            'user_id' => $adminUser->id,
            'session_id' => 'admin_session_123',
            'page_url' => '/dashboard',
            'referrer_url' => 'https://google.com',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'ip_address' => '127.0.0.1',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'os' => 'macOS',
            'country' => 'US',
            'city' => 'San Francisco',
            'properties' => json_encode([
                'page_title' => 'Dashboard',
                'load_time' => 1.2
            ]),
            'metadata' => json_encode([
                'utm_source' => 'google',
                'utm_medium' => 'organic'
            ]),
            'occurred_at' => now()->subHours(2)
        ]);

        AnalyticsEvent::create([
            'event_name' => 'button_click',
            'event_type' => 'click',
            'user_id' => $demoUser->id,
            'session_id' => 'demo_session_456',
            'page_url' => '/tutorials',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
            'ip_address' => '192.168.1.100',
            'device_type' => 'mobile',
            'browser' => 'Safari',
            'os' => 'iOS',
            'country' => 'US',
            'city' => 'Los Angeles',
            'properties' => json_encode([
                'button_text' => 'Start Tutorial',
                'tutorial_id' => 1
            ]),
            'occurred_at' => now()->subHours(1)
        ]);

        // Create sample performance metrics
        PerformanceMetric::create([
            'metric_name' => 'page_load_time',
            'metric_type' => 'performance',
            'page_url' => '/dashboard',
            'value' => 1.2,
            'unit' => 'seconds',
            'user_id' => $adminUser->id,
            'session_id' => 'admin_session_123',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'metadata' => json_encode([
                'dom_ready' => 0.8,
                'load_complete' => 1.2
            ]),
            'recorded_at' => now()->subHours(2)
        ]);

        PerformanceMetric::create([
            'metric_name' => 'api_response_time',
            'metric_type' => 'performance',
            'api_endpoint' => '/api/tutorials',
            'value' => 250.5,
            'unit' => 'ms',
            'user_id' => $demoUser->id,
            'session_id' => 'demo_session_456',
            'device_type' => 'mobile',
            'browser' => 'Safari',
            'recorded_at' => now()->subHours(1)
        ]);

        // Create sample error logs
        ErrorLog::create([
            'error_type' => 'javascript',
            'error_level' => 'error',
            'error_message' => 'Cannot read property \'length\' of undefined',
            'error_stack' => 'TypeError: Cannot read property \'length\' of undefined\n    at processData (app.js:45:12)\n    at handleSubmit (app.js:78:8)',
            'file' => 'app.js',
            'line' => 45,
            'user_id' => $demoUser->id,
            'session_id' => 'demo_session_456',
            'page_url' => '/tutorials/1',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
            'ip_address' => '192.168.1.100',
            'context' => json_encode([
                'form_data' => ['name' => 'test'],
                'browser_version' => '15.0'
            ]),
            'occurred_at' => now()->subMinutes(30)
        ]);

        // Create sample user activity logs
        UserActivityLog::create([
            'user_id' => $adminUser->id,
            'activity_type' => 'login',
            'activity_description' => 'User logged in successfully',
            'page_url' => '/login',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'properties' => json_encode([
                'login_method' => 'email',
                'two_factor_used' => true
            ]),
            'occurred_at' => now()->subHours(3)
        ]);

        UserActivityLog::create([
            'user_id' => $demoUser->id,
            'activity_type' => 'tutorial_start',
            'activity_description' => 'User started AI Fundamentals tutorial',
            'page_url' => '/tutorials/ai-fundamentals',
            'ip_address' => '192.168.1.100',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
            'properties' => json_encode([
                'tutorial_id' => 1,
                'tutorial_title' => 'AI Fundamentals'
            ]),
            'occurred_at' => now()->subHours(1)
        ]);

        // Create sample session analytics
        SessionAnalytics::create([
            'session_id' => 'admin_session_123',
            'user_id' => $adminUser->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'os' => 'macOS',
            'country' => 'US',
            'city' => 'San Francisco',
            'page_views' => 15,
            'events_count' => 25,
            'duration_seconds' => 1800, // 30 minutes
            'started_at' => now()->subHours(3),
            'ended_at' => now()->subHours(2.5),
            'is_active' => false,
            'metadata' => json_encode([
                'referrer' => 'https://google.com',
                'utm_source' => 'google'
            ])
        ]);

        SessionAnalytics::create([
            'session_id' => 'demo_session_456',
            'user_id' => $demoUser->id,
            'ip_address' => '192.168.1.100',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
            'device_type' => 'mobile',
            'browser' => 'Safari',
            'os' => 'iOS',
            'country' => 'US',
            'city' => 'Los Angeles',
            'page_views' => 8,
            'events_count' => 12,
            'duration_seconds' => 900, // 15 minutes
            'started_at' => now()->subHours(1),
            'is_active' => true,
            'metadata' => json_encode([
                'referrer' => 'direct',
                'app_version' => '1.0.0'
            ])
        ]);

        // Create sample content analytics
        ContentAnalytics::create([
            'content_type' => 'tutorial',
            'content_id' => 1,
            'user_id' => $demoUser->id,
            'action_type' => 'view',
            'page_url' => '/tutorials/ai-fundamentals',
            'properties' => json_encode([
                'tutorial_title' => 'AI Fundamentals',
                'category' => 'AI'
            ]),
            'occurred_at' => now()->subHours(1)
        ]);

        ContentAnalytics::create([
            'content_type' => 'tutorial',
            'content_id' => 1,
            'user_id' => $demoUser->id,
            'action_type' => 'complete',
            'page_url' => '/tutorials/ai-fundamentals',
            'duration_seconds' => 7200, // 2 hours
            'completion_percentage' => 100.00,
            'properties' => json_encode([
                'tutorial_title' => 'AI Fundamentals',
                'steps_completed' => 5
            ]),
            'occurred_at' => now()->subMinutes(30)
        ]);

        // Create sample search analytics
        SearchAnalytics::create([
            'user_id' => $demoUser->id,
            'query' => 'artificial intelligence',
            'search_type' => 'global',
            'results_count' => 15,
            'clicked_result_position' => 2,
            'clicked_result_type' => 'tutorial',
            'clicked_result_id' => 1,
            'result_clicked' => true,
            'time_to_click' => 3,
            'filters_used' => json_encode(['type' => 'tutorial', 'difficulty' => 'beginner']),
            'searched_at' => now()->subHours(1)
        ]);

        SearchAnalytics::create([
            'user_id' => $adminUser->id,
            'query' => 'stargate project',
            'search_type' => 'content',
            'results_count' => 8,
            'result_clicked' => false,
            'filters_used' => json_encode(['category' => 'news']),
            'searched_at' => now()->subHours(2)
        ]);
    }
}