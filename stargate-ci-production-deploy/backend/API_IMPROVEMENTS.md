# API Improvements Documentation

## Overview
This document outlines the improvements made to the Stargate.ci API to enhance connectivity, error handling, rate limiting, and caching.

## Implemented Features

### 1. Global Error Handling
**Middleware**: `ApiErrorHandler`
**Location**: `app/Http/Middleware/ApiErrorHandler.php`

**Features**:
- Catches and formats all API exceptions
- Provides consistent error response format
- Handles common exceptions:
  - `ValidationException` → 422 Unprocessable Entity
  - `ModelNotFoundException` → 404 Not Found
  - `NotFoundHttpException` → 404 Not Found
  - `MethodNotAllowedHttpException` → 405 Method Not Allowed
  - `AuthenticationException` → 401 Unauthorized
  - `AuthorizationException` → 403 Forbidden
  - Generic `Throwable` → 500 Internal Server Error

**Response Format**:
```json
{
  "success": false,
  "error": "Error Type",
  "message": "Human-readable error message",
  "timestamp": "2025-10-12T20:44:39+00:00"
}
```

**Validation Error Response**:
```json
{
  "success": false,
  "error": "Validation Error",
  "message": "The given data was invalid.",
  "errors": {
    "field": ["error message"]
  },
  "timestamp": "2025-10-12T20:44:39+00:00"
}
```

### 2. Rate Limiting
**Middleware**: `ApiRateLimiter`
**Location**: `app/Http/Middleware/ApiRateLimiter.php`

**Features**:
- Configurable rate limits per route/group
- Separate rate limiting for authenticated and guest users
- Rate limit headers in all responses
- Graceful handling of rate limit exceeded

**Configuration**:
- Default: 100 requests per minute
- Authentication routes: 10 requests per minute
- External API routes: Custom limits

**Response Headers**:
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 99
X-RateLimit-Reset: 1760302574
```

**Rate Limit Exceeded Response** (429):
```json
{
  "success": false,
  "error": "Too Many Requests",
  "message": "Rate limit exceeded. Please try again later.",
  "retry_after": 60,
  "timestamp": "2025-10-12T20:44:39+00:00"
}
```

### 3. Response Caching
**Middleware**: `ApiResponseCache`
**Location**: `app/Http/Middleware/ApiResponseCache.php`

**Features**:
- Automatic caching of GET requests
- Configurable TTL (Time To Live) per route/group
- Cache key generation based on URI and query parameters
- User-specific caching for authenticated requests
- Cache headers for debugging

**Cache Configuration**:
- Articles: 30 minutes
- FAQs: 120 minutes (2 hours)
- Content: 60 minutes
- Community Posts: 15 minutes (default)

**Cache Headers**:
```
X-Cache-Hit: true/false
X-Cache-Key: api_cache:561d069eef6afdb299a36a8bfc0f2649
X-Cache-TTL: 1800
```

## Usage Examples

### Error Handling
All API errors are automatically caught and formatted. No additional code needed in controllers.

```php
// Controller code remains simple
public function show($id) {
    $article = Article::findOrFail($id); // Throws ModelNotFoundException if not found
    return response()->json(['success' => true, 'data' => $article]);
}
```

### Rate Limiting
Apply to routes or route groups:

```php
// Single route
Route::get('/articles', [ArticleController::class, 'index'])
    ->middleware('api.throttle:60,1'); // 60 requests per 1 minute

// Route group
Route::middleware('api.throttle:100,1')->group(function () {
    // All routes in this group have 100 requests per minute limit
});
```

### Caching
Apply to GET routes or route groups:

```php
// Single route
Route::get('/articles', [ArticleController::class, 'index'])
    ->middleware('api.cache:30'); // Cache for 30 minutes

// Route group
Route::middleware('api.cache:60')->group(function () {
    // All GET routes in this group are cached for 60 minutes
});
```

## Testing

### Test Rate Limiting
```bash
# Make multiple requests and check headers
curl -I http://localhost:8000/api/v1/articles
```

### Test Caching
```bash
# First request (cache miss)
curl -v http://localhost:8000/api/v1/articles 2>&1 | grep X-Cache-Hit
# Output: X-Cache-Hit: false

# Second request (cache hit)
curl -v http://localhost:8000/api/v1/articles 2>&1 | grep X-Cache-Hit
# Output: X-Cache-Hit: true
```

### Test Error Handling
```bash
# Test 404 error
curl http://localhost:8000/api/v1/articles/999999

# Expected response:
# {
#   "success": false,
#   "message": "Article not found",
#   "error": "No query results for model [App\\Models\\Article]."
# }
```

## Performance Benefits

1. **Rate Limiting**:
   - Protects against abuse and DDoS attacks
   - Ensures fair resource allocation
   - Prevents server overload

2. **Caching**:
   - Reduces database queries
   - Faster response times
   - Lower server load
   - Better scalability

3. **Error Handling**:
   - Consistent API responses
   - Better debugging with detailed error messages
   - Improved developer experience

## Cache Management

### Clear All Cache
```bash
php artisan cache:clear
```

### Clear Specific Cache Key
```php
use Illuminate\Support\Facades\Cache;

Cache::forget('api_cache:561d069eef6afdb299a36a8bfc0f2649');
```

### Clear All API Cache
```php
use Illuminate\Support\Facades\Cache;

// Clear all cache keys starting with 'api_cache:'
Cache::flush(); // Clears all cache (use with caution)
```

## Future Improvements

1. **Advanced Caching**:
   - Cache invalidation on data updates
   - Cache warming strategies
   - Redis integration for distributed caching

2. **Rate Limiting**:
   - Per-user rate limits
   - Premium tier with higher limits
   - Sliding window rate limiting

3. **Error Handling**:
   - Error tracking integration (Sentry, Bugsnag)
   - Custom error pages for different error types
   - Error analytics and monitoring

4. **Monitoring**:
   - API usage statistics
   - Performance metrics
   - Cache hit/miss ratios
   - Rate limit violations tracking

## Configuration

All middleware are registered in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'api.error' => \App\Http\Middleware\ApiErrorHandler::class,
        'api.throttle' => \App\Http\Middleware\ApiRateLimiter::class,
        'api.cache' => \App\Http\Middleware\ApiResponseCache::class,
    ]);
    
    // Apply error handler to all API routes
    $middleware->appendToGroup('api', [
        \App\Http\Middleware\ApiErrorHandler::class,
    ]);
});
```

## Conclusion

These improvements significantly enhance the API's reliability, performance, and developer experience. The middleware stack provides a solid foundation for scaling the application while maintaining consistent behavior across all endpoints.

