/**
 * Payment Service - Handles all payment-related operations
 * Simulates payment processing, subscription management, and billing
 */

export interface PaymentMethod {
  id: string
  type: 'credit_card' | 'paypal' | 'bank_transfer'
  last4?: string
  brand?: string
  expiryMonth?: number
  expiryYear?: number
  isDefault: boolean
  createdAt: string
}

export interface SubscriptionPlan {
  id: string
  name: string
  description: string
  price: number
  currency: string
  interval: 'monthly' | 'quarterly' | 'annually'
  features: string[]
  maxUsers?: number
  maxProjects?: number
  maxStorage?: number // in GB
  isPopular?: boolean
  isEnterprise?: boolean
}

export interface Subscription {
  id: string
  userId: string
  planId: string
  status: 'active' | 'cancelled' | 'past_due' | 'trialing'
  currentPeriodStart: string
  currentPeriodEnd: string
  cancelAtPeriodEnd: boolean
  trialEnd?: string
  createdAt: string
  updatedAt: string
}

export interface Invoice {
  id: string
  userId: string
  subscriptionId: string
  amount: number
  currency: string
  status: 'paid' | 'pending' | 'failed' | 'refunded'
  dueDate: string
  paidAt?: string
  invoiceUrl: string
  receiptUrl?: string
  createdAt: string
}

export interface PaymentTransaction {
  id: string
  userId: string
  amount: number
  currency: string
  status: 'succeeded' | 'pending' | 'failed' | 'refunded'
  paymentMethodId: string
  description: string
  metadata?: Record<string, any>
  createdAt: string
}

export interface BillingAddress {
  line1: string
  line2?: string
  city: string
  state: string
  postalCode: string
  country: string
}

class PaymentService {
  private storageKey = 'stargate_payment_data'
  private currentUser: any = null

  constructor() {
    this.initializeData()
  }

  private initializeData() {
    const existingData = localStorage.getItem(this.storageKey)
    if (!existingData) {
      const initialData = {
        paymentMethods: [],
        subscriptions: [],
        invoices: [],
        transactions: [],
        plans: this.getDefaultPlans()
      }
      localStorage.setItem(this.storageKey, JSON.stringify(initialData))
    }
  }

  private getDefaultPlans(): SubscriptionPlan[] {
    return [
      {
        id: 'free',
        name: 'Free',
        description: 'Perfect for getting started with Stargate',
        price: 0,
        currency: 'USD',
        interval: 'monthly',
        features: [
          'Basic Stargate access',
          'Community support',
          '5 projects maximum',
          '1GB storage',
          'Basic analytics'
        ],
        maxUsers: 1,
        maxProjects: 5,
        maxStorage: 1
      },
      {
        id: 'basic',
        name: 'Basic',
        description: 'For small teams and individual developers',
        price: 29,
        currency: 'USD',
        interval: 'monthly',
        features: [
          'Everything in Free',
          'Priority support',
          '25 projects maximum',
          '10GB storage',
          'Advanced analytics',
          'API access',
          'Custom integrations'
        ],
        maxUsers: 5,
        maxProjects: 25,
        maxStorage: 10
      },
      {
        id: 'premium',
        name: 'Premium',
        description: 'For growing teams and enterprises',
        price: 99,
        currency: 'USD',
        interval: 'monthly',
        features: [
          'Everything in Basic',
          '24/7 phone support',
          'Unlimited projects',
          '100GB storage',
          'Advanced security features',
          'Team collaboration tools',
          'Custom branding',
          'SSO integration'
        ],
        maxUsers: 25,
        maxProjects: -1, // unlimited
        maxStorage: 100,
        isPopular: true
      },
      {
        id: 'enterprise',
        name: 'Enterprise',
        description: 'For large organizations with custom needs',
        price: 299,
        currency: 'USD',
        interval: 'monthly',
        features: [
          'Everything in Premium',
          'Dedicated account manager',
          'Unlimited everything',
          'Custom integrations',
          'On-premise deployment',
          'Advanced compliance',
          'Custom SLA',
          'Training and onboarding'
        ],
        maxUsers: -1, // unlimited
        maxProjects: -1, // unlimited
        maxStorage: -1, // unlimited
        isEnterprise: true
      }
    ]
  }

  private getData() {
    const data = localStorage.getItem(this.storageKey)
    return data ? JSON.parse(data) : {
      paymentMethods: [],
      subscriptions: [],
      invoices: [],
      transactions: [],
      plans: this.getDefaultPlans()
    }
  }

  private saveData(data: any) {
    localStorage.setItem(this.storageKey, JSON.stringify(data))
  }

  // Payment Methods
  async getPaymentMethods(userId: string): Promise<PaymentMethod[]> {
    const data = this.getData()
    return data.paymentMethods.filter((pm: PaymentMethod) => pm.id.includes(userId))
  }

  async addPaymentMethod(userId: string, paymentMethod: Omit<PaymentMethod, 'id' | 'createdAt'>): Promise<PaymentMethod> {
    const data = this.getData()
    const newPaymentMethod: PaymentMethod = {
      ...paymentMethod,
      id: `pm_${userId}_${Date.now()}`,
      createdAt: new Date().toISOString()
    }
    
    data.paymentMethods.push(newPaymentMethod)
    this.saveData(data)
    
    return newPaymentMethod
  }

  async removePaymentMethod(paymentMethodId: string): Promise<boolean> {
    const data = this.getData()
    const index = data.paymentMethods.findIndex((pm: PaymentMethod) => pm.id === paymentMethodId)
    if (index > -1) {
      data.paymentMethods.splice(index, 1)
      this.saveData(data)
      return true
    }
    return false
  }

  async setDefaultPaymentMethod(paymentMethodId: string): Promise<boolean> {
    const data = this.getData()
    const paymentMethod = data.paymentMethods.find((pm: PaymentMethod) => pm.id === paymentMethodId)
    if (paymentMethod) {
      // Remove default from all other methods
      data.paymentMethods.forEach((pm: PaymentMethod) => {
        pm.isDefault = pm.id === paymentMethodId
      })
      this.saveData(data)
      return true
    }
    return false
  }

  // Subscription Plans
  async getPlans(): Promise<SubscriptionPlan[]> {
    const data = this.getData()
    return data.plans
  }

  async getPlan(planId: string): Promise<SubscriptionPlan | null> {
    const data = this.getData()
    return data.plans.find((plan: SubscriptionPlan) => plan.id === planId) || null
  }

  // Subscriptions
  async getSubscription(userId: string): Promise<Subscription | null> {
    const data = this.getData()
    return data.subscriptions.find((sub: Subscription) => sub.userId === userId) || null
  }

  async createSubscription(userId: string, planId: string, paymentMethodId: string): Promise<Subscription> {
    const data = this.getData()
    const plan = await this.getPlan(planId)
    if (!plan) {
      throw new Error('Plan not found')
    }

    const now = new Date()
    const periodEnd = new Date(now)
    
    // Set period end based on interval
    switch (plan.interval) {
      case 'monthly':
        periodEnd.setMonth(periodEnd.getMonth() + 1)
        break
      case 'quarterly':
        periodEnd.setMonth(periodEnd.getMonth() + 3)
        break
      case 'annually':
        periodEnd.setFullYear(periodEnd.getFullYear() + 1)
        break
    }

    const subscription: Subscription = {
      id: `sub_${userId}_${Date.now()}`,
      userId,
      planId,
      status: 'active',
      currentPeriodStart: now.toISOString(),
      currentPeriodEnd: periodEnd.toISOString(),
      cancelAtPeriodEnd: false,
      createdAt: now.toISOString(),
      updatedAt: now.toISOString()
    }

    // Create initial invoice
    await this.createInvoice(userId, subscription.id, plan.price, plan.currency)

    data.subscriptions.push(subscription)
    this.saveData(data)

    return subscription
  }

  async updateSubscription(subscriptionId: string, updates: Partial<Subscription>): Promise<Subscription | null> {
    const data = this.getData()
    const index = data.subscriptions.findIndex((sub: Subscription) => sub.id === subscriptionId)
    if (index > -1) {
      data.subscriptions[index] = {
        ...data.subscriptions[index],
        ...updates,
        updatedAt: new Date().toISOString()
      }
      this.saveData(data)
      return data.subscriptions[index]
    }
    return null
  }

  async cancelSubscription(subscriptionId: string, cancelAtPeriodEnd: boolean = true): Promise<boolean> {
    const data = this.getData()
    const subscription = data.subscriptions.find((sub: Subscription) => sub.id === subscriptionId)
    if (subscription) {
      if (cancelAtPeriodEnd) {
        subscription.cancelAtPeriodEnd = true
        subscription.updatedAt = new Date().toISOString()
      } else {
        subscription.status = 'cancelled'
        subscription.updatedAt = new Date().toISOString()
      }
      this.saveData(data)
      return true
    }
    return false
  }

  // Invoices
  async getInvoices(userId: string): Promise<Invoice[]> {
    const data = this.getData()
    return data.invoices.filter((invoice: Invoice) => invoice.userId === userId)
  }

  async createInvoice(userId: string, subscriptionId: string, amount: number, currency: string): Promise<Invoice> {
    const data = this.getData()
    const now = new Date()
    const dueDate = new Date(now)
    dueDate.setDate(dueDate.getDate() + 30) // 30 days to pay

    const invoice: Invoice = {
      id: `inv_${userId}_${Date.now()}`,
      userId,
      subscriptionId,
      amount,
      currency,
      status: 'pending',
      dueDate: dueDate.toISOString(),
      invoiceUrl: `https://stargate.ci/invoices/${userId}_${Date.now()}`,
      createdAt: now.toISOString()
    }

    data.invoices.push(invoice)
    this.saveData(data)

    return invoice
  }

  async payInvoice(invoiceId: string, paymentMethodId: string): Promise<boolean> {
    const data = this.getData()
    const invoice = data.invoices.find((inv: Invoice) => inv.id === invoiceId)
    if (invoice) {
      // Simulate payment processing
      const success = Math.random() > 0.1 // 90% success rate
      
      if (success) {
        invoice.status = 'paid'
        invoice.paidAt = new Date().toISOString()
        invoice.receiptUrl = `https://stargate.ci/receipts/${invoiceId}`
        
        // Create transaction record
        const transaction: PaymentTransaction = {
          id: `txn_${Date.now()}`,
          userId: invoice.userId,
          amount: invoice.amount,
          currency: invoice.currency,
          status: 'succeeded',
          paymentMethodId,
          description: `Payment for invoice ${invoiceId}`,
          createdAt: new Date().toISOString()
        }
        
        data.transactions.push(transaction)
      } else {
        invoice.status = 'failed'
      }
      
      this.saveData(data)
      return success
    }
    return false
  }

  // Transactions
  async getTransactions(userId: string): Promise<PaymentTransaction[]> {
    const data = this.getData()
    return data.transactions.filter((txn: PaymentTransaction) => txn.userId === userId)
  }

  // Billing
  async getBillingSummary(userId: string): Promise<{
    subscription: Subscription | null
    nextInvoice: Invoice | null
    paymentMethods: PaymentMethod[]
    recentTransactions: PaymentTransaction[]
  }> {
    const subscription = await this.getSubscription(userId)
    const invoices = await this.getInvoices(userId)
    const paymentMethods = await this.getPaymentMethods(userId)
    const transactions = await this.getTransactions(userId)

    const nextInvoice = invoices
      .filter(inv => inv.status === 'pending')
      .sort((a, b) => new Date(a.dueDate).getTime() - new Date(b.dueDate).getTime())[0] || null

    const recentTransactions = transactions
      .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
      .slice(0, 5)

    return {
      subscription,
      nextInvoice,
      paymentMethods,
      recentTransactions
    }
  }

  // Utility methods
  async validatePaymentMethod(paymentMethod: any): Promise<boolean> {
    // Simulate validation
    return paymentMethod.type && paymentMethod.type !== ''
  }

  async processRefund(transactionId: string, amount?: number): Promise<boolean> {
    const data = this.getData()
    const transaction = data.transactions.find((txn: PaymentTransaction) => txn.id === transactionId)
    if (transaction && transaction.status === 'succeeded') {
      transaction.status = 'refunded'
      this.saveData(data)
      return true
    }
    return false
  }

  // Admin methods
  async getAllSubscriptions(): Promise<Subscription[]> {
    const data = this.getData()
    return data.subscriptions
  }

  async getAllInvoices(): Promise<Invoice[]> {
    const data = this.getData()
    return data.invoices
  }

  async getAllTransactions(): Promise<PaymentTransaction[]> {
    const data = this.getData()
    return data.transactions
  }

  async getRevenueStats(): Promise<{
    totalRevenue: number
    monthlyRevenue: number
    activeSubscriptions: number
    churnRate: number
  }> {
    const data = this.getData()
    const paidInvoices = data.invoices.filter((inv: Invoice) => inv.status === 'paid')
    const activeSubscriptions = data.subscriptions.filter((sub: Subscription) => sub.status === 'active')
    
    const totalRevenue = paidInvoices.reduce((sum: number, inv: Invoice) => sum + inv.amount, 0)
    
    const now = new Date()
    const thisMonth = new Date(now.getFullYear(), now.getMonth(), 1)
    const monthlyRevenue = paidInvoices
      .filter((inv: Invoice) => new Date(inv.paidAt || inv.createdAt) >= thisMonth)
      .reduce((sum: number, inv: Invoice) => sum + inv.amount, 0)

    const cancelledSubscriptions = data.subscriptions.filter((sub: Subscription) => sub.status === 'cancelled')
    const churnRate = activeSubscriptions.length > 0 
      ? (cancelledSubscriptions.length / (activeSubscriptions.length + cancelledSubscriptions.length)) * 100 
      : 0

    return {
      totalRevenue,
      monthlyRevenue,
      activeSubscriptions: activeSubscriptions.length,
      churnRate
    }
  }
}

export const paymentService = new PaymentService()
