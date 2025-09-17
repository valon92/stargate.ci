<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CacheEntry;
use App\Models\CdnAsset;
use App\Models\IntegrationSetting;
use App\Models\PerformanceReport;
use App\Models\NotificationSetting;
use App\Models\UserPreference;
use App\Models\SystemLog;
use App\Models\BackupLog;
use App\Models\User;

class AdvancedFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $demoUser = User::where('email', 'demo@stargate.ci')->first();

        // Create cache entries
        CacheEntry::create([
            'key' => 'stargate_project_data',
            'value' => json_encode([
                'total_investment' => '$500 billion',
                'partners' => ['OpenAI', 'SoftBank', 'Arm'],
                'status' => 'active',
                'last_updated' => now()->toISOString()
            ]),
            'expires_at' => now()->addHours(24)->timestamp,
            'tags' => 'stargate,project,data',
            'hit_count' => 150,
            'miss_count' => 5,
            'last_accessed_at' => now()
        ]);

        CacheEntry::create([
            'key' => 'ai_tutorials_list',
            'value' => json_encode([
                'tutorials' => [
                    ['id' => 1, 'title' => 'AI Fundamentals', 'difficulty' => 'beginner'],
                    ['id' => 2, 'title' => 'Machine Learning Basics', 'difficulty' => 'intermediate'],
                    ['id' => 3, 'title' => 'Deep Learning Advanced', 'difficulty' => 'advanced']
                ],
                'total_count' => 3
            ]),
            'expires_at' => now()->addHours(12)->timestamp,
            'tags' => 'tutorials,ai,content',
            'hit_count' => 75,
            'miss_count' => 2,
            'last_accessed_at' => now()->subMinutes(30)
        ]);

        // Create CDN assets
        CdnAsset::create([
            'filename' => 'stargate-logo.png',
            'original_filename' => 'stargate-logo-original.png',
            'path' => '/assets/images/stargate-logo.png',
            'url' => 'https://cdn.stargate.ci/assets/images/stargate-logo.png',
            'mime_type' => 'image/png',
            'file_size' => 25600, // 25KB
            'hash' => 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6',
            'type' => 'image',
            'status' => 'ready',
            'metadata' => json_encode([
                'width' => 512,
                'height' => 512,
                'format' => 'PNG',
                'optimized' => true
            ]),
            'optimization_settings' => json_encode([
                'compression' => 'lossless',
                'webp_conversion' => true,
                'lazy_loading' => true
            ]),
            'download_count' => 1250,
            'last_accessed_at' => now()->subMinutes(15)
        ]);

        CdnAsset::create([
            'filename' => 'ai-tutorial-intro.mp4',
            'original_filename' => 'ai-tutorial-intro-original.mp4',
            'path' => '/assets/videos/ai-tutorial-intro.mp4',
            'url' => 'https://cdn.stargate.ci/assets/videos/ai-tutorial-intro.mp4',
            'mime_type' => 'video/mp4',
            'file_size' => 52428800, // 50MB
            'hash' => 'b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7',
            'type' => 'video',
            'status' => 'ready',
            'metadata' => json_encode([
                'duration' => 300, // 5 minutes
                'resolution' => '1920x1080',
                'bitrate' => '2000kbps',
                'codec' => 'H.264'
            ]),
            'optimization_settings' => json_encode([
                'adaptive_streaming' => true,
                'multiple_qualities' => ['720p', '1080p'],
                'thumbnail_generation' => true
            ]),
            'download_count' => 450,
            'last_accessed_at' => now()->subHours(2)
        ]);

        // Create integration settings
        IntegrationSetting::create([
            'integration_name' => 'Stripe Payment',
            'integration_type' => 'api',
            'configuration' => json_encode([
                'api_version' => '2023-10-16',
                'webhook_endpoint' => '/webhooks/stripe',
                'supported_currencies' => ['USD', 'EUR', 'GBP']
            ]),
            'credentials' => json_encode([
                'public_key' => 'pk_test_...',
                'secret_key' => 'sk_test_...' // This would be encrypted in real app
            ]),
            'is_active' => true,
            'is_verified' => true,
            'last_sync_at' => now()->subMinutes(30),
            'metadata' => json_encode([
                'environment' => 'sandbox',
                'webhook_events' => ['payment.succeeded', 'payment.failed']
            ])
        ]);

        IntegrationSetting::create([
            'integration_name' => 'OpenAI API',
            'integration_type' => 'api',
            'configuration' => json_encode([
                'model' => 'gpt-4',
                'max_tokens' => 4000,
                'temperature' => 0.7
            ]),
            'credentials' => json_encode([
                'api_key' => 'sk-...' // This would be encrypted in real app
            ]),
            'is_active' => true,
            'is_verified' => true,
            'last_sync_at' => now()->subHours(1),
            'metadata' => json_encode([
                'usage_limit' => 10000,
                'current_usage' => 2500
            ])
        ]);

        // Create performance reports
        PerformanceReport::create([
            'report_name' => 'Page Load Performance - January 2025',
            'report_type' => 'page_load',
            'report_date' => now()->toDateString(),
            'metrics' => json_encode([
                'average_load_time' => 1.2,
                'median_load_time' => 1.1,
                'p95_load_time' => 2.5,
                'p99_load_time' => 4.2,
                'total_page_views' => 15000
            ]),
            'recommendations' => json_encode([
                'Optimize images for faster loading',
                'Implement lazy loading for below-fold content',
                'Minify CSS and JavaScript files'
            ]),
            'overall_score' => 85.5,
            'status' => 'completed',
            'generated_by' => $adminUser->id,
            'metadata' => json_encode([
                'report_period' => 'monthly',
                'pages_analyzed' => 25
            ])
        ]);

        // Create notification settings
        NotificationSetting::create([
            'user_id' => $adminUser->id,
            'notification_type' => 'email',
            'event_type' => 'new_content',
            'is_enabled' => true,
            'preferences' => json_encode([
                'frequency' => 'immediate',
                'content_types' => ['tutorials', 'articles', 'announcements']
            ]),
            'schedule' => json_encode([
                'timezone' => 'UTC',
                'quiet_hours' => ['22:00', '08:00']
            ])
        ]);

        NotificationSetting::create([
            'user_id' => $demoUser->id,
            'notification_type' => 'push',
            'event_type' => 'tutorial_completion',
            'is_enabled' => true,
            'preferences' => json_encode([
                'frequency' => 'immediate',
                'include_achievements' => true
            ])
        ]);

        // Create user preferences
        UserPreference::create([
            'user_id' => $adminUser->id,
            'preference_key' => 'theme',
            'preference_value' => 'dark',
            'preference_type' => 'string',
            'metadata' => json_encode(['applied_at' => now()])
        ]);

        UserPreference::create([
            'user_id' => $adminUser->id,
            'preference_key' => 'language',
            'preference_value' => 'en',
            'preference_type' => 'string'
        ]);

        UserPreference::create([
            'user_id' => $demoUser->id,
            'preference_key' => 'theme',
            'preference_value' => 'light',
            'preference_type' => 'string'
        ]);

        UserPreference::create([
            'user_id' => $demoUser->id,
            'preference_key' => 'notifications',
            'preference_value' => json_encode([
                'email' => true,
                'push' => true,
                'sms' => false
            ]),
            'preference_type' => 'json'
        ]);

        // Create system logs
        SystemLog::create([
            'level' => 'info',
            'channel' => 'application',
            'message' => 'User logged in successfully',
            'context' => json_encode([
                'user_id' => $adminUser->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'
            ]),
            'user_id' => $adminUser->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'logged_at' => now()->subHours(2)
        ]);

        SystemLog::create([
            'level' => 'warning',
            'channel' => 'api',
            'message' => 'API rate limit approaching',
            'context' => json_encode([
                'endpoint' => '/api/tutorials',
                'current_usage' => 950,
                'limit' => 1000,
                'reset_time' => now()->addHours(1)->toISOString()
            ]),
            'logged_at' => now()->subMinutes(30)
        ]);

        // Create backup logs
        BackupLog::create([
            'backup_name' => 'daily_database_backup',
            'backup_type' => 'database',
            'file_path' => '/backups/database_2025_01_17.sql',
            'file_size' => 10485760, // 10MB
            'status' => 'completed',
            'started_at' => now()->subHours(6),
            'completed_at' => now()->subHours(6)->addMinutes(5),
            'metadata' => json_encode([
                'tables_backed_up' => 25,
                'compression_ratio' => 0.3
            ])
        ]);

        BackupLog::create([
            'backup_name' => 'weekly_files_backup',
            'backup_type' => 'files',
            'file_path' => '/backups/files_2025_week_03.tar.gz',
            'file_size' => 52428800, // 50MB
            'status' => 'in_progress',
            'started_at' => now()->subMinutes(15),
            'metadata' => json_encode([
                'files_count' => 1250,
                'directories_count' => 45
            ])
        ]);
    }
}