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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('plan_name');
            $table->string('plan_slug');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('billing_cycle', ['monthly', 'yearly', 'lifetime']);
            $table->enum('status', ['active', 'inactive', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->json('features')->nullable(); // Available features
            $table->json('limits')->nullable(); // Usage limits
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create payment_methods table
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // credit_card, paypal, bank_transfer, etc.
            $table->string('provider'); // stripe, paypal, etc.
            $table->string('external_id'); // ID from payment provider
            $table->string('last_four')->nullable(); // Last 4 digits of card
            $table->string('brand')->nullable(); // Visa, Mastercard, etc.
            $table->string('exp_month')->nullable();
            $table->string('exp_year')->nullable();
            $table->string('holder_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create billing_history table
        Schema::create('billing_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded', 'cancelled']);
            $table->enum('type', ['subscription', 'one_time', 'refund', 'credit']);
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->json('line_items')->nullable(); // Invoice line items
            $table->json('taxes')->nullable(); // Tax information
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create payment_transactions table
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->foreignId('billing_id')->nullable()->constrained('billing_history')->onDelete('set null');
            $table->string('transaction_id')->unique(); // External transaction ID
            $table->string('provider'); // stripe, paypal, etc.
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded']);
            $table->enum('type', ['payment', 'refund', 'chargeback', 'dispute']);
            $table->string('payment_method_id')->nullable();
            $table->text('description')->nullable();
            $table->json('provider_response')->nullable(); // Response from payment provider
            $table->json('metadata')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // Create invoices table
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->string('invoice_number')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled']);
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('line_items')->nullable();
            $table->json('billing_address')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // Create usage_tracking table
        Schema::create('usage_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            $table->string('feature_name'); // API calls, storage, etc.
            $table->integer('usage_count')->default(0);
            $table->integer('limit_count')->nullable(); // Usage limit
            $table->date('tracking_date');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'subscription_id', 'feature_name', 'tracking_date']);
        });

        // Create subscription_plans table
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('billing_cycle', ['monthly', 'yearly', 'lifetime']);
            $table->integer('trial_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->json('features')->nullable(); // Available features
            $table->json('limits')->nullable(); // Usage limits
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('usage_tracking');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('billing_history');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('subscriptions');
    }
};