<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\SubscriberController;
use App\Http\Controllers\Api\VideoInteractionController;
use App\Http\Controllers\Api\VideoCommentController;

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
    // Public content routes (cached for 60 minutes)
    Route::middleware('api.cache:60')->group(function () {
        Route::get('/content/articles', [ArticleController::class, 'index']);
        Route::get('/content/articles/{id}', [ArticleController::class, 'show']);
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
    
    // Contact routes
    Route::post('/contact', [ContactController::class, 'store']);
    
    // Video System Routes (public)
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/videos/{contentId}', [VideoController::class, 'show']);
    Route::post('/videos', [VideoController::class, 'store']);
    Route::put('/videos/{contentId}/counts', [VideoController::class, 'updateCounts']);
    Route::get('/videos/stats/overview', [VideoController::class, 'stats']);
    
    // Video interaction routes (public)
    Route::post('/videos/interactions/like', [VideoInteractionController::class, 'toggleLike']);
    Route::post('/videos/interactions/share', [VideoInteractionController::class, 'addShare']);
    Route::post('/videos/interactions/view', [VideoInteractionController::class, 'addView']);
    Route::post('/videos/interactions/check', [VideoInteractionController::class, 'checkInteraction']);
    Route::get('/videos/interactions/user', [VideoInteractionController::class, 'getUserInteractions']);
    
    // Video comment routes (public) - no caching for real-time data
    Route::get('/videos/comments', [VideoCommentController::class, 'index']);
    Route::post('/videos/comments', [VideoCommentController::class, 'store']);
    Route::put('/videos/comments/{id}', [VideoCommentController::class, 'update']);
    Route::delete('/videos/comments/{id}', [VideoCommentController::class, 'destroy']);
    Route::post('/videos/comments/like', [VideoCommentController::class, 'toggleLike']);
    Route::post('/videos/comments/pin/{id}', [VideoCommentController::class, 'togglePin']);
    Route::post('/videos/comments/check-like', [VideoCommentController::class, 'checkLike']);
    
    // Subscriber routes (public)
    Route::get('/subscribers', [SubscriberController::class, 'index']);
    Route::post('/subscribers', [SubscriberController::class, 'store']);
    Route::get('/subscribers/{id}', [SubscriberController::class, 'show']);
    Route::put('/subscribers/{id}', [SubscriberController::class, 'update']);
    Route::delete('/subscribers/{id}', [SubscriberController::class, 'destroy']);
    Route::get('/subscribers/stats/overview', [SubscriberController::class, 'stats']);
    Route::put('/subscribers/{id}/activity', [SubscriberController::class, 'updateActivity']);
});

// Health check route
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Simple test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'Stargate.ci API is working!',
        'timestamp' => now()
    ]);
});