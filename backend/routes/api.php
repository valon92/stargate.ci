<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\FAQController;
use App\Http\Controllers\Api\ContactController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::prefix('v1')->group(function () {
    // Articles
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);
    
    // FAQs
    Route::get('/faqs', [FAQController::class, 'index']);
    Route::get('/faqs/{id}', [FAQController::class, 'show']);
    
    // Contact
    Route::post('/contact', [ContactController::class, 'store']);
});

// Admin routes (protected by middleware)
Route::prefix('v1/admin')->middleware(['auth:sanctum'])->group(function () {
    // Articles management
    Route::apiResource('articles', ArticleController::class);
    
    // FAQs management
    Route::apiResource('faqs', FAQController::class);
    
    // Contact messages management
    Route::get('/contact-messages', [ContactController::class, 'index']);
    Route::get('/contact-messages/{id}', [ContactController::class, 'show']);
    Route::put('/contact-messages/{id}/read', [ContactController::class, 'markAsRead']);
    Route::delete('/contact-messages/{id}', [ContactController::class, 'destroy']);
});
