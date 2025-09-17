<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunityCategory;
use App\Models\CommunityPost;
use App\Models\CommunityComment;
use App\Models\CommunityTag;
use App\Models\CommunityBadge;
use App\Models\UserBadge;
use App\Models\UserReputation;
use App\Models\User;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $demoUser = User::where('email', 'demo@stargate.ci')->first();

        // Create community categories
        $generalCategory = CommunityCategory::firstOrCreate(
            ['slug' => 'general'],
            [
                'name' => 'General Discussion',
                'description' => 'General discussions about AI, technology, and the Stargate project',
                'icon' => 'chat',
                'color' => '#3B82F6',
                'sort_order' => 1,
                'is_active' => true,
                'rules' => json_encode([
                    'Be respectful to other members',
                    'Stay on topic',
                    'No spam or self-promotion'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['general', 'discussion', 'community']
                ])
            ]
        );

        $aiCategory = CommunityCategory::firstOrCreate(
            ['slug' => 'artificial-intelligence'],
            [
                'name' => 'Artificial Intelligence',
                'description' => 'Discussions about AI technologies, machine learning, and neural networks',
                'icon' => 'brain',
                'color' => '#10B981',
                'sort_order' => 2,
                'is_active' => true,
                'rules' => json_encode([
                    'Share knowledge and experiences',
                    'Ask questions and help others',
                    'Provide constructive feedback'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['AI', 'Machine Learning', 'Neural Networks', 'Deep Learning']
                ])
            ]
        );

        $stargateCategory = CommunityCategory::firstOrCreate(
            ['slug' => 'stargate-project'],
            [
                'name' => 'Stargate Project',
                'description' => 'Official discussions about the Stargate project and updates',
                'icon' => 'star',
                'color' => '#8B5CF6',
                'sort_order' => 3,
                'is_active' => true,
                'rules' => json_encode([
                    'Official information only',
                    'No speculation or rumors',
                    'Respect intellectual property'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'Official']
                ])
            ]
        );

        // Create community tags
        $aiTag = CommunityTag::firstOrCreate(
            ['slug' => 'artificial-intelligence'],
            [
                'name' => 'Artificial Intelligence',
                'color' => '#10B981',
                'description' => 'Topics related to AI and machine learning',
                'is_active' => true
            ]
        );

        $stargateTag = CommunityTag::firstOrCreate(
            ['slug' => 'stargate'],
            [
                'name' => 'Stargate',
                'color' => '#8B5CF6',
                'description' => 'Stargate project related discussions',
                'is_active' => true
            ]
        );

        $tutorialTag = CommunityTag::firstOrCreate(
            ['slug' => 'tutorial'],
            [
                'name' => 'Tutorial',
                'color' => '#F59E0B',
                'description' => 'Tutorials and how-to guides',
                'is_active' => true
            ]
        );

        // Create community badges
        $newcomerBadge = CommunityBadge::firstOrCreate(
            ['slug' => 'newcomer'],
            [
                'name' => 'Newcomer',
                'description' => 'Welcome to the community!',
                'icon' => 'star',
                'color' => '#6B7280',
                'type' => 'achievement',
                'criteria' => json_encode(['join_community']),
                'is_active' => true,
                'sort_order' => 1
            ]
        );

        $contributorBadge = CommunityBadge::firstOrCreate(
            ['slug' => 'contributor'],
            [
                'name' => 'Contributor',
                'description' => 'Active community contributor',
                'icon' => 'heart',
                'color' => '#EF4444',
                'type' => 'achievement',
                'criteria' => json_encode(['posts_count' => 10, 'comments_count' => 50]),
                'is_active' => true,
                'sort_order' => 2
            ]
        );

        $expertBadge = CommunityBadge::firstOrCreate(
            ['slug' => 'expert'],
            [
                'name' => 'Expert',
                'description' => 'AI and technology expert',
                'icon' => 'award',
                'color' => '#F59E0B',
                'type' => 'achievement',
                'criteria' => json_encode(['reputation_points' => 1000, 'posts_count' => 50]),
                'is_active' => true,
                'sort_order' => 3
            ]
        );

        // Create sample posts
        $welcomePost = CommunityPost::firstOrCreate(
            ['slug' => 'welcome-to-stargate-ci-community'],
            [
                'title' => 'Welcome to Stargate.ci Community!',
                'content' => 'Welcome to our community! This is a place where we discuss AI, technology, and the groundbreaking Stargate project. Feel free to introduce yourself and share your thoughts.',
                'user_id' => $adminUser->id,
                'category_id' => $generalCategory->id,
                'type' => 'announcement',
                'status' => 'published',
                'is_pinned' => true,
                'tags' => json_encode(['welcome', 'community', 'introduction']),
                'published_at' => now(),
                'last_activity_at' => now(),
                'metadata' => json_encode([
                    'featured' => true,
                    'priority' => 'high'
                ])
            ]
        );

        $aiPost = CommunityPost::firstOrCreate(
            ['slug' => 'getting-started-with-ai'],
            [
                'title' => 'Getting Started with AI: A Beginner\'s Guide',
                'content' => 'Are you new to AI? This post covers the basics of artificial intelligence, machine learning, and how to get started with your first AI project. Let\'s discuss!',
                'user_id' => $demoUser->id,
                'category_id' => $aiCategory->id,
                'type' => 'discussion',
                'status' => 'published',
                'tags' => json_encode(['AI', 'beginner', 'tutorial', 'machine-learning']),
                'published_at' => now()->subDays(2),
                'last_activity_at' => now()->subHours(5),
                'view_count' => 150,
                'like_count' => 25,
                'comment_count' => 8,
                'metadata' => json_encode([
                    'featured' => true,
                    'difficulty' => 'beginner'
                ])
            ]
        );

        $stargatePost = CommunityPost::firstOrCreate(
            ['slug' => 'stargate-project-update'],
            [
                'title' => 'Stargate Project: Latest Updates and Developments',
                'content' => 'The Stargate project continues to make progress with its $500 billion investment in AI infrastructure. Here are the latest updates from OpenAI, SoftBank, and Arm.',
                'user_id' => $adminUser->id,
                'category_id' => $stargateCategory->id,
                'type' => 'news',
                'status' => 'published',
                'tags' => json_encode(['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'official']),
                'published_at' => now()->subDays(1),
                'last_activity_at' => now()->subHours(2),
                'view_count' => 300,
                'like_count' => 45,
                'comment_count' => 12,
                'metadata' => json_encode([
                    'featured' => true,
                    'official' => true,
                    'priority' => 'high'
                ])
            ]
        );

        // Create sample comments
        CommunityComment::firstOrCreate(
            [
                'post_id' => $welcomePost->id,
                'user_id' => $demoUser->id,
                'content' => 'Thanks for the warm welcome! I\'m excited to be part of this community and learn more about AI and the Stargate project.'
            ],
            [
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'like_count' => 5
            ]
        );

        CommunityComment::firstOrCreate(
            [
                'post_id' => $aiPost->id,
                'user_id' => $adminUser->id,
                'content' => 'Great post! I\'d recommend starting with Python and libraries like TensorFlow or PyTorch. What specific area of AI interests you most?'
            ],
            [
                'status' => 'published',
                'published_at' => now()->subHours(10),
                'like_count' => 8
            ]
        );

        // Create user badges
        UserBadge::firstOrCreate(
            [
                'user_id' => $adminUser->id,
                'badge_id' => $expertBadge->id
            ],
            [
                'earned_at' => now()->subDays(30),
                'metadata' => json_encode(['earned_for' => 'admin_role'])
            ]
        );

        UserBadge::firstOrCreate(
            [
                'user_id' => $demoUser->id,
                'badge_id' => $newcomerBadge->id
            ],
            [
                'earned_at' => now()->subDays(7),
                'metadata' => json_encode(['earned_for' => 'community_join'])
            ]
        );

        // Create user reputation
        UserReputation::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'points' => 2500,
                'level' => 5,
                'title' => 'Community Expert',
                'achievements' => json_encode([
                    'first_post' => now()->subDays(60),
                    'helpful_contributor' => now()->subDays(45),
                    'community_leader' => now()->subDays(30)
                ]),
                'last_updated_at' => now()
            ]
        );

        UserReputation::firstOrCreate(
            ['user_id' => $demoUser->id],
            [
                'points' => 150,
                'level' => 2,
                'title' => 'Active Member',
                'achievements' => json_encode([
                    'first_post' => now()->subDays(7),
                    'first_comment' => now()->subDays(6)
                ]),
                'last_updated_at' => now()
            ]
        );
    }
}