<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create or update subscriber for interactions/comments
        $username = strtolower(str_replace(' ', '_', $request->name));
        $subscriber = Subscriber::firstOrCreate(
            ['email' => $request->email],
            [
                'username' => $username . '_' . $user->id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'first_name' => $request->name,
                'is_active' => true,
                'email_notifications' => true,
                'subscribed_at' => now(),
                'last_activity_at' => now()
            ]
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'subscriber_id' => $subscriber->id, // Include subscriber ID for frontend
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        
        // Get or create subscriber for interactions/comments
        $subscriber = Subscriber::firstOrCreate(
            ['email' => $user->email],
            [
                'username' => strtolower(str_replace(' ', '_', $user->name)) . '_' . $user->id,
                'email' => $user->email,
                'first_name' => $user->name,
                'is_active' => true,
                'email_notifications' => true,
                'subscribed_at' => now(),
                'last_activity_at' => now()
            ]
        );
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'subscriber_id' => $subscriber->id, // Include subscriber ID for frontend
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        $user = $request->user();
        
        // Get subscriber for interactions/comments
        $subscriber = Subscriber::where('email', $user->email)->first();
        
        $userData = $user->toArray();
        if ($subscriber) {
            $userData['subscriber_id'] = $subscriber->id;
        }
        
        return response()->json([
            'success' => true,
            'data' => $userData
        ]);
    }
}
