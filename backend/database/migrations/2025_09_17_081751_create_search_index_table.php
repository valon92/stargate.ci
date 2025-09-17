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
        Schema::create('search_index', function (Blueprint $table) {
            $table->id();
            $table->string('searchable_type'); // Model class name
            $table->unsignedBigInteger('searchable_id'); // Model ID
            $table->string('title');
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->json('keywords')->nullable(); // Array of keywords
            $table->json('tags')->nullable(); // Array of tags
            $table->string('category')->nullable();
            $table->string('author')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->integer('relevance_score')->default(0);
            $table->integer('view_count')->default(0);
            $table->timestamp('indexed_at');
            $table->timestamps();
            
            $table->index(['searchable_type', 'searchable_id']);
            $table->index(['status', 'relevance_score']);
            $table->index(['title', 'content', 'excerpt']);
        });

        // Create search_queries table
        Schema::create('search_queries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('query');
            $table->string('search_type'); // global, content, community, etc.
            $table->integer('results_count')->default(0);
            $table->integer('clicked_result_position')->nullable();
            $table->string('clicked_result_type')->nullable();
            $table->unsignedBigInteger('clicked_result_id')->nullable();
            $table->boolean('result_clicked')->default(false);
            $table->integer('time_to_click')->nullable(); // Time in seconds
            $table->json('filters_used')->nullable();
            $table->json('suggestions_shown')->nullable();
            $table->timestamp('searched_at');
            $table->timestamps();
        });

        // Create search_filters table
        Schema::create('search_filters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type'); // category, tag, date, range, etc.
            $table->text('description')->nullable();
            $table->json('options')->nullable(); // Filter options
            $table->string('field_name')->nullable(); // Database field name
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create saved_searches table
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('query');
            $table->string('search_type');
            $table->json('filters')->nullable();
            $table->json('sort_options')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        // Create search_suggestions table
        Schema::create('search_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('suggestion');
            $table->string('type'); // popular, trending, related, etc.
            $table->integer('usage_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create search_history table
        Schema::create('search_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->string('query');
            $table->string('search_type');
            $table->json('filters')->nullable();
            $table->integer('results_count')->default(0);
            $table->timestamp('searched_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_history');
        Schema::dropIfExists('search_suggestions');
        Schema::dropIfExists('saved_searches');
        Schema::dropIfExists('search_filters');
        Schema::dropIfExists('search_queries');
        Schema::dropIfExists('search_index');
    }
};