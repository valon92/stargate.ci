<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateDownload;
use App\Models\TemplateReview;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    /**
     * Get all templates with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Template::active();

            // Filter by category
            if ($request->has('category') && $request->category !== 'all') {
                $query->byCategory($request->category);
            }

            // Filter by difficulty
            if ($request->has('difficulty')) {
                $query->byDifficulty($request->difficulty);
            }

            // Filter by featured
            if ($request->has('featured') && $request->featured) {
                $query->featured();
            }

            // Search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhereJsonContains('features', $search);
                });
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSortFields = ['created_at', 'updated_at', 'name', 'rating', 'download_count'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $templates = $query->with(['creator', 'reviews' => function($query) {
                $query->latest()->limit(3);
            }])->paginate($request->get('per_page', 12));

            return response()->json([
                'success' => true,
                'data' => $templates->items(),
                'pagination' => [
                    'current_page' => $templates->currentPage(),
                    'last_page' => $templates->lastPage(),
                    'per_page' => $templates->perPage(),
                    'total' => $templates->total(),
                ],
                'message' => 'Templates retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve templates',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific template by slug
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $template = Template::active()
                ->where('slug', $slug)
                ->with([
                    'creator',
                    'reviews.user' => function($query) {
                        $query->latest()->limit(10);
                    }
                ])
                ->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $template,
                'message' => 'Template retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get template categories
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = Template::active()
                ->select('category')
                ->distinct()
                ->pluck('category')
                ->map(function ($category) {
                    return [
                        'name' => $category,
                        'slug' => Str::slug($category),
                        'icon' => $this->getCategoryIcon($category),
                        'count' => Template::active()->where('category', $category)->count()
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular templates
     */
    public function getPopular(): JsonResponse
    {
        try {
            $templates = Template::active()
                ->popular(10)
                ->with(['creator'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $templates,
                'message' => 'Popular templates retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve popular templates',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured templates
     */
    public function getFeatured(): JsonResponse
    {
        try {
            $templates = Template::active()
                ->featured()
                ->with(['creator'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $templates,
                'message' => 'Featured templates retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve featured templates',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a template
     */
    public function download(Request $request, string $slug): JsonResponse
    {
        try {
            $template = Template::active()->where('slug', $slug)->first();

            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template not found'
                ], 404);
            }

            // Create download record
            $download = TemplateDownload::create([
                'template_id' => $template->id,
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'download_token' => TemplateDownload::generateDownloadToken(),
                'metadata' => [
                    'referrer' => $request->header('referer'),
                    'downloaded_at' => now()->toISOString()
                ],
                'downloaded_at' => now()
            ]);

            // Increment download count
            $template->incrementDownloadCount();

            // Prepare template data for download
            $templateData = [
                'name' => $template->name,
                'description' => $template->description,
                'category' => $template->category,
                'difficulty' => $template->difficulty,
                'estimated_time' => $template->estimated_time,
                'team_size' => $template->team_size,
                'budget_range' => $template->budget_range,
                'features' => $template->features,
                'architecture' => $template->architecture,
                'implementation_steps' => $template->implementation_steps,
                'requirements' => $template->requirements,
                'version' => $template->version,
                'download_token' => $download->download_token,
                'downloaded_at' => $download->downloaded_at->toISOString(),
                'metadata' => [
                    'source' => 'stargate.ci',
                    'template_id' => $template->id,
                    'download_id' => $download->id
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $templateData,
                'download_token' => $download->download_token,
                'message' => 'Template downloaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new template (authenticated users only)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|max:255',
                'difficulty' => 'required|in:beginner,intermediate,advanced',
                'estimated_time' => 'required|string|max:255',
                'team_size' => 'required|string|max:255',
                'budget_range' => 'required|string|max:255',
                'features' => 'required|array',
                'architecture' => 'required|string',
                'implementation_steps' => 'required|array',
                'requirements' => 'required|array',
                'icon' => 'nullable|string|max:255',
                'is_featured' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $template = Template::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'icon' => $request->icon,
                'category' => $request->category,
                'difficulty' => $request->difficulty,
                'estimated_time' => $request->estimated_time,
                'team_size' => $request->team_size,
                'budget_range' => $request->budget_range,
                'features' => $request->features,
                'architecture' => $request->architecture,
                'implementation_steps' => $request->implementation_steps,
                'requirements' => $request->requirements,
                'is_featured' => $request->boolean('is_featured', false),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'data' => $template,
                'message' => 'Template created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get category icon
     */
    private function getCategoryIcon(string $category): string
    {
        return match($category) {
            'AI & Machine Learning' => '🤖',
            'Data Analytics' => '📊',
            'Cloud Infrastructure' => '☁️',
            'Enterprise Solutions' => '🏢',
            'Startup Solutions' => '🚀',
            'IoT & Edge Computing' => '🌐',
            default => '📋'
        };
    }
}