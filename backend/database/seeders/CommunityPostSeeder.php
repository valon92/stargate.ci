<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunityPost;
use App\Models\Subscriber;

class CommunityPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some subscribers to use as authors
        $subscribers = Subscriber::limit(5)->get();
        
        if ($subscribers->isEmpty()) {
            $this->command->warn('No subscribers found. Please run SubscriberSeeder first.');
            return;
        }

        $posts = [
            [
                'title' => 'My Experience with Stargate Project - First Impressions',
                'content' => 'I\'ve been following the Stargate Project closely and I wanted to share my thoughts on this revolutionary $500 billion AI infrastructure initiative. The collaboration between OpenAI, SoftBank, and Arm represents a significant leap forward in AI development. The scale and ambition of this project is truly unprecedented. I\'m particularly excited about the potential impact on AI research and development. What are your thoughts on this?',
                'category' => 'experience',
                'tags' => ['stargate', 'ai', 'experience', 'openai'],
                'images' => [
                    'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=800',
                    'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800'
                ],
                'videos' => null,
                'media_type' => 'image',
                'is_pinned' => true,
            ],
            [
                'title' => 'Understanding Cristal Intelligence - A New Paradigm',
                'content' => 'Cristal Intelligence represents the next paradigm in AI development, emphasizing transparency, interpretability, and ethical alignment. This approach is crucial for building trust in AI systems. I\'ve been researching how this relates to the Stargate Project and I believe it\'s a perfect match. The combination of massive infrastructure with ethical AI principles could reshape the industry. Let\'s discuss the implications!',
                'category' => 'discussion',
                'tags' => ['cristal-intelligence', 'ai-ethics', 'discussion', 'stargate'],
                'images' => [
                    'https://images.unsplash.com/photo-1555255707-c07966088b7b?w=800'
                ],
                'videos' => null,
                'media_type' => 'image',
                'is_pinned' => false,
            ],
            [
                'title' => 'Question: How will Stargate Project affect AI startups?',
                'content' => 'As an AI startup founder, I\'m curious about how the Stargate Project will impact smaller companies in the AI space. Will this create opportunities for collaboration, or will it make it harder for startups to compete? I\'d love to hear from others who are thinking about this. What strategies are you considering?',
                'category' => 'question',
                'tags' => ['question', 'startup', 'ai', 'business'],
                'images' => null,
                'videos' => [
                    'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4'
                ],
                'media_type' => 'video',
                'is_pinned' => false,
            ],
            [
                'title' => 'Idea: Community-Driven AI Ethics Framework',
                'content' => 'I have an idea for creating a community-driven AI ethics framework that could complement the Stargate Project. The framework would involve input from researchers, developers, ethicists, and the general public. This could help ensure that as AI infrastructure scales, ethical considerations remain at the forefront. What do you think? Should we start a working group?',
                'category' => 'idea',
                'tags' => ['idea', 'ethics', 'community', 'framework'],
                'images' => [
                    'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800',
                    'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800',
                    'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800'
                ],
                'videos' => null,
                'media_type' => 'image',
                'is_pinned' => false,
            ],
            [
                'title' => 'Discussion: The Future of AI Infrastructure',
                'content' => 'Let\'s have a deep discussion about the future of AI infrastructure. With projects like Stargate pushing the boundaries, what do you think the next 10 years will look like? Will we see more massive investments? How will this affect accessibility? I\'m particularly interested in hearing diverse perspectives on this topic.',
                'category' => 'discussion',
                'tags' => ['discussion', 'future', 'infrastructure', 'ai'],
                'images' => [
                    'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800'
                ],
                'videos' => [
                    'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4'
                ],
                'media_type' => 'mixed',
                'is_pinned' => false,
            ],
            [
                'title' => 'My Journey Learning About Stargate Project',
                'content' => 'I\'ve been on an incredible journey learning about the Stargate Project. From reading the initial announcements to diving deep into the technical aspects, it\'s been fascinating. The collaboration between three major tech companies shows the importance of partnerships in advancing AI. I\'ve created a visual summary of key milestones. Check it out!',
                'category' => 'experience',
                'tags' => ['experience', 'journey', 'learning', 'stargate'],
                'images' => [
                    'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800',
                    'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800'
                ],
                'videos' => null,
                'media_type' => 'image',
                'is_pinned' => false,
            ],
            [
                'title' => 'Question: Best Resources for Understanding AI Infrastructure?',
                'content' => 'I\'m new to AI infrastructure and I\'m trying to understand the technical aspects of projects like Stargate. Can anyone recommend good resources? Books, courses, papers, or even YouTube channels would be helpful. I\'m particularly interested in distributed systems and large-scale computing.',
                'category' => 'question',
                'tags' => ['question', 'resources', 'learning', 'infrastructure'],
                'images' => null,
                'videos' => null,
                'media_type' => null,
                'is_pinned' => false,
            ],
            [
                'title' => 'Idea: Open Source Tools for Stargate Community',
                'content' => 'I think we should create a collection of open-source tools and resources specifically for the Stargate community. This could include visualization tools, educational materials, and collaboration platforms. Would anyone be interested in contributing to such a project? Let\'s make AI more accessible!',
                'category' => 'idea',
                'tags' => ['idea', 'open-source', 'tools', 'community'],
                'images' => [
                    'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800'
                ],
                'videos' => null,
                'media_type' => 'image',
                'is_pinned' => false,
            ],
            [
                'title' => 'Discussion: Ethical Considerations in Large-Scale AI',
                'content' => 'As we move towards larger and more powerful AI systems, ethical considerations become increasingly important. How do we ensure that projects like Stargate benefit humanity as a whole? What safeguards should be in place? Let\'s have a thoughtful discussion about this critical topic.',
                'category' => 'discussion',
                'tags' => ['discussion', 'ethics', 'safety', 'ai'],
                'images' => [
                    'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800'
                ],
                'videos' => null,
                'media_type' => 'image',
                'is_pinned' => false,
            ],
            [
                'title' => 'Experience: Attending Stargate Project Webinar',
                'content' => 'I just attended an amazing webinar about the Stargate Project. The speakers provided incredible insights into the technical architecture and future plans. The Q&A session was particularly enlightening. I took some notes and screenshots that I\'d like to share with the community. What questions do you have?',
                'category' => 'experience',
                'tags' => ['experience', 'webinar', 'learning', 'stargate'],
                'images' => [
                    'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=800',
                    'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800'
                ],
                'videos' => null,
                'media_type' => 'image',
                'is_pinned' => false,
            ],
        ];

        foreach ($posts as $index => $postData) {
            $subscriber = $subscribers[$index % $subscribers->count()];
            
            // Get or create user_id from subscriber
            $user = \App\Models\User::where('email', $subscriber->email)->first();
            if (!$user) {
                // Create a user if it doesn't exist
                $user = \App\Models\User::create([
                    'name' => $subscriber->username,
                    'email' => $subscriber->email,
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);
            }
            $userId = $user->id;

            // Generate slug
            $slug = \Illuminate\Support\Str::slug($postData['title']);
            $count = CommunityPost::where('slug', $slug)->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }

            // Map category to category_id
            $categoryIdMap = [
                'general' => 1,
                'experience' => 2,
                'question' => 3,
                'idea' => 4,
                'discussion' => 5
            ];
            $categoryId = $categoryIdMap[$postData['category']] ?? 1;

            CommunityPost::create([
                'subscriber_id' => $subscriber->id,
                'user_id' => $userId,
                'title' => $postData['title'],
                'slug' => $slug,
                'content' => $postData['content'],
                'images' => $postData['images'],
                'videos' => $postData['videos'],
                'media_type' => $postData['media_type'],
                'category' => $postData['category'],
                'category_id' => $categoryId,
                'type' => 'post',
                'tags' => json_encode($postData['tags']),
                'is_pinned' => $postData['is_pinned'],
                'is_locked' => false,
                'views_count' => rand(50, 500),
                'likes_count' => rand(5, 50),
                'comments_count' => rand(2, 20),
                'shares_count' => rand(1, 15),
                'status' => 'published'
            ]);
        }

        $this->command->info('Created ' . count($posts) . ' community posts with media!');
    }
}

