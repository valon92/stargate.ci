// Stripe Service for Stargate.ci
// Handles payment processing, subscriptions, and billing

import { apiConfig } from '../config/apiConfig';

export interface PaymentIntentRequest {
  amount: number;
  currency: string;
  customer?: string;
  description?: string;
  metadata?: Record<string, string>;
  payment_method_types?: string[];
  receipt_email?: string;
}

export interface PaymentIntentResponse {
  id: string;
  object: string;
  amount: number;
  currency: string;
  status: string;
  client_secret: string;
  customer?: string;
  description?: string;
  metadata?: Record<string, string>;
}

export interface CustomerRequest {
  email: string;
  name?: string;
  phone?: string;
  description?: string;
  metadata?: Record<string, string>;
}

export interface CustomerResponse {
  id: string;
  object: string;
  email: string;
  name?: string;
  phone?: string;
  description?: string;
  metadata?: Record<string, string>;
  created: number;
}

export interface SubscriptionRequest {
  customer: string;
  items: Array<{
    price: string;
    quantity?: number;
  }>;
  trial_period_days?: number;
  metadata?: Record<string, string>;
}

export interface SubscriptionResponse {
  id: string;
  object: string;
  status: string;
  customer: string;
  items: {
    data: Array<{
      id: string;
      object: string;
      price: {
        id: string;
        object: string;
        unit_amount: number;
        currency: string;
        recurring: {
          interval: string;
          interval_count: number;
        };
      };
      quantity: number;
    }>;
  };
  current_period_start: number;
  current_period_end: number;
  trial_start?: number;
  trial_end?: number;
  metadata?: Record<string, string>;
}

export interface PriceRequest {
  unit_amount: number;
  currency: string;
  recurring?: {
    interval: 'day' | 'week' | 'month' | 'year';
    interval_count?: number;
  };
  product: string;
  nickname?: string;
  metadata?: Record<string, string>;
}

export interface PriceResponse {
  id: string;
  object: string;
  active: boolean;
  currency: string;
  unit_amount: number;
  recurring?: {
    interval: string;
    interval_count: number;
  };
  product: string;
  nickname?: string;
  metadata?: Record<string, string>;
}

export interface ProductRequest {
  name: string;
  description?: string;
  images?: string[];
  metadata?: Record<string, string>;
  type?: 'service' | 'good';
}

export interface ProductResponse {
  id: string;
  object: string;
  active: boolean;
  name: string;
  description?: string;
  images: string[];
  metadata?: Record<string, string>;
  type: string;
  created: number;
  updated: number;
}

class StripeService {
  private publishableKey: string;
  private secretKey: string;
  private webhookSecret: string;
  private currency: string;

  constructor() {
    this.publishableKey = apiConfig.stripe.publishableKey;
    this.secretKey = apiConfig.stripe.secretKey;
    this.webhookSecret = apiConfig.stripe.webhookSecret;
    this.currency = apiConfig.stripe.currency;
  }

  // Check if Stripe is configured
  public isConfigured(): boolean {
    return !!(this.publishableKey && this.secretKey);
  }

  // Get publishable key for frontend
  public getPublishableKey(): string {
    return this.publishableKey;
  }

  // Make authenticated request to Stripe API
  private async makeRequest<T>(endpoint: string, data: any, method: 'GET' | 'POST' | 'PUT' | 'DELETE' = 'POST'): Promise<T> {
    if (!this.isConfigured()) {
      throw new Error('Stripe API keys not configured');
    }

    const response = await fetch(`https://api.stripe.com${endpoint}`, {
      method,
      headers: {
        'Authorization': `Bearer ${this.secretKey}`,
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: method !== 'GET' ? new URLSearchParams(data).toString() : undefined
    });

    if (!response.ok) {
      const error = await response.json().catch(() => ({}));
      throw new Error(`Stripe API error: ${response.status} - ${error.error?.message || response.statusText}`);
    }

    return response.json();
  }

  // Create payment intent
  public async createPaymentIntent(request: PaymentIntentRequest): Promise<PaymentIntentResponse> {
    const data = {
      amount: request.amount.toString(),
      currency: request.currency || this.currency,
      ...(request.customer && { customer: request.customer }),
      ...(request.description && { description: request.description }),
      ...(request.metadata && { metadata: JSON.stringify(request.metadata) }),
      ...(request.payment_method_types && { 'payment_method_types[]': request.payment_method_types }),
      ...(request.receipt_email && { receipt_email: request.receipt_email })
    };

    return this.makeRequest<PaymentIntentResponse>('/v1/payment_intents', data);
  }

  // Create customer
  public async createCustomer(request: CustomerRequest): Promise<CustomerResponse> {
    const data = {
      email: request.email,
      ...(request.name && { name: request.name }),
      ...(request.phone && { phone: request.phone }),
      ...(request.description && { description: request.description }),
      ...(request.metadata && { metadata: JSON.stringify(request.metadata) })
    };

    return this.makeRequest<CustomerResponse>('/v1/customers', data);
  }

  // Get customer
  public async getCustomer(customerId: string): Promise<CustomerResponse> {
    return this.makeRequest<CustomerResponse>(`/v1/customers/${customerId}`, {}, 'GET');
  }

  // Update customer
  public async updateCustomer(customerId: string, updates: Partial<CustomerRequest>): Promise<CustomerResponse> {
    const data = {
      ...(updates.email && { email: updates.email }),
      ...(updates.name && { name: updates.name }),
      ...(updates.phone && { phone: updates.phone }),
      ...(updates.description && { description: updates.description }),
      ...(updates.metadata && { metadata: JSON.stringify(updates.metadata) })
    };

    return this.makeRequest<CustomerResponse>(`/v1/customers/${customerId}`, data, 'POST');
  }

  // Create product
  public async createProduct(request: ProductRequest): Promise<ProductResponse> {
    const data = {
      name: request.name,
      ...(request.description && { description: request.description }),
      ...(request.images && { images: request.images }),
      ...(request.metadata && { metadata: JSON.stringify(request.metadata) }),
      type: request.type || 'service'
    };

    return this.makeRequest<ProductResponse>('/v1/products', data);
  }

  // Create price
  public async createPrice(request: PriceRequest): Promise<PriceResponse> {
    const data = {
      unit_amount: request.unit_amount.toString(),
      currency: request.currency || this.currency,
      product: request.product,
      ...(request.recurring && { 
        'recurring[interval]': request.recurring.interval,
        ...(request.recurring.interval_count && { 'recurring[interval_count]': request.recurring.interval_count.toString() })
      }),
      ...(request.nickname && { nickname: request.nickname }),
      ...(request.metadata && { metadata: JSON.stringify(request.metadata) })
    };

    return this.makeRequest<PriceResponse>('/v1/prices', data);
  }

  // Create subscription
  public async createSubscription(request: SubscriptionRequest): Promise<SubscriptionResponse> {
    const data = {
      customer: request.customer,
      'items[0][price]': request.items[0].price,
      ...(request.items[0].quantity && { 'items[0][quantity]': request.items[0].quantity.toString() }),
      ...(request.trial_period_days && { trial_period_days: request.trial_period_days.toString() }),
      ...(request.metadata && { metadata: JSON.stringify(request.metadata) })
    };

    return this.makeRequest<SubscriptionResponse>('/v1/subscriptions', data);
  }

  // Get subscription
  public async getSubscription(subscriptionId: string): Promise<SubscriptionResponse> {
    return this.makeRequest<SubscriptionResponse>(`/v1/subscriptions/${subscriptionId}`, {}, 'GET');
  }

  // Cancel subscription
  public async cancelSubscription(subscriptionId: string, immediately: boolean = false): Promise<SubscriptionResponse> {
    const data = immediately ? { prorate: 'true' } : {};
    return this.makeRequest<SubscriptionResponse>(`/v1/subscriptions/${subscriptionId}`, data, 'DELETE');
  }

  // Get customer's subscriptions
  public async getCustomerSubscriptions(customerId: string): Promise<{ data: SubscriptionResponse[] }> {
    return this.makeRequest<{ data: SubscriptionResponse[] }>(`/v1/subscriptions?customer=${customerId}`, {}, 'GET');
  }

  // Create Stargate.ci specific products and prices
  public async createStargateProducts(): Promise<{ product: ProductResponse; price: PriceResponse }[]> {
    const products = [
      {
        name: 'Stargate.ci Basic Plan',
        description: 'Access to basic Stargate.ci features and educational content',
        price: 2900 // $29.00
      },
      {
        name: 'Stargate.ci Pro Plan',
        description: 'Advanced features, AI integration, and priority support',
        price: 9900 // $99.00
      },
      {
        name: 'Stargate.ci Enterprise Plan',
        description: 'Full access to all features, custom integrations, and dedicated support',
        price: 29900 // $299.00
      }
    ];

    const results = [];

    for (const productData of products) {
      // Create product
      const product = await this.createProduct({
        name: productData.name,
        description: productData.description,
        metadata: {
          platform: 'stargate.ci',
          category: 'subscription'
        }
      });

      // Create price
      const price = await this.createPrice({
        unit_amount: productData.price,
        currency: this.currency,
        product: product.id,
        recurring: {
          interval: 'month',
          interval_count: 1
        },
        nickname: `${productData.name} - Monthly`,
        metadata: {
          platform: 'stargate.ci',
          billing_cycle: 'monthly'
        }
      });

      results.push({ product, price });
    }

    return results;
  }

  // Process Stargate.ci subscription
  public async processStargateSubscription(
    customerEmail: string, 
    planType: 'basic' | 'pro' | 'enterprise',
    customerName?: string
  ): Promise<{ customer: CustomerResponse; subscription: SubscriptionResponse }> {
    // Create or get customer
    let customer: CustomerResponse;
    try {
      // Try to find existing customer by email
      const customers = await this.makeRequest<{ data: CustomerResponse[] }>(`/v1/customers?email=${customerEmail}`, {}, 'GET');
      customer = customers.data[0];
    } catch {
      // Create new customer
      customer = await this.createCustomer({
        email: customerEmail,
        name: customerName,
        description: `Stargate.ci ${planType} subscriber`,
        metadata: {
          platform: 'stargate.ci',
          plan_type: planType
        }
      });
    }

    // Get the appropriate price ID (in real implementation, store these in database)
    const priceIds = {
      basic: 'price_basic_monthly', // Replace with actual price ID
      pro: 'price_pro_monthly',     // Replace with actual price ID
      enterprise: 'price_enterprise_monthly' // Replace with actual price ID
    };

    // Create subscription
    const subscription = await this.createSubscription({
      customer: customer.id,
      items: [{ price: priceIds[planType] }],
      trial_period_days: 14, // 14-day free trial
      metadata: {
        platform: 'stargate.ci',
        plan_type: planType
      }
    });

    return { customer, subscription };
  }

  // Verify webhook signature
  public verifyWebhookSignature(payload: string, signature: string): boolean {
    // In a real implementation, use Stripe's webhook signature verification
    // For now, return true (implement proper verification in production)
    return true;
  }

  // Handle webhook events
  public async handleWebhookEvent(event: any): Promise<void> {
    switch (event.type) {
      case 'payment_intent.succeeded':
        await this.handlePaymentSucceeded(event.data.object);
        break;
      case 'customer.subscription.created':
        await this.handleSubscriptionCreated(event.data.object);
        break;
      case 'customer.subscription.updated':
        await this.handleSubscriptionUpdated(event.data.object);
        break;
      case 'customer.subscription.deleted':
        await this.handleSubscriptionDeleted(event.data.object);
        break;
      default:
        console.log(`Unhandled event type: ${event.type}`);
    }
  }

  private async handlePaymentSucceeded(paymentIntent: PaymentIntentResponse): Promise<void> {
    console.log('Payment succeeded:', paymentIntent.id);
    // Update user's subscription status in your database
  }

  private async handleSubscriptionCreated(subscription: SubscriptionResponse): Promise<void> {
    console.log('Subscription created:', subscription.id);
    // Activate user's subscription in your database
  }

  private async handleSubscriptionUpdated(subscription: SubscriptionResponse): Promise<void> {
    console.log('Subscription updated:', subscription.id);
    // Update user's subscription in your database
  }

  private async handleSubscriptionDeleted(subscription: SubscriptionResponse): Promise<void> {
    console.log('Subscription deleted:', subscription.id);
    // Deactivate user's subscription in your database
  }
}

// Export singleton instance
export const stripeService = new StripeService();
