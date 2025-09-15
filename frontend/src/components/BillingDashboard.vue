<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">Billing & Subscription</h1>
      <p class="text-gray-300">Manage your subscription, payment methods, and billing history</p>
    </div>

    <!-- Current Subscription Status -->
    <div v-if="billingSummary.subscription" class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-white">Current Subscription</h2>
        <span 
          :class="getStatusBadgeClass(billingSummary.subscription.status)"
          class="px-3 py-1 rounded-full text-sm font-medium"
        >
          {{ getStatusText(billingSummary.subscription.status) }}
        </span>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
          <h3 class="text-lg font-semibold text-white mb-2">{{ currentPlan?.name }}</h3>
          <p class="text-gray-300 text-sm mb-2">{{ currentPlan?.description }}</p>
          <div class="text-2xl font-bold text-primary-400">
            ${{ currentPlan?.price }}/{{ currentPlan?.interval }}
          </div>
        </div>
        
        <div>
          <h4 class="text-sm font-medium text-gray-300 mb-2">Current Period</h4>
          <p class="text-white">
            {{ formatDate(billingSummary.subscription.currentPeriodStart) }} - 
            {{ formatDate(billingSummary.subscription.currentPeriodEnd) }}
          </p>
          <div v-if="billingSummary.subscription.cancelAtPeriodEnd" class="mt-2">
            <span class="text-yellow-400 text-sm">⚠️ Cancels at period end</span>
          </div>
        </div>
        
        <div class="flex flex-col space-y-2">
          <button
            v-if="!billingSummary.subscription.cancelAtPeriodEnd"
            @click="showCancelModal = true"
            class="px-4 py-2 bg-red-600/20 text-red-400 rounded-lg hover:bg-red-600/30 transition-colors"
          >
            Cancel Subscription
          </button>
          <button
            @click="showUpgradeModal = true"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
          >
            Change Plan
          </button>
        </div>
      </div>
    </div>

    <!-- No Subscription -->
    <div v-else class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <div class="text-center">
        <h2 class="text-xl font-bold text-white mb-2">No Active Subscription</h2>
        <p class="text-gray-300 mb-4">You're currently on the free plan</p>
        <button
          @click="showUpgradeModal = true"
          class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium"
        >
          Upgrade Now
        </button>
      </div>
    </div>

    <!-- Next Invoice -->
    <div v-if="billingSummary.nextInvoice" class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <h2 class="text-xl font-bold text-white mb-4">Next Invoice</h2>
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-300">Due: {{ formatDate(billingSummary.nextInvoice.dueDate) }}</p>
          <p class="text-2xl font-bold text-white">${{ billingSummary.nextInvoice.amount }} {{ billingSummary.nextInvoice.currency }}</p>
        </div>
        <button
          @click="payInvoice(billingSummary.nextInvoice.id)"
          :disabled="isProcessing"
          class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors font-medium"
        >
          <span v-if="isProcessing">Processing...</span>
          <span v-else>Pay Now</span>
        </button>
      </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-white">Payment Methods</h2>
        <button
          @click="showAddPaymentMethod = true"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
        >
          Add Payment Method
        </button>
      </div>
      
      <div v-if="billingSummary.paymentMethods.length > 0" class="space-y-3">
        <div
          v-for="method in billingSummary.paymentMethods"
          :key="method.id"
          class="flex items-center justify-between p-4 bg-gray-700 rounded-lg border border-gray-600"
        >
          <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
              </svg>
            </div>
            <div>
              <p class="text-white font-medium">
                {{ method.brand?.toUpperCase() }} •••• {{ method.last4 }}
              </p>
              <p class="text-gray-400 text-sm">
                Expires {{ method.expiryMonth }}/{{ method.expiryYear }}
                <span v-if="method.isDefault" class="ml-2 text-primary-400">(Default)</span>
              </p>
            </div>
          </div>
          <div class="flex space-x-2">
            <button
              v-if="!method.isDefault"
              @click="setDefaultPaymentMethod(method.id)"
              class="text-primary-400 hover:text-primary-300 text-sm"
            >
              Set Default
            </button>
            <button
              @click="removePaymentMethod(method.id)"
              class="text-red-400 hover:text-red-300 text-sm"
            >
              Remove
            </button>
          </div>
        </div>
      </div>
      
      <div v-else class="text-center py-8">
        <p class="text-gray-400 mb-4">No payment methods added</p>
        <button
          @click="showAddPaymentMethod = true"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
        >
          Add Your First Payment Method
        </button>
      </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
      <h2 class="text-xl font-bold text-white mb-4">Recent Transactions</h2>
      
      <div v-if="billingSummary.recentTransactions.length > 0" class="space-y-3">
        <div
          v-for="transaction in billingSummary.recentTransactions"
          :key="transaction.id"
          class="flex items-center justify-between p-4 bg-gray-700 rounded-lg border border-gray-600"
        >
          <div>
            <p class="text-white font-medium">{{ transaction.description }}</p>
            <p class="text-gray-400 text-sm">{{ formatDate(transaction.createdAt) }}</p>
          </div>
          <div class="text-right">
            <p class="text-white font-medium">${{ transaction.amount }} {{ transaction.currency }}</p>
            <span 
              :class="getTransactionStatusClass(transaction.status)"
              class="text-sm"
            >
              {{ getTransactionStatusText(transaction.status) }}
            </span>
          </div>
        </div>
      </div>
      
      <div v-else class="text-center py-8">
        <p class="text-gray-400">No transactions yet</p>
      </div>
    </div>

    <!-- Modals -->
    <!-- Add Payment Method Modal -->
    <div v-if="showAddPaymentMethod" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showAddPaymentMethod = false"></div>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-white">Add Payment Method</h3>
              <button @click="showAddPaymentMethod = false" class="text-gray-400 hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <form @submit.prevent="addPaymentMethod">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Card Number</label>
                  <input
                    v-model="newPaymentMethod.cardNumber"
                    type="text"
                    placeholder="1234 5678 9012 3456"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                    required
                  />
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Expiry Date</label>
                    <input
                      v-model="newPaymentMethod.expiryDate"
                      type="text"
                      placeholder="MM/YY"
                      class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                      required
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">CVC</label>
                    <input
                      v-model="newPaymentMethod.cvc"
                      type="text"
                      placeholder="123"
                      class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                      required
                    />
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Cardholder Name</label>
                  <input
                    v-model="newPaymentMethod.cardholderName"
                    type="text"
                    placeholder="John Doe"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                    required
                  />
                </div>
              </div>
              
              <div class="mt-6 flex justify-end space-x-3">
                <button
                  type="button"
                  @click="showAddPaymentMethod = false"
                  class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="isProcessing"
                  class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors"
                >
                  <span v-if="isProcessing">Adding...</span>
                  <span v-else>Add Payment Method</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Upgrade Plan Modal -->
    <div v-if="showUpgradeModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showUpgradeModal = false"></div>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
          <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-medium text-white">Choose Your Plan</h3>
              <button @click="showUpgradeModal = false" class="text-gray-400 hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div
                v-for="plan in availablePlans"
                :key="plan.id"
                :class="[
                  'relative p-6 rounded-lg border-2 transition-all cursor-pointer',
                  selectedPlan?.id === plan.id 
                    ? 'border-primary-500 bg-primary-500/10' 
                    : 'border-gray-600 bg-gray-700 hover:border-gray-500',
                  plan.isPopular ? 'ring-2 ring-primary-500/50' : ''
                ]"
                @click="selectedPlan = plan"
              >
                <div v-if="plan.isPopular" class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                  <span class="bg-primary-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                    Most Popular
                  </span>
                </div>
                
                <div class="text-center">
                  <h4 class="text-lg font-bold text-white mb-2">{{ plan.name }}</h4>
                  <p class="text-gray-300 text-sm mb-4">{{ plan.description }}</p>
                  
                  <div class="mb-4">
                    <span class="text-3xl font-bold text-white">${{ plan.price }}</span>
                    <span class="text-gray-400">/{{ plan.interval }}</span>
                  </div>
                  
                  <ul class="text-sm text-gray-300 space-y-2 mb-6">
                    <li v-for="feature in plan.features" :key="feature" class="flex items-center">
                      <svg class="w-4 h-4 text-primary-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      {{ feature }}
                    </li>
                  </ul>
                  
                  <button
                    @click="upgradeToPlan(plan)"
                    :disabled="isProcessing"
                    :class="[
                      'w-full px-4 py-2 rounded-lg font-medium transition-colors',
                      selectedPlan?.id === plan.id
                        ? 'bg-primary-600 text-white hover:bg-primary-700'
                        : 'bg-gray-600 text-gray-300 hover:bg-gray-500'
                    ]"
                  >
                    <span v-if="isProcessing">Processing...</span>
                    <span v-else>{{ plan.price === 0 ? 'Current Plan' : 'Select Plan' }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Cancel Subscription Modal -->
    <div v-if="showCancelModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showCancelModal = false"></div>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-white">Cancel Subscription</h3>
              <button @click="showCancelModal = false" class="text-gray-400 hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <p class="text-gray-300 mb-6">
              Are you sure you want to cancel your subscription? You'll continue to have access until the end of your current billing period.
            </p>
            
            <div class="flex justify-end space-x-3">
              <button
                @click="showCancelModal = false"
                class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors"
              >
                Keep Subscription
              </button>
              <button
                @click="cancelSubscription"
                :disabled="isProcessing"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors"
              >
                <span v-if="isProcessing">Cancelling...</span>
                <span v-else>Cancel Subscription</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { paymentService, type Subscription, type PaymentMethod, type Invoice, type PaymentTransaction, type SubscriptionPlan } from '../services/paymentService'
import { authService } from '../services/authService'

const { t } = useI18n()

// Reactive data
const billingSummary = ref<{
  subscription: Subscription | null
  nextInvoice: Invoice | null
  paymentMethods: PaymentMethod[]
  recentTransactions: PaymentTransaction[]
}>({
  subscription: null,
  nextInvoice: null,
  paymentMethods: [],
  recentTransactions: []
})

const availablePlans = ref<SubscriptionPlan[]>([])
const currentPlan = ref<SubscriptionPlan | null>(null)
const selectedPlan = ref<SubscriptionPlan | null>(null)

// Modal states
const showAddPaymentMethod = ref(false)
const showUpgradeModal = ref(false)
const showCancelModal = ref(false)
const isProcessing = ref(false)

// New payment method form
const newPaymentMethod = ref({
  cardNumber: '',
  expiryDate: '',
  cvc: '',
  cardholderName: ''
})

// Methods
const loadBillingData = async () => {
  try {
    const currentUser = authService.getCurrentUser()
    if (!currentUser) return

    const summary = await paymentService.getBillingSummary(currentUser.id)
    billingSummary.value = summary

    // Load current plan
    if (summary.subscription) {
      currentPlan.value = await paymentService.getPlan(summary.subscription.planId)
    }

    // Load available plans
    availablePlans.value = await paymentService.getPlans()
  } catch (error) {
    console.error('Error loading billing data:', error)
  }
}

const addPaymentMethod = async () => {
  try {
    isProcessing.value = true
    const currentUser = authService.getCurrentUser()
    if (!currentUser) return

    // Extract card details
    const cardNumber = newPaymentMethod.value.cardNumber.replace(/\s/g, '')
    const [expiryMonth, expiryYear] = newPaymentMethod.value.expiryDate.split('/')
    
    const paymentMethod = await paymentService.addPaymentMethod(currentUser.id, {
      type: 'credit_card',
      last4: cardNumber.slice(-4),
      brand: getCardBrand(cardNumber),
      expiryMonth: parseInt(expiryMonth),
      expiryYear: parseInt('20' + expiryYear),
      isDefault: billingSummary.value.paymentMethods.length === 0
    })

    // Reset form
    newPaymentMethod.value = {
      cardNumber: '',
      expiryDate: '',
      cvc: '',
      cardholderName: ''
    }

    showAddPaymentMethod.value = false
    await loadBillingData()
  } catch (error) {
    console.error('Error adding payment method:', error)
  } finally {
    isProcessing.value = false
  }
}

const removePaymentMethod = async (paymentMethodId: string) => {
  try {
    await paymentService.removePaymentMethod(paymentMethodId)
    await loadBillingData()
  } catch (error) {
    console.error('Error removing payment method:', error)
  }
}

const setDefaultPaymentMethod = async (paymentMethodId: string) => {
  try {
    await paymentService.setDefaultPaymentMethod(paymentMethodId)
    await loadBillingData()
  } catch (error) {
    console.error('Error setting default payment method:', error)
  }
}

const upgradeToPlan = async (plan: SubscriptionPlan) => {
  try {
    isProcessing.value = true
    const currentUser = authService.getCurrentUser()
    if (!currentUser) return

    // Check if user has payment methods for paid plans
    if (plan.price > 0 && billingSummary.value.paymentMethods.length === 0) {
      alert('Please add a payment method first')
      showUpgradeModal.value = false
      showAddPaymentMethod.value = true
      return
    }

    // Create or update subscription
    if (billingSummary.value.subscription) {
      // Update existing subscription
      await paymentService.updateSubscription(billingSummary.value.subscription.id, {
        planId: plan.id
      })
    } else {
      // Create new subscription
      const defaultPaymentMethod = billingSummary.value.paymentMethods.find(pm => pm.isDefault)
      if (defaultPaymentMethod) {
        await paymentService.createSubscription(currentUser.id, plan.id, defaultPaymentMethod.id)
      }
    }

    showUpgradeModal.value = false
    await loadBillingData()
  } catch (error) {
    console.error('Error upgrading plan:', error)
  } finally {
    isProcessing.value = false
  }
}

const cancelSubscription = async () => {
  try {
    isProcessing.value = true
    if (billingSummary.value.subscription) {
      await paymentService.cancelSubscription(billingSummary.value.subscription.id, true)
      showCancelModal.value = false
      await loadBillingData()
    }
  } catch (error) {
    console.error('Error cancelling subscription:', error)
  } finally {
    isProcessing.value = false
  }
}

const payInvoice = async (invoiceId: string) => {
  try {
    isProcessing.value = true
    const defaultPaymentMethod = billingSummary.value.paymentMethods.find(pm => pm.isDefault)
    if (defaultPaymentMethod) {
      const success = await paymentService.payInvoice(invoiceId, defaultPaymentMethod.id)
      if (success) {
        await loadBillingData()
      } else {
        alert('Payment failed. Please try again.')
      }
    }
  } catch (error) {
    console.error('Error paying invoice:', error)
  } finally {
    isProcessing.value = false
  }
}

// Utility functions
const getCardBrand = (cardNumber: string): string => {
  if (cardNumber.startsWith('4')) return 'visa'
  if (cardNumber.startsWith('5')) return 'mastercard'
  if (cardNumber.startsWith('3')) return 'amex'
  return 'unknown'
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString()
}

const getStatusBadgeClass = (status: string): string => {
  switch (status) {
    case 'active': return 'bg-green-600/20 text-green-400'
    case 'cancelled': return 'bg-red-600/20 text-red-400'
    case 'past_due': return 'bg-yellow-600/20 text-yellow-400'
    case 'trialing': return 'bg-blue-600/20 text-blue-400'
    default: return 'bg-gray-600/20 text-gray-400'
  }
}

const getStatusText = (status: string): string => {
  switch (status) {
    case 'active': return 'Active'
    case 'cancelled': return 'Cancelled'
    case 'past_due': return 'Past Due'
    case 'trialing': return 'Trial'
    default: return 'Unknown'
  }
}

const getTransactionStatusClass = (status: string): string => {
  switch (status) {
    case 'succeeded': return 'text-green-400'
    case 'pending': return 'text-yellow-400'
    case 'failed': return 'text-red-400'
    case 'refunded': return 'text-gray-400'
    default: return 'text-gray-400'
  }
}

const getTransactionStatusText = (status: string): string => {
  switch (status) {
    case 'succeeded': return 'Completed'
    case 'pending': return 'Pending'
    case 'failed': return 'Failed'
    case 'refunded': return 'Refunded'
    default: return 'Unknown'
  }
}

// Lifecycle
onMounted(() => {
  loadBillingData()
})
</script>
