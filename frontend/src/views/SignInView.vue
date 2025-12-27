<template>
  <div class="min-h-screen bg-gray-900 dark:bg-gray-900 bg-white py-12">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold gradient-text mb-4">
          Sign In
        </h1>
        <p class="text-xl text-gray-700 dark:text-gray-300">
          Sign in to your Stargate.ci account
        </p>
      </div>

      <!-- Sign In Form -->
      <div class="bg-gray-800 dark:bg-gray-800 bg-white rounded-lg p-8 border border-gray-700 dark:border-gray-700 border-gray-200">
        <form @submit.prevent="handleSignIn" class="space-y-6">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Email Address *
            </label>
            <input
              type="email"
              id="email"
              v-model="form.email"
              required
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter your email address"
            />
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Password *
            </label>
            <input
              type="password"
              id="password"
              v-model="form.password"
              required
              class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter your password"
            />
          </div>

          <!-- Remember Me -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember"
                type="checkbox"
                v-model="form.remember"
                class="h-4 w-4 text-primary-600 dark:text-primary-600 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-300 border-gray-300 rounded"
              />
              <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                Remember me
              </label>
            </div>
            <a href="#" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
              Forgot password?
            </a>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isSubmitting"
            class="w-full bg-black dark:bg-black bg-gray-900 text-white dark:text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-900 dark:hover:bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-offset-white disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
          >
            {{ isSubmitting ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <!-- Success Message -->
        <div v-if="showSuccess" class="mt-6 p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
          <p class="text-green-200 text-center">
            ✅ Successfully signed in! Welcome back to Stargate.ci.
          </p>
        </div>

        <!-- Error Message -->
        <div v-if="showError" class="mt-6 p-4 bg-red-900/20 border border-red-500/30 rounded-lg">
          <p class="text-red-200 text-center">
            ❌ {{ errorMessage }}
          </p>
        </div>
      </div>

      <!-- Sign Up Link -->
      <div class="mt-8 text-center">
        <p class="text-gray-600 dark:text-gray-400">
          Don't have an account? 
          <RouterLink to="/signup" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 underline">
            Sign up for Stargate.ci
          </RouterLink>
        </p>
      </div>

      <!-- Benefits -->
      <div class="mt-12 grid grid-cols-1 gap-6">
        <div class="text-center">
          <div class="w-12 h-12 bg-primary-500/20 rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-black dark:text-white mb-2">Access Your Content</h3>
          <p class="text-gray-600 dark:text-gray-400">Continue where you left off with your personalized experience</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useHead } from '@vueuse/head'
import { useRouter } from 'vue-router'
import { authService, type LoginRequest } from '@/services/authService'
import { useAuthStore } from '@/stores/auth'

// Router
const router = useRouter()

// Use authentication store
const authStore = useAuthStore()

// Set page title
useHead({
  title: 'Sign In - Stargate.ci',
  meta: [
    { name: 'description', content: 'Sign in to your Stargate.ci account to access exclusive content and features' }
  ]
})

// Form data
const form = ref({
  email: '',
  password: '',
  remember: false
})

// UI state
const isSubmitting = ref(false)
const showSuccess = ref(false)
const showError = ref(false)
const errorMessage = ref('')

// Handle form submission
const handleSignIn = async () => {
  isSubmitting.value = true
  showSuccess.value = false
  showError.value = false

  try {
    // Validate form
    if (!form.value.email || !form.value.password) {
      throw new Error('Email and password are required')
    }

    // Authenticate with backend
    const loginData: LoginRequest = {
      email: form.value.email,
      password: form.value.password
    }

    const response = await authService.login(loginData)
    
    if (response.success) {
      // Merge subscriber_id into user object if provided
      const userData = {
        ...response.data.user,
        subscriber_id: response.data.subscriber_id || response.data.user.subscriber_id
      }
      
      // Store authentication data in store
      authStore.login(userData, response.data.token)
      
      // Show success message
      showSuccess.value = true
      showToast('Successfully signed in! Welcome back.', 'success')
      
      // Reset form
      form.value = {
        email: '',
        password: '',
        remember: false
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
          // Redirect to profile page after successful login
          router.push('/profile')
        }
      }, 1500)
    } else {
      throw new Error(response.message || 'Login failed')
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
  @apply text-black dark:text-white;
}
</style>
