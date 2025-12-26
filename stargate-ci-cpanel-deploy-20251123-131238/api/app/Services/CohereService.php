<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CohereService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('COHERE_API_KEY', '');
        $this->baseUrl = env('COHERE_BASE_URL', 'https://api.cohere.ai/v1');
    }

    /**
     * Get events from Cohere API
     */
    public function getEvents(): array
    {
        try {
            $cacheKey = 'cohere_events_' . date('Y-m-d-H');
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                return $cached;
            }

            // Use Cohere API to generate events
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/generate', [
                'model' => 'command',
                'prompt' => 'Generate 3-5 upcoming tech events related to AI, machine learning, and technology conferences. Return only valid JSON array with events containing: title, description, category (stargate/cristal/conferences/meetings/announcements), type (conference/meeting/announcement/workshop/video), event_date (YYYY-MM-DD), event_time (HH:MM:SS), location, organizer, icon (emoji), registration_url (use real URLs like https://cohere.ai/events/[event-name] or https://eventbrite.com/e/[event-name]), video_url (optional). Focus on enterprise AI and business applications.',
                'max_tokens' => 2000,
                'temperature' => 0.7,
                'stop_sequences' => ['\n\n']
            ]);

            if ($response->successful()) {
                $content = $response->json()['generations'][0]['text'];
                
                // Try to extract JSON from the response
                if (preg_match('/\[.*\]/s', $content, $matches)) {
                    $events = json_decode($matches[0], true);
                    
                    if (is_array($events)) {
                        // Add source and metadata
                        foreach ($events as &$event) {
                            $event['source'] = 'cohere';
                            $event['metadata'] = [
                                'api_source' => 'cohere',
                                'generated_at' => now()->toISOString(),
                                'model' => 'command'
                            ];
                        }
                        
                        Cache::put($cacheKey, $events, 3600); // Cache for 1 hour
                        return $events;
                    }
                }
            }

            Log::error('Cohere API Error: ' . $response->body());
            return $this->getFallbackEvents();

        } catch (\Exception $e) {
            Log::error('Cohere Service Error: ' . $e->getMessage());
            return $this->getFallbackEvents();
        }
    }

    /**
     * Get fallback events when API fails
     */
    private function getFallbackEvents(): array
    {
        return [
            [
                'external_id' => 'cohere-enterprise-ai-summit',
                'title' => 'Cohere Enterprise AI Summit',
                'description' => 'Discover how enterprise AI is transforming business operations with Cohere\'s language models.',
                'category' => 'conferences',
                'type' => 'conference',
                'event_date' => '2025-04-05',
                'event_time' => '09:00:00',
                'location' => 'Toronto, Canada',
                'organizer' => 'Cohere AI',
                'icon' => '🏢',
                'registration_url' => 'https://cohere.ai/enterprise-summit',
                'source' => 'cohere',
                'metadata' => [
                    'api_source' => 'cohere',
                    'event_type' => 'enterprise_summit',
                    'focus' => 'business_ai'
                ]
            ],
            [
                'external_id' => 'cohere-language-models-workshop',
                'title' => 'Advanced Language Models Workshop',
                'description' => 'Hands-on workshop on building and deploying large language models for enterprise applications.',
                'category' => 'conferences',
                'type' => 'workshop',
                'event_date' => '2025-04-10',
                'event_time' => '13:00:00',
                'location' => 'Virtual Event',
                'organizer' => 'Cohere AI',
                'icon' => '📚',
                'registration_url' => 'https://cohere.ai/lm-workshop',
                'source' => 'cohere',
                'metadata' => [
                    'api_source' => 'cohere',
                    'event_type' => 'technical_workshop',
                    'focus' => 'language_models'
                ]
            ]
        ];
    }

    /**
     * Test API connection
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/models');

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Cohere Connection Test Failed: ' . $e->getMessage());
            return false;
        }
    }
}
