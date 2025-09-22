<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\AnalyticsController;

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

// Public routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    
    // Public content routes
    Route::get('/content/tutorials', [ContentController::class, 'getTutorials']);
    Route::get('/content/articles', [ContentController::class, 'getArticles']);
    Route::get('/content/categories', [ContentController::class, 'getCategories']);
    Route::get('/content/{id}', [ContentController::class, 'show']);
    
    // Public community routes
    Route::get('/community/posts', [CommunityController::class, 'getPosts']);
    Route::get('/community/categories', [CommunityController::class, 'getCategories']);
    Route::get('/community/posts/{id}', [CommunityController::class, 'showPost']);
    
    // Search routes
    Route::get('/search', [SearchController::class, 'search']);
    Route::get('/search/suggestions', [SearchController::class, 'getSuggestions']);
    Route::get('/search/filters', [SearchController::class, 'getFilters']);
    
    // Analytics routes (public stats)
    Route::get('/analytics/stats', [AnalyticsController::class, 'getPublicStats']);
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
    
    // Search routes (authenticated)
    Route::post('/search/save', [SearchController::class, 'saveSearch']);
    Route::get('/search/history', [SearchController::class, 'getSearchHistory']);
    Route::delete('/search/history/{id}', [SearchController::class, 'deleteSearchHistory']);
    
    // Analytics routes (authenticated)
    Route::get('/analytics/user', [AnalyticsController::class, 'getUserAnalytics']);
    Route::get('/analytics/content', [AnalyticsController::class, 'getContentAnalytics']);
    Route::post('/analytics/track', [AnalyticsController::class, 'trackEvent']);
    
    // File upload routes
    Route::post('/files/upload', [App\Http\Controllers\Api\FileUploadController::class, 'uploadFile']);
    Route::post('/files/upload-multiple', [App\Http\Controllers\Api\FileUploadController::class, 'uploadMultiple']);
    Route::get('/files', [App\Http\Controllers\Api\FileUploadController::class, 'getUserFiles']);
    Route::delete('/files/{id}', [App\Http\Controllers\Api\FileUploadController::class, 'deleteFile']);
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
    
    // Community management
    Route::get('/community/admin/posts', [CommunityController::class, 'index']);
    Route::put('/community/posts/{id}/moderate', [CommunityController::class, 'moderatePost']);
    Route::delete('/community/posts/{id}', [CommunityController::class, 'deletePost']);
    
    // Analytics management
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::get('/analytics/reports', [AnalyticsController::class, 'getReports']);
    Route::post('/analytics/reports', [AnalyticsController::class, 'generateReport']);
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