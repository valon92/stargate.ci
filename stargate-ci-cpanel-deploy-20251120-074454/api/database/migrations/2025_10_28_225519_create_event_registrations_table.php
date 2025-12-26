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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->enum('status', ['registered', 'confirmed', 'cancelled'])->default('registered');
            $table->json('preferences')->nullable(); // Email preferences, timezone, etc.
            $table->timestamp('registered_at');
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['event_id', 'email']);
            $table->index(['email', 'status']);
            $table->index(['reminder_sent_at']);
            $table->unique(['event_id', 'email']); // Prevent duplicate registrations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};