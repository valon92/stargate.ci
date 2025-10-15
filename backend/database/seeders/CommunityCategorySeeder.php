<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunityCategory;

class CommunityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General Discussion',
                'slug' => 'general-discussion',
                'description' => 'General discussions about AI, technology, and the Stargate project',
                'icon' => 'chat',
                'color' => '#6B7280',
                'sort_order' => 1,
                'is_active' => true,
                'rules' => json_encode([
                    'Be respectful and constructive',
                    'Stay on topic',
                    'No spam or self-promotion',
                    'Use appropriate language'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['General', 'Discussion', 'AI', 'Technology'],
                    'moderation_level' => 'standard'
                ])
            ],
            [
                'name' => 'Stargate Project',
                'slug' => 'stargate-project',
                'description' => 'Official discussions about the Stargate project and updates',
                'icon' => 'star',
                'color' => '#8B5CF6',
                'sort_order' => 2,
                'is_active' => true,
                'rules' => json_encode([
                    'Official information only',
                    'No speculation or rumors',
                    'Respect intellectual property',
                    'Cite sources when possible'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'Official'],
                    'moderation_level' => 'strict'
                ])
            ],
            [
                'name' => 'Cristal Intelligence',
                'slug' => 'cristal-intelligence',
                'description' => 'Discussions about Cristal Intelligence and its applications',
                'icon' => 'crystal',
                'color' => '#06B6D4',
                'sort_order' => 3,
                'is_active' => true,
                'rules' => json_encode([
                    'Focus on Cristal Intelligence concepts',
                    'Share relevant research and insights',
                    'Be open to different perspectives',
                    'Maintain academic tone'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['Cristal Intelligence', 'AI Paradigm', 'Transparency', 'Ethics'],
                    'moderation_level' => 'standard'
                ])
            ],
            [
                'name' => 'AI Ethics & Governance',
                'slug' => 'ai-ethics-governance',
                'description' => 'Discussions about AI ethics, governance, and responsible development',
                'icon' => 'shield',
                'color' => '#10B981',
                'sort_order' => 4,
                'is_active' => true,
                'rules' => json_encode([
                    'Respectful debate encouraged',
                    'Cite ethical frameworks and principles',
                    'Consider multiple perspectives',
                    'Focus on constructive solutions'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['AI Ethics', 'Governance', 'Responsible AI', 'Policy'],
                    'moderation_level' => 'standard'
                ])
            ],
            [
                'name' => 'Technical Discussions',
                'slug' => 'technical-discussions',
                'description' => 'Technical discussions about AI implementation, infrastructure, and development',
                'icon' => 'code',
                'color' => '#F59E0B',
                'sort_order' => 5,
                'is_active' => true,
                'rules' => json_encode([
                    'Technical accuracy is important',
                    'Share code and technical details appropriately',
                    'Help others learn and grow',
                    'Use proper technical terminology'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['Technical', 'Implementation', 'Infrastructure', 'Development'],
                    'moderation_level' => 'standard'
                ])
            ],
            [
                'name' => 'Research & Development',
                'slug' => 'research-development',
                'description' => 'Share and discuss AI research, papers, and development projects',
                'icon' => 'flask',
                'color' => '#EF4444',
                'sort_order' => 6,
                'is_active' => true,
                'rules' => json_encode([
                    'Share relevant research papers',
                    'Cite sources properly',
                    'Discuss methodology and results',
                    'Encourage peer review and feedback'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['Research', 'Development', 'Papers', 'Methodology'],
                    'moderation_level' => 'standard'
                ])
            ],
            [
                'name' => 'Industry Applications',
                'slug' => 'industry-applications',
                'description' => 'Discuss real-world AI applications across different industries',
                'icon' => 'building',
                'color' => '#8B5CF6',
                'sort_order' => 7,
                'is_active' => true,
                'rules' => json_encode([
                    'Share real-world use cases',
                    'Discuss industry challenges and solutions',
                    'Respect confidentiality and NDAs',
                    'Focus on practical applications'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['Industry', 'Applications', 'Use Cases', 'Business'],
                    'moderation_level' => 'standard'
                ])
            ],
            [
                'name' => 'News & Updates',
                'slug' => 'news-updates',
                'description' => 'Latest news and updates about AI, technology, and related topics',
                'icon' => 'newspaper',
                'color' => '#06B6D4',
                'sort_order' => 8,
                'is_active' => true,
                'rules' => json_encode([
                    'Share recent and relevant news',
                    'Verify information before posting',
                    'Avoid duplicate posts',
                    'Include source links'
                ]),
                'metadata' => json_encode([
                    'keywords' => ['News', 'Updates', 'Current Events', 'Information'],
                    'moderation_level' => 'standard'
                ])
            ]
        ];

        foreach ($categories as $categoryData) {
            CommunityCategory::create($categoryData);
        }
    }
}
