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
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\EventsController;
use App\Http\Controllers\Api\EventRegistrationController;
use App\Http\Controllers\Api\VoiceActionsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\JobPostController;
use App\Http\Controllers\Api\ProductController;

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

// Authentication routes (no rate limiting for auth)
Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/auth/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    
    // Profile routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [ProfileController::class, 'getProfile']);
        Route::put('/profile', [ProfileController::class, 'updateProfile']);
        Route::get('/profile/stats', [ProfileController::class, 'getStats']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    });
    
    // Test route
    Route::get('/auth/test', function() {
        return response()->json(['message' => 'Auth routes working!']);
    });
});

// Public routes with rate limiting
Route::prefix('v1')->middleware('api.throttle:1000,1')->group(function () {
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
        Route::post('/videos', [VideoController::class, 'store']);
        Route::get('/videos/stats/overview', [VideoController::class, 'stats']);
        
        // Video comment routes (public) - no caching for real-time data - MUST be before {contentId} route
        Route::get('/videos/comments', [VideoCommentController::class, 'index']);
        Route::post('/videos/comments', [VideoCommentController::class, 'store']);
        Route::put('/videos/comments/{id}', [VideoCommentController::class, 'update']);
        Route::delete('/videos/comments/{id}', [VideoCommentController::class, 'destroy']);
        Route::post('/videos/comments/like', [VideoCommentController::class, 'toggleLike']);
        Route::post('/videos/comments/pin/{id}', [VideoCommentController::class, 'togglePin']);
        Route::post('/videos/comments/check-like', [VideoCommentController::class, 'checkLike']);
        
        // Video interaction routes (public)
        Route::post('/videos/interactions/like', [VideoInteractionController::class, 'toggleLike']);
        Route::post('/videos/interactions/share', [VideoInteractionController::class, 'addShare']);
        Route::post('/videos/interactions/view', [VideoInteractionController::class, 'addView']);
        Route::post('/videos/interactions/check', [VideoInteractionController::class, 'checkInteraction']);
        Route::get('/videos/interactions/user', [VideoInteractionController::class, 'getUserInteractions']);
        
        // Video routes with contentId - MUST be after specific routes
        Route::get('/videos/{contentId}', [VideoController::class, 'show']);
        Route::put('/videos/{contentId}/counts', [VideoController::class, 'updateCounts']);
    
    // Test route for debugging (outside middleware)
    Route::get('/videos/test', function() {
        return response()->json(['message' => 'Video route works!']);
    });
    
    // Search routes (public with rate limiting)
    Route::middleware('api.throttle:200,1')->group(function () {
        Route::get('/search', [SearchController::class, 'search']);
        Route::get('/search/suggestions', [SearchController::class, 'suggestions']);
        Route::get('/search/filters', [SearchController::class, 'filters']);
        Route::get('/search/analytics', [SearchController::class, 'analytics']);
    });
    
    // Protected search routes (require authentication)
    Route::middleware(['auth:sanctum', 'api.throttle:100,1'])->group(function () {
        Route::post('/search/save', [SearchController::class, 'saveSearch']);
        Route::get('/search/saved', [SearchController::class, 'savedSearches']);
    });
    
    // Subscriber routes (public)
    Route::get('/subscribers', [SubscriberController::class, 'index']);
    Route::post('/subscribers', [SubscriberController::class, 'store']);
    Route::get('/subscribers/email/{email}', [SubscriberController::class, 'getByEmail']);
    Route::get('/subscribers/stats/overview', [SubscriberController::class, 'stats']);
    Route::get('/subscribers/{id}', [SubscriberController::class, 'show']);
    Route::put('/subscribers/{id}', [SubscriberController::class, 'update']);
    Route::delete('/subscribers/{id}', [SubscriberController::class, 'destroy']);
    Route::put('/subscribers/{id}/activity', [SubscriberController::class, 'updateActivity']);
    
    // Events routes (public)
    Route::get('/events', [EventsController::class, 'index']);
    Route::get('/events/categories', [EventsController::class, 'categories']);
    Route::get('/events/upcoming', [EventsController::class, 'upcoming']);
    Route::get('/events/category/{category}', [EventsController::class, 'byCategory']);
    Route::get('/events/search', [EventsController::class, 'search']);
    Route::get('/events/sync-status', [EventsController::class, 'syncStatus']);
    
    Route::get('/events/{id}', [EventsController::class, 'show']);
    
    // Community routes (public read, authenticated write)
    Route::get('/community/posts', [CommunityController::class, 'index']);
    Route::get('/community/posts/{id}', [CommunityController::class, 'show']);
    Route::get('/community/categories', [CommunityController::class, 'categories']);
    Route::post('/community/posts/{id}/share', [CommunityController::class, 'share']); // Public share route
    
    // Jobs routes (public read, authenticated write)
    // IMPORTANT: Specific routes must come before parameterized routes
    Route::get('/jobs/categories', [JobPostController::class, 'categories']);
    Route::get('/jobs/types', [JobPostController::class, 'jobTypes']);
    Route::get('/jobs/experience-levels', [JobPostController::class, 'experienceLevels']);
    Route::get('/jobs', [JobPostController::class, 'index']);
    Route::get('/jobs/{id}', [JobPostController::class, 'show']);
    
    // Products routes (public read, authenticated write)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    
    // Authenticated community routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/community/posts', [CommunityController::class, 'store']);
        Route::put('/community/posts/{id}', [CommunityController::class, 'update']);
        Route::delete('/community/posts/{id}', [CommunityController::class, 'destroy']);
        Route::post('/community/posts/{id}/like', [CommunityController::class, 'like']);
        Route::post('/community/posts/{id}/comments', [CommunityController::class, 'addComment']);
        Route::post('/community/comments/{id}/like', [CommunityController::class, 'likeComment']);
        
        // Authenticated jobs routes
        Route::post('/jobs', [JobPostController::class, 'store']);
        Route::put('/jobs/{id}', [JobPostController::class, 'update']);
        Route::delete('/jobs/{id}', [JobPostController::class, 'destroy']);
        
        // Authenticated products routes
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::get('/products/my/list', [ProductController::class, 'myProducts']);
    });
    
    // Events sync routes (with rate limiting)
    Route::middleware('api.throttle:10,1')->group(function () {
        Route::post('/events/sync', [EventsController::class, 'sync']);
    });
    
    // Event registration routes (public)
    Route::post('/events/register', [EventRegistrationController::class, 'register']);
    Route::get('/events/{eventId}/stats', [EventRegistrationController::class, 'stats']);
    Route::get('/events/check-registration', [EventRegistrationController::class, 'checkRegistration']);
    Route::get('/events/user-events', [EventRegistrationController::class, 'userEvents']);
    Route::delete('/events/registrations/{registrationId}', [EventRegistrationController::class, 'cancel']);
    
    // Test reminder route (for testing)
    Route::post('/events/test-reminder/{registrationId}', [EventRegistrationController::class, 'sendTestReminder']);
    
    // Organizer routes (authenticated)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/events', [EventsController::class, 'store']);
        Route::put('/events/{id}', [EventsController::class, 'update']);
        Route::delete('/events/{id}', [EventsController::class, 'destroy']);
        Route::get('/events/my-events', [EventsController::class, 'myEvents']);
    });
    
    // Voice Actions SDK routes (public)
    Route::get('/commands', [VoiceActionsController::class, 'getCommands']);
    Route::get('/commands/demo', [VoiceActionsController::class, 'getDemoCommands']);
});

// Voice Actions SDK routes without v1 prefix (for SDK compatibility)
Route::middleware('api.throttle:1000,1')->group(function () {
    Route::get('/commands', [VoiceActionsController::class, 'getCommands']);
    Route::get('/commands/demo', [VoiceActionsController::class, 'getDemoCommands']);
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