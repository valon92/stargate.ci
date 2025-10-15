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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon')->nullable();
            $table->string('category');
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->string('estimated_time');
            $table->string('team_size');
            $table->string('budget_range');
            $table->json('features'); // Array of features
            $table->text('architecture');
            $table->json('implementation_steps'); // Array of steps
            $table->json('requirements'); // Technical and team requirements
            $table->json('metadata')->nullable(); // Additional metadata
            $table->string('version')->default('1.0.0');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('download_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00); // 0.00 to 5.00
            $table->integer('review_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['category', 'is_active']);
            $table->index(['difficulty', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};