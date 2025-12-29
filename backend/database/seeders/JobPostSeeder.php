<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPost;
use App\Models\Subscriber;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a subscriber for Stargate Project
        $stargateCompany = Subscriber::firstOrCreate(
            ['email' => 'stargate.project@example.com'],
            [
                'username' => 'stargate_project',
                'email' => 'stargate.project@example.com',
                'first_name' => 'Stargate',
                'last_name' => 'Project',
                'company' => 'Stargate Project - OpenAI, SoftBank & Arm',
                'country' => 'United States',
                'profession' => 'AI Infrastructure',
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        // Get or create OpenAI subscriber
        $openaiCompany = Subscriber::firstOrCreate(
            ['email' => 'careers@openai.com'],
            [
                'username' => 'openai_careers',
                'email' => 'careers@openai.com',
                'first_name' => 'OpenAI',
                'last_name' => 'Careers',
                'company' => 'OpenAI',
                'country' => 'United States',
                'profession' => 'AI Research',
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        // Get or create SoftBank subscriber
        $softbankCompany = Subscriber::firstOrCreate(
            ['email' => 'careers@softbank.com'],
            [
                'username' => 'softbank_careers',
                'email' => 'careers@softbank.com',
                'first_name' => 'SoftBank',
                'last_name' => 'Group',
                'company' => 'SoftBank Group',
                'country' => 'Japan',
                'profession' => 'Investment & Technology',
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        $jobPosts = [
            // Stargate Project Jobs
            [
                'subscriber_id' => $stargateCompany->id,
                'title' => 'Senior AI Infrastructure Engineer - Stargate Project',
                'description' => 'Join the revolutionary $500 billion Stargate Project as a Senior AI Infrastructure Engineer. You will be responsible for designing and implementing cutting-edge AI infrastructure systems that will power the next generation of artificial intelligence. This role involves working with state-of-the-art hardware, developing scalable distributed systems, and collaborating with world-class engineers from OpenAI, SoftBank, and Arm. You will work on the largest AI infrastructure project in history, contributing to systems that will shape the future of technology.',
                'requirements' => '• Master\'s degree or PhD in Computer Science, AI, or related field\n• 5+ years of experience in large-scale distributed systems\n• Strong background in AI/ML infrastructure\n• Experience with GPU clusters and high-performance computing\n• Proficiency in Python, C++, and CUDA\n• Knowledge of Kubernetes, Docker, and cloud infrastructure\n• Excellent problem-solving and communication skills',
                'location' => 'San Francisco, CA / Remote',
                'job_type' => 'full-time',
                'category' => 'ai',
                'experience_level' => 'senior',
                'salary_range' => '$200,000 - $350,000',
                'skills' => ['Python', 'C++', 'CUDA', 'Kubernetes', 'Docker', 'AI/ML', 'Distributed Systems', 'High-Performance Computing'],
                'benefits' => ['Health Insurance', 'Dental & Vision', '401(k) Matching', 'Stock Options', 'Flexible PTO', 'Remote Work', 'Learning Budget', 'Gym Membership'],
                'application_link' => 'https://stargate.ci/careers/ai-infrastructure-engineer',
                'application_deadline' => now()->addMonths(2)->toDateString(),
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'subscriber_id' => $stargateCompany->id,
                'title' => 'Machine Learning Research Scientist - Stargate Project',
                'description' => 'We are seeking a Machine Learning Research Scientist to join the Stargate Project research team. You will conduct groundbreaking research in AI systems, develop novel algorithms, and contribute to the development of the most advanced AI infrastructure in the world. This position offers the opportunity to work alongside leading researchers from OpenAI and collaborate on projects that will define the future of artificial intelligence. You will publish research papers, present at top-tier conferences, and contribute to open-source projects.',
                'requirements' => '• PhD in Machine Learning, Computer Science, or related field\n• Strong publication record in top-tier ML/AI conferences\n• Experience with large language models and transformer architectures\n• Proficiency in PyTorch, TensorFlow, or JAX\n• Strong mathematical background in optimization and statistics\n• Excellent research and analytical skills\n• Ability to work independently and in teams',
                'location' => 'San Francisco, CA / Remote',
                'job_type' => 'full-time',
                'category' => 'ai',
                'experience_level' => 'senior',
                'salary_range' => '$250,000 - $400,000',
                'skills' => ['Machine Learning', 'Deep Learning', 'PyTorch', 'TensorFlow', 'Research', 'NLP', 'Computer Vision', 'Statistics'],
                'benefits' => ['Health Insurance', 'Dental & Vision', '401(k) Matching', 'Stock Options', 'Research Budget', 'Conference Travel', 'Flexible PTO', 'Remote Work'],
                'application_link' => 'https://stargate.ci/careers/ml-research-scientist',
                'application_deadline' => now()->addMonths(2)->toDateString(),
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'subscriber_id' => $stargateCompany->id,
                'title' => 'DevOps Engineer - Stargate Infrastructure',
                'description' => 'Join our DevOps team to build and maintain the infrastructure that powers the Stargate Project. You will work on automating deployments, managing cloud infrastructure, ensuring system reliability, and optimizing performance at scale. This role involves working with cutting-edge technologies, managing petabytes of data, and ensuring 99.99% uptime for critical AI systems. You will collaborate with engineers across OpenAI, SoftBank, and Arm to deliver world-class infrastructure solutions.',
                'requirements' => '• Bachelor\'s degree in Computer Science or related field\n• 3+ years of DevOps/SRE experience\n• Strong experience with Kubernetes, Terraform, and cloud platforms (AWS, GCP, Azure)\n• Proficiency in Python, Bash, and infrastructure as code\n• Experience with monitoring and observability tools\n• Knowledge of CI/CD pipelines and automation\n• Strong troubleshooting and problem-solving skills',
                'location' => 'Remote / Hybrid',
                'job_type' => 'full-time',
                'category' => 'software',
                'experience_level' => 'mid',
                'salary_range' => '$150,000 - $220,000',
                'skills' => ['Kubernetes', 'Terraform', 'AWS', 'GCP', 'Docker', 'Python', 'Bash', 'CI/CD', 'Monitoring'],
                'benefits' => ['Health Insurance', 'Dental & Vision', '401(k) Matching', 'Stock Options', 'Flexible PTO', 'Remote Work', 'Home Office Stipend'],
                'application_link' => 'https://stargate.ci/careers/devops-engineer',
                'application_deadline' => now()->addMonths(1)->toDateString(),
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'subscriber_id' => $openaiCompany->id,
                'title' => 'AI Safety Engineer - Stargate Project',
                'description' => 'OpenAI is seeking an AI Safety Engineer to work on the Stargate Project, focusing on ensuring the safety, security, and ethical alignment of advanced AI systems. You will develop safety protocols, conduct risk assessments, and work on alignment research to ensure that the AI systems we build are beneficial for humanity. This role involves close collaboration with researchers, engineers, and policy experts to address one of the most important challenges in AI development.',
                'requirements' => '• Master\'s degree or PhD in AI Safety, Computer Science, or related field\n• Strong understanding of AI alignment and safety research\n• Experience with AI systems and their potential risks\n• Strong analytical and research skills\n• Ability to think critically about long-term AI impacts\n• Excellent communication skills for technical and non-technical audiences',
                'location' => 'San Francisco, CA',
                'job_type' => 'full-time',
                'category' => 'ai',
                'experience_level' => 'senior',
                'salary_range' => '$220,000 - $350,000',
                'skills' => ['AI Safety', 'AI Alignment', 'Research', 'Risk Assessment', 'Python', 'Machine Learning', 'Ethics'],
                'benefits' => ['Health Insurance', 'Dental & Vision', '401(k) Matching', 'Stock Options', 'Research Budget', 'Flexible PTO'],
                'application_link' => 'https://openai.com/careers/ai-safety-engineer',
                'application_deadline' => now()->addMonths(3)->toDateString(),
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'subscriber_id' => $softbankCompany->id,
                'title' => 'Investment Analyst - Stargate Project',
                'description' => 'SoftBank Group is seeking an Investment Analyst to work on the Stargate Project, focusing on strategic investments, partnerships, and financial planning for this groundbreaking AI infrastructure initiative. You will analyze market trends, evaluate investment opportunities, and support strategic decision-making for one of the largest technology investments in history. This role offers exposure to cutting-edge AI technology and the opportunity to work with leading technology companies.',
                'requirements' => '• MBA or Master\'s degree in Finance, Business, or related field\n• 2+ years of experience in investment banking, private equity, or venture capital\n• Strong analytical and financial modeling skills\n• Interest in AI and technology investments\n• Excellent communication and presentation skills\n• Ability to work in a fast-paced, dynamic environment',
                'location' => 'Tokyo, Japan / New York, NY',
                'job_type' => 'full-time',
                'category' => 'software',
                'experience_level' => 'mid',
                'salary_range' => '$120,000 - $180,000',
                'skills' => ['Financial Analysis', 'Investment Banking', 'Financial Modeling', 'Strategic Planning', 'Excel', 'PowerPoint'],
                'benefits' => ['Health Insurance', 'Dental & Vision', 'Retirement Plan', 'Stock Options', 'Flexible PTO', 'Travel Opportunities'],
                'application_link' => 'https://group.softbank/careers/investment-analyst',
                'application_deadline' => now()->addMonths(2)->toDateString(),
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'subscriber_id' => $stargateCompany->id,
                'title' => 'Data Engineer - Stargate Project',
                'description' => 'We are looking for a Data Engineer to join the Stargate Project team, responsible for building and maintaining data pipelines that process petabytes of information for AI training and inference. You will work on designing scalable data architectures, optimizing data processing workflows, and ensuring data quality and reliability. This role involves working with massive datasets, cutting-edge data processing technologies, and collaborating with ML engineers and researchers to support AI development.',
                'requirements' => '• Bachelor\'s degree in Computer Science, Data Engineering, or related field\n• 3+ years of experience in data engineering\n• Strong experience with Spark, Airflow, and data processing frameworks\n• Proficiency in Python, SQL, and data warehousing\n• Experience with cloud data platforms (Snowflake, BigQuery, Redshift)\n• Knowledge of data modeling and ETL processes\n• Strong problem-solving and optimization skills',
                'location' => 'Remote / San Francisco, CA',
                'job_type' => 'full-time',
                'category' => 'data-science',
                'experience_level' => 'mid',
                'salary_range' => '$160,000 - $240,000',
                'skills' => ['Python', 'SQL', 'Spark', 'Airflow', 'Data Warehousing', 'ETL', 'BigQuery', 'Snowflake'],
                'benefits' => ['Health Insurance', 'Dental & Vision', '401(k) Matching', 'Stock Options', 'Flexible PTO', 'Remote Work', 'Learning Budget'],
                'application_link' => 'https://stargate.ci/careers/data-engineer',
                'application_deadline' => now()->addMonths(2)->toDateString(),
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'subscriber_id' => $stargateCompany->id,
                'title' => 'Robotics Engineer - Stargate AI Integration',
                'description' => 'Join our robotics team to integrate advanced AI systems from the Stargate Project into robotic platforms. You will work on developing autonomous systems, integrating AI models with robotic hardware, and building robots that leverage the power of Stargate AI infrastructure. This role involves working with cutting-edge robotics hardware, developing control systems, and collaborating with AI researchers to bring advanced AI capabilities to physical systems.',
                'requirements' => '• Master\'s degree or PhD in Robotics, Mechanical Engineering, or related field\n• 3+ years of experience in robotics development\n• Strong background in ROS (Robot Operating System)\n• Experience with computer vision and sensor fusion\n• Proficiency in C++, Python, and robotics frameworks\n• Knowledge of AI/ML integration with robotics\n• Hands-on experience with robotic hardware',
                'location' => 'Boston, MA / Remote',
                'job_type' => 'full-time',
                'category' => 'robotics',
                'experience_level' => 'mid',
                'salary_range' => '$140,000 - $220,000',
                'skills' => ['ROS', 'C++', 'Python', 'Robotics', 'Computer Vision', 'Sensor Fusion', 'AI/ML', 'Hardware Integration'],
                'benefits' => ['Health Insurance', 'Dental & Vision', '401(k) Matching', 'Stock Options', 'Flexible PTO', 'Lab Access', 'Hardware Budget'],
                'application_link' => 'https://stargate.ci/careers/robotics-engineer',
                'application_deadline' => now()->addMonths(2)->toDateString(),
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'subscriber_id' => $stargateCompany->id,
                'title' => 'Security Engineer - Stargate Infrastructure',
                'description' => 'We are seeking a Security Engineer to protect the critical infrastructure of the Stargate Project. You will be responsible for securing AI systems, protecting sensitive data, implementing security protocols, and ensuring compliance with security standards. This role involves working on one of the most valuable technology assets in the world, requiring the highest levels of security expertise and vigilance. You will work with security teams across OpenAI, SoftBank, and Arm to maintain world-class security standards.',
                'requirements' => '• Bachelor\'s degree in Computer Science, Cybersecurity, or related field\n• 4+ years of experience in cybersecurity\n• Strong knowledge of cloud security, network security, and application security\n• Experience with security tools and frameworks\n• Knowledge of compliance standards (SOC 2, ISO 27001)\n• Strong analytical and problem-solving skills\n• Security certifications (CISSP, CISM, etc.) preferred',
                'location' => 'Remote / San Francisco, CA',
                'job_type' => 'full-time',
                'category' => 'software',
                'experience_level' => 'senior',
                'salary_range' => '$180,000 - $280,000',
                'skills' => ['Cybersecurity', 'Cloud Security', 'Network Security', 'Security Tools', 'Compliance', 'Python', 'Security Analysis'],
                'benefits' => ['Health Insurance', 'Dental & Vision', '401(k) Matching', 'Stock Options', 'Flexible PTO', 'Security Training Budget'],
                'application_link' => 'https://stargate.ci/careers/security-engineer',
                'application_deadline' => now()->addMonths(2)->toDateString(),
                'status' => 'published',
                'is_featured' => false,
            ],
        ];

        foreach ($jobPosts as $jobData) {
            JobPost::create([
                'company_id' => $jobData['subscriber_id'],
                'title' => $jobData['title'],
                'description' => $jobData['description'],
                'requirements' => $jobData['requirements'],
                'location' => $jobData['location'],
                'job_type' => $jobData['job_type'],
                'category' => $jobData['category'],
                'experience_level' => $jobData['experience_level'],
                'salary_range' => $jobData['salary_range'],
                'skills' => $jobData['skills'],
                'benefits' => $jobData['benefits'],
                'application_url' => $jobData['application_link'],
                'application_deadline' => $jobData['application_deadline'],
                'status' => $jobData['status'],
                'is_featured' => $jobData['is_featured'],
                'is_active' => true,
                'views_count' => rand(10, 500),
            ]);
        }
    }
}

