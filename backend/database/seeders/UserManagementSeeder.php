<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use App\Models\TwoFactorAuth;
use App\Models\EmailVerification;
use App\Models\UserSession;
use Illuminate\Support\Facades\Hash;

class UserManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles
        $adminRole = UserRole::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full system access',
                'permissions' => [
                    'users.create', 'users.read', 'users.update', 'users.delete',
                    'content.create', 'content.read', 'content.update', 'content.delete',
                    'analytics.read', 'settings.update', 'roles.manage'
                ],
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $moderatorRole = UserRole::firstOrCreate(
            ['slug' => 'moderator'],
            [
                'name' => 'Moderator',
                'description' => 'Content moderation and user management',
                'permissions' => [
                    'users.read', 'users.update',
                    'content.create', 'content.read', 'content.update',
                    'analytics.read'
                ],
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $userRole = UserRole::firstOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'User',
                'description' => 'Standard user access',
                'permissions' => [
                    'profile.read', 'profile.update',
                    'content.read', 'community.participate'
                ],
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@stargate.ci'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
            ]
        );

        // Assign admin role
        if (!$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
            $adminRole->assignToUser($adminUser->id, $adminUser->id);
        }

        // Create admin profile
        UserProfile::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'company' => 'Stargate.ci',
                'company_size' => 'enterprise',
                'industry' => 'Technology',
                'investment_capacity' => 'enterprise',
                'interests' => ['AI', 'Cloud Computing', 'Advanced Computing'],
                'location' => 'Global',
                'preferred_contact' => 'email',
                'bio' => 'Administrator of Stargate.ci platform',
                'email_notifications' => true,
                'sms_notifications' => false,
                'preferences' => [
                    'theme' => 'dark',
                    'language' => 'en',
                    'timezone' => 'UTC'
                ],
                'last_active_at' => now(),
            ]
        );

        // Create demo user
        $demoUser = User::firstOrCreate(
            ['email' => 'demo@stargate.ci'],
            [
                'name' => 'Demo User',
                'email_verified_at' => now(),
                'password' => Hash::make('demo123'),
            ]
        );

        // Assign user role
        if (!$demoUser->roles()->where('role_id', $userRole->id)->exists()) {
            $userRole->assignToUser($demoUser->id, $adminUser->id);
        }

        // Create demo profile
        UserProfile::firstOrCreate(
            ['user_id' => $demoUser->id],
            [
                'company' => 'Tech Startup',
                'company_size' => 'startup',
                'industry' => 'Technology',
                'investment_capacity' => 'medium',
                'interests' => ['AI', 'Machine Learning', 'Data Science'],
                'location' => 'San Francisco, CA',
                'preferred_contact' => 'email',
                'bio' => 'Interested in Stargate project and AI technologies',
                'email_notifications' => true,
                'sms_notifications' => false,
                'preferences' => [
                    'theme' => 'light',
                    'language' => 'en',
                    'timezone' => 'America/Los_Angeles'
                ],
                'last_active_at' => now(),
            ]
        );

        // Create 2FA for admin
        TwoFactorAuth::create([
            'user_id' => $adminUser->id,
            'secret_key' => 'JBSWY3DPEHPK3PXP',
            'qr_code' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==',
            'backup_codes' => ['123456', '234567', '345678', '456789', '567890'],
            'is_enabled' => true,
            'enabled_at' => now(),
        ]);

        // Create email verification for demo user
        EmailVerification::create([
            'user_id' => $demoUser->id,
            'email' => 'demo@stargate.ci',
            'token' => 'verified_token_123',
            'expires_at' => now()->addDays(1),
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        // Create active sessions
        UserSession::create([
            'user_id' => $adminUser->id,
            'session_id' => 'admin_session_' . uniqid(),
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'device_type' => 'desktop',
            'browser' => 'Chrome',
            'os' => 'macOS',
            'country' => 'US',
            'city' => 'San Francisco',
            'is_active' => true,
            'last_activity' => now(),
            'expires_at' => now()->addHours(24),
        ]);

        UserSession::create([
            'user_id' => $demoUser->id,
            'session_id' => 'demo_session_' . uniqid(),
            'ip_address' => '192.168.1.100',
            'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
            'device_type' => 'mobile',
            'browser' => 'Safari',
            'os' => 'iOS',
            'country' => 'US',
            'city' => 'Los Angeles',
            'is_active' => true,
            'last_activity' => now(),
            'expires_at' => now()->addHours(12),
        ]);
    }
}