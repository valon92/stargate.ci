<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tutorial;
use App\Models\ContentItem;
use App\Models\ContentCategory;
use App\Models\User;

class TutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stargateCategory = ContentCategory::where('slug', 'stargate-project')->first();
        $cristalCategory = ContentCategory::where('slug', 'cristal-intelligence')->first();
        $ethicsCategory = ContentCategory::where('slug', 'ai-ethics-governance')->first();
        
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $expertUser = User::where('email', 'michael@example.com')->first();

        // Create content items first
        $contentItems = [
            [
                'title' => 'Introduction to the Stargate Project',
                'slug' => 'introduction-stargate-project',
                'excerpt' => 'Learn the fundamentals of the Stargate project and its significance in AI development.',
                'content' => $this->getStargateIntroContent(),
                'type' => 'tutorial',
                'category_id' => $stargateCategory->id,
                'author_id' => $adminUser->id,
                'featured_image' => '/images/tutorials/stargate-intro.jpg',
                'read_time' => 15,
                'difficulty_level' => 1,
                'learning_objectives' => json_encode([
                    'Understand the Stargate project overview',
                    'Learn about key partners and their roles',
                    'Grasp the project timeline and milestones'
                ]),
                'tags' => json_encode(['Stargate', 'Introduction', 'Basics', 'Overview']),
                'is_published' => true,
                'published_at' => now()->subDays(5)
            ],
            [
                'title' => 'Understanding Cristal Intelligence Principles',
                'slug' => 'understanding-cristal-intelligence-principles',
                'excerpt' => 'Deep dive into the core principles and concepts of Cristal Intelligence.',
                'content' => $this->getCristalPrinciplesContent(),
                'type' => 'tutorial',
                'category_id' => $cristalCategory->id,
                'author_id' => $expertUser->id,
                'featured_image' => '/images/tutorials/cristal-principles.jpg',
                'read_time' => 20,
                'difficulty_level' => 2,
                'learning_objectives' => json_encode([
                    'Master the four core principles of Cristal Intelligence',
                    'Understand the differences from traditional AI',
                    'Apply principles to real-world scenarios'
                ]),
                'tags' => json_encode(['Cristal Intelligence', 'Principles', 'Advanced', 'Concepts']),
                'is_published' => true,
                'published_at' => now()->subDays(3)
            ],
            [
                'title' => 'AI Ethics Framework Implementation',
                'slug' => 'ai-ethics-framework-implementation',
                'excerpt' => 'Learn how to implement ethical frameworks in AI development projects.',
                'content' => $this->getEthicsFrameworkContent(),
                'type' => 'tutorial',
                'category_id' => $ethicsCategory->id,
                'author_id' => $expertUser->id,
                'featured_image' => '/images/tutorials/ethics-framework.jpg',
                'read_time' => 25,
                'difficulty_level' => 3,
                'learning_objectives' => json_encode([
                    'Design ethical frameworks for AI projects',
                    'Implement governance structures',
                    'Conduct ethical impact assessments'
                ]),
                'tags' => json_encode(['AI Ethics', 'Framework', 'Implementation', 'Governance']),
                'is_published' => true,
                'published_at' => now()->subDays(1)
            ]
        ];

        foreach ($contentItems as $contentData) {
            $contentItem = ContentItem::create($contentData);
            
            // Create corresponding tutorial
            Tutorial::create([
                'title' => $contentItem->title,
                'slug' => $contentItem->slug,
                'description' => $contentItem->excerpt,
                'overview' => 'This tutorial provides comprehensive coverage of the topic with practical examples and real-world applications.',
                'content_id' => $contentItem->id,
                'author_id' => $contentItem->author_id,
                'estimated_duration' => $contentItem->read_time,
                'difficulty_level' => $contentItem->difficulty_level,
                'learning_objectives' => $contentItem->learning_objectives,
                'prerequisites' => json_encode($this->getPrerequisites($contentItem->difficulty_level)),
                'resources' => json_encode($this->getResources($contentItem->slug)),
                'thumbnail' => $contentItem->featured_image,
                'steps' => json_encode($this->getTutorialSteps($contentItem->slug)),
                'is_interactive' => true,
                'has_quiz' => true,
                'has_certificate' => true,
                'is_published' => true,
                'published_at' => $contentItem->published_at
            ]);
        }
    }

    private function getStargateIntroContent(): string
    {
        return '
        <h2>Welcome to the Stargate Project Tutorial</h2>
        <p>This tutorial will introduce you to the Stargate project, one of the most ambitious AI infrastructure initiatives ever undertaken.</p>
        
        <h3>What You\'ll Learn</h3>
        <ul>
            <li>Project overview and significance</li>
            <li>Key partners and their contributions</li>
            <li>Timeline and development phases</li>
            <li>Expected impact and outcomes</li>
        </ul>
        
        <h3>Prerequisites</h3>
        <p>Basic understanding of artificial intelligence concepts is helpful but not required.</p>
        ';
    }

    private function getCristalPrinciplesContent(): string
    {
        return '
        <h2>Cristal Intelligence Principles</h2>
        <p>This tutorial explores the revolutionary principles that define Cristal Intelligence.</p>
        
        <h3>Core Principles</h3>
        <ol>
            <li><strong>Transparency:</strong> Clear and explainable decision-making</li>
            <li><strong>Precision:</strong> Crystal-clear accuracy in operations</li>
            <li><strong>Harmony:</strong> Seamless integration with human values</li>
            <li><strong>Evolution:</strong> Continuous improvement and adaptation</li>
        </ol>
        
        <h3>Practical Applications</h3>
        <p>Learn how these principles apply to real-world AI systems and development projects.</p>
        ';
    }

    private function getEthicsFrameworkContent(): string
    {
        return '
        <h2>AI Ethics Framework Implementation</h2>
        <p>This advanced tutorial covers the implementation of ethical frameworks in AI development.</p>
        
        <h3>Framework Components</h3>
        <ul>
            <li>Ethical principles and guidelines</li>
            <li>Governance structures</li>
            <li>Impact assessment methodologies</li>
            <li>Monitoring and evaluation systems</li>
        </ul>
        
        <h3>Implementation Strategies</h3>
        <p>Step-by-step guide to implementing ethical frameworks in your AI projects.</p>
        ';
    }

    private function getPrerequisites(int $difficultyLevel): array
    {
        return match ($difficultyLevel) {
            1 => [],
            2 => ['Basic understanding of AI concepts', 'Familiarity with machine learning principles'],
            3 => ['Advanced AI knowledge', 'Experience with AI development', 'Understanding of ethical frameworks'],
            default => []
        };
    }

    private function getResources(string $slug): array
    {
        return match ($slug) {
            'introduction-stargate-project' => [
                'Stargate Project Official Documentation',
                'OpenAI Research Papers',
                'SoftBank AI Investment Reports'
            ],
            'understanding-cristal-intelligence-principles' => [
                'Cristal Intelligence White Paper',
                'AI Ethics Research Papers',
                'Transparency in AI Systems'
            ],
            'ai-ethics-framework-implementation' => [
                'AI Ethics Guidelines',
                'Governance Framework Templates',
                'Impact Assessment Tools'
            ],
            default => []
        };
    }

    private function getTutorialSteps(string $slug): array
    {
        return match ($slug) {
            'introduction-stargate-project' => [
                [
                    'title' => 'Project Overview',
                    'content' => 'Introduction to the Stargate project and its significance',
                    'duration' => 5,
                    'type' => 'reading'
                ],
                [
                    'title' => 'Key Partners',
                    'content' => 'Understanding the roles of OpenAI, SoftBank, and Arm',
                    'duration' => 5,
                    'type' => 'reading'
                ],
                [
                    'title' => 'Timeline and Phases',
                    'content' => 'Project development timeline and key milestones',
                    'duration' => 5,
                    'type' => 'interactive'
                ]
            ],
            'understanding-cristal-intelligence-principles' => [
                [
                    'title' => 'Introduction to Cristal Intelligence',
                    'content' => 'Overview of Cristal Intelligence concepts',
                    'duration' => 7,
                    'type' => 'reading'
                ],
                [
                    'title' => 'Core Principles Deep Dive',
                    'content' => 'Detailed exploration of the four core principles',
                    'duration' => 8,
                    'type' => 'interactive'
                ],
                [
                    'title' => 'Practical Applications',
                    'content' => 'Real-world examples and use cases',
                    'duration' => 5,
                    'type' => 'case_study'
                ]
            ],
            'ai-ethics-framework-implementation' => [
                [
                    'title' => 'Ethical Principles Review',
                    'content' => 'Review of key ethical principles for AI',
                    'duration' => 8,
                    'type' => 'reading'
                ],
                [
                    'title' => 'Framework Design',
                    'content' => 'Designing ethical frameworks for AI projects',
                    'duration' => 10,
                    'type' => 'interactive'
                ],
                [
                    'title' => 'Implementation Planning',
                    'content' => 'Planning and implementing ethical frameworks',
                    'duration' => 7,
                    'type' => 'workshop'
                ]
            ],
            default => []
        };
    }
}
