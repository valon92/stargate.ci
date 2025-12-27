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
        if (!Schema::hasTable('community_comments')) {
            Schema::create('community_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('community_post_id')->constrained('community_posts')->onDelete('cascade');
                $table->foreignId('subscriber_id')->constrained('subscribers')->onDelete('cascade');
                $table->foreignId('parent_id')->nullable()->constrained('community_comments')->onDelete('cascade');
                $table->text('content');
                $table->integer('likes_count')->default(0);
                $table->boolean('is_pinned')->default(false);
                $table->string('status')->default('published'); // published, deleted
                $table->timestamps();
                
                $table->index(['community_post_id', 'status']);
                $table->index(['subscriber_id', 'status']);
                $table->index('parent_id');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_comments');
    }
};
