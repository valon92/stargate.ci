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
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->enum('source', ['website', 'referral', 'social', 'email', 'phone', 'event', 'other']);
            $table->enum('status', ['lead', 'prospect', 'customer', 'inactive']);
            $table->integer('lead_score')->default(0);
            $table->json('tags')->nullable();
            $table->text('notes')->nullable();
            $table->date('last_contact_date')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
        });

        // Create crm_leads table
        Schema::create('crm_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->constrained('crm_contacts')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost']);
            $table->decimal('value', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->date('expected_close_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('probability')->default(0); // 0-100 percentage
            $table->json('custom_fields')->nullable();
            $table->timestamps();
        });

        // Create crm_deals table
        Schema::create('crm_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('crm_leads')->onDelete('cascade');
            $table->string('deal_name');
            $table->text('description')->nullable();
            $table->enum('stage', ['prospecting', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost']);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('close_date')->nullable();
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->integer('probability')->default(0);
            $table->json('custom_fields')->nullable();
            $table->timestamps();
        });

        // Create email_campaigns table
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->text('preview_text')->nullable();
            $table->longText('content');
            $table->enum('type', ['newsletter', 'promotional', 'transactional', 'automated']);
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'paused', 'cancelled']);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->json('audience')->nullable(); // Audience criteria
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('delivered_count')->default(0);
            $table->integer('opened_count')->default(0);
            $table->integer('clicked_count')->default(0);
            $table->integer('bounced_count')->default(0);
            $table->integer('unsubscribed_count')->default(0);
            $table->decimal('open_rate', 5, 2)->default(0);
            $table->decimal('click_rate', 5, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create email_subscribers table
        Schema::create('email_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('status', ['active', 'unsubscribed', 'bounced', 'complained']);
            $table->enum('source', ['website', 'referral', 'social', 'import', 'manual', 'other']);
            $table->json('tags')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamp('subscribed_at');
            $table->timestamp('unsubscribed_at')->nullable();
            $table->integer('bounce_count')->default(0);
            $table->integer('complaint_count')->default(0);
            $table->json('location')->nullable(); // Country, city, timezone
            $table->timestamps();
        });

        // Create email_templates table
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject');
            $table->text('preview_text')->nullable();
            $table->longText('content');
            $table->enum('type', ['newsletter', 'promotional', 'transactional', 'automated']);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->json('variables')->nullable(); // Available template variables
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create audience_segments table
        Schema::create('audience_segments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('criteria')->nullable(); // Segment criteria
            $table->integer('subscriber_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Create campaign_analytics table
        Schema::create('campaign_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('email_campaigns')->onDelete('cascade');
            $table->foreignId('subscriber_id')->nullable()->constrained('email_subscribers')->onDelete('set null');
            $table->string('event_type'); // sent, delivered, opened, clicked, bounced, unsubscribed
            $table->timestamp('occurred_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_analytics');
        Schema::dropIfExists('audience_segments');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('email_subscribers');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('crm_deals');
        Schema::dropIfExists('crm_leads');
        Schema::dropIfExists('crm_contacts');
    }
};