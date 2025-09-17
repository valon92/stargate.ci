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
        Schema::create('community_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('rules')->nullable(); // Community rules for this category
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create community_posts table
        Schema::create('community_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('community_categories')->onDelete('cascade');
            $table->enum('type', ['discussion', 'question', 'announcement', 'showcase', 'news']);
            $table->enum('status', ['draft', 'published', 'archived', 'deleted'])->default('draft');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->json('tags')->nullable(); // Array of tag names
            $table->json('media_files')->nullable(); // Array of media file paths
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('dislike_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('share_count')->default(0);
            $table->decimal('rating', 3, 2)->nullable(); // Average rating
            $table->timestamp('published_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create community_comments table
        Schema::create('community_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('community_posts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('community_comments')->onDelete('cascade');
            $table->text('content');
            $table->enum('status', ['published', 'hidden', 'deleted'])->default('published');
            $table->boolean('is_solution')->default(false); // For Q&A posts
            $table->integer('like_count')->default(0);
            $table->integer('dislike_count')->default(0);
            $table->integer('reply_count')->default(0);
            $table->json('media_files')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Create community_tags table
        Schema::create('community_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->integer('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create community_post_tags table (pivot)
        Schema::create('community_post_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('community_posts')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('community_tags')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['post_id', 'tag_id']);
        });

        // Create community_likes table
        Schema::create('community_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->morphs('likeable'); // Can like posts or comments
            $table->enum('type', ['like', 'dislike'])->default('like');
            $table->timestamps();
            
            $table->unique(['user_id', 'likeable_type', 'likeable_id']);
        });

        // Create community_badges table
        Schema::create('community_badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->enum('type', ['achievement', 'rank', 'special', 'custom']);
            $table->json('criteria')->nullable(); // Criteria for earning the badge
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Create user_badges table
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('community_badges')->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'badge_id']);
        });

        // Create user_reputation table
        Schema::create('user_reputation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->integer('level')->default(1);
            $table->string('title')->nullable();
            $table->json('achievements')->nullable(); // Array of achievement data
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
        });

        // Create community_follows table
        Schema::create('community_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('following_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('followed_at');
            $table->timestamps();
            
            $table->unique(['follower_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_follows');
        Schema::dropIfExists('user_reputation');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('community_badges');
        Schema::dropIfExists('community_likes');
        Schema::dropIfExists('community_post_tags');
        Schema::dropIfExists('community_tags');
        Schema::dropIfExists('community_comments');
        Schema::dropIfExists('community_posts');
        Schema::dropIfExists('community_categories');
    }
};