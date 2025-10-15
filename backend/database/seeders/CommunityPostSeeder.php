<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunityPost;
use App\Models\CommunityCategory;
use App\Models\User;

class CommunityPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stargateCategory = CommunityCategory::where('slug', 'stargate-project')->first();
        $cristalCategory = CommunityCategory::where('slug', 'cristal-intelligence')->first();
        $ethicsCategory = CommunityCategory::where('slug', 'ai-ethics-governance')->first();
        $technicalCategory = CommunityCategory::where('slug', 'technical-discussions')->first();
        $generalCategory = CommunityCategory::where('slug', 'general-discussion')->first();
        
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $expertUser = User::where('email', 'michael@example.com')->first();
        $journalistUser = User::where('email', 'sarah@example.com')->first();
        $johnUser = User::where('email', 'john@example.com')->first();
        $janeUser = User::where('email', 'jane@example.com')->first();

        $posts = [
            [
                'title' => 'Stargate Project: Latest Updates and Developments',
                'slug' => 'stargate-project-update',
                'content' => 'The Stargate project continues to make progress with its $500 billion investment in AI infrastructure. Here are the latest updates from OpenAI, SoftBank, and Arm.',
                'category_id' => $stargateCategory->id,
                'user_id' => $adminUser->id,
                'type' => 'news',
                'status' => 'published',
                'tags' => json_encode(['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'official']),
                'metadata' => json_encode([
                    'featured' => true,
                    'official' => true,
                    'priority' => 'high'
                ]),
                'published_at' => now()->subDays(2)
            ],
            [
                'title' => 'Understanding Cristal Intelligence: A New Paradigm',
                'slug' => 'understanding-cristal-intelligence-paradigm',
                'content' => 'Cristal Intelligence represents a revolutionary approach to AI that prioritizes transparency and ethical alignment. Let\'s explore what this means for the future of artificial intelligence.',
                'category_id' => $cristalCategory->id,
                'user_id' => $expertUser->id,
                'type' => 'discussion',
                'status' => 'published',
                'tags' => json_encode(['Cristal Intelligence', 'Paradigm', 'Transparency', 'Ethics']),
                'metadata' => json_encode([
                    'featured' => true,
                    'academic' => true
                ]),
                'published_at' => now()->subDays(1)
            ],
            [
                'title' => 'Ethical Considerations in Large-Scale AI Development',
                'slug' => 'ethical-considerations-large-scale-ai',
                'content' => 'As AI systems become more powerful and widespread, ethical considerations become increasingly important. How can we ensure that large-scale AI projects like Stargate benefit humanity?',
                'category_id' => $ethicsCategory->id,
                'user_id' => $expertUser->id,
                'type' => 'discussion',
                'status' => 'published',
                'tags' => json_encode(['Ethics', 'Large-Scale AI', 'Humanity', 'Governance']),
                'metadata' => json_encode([
                    'featured' => false,
                    'academic' => true
                ]),
                'published_at' => now()->subDays(3)
            ],
            [
                'title' => 'Technical Infrastructure Requirements for Stargate',
                'slug' => 'technical-infrastructure-stargate-requirements',
                'content' => 'The Stargate project requires unprecedented technical infrastructure. Let\'s discuss the hardware, software, and networking requirements needed to support such a massive AI system.',
                'category_id' => $technicalCategory->id,
                'author_id' => $janeUser->id,
                'type' => 'technical',
                'status' => 'published',
                'tags' => json_encode(['Infrastructure', 'Hardware', 'Software', 'Networking']),
                'metadata' => json_encode([
                    'featured' => false,
                    'technical' => true
                ]),
                'published_at' => now()->subDays(4)
            ],
            [
                'title' => 'What Does the Future Hold for AI Development?',
                'slug' => 'future-ai-development-predictions',
                'content' => 'With projects like Stargate pushing the boundaries of what\'s possible, what do you think the future holds for AI development? Share your thoughts and predictions.',
                'category_id' => $generalCategory->id,
                'author_id' => $johnUser->id,
                'type' => 'discussion',
                'status' => 'published',
                'tags' => json_encode(['Future', 'AI Development', 'Predictions', 'Discussion']),
                'metadata' => json_encode([
                    'featured' => false,
                    'community' => true
                ]),
                'published_at' => now()->subDays(5)
            ]
        ];

        foreach ($posts as $postData) {
            CommunityPost::create($postData);
        }
    }
}