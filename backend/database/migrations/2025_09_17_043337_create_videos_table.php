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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->foreignId('content_id')->constrained('content_items')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('video_url'); // Main video URL
            $table->string('thumbnail_url')->nullable();
            $table->string('poster_url')->nullable(); // Video poster image
            $table->integer('duration'); // Duration in seconds
            $table->string('format')->default('mp4'); // Video format
            $table->integer('file_size')->nullable(); // File size in bytes
            $table->json('quality_options')->nullable(); // Different quality versions
            $table->json('subtitles')->nullable(); // Array of subtitle files
            $table->json('chapters')->nullable(); // Video chapters/timestamps
            $table->boolean('has_transcript')->default(false);
            $table->text('transcript')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('allow_download')->default(false);
            $table->boolean('allow_embed')->default(true);
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('dislike_count')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create video_analytics table
        Schema::create('video_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained('videos')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->integer('watch_time'); // Time watched in seconds
            $table->integer('completion_percentage'); // 0-100
            $table->json('watch_events')->nullable(); // Array of watch events
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('watched_at');
            $table->timestamps();
        });

        // Create video_playlists table
        Schema::create('video_playlists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('video_count')->default(0);
            $table->integer('total_duration')->default(0); // Total duration in seconds
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create video_playlist_items table
        Schema::create('video_playlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist_id')->constrained('video_playlists')->onDelete('cascade');
            $table->foreignId('video_id')->constrained('videos')->onDelete('cascade');
            $table->integer('sort_order')->default(0);
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['playlist_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_playlist_items');
        Schema::dropIfExists('video_playlists');
        Schema::dropIfExists('video_analytics');
        Schema::dropIfExists('videos');
    }
};