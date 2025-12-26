<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OpenAIService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY', '');
        $this->baseUrl = env('OPENAI_BASE_URL', 'https://api.openai.com/v1');
    }

    /**
     * Get events from OpenAI API
     */
    public function getEvents(): array
    {
        try {
            $cacheKey = 'openai_events_' . date('Y-m-d-H');
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                return $cached;
            }

            // Use OpenAI API to generate events
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an AI assistant that generates realistic tech events data. Generate 3-5 upcoming tech events related to AI, machine learning, and technology conferences. Return only valid JSON array with events containing: title, description, category (stargate/cristal/conferences/meetings/announcements), type (conference/meeting/announcement/workshop/video), event_date (YYYY-MM-DD), event_time (HH:MM:SS), location, organizer, icon (emoji), registration_url (use real URLs like https://openai.com/events/[event-name] or https://eventbrite.com/e/[event-name]), video_url (optional).'
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Generate upcoming tech events for the next 3 months.'
                    ]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.7
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                
                // Try to extract JSON from the response
                if (preg_match('/\[.*\]/s', $content, $matches)) {
                    $events = json_decode($matches[0], true);
                    
                    if (is_array($events)) {
                        // Add source and metadata
                        foreach ($events as &$event) {
                            $event['source'] = 'openai';
                            $event['metadata'] = [
                                'api_source' => 'openai',
                                'generated_at' => now()->toISOString(),
                                'model' => 'gpt-4'
                            ];
                        }
                        
                        Cache::put($cacheKey, $events, 3600); // Cache for 1 hour
                        return $events;
                    }
                }
            }

            Log::error('OpenAI API Error: ' . $response->body());
            return $this->getFallbackEvents();

        } catch (\Exception $e) {
            Log::error('OpenAI Service Error: ' . $e->getMessage());
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
                'external_id' => 'openai-dev-day-2025',
                'title' => 'OpenAI Developer Day 2025',
                'description' => 'Join us for the latest announcements about GPT models, API updates, and Stargate Project developments.',
                'category' => 'stargate',
                'type' => 'conference',
                'event_date' => '2025-02-15',
                'event_time' => '09:00:00',
                'location' => 'San Francisco, CA',
                'organizer' => 'OpenAI',
                'icon' => '🤖',
                'registration_url' => 'https://openai.com/dev-day-2025',
                'source' => 'openai',
                'metadata' => [
                    'api_source' => 'openai',
                    'event_type' => 'developer_conference',
                    'capacity' => 5000,
                    'streaming' => true
                ]
            ],
            [
                'external_id' => 'openai-stargate-update',
                'title' => 'Stargate Project Infrastructure Update',
                'description' => 'Latest updates on the $500B Stargate Project infrastructure development and deployment timeline.',
                'category' => 'stargate',
                'type' => 'announcement',
                'event_date' => '2025-02-20',
                'event_time' => '14:00:00',
                'location' => 'Virtual Event',
                'organizer' => 'OpenAI',
                'icon' => '🚀',
                'video_url' => 'https://youtube.com/watch?v=stargate-update',
                'source' => 'openai',
                'metadata' => [
                    'api_source' => 'openai',
                    'event_type' => 'announcement',
                    'live_stream' => true
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
            Log::error('OpenAI Connection Test Failed: ' . $e->getMessage());
            return false;
        }
    }
}