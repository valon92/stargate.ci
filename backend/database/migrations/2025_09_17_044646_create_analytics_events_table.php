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
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('event_type'); // page_view, click, form_submit, etc.
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->string('page_url')->nullable();
            $table->string('referrer_url')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->json('properties')->nullable(); // Custom event properties
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        // Create performance_metrics table
        Schema::create('performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_name'); // page_load_time, api_response_time, etc.
            $table->string('metric_type'); // performance, error, custom
            $table->string('page_url')->nullable();
            $table->string('api_endpoint')->nullable();
            $table->decimal('value', 10, 4); // Metric value
            $table->string('unit')->nullable(); // ms, bytes, count, etc.
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });

        // Create error_logs table
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('error_type'); // javascript, php, database, api, etc.
            $table->string('error_level'); // error, warning, info, debug
            $table->string('error_message');
            $table->text('error_stack')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->string('page_url')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('context')->nullable(); // Additional context data
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        // Create user_activity_logs table
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('activity_type'); // login, logout, page_view, action, etc.
            $table->string('activity_description');
            $table->string('page_url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->json('properties')->nullable(); // Activity-specific properties
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        // Create session_analytics table
        Schema::create('session_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->integer('page_views')->default(0);
            $table->integer('events_count')->default(0);
            $table->integer('duration_seconds')->default(0);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create content_analytics table
        Schema::create('content_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('content_type'); // article, tutorial, video, quiz, etc.
            $table->unsignedBigInteger('content_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action_type'); // view, like, share, comment, complete, etc.
            $table->string('page_url')->nullable();
            $table->integer('duration_seconds')->nullable(); // For video/tutorial completion
            $table->decimal('completion_percentage', 5, 2)->nullable(); // For tutorials/quizzes
            $table->json('properties')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
        });

        // Create search_analytics table
        Schema::create('search_analytics', function (Blueprint $table) {
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
            $table->timestamp('searched_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_analytics');
        Schema::dropIfExists('content_analytics');
        Schema::dropIfExists('session_analytics');
        Schema::dropIfExists('user_activity_logs');
        Schema::dropIfExists('error_logs');
        Schema::dropIfExists('performance_metrics');
        Schema::dropIfExists('analytics_events');
    }
};