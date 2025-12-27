<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\VideoInteraction;
use App\Models\VideoComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Get user profile with subscriber data
     */
    public function getProfile(Request $request)
    {
        $user = $request->user();
        $subscriber = Subscriber::where('email', $user->email)->first();
        
        $profileData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'subscriber_id' => $subscriber?->id,
        ];
        
        if ($subscriber) {
            $profileData = array_merge($profileData, [
                'username' => $subscriber->username,
                'first_name' => $subscriber->first_name,
                'last_name' => $subscriber->last_name,
                'country' => $subscriber->country,
                'profession' => $subscriber->profession,
                'company' => $subscriber->company,
                'avatar' => $subscriber->avatar,
                'bio' => $subscriber->bio ?? null,
                'interests' => $subscriber->interests ?? [],
                'email_notifications' => $subscriber->email_notifications ?? true,
            ]);
        }
        
        // Get stats
        $stats = $this->getUserStats($subscriber?->id);
        $profileData['stats'] = $stats;
        
        return response()->json([
            'success' => true,
            'data' => $profileData
        ]);
    }
    
    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'profession' => 'sometimes|string|max:255',
            'company' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:1000',
            'interests' => 'sometimes|array',
            'email_notifications' => 'sometimes|boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = $request->user();
        $subscriber = Subscriber::where('email', $user->email)->first();
        
        // Update user
        if ($request->has('name')) {
            $user->name = $request->name;
            $user->save();
        }
        
        // Update or create subscriber
        if (!$subscriber) {
            $subscriber = Subscriber::create([
                'email' => $user->email,
                'username' => strtolower(str_replace(' ', '_', $user->name)) . '_' . $user->id,
                'first_name' => $user->name,
                'is_active' => true,
                'email_notifications' => true,
                'subscribed_at' => now(),
                'last_activity_at' => now()
            ]);
        }
        
        // Update subscriber fields
        $subscriberData = $request->only([
            'first_name', 'last_name', 'country', 'profession', 
            'company', 'bio', 'interests', 'email_notifications'
        ]);
        
        if (isset($subscriberData['name']) && !isset($subscriberData['first_name'])) {
            $subscriberData['first_name'] = $subscriberData['name'];
        }
        
        $subscriber->update($subscriberData);
        $subscriber->last_activity_at = now();
        $subscriber->save();
        
        // Get updated profile with stats
        $profileData = $this->getProfileData($user, $subscriber);
        
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $profileData
        ]);
    }
    
    /**
     * Get user statistics
     */
    public function getStats(Request $request)
    {
        $user = $request->user();
        $subscriber = Subscriber::where('email', $user->email)->first();
        
        $stats = $this->getUserStats($subscriber?->id);
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = $request->user();
        
        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }
        
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        // Update subscriber password if exists
        $subscriber = Subscriber::where('email', $user->email)->first();
        if ($subscriber) {
            $subscriber->password = Hash::make($request->new_password);
            $subscriber->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
    
    /**
     * Get user statistics
     */
    private function getUserStats(?int $subscriberId): array
    {
        if (!$subscriberId) {
            return [
                'total_comments' => 0,
                'total_likes' => 0,
                'total_shares' => 0,
                'articles_read' => 0,
                'events_registered' => 0,
                'last_activity' => null,
            ];
        }
        
        $totalComments = VideoComment::where('subscriber_id', $subscriberId)->count();
        $totalLikes = VideoInteraction::where('subscriber_id', $subscriberId)
            ->where('interaction_type', 'like')
            ->count();
        $totalShares = VideoInteraction::where('subscriber_id', $subscriberId)
            ->where('interaction_type', 'share')
            ->count();
        
        // Get last activity
        $lastActivity = Subscriber::where('id', $subscriberId)
            ->value('last_activity_at');
        
        return [
            'total_comments' => $totalComments,
            'total_likes' => $totalLikes,
            'total_shares' => $totalShares,
            'articles_read' => 0, // Can be implemented later
            'events_registered' => 0, // Can be implemented later
            'last_activity' => $lastActivity ? $lastActivity->toIso8601String() : null,
        ];
    }
    
    /**
     * Get profile data
     */
    private function getProfileData(User $user, ?Subscriber $subscriber): array
    {
        $profileData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'subscriber_id' => $subscriber?->id,
        ];
        
        if ($subscriber) {
            $profileData = array_merge($profileData, [
                'username' => $subscriber->username,
                'first_name' => $subscriber->first_name,
                'last_name' => $subscriber->last_name,
                'country' => $subscriber->country,
                'profession' => $subscriber->profession,
                'company' => $subscriber->company,
                'avatar' => $subscriber->avatar,
                'bio' => $subscriber->bio ?? null,
                'interests' => $subscriber->interests ?? [],
                'email_notifications' => $subscriber->email_notifications ?? true,
            ]);
            
            // Get stats
            $stats = $this->getUserStats($subscriber->id);
            $profileData['stats'] = $stats;
        } else {
            $profileData['stats'] = $this->getUserStats(null);
        }
        
        return $profileData;
    }
}

