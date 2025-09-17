<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContentCategory;
use App\Models\ContentItem;
use App\Models\Tutorial;
use App\Models\Video;
use App\Models\Quiz;
use App\Models\User;

class ContentManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user
        $adminUser = User::where('email', 'admin@stargate.ci')->first();

        // Create content categories
        $aiCategory = ContentCategory::firstOrCreate(
            ['slug' => 'artificial-intelligence'],
            [
                'name' => 'Artificial Intelligence',
                'description' => 'Learn about AI technologies, machine learning, and neural networks',
                'icon' => 'brain',
                'color' => '#3B82F6',
                'sort_order' => 1,
                'is_active' => true,
                'metadata' => [
                    'keywords' => ['AI', 'Machine Learning', 'Neural Networks', 'Deep Learning']
                ]
            ]
        );

        $cloudCategory = ContentCategory::firstOrCreate(
            ['slug' => 'cloud-computing'],
            [
                'name' => 'Cloud Computing',
                'description' => 'Explore cloud technologies, infrastructure, and services',
                'icon' => 'cloud',
                'color' => '#10B981',
                'sort_order' => 2,
                'is_active' => true,
                'metadata' => [
                    'keywords' => ['Cloud', 'Infrastructure', 'AWS', 'Azure', 'GCP']
                ]
            ]
        );

        $stargateCategory = ContentCategory::firstOrCreate(
            ['slug' => 'stargate-project'],
            [
                'name' => 'Stargate Project',
                'description' => 'Official information about the Stargate AI project',
                'icon' => 'star',
                'color' => '#8B5CF6',
                'sort_order' => 3,
                'is_active' => true,
                'metadata' => [
                    'keywords' => ['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'AI Infrastructure']
                ]
            ]
        );

        // Create sample content items
        $aiArticle = ContentItem::firstOrCreate(
            ['slug' => 'introduction-to-ai'],
            [
                'title' => 'Introduction to Artificial Intelligence',
                'excerpt' => 'A comprehensive guide to understanding AI concepts and applications',
                'content' => 'Artificial Intelligence (AI) is revolutionizing the way we approach problem-solving and decision-making...',
                'type' => 'article',
                'category_id' => $aiCategory->id,
                'author_id' => $adminUser->id,
                'featured_image' => '/images/ai-intro.jpg',
                'read_time' => 10,
                'difficulty_level' => 1,
                'learning_objectives' => [
                    'Understand basic AI concepts',
                    'Learn about AI applications',
                    'Explore AI technologies'
                ],
                'tags' => ['AI', 'Introduction', 'Basics', 'Technology'],
                'is_published' => true,
                'published_at' => now(),
                'metadata' => [
                    'featured' => true,
                    'priority' => 'high'
                ]
            ]
        );

        $stargateArticle = ContentItem::firstOrCreate(
            ['slug' => 'stargate-project-overview'],
            [
                'title' => 'Stargate Project: The Future of AI Computing',
                'excerpt' => 'Discover the groundbreaking Stargate project by OpenAI, SoftBank, and Arm',
                'content' => 'The Stargate project represents a $500 billion investment in next-generation AI computing infrastructure...',
                'type' => 'article',
                'category_id' => $stargateCategory->id,
                'author_id' => $adminUser->id,
                'featured_image' => '/images/stargate-overview.jpg',
                'read_time' => 15,
                'difficulty_level' => 2,
                'learning_objectives' => [
                    'Understand the Stargate project scope',
                    'Learn about the investment and timeline',
                    'Explore the technological benefits'
                ],
                'tags' => ['Stargate', 'OpenAI', 'SoftBank', 'Arm', 'AI Infrastructure'],
                'is_published' => true,
                'published_at' => now(),
                'metadata' => [
                    'featured' => true,
                    'priority' => 'high',
                    'official' => true
                ]
            ]
        );

        // Create sample tutorial
        $aiTutorial = Tutorial::firstOrCreate(
            ['slug' => 'ai-fundamentals-tutorial'],
            [
                'title' => 'AI Fundamentals Tutorial',
                'description' => 'Learn the basics of artificial intelligence through hands-on exercises',
                'overview' => 'This comprehensive tutorial covers the fundamental concepts of AI...',
                'content_id' => $aiArticle->id,
                'author_id' => $adminUser->id,
                'estimated_duration' => 120, // 2 hours
                'difficulty_level' => 2,
                'learning_objectives' => [
                    'Master basic AI concepts',
                    'Understand machine learning principles',
                    'Build your first AI model'
                ],
                'prerequisites' => [],
                'resources' => [
                    'Python programming knowledge',
                    'Basic mathematics understanding'
                ],
                'thumbnail' => '/images/ai-tutorial.jpg',
                'steps' => [
                    [
                        'title' => 'Introduction to AI',
                        'description' => 'Learn what AI is and its applications',
                        'duration' => 20
                    ],
                    [
                        'title' => 'Machine Learning Basics',
                        'description' => 'Understand how machines learn',
                        'duration' => 30
                    ],
                    [
                        'title' => 'Hands-on Exercise',
                        'description' => 'Build your first AI model',
                        'duration' => 70
                    ]
                ],
                'is_interactive' => true,
                'has_quiz' => true,
                'has_certificate' => true,
                'is_published' => true,
                'published_at' => now(),
                'metadata' => [
                    'featured' => true,
                    'difficulty' => 'beginner'
                ]
            ]
        );

        // Create sample video
        $stargateVideo = Video::firstOrCreate(
            ['slug' => 'stargate-project-video'],
            [
                'title' => 'Stargate Project: Official Introduction',
                'description' => 'Official video introduction to the Stargate project',
                'content_id' => $stargateArticle->id,
                'author_id' => $adminUser->id,
                'video_url' => '/videos/stargate-intro.mp4',
                'thumbnail_url' => '/images/stargate-video-thumb.jpg',
                'duration' => 300, // 5 minutes
                'format' => 'mp4',
                'file_size' => 50000000, // 50MB
                'quality_options' => [
                    '720p' => '/videos/stargate-intro-720p.mp4',
                    '1080p' => '/videos/stargate-intro-1080p.mp4'
                ],
                'subtitles' => [
                    'en' => '/subtitles/stargate-intro-en.vtt',
                    'es' => '/subtitles/stargate-intro-es.vtt'
                ],
                'chapters' => [
                    ['title' => 'Introduction', 'start' => 0, 'end' => 60],
                    ['title' => 'Project Overview', 'start' => 60, 'end' => 180],
                    ['title' => 'Technology', 'start' => 180, 'end' => 240],
                    ['title' => 'Conclusion', 'start' => 240, 'end' => 300]
                ],
                'has_transcript' => true,
                'transcript' => 'Welcome to the Stargate project introduction...',
                'is_public' => true,
                'allow_download' => false,
                'allow_embed' => true,
                'metadata' => [
                    'featured' => true,
                    'official' => true
                ]
            ]
        );

        // Create sample quiz
        $aiQuiz = Quiz::firstOrCreate(
            ['slug' => 'ai-fundamentals-quiz'],
            [
                'title' => 'AI Fundamentals Quiz',
                'description' => 'Test your knowledge of AI fundamentals',
                'content_id' => $aiArticle->id,
                'author_id' => $adminUser->id,
                'type' => 'assessment',
                'time_limit' => 30, // 30 minutes
                'passing_score' => 70,
                'max_attempts' => 3,
                'instructions' => [
                    'Read each question carefully',
                    'Select the best answer',
                    'You have 30 minutes to complete',
                    'Passing score is 70%'
                ],
                'randomize_questions' => true,
                'show_correct_answers' => true,
                'show_explanations' => true,
                'allow_review' => true,
                'is_published' => true,
                'published_at' => now(),
                'question_count' => 10,
                'total_points' => 100,
                'metadata' => [
                    'featured' => true,
                    'difficulty' => 'beginner'
                ]
            ]
        );
    }
}