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
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id'); // References video_comments.id
            $table->unsignedBigInteger('subscriber_id')->nullable(); // References subscribers.id
            $table->string('session_id')->nullable(); // For guest users
            $table->timestamp('liked_at')->useCurrent();
            $table->timestamps();
            
            // Unique constraint to prevent duplicate likes
            $table->unique(['comment_id', 'subscriber_id'], 'unique_subscriber_comment_like');
            $table->unique(['comment_id', 'session_id'], 'unique_session_comment_like');
            
            $table->index('comment_id');
            $table->index('subscriber_id');
            $table->index('liked_at');
            
            // Foreign key constraints
            $table->foreign('comment_id')->references('id')->on('video_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
    }
};