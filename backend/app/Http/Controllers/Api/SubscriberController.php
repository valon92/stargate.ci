<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    /**
     * Get all subscribers
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $subscribers = Subscriber::active()
                ->orderBy('subscribed_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $subscribers->items(),
                'pagination' => [
                    'current_page' => $subscribers->currentPage(),
                    'last_page' => $subscribers->lastPage(),
                    'per_page' => $subscribers->perPage(),
                    'total' => $subscribers->total()
                ],
                'message' => 'Subscribers retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subscribers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new subscriber
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:subscribers,username',
                'email' => 'required|email|max:255|unique:subscribers,email',
                'password' => 'required|string|min:6',
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'profession' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'interests' => 'nullable|array',
                'avatar' => 'nullable|string|max:255',
                'email_notifications' => 'nullable|boolean',
                'preferences' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $subscriber = Subscriber::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password, // Will be hashed by Laravel
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'country' => $request->country,
                'profession' => $request->profession,
                'company' => $request->company,
                'interests' => $request->interests,
                'avatar' => $request->avatar,
                'email_notifications' => $request->email_notifications ?? true,
                'subscribed_at' => now(),
                'last_activity_at' => now(),
                'preferences' => $request->preferences
            ]);

            return response()->json([
                'success' => true,
                'data' => $subscriber,
                'message' => 'Subscriber created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subscriber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific subscriber
     */
    public function show(int $id): JsonResponse
    {
        try {
            $subscriber = Subscriber::find($id);

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $subscriber,
                'message' => 'Subscriber retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subscriber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subscriber by email
     */
    public function getByEmail(string $email): JsonResponse
    {
        try {
            $subscriber = Subscriber::where('email', $email)->first();

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $subscriber,
                'message' => 'Subscriber retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subscriber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a subscriber
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $subscriber = Subscriber::find($id);

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'username' => 'sometimes|string|max:255|unique:subscribers,username,' . $id,
                'email' => 'sometimes|email|max:255|unique:subscribers,email,' . $id,
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'profession' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'interests' => 'nullable|array',
                'avatar' => 'nullable|string|max:255',
                'email_notifications' => 'nullable|boolean',
                'preferences' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $subscriber->update($request->only([
                'username', 'email', 'first_name', 'last_name', 'country',
                'profession', 'company', 'interests', 'avatar',
                'email_notifications', 'preferences'
            ]));

            return response()->json([
                'success' => true,
                'data' => $subscriber->fresh(),
                'message' => 'Subscriber updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subscriber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a subscriber (soft delete by setting is_active to false)
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $subscriber = Subscriber::find($id);

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $subscriber->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Subscriber deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subscriber',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subscriber statistics
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total_subscribers' => Subscriber::active()->count(),
                'subscribers_this_month' => Subscriber::active()
                    ->where('subscribed_at', '>=', now()->startOfMonth())
                    ->count(),
                'countries_count' => Subscriber::active()
                    ->whereNotNull('country')
                    ->distinct('country')
                    ->count('country'),
                'top_countries' => Subscriber::active()
                    ->selectRaw('country, COUNT(*) as count')
                    ->whereNotNull('country')
                    ->groupBy('country')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->pluck('count', 'country'),
                'professions_count' => Subscriber::active()
                    ->whereNotNull('profession')
                    ->distinct('profession')
                    ->count('profession')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Subscriber statistics retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subscriber statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update subscriber last activity
     */
    public function updateActivity(int $id): JsonResponse
    {
        try {
            $subscriber = Subscriber::find($id);

            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $subscriber->updateLastActivity();

            return response()->json([
                'success' => true,
                'data' => $subscriber->fresh(),
                'message' => 'Subscriber activity updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subscriber activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}