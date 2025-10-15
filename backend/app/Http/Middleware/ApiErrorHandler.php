<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class ApiErrorHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
            
            // If response is already a JSON response with error, format it
            if ($response instanceof JsonResponse) {
                $data = $response->getData(true);
                if (isset($data['error']) && !isset($data['success'])) {
                    return response()->json([
                        'success' => false,
                        'error' => $data['error'],
                        'message' => $data['message'] ?? 'An error occurred',
                        'timestamp' => now()->toIso8601String()
                    ], $response->getStatusCode());
                }
            }
            
            return $response;
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation Error',
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
                'timestamp' => now()->toIso8601String()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Not Found',
                'message' => 'The requested resource was not found.',
                'timestamp' => now()->toIso8601String()
            ], 404);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Not Found',
                'message' => 'The requested endpoint was not found.',
                'timestamp' => now()->toIso8601String()
            ], 404);
        } catch (MethodNotAllowedHttpException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Method Not Allowed',
                'message' => 'The HTTP method used is not allowed for this endpoint.',
                'timestamp' => now()->toIso8601String()
            ], 405);
        } catch (AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthenticated',
                'message' => 'You must be authenticated to access this resource.',
                'timestamp' => now()->toIso8601String()
            ], 401);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Forbidden',
                'message' => 'You do not have permission to access this resource.',
                'timestamp' => now()->toIso8601String()
            ], 403);
        } catch (Throwable $e) {
            // Log the error for debugging
            \Log::error('API Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return generic error response
            return response()->json([
                'success' => false,
                'error' => 'Internal Server Error',
                'message' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred. Please try again later.',
                'timestamp' => now()->toIso8601String()
            ], 500);
        }
    }
}
