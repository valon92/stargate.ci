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
        // Only create table if it doesn't exist
        if (!Schema::hasTable('community_likes')) {
            Schema::create('community_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscriber_id')->constrained('subscribers')->onDelete('cascade');
                $table->unsignedBigInteger('likeable_id');
                $table->string('likeable_type'); // App\Models\CommunityPost or App\Models\CommunityComment
                $table->timestamps();
                
                $table->unique(['subscriber_id', 'likeable_id', 'likeable_type'], 'unique_like');
                $table->index(['likeable_id', 'likeable_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_likes');
    }
};
