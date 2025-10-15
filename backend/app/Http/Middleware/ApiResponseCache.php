<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  int  $ttl  Time to live in minutes (default: 60)
     */
    public function handle(Request $request, Closure $next, int $ttl = 60): Response
    {
        // Only cache GET requests
        if (!$request->isMethod('GET')) {
            return $next($request);
        }
        
        // Generate cache key from request
        $cacheKey = $this->generateCacheKey($request);
        
        // Check if response is cached
        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            
            return response()->json($cachedData['data'], $cachedData['status'])
                ->withHeaders(array_merge($cachedData['headers'], [
                    'X-Cache-Hit' => 'true',
                    'X-Cache-Key' => $cacheKey,
                ]));
        }
        
        // Process request
        $response = $next($request);
        
        // Only cache successful JSON responses
        if ($response instanceof JsonResponse && $response->getStatusCode() === 200) {
            $data = [
                'data' => $response->getData(true),
                'status' => $response->getStatusCode(),
                'headers' => $this->getHeadersToCache($response),
            ];
            
            // Store in cache
            Cache::put($cacheKey, $data, now()->addMinutes($ttl));
            
            // Add cache headers
            $response->headers->add([
                'X-Cache-Hit' => 'false',
                'X-Cache-Key' => $cacheKey,
                'X-Cache-TTL' => $ttl * 60,
            ]);
        }
        
        return $response;
    }
    
    /**
     * Generate a unique cache key for the request
     */
    protected function generateCacheKey(Request $request): string
    {
        $uri = $request->getRequestUri();
        $query = $request->query();
        
        // Sort query parameters for consistency
        ksort($query);
        
        $queryString = http_build_query($query);
        $key = 'api_cache:' . md5($uri . $queryString);
        
        // Include user ID if authenticated
        if ($user = $request->user()) {
            $key .= ':user_' . $user->id;
        }
        
        return $key;
    }
    
    /**
     * Get headers that should be cached
     */
    protected function getHeadersToCache(Response $response): array
    {
        $headersToCache = [
            'Content-Type',
            'X-RateLimit-Limit',
            'X-RateLimit-Remaining',
        ];
        
        $headers = [];
        foreach ($headersToCache as $header) {
            if ($response->headers->has($header)) {
                $headers[$header] = $response->headers->get($header);
            }
        }
        
        return $headers;
    }
}
