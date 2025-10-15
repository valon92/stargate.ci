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
        Schema::create('template_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('templates')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating'); // 1-5 stars
            $table->text('review')->nullable();
            $table->json('pros')->nullable(); // Array of pros
            $table->json('cons')->nullable(); // Array of cons
            $table->boolean('is_verified_download')->default(false); // User actually downloaded
            $table->boolean('is_helpful')->default(false); // Marked as helpful by others
            $table->integer('helpful_count')->default(0); // How many found it helpful
            $table->timestamps();
            
            // Ensure one review per user per template
            $table->unique(['template_id', 'user_id']);
            $table->index(['template_id', 'rating']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_reviews');
    }
};