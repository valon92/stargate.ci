<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\ContentCategory;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stargateCategory = ContentCategory::where('slug', 'stargate-project')->first();
        $cristalCategory = ContentCategory::where('slug', 'cristal-intelligence')->first();
        $ethicsCategory = ContentCategory::where('slug', 'ai-ethics-governance')->first();
        $infrastructureCategory = ContentCategory::where('slug', 'technology-infrastructure')->first();
        
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $expertUser = User::where('email', 'michael@example.com')->first();
        $journalistUser = User::where('email', 'sarah@example.com')->first();

        $articles = [
            [
                'title' => 'Stargate Project: The $500 Billion AI Infrastructure Revolution',
                'slug' => 'stargate-project-500-billion-ai-infrastructure',
                'excerpt' => 'Explore the groundbreaking Stargate project, a $500 billion investment in AI infrastructure by OpenAI, SoftBank, and Arm that promises to revolutionize artificial intelligence.',
                'content' => $this->getStargateArticleContent(),
                'category' => 'Stargate Project',
                'author' => 'Admin User',
                'read_time' => 15,
                'published' => true,
                'published_at' => now()->subDays(5)
            ],
            [
                'title' => 'Understanding Cristal Intelligence: The Next Paradigm in AI',
                'slug' => 'understanding-cristal-intelligence-next-paradigm',
                'excerpt' => 'Cristal Intelligence represents a revolutionary approach to artificial intelligence, transcending traditional boundaries and offering unprecedented clarity in AI systems.',
                'content' => $this->getCristalIntelligenceContent(),
                'category' => 'Cristal Intelligence',
                'author' => 'Dr. Michael Chen',
                'read_time' => 20,
                'published' => true,
                'published_at' => now()->subDays(3)
            ],
            [
                'title' => 'Ethical AI Development: Guiding Principles for the Stargate Era',
                'slug' => 'ethical-ai-development-stargate-era-principles',
                'excerpt' => 'As we enter the Stargate era of AI development, ethical considerations become paramount. Learn about the guiding principles for responsible AI implementation.',
                'content' => $this->getEthicalAIContent(),
                'category' => 'AI Ethics',
                'author' => 'Dr. Michael Chen',
                'read_time' => 25,
                'published' => true,
                'published_at' => now()->subDays(7)
            ],
            [
                'title' => 'The Technical Infrastructure Behind Stargate: A Deep Dive',
                'slug' => 'technical-infrastructure-stargate-deep-dive',
                'excerpt' => 'Explore the massive technical infrastructure required to support the Stargate project, from data centers to specialized hardware and networking.',
                'content' => $this->getInfrastructureContent(),
                'category' => 'Technology Infrastructure',
                'author' => 'Admin User',
                'read_time' => 30,
                'published' => true,
                'published_at' => now()->subDays(10)
            ],
            [
                'title' => 'Stargate Project Timeline: From Concept to Implementation',
                'slug' => 'stargate-project-timeline-concept-implementation',
                'excerpt' => 'Follow the journey of the Stargate project from its initial concept to full implementation, including key milestones and future projections.',
                'content' => $this->getTimelineContent(),
                'category' => 'Stargate Project',
                'author' => 'Sarah Johnson',
                'read_time' => 18,
                'published' => true,
                'published_at' => now()->subDays(2)
            ],
            [
                'title' => 'Cristal Intelligence vs Traditional AI: A Comparative Analysis',
                'slug' => 'cristal-intelligence-vs-traditional-ai-analysis',
                'excerpt' => 'Compare Cristal Intelligence with traditional AI approaches, examining the advantages, challenges, and transformative potential of this new paradigm.',
                'content' => $this->getComparisonContent(),
                'category' => 'Cristal Intelligence',
                'author' => 'Dr. Michael Chen',
                'read_time' => 22,
                'published' => true,
                'published_at' => now()->subDays(1)
            ]
        ];

        foreach ($articles as $articleData) {
            Article::create($articleData);
        }
    }

    private function getStargateArticleContent(): string
    {
        return '
        <h2>Introduction to the Stargate Project</h2>
        <p>The Stargate project represents one of the most ambitious technological endeavors in human history. With a staggering $500 billion investment from OpenAI, SoftBank, and Arm, this project aims to create the world\'s most advanced AI infrastructure.</p>
        
        <h3>Project Overview</h3>
        <p>The Stargate project is designed to push the boundaries of artificial intelligence by creating a massive, interconnected network of AI systems that can process information at unprecedented scales. This infrastructure will support the development of next-generation AI models that could revolutionize everything from scientific research to business operations.</p>
        
        <h3>Key Partners and Their Roles</h3>
        <ul>
            <li><strong>OpenAI:</strong> Leading the AI research and development aspects of the project</li>
            <li><strong>SoftBank:</strong> Providing significant financial backing and strategic support</li>
            <li><strong>Arm:</strong> Contributing advanced chip technology and hardware expertise</li>
        </ul>
        
        <h3>Technical Specifications</h3>
        <p>The Stargate infrastructure will include:</p>
        <ul>
            <li>Massive data centers with specialized AI hardware</li>
            <li>Advanced networking capabilities for distributed computing</li>
            <li>Cutting-edge cooling systems for high-performance computing</li>
            <li>Robust security measures to protect intellectual property</li>
        </ul>
        
        <h3>Expected Impact</h3>
        <p>The Stargate project is expected to accelerate AI development by several years, potentially leading to breakthroughs in areas such as:</p>
        <ul>
            <li>Natural language processing</li>
            <li>Computer vision</li>
            <li>Scientific discovery</li>
            <li>Automated reasoning</li>
        </ul>
        
        <h3>Timeline and Milestones</h3>
        <p>The project is expected to be completed in phases over the next several years, with initial infrastructure coming online in 2025 and full deployment by 2027.</p>
        ';
    }

    private function getCristalIntelligenceContent(): string
    {
        return '
        <h2>What is Cristal Intelligence?</h2>
        <p>Cristal Intelligence represents a revolutionary paradigm shift in artificial intelligence, moving beyond traditional approaches to create AI systems with unprecedented clarity, precision, and transparency.</p>
        
        <h3>Core Principles</h3>
        <p>Cristal Intelligence is built on several fundamental principles:</p>
        <ul>
            <li><strong>Transparency:</strong> Every decision and process is clear and explainable</li>
            <li><strong>Precision:</strong> Crystal-clear accuracy in all operations</li>
            <li><strong>Harmony:</strong> Seamless integration with human values and ethics</li>
            <li><strong>Evolution:</strong> Continuous improvement and adaptation</li>
        </ul>
        
        <h3>Key Differences from Traditional AI</h3>
        <p>Unlike traditional AI systems that often operate as "black boxes," Cristal Intelligence systems are designed to be:</p>
        <ul>
            <li>Fully transparent in their decision-making processes</li>
            <li>Ethically aligned with human values</li>
            <li>Capable of explaining their reasoning</li>
            <li>Adaptable to changing circumstances</li>
        </ul>
        
        <h3>Applications and Use Cases</h3>
        <p>Cristal Intelligence can be applied across various domains:</p>
        <ul>
            <li>Healthcare: Precise diagnosis and treatment recommendations</li>
            <li>Finance: Transparent and fair lending decisions</li>
            <li>Education: Personalized learning experiences</li>
            <li>Scientific Research: Accelerated discovery and analysis</li>
        </ul>
        
        <h3>The Future of Cristal Intelligence</h3>
        <p>As we move forward, Cristal Intelligence will play a crucial role in ensuring that AI development remains beneficial, transparent, and aligned with human values.</p>
        ';
    }

    private function getEthicalAIContent(): string
    {
        return '
        <h2>The Importance of Ethical AI Development</h2>
        <p>As we enter the Stargate era of AI development, ethical considerations become more critical than ever. The massive scale and power of these systems require careful attention to ethical principles.</p>
        
        <h3>Core Ethical Principles</h3>
        <ul>
            <li><strong>Beneficence:</strong> AI systems should benefit humanity</li>
            <li><strong>Non-maleficence:</strong> AI systems should not cause harm</li>
            <li><strong>Autonomy:</strong> Respect for human autonomy and decision-making</li>
            <li><strong>Justice:</strong> Fair and equitable treatment for all</li>
            <li><strong>Transparency:</strong> Open and explainable AI systems</li>
        </ul>
        
        <h3>Governance Frameworks</h3>
        <p>Effective AI governance requires:</p>
        <ul>
            <li>Clear regulatory frameworks</li>
            <li>Industry standards and best practices</li>
            <li>Regular audits and assessments</li>
            <li>Stakeholder engagement and input</li>
        </ul>
        
        <h3>Implementation Strategies</h3>
        <p>Organizations should implement ethical AI through:</p>
        <ul>
            <li>Ethics committees and review boards</li>
            <li>Regular training and education programs</li>
            <li>Impact assessments and risk evaluations</li>
            <li>Continuous monitoring and improvement</li>
        </ul>
        ';
    }

    private function getInfrastructureContent(): string
    {
        return '
        <h2>Stargate Infrastructure Overview</h2>
        <p>The Stargate project requires an unprecedented level of technical infrastructure to support its ambitious AI goals.</p>
        
        <h3>Data Centers</h3>
        <p>The project will utilize multiple massive data centers featuring:</p>
        <ul>
            <li>Specialized AI computing hardware</li>
            <li>Advanced cooling systems</li>
            <li>Redundant power systems</li>
            <li>High-speed networking infrastructure</li>
        </ul>
        
        <h3>Hardware Requirements</h3>
        <p>Specialized hardware includes:</p>
        <ul>
            <li>Custom AI chips designed by Arm</li>
            <li>High-performance GPUs and TPUs</li>
            <li>Massive storage systems</li>
            <li>Advanced networking equipment</li>
        </ul>
        
        <h3>Software Infrastructure</h3>
        <p>The software stack includes:</p>
        <ul>
            <li>Distributed computing frameworks</li>
            <li>AI model training platforms</li>
            <li>Data processing pipelines</li>
            <li>Security and monitoring systems</li>
        </ul>
        ';
    }

    private function getTimelineContent(): string
    {
        return '
        <h2>Stargate Project Timeline</h2>
        <p>The Stargate project has followed a carefully planned timeline from concept to implementation.</p>
        
        <h3>Phase 1: Planning and Design (2023-2024)</h3>
        <ul>
            <li>Initial concept development</li>
            <li>Partnership agreements</li>
            <li>Technical architecture design</li>
            <li>Site selection and preparation</li>
        </ul>
        
        <h3>Phase 2: Infrastructure Development (2024-2025)</h3>
        <ul>
            <li>Data center construction</li>
            <li>Hardware procurement and installation</li>
            <li>Network infrastructure deployment</li>
            <li>Initial system testing</li>
        </ul>
        
        <h3>Phase 3: AI System Development (2025-2026)</h3>
        <ul>
            <li>AI model training and development</li>
            <li>System integration and testing</li>
            <li>Performance optimization</li>
            <li>Security implementation</li>
        </ul>
        
        <h3>Phase 4: Full Deployment (2026-2027)</h3>
        <ul>
            <li>Complete system deployment</li>
            <li>Full-scale testing and validation</li>
            <li>Public launch and operation</li>
            <li>Ongoing maintenance and updates</li>
        </ul>
        ';
    }

    private function getComparisonContent(): string
    {
        return '
        <h2>Cristal Intelligence vs Traditional AI</h2>
        <p>A comprehensive comparison of Cristal Intelligence with traditional AI approaches.</p>
        
        <h3>Transparency and Explainability</h3>
        <p><strong>Cristal Intelligence:</strong> Fully transparent decision-making processes with clear explanations for every action.</p>
        <p><strong>Traditional AI:</strong> Often operates as a "black box" with limited explainability.</p>
        
        <h3>Ethical Alignment</h3>
        <p><strong>Cristal Intelligence:</strong> Built-in ethical frameworks and human value alignment.</p>
        <p><strong>Traditional AI:</strong> Ethical considerations often added as afterthoughts.</p>
        
        <h3>Adaptability</h3>
        <p><strong>Cristal Intelligence:</strong> Designed for continuous learning and adaptation.</p>
        <p><strong>Traditional AI:</strong> Limited adaptability and requires manual updates.</p>
        
        <h3>Performance and Efficiency</h3>
        <p><strong>Cristal Intelligence:</strong> Optimized for both performance and efficiency.</p>
        <p><strong>Traditional AI:</strong> Often focuses on performance at the expense of efficiency.</p>
        ';
    }
}