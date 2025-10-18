<template>
  <div class="min-h-screen bg-gray-900 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold gradient-text mb-4">
          Subscribe to Stargate.ci
        </h1>
        <p class="text-xl text-gray-300">
          Stay updated with the latest news about Stargate Project and Cristal Intelligence
        </p>
      </div>

      <!-- Subscribe Form -->
      <div class="bg-gray-800 rounded-lg p-8 border border-gray-700">
        <form @submit.prevent="handleSubscribe" class="space-y-6">
          <!-- Username -->
          <div>
            <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
              Username (Optional)
            </label>
            <input
              type="text"
              id="username"
              v-model="form.username"
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter your username"
            />
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
              Email Address *
            </label>
            <input
              type="email"
              id="email"
              v-model="form.email"
              required
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter your email address"
            />
          </div>

          <!-- Country (Optional) -->
          <div>
            <label for="country" class="block text-sm font-medium text-gray-300 mb-2">
              Country (Optional)
            </label>
            <input
              type="text"
              id="country"
              v-model="form.country"
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter your country"
            />
          </div>

          <!-- Interests (Optional) -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Interests (Optional)
            </label>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.interests"
                  value="stargate"
                  class="rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500"
                />
                <span class="ml-2 text-gray-300">Stargate Project</span>
              </label>
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.interests"
                  value="cristal"
                  class="rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500"
                />
                <span class="ml-2 text-gray-300">Cristal Intelligence</span>
              </label>
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.interests"
                  value="ai"
                  class="rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500"
                />
                <span class="ml-2 text-gray-300">AI Technology</span>
              </label>
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.interests"
                  value="news"
                  class="rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500"
                />
                <span class="ml-2 text-gray-300">Latest News</span>
              </label>
            </div>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isSubmitting"
            class="w-full bg-gradient-to-r from-primary-500 to-secondary-500 text-white py-3 px-6 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
          >
            {{ isSubmitting ? 'Subscribing...' : 'Subscribe' }}
          </button>
        </form>

        <!-- Success Message -->
        <div v-if="showSuccess" class="mt-6 p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
          <p class="text-green-200 text-center">
            ✅ Successfully subscribed! You'll receive updates about Stargate Project and Cristal Intelligence.
          </p>
        </div>

        <!-- Error Message -->
        <div v-if="showError" class="mt-6 p-4 bg-red-900/20 border border-red-500/30 rounded-lg">
          <p class="text-red-200 text-center">
            ❌ {{ errorMessage }}
          </p>
        </div>
      </div>

      <!-- Benefits -->
      <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center">
          <div class="w-12 h-12 bg-primary-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-white mb-2">Latest Updates</h3>
          <p class="text-gray-400">Get notified about new developments in Stargate Project and Cristal Intelligence</p>
        </div>

        <div class="text-center">
          <div class="w-12 h-12 bg-secondary-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-white mb-2">Educational Content</h3>
          <p class="text-gray-400">Access to exclusive educational materials and insights</p>
        </div>

        <div class="text-center">
          <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-white mb-2">Community Access</h3>
          <p class="text-gray-400">Join our community of AI enthusiasts and researchers</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useHead } from '@vueuse/head'
import { useRouter } from 'vue-router'

// Router
const router = useRouter()

// Set page title
useHead({
  title: 'Subscribe - Stargate.ci',
  meta: [
    { name: 'description', content: 'Subscribe to Stargate.ci for the latest updates on Stargate Project and Cristal Intelligence' }
  ]
})

// Form data
const form = ref({
  username: '',
  email: '',
  country: '',
  interests: [] as string[]
})

// UI state
const isSubmitting = ref(false)
const showSuccess = ref(false)
const showError = ref(false)
const errorMessage = ref('')

// Handle form submission
const handleSubscribe = async () => {
  isSubmitting.value = true
  showSuccess.value = false
  showError.value = false

  try {
    // Validate email
    if (!form.value.email) {
      throw new Error('Email is required')
    }

    // Create subscriber object
    const subscriber = {
      id: Date.now(),
      username: form.value.username || 'Anonymous',
      email: form.value.email,
      country: form.value.country || 'Unknown',
      interests: form.value.interests,
      subscribedAt: new Date().toISOString(),
      status: 'active'
    }

    // Get existing subscribers
    const existingSubscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
    
    // Check if email already exists
    const emailExists = existingSubscribers.some((sub: any) => sub.email === form.value.email)
    if (emailExists) {
      throw new Error('This email is already subscribed')
    }

    // Add new subscriber
    existingSubscribers.push(subscriber)
    localStorage.setItem('stargate_subscribers', JSON.stringify(existingSubscribers))

    // Simulate email notification
    console.log('Sending email notification to:', form.value.email)
    console.log('Subscriber data:', subscriber)

    // Show success message
    showSuccess.value = true
    
    // Dispatch custom event to update navbar
    window.dispatchEvent(new CustomEvent('subscription-changed'))
    
    // Reset form
    form.value = {
      username: '',
      email: '',
      country: '',
      interests: []
    }

    // Redirect to home page after 2 seconds
    setTimeout(() => {
      router.push('/')
    }, 2000)

  } catch (error: any) {
    errorMessage.value = error.message
    showError.value = true
    
    // Hide error message after 5 seconds
    setTimeout(() => {
      showError.value = false
    }, 5000)
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #0ea5e9, #a855f7);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>
