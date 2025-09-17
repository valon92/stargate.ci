<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use App\Models\BillingHistory;
use App\Models\PaymentTransaction;
use App\Models\Invoice;
use App\Models\UsageTracking;
use App\Models\User;

class PaymentBillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $adminUser = User::where('email', 'admin@stargate.ci')->first();
        $demoUser = User::where('email', 'demo@stargate.ci')->first();

        // Create subscription plans
        $freePlan = SubscriptionPlan::firstOrCreate(
            ['slug' => 'free'],
            [
                'name' => 'Free Plan',
                'description' => 'Basic access to Stargate.ci platform',
                'price' => 0.00,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'trial_days' => 0,
                'is_active' => true,
                'is_popular' => false,
                'features' => json_encode([
                    'Access to basic content',
                    'Community participation',
                    'Basic search functionality',
                    'Email support'
                ]),
                'limits' => json_encode([
                    'api_calls' => 1000,
                    'storage_mb' => 100,
                    'tutorials_per_month' => 5
                ]),
                'sort_order' => 1
            ]
        );

        $proPlan = SubscriptionPlan::firstOrCreate(
            ['slug' => 'pro'],
            [
                'name' => 'Pro Plan',
                'description' => 'Advanced features for professionals',
                'price' => 29.99,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'trial_days' => 14,
                'is_active' => true,
                'is_popular' => true,
                'features' => json_encode([
                    'Unlimited content access',
                    'Advanced tutorials',
                    'Priority support',
                    'API access',
                    'Custom integrations'
                ]),
                'limits' => json_encode([
                    'api_calls' => 10000,
                    'storage_mb' => 1000,
                    'tutorials_per_month' => -1 // unlimited
                ]),
                'sort_order' => 2
            ]
        );

        $enterprisePlan = SubscriptionPlan::firstOrCreate(
            ['slug' => 'enterprise'],
            [
                'name' => 'Enterprise Plan',
                'description' => 'Full access for organizations',
                'price' => 99.99,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'trial_days' => 30,
                'is_active' => true,
                'is_popular' => false,
                'features' => json_encode([
                    'Everything in Pro',
                    'White-label solution',
                    'Dedicated support',
                    'Custom training',
                    'SLA guarantee'
                ]),
                'limits' => json_encode([
                    'api_calls' => -1, // unlimited
                    'storage_mb' => -1, // unlimited
                    'tutorials_per_month' => -1 // unlimited
                ]),
                'sort_order' => 3
            ]
        );

        // Create subscriptions
        $adminSubscription = Subscription::create([
            'user_id' => $adminUser->id,
            'plan_name' => 'Enterprise Plan',
            'plan_slug' => 'enterprise',
            'description' => 'Full access for organizations',
            'price' => 99.99,
            'currency' => 'USD',
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'starts_at' => now()->subDays(30),
            'ends_at' => now()->addDays(30),
            'auto_renew' => true,
            'features' => json_encode([
                'Everything in Pro',
                'White-label solution',
                'Dedicated support',
                'Custom training',
                'SLA guarantee'
            ]),
            'limits' => json_encode([
                'api_calls' => -1,
                'storage_mb' => -1,
                'tutorials_per_month' => -1
            ])
        ]);

        $demoSubscription = Subscription::create([
            'user_id' => $demoUser->id,
            'plan_name' => 'Pro Plan',
            'plan_slug' => 'pro',
            'description' => 'Advanced features for professionals',
            'price' => 29.99,
            'currency' => 'USD',
            'billing_cycle' => 'monthly',
            'status' => 'active',
            'starts_at' => now()->subDays(7),
            'ends_at' => now()->addDays(23),
            'auto_renew' => true,
            'features' => json_encode([
                'Unlimited content access',
                'Advanced tutorials',
                'Priority support',
                'API access',
                'Custom integrations'
            ]),
            'limits' => json_encode([
                'api_calls' => 10000,
                'storage_mb' => 1000,
                'tutorials_per_month' => -1
            ])
        ]);

        // Create payment methods
        PaymentMethod::create([
            'user_id' => $adminUser->id,
            'type' => 'credit_card',
            'provider' => 'stripe',
            'external_id' => 'pm_1234567890',
            'last_four' => '4242',
            'brand' => 'Visa',
            'exp_month' => '12',
            'exp_year' => '2025',
            'holder_name' => 'Admin User',
            'is_default' => true,
            'is_active' => true
        ]);

        PaymentMethod::create([
            'user_id' => $demoUser->id,
            'type' => 'credit_card',
            'provider' => 'stripe',
            'external_id' => 'pm_0987654321',
            'last_four' => '5555',
            'brand' => 'Mastercard',
            'exp_month' => '08',
            'exp_year' => '2026',
            'holder_name' => 'Demo User',
            'is_default' => true,
            'is_active' => true
        ]);

        // Create billing history
        $adminBilling = BillingHistory::firstOrCreate(
            ['invoice_number' => 'INV-2025-001'],
            [
            'user_id' => $adminUser->id,
            'subscription_id' => $adminSubscription->id,
            'invoice_number' => 'INV-2025-001',
            'amount' => 99.99,
            'currency' => 'USD',
            'status' => 'paid',
            'type' => 'subscription',
            'description' => 'Enterprise Plan - Monthly Subscription',
            'due_date' => now()->subDays(30),
            'paid_date' => now()->subDays(30),
            'line_items' => json_encode([
                [
                    'description' => 'Enterprise Plan',
                    'amount' => 99.99,
                    'quantity' => 1
                ]
            ]),
            'taxes' => json_encode([
                'rate' => 0.08,
                'amount' => 7.99
            ])
            ]
        );

        $demoBilling = BillingHistory::firstOrCreate(
            ['invoice_number' => 'INV-2025-002'],
            [
            'user_id' => $demoUser->id,
            'subscription_id' => $demoSubscription->id,
            'invoice_number' => 'INV-2025-002',
            'amount' => 29.99,
            'currency' => 'USD',
            'status' => 'paid',
            'type' => 'subscription',
            'description' => 'Pro Plan - Monthly Subscription',
            'due_date' => now()->subDays(7),
            'paid_date' => now()->subDays(7),
            'line_items' => json_encode([
                [
                    'description' => 'Pro Plan',
                    'amount' => 29.99,
                    'quantity' => 1
                ]
            ]),
            'taxes' => json_encode([
                'rate' => 0.08,
                'amount' => 2.40
            ])
            ]
        );

        // Create payment transactions
        PaymentTransaction::firstOrCreate(
            ['transaction_id' => 'txn_admin_001'],
            [
            'user_id' => $adminUser->id,
            'subscription_id' => $adminSubscription->id,
            'billing_id' => $adminBilling->id,
            'transaction_id' => 'txn_admin_001',
            'provider' => 'stripe',
            'amount' => 99.99,
            'currency' => 'USD',
            'status' => 'completed',
            'type' => 'payment',
            'payment_method_id' => 'pm_1234567890',
            'description' => 'Enterprise Plan subscription payment',
            'provider_response' => json_encode([
                'charge_id' => 'ch_1234567890',
                'status' => 'succeeded'
            ]),
            'processed_at' => now()->subDays(30)
            ]
        );

        PaymentTransaction::firstOrCreate(
            ['transaction_id' => 'txn_demo_001'],
            [
            'user_id' => $demoUser->id,
            'subscription_id' => $demoSubscription->id,
            'billing_id' => $demoBilling->id,
            'transaction_id' => 'txn_demo_001',
            'provider' => 'stripe',
            'amount' => 29.99,
            'currency' => 'USD',
            'status' => 'completed',
            'type' => 'payment',
            'payment_method_id' => 'pm_0987654321',
            'description' => 'Pro Plan subscription payment',
            'provider_response' => json_encode([
                'charge_id' => 'ch_0987654321',
                'status' => 'succeeded'
            ]),
            'processed_at' => now()->subDays(7)
            ]
        );

        // Create invoices
        Invoice::firstOrCreate(
            ['invoice_number' => 'INV-2025-001'],
            [
            'user_id' => $adminUser->id,
            'subscription_id' => $adminSubscription->id,
            'invoice_number' => 'INV-2025-001',
            'subtotal' => 92.00,
            'tax_amount' => 7.99,
            'total_amount' => 99.99,
            'currency' => 'USD',
            'status' => 'paid',
            'issue_date' => now()->subDays(30),
            'due_date' => now()->subDays(30),
            'paid_date' => now()->subDays(30),
            'line_items' => json_encode([
                [
                    'description' => 'Enterprise Plan - Monthly',
                    'quantity' => 1,
                    'unit_price' => 92.00,
                    'total' => 92.00
                ]
            ]),
            'billing_address' => json_encode([
                'name' => 'Admin User',
                'company' => 'Stargate.ci',
                'email' => 'admin@stargate.ci',
                'address' => '123 Tech Street',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip' => '94105',
                'country' => 'US'
            ])
            ]
        );

        // Create usage tracking
        UsageTracking::create([
            'user_id' => $adminUser->id,
            'subscription_id' => $adminSubscription->id,
            'feature_name' => 'api_calls',
            'usage_count' => 2500,
            'limit_count' => -1, // unlimited
            'tracking_date' => now()->toDateString()
        ]);

        UsageTracking::create([
            'user_id' => $demoUser->id,
            'subscription_id' => $demoSubscription->id,
            'feature_name' => 'api_calls',
            'usage_count' => 1500,
            'limit_count' => 10000,
            'tracking_date' => now()->toDateString()
        ]);

        UsageTracking::create([
            'user_id' => $demoUser->id,
            'subscription_id' => $demoSubscription->id,
            'feature_name' => 'tutorials_completed',
            'usage_count' => 8,
            'limit_count' => -1, // unlimited
            'tracking_date' => now()->toDateString()
        ]);
    }
}