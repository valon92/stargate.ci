<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Subscriber;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some subscribers to use as product creators
        $subscribers = Subscriber::limit(5)->get();
        
        if ($subscribers->isEmpty()) {
            $this->command->warn('No subscribers found. Please run SubscriberSeeder first.');
            return;
        }

        $products = [
            // API Products
            [
                'name' => 'Stargate REST API',
                'description' => 'Comprehensive RESTful API for accessing Stargate Project data, events, and resources. Built for developers who want to integrate Stargate capabilities into their applications. Features real-time updates, rate limiting, and comprehensive authentication.',
                'category' => 'api',
                'icon' => '🚀',
                'documentation_url' => 'https://docs.stargate.ci/api',
                'github_url' => 'https://github.com/stargate/api',
                'demo_url' => 'https://api-demo.stargate.ci',
                'features' => ['RESTful API', 'Real-time updates', 'Rate limiting', 'Authentication', 'Webhooks'],
                'is_new' => false,
                'is_popular' => true,
                'downloads_count' => 125000,
                'stars_count' => 3200,
                'users_count' => 5000,
                'status' => 'published',
            ],
            [
                'name' => 'Cristal Intelligence GraphQL API',
                'description' => 'Modern GraphQL API for querying Cristal Intelligence data with flexible queries and real-time subscriptions. Perfect for building dynamic dashboards and analytics tools.',
                'category' => 'api',
                'icon' => '💎',
                'documentation_url' => 'https://docs.stargate.ci/graphql',
                'github_url' => 'https://github.com/stargate/graphql-api',
                'demo_url' => 'https://graphql.stargate.ci',
                'features' => ['GraphQL', 'Real-time subscriptions', 'Type-safe queries', 'Schema introspection'],
                'is_new' => true,
                'is_popular' => false,
                'downloads_count' => 45000,
                'stars_count' => 850,
                'users_count' => 1200,
                'status' => 'published',
            ],

            // SDK Products
            [
                'name' => 'Cristal Intelligence JavaScript SDK',
                'description' => 'Official JavaScript/TypeScript SDK for integrating Cristal Intelligence capabilities into your web applications. Includes React hooks, Vue composables, and vanilla JS support.',
                'category' => 'sdk',
                'icon' => '📦',
                'documentation_url' => 'https://docs.stargate.ci/sdk/javascript',
                'github_url' => 'https://github.com/stargate/js-sdk',
                'demo_url' => 'https://sdk-demo.stargate.ci',
                'features' => ['TypeScript support', 'React hooks', 'Vue composables', 'Tree-shakeable', 'Browser & Node.js'],
                'is_new' => false,
                'is_popular' => true,
                'downloads_count' => 89000,
                'stars_count' => 1200,
                'users_count' => 3200,
                'status' => 'published',
            ],
            [
                'name' => 'Stargate Python SDK',
                'description' => 'Python SDK for building AI applications with Stargate Project. Includes async support, type hints, and comprehensive documentation. Perfect for data science and ML workflows.',
                'category' => 'sdk',
                'icon' => '🐍',
                'documentation_url' => 'https://docs.stargate.ci/sdk/python',
                'github_url' => 'https://github.com/stargate/python-sdk',
                'demo_url' => null,
                'features' => ['Async/await support', 'Type hints', 'Jupyter notebooks', 'Data science tools'],
                'is_new' => false,
                'is_popular' => true,
                'downloads_count' => 67000,
                'stars_count' => 950,
                'users_count' => 2100,
                'status' => 'published',
            ],
            [
                'name' => 'Stargate Go SDK',
                'description' => 'High-performance Go SDK for building scalable applications with Stargate. Designed for microservices and cloud-native architectures.',
                'category' => 'sdk',
                'icon' => '🐹',
                'documentation_url' => 'https://docs.stargate.ci/sdk/go',
                'github_url' => 'https://github.com/stargate/go-sdk',
                'demo_url' => null,
                'features' => ['High performance', 'Concurrent safe', 'gRPC support', 'Context support'],
                'is_new' => true,
                'is_popular' => false,
                'downloads_count' => 28000,
                'stars_count' => 420,
                'users_count' => 850,
                'status' => 'published',
            ],

            // Tools Products
            [
                'name' => 'Stargate CLI',
                'description' => 'Command-line interface for managing Stargate resources, deploying applications, and interacting with the Stargate ecosystem from your terminal. Cross-platform support with auto-completion.',
                'category' => 'tools',
                'icon' => '⚡',
                'documentation_url' => 'https://docs.stargate.ci/cli',
                'github_url' => 'https://github.com/stargate/cli',
                'demo_url' => null,
                'features' => ['Cross-platform', 'Interactive mode', 'Plugin system', 'Auto-completion', 'Shell scripts'],
                'is_new' => false,
                'is_popular' => true,
                'downloads_count' => 45000,
                'stars_count' => 680,
                'users_count' => 1800,
                'status' => 'published',
            ],
            [
                'name' => 'Stargate Data Pipeline Builder',
                'description' => 'Visual tool for building, processing, and analyzing data pipelines within the Stargate ecosystem. ETL pipelines, data validation, and real-time monitoring.',
                'category' => 'tools',
                'icon' => '🔧',
                'documentation_url' => 'https://docs.stargate.ci/pipeline',
                'github_url' => 'https://github.com/stargate/pipeline-tools',
                'demo_url' => 'https://pipeline.stargate.ci',
                'features' => ['Visual editor', 'Real-time processing', 'Error handling', 'Monitoring dashboard', 'Data validation'],
                'is_new' => false,
                'is_popular' => false,
                'downloads_count' => 38000,
                'stars_count' => 520,
                'users_count' => 2100,
                'status' => 'published',
            ],
            [
                'name' => 'Cristal Intelligence Model Trainer',
                'description' => 'Advanced tool for training and fine-tuning AI models using Cristal Intelligence principles. Includes experiment tracking, hyperparameter optimization, and model versioning.',
                'category' => 'tools',
                'icon' => '🧠',
                'documentation_url' => 'https://docs.stargate.ci/trainer',
                'github_url' => 'https://github.com/stargate/model-trainer',
                'demo_url' => null,
                'features' => ['Experiment tracking', 'Hyperparameter tuning', 'Model versioning', 'Distributed training'],
                'is_new' => true,
                'is_popular' => false,
                'downloads_count' => 15000,
                'stars_count' => 280,
                'users_count' => 650,
                'status' => 'published',
            ],

            // Cloud Services
            [
                'name' => 'Stargate Cloud Platform',
                'description' => 'Managed cloud infrastructure for running Stargate applications. Scalable, secure, and optimized for AI workloads. Global CDN, auto-scaling, and 99.9% uptime SLA.',
                'category' => 'cloud',
                'icon' => '☁️',
                'documentation_url' => 'https://docs.stargate.ci/cloud',
                'github_url' => null,
                'demo_url' => 'https://cloud.stargate.ci',
                'features' => ['Auto-scaling', 'Global CDN', 'DDoS protection', '99.9% uptime', 'Multi-region'],
                'is_new' => false,
                'is_popular' => true,
                'downloads_count' => 0,
                'stars_count' => 0,
                'users_count' => 12000,
                'status' => 'published',
            ],
            [
                'name' => 'AI Model Hub',
                'description' => 'Centralized repository of pre-trained AI models from the Stargate Project. Access, download, and deploy models for your applications. Includes version control and performance metrics.',
                'category' => 'cloud',
                'icon' => '📦',
                'documentation_url' => 'https://docs.stargate.ci/models',
                'github_url' => null,
                'demo_url' => 'https://models.stargate.ci',
                'features' => ['Model marketplace', 'Version control', 'Deployment tools', 'Performance metrics', 'Model registry'],
                'is_new' => true,
                'is_popular' => true,
                'downloads_count' => 250000,
                'stars_count' => 0,
                'users_count' => 8500,
                'status' => 'published',
            ],

            // Documentation
            [
                'name' => 'Stargate Documentation Portal',
                'description' => 'Comprehensive documentation covering all aspects of the Stargate Project, from getting started guides to advanced topics and best practices. Includes interactive tutorials and API reference.',
                'category' => 'documentation',
                'icon' => '📚',
                'documentation_url' => 'https://docs.stargate.ci',
                'github_url' => 'https://github.com/stargate/docs',
                'demo_url' => 'https://docs.stargate.ci',
                'features' => ['Search functionality', 'Code examples', 'Interactive tutorials', 'API reference', 'Versioned docs'],
                'is_new' => false,
                'is_popular' => false,
                'downloads_count' => 0,
                'stars_count' => 450,
                'users_count' => 15000,
                'status' => 'published',
            ],

            // Libraries
            [
                'name' => 'React Stargate',
                'description' => 'React components and hooks for building Stargate-powered applications. Pre-built UI components, state management, and utilities. Fully typed with TypeScript.',
                'category' => 'libraries',
                'icon' => '⚛️',
                'documentation_url' => 'https://docs.stargate.ci/react',
                'github_url' => 'https://github.com/stargate/react-stargate',
                'demo_url' => 'https://react.stargate.ci',
                'features' => ['TypeScript support', 'Hooks API', 'Component library', 'Tree-shakeable', 'SSR ready'],
                'is_new' => false,
                'is_popular' => true,
                'downloads_count' => 67000,
                'stars_count' => 850,
                'users_count' => 2400,
                'status' => 'published',
            ],
            [
                'name' => 'Vue Stargate',
                'description' => 'Vue.js composables and components for integrating Stargate functionality into Vue applications. Built with Vue 3 Composition API and TypeScript.',
                'category' => 'libraries',
                'icon' => '🟢',
                'documentation_url' => 'https://docs.stargate.ci/vue',
                'github_url' => 'https://github.com/stargate/vue-stargate',
                'demo_url' => 'https://vue.stargate.ci',
                'features' => ['Composition API', 'TypeScript support', 'SSR ready', 'Plugin system', 'Auto-imports'],
                'is_new' => false,
                'is_popular' => false,
                'downloads_count' => 42000,
                'stars_count' => 520,
                'users_count' => 1500,
                'status' => 'published',
            ],
            [
                'name' => 'Stargate Angular Library',
                'description' => 'Angular library for building Stargate-powered applications. Includes services, directives, and components following Angular best practices.',
                'category' => 'libraries',
                'icon' => '🅰️',
                'documentation_url' => 'https://docs.stargate.ci/angular',
                'github_url' => 'https://github.com/stargate/angular-stargate',
                'demo_url' => null,
                'features' => ['Angular services', 'Directives', 'Components', 'RxJS integration', 'AOT compatible'],
                'is_new' => true,
                'is_popular' => false,
                'downloads_count' => 18000,
                'stars_count' => 320,
                'users_count' => 750,
                'status' => 'published',
            ],
        ];

        foreach ($products as $index => $productData) {
            // Assign products to different subscribers
            $subscriber = $subscribers[$index % $subscribers->count()];
            
            Product::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($productData['name'])],
                array_merge($productData, [
                    'subscriber_id' => $subscriber->id,
                ])
            );
        }

        $this->command->info('Created ' . count($products) . ' products successfully!');
    }
}