<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnalyticsEvent;
use App\Models\User;

class AnalyticsEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $events = [
            'page_view',
            'article_read',
            'faq_viewed',
            'search_performed',
            'tutorial_started',
            'tutorial_completed',
            'community_post_created',
            'comment_added',
            'file_uploaded',
            'user_registered',
            'user_logged_in',
            'newsletter_subscribed'
        ];

        $pages = [
            '/',
            '/about',
            '/services',
            '/insights',
            '/faq',
            '/contact',
            '/search',
            '/community',
            '/dashboard',
            '/articles',
            '/tutorials'
        ];

        // Generate analytics events for the last 30 days
        for ($i = 0; $i < 1000; $i++) {
            $user = $users->random();
            $event = $events[array_rand($events)];
            $page = $pages[array_rand($pages)];
            $createdAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $eventData = [
                'user_id' => $user->id,
                'event_type' => $event,
                'event_name' => $this->getEventName($event),
                'page_url' => $page,
                'referrer' => $this->getRandomReferrer(),
                'user_agent' => $this->getRandomUserAgent(),
                'ip_address' => $this->getRandomIP(),
                'session_id' => 'session_' . uniqid(),
                'properties' => json_encode($this->getEventProperties($event)),
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ];

            AnalyticsEvent::create($eventData);
        }
    }

    private function getEventName(string $eventType): string
    {
        return match ($eventType) {
            'page_view' => 'Page Viewed',
            'article_read' => 'Article Read',
            'faq_viewed' => 'FAQ Viewed',
            'search_performed' => 'Search Performed',
            'tutorial_started' => 'Tutorial Started',
            'tutorial_completed' => 'Tutorial Completed',
            'community_post_created' => 'Community Post Created',
            'comment_added' => 'Comment Added',
            'file_uploaded' => 'File Uploaded',
            'user_registered' => 'User Registered',
            'user_logged_in' => 'User Logged In',
            'newsletter_subscribed' => 'Newsletter Subscribed',
            default => 'Unknown Event'
        };
    }

    private function getRandomReferrer(): ?string
    {
        $referrers = [
            null,
            'https://google.com',
            'https://bing.com',
            'https://duckduckgo.com',
            'https://youtube.com',
            'https://twitter.com',
            'https://linkedin.com',
            'https://reddit.com',
            'https://github.com',
            'https://stackoverflow.com'
        ];

        return $referrers[array_rand($referrers)];
    }

    private function getRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15'
        ];

        return $userAgents[array_rand($userAgents)];
    }

    private function getRandomIP(): string
    {
        return '192.168.' . rand(1, 255) . '.' . rand(1, 255);
    }

    private function getEventProperties(string $eventType): array
    {
        return match ($eventType) {
            'page_view' => [
                'page_title' => 'Stargate.ci - ' . ucfirst(str_replace('/', '', $this->getRandomPage())),
                'load_time' => rand(500, 3000),
                'viewport_width' => rand(320, 1920),
                'viewport_height' => rand(568, 1080)
            ],
            'article_read' => [
                'article_id' => rand(1, 6),
                'article_title' => 'Sample Article Title',
                'read_time' => rand(30, 300),
                'completion_rate' => rand(50, 100)
            ],
            'faq_viewed' => [
                'faq_id' => rand(1, 15),
                'faq_category' => ['Stargate Project', 'Cristal Intelligence', 'AI Ethics', 'Infrastructure', 'General'][array_rand([0, 1, 2, 3, 4])],
                'search_query' => 'sample search query'
            ],
            'search_performed' => [
                'query' => ['Stargate', 'AI', 'Cristal Intelligence', 'Ethics', 'OpenAI'][array_rand([0, 1, 2, 3, 4])],
                'results_count' => rand(0, 50),
                'search_type' => ['all', 'articles', 'faqs', 'tutorials', 'community'][array_rand([0, 1, 2, 3, 4])]
            ],
            'tutorial_started' => [
                'tutorial_id' => rand(1, 3),
                'tutorial_title' => 'Sample Tutorial',
                'difficulty_level' => rand(1, 3)
            ],
            'tutorial_completed' => [
                'tutorial_id' => rand(1, 3),
                'completion_time' => rand(300, 1800),
                'score' => rand(70, 100)
            ],
            'community_post_created' => [
                'post_id' => rand(1, 10),
                'category' => ['General Discussion', 'Stargate Project', 'Cristal Intelligence'][array_rand([0, 1, 2])],
                'post_type' => ['discussion', 'question', 'announcement'][array_rand([0, 1, 2])]
            ],
            'comment_added' => [
                'comment_id' => rand(1, 50),
                'post_id' => rand(1, 10),
                'comment_length' => rand(10, 500)
            ],
            'file_uploaded' => [
                'file_id' => rand(1, 20),
                'file_type' => ['image', 'document', 'video'][array_rand([0, 1, 2])],
                'file_size' => rand(1024, 10485760) // 1KB to 10MB
            ],
            'user_registered' => [
                'registration_source' => ['direct', 'google', 'social', 'referral'][array_rand([0, 1, 2, 3])],
                'user_type' => ['user', 'expert', 'admin'][array_rand([0, 1, 2])]
            ],
            'user_logged_in' => [
                'login_method' => ['email', 'social', 'sso'][array_rand([0, 1, 2])],
                'session_duration' => rand(300, 7200) // 5 minutes to 2 hours
            ],
            'newsletter_subscribed' => [
                'newsletter_type' => ['weekly', 'monthly', 'updates'][array_rand([0, 1, 2])],
                'subscription_source' => ['homepage', 'footer', 'popup'][array_rand([0, 1, 2])]
            ],
            default => []
        };
    }

    private function getRandomPage(): string
    {
        $pages = ['/', '/about', '/services', '/insights', '/faq', '/contact', '/search', '/community'];
        return $pages[array_rand($pages)];
    }
}
