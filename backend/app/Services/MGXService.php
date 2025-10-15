<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MGXService
{
    private ?string $apiKey;
    private string $baseUrl;
    private array $defaultHeaders;

    public function __construct()
    {
        $this->apiKey = config('services.mgx.api_key', env('MGX_API_KEY')) ?: null;
        $this->baseUrl = config('services.mgx.base_url', 'https://api.mgx.com/v1');
        $this->defaultHeaders = [
            'Authorization' => 'Bearer ' . ($this->apiKey ?? 'demo-key'),
            'Content-Type' => 'application/json',
            'X-MGX-Client' => 'stargate-ci'
        ];
    }

    /**
     * Get MGX platform data
     */
    public function getPlatformData(): array
    {
        try {
            $cacheKey = 'mgx_platform_data_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 3600, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/platform/data");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'platform_info' => $data['platform_info'] ?? [],
                        'capabilities' => $data['capabilities'] ?? [],
                        'services' => $data['services'] ?? [],
                        'infrastructure' => $data['infrastructure'] ?? [],
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
            Log::error('MGX Platform Data Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Platform data service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get AI and ML capabilities
     */
    public function getAICapabilities(): array
    {
        try {
            $cacheKey = 'mgx_ai_capabilities_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 1800, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/ai/capabilities");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'ai_models' => $data['ai_models'] ?? [],
                        'ml_algorithms' => $data['ml_algorithms'] ?? [],
                        'processing_capabilities' => $data['processing_capabilities'] ?? [],
                        'performance_metrics' => $data['performance_metrics'] ?? [],
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
            Log::error('MGX AI Capabilities Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'AI capabilities service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get Stargate project integration details
     */
    public function getStargateIntegration(): array
    {
        try {
            $cacheKey = 'mgx_stargate_integration_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 7200, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/projects/stargate/integration");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'project_name' => $data['project_name'] ?? 'Stargate',
                        'mgx_contribution' => $data['mgx_contribution'] ?? [],
                        'integration_points' => $data['integration_points'] ?? [],
                        'shared_resources' => $data['shared_resources'] ?? [],
                        'collaboration_areas' => $data['collaboration_areas'] ?? [],
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
            Log::error('MGX Stargate Integration Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Stargate integration service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        try {
            $cacheKey = 'mgx_performance_metrics_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 900, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/performance/metrics");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'metrics' => $data['metrics'] ?? [],
                        'throughput' => $data['throughput'] ?? 0,
                        'latency' => $data['latency'] ?? 0,
                        'efficiency' => $data['efficiency'] ?? 0,
                        'scalability' => $data['scalability'] ?? 0,
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
            Log::error('MGX Performance Metrics Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Performance metrics service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get innovation and research data
     */
    public function getInnovationResearch(): array
    {
        try {
            $cacheKey = 'mgx_innovation_research_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 3600, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/innovation/research");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'research_projects' => $data['research_projects'] ?? [],
                        'innovations' => $data['innovations'] ?? [],
                        'patents' => $data['patents'] ?? [],
                        'publications' => $data['publications'] ?? [],
                        'collaborations' => $data['collaborations'] ?? [],
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
            Log::error('MGX Innovation Research Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Innovation research service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get market position and strategy
     */
    public function getMarketStrategy(): array
    {
        try {
            $cacheKey = 'mgx_market_strategy_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 7200, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/market/strategy");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'market_position' => $data['market_position'] ?? [],
                        'strategy' => $data['strategy'] ?? [],
                        'competitive_advantages' => $data['competitive_advantages'] ?? [],
                        'growth_plans' => $data['growth_plans'] ?? [],
                        'partnerships' => $data['partnerships'] ?? [],
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
            Log::error('MGX Market Strategy Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Market strategy service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get sustainability and ESG data
     */
    public function getSustainabilityData(): array
    {
        try {
            $cacheKey = 'mgx_sustainability_data_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 3600, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(30)
                    ->get("{$this->baseUrl}/sustainability/data");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'esg_metrics' => $data['esg_metrics'] ?? [],
                        'carbon_footprint' => $data['carbon_footprint'] ?? [],
                        'sustainability_goals' => $data['sustainability_goals'] ?? [],
                        'green_technologies' => $data['green_technologies'] ?? [],
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
            Log::error('MGX Sustainability Data Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Sustainability data service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->apiKey !== 'your-mgx-api-key-here';
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
                    'message' => 'MGX API connection successful',
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
