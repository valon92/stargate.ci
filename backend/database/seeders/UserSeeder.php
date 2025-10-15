<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@stargate.ci',
            'email_verified_at' => now(),
            'password' => Hash::make('password123')
        ]);

        // Create regular users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Alex Rodriguez',
                'email' => 'alex@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123')
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
