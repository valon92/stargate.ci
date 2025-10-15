<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            // Stargate Project FAQs
            [
                'question' => 'What is the Stargate project?',
                'answer' => 'The Stargate project is a $500 billion AI infrastructure initiative by OpenAI, SoftBank, and Arm. It represents the largest investment in AI infrastructure ever made, designed to create the world\'s most advanced AI systems.',
                'order' => 1,
                'active' => true
            ],
            [
                'question' => 'Who are the partners in the Stargate project?',
                'answer' => 'The Stargate project is a collaboration between three major technology companies: OpenAI (leading AI research and development), SoftBank (providing financial backing and strategic support), and Arm (contributing advanced chip technology and hardware expertise).',
                'order' => 2,
                'active' => true
            ],
            [
                'question' => 'What is the timeline for the Stargate project?',
                'answer' => 'The Stargate project is expected to be completed in phases: Planning and Design (2023-2024), Infrastructure Development (2024-2025), AI System Development (2025-2026), and Full Deployment (2026-2027).',
                'order' => 3,
                'active' => true
            ],
            [
                'question' => 'How much is being invested in the Stargate project?',
                'answer' => 'The total investment in the Stargate project is $500 billion, making it the largest AI infrastructure investment in history. This funding covers infrastructure, hardware, software, and ongoing operational costs.',
                'order' => 4,
                'active' => true
            ],

            // Cristal Intelligence FAQs
            [
                'question' => 'What is Cristal Intelligence?',
                'answer' => 'Cristal Intelligence is a revolutionary paradigm in artificial intelligence that emphasizes transparency, precision, and ethical alignment. It represents a new approach to AI development that prioritizes clarity and human value alignment.',
                'order' => 5,
                'active' => true
            ],
            [
                'question' => 'How is Cristal Intelligence different from traditional AI?',
                'answer' => 'Cristal Intelligence differs from traditional AI in several key ways: it prioritizes transparency and explainability, has built-in ethical frameworks, is designed for continuous adaptation, and maintains crystal-clear precision in all operations.',
                'order' => 6,
                'active' => true
            ],
            [
                'question' => 'What are the core principles of Cristal Intelligence?',
                'answer' => 'The core principles of Cristal Intelligence are: Transparency (clear and explainable decisions), Precision (crystal-clear accuracy), Harmony (seamless integration with human values), and Evolution (continuous improvement and adaptation).',
                'order' => 7,
                'active' => true
            ],

            // AI Ethics FAQs
            [
                'question' => 'Why is ethical AI development important?',
                'answer' => 'Ethical AI development is crucial because AI systems have the potential to significantly impact society. Without proper ethical considerations, AI could perpetuate bias, violate privacy, or cause unintended harm. Ethical development ensures AI benefits humanity while respecting human values and rights.',
                'order' => 8,
                'active' => true
            ],
            [
                'question' => 'What are the key ethical principles for AI development?',
                'answer' => 'The key ethical principles for AI development include: Beneficence (AI should benefit humanity), Non-maleficence (AI should not cause harm), Autonomy (respect for human decision-making), Justice (fair and equitable treatment), and Transparency (open and explainable systems).',
                'order' => 9,
                'active' => true
            ],
            [
                'question' => 'How can organizations implement ethical AI?',
                'answer' => 'Organizations can implement ethical AI through: establishing ethics committees and review boards, providing regular training and education programs, conducting impact assessments and risk evaluations, and implementing continuous monitoring and improvement processes.',
                'order' => 10,
                'active' => true
            ],

            // Technology Infrastructure FAQs
            [
                'question' => 'What kind of infrastructure is needed for the Stargate project?',
                'answer' => 'The Stargate project requires massive infrastructure including: specialized data centers with AI computing hardware, advanced cooling systems, redundant power systems, high-speed networking infrastructure, and robust security measures.',
                'order' => 11,
                'active' => true
            ],
            [
                'question' => 'What hardware is being used in the Stargate project?',
                'answer' => 'The Stargate project utilizes specialized hardware including: custom AI chips designed by Arm, high-performance GPUs and TPUs, massive storage systems, and advanced networking equipment optimized for AI workloads.',
                'order' => 12,
                'active' => true
            ],

            // General FAQs
            [
                'question' => 'What is the purpose of stargate.ci?',
                'answer' => 'Stargate.ci is an independent educational platform dedicated to informing the public about the Stargate project and Cristal Intelligence. We provide transparent, accessible information about cutting-edge AI technologies and their ethical implications.',
                'order' => 13,
                'active' => true
            ],
            [
                'question' => 'Is stargate.ci officially affiliated with the Stargate project?',
                'answer' => 'No, stargate.ci is an independent educational initiative and is not officially affiliated with the Stargate project, OpenAI, SoftBank, or Arm. We are an independent platform providing educational content based on publicly available information.',
                'order' => 14,
                'active' => true
            ],
            [
                'question' => 'How can I stay updated on Stargate project developments?',
                'answer' => 'You can stay updated by following our platform, subscribing to our newsletter, and regularly checking our articles and insights section. We provide regular updates based on publicly available information about the Stargate project.',
                'order' => 15,
                'active' => true
            ],
            [
                'question' => 'What should I know about AI safety and the Stargate project?',
                'answer' => 'AI safety is a critical consideration for the Stargate project. The massive scale and power of these systems require careful attention to safety measures, ethical guidelines, and governance frameworks to ensure beneficial outcomes for humanity.',
                'order' => 16,
                'active' => true
            ]
        ];

        foreach ($faqs as $faqData) {
            FAQ::create($faqData);
        }
    }
}