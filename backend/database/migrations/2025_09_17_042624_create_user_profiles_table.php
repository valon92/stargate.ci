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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company')->nullable();
            $table->enum('company_size', ['startup', 'small', 'medium', 'large', 'enterprise'])->nullable();
            $table->string('industry')->nullable();
            $table->enum('investment_capacity', ['low', 'medium', 'high', 'enterprise'])->nullable();
            $table->json('interests')->nullable(); // Array of interests
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('message')->nullable();
            $table->enum('preferred_contact', ['email', 'phone', 'both'])->default('email');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->json('social_links')->nullable(); // Array of social media links
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->json('preferences')->nullable(); // User preferences
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};