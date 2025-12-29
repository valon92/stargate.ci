<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->json('images')->nullable()->after('content'); // Array of image URLs
            $table->json('videos')->nullable()->after('images'); // Array of video URLs
            $table->string('media_type')->nullable()->after('videos'); // 'image', 'video', 'mixed', null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->dropColumn(['images', 'videos', 'media_type']);
        });
    }
};
