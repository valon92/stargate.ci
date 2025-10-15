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
        Schema::create('template_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('templates')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('download_token')->unique(); // For tracking downloads
            $table->json('metadata')->nullable(); // Additional download metadata
            $table->timestamp('downloaded_at');
            $table->timestamps();
            
            $table->index(['template_id', 'downloaded_at']);
            $table->index(['user_id', 'downloaded_at']);
            $table->index('download_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_downloads');
    }
};