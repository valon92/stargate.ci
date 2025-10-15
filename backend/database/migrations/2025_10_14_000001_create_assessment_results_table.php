<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->unsignedTinyInteger('current_step')->default(0);
            $table->unsignedSmallInteger('score')->default(0);
            $table->string('readiness_title')->nullable();
            $table->json('assessment'); // raw answers
            $table->json('recommendations')->nullable();
            $table->json('metadata')->nullable(); // device, locale, version
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};


