<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="relative overflow-hidden">
      <!-- Background Effects -->
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <!-- Header -->
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-end">
          <RouterLink to="/" class="text-gray-400 hover:text-white transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </RouterLink>
        </div>
      </div>

      <!-- Main Content -->
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-8">
          <h1 class="text-3xl md:text-4xl font-bold mb-3">
            <span class="gradient-text">Subscribe</span>
          </h1>
          <p class="text-lg text-gray-300 max-w-xl mx-auto">
            Get notified about Stargate Project and Cristal Intelligence updates.
          </p>
        </div>

        <!-- Subscription Form -->
        <div class="max-w-2xl mx-auto">
          <div class="card">
            <div class="text-center mb-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="text-xl">📧</span>
              </div>
              <h2 class="text-xl font-bold mb-2">
                <span class="gradient-text">Stay Updated</span>
              </h2>
              <p class="text-gray-400 text-sm">Simple subscription form</p>
            </div>

            <form @submit.prevent="handleSubscription" class="space-y-4">
              <div>
                <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
                  Username *
                </label>
                <input
                  id="username"
                  v-model="subscriptionForm.username"
                  type="text"
                  required
                  class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
                  placeholder="Choose a username"
                />
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                  Email Address *
                </label>
                <input
                  id="email"
                  v-model="subscriptionForm.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
                  placeholder="Enter your email address"
                />
              </div>

              <div>
                <label for="role" class="block text-sm font-medium text-gray-300 mb-2">
                  Your Role
                </label>
                <select
                  id="role"
                  v-model="subscriptionForm.role"
                  class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white"
                >
                  <option value="">Select your role</option>
                  <option value="researcher">Researcher</option>
                  <option value="developer">Developer</option>
                  <option value="business">Business Professional</option>
                  <option value="student">Student</option>
                  <option value="investor">Investor</option>
                  <option value="entrepreneur">Entrepreneur</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="flex items-center">
                <input
                  v-model="subscriptionForm.agreeToTerms"
                  type="checkbox"
                  required
                  class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                />
                <span class="ml-2 text-sm text-gray-300">
                  I agree to receive updates and newsletters from Stargate.ci
                </span>
              </div>

              <div v-if="subscriptionError" class="p-3 bg-red-900/20 border border-red-500/50 rounded-lg">
                <p class="text-red-400 text-sm">{{ subscriptionError }}</p>
              </div>

              <button
                type="submit"
                :disabled="isSubscribing"
                class="w-full btn-primary py-3 text-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="isSubscribing" class="flex items-center justify-center">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Subscribing...
                </span>
                <span v-else>Subscribe Now</span>
              </button>
            </form>

            <div class="mt-4 text-center">
              <p class="text-gray-400 text-sm">
                Already subscribed?
                <RouterLink to="/dashboard" class="text-primary-400 hover:text-primary-300 font-medium">
                  Access your dashboard
                </RouterLink>
              </p>
            </div>

            <!-- Removed admin login section as it's unnecessary for educational platform -->
          </div>
        </div>

        <!-- Benefits -->
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">📰</span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Latest News</h3>
            <p class="text-gray-400 text-sm">Stay updated with the latest developments in Stargate Project and Cristal Intelligence</p>
          </div>
          
          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🎓</span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Educational Resources</h3>
            <p class="text-gray-400 text-sm">Access comprehensive learning materials and insights about AI technologies</p>
          </div>
          
          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🌐</span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Global Community</h3>
            <p class="text-gray-400 text-sm">Join a worldwide community of innovators and technology enthusiasts</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    <div v-if="subscriptionSuccess" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg p-8 w-full max-w-md text-center">
        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Subscription Successful!</h3>
        <p class="text-gray-400 mb-4">Thank you for subscribing to Stargate.ci updates. You'll receive the latest news and insights about Stargate Project and Cristal Intelligence.</p>
        <p class="text-sm text-gray-500">Redirecting to home page...</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { emailNotificationService } from '../services/emailNotificationService'

const router = useRouter()

// State
const isSubscribing = ref(false)
const subscriptionError = ref('')
const subscriptionSuccess = ref(false)

// Subscription form
const subscriptionForm = reactive({
  username: '',
  email: '',
  fullName: '',
  organization: '',
  interests: [] as string[],
  role: '',
  agreeToTerms: false
})

// Handle subscription
const handleSubscription = async () => {
  subscriptionError.value = ''
  isSubscribing.value = true
  
  try {
    // Simulate API call to save subscription
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    // Add subscriber using email notification service
    const newSubscriber = emailNotificationService.addSubscriber({
      username: subscriptionForm.username,
      email: subscriptionForm.email,
      fullName: subscriptionForm.fullName,
      organization: subscriptionForm.organization,
      interests: subscriptionForm.interests,
      role: subscriptionForm.role
    })
    
    console.log('New subscriber added:', newSubscriber)
    console.log('All subscribers:', emailNotificationService.getSubscribers())
    
    subscriptionSuccess.value = true
    
    // Redirect to home page after successful subscription
    setTimeout(() => {
      router.push('/')
    }, 2000)
    
  } catch (error) {
    subscriptionError.value = 'Failed to subscribe. Please try again.'
  } finally {
    isSubscribing.value = false
  }
}

useHead({
  title: 'Subscribe - Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Subscribe to Stargate.ci to receive the latest news and updates about Stargate Project and Cristal Intelligence.'
    }
  ]
})
</script>

<style scoped>
.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-8 hover:border-primary-500/50 transition-all duration-300;
}

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-300;
}

.btn-secondary {
  @apply bg-gradient-to-r from-secondary-500 to-primary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-secondary-600 hover:to-primary-600 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
