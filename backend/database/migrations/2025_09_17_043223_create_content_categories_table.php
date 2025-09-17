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
        Schema::create('content_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional category data
            $table->timestamps();
        });

        // Create content_tags table
        Schema::create('content_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->integer('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create content_items table
        Schema::create('content_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->enum('type', ['article', 'tutorial', 'video', 'quiz', 'interactive', 'document']);
            $table->foreignId('category_id')->nullable()->constrained('content_categories')->onDelete('set null');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('featured_image')->nullable();
            $table->json('media_files')->nullable(); // Array of media file paths
            $table->integer('read_time')->default(5); // Estimated read time in minutes
            $table->integer('difficulty_level')->default(1); // 1-5 difficulty scale
            $table->json('prerequisites')->nullable(); // Array of prerequisite content IDs
            $table->json('learning_objectives')->nullable(); // Array of learning objectives
            $table->json('tags')->nullable(); // Array of tag names
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->decimal('rating', 3, 2)->nullable(); // Average rating 0.00-5.00
            $table->json('metadata')->nullable(); // Additional content data
            $table->timestamps();
        });

        // Create content_versions table
        Schema::create('content_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('content_items')->onDelete('cascade');
            $table->integer('version_number');
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->json('changes')->nullable(); // Description of changes made
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_versions');
        Schema::dropIfExists('content_items');
        Schema::dropIfExists('content_tags');
        Schema::dropIfExists('content_categories');
    }
};