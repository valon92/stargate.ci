<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            [
                'question' => 'What is the Stargate project?',
                'answer' => 'The Stargate project is a large-scale AI infrastructure initiative involving SoftBank, OpenAI, and Arm. It represents a significant investment in advanced computing capabilities and AI research, aimed at pushing the boundaries of artificial intelligence and machine learning technologies.',
                'order' => 1,
                'active' => true,
            ],
            [
                'question' => 'What is Crystal Intelligence?',
                'answer' => 'Crystal Intelligence is an AI company that\'s part of the Stargate project ecosystem. It focuses on developing cutting-edge artificial intelligence solutions and contributes to the overall technological advancement of the project.',
                'order' => 2,
                'active' => true,
            ],
            [
                'question' => 'Is this the official Stargate project website?',
                'answer' => 'No, this is an independent educational platform. We are not officially affiliated with the Stargate project, SoftBank, OpenAI, or Arm. Our mission is to provide educational content and information based on publicly available sources about these technologies and organizations.',
                'order' => 3,
                'active' => true,
            ],
            [
                'question' => 'What technologies are involved in Stargate?',
                'answer' => 'The Stargate project involves several key technologies including artificial intelligence, machine learning, cloud computing, advanced data processing, and high-performance computing infrastructure. These technologies work together to create a comprehensive AI ecosystem.',
                'order' => 4,
                'active' => true,
            ],
            [
                'question' => 'How can I learn more about AI and these technologies?',
                'answer' => 'You can explore our Insights section for educational articles, read about our partners and their contributions, or check out our Services section to understand the different technologies involved. We also recommend following official sources and academic publications for the most up-to-date information.',
                'order' => 5,
                'active' => true,
            ],
            [
                'question' => 'What is the investment scale of the Stargate project?',
                'answer' => 'While specific details are not publicly disclosed, the Stargate project represents one of the largest investments in AI infrastructure, with estimates suggesting investments in the range of $100 billion or more. This reflects the scale and ambition of the project.',
                'order' => 6,
                'active' => true,
            ],
            [
                'question' => 'How does this project impact global technology?',
                'answer' => 'The Stargate project has the potential to significantly advance AI capabilities globally. By combining the expertise of leading technology companies, it aims to accelerate AI research and development, potentially leading to breakthroughs in various fields including healthcare, finance, and scientific research.',
                'order' => 7,
                'active' => true,
            ],
            [
                'question' => 'Are there any ethical considerations discussed?',
                'answer' => 'Yes, ethical considerations are an important part of AI development. Organizations like OpenAI have publicly committed to developing AI safely and ensuring it benefits all of humanity. We discuss these topics in our educational content to promote awareness of responsible AI development.',
                'order' => 8,
                'active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            FAQ::create($faq);
        }
    }
}
