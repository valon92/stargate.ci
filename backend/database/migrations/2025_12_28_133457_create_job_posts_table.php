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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('subscribers')->onDelete('cascade'); // Company posting the job
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('location')->nullable(); // Remote, On-site, Hybrid
            $table->string('job_type')->default('full-time'); // full-time, part-time, contract, internship
            $table->string('category')->default('software'); // software, robotics, ai, data-science, etc.
            $table->string('experience_level')->nullable(); // entry, mid, senior, executive
            $table->string('salary_range')->nullable();
            $table->string('currency')->default('USD');
            $table->json('skills')->nullable(); // Required skills array
            $table->json('benefits')->nullable(); // Company benefits array
            $table->string('application_email')->nullable();
            $table->string('application_url')->nullable();
            $table->date('application_deadline')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->string('status')->default('published'); // published, draft, closed
            $table->timestamps();
            
            $table->index(['category', 'status']);
            $table->index(['job_type', 'status']);
            $table->index(['is_featured', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
