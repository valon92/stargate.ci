<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class ApiRateLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $limit = '60'): Response
    {
        // Parse limit (format: "requests,minutes")
        $parts = explode(',', $limit);
        $maxAttempts = (int) ($parts[0] ?? 60);
        $decayMinutes = (int) ($parts[1] ?? 1);
        
        // Create a unique key for this user/IP
        $key = $this->resolveRequestSignature($request);
        
        // Check if rate limit is exceeded
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'error' => 'Too Many Requests',
                'message' => 'Rate limit exceeded. Please try again later.',
                'retry_after' => $retryAfter,
                'timestamp' => now()->toIso8601String()
            ], 429)->withHeaders([
                'X-RateLimit-Limit' => $maxAttempts,
                'X-RateLimit-Remaining' => 0,
                'Retry-After' => $retryAfter,
                'X-RateLimit-Reset' => now()->addSeconds($retryAfter)->timestamp,
            ]);
        }
        
        // Hit the rate limiter
        RateLimiter::hit($key, $decayMinutes * 60);
        
        $response = $next($request);
        
        // Add rate limit headers to response
        $remaining = RateLimiter::remaining($key, $maxAttempts);
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $remaining),
            'X-RateLimit-Reset' => now()->addMinutes($decayMinutes)->timestamp,
        ]);
        
        return $response;
    }
    
    /**
     * Resolve the request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        // Use user ID if authenticated, otherwise use IP
        if ($user = $request->user()) {
            return 'api_rate_limit:user_' . $user->id;
        }
        
        return 'api_rate_limit:ip_' . $request->ip();
    }
}
