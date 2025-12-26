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
        Schema::create('content_videos', function (Blueprint $table) {
            $table->id();
            $table->string('content_id')->unique(); // e.g., 'stargate-intro-video'
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('youtube_id')->nullable(); // YouTube video ID
            $table->string('youtube_url')->nullable(); // Full YouTube URL
            $table->string('content_type')->default('video'); // video, news, event
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();
            
            $table->index(['content_id', 'content_type']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_videos');
    }
};