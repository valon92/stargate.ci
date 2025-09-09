<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Understanding Artificial Intelligence: A Beginner\'s Guide',
                'slug' => 'understanding-artificial-intelligence-beginners-guide',
                'excerpt' => 'Explore the fundamentals of AI, from machine learning basics to advanced neural networks, and understand how these technologies are shaping our world.',
                'content' => '<h2>Introduction to Artificial Intelligence</h2><p>Artificial Intelligence (AI) represents one of the most transformative technologies of our time. From simple rule-based systems to complex neural networks, AI has evolved dramatically over the past few decades.</p><h3>What is Artificial Intelligence?</h3><p>AI refers to the simulation of human intelligence in machines that are programmed to think and learn like humans. These systems can perform tasks that typically require human intelligence, such as visual perception, speech recognition, decision-making, and language translation.</p><h3>Types of AI</h3><p>There are several types of AI systems:</p><ul><li><strong>Narrow AI:</strong> Designed for specific tasks, like image recognition or language translation</li><li><strong>General AI:</strong> Hypothetical AI that can understand, learn, and apply knowledge across different domains</li><li><strong>Superintelligence:</strong> AI that surpasses human intelligence in all areas</li></ul><h3>Machine Learning Fundamentals</h3><p>Machine learning is a subset of AI that enables computers to learn and improve from experience without being explicitly programmed. It uses algorithms to identify patterns in data and make predictions or decisions.</p><h3>Real-World Applications</h3><p>AI is already transforming various industries:</p><ul><li>Healthcare: Medical diagnosis and drug discovery</li><li>Finance: Fraud detection and algorithmic trading</li><li>Transportation: Autonomous vehicles and traffic optimization</li><li>Entertainment: Content recommendation and game AI</li></ul><h3>Ethical Considerations</h3><p>As AI becomes more powerful, it\'s crucial to consider the ethical implications. Issues such as bias, privacy, job displacement, and decision-making transparency need to be addressed to ensure AI benefits all of humanity.</p><h3>Conclusion</h3><p>Understanding AI is essential in our increasingly digital world. As these technologies continue to evolve, staying informed about their capabilities, limitations, and implications will help us navigate the future effectively.</p>',
                'category' => 'AI',
                'icon' => '🤖',
                'author' => 'Stargate.ci Team',
                'read_time' => 8,
                'published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'The Future of Cloud Computing in AI Infrastructure',
                'slug' => 'future-cloud-computing-ai-infrastructure',
                'excerpt' => 'Discover how cloud computing is revolutionizing AI development and deployment, enabling scalable solutions for complex machine learning workloads.',
                'content' => '<h2>Cloud Computing and AI: A Perfect Match</h2><p>Cloud computing has become the backbone of modern AI infrastructure, providing the scalability, flexibility, and computational power needed for advanced machine learning applications.</p><h3>Scalability Benefits</h3><p>Cloud platforms allow AI systems to scale resources dynamically based on demand, ensuring optimal performance while controlling costs.</p><h3>Global Accessibility</h3><p>Cloud-based AI services make advanced capabilities accessible to organizations worldwide, democratizing access to cutting-edge technology.</p>',
                'category' => 'Cloud',
                'icon' => '☁️',
                'author' => 'Stargate.ci Team',
                'read_time' => 6,
                'published' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Ethical AI: Building Responsible Technology',
                'slug' => 'ethical-ai-building-responsible-technology',
                'excerpt' => 'Learn about the importance of ethical considerations in AI development and how organizations are working to ensure AI benefits all of humanity.',
                'content' => '<h2>The Importance of Ethical AI</h2><p>As AI systems become more powerful and widespread, ensuring they are developed and deployed ethically is crucial for building trust and maximizing benefits.</p><h3>Key Principles</h3><p>Ethical AI development should prioritize fairness, transparency, accountability, and human well-being.</p><h3>Industry Initiatives</h3><p>Leading organizations are establishing frameworks and guidelines to promote responsible AI development.</p>',
                'category' => 'Ethics',
                'icon' => '⚖️',
                'author' => 'Stargate.ci Team',
                'read_time' => 10,
                'published' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Data Security in the Age of AI',
                'slug' => 'data-security-age-ai',
                'excerpt' => 'Understand the critical importance of data security and privacy protection in AI systems, and learn about best practices for secure AI deployment.',
                'content' => '<h2>Securing AI Systems</h2><p>As AI systems process vast amounts of sensitive data, implementing robust security measures is essential to protect privacy and prevent misuse.</p><h3>Privacy Protection</h3><p>Techniques like differential privacy and federated learning help protect individual data while enabling AI training.</p><h3>Best Practices</h3><p>Organizations should implement comprehensive security frameworks covering data encryption, access controls, and monitoring.</p>',
                'category' => 'Security',
                'icon' => '🔒',
                'author' => 'Stargate.ci Team',
                'read_time' => 7,
                'published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Machine Learning Algorithms Explained',
                'slug' => 'machine-learning-algorithms-explained',
                'excerpt' => 'Dive deep into different types of machine learning algorithms, from supervised learning to reinforcement learning, and their real-world applications.',
                'content' => '<h2>Types of Machine Learning</h2><p>Machine learning can be categorized into three main types: supervised, unsupervised, and reinforcement learning.</p><h3>Supervised Learning</h3><p>Uses labeled training data to learn patterns and make predictions on new, unseen data.</p><h3>Unsupervised Learning</h3><p>Finds hidden patterns in data without labeled examples, useful for clustering and dimensionality reduction.</p><h3>Reinforcement Learning</h3><p>Learns through interaction with an environment, receiving rewards or penalties for actions.</p>',
                'category' => 'AI',
                'icon' => '🧠',
                'author' => 'Stargate.ci Team',
                'read_time' => 12,
                'published' => true,
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Edge Computing: Bringing AI Closer to Users',
                'slug' => 'edge-computing-bringing-ai-closer-users',
                'excerpt' => 'Explore how edge computing is enabling AI applications to run closer to users, reducing latency and improving performance in real-world scenarios.',
                'content' => '<h2>What is Edge Computing?</h2><p>Edge computing brings computation and data storage closer to the sources of data, reducing latency and improving response times.</p><h3>AI at the Edge</h3><p>Running AI models on edge devices enables real-time processing and reduces dependence on cloud connectivity.</p><h3>Benefits</h3><p>Edge AI offers improved privacy, reduced bandwidth usage, and faster response times for critical applications.</p>',
                'category' => 'Cloud',
                'icon' => '⚡',
                'author' => 'Stargate.ci Team',
                'read_time' => 9,
                'published' => true,
                'published_at' => now()->subDays(6),
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
