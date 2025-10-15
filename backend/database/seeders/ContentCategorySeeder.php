<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContentCategory;

class ContentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Stargate Project',
                'slug' => 'stargate-project',
                'description' => 'Official information and updates about the Stargate AI project',
                'icon' => 'star',
                'color' => '#8B5CF6',
                'sort_order' => 1,
                'is_active' => true,
                'metadata' => json_encode([
                    'keywords' => ['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'AI', 'Infrastructure'],
                    'featured' => true
                ])
            ],
            [
                'name' => 'Cristal Intelligence',
                'slug' => 'cristal-intelligence',
                'description' => 'Understanding Cristal Intelligence and its applications',
                'icon' => 'crystal',
                'color' => '#06B6D4',
                'sort_order' => 2,
                'is_active' => true,
                'metadata' => json_encode([
                    'keywords' => ['Cristal Intelligence', 'AI', 'Future', 'Technology', 'Innovation'],
                    'featured' => true
                ])
            ],
            [
                'name' => 'AI Ethics & Governance',
                'slug' => 'ai-ethics-governance',
                'description' => 'Ethical considerations and governance frameworks for AI development',
                'icon' => 'shield',
                'color' => '#10B981',
                'sort_order' => 3,
                'is_active' => true,
                'metadata' => json_encode([
                    'keywords' => ['AI Ethics', 'Governance', 'Responsible AI', 'Ethics', 'Policy'],
                    'featured' => false
                ])
            ],
            [
                'name' => 'Technology Infrastructure',
                'slug' => 'technology-infrastructure',
                'description' => 'Technical infrastructure and implementation details',
                'icon' => 'server',
                'color' => '#F59E0B',
                'sort_order' => 4,
                'is_active' => true,
                'metadata' => json_encode([
                    'keywords' => ['Infrastructure', 'Technology', 'AI Systems', 'Cloud', 'Hardware'],
                    'featured' => false
                ])
            ],
            [
                'name' => 'Industry Applications',
                'slug' => 'industry-applications',
                'description' => 'Real-world applications and use cases across industries',
                'icon' => 'building',
                'color' => '#EF4444',
                'sort_order' => 5,
                'is_active' => true,
                'metadata' => json_encode([
                    'keywords' => ['Applications', 'Industry', 'Use Cases', 'AI in Practice', 'Business'],
                    'featured' => false
                ])
            ],
            [
                'name' => 'Research & Development',
                'slug' => 'research-development',
                'description' => 'Latest research findings and development updates',
                'icon' => 'flask',
                'color' => '#8B5CF6',
                'sort_order' => 6,
                'is_active' => true,
                'metadata' => json_encode([
                    'keywords' => ['Research', 'Development', 'Innovation', 'Breakthroughs', 'Science'],
                    'featured' => false
                ])
            ]
        ];

        foreach ($categories as $categoryData) {
            ContentCategory::create($categoryData);
        }
    }
}
