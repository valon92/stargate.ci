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
        Schema::create('cache_entries', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value');
            $table->integer('expires_at');
            $table->string('tags')->nullable(); // Cache tags for invalidation
            $table->integer('hit_count')->default(0);
            $table->integer('miss_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
        });

        // Create cdn_assets table
        Schema::create('cdn_assets', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('path');
            $table->string('url');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->string('hash')->unique(); // File hash for deduplication
            $table->enum('type', ['image', 'video', 'document', 'css', 'js', 'font', 'other']);
            $table->enum('status', ['uploading', 'processing', 'ready', 'failed', 'deleted'])->default('uploading');
            $table->json('metadata')->nullable(); // Additional file metadata
            $table->json('optimization_settings')->nullable(); // CDN optimization settings
            $table->integer('download_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();
        });

        // Create integration_settings table
        Schema::create('integration_settings', function (Blueprint $table) {
            $table->id();
            $table->string('integration_name');
            $table->string('integration_type'); // api, webhook, oauth, etc.
            $table->json('configuration')->nullable(); // Integration configuration
            $table->json('credentials')->nullable(); // Encrypted credentials
            $table->boolean('is_active')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamp('last_error_at')->nullable();
            $table->text('last_error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create performance_reports table
        Schema::create('performance_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->string('report_type'); // page_load, api_response, database_query, etc.
            $table->date('report_date');
            $table->json('metrics')->nullable(); // Performance metrics
            $table->json('recommendations')->nullable(); // Performance recommendations
            $table->decimal('overall_score', 5, 2)->nullable(); // Overall performance score
            $table->enum('status', ['generating', 'completed', 'failed'])->default('generating');
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create notification_settings table
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('notification_type'); // email, push, sms, in_app
            $table->string('event_type'); // new_content, system_update, etc.
            $table->boolean('is_enabled')->default(true);
            $table->json('preferences')->nullable(); // Notification preferences
            $table->json('schedule')->nullable(); // Notification schedule
            $table->timestamps();
            
            $table->unique(['user_id', 'notification_type', 'event_type']);
        });

        // Create user_preferences table
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('preference_key');
            $table->text('preference_value');
            $table->string('preference_type')->default('string'); // string, boolean, integer, json
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'preference_key']);
        });

        // Create system_logs table
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // debug, info, warning, error, critical
            $table->string('channel'); // application, database, api, etc.
            $table->text('message');
            $table->json('context')->nullable(); // Additional context data
            $table->string('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();
        });

        // Create backup_logs table
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('backup_name');
            $table->string('backup_type'); // database, files, full
            $table->string('file_path');
            $table->integer('file_size');
            $table->enum('status', ['in_progress', 'completed', 'failed'])->default('in_progress');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('notification_settings');
        Schema::dropIfExists('performance_reports');
        Schema::dropIfExists('integration_settings');
        Schema::dropIfExists('cdn_assets');
        Schema::dropIfExists('cache_entries');
    }
};