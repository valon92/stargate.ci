<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EventsController extends Controller
{
    private EventsService $eventsService;

    public function __construct(EventsService $eventsService)
    {
        $this->eventsService = $eventsService;
    }

    /**
     * Get all events with optional filtering
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'category' => 'sometimes|string|in:all,stargate,cristal,conferences,meetings,announcements',
                'upcoming' => 'sometimes|string|in:true,false,1,0',
                'featured' => 'sometimes|string|in:true,false,1,0',
                'source' => 'sometimes|string|in:internal,openai,gemini,cohere,softbank,oracle,mgx',
                'search' => 'sometimes|string|max:255',
                'limit' => 'sometimes|integer|min:1|max:100',
                'page' => 'sometimes|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $filters = $request->only(['category', 'upcoming', 'featured', 'source', 'search', 'limit']);
            
            // Convert string boolean values to actual booleans
            if (isset($filters['upcoming'])) {
                $filters['upcoming'] = in_array($filters['upcoming'], ['true', '1', 1, true]);
            }
            if (isset($filters['featured'])) {
                $filters['featured'] = in_array($filters['featured'], ['true', '1', 1, true]);
            }
            
            $result = $this->eventsService->getEventsWithExternalData($filters);

            return response()->json([
                'success' => true,
                'data' => $result['events'],
                'meta' => [
                    'total' => $result['total'],
                    'sources' => $result['sources'],
                    'last_sync' => $result['last_sync'],
                    'filters_applied' => $filters
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Events API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get a specific event by ID
     */
    public function show(string $id): JsonResponse
    {
        try {
            $event = Event::active()->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $event,
                'meta' => [
                    'formatted_date' => $event->formatted_date,
                    'formatted_time' => $event->formatted_time,
                    'is_upcoming' => $event->is_upcoming,
                    'source_info' => $this->getSourceInfo($event->source)
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Event Show API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch event',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get upcoming events
     */
    public function upcoming(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $filters = array_merge($request->only(['category', 'source']), [
                'upcoming' => true,
                'limit' => $limit
            ]);

            $result = $this->eventsService->getEventsWithExternalData($filters);

            return response()->json([
                'success' => true,
                'data' => $result['events'],
                'meta' => [
                    'total' => $result['total'],
                    'limit' => $limit,
                    'sources' => $result['sources']
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Upcoming Events API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch upcoming events',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get events by category
     */
    public function byCategory(string $category, Request $request): JsonResponse
    {
        try {
            $validator = Validator::make(['category' => $category], [
                'category' => 'required|string|in:stargate,cristal,conferences,meetings,announcements'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid category',
                    'errors' => $validator->errors()
                ], 422);
            }

            $limit = $request->get('limit', 20);
            $filters = array_merge($request->only(['upcoming', 'featured', 'source']), [
                'category' => $category,
                'limit' => $limit
            ]);

            $result = $this->eventsService->getEventsWithExternalData($filters);

            return response()->json([
                'success' => true,
                'data' => $result['events'],
                'meta' => [
                    'total' => $result['total'],
                    'category' => $category,
                    'limit' => $limit,
                    'sources' => $result['sources']
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Events by Category API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events by category',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Search events
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:2|max:255',
                'category' => 'sometimes|string|in:all,stargate,cristal,conferences,meetings,announcements',
                'source' => 'sometimes|string|in:internal,openai,gemini,cohere,softbank,oracle,mgx',
                'limit' => 'sometimes|integer|min:1|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $filters = array_merge($request->only(['category', 'source', 'limit']), [
                'search' => $request->get('q')
            ]);

            $result = $this->eventsService->getEventsWithExternalData($filters);

            return response()->json([
                'success' => true,
                'data' => $result['events'],
                'meta' => [
                    'total' => $result['total'],
                    'query' => $request->get('q'),
                    'filters_applied' => $filters,
                    'sources' => $result['sources']
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Events Search API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search events',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get event categories
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = [
                [
                    'id' => 'all',
                    'name' => 'All Events',
                    'icon' => '📅',
                    'description' => 'All upcoming events and announcements',
                    'count' => Event::active()->count()
                ],
                [
                    'id' => 'stargate',
                    'name' => 'Stargate Project',
                    'icon' => '🚀',
                    'description' => 'Events related to the $500 billion AI infrastructure initiative',
                    'count' => Event::active()->byCategory('stargate')->count()
                ],
                [
                    'id' => 'cristal',
                    'name' => 'Cristal Intelligence',
                    'icon' => '💎',
                    'description' => 'Events about transparent AI and ethical AI development',
                    'count' => Event::active()->byCategory('cristal')->count()
                ],
                [
                    'id' => 'conferences',
                    'name' => 'Conferences',
                    'icon' => '🎤',
                    'description' => 'Major conferences and speaking engagements',
                    'count' => Event::active()->byCategory('conferences')->count()
                ],
                [
                    'id' => 'meetings',
                    'name' => 'Meetings',
                    'icon' => '🤝',
                    'description' => 'Partnership meetings and strategic discussions',
                    'count' => Event::active()->byCategory('meetings')->count()
                ],
                [
                    'id' => 'announcements',
                    'name' => 'Announcements',
                    'icon' => '📢',
                    'description' => 'Important announcements and milestone releases',
                    'count' => Event::active()->byCategory('announcements')->count()
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $categories,
                'meta' => [
                    'total_categories' => count($categories),
                    'total_events' => Event::active()->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Event Categories API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch event categories',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Sync events from external APIs
     */
    public function sync(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'source' => 'sometimes|string|in:all,openai,softbank,oracle,mgx',
                'force' => 'sometimes|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $source = $request->get('source', 'all');
            $force = $request->get('force', false);

            if ($force) {
                $result = $this->eventsService->forceRefreshAllEvents();
            } else {
                $result = $this->eventsService->syncAllExternalEvents();
            }

            return response()->json([
                'success' => true,
                'message' => 'Events sync completed',
                'data' => $result,
                'meta' => [
                    'total_synced' => $result['total_synced'],
                    'sync_time' => now(),
                    'force_refresh' => $force
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Events Sync API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync events',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get sync status
     */
    public function syncStatus(): JsonResponse
    {
        try {
            $sources = ['openai', 'softbank', 'oracle', 'mgx'];
            $status = [];

            foreach ($sources as $source) {
                $lastEvent = Event::bySource($source)->orderBy('last_synced_at', 'desc')->first();
                $status[$source] = [
                    'last_sync' => $lastEvent ? $lastEvent->last_synced_at : null,
                    'total_events' => Event::bySource($source)->count(),
                    'active_events' => Event::active()->bySource($source)->count(),
                    'upcoming_events' => Event::active()->upcoming()->bySource($source)->count()
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $status,
                'meta' => [
                    'total_sources' => count($sources),
                    'overall_status' => 'operational'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Events Sync Status API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sync status',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get source information
     */
    private function getSourceInfo(string $source): array
    {
        $sourceInfo = [
            'internal' => [
                'name' => 'Internal Database',
                'description' => 'Events stored in local database',
                'icon' => '🏠',
                'color' => 'blue'
            ],
            'openai' => [
                'name' => 'OpenAI API',
                'description' => 'Events from OpenAI official sources',
                'icon' => '🤖',
                'color' => 'green'
            ],
            'gemini' => [
                'name' => 'Gemini API',
                'description' => 'Events from Google Gemini AI',
                'icon' => '💎',
                'color' => 'purple'
            ],
            'cohere' => [
                'name' => 'Cohere API',
                'description' => 'Events from Cohere AI',
                'icon' => '🏢',
                'color' => 'orange'
            ],
            'softbank' => [
                'name' => 'SoftBank API',
                'description' => 'Events from SoftBank Group',
                'icon' => '📊',
                'color' => 'orange'
            ],
            'oracle' => [
                'name' => 'Oracle API',
                'description' => 'Events from Oracle Corporation',
                'icon' => '☁️',
                'color' => 'red'
            ],
            'mgx' => [
                'name' => 'MGX API',
                'description' => 'Events from MGX',
                'icon' => '💎',
                'color' => 'purple'
            ]
        ];

        return $sourceInfo[$source] ?? [
            'name' => 'Unknown Source',
            'description' => 'Unknown event source',
            'icon' => '❓',
            'color' => 'gray'
        ];
    }
}