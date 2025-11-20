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
        Schema::create('video_comments', function (Blueprint $table) {
            $table->id();
            $table->string('video_content_id'); // References videos.content_id
            $table->unsignedBigInteger('subscriber_id')->nullable(); // References subscribers.id
            $table->string('session_id')->nullable(); // For guest users
            $table->unsignedBigInteger('parent_id')->nullable(); // For replies
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional comment data
            $table->timestamps();
            
            $table->index(['video_content_id', 'is_active']);
            $table->index(['parent_id', 'is_active']);
            $table->index('subscriber_id');
            $table->index('created_at');
            
            // Foreign key constraints
            $table->foreign('parent_id')->references('id')->on('video_comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_comments');
    }
};