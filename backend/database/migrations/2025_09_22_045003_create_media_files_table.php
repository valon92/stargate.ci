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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('url');
            $table->string('mime_type');
            $table->bigInteger('size'); // File size in bytes
            $table->enum('type', ['image', 'video', 'audio', 'document']);
            $table->string('context')->nullable(); // e.g., 'post', 'comment', 'profile'
            $table->unsignedBigInteger('context_id')->nullable(); // ID of related entity
            $table->json('metadata')->nullable(); // Store additional file info (dimensions, duration, etc.)
            $table->boolean('is_processed')->default(false); // For files that need processing
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'type']);
            $table->index(['context', 'context_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};