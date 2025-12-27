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
        // Only create table if it doesn't exist
        if (!Schema::hasTable('community_posts')) {
            Schema::create('community_posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscriber_id')->constrained('subscribers')->onDelete('cascade');
                $table->string('title');
                $table->text('content');
                $table->string('category')->default('general'); // general, experience, question, idea, discussion
                $table->json('tags')->nullable();
                $table->boolean('is_pinned')->default(false);
                $table->boolean('is_locked')->default(false);
                $table->integer('views_count')->default(0);
                $table->integer('likes_count')->default(0);
                $table->integer('comments_count')->default(0);
                $table->integer('shares_count')->default(0);
                $table->string('status')->default('published'); // published, draft, archived
                $table->timestamps();
                
                $table->index(['subscriber_id', 'status']);
                $table->index(['category', 'status']);
                $table->index('is_pinned');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_posts');
    }
};
