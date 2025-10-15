<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\ExternalApiController;
use App\Http\Controllers\Api\TemplateController;
use App\Http\Controllers\Api\TemplateReviewController;
use App\Http\Controllers\Api\AssessmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes with rate limiting
Route::prefix('v1')->middleware('api.throttle:100,1')->group(function () {
    // Authentication routes (stricter rate limit)
    Route::middleware('api.throttle:10,1')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    });
    
    // Public content routes (cached for 60 minutes)
    Route::middleware('api.cache:60')->group(function () {
        Route::get('/content/tutorials', [ContentController::class, 'getTutorials']);
        Route::get('/content/articles', [ContentController::class, 'getArticles']);
        Route::get('/content/categories', [ContentController::class, 'getCategories']);
        Route::get('/content/{id}', [ContentController::class, 'show']);
    });
    
    // Public FAQ routes (cached for 120 minutes)
    Route::middleware('api.cache:120')->group(function () {
        Route::get('/faqs', [FAQController::class, 'index']);
        Route::get('/faqs/{id}', [FAQController::class, 'show']);
        Route::get('/faqs/categories', [FAQController::class, 'getCategories']);
        Route::get('/faqs/popular', [FAQController::class, 'getPopular']);
    });
    Route::post('/faqs/{id}/view', [FAQController::class, 'incrementView']);
    
    // Public Article routes (cached for 30 minutes)
    Route::middleware('api.cache:30')->group(function () {
        Route::get('/articles', [ArticleController::class, 'index']);
        Route::get('/articles/{id}', [ArticleController::class, 'show']);
        Route::get('/articles/categories', [ArticleController::class, 'getCategories']);
        Route::get('/articles/popular', [ArticleController::class, 'getPopular']);
        Route::get('/articles/{id}/related', [ArticleController::class, 'getRelated']);
    });
    
    // Public community routes
    Route::get('/community/posts', [CommunityController::class, 'getPosts']);
    Route::get('/community/categories', [CommunityController::class, 'getCategories']);
    Route::get('/community/posts/{id}', [CommunityController::class, 'showPost']);
    
    // Public Template routes (cached for 60 minutes)
    Route::middleware('api.cache:60')->group(function () {
        Route::get('/templates', [TemplateController::class, 'index']);
        Route::get('/templates/categories', [TemplateController::class, 'getCategories']);
        Route::get('/templates/popular', [TemplateController::class, 'getPopular']);
        Route::get('/templates/featured', [TemplateController::class, 'getFeatured']);
        Route::get('/templates/{slug}', [TemplateController::class, 'show']);
    });
    
    // Public Template Review routes
    Route::get('/templates/{slug}/reviews', [TemplateReviewController::class, 'index']);
    
    // Search routes
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/search/suggestions', [SearchController::class, 'getSuggestions']);
    Route::get('/search/filters', [SearchController::class, 'getFilters']);
    
    // Analytics routes (public stats)
    Route::get('/analytics/stats', [AnalyticsController::class, 'getPublicStats']);

    // Assessment routes (public save with session or authenticated)
    Route::post('/assessment/results', [AssessmentController::class, 'store']);
    Route::get('/assessment/results', [AssessmentController::class, 'index']);
    Route::get('/assessment/results/{id}', [AssessmentController::class, 'show']);
});

// Protected routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::get('/user/dashboard', [UserController::class, 'dashboard']);
    Route::get('/user/preferences', [UserController::class, 'getPreferences']);
    Route::put('/user/preferences', [UserController::class, 'updatePreferences']);
    
    // Content routes (authenticated)
    Route::post('/content/tutorials', [ContentController::class, 'createTutorial']);
    Route::put('/content/tutorials/{id}', [ContentController::class, 'updateTutorial']);
    Route::delete('/content/tutorials/{id}', [ContentController::class, 'deleteTutorial']);
    Route::post('/content/tutorials/{id}/complete', [ContentController::class, 'completeTutorial']);
    Route::post('/content/tutorials/{id}/bookmark', [ContentController::class, 'bookmarkTutorial']);
    
    // Community routes (authenticated)
    Route::post('/community/posts', [CommunityController::class, 'createPost']);
    Route::put('/community/posts/{id}', [CommunityController::class, 'updatePost']);
    Route::delete('/community/posts/{id}', [CommunityController::class, 'deletePost']);
    Route::post('/community/posts/{id}/comments', [CommunityController::class, 'addComment']);
    Route::post('/community/posts/{id}/like', [CommunityController::class, 'likePost']);
    
    // Article routes (authenticated)
    Route::post('/articles/{id}/like', [ArticleController::class, 'toggleLike']);
    
    // Search routes (authenticated)
    Route::post('/search/save', [SearchController::class, 'saveSearch']);
    Route::get('/search/history', [SearchController::class, 'getSearchHistory']);
    Route::delete('/search/history/{id}', [SearchController::class, 'deleteSearchHistory']);
    
    // Analytics routes (authenticated)
    Route::get('/analytics/user', [AnalyticsController::class, 'getUserAnalytics']);
    Route::get('/analytics/content', [AnalyticsController::class, 'getContentAnalytics']);
    Route::post('/analytics/track', [AnalyticsController::class, 'trackEvent']);
    
    // File upload routes
    Route::post('/files/upload', [FileUploadController::class, 'uploadFile']);
    Route::post('/files/upload-multiple', [FileUploadController::class, 'uploadMultiple']);
    Route::get('/files', [FileUploadController::class, 'getUserFiles']);
    Route::get('/files/{id}', [FileUploadController::class, 'getFileInfo']);
    Route::delete('/files/{id}', [FileUploadController::class, 'deleteFile']);
    
    // Template routes (authenticated)
    Route::post('/templates', [TemplateController::class, 'store']);
    Route::post('/templates/{slug}/download', [TemplateController::class, 'download']);
    
    // Template Review routes (authenticated)
    Route::post('/templates/{slug}/reviews', [TemplateReviewController::class, 'store']);
    Route::put('/templates/{slug}/reviews/{reviewId}', [TemplateReviewController::class, 'update']);
    Route::delete('/templates/{slug}/reviews/{reviewId}', [TemplateReviewController::class, 'destroy']);
    Route::post('/templates/{slug}/reviews/{reviewId}/helpful', [TemplateReviewController::class, 'markHelpful']);
});

// Admin routes (require admin role)
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // User management
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/{id}/roles', [UserController::class, 'assignRole']);
    
    // Content management
    Route::get('/content', [ContentController::class, 'index']);
    Route::post('/content', [ContentController::class, 'store']);
    Route::put('/content/{id}', [ContentController::class, 'update']);
    Route::delete('/content/{id}', [ContentController::class, 'destroy']);
    Route::post('/content/{id}/publish', [ContentController::class, 'publish']);
    
    // FAQ management
    Route::post('/faqs', [FAQController::class, 'store']);
    Route::put('/faqs/{id}', [FAQController::class, 'update']);
    Route::delete('/faqs/{id}', [FAQController::class, 'destroy']);
    
    // Article management
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
    
    // Template management
    Route::get('/templates', [TemplateController::class, 'index']);
    Route::put('/templates/{id}', [TemplateController::class, 'update']);
    Route::delete('/templates/{id}', [TemplateController::class, 'destroy']);
    Route::post('/templates/{id}/feature', [TemplateController::class, 'toggleFeatured']);
    Route::post('/templates/{id}/activate', [TemplateController::class, 'toggleActive']);
    
    // Community management
    Route::get('/community/admin/posts', [CommunityController::class, 'index']);
    Route::put('/community/posts/{id}/moderate', [CommunityController::class, 'moderatePost']);
    Route::delete('/community/posts/{id}', [CommunityController::class, 'deletePost']);
    
    // Analytics management
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/analytics/reports', [AnalyticsController::class, 'getReports']);
    Route::post('/analytics/reports', [AnalyticsController::class, 'generateReport']);
});

// External API Integration Routes
Route::prefix('external')->group(function () {
    // Comprehensive data endpoints
    Route::get('/stargate-data', [ExternalApiController::class, 'getStargateData']);
    Route::get('/ai-ecosystem', [ExternalApiController::class, 'getAIEcosystem']);
    Route::get('/market-analysis', [ExternalApiController::class, 'getMarketAnalysis']);
    Route::get('/performance-metrics', [ExternalApiController::class, 'getPerformanceMetrics']);
    Route::get('/service-health', [ExternalApiController::class, 'getServiceHealth']);
    Route::get('/test-connections', [ExternalApiController::class, 'testConnections']);
    Route::post('/generate-insights', [ExternalApiController::class, 'generateInsights']);

    // OpenAI endpoints
    Route::prefix('openai')->group(function () {
        Route::post('/generate-text', [ExternalApiController::class, 'openaiGenerateText']);
        Route::post('/generate-image', [ExternalApiController::class, 'openaiGenerateImage']);
    });

    // SoftBank endpoints
    Route::prefix('softbank')->group(function () {
        Route::get('/portfolio', [ExternalApiController::class, 'softbankPortfolio']);
        Route::get('/stargate-funding', [ExternalApiController::class, 'softbankStargateFunding']);
    });

    // Oracle endpoints
    Route::prefix('oracle')->group(function () {
        Route::get('/infrastructure', [ExternalApiController::class, 'oracleInfrastructure']);
        Route::get('/ai-services', [ExternalApiController::class, 'oracleAIServices']);
    });

    // MGX endpoints
    Route::prefix('mgx')->group(function () {
        Route::get('/platform-data', [ExternalApiController::class, 'mgxPlatformData']);
        Route::get('/ai-capabilities', [ExternalApiController::class, 'mgxAICapabilities']);
    });
});

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

// Simple test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'Laravel API is working!',
        'timestamp' => now()
    ]);
});