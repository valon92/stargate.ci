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
            UserSeeder::class,
            UserRoleSeeder::class,
            ContentCategorySeeder::class,
            CommunityCategorySeeder::class,
            
            // Content seeders
            ArticleSeeder::class,
            FAQSeeder::class,
            TutorialSeeder::class,
            CommunityPostSeeder::class,
            TemplateSeeder::class,
            
            // System seeders
            SearchQuerySeeder::class,
            AnalyticsEventSeeder::class,
        ]);
    }
}