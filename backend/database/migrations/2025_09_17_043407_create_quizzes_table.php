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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->foreignId('content_id')->constrained('content_items')->onDelete('cascade');
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['assessment', 'practice', 'certification', 'survey']);
            $table->integer('time_limit')->nullable(); // Time limit in minutes
            $table->integer('passing_score')->default(70); // Passing percentage
            $table->integer('max_attempts')->nullable(); // Maximum attempts allowed
            $table->json('instructions')->nullable(); // Quiz instructions
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_correct_answers')->default(true);
            $table->boolean('show_explanations')->default(true);
            $table->boolean('allow_review')->default(true);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('question_count')->default(0);
            $table->integer('total_points')->default(0);
            $table->integer('attempt_count')->default(0);
            $table->decimal('average_score', 5, 2)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create quiz_questions table
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->integer('question_number');
            $table->text('question_text');
            $table->enum('type', ['multiple_choice', 'true_false', 'fill_blank', 'essay', 'matching', 'ordering']);
            $table->json('options')->nullable(); // For multiple choice questions
            $table->json('correct_answers'); // Array of correct answers
            $table->text('explanation')->nullable();
            $table->integer('points')->default(1);
            $table->integer('time_limit')->nullable(); // Time limit for this question in seconds
            $table->json('hints')->nullable(); // Array of hints
            $table->json('media_files')->nullable(); // Array of media file paths
            $table->boolean('is_required')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create quiz_attempts table
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->integer('attempt_number');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent')->nullable(); // Time spent in seconds
            $table->integer('score')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->boolean('is_passed')->default(false);
            $table->enum('status', ['in_progress', 'completed', 'abandoned', 'timeout']);
            $table->json('answers')->nullable(); // User's answers
            $table->json('results')->nullable(); // Detailed results
            $table->timestamps();
        });

        // Create quiz_question_answers table
        Schema::create('quiz_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('quiz_questions')->onDelete('cascade');
            $table->json('user_answer'); // User's answer
            $table->json('correct_answer'); // Correct answer
            $table->boolean('is_correct')->default(false);
            $table->integer('points_earned')->default(0);
            $table->integer('time_spent')->nullable(); // Time spent on this question
            $table->timestamp('answered_at');
            $table->timestamps();
        });

        // Create quiz_certificates table
        Schema::create('quiz_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('certificate_url')->nullable();
            $table->decimal('score', 5, 2);
            $table->timestamp('issued_at');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_valid')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_certificates');
        Schema::dropIfExists('quiz_question_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};