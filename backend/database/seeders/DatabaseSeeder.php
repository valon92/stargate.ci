<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core seeders
            ArticleSeeder::class,
            FAQSeeder::class,
            
            // Video system seeders
            VideoSeeder::class,
            SubscriberSeeder::class,
            
            // Job postings
            JobPostSeeder::class,
            
            // Community posts
            CommunityPostSeeder::class,
            
            // Events
            EventSeeder::class,
        ]);
    }
}