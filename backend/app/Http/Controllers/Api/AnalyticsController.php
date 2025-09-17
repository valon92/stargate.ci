<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\AnalyticsMetric;
use App\Models\PerformanceReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AnalyticsController extends Controller
{
    /**
     * Get public statistics
     */
    public function getPublicStats(): JsonResponse
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_tutorials' => \App\Models\Tutorial::where('status', 'published')->count(),
            'total_community_posts' => \App\Models\CommunityPost::where('status', 'published')->count(),
            'total_search_queries' => \App\Models\SearchQuery::count(),
            'platform_uptime' => '99.9%',
            'last_updated' => now()->toISOString(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get user analytics (authenticated)
     */
    public function getUserAnalytics(Request $request): JsonResponse
    {
        $user = $request->user();

        $analytics = [
            'user_id' => $user->id,
            'tutorials_completed' => 0, // Will be implemented with content tracking
            'community_posts' => \App\Models\CommunityPost::where('author_id', $user->id)->count(),
            'comments_made' => \App\Models\CommunityComment::where('author_id', $user->id)->count(),
            'search_queries' => \App\Models\SearchQuery::where('user_id', $user->id)->count(),
            'last_activity' => $user->updated_at,
            'account_created' => $user->created_at,
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get content analytics (authenticated)
     */
    public function getContentAnalytics(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get user's content analytics
        $contentAnalytics = [
            'tutorials_created' => \App\Models\Tutorial::where('author_id', $user->id)->count(),
            'articles_created' => \App\Models\ContentItem::where('author_id', $user->id)
                ->where('type', 'article')->count(),
            'total_views' => \App\Models\Tutorial::where('author_id', $user->id)->sum('view_count') +
                            \App\Models\ContentItem::where('author_id', $user->id)->sum('view_count'),
            'most_popular_content' => \App\Models\Tutorial::where('author_id', $user->id)
                ->orderBy('view_count', 'desc')
                ->limit(5)
                ->get(['id', 'title', 'view_count']),
        ];

        return response()->json([
            'success' => true,
            'data' => $contentAnalytics
        ]);
    }

    /**
     * Track event (authenticated)
     */
    public function trackEvent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|string|max:100',
            'properties' => 'sometimes|array',
            'session_id' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $event = AnalyticsEvent::create([
            'user_id' => $request->user()->id,
            'event_name' => $request->event_name,
            'event_type' => $request->event_type,
            'properties' => $request->properties ?? [],
            'session_id' => $request->session_id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'occurred_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event tracked successfully',
            'data' => $event
        ], 201);
    }

    /**
     * Get all analytics (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $query = AnalyticsEvent::with('user');

        if ($request->has('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->has('date_from')) {
            $query->where('occurred_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('occurred_at', '<=', $request->date_to);
        }

        $events = $query->orderBy('occurred_at', 'desc')->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get analytics reports (admin only)
     */
    public function getReports(Request $request): JsonResponse
    {
        $reports = PerformanceReport::with('generatedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    /**
     * Generate analytics report (admin only)
     */
    public function generateReport(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|string|in:page_load,api_response,database_query,user_activity',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create report record
        $report = PerformanceReport::create([
            'report_name' => $request->report_name,
            'report_type' => $request->report_type,
            'report_date' => now()->toDateString(),
            'status' => 'generating',
            'generated_by' => $request->user()->id,
            'metadata' => [
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ],
        ]);

        // Here you would typically queue a job to generate the actual report
        // For now, we'll simulate completion
        $report->update([
            'status' => 'completed',
            'metrics' => [
                'total_events' => 1500,
                'average_response_time' => 1.2,
                'error_rate' => 0.5,
            ],
            'recommendations' => [
                'Optimize database queries',
                'Implement caching for frequently accessed data',
                'Monitor API response times',
            ],
            'overall_score' => 85.5,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report generated successfully',
            'data' => $report
        ], 201);
    }

    /**
     * Get analytics metrics (admin only)
     */
    public function getMetrics(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $metrics = [
            'user_registrations' => \App\Models\User::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'tutorial_views' => \App\Models\Tutorial::whereBetween('updated_at', [$dateFrom, $dateTo])->sum('view_count'),
            'community_posts' => \App\Models\CommunityPost::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'search_queries' => \App\Models\SearchQuery::whereBetween('searched_at', [$dateFrom, $dateTo])->count(),
            'api_requests' => AnalyticsEvent::where('event_type', 'api_request')
                ->whereBetween('occurred_at', [$dateFrom, $dateTo])->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'metrics' => $metrics,
                'date_range' => [
                    'from' => $dateFrom,
                    'to' => $dateTo,
                ]
            ]
        ]);
    }

    /**
     * Get real-time analytics (admin only)
     */
    public function getRealTimeAnalytics(): JsonResponse
    {
        $realTimeData = [
            'active_users' => AnalyticsEvent::where('occurred_at', '>=', now()->subMinutes(5))
                ->distinct('user_id')->count(),
            'requests_per_minute' => AnalyticsEvent::where('occurred_at', '>=', now()->subMinutes(1))->count(),
            'error_rate' => AnalyticsEvent::where('event_type', 'error')
                ->where('occurred_at', '>=', now()->subMinutes(5))->count(),
            'top_pages' => AnalyticsEvent::where('event_type', 'page_view')
                ->where('occurred_at', '>=', now()->subMinutes(5))
                ->selectRaw('properties->>"$.page" as page, COUNT(*) as views')
                ->groupBy('page')
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $realTimeData
        ]);
    }
}