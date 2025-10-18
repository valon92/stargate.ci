<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = [
            [
                'content_id' => 'stargate-intro-video',
                'title' => 'Stargate Project - Introduction Video',
                'description' => 'An introduction video that explains the scope and vision of the Stargate Project and Cristal Intelligence, providing users with a comprehensive understanding of these revolutionary AI initiatives.',
                'youtube_id' => 'GhIJs4zbH0o',
                'youtube_url' => 'https://www.youtube.com/embed/GhIJs4zbH0o?rel=0&modestbranding=1',
                'content_type' => 'video',
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'views_count' => 0,
                'is_active' => true,
                'metadata' => [
                    'category' => 'introduction',
                    'duration' => '10:30',
                    'tags' => ['stargate', 'introduction', 'ai', 'infrastructure']
                ]
            ],
            [
                'content_id' => 'stargate-deep-dive-video',
                'title' => 'Stargate Project - Deep Dive Analysis',
                'description' => 'A comprehensive deep dive analysis of the Stargate Project, exploring the technical aspects, partnerships, and future implications of this groundbreaking AI infrastructure initiative.',
                'youtube_id' => 'JPcxiWOOj2E',
                'youtube_url' => 'https://www.youtube.com/embed/JPcxiWOOj2E?rel=0&modestbranding=1',
                'content_type' => 'video',
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'views_count' => 0,
                'is_active' => true,
                'metadata' => [
                    'category' => 'analysis',
                    'duration' => '15:45',
                    'tags' => ['stargate', 'analysis', 'technical', 'partnerships']
                ]
            ],
            [
                'content_id' => 'stargate-technical-video',
                'title' => 'Stargate Project - Technical Implementation',
                'description' => 'A detailed technical implementation guide for the Stargate Project, covering infrastructure requirements, deployment strategies, and integration approaches for organizations looking to leverage this advanced AI technology.',
                'youtube_id' => '1oYSZ-naJVo',
                'youtube_url' => 'https://www.youtube.com/embed/1oYSZ-naJVo?rel=0&modestbranding=1',
                'content_type' => 'video',
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'views_count' => 0,
                'is_active' => true,
                'metadata' => [
                    'category' => 'technical',
                    'duration' => '12:20',
                    'tags' => ['stargate', 'technical', 'implementation', 'infrastructure']
                ]
            ],
            [
                'content_id' => 'cristal-intelligence-video',
                'title' => 'Cristal Intelligence - Advanced AI Paradigm',
                'description' => 'An in-depth exploration of Cristal Intelligence, the revolutionary AI paradigm that emphasizes transparency, interpretability, and ethical alignment in artificial intelligence systems.',
                'youtube_id' => 'DHiX4_jetjs',
                'youtube_url' => 'https://www.youtube.com/embed/DHiX4_jetjs?rel=0&modestbranding=1',
                'content_type' => 'video',
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'views_count' => 0,
                'is_active' => true,
                'metadata' => [
                    'category' => 'cristal-intelligence',
                    'duration' => '18:15',
                    'tags' => ['cristal-intelligence', 'ai-paradigm', 'transparency', 'ethics']
                ]
            ],
            [
                'content_id' => 'future-ai-video',
                'title' => 'Future of AI - Industry Impact & Applications',
                'description' => 'A comprehensive analysis of how the Stargate Project and Cristal Intelligence will transform industries, examining real-world applications, market implications, and the future landscape of artificial intelligence.',
                'youtube_id' => 'po3TG3oSsSI',
                'youtube_url' => 'https://www.youtube.com/embed/po3TG3oSsSI?rel=0&modestbranding=1',
                'content_type' => 'video',
                'likes_count' => 0,
                'comments_count' => 0,
                'shares_count' => 0,
                'views_count' => 0,
                'is_active' => true,
                'metadata' => [
                    'category' => 'future',
                    'duration' => '14:30',
                    'tags' => ['future-ai', 'industry', 'applications', 'market-analysis']
                ]
            ]
        ];

        foreach ($videos as $videoData) {
            Video::updateOrCreate(
                ['content_id' => $videoData['content_id']],
                $videoData
            );
        }
    }
}