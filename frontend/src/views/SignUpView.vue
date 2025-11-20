<template>
  <div class="min-h-screen bg-gray-900 py-12">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold gradient-text mb-4">
          Sign Up
        </h1>
        <p class="text-xl text-gray-300">
          Create your Stargate.ci account
        </p>
      </div>

      <!-- Sign Up Form -->
      <div class="bg-gray-800 rounded-lg p-8 border border-gray-700">
        <form @submit.prevent="handleSignUp" class="space-y-6">
          <!-- Username -->
          <div>
            <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
              Username *
            </label>
            <input
              type="text"
              id="username"
              v-model="form.username"
              required
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Choose a username"
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

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
              Password *
            </label>
            <input
              type="password"
              id="password"
              v-model="form.password"
              required
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Create a password"
            />
          </div>

          <!-- Confirm Password -->
          <div>
            <label for="confirmPassword" class="block text-sm font-medium text-gray-300 mb-2">
              Confirm Password *
            </label>
            <input
              type="password"
              id="confirmPassword"
              v-model="form.confirmPassword"
              required
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Confirm your password"
            />
          </div>

          <!-- Country -->
          <div>
            <label for="country" class="block text-sm font-medium text-gray-300 mb-2">
              Country
            </label>
            <select
              id="country"
              v-model="form.country"
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
              <option value="">Select your country</option>
              <option value="Kosovo">Kosovo</option>
              <option value="Albania">Albania</option>
              <option value="USA">United States</option>
              <option value="UK">United Kingdom</option>
              <option value="Germany">Germany</option>
              <option value="France">France</option>
              <option value="Italy">Italy</option>
              <option value="Spain">Spain</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <!-- Interests -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Interests (Optional)
            </label>
            <div class="grid grid-cols-2 gap-2">
              <label v-for="interest in availableInterests" :key="interest" class="flex items-center">
                <input
                  type="checkbox"
                  :value="interest"
                  v-model="form.interests"
                  class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                />
                <span class="ml-2 text-sm text-gray-300">{{ interest }}</span>
              </label>
            </div>
          </div>

          <!-- Terms and Conditions -->
          <div class="flex items-center">
            <input
              id="terms"
              type="checkbox"
              v-model="form.acceptTerms"
              required
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            />
            <label for="terms" class="ml-2 block text-sm text-gray-300">
              I agree to the 
              <a href="/terms" class="text-primary-400 hover:text-primary-300 underline">Terms of Service</a>
              and 
              <a href="/privacy" class="text-primary-400 hover:text-primary-300 underline">Privacy Policy</a>
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isSubmitting"
            class="w-full bg-black text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
          >
            {{ isSubmitting ? 'Creating account...' : 'Sign Up' }}
          </button>
        </form>

        <!-- Success Message -->
        <div v-if="showSuccess" class="mt-6 p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
          <p class="text-green-200 text-center">
            ✅ Account created successfully! Welcome to Stargate.ci.
          </p>
        </div>

        <!-- Error Message -->
        <div v-if="showError" class="mt-6 p-4 bg-red-900/20 border border-red-500/30 rounded-lg">
          <p class="text-red-200 text-center">
            ❌ {{ errorMessage }}
          </p>
        </div>
      </div>

      <!-- Sign In Link -->
      <div class="mt-8 text-center">
        <p class="text-gray-400">
          Already have an account? 
          <RouterLink to="/signin" class="text-primary-400 hover:text-primary-300 underline">
            Sign in to Stargate.ci
          </RouterLink>
        </p>
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
import { ref, onMounted } from 'vue'
import { useHead } from '@vueuse/head'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { videoApiService } from '@/services/videoApiService'

// Router and Auth
const router = useRouter()
const authStore = useAuthStore()

// Set page title
useHead({
  title: 'Sign Up - Stargate.ci',
  meta: [
    { name: 'description', content: 'Create your Stargate.ci account to access exclusive content and features' }
  ]
})

// Available interests
const availableInterests = [
  'Stargate Project',
  'Cristal Intelligence',
  'AI Research',
  'Machine Learning',
  'Deep Learning',
  'Neural Networks',
  'Computer Vision',
  'Natural Language Processing',
  'Robotics',
  'Quantum Computing',
  'Data Science',
  'Technology'
]

// Form data
const form = ref({
  username: '',
  email: '',
  password: '',
  confirmPassword: '',
  country: '',
  interests: [] as string[],
  acceptTerms: false
})

// UI state
const isSubmitting = ref(false)
const showSuccess = ref(false)
const showError = ref(false)
const errorMessage = ref('')

// Handle form submission
const handleSignUp = async () => {
  isSubmitting.value = true
  showSuccess.value = false
  showError.value = false

  try {
    // Validate form
    if (!form.value.username || !form.value.email || !form.value.password) {
      throw new Error('Username, email, and password are required')
    }

    if (form.value.password !== form.value.confirmPassword) {
      throw new Error('Passwords do not match')
    }

    if (!form.value.acceptTerms) {
      throw new Error('You must accept the Terms of Service and Privacy Policy')
    }

    // Check if subscriber already exists in database
    let existingSubscriber
    try {
      existingSubscriber = await videoApiService.getSubscriberByEmail(form.value.email)
      console.log('SignUp - existingSubscriber response:', existingSubscriber)
    } catch (error) {
      console.log('SignUp - Subscriber not found, will create new one:', (error as any).message)
      existingSubscriber = { success: false, data: null }
    }
    
    if (existingSubscriber.success && existingSubscriber.data) {
      // Subscriber exists, redirect to sign in page
      showToast('Account already exists! Please sign in instead.', 'warning')
      
      // Redirect to sign in page after 2 seconds
      setTimeout(() => {
        router.push('/signin')
      }, 2000)
      
      return // Exit early, don't create new subscriber
      
    } else {
      // Subscriber doesn't exist, create new one
      console.log('Creating new subscriber for email:', form.value.email)
      // Create new subscriber in database
      const subscriberData = {
        username: form.value.username,
        email: form.value.email,
        password: form.value.password, // Will be hashed by Laravel
        first_name: form.value.username,
        last_name: undefined,
        country: form.value.country || undefined,
        profession: undefined,
        company: undefined,
        interests: form.value.interests,
        avatar: form.value.username.charAt(0).toUpperCase(),
        is_active: true,
        email_notifications: true
      }

      const response = await videoApiService.createSubscriber(subscriberData)
      
      if (response.success) {
        const newSubscriber = response.data
        
        // Store subscriber in localStorage for session
        localStorage.setItem('stargate_subscribers', JSON.stringify([newSubscriber]))
        localStorage.setItem('stargate_session_id', `user_${newSubscriber.id}_${Date.now()}`)
        
        // Show success message
        showSuccess.value = true
        showToast('Account created successfully! You are now signed in.', 'success')
        
        // Dispatch custom event to update navbar
        window.dispatchEvent(new CustomEvent('subscription-changed'))
        
        // Reset form
        form.value = {
          username: '',
          email: '',
          password: '',
          confirmPassword: '',
          country: '',
          interests: [],
          acceptTerms: false
        }

        // Redirect to saved URL or default location
        const returnUrl = sessionStorage.getItem('return_url')
        const returnScroll = sessionStorage.getItem('return_scroll')
        const pendingVideoUrl = sessionStorage.getItem('pending_video_url')
        
        // Clean up sessionStorage
        if (returnUrl) sessionStorage.removeItem('return_url')
        if (returnScroll) sessionStorage.removeItem('return_scroll')
        
        setTimeout(() => {
          if (returnUrl) {
            // Navigate to saved URL
            router.push(returnUrl).then(() => {
              // Restore scroll position after navigation
              if (returnScroll) {
                setTimeout(() => {
                  window.scrollTo({ top: parseInt(returnScroll), behavior: 'auto' })
                }, 100)
              }
            })
          } else if (pendingVideoUrl) {
            // Redirect to events page to open video
            router.push('/events')
          } else {
            // Redirect to home page
            router.push('/')
          }
        }, 1500)
        
      } else {
        throw new Error(response.message || 'Failed to create account')
      }
    }

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

// Toast notification function
const showToast = (message: string, type: 'success' | 'error' | 'warning' | 'info' = 'info') => {
  // Simple toast implementation - you can enhance this later
  console.log(`${type.toUpperCase()}: ${message}`)
}

// Check if user is already signed in
onMounted(() => {
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'instant' })
  
  // Initialize auth store
  authStore.initialize()
  
  // Only redirect if user is actually authenticated
  if (authStore.isAuthenticated) {
    // User is already logged in, redirect to home
    showToast('You are already logged in!', 'info')
    setTimeout(() => {
      router.push('/')
    }, 1500)
  }
})
</script>

<style scoped>
.gradient-text {
  color: white;
}
</style>
