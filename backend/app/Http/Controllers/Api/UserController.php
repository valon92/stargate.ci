<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get user profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->profile;
        $preferences = $user->preferences;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                ],
                'profile' => $profile ? [
                    'company' => $profile->company,
                    'company_size' => $profile->company_size,
                    'industry' => $profile->industry,
                    'investment_capacity' => $profile->investment_capacity,
                    'interests' => $profile->interests,
                    'location' => $profile->location,
                    'phone' => $profile->phone,
                    'website' => $profile->website,
                    'bio' => $profile->bio,
                    'preferred_contact' => $profile->preferred_contact,
                ] : null,
                'preferences' => $preferences ? $preferences->pluck('preference_value', 'preference_key')->toArray() : []
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'company' => 'sometimes|string|max:255',
            'company_size' => 'sometimes|string|in:startup,small,medium,large,enterprise',
            'industry' => 'sometimes|string|max:255',
            'investment_capacity' => 'sometimes|string|in:low,medium,high,enterprise',
            'interests' => 'sometimes|array',
            'location' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'website' => 'sometimes|url|max:255',
            'bio' => 'sometimes|string|max:1000',
            'preferred_contact' => 'sometimes|string|in:email,phone,both',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update user basic info
        if ($request->has('name') || $request->has('email')) {
            $user->update($request->only(['name', 'email']));
        }

        // Update or create profile
        $profileData = $request->only([
            'company', 'company_size', 'industry', 'investment_capacity',
            'interests', 'location', 'phone', 'website', 'bio', 'preferred_contact'
        ]);

        if (!empty($profileData)) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]
        ]);
    }

    /**
     * Get user dashboard data
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get user statistics
        $stats = [
            'tutorials_completed' => 0, // Will be implemented with content tracking
            'community_posts' => 0, // Will be implemented with community tracking
            'search_queries' => 0, // Will be implemented with search tracking
            'last_login' => $user->updated_at,
        ];

        // Get recent activity (placeholder)
        $recentActivity = [
            [
                'type' => 'tutorial_completed',
                'title' => 'AI Fundamentals',
                'timestamp' => now()->subHours(2),
            ],
            [
                'type' => 'community_post',
                'title' => 'Stargate Project Discussion',
                'timestamp' => now()->subDays(1),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_activity' => $recentActivity,
                'recommendations' => [
                    'tutorials' => [],
                    'community_posts' => [],
                ]
            ]
        ]);
    }

    /**
     * Get user preferences
     */
    public function getPreferences(Request $request): JsonResponse
    {
        $user = $request->user();
        $preferences = $user->preferences()->get();

        $formattedPreferences = $preferences->mapWithKeys(function ($pref) {
            return [$pref->preference_key => $pref->preference_value];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'preferences' => $formattedPreferences
            ]
        ]);
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'theme' => 'sometimes|string|in:light,dark,auto',
            'language' => 'sometimes|string|in:en,fr,de,es,it,ar,pt,ru,ja,zh',
            'notifications' => 'sometimes|array',
            'timezone' => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->all() as $key => $value) {
            $user->preferences()->updateOrCreate(
                ['preference_key' => $key],
                [
                    'preference_value' => is_array($value) ? json_encode($value) : $value,
                    'preference_type' => is_array($value) ? 'json' : 'string'
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated successfully'
        ]);
    }

    /**
     * Get all users (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $users = User::with(['profile', 'roles'])
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get specific user (admin only)
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['profile', 'roles', 'preferences']);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Update user (admin only)
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $request->only(['name', 'email']);
        
        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Delete user (admin only)
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Assign role to user (admin only)
     */
    public function assignRole(Request $request, User $user): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|exists:user_roles,slug',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'Role assigned successfully'
        ]);
    }
}