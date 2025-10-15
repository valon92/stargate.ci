<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SearchQuery;

class SearchQuerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $searchQueries = [
            [
                'query' => 'Stargate project',
                'type' => 'all',
                'category' => null,
                'count' => 150,
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'query' => 'Cristal Intelligence',
                'type' => 'articles',
                'category' => 'cristal-intelligence',
                'count' => 89,
                'ip_address' => '192.168.1.2',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'
            ],
            [
                'query' => 'AI ethics',
                'type' => 'all',
                'category' => 'ai-ethics-governance',
                'count' => 76,
                'ip_address' => '192.168.1.3',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36'
            ],
            [
                'query' => 'OpenAI',
                'type' => 'articles',
                'category' => 'stargate-project',
                'count' => 134,
                'ip_address' => '192.168.1.4',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'query' => 'SoftBank',
                'type' => 'all',
                'category' => 'stargate-project',
                'count' => 67,
                'ip_address' => '192.168.1.5',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'
            ],
            [
                'query' => 'Arm technology',
                'type' => 'articles',
                'category' => 'technology-infrastructure',
                'count' => 45,
                'ip_address' => '192.168.1.6',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36'
            ],
            [
                'query' => 'AI infrastructure',
                'type' => 'all',
                'category' => 'technology-infrastructure',
                'count' => 92,
                'ip_address' => '192.168.1.7',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'query' => 'machine learning',
                'type' => 'tutorials',
                'category' => 'research-development',
                'count' => 58,
                'ip_address' => '192.168.1.8',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'
            ],
            [
                'query' => 'neural networks',
                'type' => 'all',
                'category' => 'research-development',
                'count' => 43,
                'ip_address' => '192.168.1.9',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36'
            ],
            [
                'query' => 'AI governance',
                'type' => 'articles',
                'category' => 'ai-ethics-governance',
                'count' => 38,
                'ip_address' => '192.168.1.10',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'query' => 'responsible AI',
                'type' => 'all',
                'category' => 'ai-ethics-governance',
                'count' => 52,
                'ip_address' => '192.168.1.11',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'
            ],
            [
                'query' => 'data centers',
                'type' => 'articles',
                'category' => 'technology-infrastructure',
                'count' => 29,
                'ip_address' => '192.168.1.12',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36'
            ],
            [
                'query' => 'AI safety',
                'type' => 'all',
                'category' => 'ai-ethics-governance',
                'count' => 41,
                'ip_address' => '192.168.1.13',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'query' => 'transparency in AI',
                'type' => 'articles',
                'category' => 'cristal-intelligence',
                'count' => 35,
                'ip_address' => '192.168.1.14',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'
            ],
            [
                'query' => 'AI applications',
                'type' => 'all',
                'category' => 'industry-applications',
                'count' => 63,
                'ip_address' => '192.168.1.15',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36'
            ],
            [
                'query' => 'artificial intelligence',
                'type' => 'all',
                'category' => null,
                'count' => 187,
                'ip_address' => '192.168.1.16',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'query' => 'deep learning',
                'type' => 'tutorials',
                'category' => 'research-development',
                'count' => 47,
                'ip_address' => '192.168.1.17',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'
            ],
            [
                'query' => 'AI research',
                'type' => 'all',
                'category' => 'research-development',
                'count' => 71,
                'ip_address' => '192.168.1.18',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36'
            ],
            [
                'query' => 'technology innovation',
                'type' => 'all',
                'category' => 'research-development',
                'count' => 33,
                'ip_address' => '192.168.1.19',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'query' => 'AI development',
                'type' => 'all',
                'category' => null,
                'count' => 95,
                'ip_address' => '192.168.1.20',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'
            ]
        ];

        foreach ($searchQueries as $queryData) {
            SearchQuery::create($queryData);
        }
    }
}
