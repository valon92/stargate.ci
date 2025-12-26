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
        Schema::create('video_interactions', function (Blueprint $table) {
            $table->id();
            $table->string('video_content_id'); // References videos.content_id
            $table->unsignedBigInteger('subscriber_id')->nullable(); // References subscribers.id
            $table->string('session_id')->nullable(); // For guest users
            $table->string('interaction_type'); // like, share, view
            $table->string('platform')->nullable(); // facebook, twitter, whatsapp, etc. for shares
            $table->json('metadata')->nullable(); // Additional interaction data
            $table->timestamp('interacted_at')->useCurrent();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate interactions
            $table->unique(['video_content_id', 'subscriber_id', 'interaction_type'], 'unique_subscriber_interaction');
            $table->unique(['video_content_id', 'session_id', 'interaction_type'], 'unique_session_interaction');
            
            $table->index(['video_content_id', 'interaction_type']);
            $table->index('subscriber_id');
            $table->index('interacted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_interactions');
    }
};