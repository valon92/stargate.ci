<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $expertUser = User::where('email', 'michael@example.com')->first();
        $regularUsers = User::whereIn('email', [
            'john@example.com',
            'jane@example.com',
            'sarah@example.com',
            'alex@example.com'
        ])->get();

        // Create roles
        $adminRole = UserRole::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full system administrator with all permissions',
            'permissions' => json_encode([
                'manage_users' => true,
                'manage_content' => true,
                'manage_community' => true,
                'view_analytics' => true,
                'manage_settings' => true,
                'moderate_content' => true
            ]),
            'is_active' => true,
            'sort_order' => 1
        ]);

        $expertRole = UserRole::create([
            'name' => 'Expert',
            'slug' => 'expert',
            'description' => 'AI expert with content creation and moderation permissions',
            'permissions' => json_encode([
                'create_content' => true,
                'moderate_community' => true,
                'view_analytics' => true,
                'manage_own_content' => true
            ]),
            'is_active' => true,
            'sort_order' => 2
        ]);

        $userRole = UserRole::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'Regular user with basic permissions',
            'permissions' => json_encode([
                'create_community_posts' => true,
                'comment_on_posts' => true,
                'upload_files' => true,
                'view_content' => true
            ]),
            'is_active' => true,
            'sort_order' => 3
        ]);

        // Assign roles to users
        if ($adminUser) {
            DB::table('user_role_assignments')->insert([
                'user_id' => $adminUser->id,
                'role_id' => $adminRole->id,
                'assigned_by' => $adminUser->id,
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if ($expertUser) {
            DB::table('user_role_assignments')->insert([
                'user_id' => $expertUser->id,
                'role_id' => $expertRole->id,
                'assigned_by' => $adminUser->id,
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        foreach ($regularUsers as $user) {
            DB::table('user_role_assignments')->insert([
                'user_id' => $user->id,
                'role_id' => $userRole->id,
                'assigned_by' => $adminUser->id,
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
