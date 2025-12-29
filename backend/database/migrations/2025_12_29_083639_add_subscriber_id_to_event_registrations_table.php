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
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->foreignId('subscriber_id')->nullable()->after('event_id')->constrained('subscribers')->onDelete('set null');
            $table->index(['subscriber_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropForeign(['subscriber_id']);
            $table->dropIndex(['subscriber_id', 'event_id']);
            $table->dropColumn('subscriber_id');
        });
    }
};
