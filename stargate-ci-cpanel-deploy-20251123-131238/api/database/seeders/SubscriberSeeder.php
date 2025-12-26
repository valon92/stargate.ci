<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscriber;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscribers = [
            [
                'username' => 'ai_enthusiast_2024',
                'email' => 'ai.enthusiast@example.com',
                'first_name' => 'Alex',
                'last_name' => 'Johnson',
                'country' => 'United States',
                'profession' => 'AI Researcher',
                'company' => 'Tech Innovations Inc.',
                'interests' => ['artificial-intelligence', 'machine-learning', 'stargate-project'],
                'avatar' => 'A',
                'is_active' => true,
                'email_notifications' => true,
                'subscribed_at' => now()->subDays(30),
                'last_activity_at' => now()->subHours(2),
                'preferences' => [
                    'newsletter' => true,
                    'updates' => true,
                    'events' => true
                ]
            ],
            [
                'username' => 'tech_researcher',
                'email' => 'researcher@techcorp.com',
                'first_name' => 'Sarah',
                'last_name' => 'Chen',
                'country' => 'Canada',
                'profession' => 'Data Scientist',
                'company' => 'DataCorp Solutions',
                'interests' => ['data-science', 'cristal-intelligence', 'ai-ethics'],
                'avatar' => 'S',
                'is_active' => true,
                'email_notifications' => true,
                'subscribed_at' => now()->subDays(25),
                'last_activity_at' => now()->subHours(5),
                'preferences' => [
                    'newsletter' => true,
                    'updates' => false,
                    'events' => true
                ]
            ],
            [
                'username' => 'system_architect',
                'email' => 'architect@systems.com',
                'first_name' => 'Michael',
                'last_name' => 'Rodriguez',
                'country' => 'Spain',
                'profession' => 'System Architect',
                'company' => 'InfraTech Systems',
                'interests' => ['system-architecture', 'infrastructure', 'stargate-project'],
                'avatar' => 'M',
                'is_active' => true,
                'email_notifications' => true,
                'subscribed_at' => now()->subDays(20),
                'last_activity_at' => now()->subHours(1),
                'preferences' => [
                    'newsletter' => false,
                    'updates' => true,
                    'events' => false
                ]
            ],
            [
                'username' => 'devops_engineer',
                'email' => 'devops@cloudtech.com',
                'first_name' => 'Emma',
                'last_name' => 'Thompson',
                'country' => 'United Kingdom',
                'profession' => 'DevOps Engineer',
                'company' => 'CloudTech Solutions',
                'interests' => ['devops', 'cloud-computing', 'automation'],
                'avatar' => 'E',
                'is_active' => true,
                'email_notifications' => true,
                'subscribed_at' => now()->subDays(15),
                'last_activity_at' => now()->subMinutes(30),
                'preferences' => [
                    'newsletter' => true,
                    'updates' => true,
                    'events' => true
                ]
            ],
            [
                'username' => 'ai_ethicist',
                'email' => 'ethics@ai-institute.org',
                'first_name' => 'Dr. Priya',
                'last_name' => 'Patel',
                'country' => 'India',
                'profession' => 'AI Ethicist',
                'company' => 'AI Ethics Institute',
                'interests' => ['ai-ethics', 'cristal-intelligence', 'responsible-ai'],
                'avatar' => 'P',
                'is_active' => true,
                'email_notifications' => true,
                'subscribed_at' => now()->subDays(10),
                'last_activity_at' => now()->subHours(3),
                'preferences' => [
                    'newsletter' => true,
                    'updates' => true,
                    'events' => true
                ]
            ],
            [
                'username' => 'business_leader',
                'email' => 'leader@businesscorp.com',
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'country' => 'Australia',
                'profession' => 'Business Leader',
                'company' => 'Future Business Corp',
                'interests' => ['business-strategy', 'ai-adoption', 'market-analysis'],
                'avatar' => 'J',
                'is_active' => true,
                'email_notifications' => false,
                'subscribed_at' => now()->subDays(5),
                'last_activity_at' => now()->subHours(8),
                'preferences' => [
                    'newsletter' => false,
                    'updates' => true,
                    'events' => false
                ]
            ]
        ];

        foreach ($subscribers as $subscriberData) {
            Subscriber::updateOrCreate(
                ['email' => $subscriberData['email']],
                $subscriberData
            );
        }
    }
}