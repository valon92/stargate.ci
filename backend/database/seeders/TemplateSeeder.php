<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;
use App\Models\User;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $expertUser = User::where('email', 'michael@example.com')->first();

        $templates = [
            [
                'name' => 'AI Chatbot Template',
                'slug' => 'ai-chatbot-template',
                'description' => 'Complete AI chatbot implementation using Stargate infrastructure with natural language processing capabilities.',
                'icon' => '🤖',
                'category' => 'AI & Machine Learning',
                'difficulty' => 'intermediate',
                'estimated_time' => '2-3 weeks',
                'team_size' => '3-5 developers',
                'budget_range' => '$50K - $100K',
                'features' => [
                    'Natural Language Processing',
                    'Multi-language Support',
                    'Real-time Chat Interface',
                    'Integration APIs',
                    'Analytics Dashboard',
                    'Scalable Architecture'
                ],
                'architecture' => "Frontend: React/Vue.js\nBackend: Node.js/Python\nAI Engine: OpenAI GPT Integration\nDatabase: PostgreSQL/MongoDB\nCache: Redis\nMessage Queue: RabbitMQ\nAPI Gateway: Express.js/FastAPI\nMonitoring: Prometheus + Grafana",
                'implementation_steps' => [
                    'Set up Stargate infrastructure and configure AI services',
                    'Implement backend API with authentication and rate limiting',
                    'Create frontend chat interface with real-time messaging',
                    'Integrate OpenAI API for natural language processing',
                    'Implement conversation memory and context management',
                    'Add analytics and monitoring dashboards',
                    'Deploy and configure production environment'
                ],
                'requirements' => [
                    'technical' => [
                        'Stargate AI infrastructure access',
                        'OpenAI API key',
                        'Node.js/Python development environment',
                        'Database setup (PostgreSQL/MongoDB)',
                        'Redis for caching',
                        'Docker for containerization'
                    ],
                    'team' => [
                        'Full-stack developer (2-3)',
                        'AI/ML engineer (1)',
                        'DevOps engineer (1)',
                        'UI/UX designer (1)'
                    ]
                ],
                'is_featured' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id
            ],
            [
                'name' => 'Data Pipeline Template',
                'slug' => 'data-pipeline-template',
                'description' => 'End-to-end data processing pipeline for big data analytics using Stargate computing power.',
                'icon' => '📊',
                'category' => 'Data Analytics',
                'difficulty' => 'advanced',
                'estimated_time' => '4-6 weeks',
                'team_size' => '4-6 developers',
                'budget_range' => '$100K - $200K',
                'features' => [
                    'Real-time Data Processing',
                    'Big Data Analytics',
                    'Machine Learning Integration',
                    'Data Visualization',
                    'Scalable Architecture',
                    'Monitoring & Alerting'
                ],
                'architecture' => "Data Sources: APIs, Databases, Files\nIngestion: Apache Kafka\nProcessing: Apache Spark\nStorage: Data Lake (S3/HDFS)\nAnalytics: Stargate Computing\nVisualization: Grafana/D3.js\nOrchestration: Apache Airflow\nMonitoring: Prometheus + Grafana",
                'implementation_steps' => [
                    'Set up data ingestion layer with Apache Kafka',
                    'Configure Apache Spark for data processing',
                    'Implement data storage in data lake',
                    'Integrate Stargate computing for analytics',
                    'Create data visualization dashboards',
                    'Set up monitoring and alerting systems',
                    'Deploy and optimize for production'
                ],
                'requirements' => [
                    'technical' => [
                        'Stargate computing infrastructure',
                        'Apache Kafka cluster',
                        'Apache Spark environment',
                        'Data lake storage (S3/HDFS)',
                        'Monitoring tools (Prometheus/Grafana)',
                        'Container orchestration (Kubernetes)'
                    ],
                    'team' => [
                        'Data engineer (2-3)',
                        'Backend developer (1-2)',
                        'DevOps engineer (1)',
                        'Data scientist (1)',
                        'UI/UX designer (1)'
                    ]
                ],
                'is_featured' => true,
                'created_by' => $expertUser->id,
                'updated_by' => $expertUser->id
            ],
            [
                'name' => 'Enterprise AI Platform',
                'slug' => 'enterprise-ai-platform',
                'description' => 'Comprehensive enterprise-grade AI platform with multi-tenant architecture and advanced security.',
                'icon' => '🏢',
                'category' => 'Enterprise Solutions',
                'difficulty' => 'advanced',
                'estimated_time' => '3-4 months',
                'team_size' => '8-12 developers',
                'budget_range' => '$500K - $1M',
                'features' => [
                    'Multi-tenant Architecture',
                    'Advanced Security & Compliance',
                    'AI Model Management',
                    'Enterprise Integration',
                    'Scalable Infrastructure',
                    'Comprehensive Monitoring'
                ],
                'architecture' => "Frontend: React/Angular\nBackend: Microservices (Node.js/Python)\nAI Engine: Stargate AI Infrastructure\nDatabase: PostgreSQL + Redis\nMessage Queue: Apache Kafka\nAPI Gateway: Kong/Ambassador\nSecurity: OAuth2 + JWT\nMonitoring: ELK Stack + Prometheus",
                'implementation_steps' => [
                    'Design multi-tenant architecture',
                    'Implement authentication and authorization',
                    'Set up microservices infrastructure',
                    'Integrate Stargate AI capabilities',
                    'Implement enterprise security features',
                    'Create comprehensive monitoring system',
                    'Deploy and configure for enterprise scale'
                ],
                'requirements' => [
                    'technical' => [
                        'Stargate AI infrastructure',
                        'Enterprise security tools',
                        'Microservices framework',
                        'Container orchestration (Kubernetes)',
                        'Enterprise databases',
                        'Monitoring and logging tools'
                    ],
                    'team' => [
                        'Senior full-stack developers (4-5)',
                        'AI/ML engineers (2-3)',
                        'DevOps engineers (2)',
                        'Security engineer (1)',
                        'UI/UX designers (2)',
                        'Project manager (1)'
                    ]
                ],
                'is_featured' => true,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id
            ],
            [
                'name' => 'Startup MVP Template',
                'slug' => 'startup-mvp-template',
                'description' => 'Rapid MVP development template for startups with essential features and quick deployment.',
                'icon' => '🚀',
                'category' => 'Startup Solutions',
                'difficulty' => 'beginner',
                'estimated_time' => '2-4 weeks',
                'team_size' => '2-4 developers',
                'budget_range' => '$20K - $50K',
                'features' => [
                    'Rapid Development',
                    'Essential Features',
                    'Quick Deployment',
                    'Scalable Foundation',
                    'Cost Effective',
                    'Easy Maintenance'
                ],
                'architecture' => "Frontend: React/Vue.js\nBackend: Node.js/Python\nDatabase: PostgreSQL\nAuthentication: Auth0/Firebase\nHosting: Vercel/Netlify\nStorage: AWS S3\nMonitoring: Basic analytics",
                'implementation_steps' => [
                    'Set up development environment',
                    'Implement core features',
                    'Add authentication system',
                    'Create user interface',
                    'Set up database and APIs',
                    'Deploy to cloud platform',
                    'Configure monitoring and analytics'
                ],
                'requirements' => [
                    'technical' => [
                        'Modern web development stack',
                        'Cloud hosting platform',
                        'Database setup',
                        'Authentication service',
                        'Basic monitoring tools'
                    ],
                    'team' => [
                        'Full-stack developer (1-2)',
                        'Frontend developer (1)',
                        'UI/UX designer (1)'
                    ]
                ],
                'is_featured' => false,
                'created_by' => $expertUser->id,
                'updated_by' => $expertUser->id
            ],
            [
                'name' => 'IoT Analytics Template',
                'slug' => 'iot-analytics-template',
                'description' => 'Complete IoT solution with real-time analytics and edge computing capabilities.',
                'icon' => '🌐',
                'category' => 'IoT & Edge Computing',
                'difficulty' => 'intermediate',
                'estimated_time' => '6-8 weeks',
                'team_size' => '5-7 developers',
                'budget_range' => '$150K - $300K',
                'features' => [
                    'Real-time IoT Data Processing',
                    'Edge Computing',
                    'Time-series Analytics',
                    'Device Management',
                    'Predictive Maintenance',
                    'Scalable Architecture'
                ],
                'architecture' => "IoT Devices: Sensors, Actuators\nEdge: Edge Computing Nodes\nGateway: MQTT/CoAP Gateway\nProcessing: Apache Kafka + Spark\nStorage: InfluxDB + TimescaleDB\nAnalytics: Stargate Edge Computing\nVisualization: Grafana\nMonitoring: Prometheus",
                'implementation_steps' => [
                    'Set up IoT device connectivity',
                    'Implement edge computing nodes',
                    'Configure data ingestion pipeline',
                    'Set up time-series database',
                    'Integrate Stargate edge analytics',
                    'Create real-time dashboards',
                    'Deploy and optimize for edge computing performance'
                ],
                'requirements' => [
                    'technical' => [
                        'Stargate edge computing infrastructure',
                        'IoT protocols (MQTT, CoAP)',
                        'Time-series database (InfluxDB)',
                        'Streaming data processing',
                        'Edge computing frameworks',
                        'Real-time analytics tools'
                    ],
                    'team' => [
                        'IoT engineer (1-2)',
                        'Data engineer (1-2)',
                        'Backend developer (1)',
                        'DevOps engineer (1)',
                        'Data scientist (1)'
                    ]
                ],
                'is_featured' => false,
                'created_by' => $expertUser->id,
                'updated_by' => $expertUser->id
            ],
            [
                'name' => 'Cloud Migration Template',
                'slug' => 'cloud-migration-template',
                'description' => 'Comprehensive cloud migration strategy with automated deployment and monitoring.',
                'icon' => '☁️',
                'category' => 'Cloud Infrastructure',
                'difficulty' => 'advanced',
                'estimated_time' => '2-3 months',
                'team_size' => '6-10 developers',
                'budget_range' => '$200K - $400K',
                'features' => [
                    'Automated Migration',
                    'Zero Downtime',
                    'Cost Optimization',
                    'Security Compliance',
                    'Performance Monitoring',
                    'Disaster Recovery'
                ],
                'architecture' => "Source: On-premise Infrastructure\nMigration: Automated Tools\nTarget: Cloud Platform (AWS/Azure/GCP)\nOrchestration: Kubernetes\nMonitoring: CloudWatch/Azure Monitor\nSecurity: Cloud Security Tools\nBackup: Cloud Backup Services",
                'implementation_steps' => [
                    'Assess current infrastructure',
                    'Plan migration strategy',
                    'Set up cloud environment',
                    'Implement automated migration tools',
                    'Migrate applications and data',
                    'Configure monitoring and security',
                    'Optimize and validate performance'
                ],
                'requirements' => [
                    'technical' => [
                        'Cloud platform access (AWS/Azure/GCP)',
                        'Migration tools and scripts',
                        'Container orchestration (Kubernetes)',
                        'Monitoring and logging tools',
                        'Security and compliance tools',
                        'Backup and disaster recovery tools'
                    ],
                    'team' => [
                        'Cloud architect (1)',
                        'DevOps engineers (2-3)',
                        'Backend developers (2-3)',
                        'Security engineer (1)',
                        'Database administrator (1)',
                        'Project manager (1)'
                    ]
                ],
                'is_featured' => false,
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id
            ]
        ];

        foreach ($templates as $templateData) {
            Template::create($templateData);
        }
    }
}