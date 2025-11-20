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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('country')->nullable();
            $table->string('profession')->nullable();
            $table->string('company')->nullable();
            $table->text('interests')->nullable(); // JSON array of interests
            $table->string('avatar')->nullable(); // Avatar URL or initial
            $table->boolean('is_active')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamp('last_activity_at')->nullable();
            $table->json('preferences')->nullable(); // User preferences
            $table->timestamps();
            
            $table->index(['email', 'is_active']);
            $table->index('subscribed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};