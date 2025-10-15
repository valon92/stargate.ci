<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OpenAIService
{
    private string $apiKey;
    private string $baseUrl;
    private array $defaultHeaders;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));
        $this->baseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');
        $this->defaultHeaders = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v2'
        ];
    }

    /**
     * Generate text using GPT models
     */
    public function generateText(string $prompt, array $options = []): array
    {
        try {
            $model = $options['model'] ?? 'gpt-4';
            $maxTokens = $options['max_tokens'] ?? 1000;
            $temperature = $options['temperature'] ?? 0.7;

            $response = Http::withHeaders($this->defaultHeaders)
                ->timeout(30)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => $model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => $maxTokens,
                    'temperature' => $temperature,
                    'stream' => false
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'content' => $data['choices'][0]['message']['content'] ?? '',
                    'usage' => $data['usage'] ?? [],
                    'model' => $data['model'] ?? $model
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Unknown error'),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate images using DALL-E
     */
    public function generateImage(string $prompt, array $options = []): array
    {
        try {
            $model = $options['model'] ?? 'dall-e-3';
            $size = $options['size'] ?? '1024x1024';
            $quality = $options['quality'] ?? 'standard';
            $style = $options['style'] ?? 'vivid';

            $response = Http::withHeaders($this->defaultHeaders)
                ->timeout(60)
                ->post("{$this->baseUrl}/images/generations", [
                    'model' => $model,
                    'prompt' => $prompt,
                    'n' => 1,
                    'size' => $size,
                    'quality' => $quality,
                    'style' => $style
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'image_url' => $data['data'][0]['url'] ?? '',
                    'revised_prompt' => $data['data'][0]['revised_prompt'] ?? $prompt
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Unknown error'),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI DALL-E Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Image generation service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Create embeddings for text
     */
    public function createEmbeddings(string $text, string $model = 'text-embedding-3-small'): array
    {
        try {
            $response = Http::withHeaders($this->defaultHeaders)
                ->timeout(30)
                ->post("{$this->baseUrl}/embeddings", [
                    'model' => $model,
                    'input' => $text,
                    'encoding_format' => 'float'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'embeddings' => $data['data'][0]['embedding'] ?? [],
                    'usage' => $data['usage'] ?? []
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Unknown error'),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI Embeddings Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Embeddings service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Moderate content for safety
     */
    public function moderateContent(string $text): array
    {
        try {
            $response = Http::withHeaders($this->defaultHeaders)
                ->timeout(15)
                ->post("{$this->baseUrl}/moderations", [
                    'input' => $text,
                    'model' => 'text-moderation-latest'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $result = $data['results'][0] ?? [];
                
                return [
                    'success' => true,
                    'flagged' => $result['flagged'] ?? false,
                    'categories' => $result['categories'] ?? [],
                    'category_scores' => $result['category_scores'] ?? []
                ];
            }

            return [
                'success' => false,
                'error' => $response->json('error.message', 'Unknown error'),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI Moderation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Content moderation service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Get available models
     */
    public function getModels(): array
    {
        try {
            $cacheKey = 'openai_models_' . md5($this->apiKey);
            
            return Cache::remember($cacheKey, 3600, function () {
                $response = Http::withHeaders($this->defaultHeaders)
                    ->timeout(15)
                    ->get("{$this->baseUrl}/models");

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'models' => $data['data'] ?? []
                    ];
                }

                return [
                    'success' => false,
                    'error' => $response->json('error.message', 'Unknown error'),
                    'status' => $response->status()
                ];
            });

        } catch (\Exception $e) {
            Log::error('OpenAI Models Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Models service temporarily unavailable',
                'exception' => $e->getMessage()
            ];
        }
    }

    /**
     * Analyze Stargate project data
     */
    public function analyzeStargateData(array $data): array
    {
        $prompt = "Analyze the following Stargate project data and provide insights:\n\n" . json_encode($data, JSON_PRETTY_PRINT);
        
        return $this->generateText($prompt, [
            'model' => 'gpt-4',
            'max_tokens' => 2000,
            'temperature' => 0.3
        ]);
    }

    /**
     * Generate Cristal Intelligence insights
     */
    public function generateCristalInsights(string $topic): array
    {
        $prompt = "Generate insights about Cristal Intelligence in relation to: {$topic}. Focus on transparency, precision, harmony, and evolution principles.";
        
        return $this->generateText($prompt, [
            'model' => 'gpt-4',
            'max_tokens' => 1500,
            'temperature' => 0.5
        ]);
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->apiKey !== 'your-openai-api-key-here';
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
                ->get("{$this->baseUrl}/models");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'OpenAI API connection successful',
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
