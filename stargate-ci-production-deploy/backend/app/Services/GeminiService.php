<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeminiService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
        $this->baseUrl = env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1');
    }

    /**
     * Get events from Gemini API
     */
    public function getEvents(): array
    {
        try {
            $cacheKey = 'gemini_events_' . date('Y-m-d-H');
            $cached = Cache::get($cacheKey);
            
            if ($cached) {
                return $cached;
            }

            // Use Gemini API to generate events
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/models/gemini-pro:generateContent?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => 'Generate 3-5 upcoming tech events related to AI, machine learning, and technology conferences. Return only valid JSON array with events containing: title, description, category (stargate/cristal/conferences/meetings/announcements), type (conference/meeting/announcement/workshop/video), event_date (YYYY-MM-DD), event_time (HH:MM:SS), location, organizer, icon (emoji), registration_url (use real URLs like https://ai.google/events/[event-name] or https://eventbrite.com/e/[event-name]), video_url (optional). Focus on Cristal Intelligence and transparent AI events.'
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 2000
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json()['candidates'][0]['content']['parts'][0]['text'];
                
                // Try to extract JSON from the response
                if (preg_match('/\[.*\]/s', $content, $matches)) {
                    $events = json_decode($matches[0], true);
                    
                    if (is_array($events)) {
                        // Add source and metadata
                        foreach ($events as &$event) {
                            $event['source'] = 'gemini';
                            $event['metadata'] = [
                                'api_source' => 'gemini',
                                'generated_at' => now()->toISOString(),
                                'model' => 'gemini-pro'
                            ];
                        }
                        
                        Cache::put($cacheKey, $events, 3600); // Cache for 1 hour
                        return $events;
                    }
                }
            }

            Log::error('Gemini API Error: ' . $response->body());
            return $this->getFallbackEvents();

        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
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
                'external_id' => 'gemini-cristal-summit',
                'title' => 'Gemini Cristal Intelligence Summit',
                'description' => 'Exploring transparent AI systems and cristalline computing principles with Google\'s Gemini AI.',
                'category' => 'cristal',
                'type' => 'conference',
                'event_date' => '2025-03-10',
                'event_time' => '10:00:00',
                'location' => 'Mountain View, CA',
                'organizer' => 'Google AI',
                'icon' => '💎',
                'registration_url' => 'https://ai.google/gemini-summit',
                'source' => 'gemini',
                'metadata' => [
                    'api_source' => 'gemini',
                    'event_type' => 'ai_summit',
                    'focus' => 'cristal_intelligence'
                ]
            ],
            [
                'external_id' => 'gemini-transparency-workshop',
                'title' => 'AI Transparency Workshop with Gemini',
                'description' => 'Hands-on workshop on building transparent AI systems using Google\'s Gemini models.',
                'category' => 'cristal',
                'type' => 'workshop',
                'event_date' => '2025-03-15',
                'event_time' => '14:00:00',
                'location' => 'Virtual Event',
                'organizer' => 'Google AI',
                'icon' => '🔬',
                'registration_url' => 'https://ai.google/transparency-workshop',
                'source' => 'gemini',
                'metadata' => [
                    'api_source' => 'gemini',
                    'event_type' => 'workshop',
                    'focus' => 'ai_transparency'
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
            $response = Http::get($this->baseUrl . '/models?key=' . $this->apiKey);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Gemini Connection Test Failed: ' . $e->getMessage());
            return false;
        }
    }
}
