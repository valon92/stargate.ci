<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class EventsService
{
    private $openaiService;
    private $geminiService;
    private $cohereService;
    private $softbankService;
    private $oracleService;
    private $mgxService;

    public function __construct()
    {
        // Initialize services
        $this->openaiService = app(OpenAIService::class);
        $this->geminiService = app(GeminiService::class);
        $this->cohereService = app(CohereService::class);
        $this->softbankService = null; // Will be implemented later
        $this->oracleService = null; // Will be implemented later
        $this->mgxService = null; // Will be implemented later
    }

    /**
     * Sync events from all external APIs
     */
    public function syncAllExternalEvents(): array
    {
        $results = [
            'openai' => $this->syncOpenAIEvents(),
            'gemini' => $this->syncGeminiEvents(),
            'cohere' => $this->syncCohereEvents(),
            'softbank' => $this->syncSoftBankEvents(),
            'oracle' => $this->syncOracleEvents(),
            'mgx' => $this->syncMGXEvents(),
            'total_synced' => 0,
            'errors' => []
        ];

        $results['total_synced'] = array_sum(array_column($results, 'count'));
        
        return $results;
    }

    /**
     * Sync events from OpenAI API
     */
    public function syncOpenAIEvents(): array
    {
        try {
            $cacheKey = 'openai_events_sync';
            $cached = Cache::get($cacheKey);
            
            if ($cached && Carbon::parse($cached['timestamp'])->diffInHours(now()) < 6) {
                return $cached['data'];
            }

            $events = $this->fetchOpenAIEvents();
            $synced = $this->storeEvents($events, 'openai');
            
            $result = [
                'source' => 'openai',
                'count' => $synced,
                'status' => 'success',
                'timestamp' => now()
            ];

            Cache::put($cacheKey, [
                'data' => $result,
                'timestamp' => now()
            ], 3600); // Cache for 1 hour

            return $result;

        } catch (\Exception $e) {
            Log::error('OpenAI Events Sync Error: ' . $e->getMessage());
            return [
                'source' => 'openai',
                'count' => 0,
                'status' => 'error',
                'error' => $e->getMessage(),
                'timestamp' => now()
            ];
        }
    }

    /**
     * Fetch events from OpenAI API
     */
    private function fetchOpenAIEvents(): array
    {
        try {
            return $this->openaiService->getEvents();
        } catch (\Exception $e) {
            Log::error('Error fetching OpenAI events: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Sync events from Gemini API
     */
    public function syncGeminiEvents(): array
    {
        try {
            $cacheKey = 'gemini_events_sync';
            $cached = Cache::get($cacheKey);
            
            if ($cached && Carbon::parse($cached['timestamp'])->diffInHours(now()) < 6) {
                return $cached['data'];
            }

            $events = $this->fetchGeminiEvents();
            $synced = $this->storeEvents($events, 'gemini');
            
            $result = [
                'source' => 'gemini',
                'count' => $synced,
                'status' => 'success',
                'timestamp' => now()
            ];

            Cache::put($cacheKey, [
                'data' => $result,
                'timestamp' => now()
            ], 3600);

            return $result;

        } catch (\Exception $e) {
            Log::error('Gemini Events Sync Error: ' . $e->getMessage());
            return [
                'source' => 'gemini',
                'count' => 0,
                'status' => 'error',
                'error' => $e->getMessage(),
                'timestamp' => now()
            ];
        }
    }

    /**
     * Sync events from Cohere API
     */
    public function syncCohereEvents(): array
    {
        try {
            $cacheKey = 'cohere_events_sync';
            $cached = Cache::get($cacheKey);
            
            if ($cached && Carbon::parse($cached['timestamp'])->diffInHours(now()) < 6) {
                return $cached['data'];
            }

            $events = $this->fetchCohereEvents();
            $synced = $this->storeEvents($events, 'cohere');
            
            $result = [
                'source' => 'cohere',
                'count' => $synced,
                'status' => 'success',
                'timestamp' => now()
            ];

            Cache::put($cacheKey, [
                'data' => $result,
                'timestamp' => now()
            ], 3600);

            return $result;

        } catch (\Exception $e) {
            Log::error('Cohere Events Sync Error: ' . $e->getMessage());
            return [
                'source' => 'cohere',
                'count' => 0,
                'status' => 'error',
                'error' => $e->getMessage(),
                'timestamp' => now()
            ];
        }
    }

    /**
     * Sync events from SoftBank API
     */
    public function syncSoftBankEvents(): array
    {
        try {
            $cacheKey = 'softbank_events_sync';
            $cached = Cache::get($cacheKey);
            
            if ($cached && Carbon::parse($cached['timestamp'])->diffInHours(now()) < 8) {
                return $cached['data'];
            }

            $events = $this->fetchSoftBankEvents();
            $synced = $this->storeEvents($events, 'softbank');
            
            $result = [
                'source' => 'softbank',
                'count' => $synced,
                'status' => 'success',
                'timestamp' => now()
            ];

            Cache::put($cacheKey, [
                'data' => $result,
                'timestamp' => now()
            ], 3600);

            return $result;

        } catch (\Exception $e) {
            Log::error('SoftBank Events Sync Error: ' . $e->getMessage());
            return [
                'source' => 'softbank',
                'count' => 0,
                'status' => 'error',
                'error' => $e->getMessage(),
                'timestamp' => now()
            ];
        }
    }

    /**
     * Fetch events from Gemini API
     */
    private function fetchGeminiEvents(): array
    {
        try {
            return $this->geminiService->getEvents();
        } catch (\Exception $e) {
            Log::error('Error fetching Gemini events: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch events from Cohere API
     */
    private function fetchCohereEvents(): array
    {
        try {
            return $this->cohereService->getEvents();
        } catch (\Exception $e) {
            Log::error('Error fetching Cohere events: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch events from SoftBank API
     */
    private function fetchSoftBankEvents(): array
    {
        // Simulate SoftBank API call for events
        return [
            [
                'external_id' => 'softbank-vision-fund-ai-summit',
                'title' => 'SoftBank Vision Fund AI Investment Summit',
                'description' => 'SoftBank discusses AI investment strategy, Stargate Project involvement, and future AI infrastructure investments.',
                'category' => 'stargate',
                'type' => 'conference',
                'event_date' => '2025-03-10',
                'event_time' => '10:00:00',
                'location' => 'Tokyo, Japan',
                'organizer' => 'SoftBank Group',
                'icon' => '📊',
                'registration_url' => 'https://softbank.com/ai-summit-2025',
                'metadata' => [
                    'api_source' => 'softbank',
                    'event_type' => 'investment_summit',
                    'capacity' => 2000,
                    'language' => ['japanese', 'english']
                ]
            ],
            [
                'external_id' => 'softbank-arm-partnership-meeting',
                'title' => 'SoftBank-ARM Partnership Strategy Meeting',
                'description' => 'Strategic meeting on ARM chip development for AI infrastructure and Stargate Project requirements.',
                'category' => 'stargate',
                'type' => 'meeting',
                'event_date' => '2025-03-15',
                'event_time' => '15:00:00',
                'location' => 'Cambridge, UK',
                'organizer' => 'SoftBank Group',
                'icon' => '🔧',
                'metadata' => [
                    'api_source' => 'softbank',
                    'event_type' => 'strategic_meeting',
                    'attendees' => 'executives_only'
                ]
            ]
        ];
    }

    /**
     * Sync events from Oracle API
     */
    public function syncOracleEvents(): array
    {
        try {
            $cacheKey = 'oracle_events_sync';
            $cached = Cache::get($cacheKey);
            
            if ($cached && Carbon::parse($cached['timestamp'])->diffInHours(now()) < 12) {
                return $cached['data'];
            }

            $events = $this->fetchOracleEvents();
            $synced = $this->storeEvents($events, 'oracle');
            
            $result = [
                'source' => 'oracle',
                'count' => $synced,
                'status' => 'success',
                'timestamp' => now()
            ];

            Cache::put($cacheKey, [
                'data' => $result,
                'timestamp' => now()
            ], 3600);

            return $result;

        } catch (\Exception $e) {
            Log::error('Oracle Events Sync Error: ' . $e->getMessage());
            return [
                'source' => 'oracle',
                'count' => 0,
                'status' => 'error',
                'error' => $e->getMessage(),
                'timestamp' => now()
            ];
        }
    }

    /**
     * Fetch events from Oracle API
     */
    private function fetchOracleEvents(): array
    {
        // Simulate Oracle API call for events
        return [
            [
                'external_id' => 'oracle-cloud-ai-workshop',
                'title' => 'Oracle Cloud AI Infrastructure Workshop',
                'description' => 'Workshop on Oracle Cloud infrastructure for AI workloads and integration with Stargate Project requirements.',
                'category' => 'conferences',
                'type' => 'workshop',
                'event_date' => '2025-04-05',
                'event_time' => '09:30:00',
                'location' => 'Austin, TX',
                'organizer' => 'Oracle Corporation',
                'icon' => '☁️',
                'registration_url' => 'https://oracle.com/ai-workshop-2025',
                'metadata' => [
                    'api_source' => 'oracle',
                    'event_type' => 'technical_workshop',
                    'capacity' => 500,
                    'certification' => true
                ]
            ]
        ];
    }

    /**
     * Sync events from MGX API
     */
    public function syncMGXEvents(): array
    {
        try {
            $cacheKey = 'mgx_events_sync';
            $cached = Cache::get($cacheKey);
            
            if ($cached && Carbon::parse($cached['timestamp'])->diffInHours(now()) < 24) {
                return $cached['data'];
            }

            $events = $this->fetchMGXEvents();
            $synced = $this->storeEvents($events, 'mgx');
            
            $result = [
                'source' => 'mgx',
                'count' => $synced,
                'status' => 'success',
                'timestamp' => now()
            ];

            Cache::put($cacheKey, [
                'data' => $result,
                'timestamp' => now()
            ], 3600);

            return $result;

        } catch (\Exception $e) {
            Log::error('MGX Events Sync Error: ' . $e->getMessage());
            return [
                'source' => 'mgx',
                'count' => 0,
                'status' => 'error',
                'error' => $e->getMessage(),
                'timestamp' => now()
            ];
        }
    }

    /**
     * Fetch events from MGX API
     */
    private function fetchMGXEvents(): array
    {
        // Simulate MGX API call for events
        return [
            [
                'external_id' => 'mgx-cristal-intelligence-seminar',
                'title' => 'MGX Cristal Intelligence Research Seminar',
                'description' => 'Research seminar on Cristal Intelligence principles and transparent AI system development.',
                'category' => 'cristal',
                'type' => 'workshop',
                'event_date' => '2025-04-20',
                'event_time' => '14:00:00',
                'location' => 'Dubai, UAE',
                'organizer' => 'MGX',
                'icon' => '💎',
                'registration_url' => 'https://mgx.com/cristal-seminar',
                'metadata' => [
                    'api_source' => 'mgx',
                    'event_type' => 'research_seminar',
                    'capacity' => 300,
                    'research_focus' => 'cristal_intelligence'
                ]
            ]
        ];
    }

    /**
     * Store events in database
     */
    private function storeEvents(array $events, string $source): int
    {
        $synced = 0;
        
        foreach ($events as $eventData) {
            $event = Event::where('external_id', $eventData['external_id'])
                         ->where('source', $source)
                         ->first();

            if ($event) {
                $event->syncFromExternalApi($eventData, $source);
            } else {
                Event::create(array_merge($eventData, ['source' => $source]));
            }
            
            $synced++;
        }

        return $synced;
    }

    /**
     * Get events with external API data
     */
    public function getEventsWithExternalData(array $filters = []): array
    {
        $query = Event::active();

        // Apply filters
        if (isset($filters['category']) && $filters['category'] !== 'all') {
            $query->byCategory($filters['category']);
        }

        if (isset($filters['upcoming']) && $filters['upcoming']) {
            $query->upcoming();
        }

        if (isset($filters['featured']) && $filters['featured']) {
            $query->featured();
        }

        if (isset($filters['source'])) {
            $query->bySource($filters['source']);
        }

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('organizer', 'like', '%' . $filters['search'] . '%');
            });
        }

        $limit = $filters['limit'] ?? 20;
        $events = $query->orderBy('event_date', 'asc')
                       ->orderBy('event_time', 'asc')
                       ->limit($limit)
                       ->get();

        return [
            'success' => true,
            'events' => $events->toArray(),
            'total' => $events->count(),
            'sources' => $this->getEventSources(),
            'last_sync' => $this->getLastSyncTimes()
        ];
    }

    /**
     * Get available event sources
     */
    private function getEventSources(): array
    {
        $sources = Event::select('source')
                   ->distinct()
                   ->pluck('source')
                   ->toArray();
        
        // Add all available sources
        $allSources = ['internal', 'openai', 'gemini', 'cohere', 'softbank', 'oracle', 'mgx'];
        return array_unique(array_merge($sources, $allSources));
    }

    /**
     * Get last sync times for each source
     */
    private function getLastSyncTimes(): array
    {
        return Event::select('source')
                   ->selectRaw('MAX(last_synced_at) as last_sync')
                   ->groupBy('source')
                   ->pluck('last_sync', 'source')
                   ->toArray();
    }

    /**
     * Force refresh all external events
     */
    public function forceRefreshAllEvents(): array
    {
        // Clear all caches
        Cache::forget('openai_events_sync');
        Cache::forget('gemini_events_sync');
        Cache::forget('cohere_events_sync');
        Cache::forget('softbank_events_sync');
        Cache::forget('oracle_events_sync');
        Cache::forget('mgx_events_sync');

        return $this->syncAllExternalEvents();
    }
}
