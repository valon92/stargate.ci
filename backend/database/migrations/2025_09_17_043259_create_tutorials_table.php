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
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('overview')->nullable();
            $table->foreignId('content_id')->constrained('content_items')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->integer('estimated_duration'); // Duration in minutes
            $table->integer('difficulty_level'); // 1-5 scale
            $table->json('learning_objectives'); // Array of objectives
            $table->json('prerequisites')->nullable(); // Array of prerequisite tutorial IDs
            $table->json('resources')->nullable(); // Array of additional resources
            $table->string('thumbnail')->nullable();
            $table->json('steps'); // Array of tutorial steps
            $table->boolean('is_interactive')->default(false);
            $table->boolean('has_quiz')->default(false);
            $table->boolean('has_certificate')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('completion_count')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create tutorial_steps table
        Schema::create('tutorial_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutorial_id')->constrained('tutorials')->onDelete('cascade');
            $table->integer('step_number');
            $table->string('title');
            $table->text('description');
            $table->longText('content');
            $table->enum('type', ['text', 'video', 'image', 'code', 'interactive', 'quiz']);
            $table->json('media_files')->nullable(); // Array of media file paths
            $table->json('code_blocks')->nullable(); // Array of code examples
            $table->json('interactive_elements')->nullable(); // Interactive components
            $table->integer('estimated_time'); // Time in minutes
            $table->boolean('is_optional')->default(false);
            $table->json('hints')->nullable(); // Array of hints
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create user_tutorial_progress table
        Schema::create('user_tutorial_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tutorial_id')->constrained('tutorials')->onDelete('cascade');
            $table->foreignId('step_id')->nullable()->constrained('tutorial_steps')->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'paused']);
            $table->integer('progress_percentage')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->json('completed_steps')->nullable(); // Array of completed step IDs
            $table->json('notes')->nullable(); // User notes
            $table->timestamps();
            
            $table->unique(['user_id', 'tutorial_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tutorial_progress');
        Schema::dropIfExists('tutorial_steps');
        Schema::dropIfExists('tutorials');
    }
};