<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'api.error' => \App\Http\Middleware\ApiErrorHandler::class,
            'api.throttle' => \App\Http\Middleware\ApiRateLimiter::class,
            'api.cache' => \App\Http\Middleware\ApiResponseCache::class,
            'cors' => \App\Http\Middleware\CorsMiddleware::class,
        ]);
        
        // Apply error handler and CORS to all API routes
        $middleware->appendToGroup('api', [
            \App\Http\Middleware\CorsMiddleware::class,
            \App\Http\Middleware\ApiErrorHandler::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
