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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique()->nullable(); // ID from external API
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['stargate', 'cristal', 'conferences', 'meetings', 'announcements']);
            $table->enum('type', ['conference', 'meeting', 'announcement', 'workshop', 'video']);
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->string('location');
            $table->string('organizer');
            $table->string('icon')->default('📅');
            $table->string('registration_url')->nullable();
            $table->string('video_url')->nullable();
            $table->string('source')->default('internal'); // internal, openai, softbank, oracle, mgx
            $table->json('metadata')->nullable(); // Additional data from external APIs
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['category', 'is_active']);
            $table->index(['event_date', 'is_active']);
            $table->index(['source', 'last_synced_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
