<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SoftBankService
{
    private ?string $apiKey;
    private string $baseUrl;
    private array $defaultHeaders;

    public function __construct()
    {
        $this->apiKey = config('services.softbank.api_key', env('SOFTBANK_API_KEY')) ?: null;
        $this->baseUrl = config('services.softbank.base_url', 'https://api.softbank.com/v1');
        $this->defaultHeaders = [
            'Authorization' => 'Bearer ' . ($this->apiKey ?? 'demo-key'),
            'Content-Type' => 'application/json',
            'X-API-Version' => '2024-01'
        ];
    }

    /**
     * Get investment portfolio data
     */
    public function getInvestmentPortfolio(): array
    {
        try {
            $cacheKey = 'softbank_portfolio_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 1800, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/investments/portfolio");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'portfolio' => $data['data'] ?? [],
                        'total_value' => $data['total_value'] ?? 0,
                        'last_updated' => $data['last_updated'] ?? now()->toISOString()
                    ];
                }

                return [
                    'success' => false,
                    'error' => $response->json('error.message', 'Unknown error'),
                    'status' => $response->status()
                ];
            });

        } catch (\Exception $e) {
            Log::error('SoftBank Portfolio Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Portfolio service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get AI investment data
     */
    public function getAIInvestments(): array
    {
        try {
            $cacheKey = 'softbank_ai_investments_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 3600, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/investments/ai");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'ai_investments' => $data['data'] ?? [],
                        'total_ai_investment' => $data['total_investment'] ?? 0,
                        'stargate_investment' => $data['stargate_investment'] ?? 0,
                        'last_updated' => $data['last_updated'] ?? now()->toISOString()
                    ];
                }

                return [
                    'success' => false,
                    'error' => $response->json('error.message', 'Unknown error'),
                    'status' => $response->status()
                ];
            });

        } catch (\Exception $e) {
            Log::error('SoftBank AI Investments Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'AI investments service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get Stargate project funding details
     */
    public function getStargateFunding(): array
    {
        try {
            $cacheKey = 'softbank_stargate_funding_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 7200, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/projects/stargate/funding");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'project_name' => $data['project_name'] ?? 'Stargate',
                        'total_funding' => $data['total_funding'] ?? 0,
                        'softbank_contribution' => $data['softbank_contribution'] ?? 0,
                        'funding_phases' => $data['funding_phases'] ?? [],
                        'timeline' => $data['timeline'] ?? [],
                        'partners' => $data['partners'] ?? [],
                        'last_updated' => $data['last_updated'] ?? now()->toISOString()
                    ];
                }

                return [
                    'success' => false,
                    'error' => $response->json('error.message', 'Unknown error'),
                    'status' => $response->status()
                ];
            });

        } catch (\Exception $e) {
            Log::error('SoftBank Stargate Funding Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Stargate funding service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get market analysis
     */
    public function getMarketAnalysis(string $sector = 'ai'): array
    {
        try {
            $cacheKey = "softbank_market_analysis_{$sector}_" . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 1800, function () use ($sector) {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/market/analysis", [
                        'sector' => $sector,
                        'timeframe' => '12m'
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'sector' => $sector,
                        'analysis' => $data['analysis'] ?? [],
                        'trends' => $data['trends'] ?? [],
                        'forecasts' => $data['forecasts'] ?? [],
                        'key_players' => $data['key_players'] ?? [],
                        'last_updated' => $data['last_updated'] ?? now()->toISOString()
                    ];
                }

                return [
                    'success' => false,
                    'error' => $response->json('error.message', 'Unknown error'),
                    'status' => $response->status()
                ];
            });

        } catch (\Exception $e) {
            Log::error('SoftBank Market Analysis Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Market analysis service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get investment performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        try {
            $cacheKey = 'softbank_performance_metrics_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 900, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/performance/metrics");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'metrics' => $data['metrics'] ?? [],
                        'roi' => $data['roi'] ?? 0,
                        'growth_rate' => $data['growth_rate'] ?? 0,
                        'risk_score' => $data['risk_score'] ?? 0,
                        'last_updated' => $data['last_updated'] ?? now()->toISOString()
                    ];
                }

                return [
                    'success' => false,
                    'error' => $response->json('error.message', 'Unknown error'),
                    'status' => $response->status()
                ];
            });

        } catch (\Exception $e) {
            Log::error('SoftBank Performance Metrics Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Performance metrics service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get news and updates
     */
    public function getNewsUpdates(int $limit = 10): array
    {
        try {
            $cacheKey = "softbank_news_{$limit}_" . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 600, function () use ($limit) {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/news", [
                        'limit' => $limit,
                        'category' => 'ai,investment,stargate'
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'news' => $data['data'] ?? [],
                        'total_count' => $data['total_count'] ?? 0,
                        'last_updated' => $data['last_updated'] ?? now()->toISOString()
                    ];
                }

                return [
                    'success' => false,
                    'error' => $response->json('error.message', 'Unknown error'),
                    'status' => $response->status()
                ];
            });

        } catch (\Exception $e) {
            Log::error('SoftBank News Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'News service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->apiKey !== 'your-softbank-api-key-here';
    }

    /**
     * Test API connection
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error' => 'API key not configured'
            ];
        }

        try {
            $response = Http::withHeaders($this->defaultHeaders)
                ->timeout(10)
                ->get("{$this->baseUrl}/health");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'SoftBank API connection successful',
                    'status' => $response->status()
                ];
            }

            return [
                'success' => false,
                'error' => 'API connection failed',
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection test failed: ' . $e->getMessage()
            ];
        }
    }
}
