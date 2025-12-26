<template>
  <div class="min-h-screen bg-gray-900 dark:bg-gray-900 bg-white py-12">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold gradient-text mb-4">
          Welcome Back
        </h1>
        <p class="text-xl text-gray-300 dark:text-gray-300 text-gray-700">
          Log in to your Stargate.ci account
        </p>
      </div>

      <!-- Login Form -->
      <div class="bg-gray-800 dark:bg-gray-800 bg-white rounded-lg p-8 border border-gray-700 dark:border-gray-700 border-gray-200">
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-300 dark:text-gray-300 text-gray-700 mb-2">
              Email Address *
            </label>
            <input
              type="email"
              id="email"
              v-model="form.email"
              required
              class="w-full px-4 py-3 bg-gray-700 dark:bg-gray-700 bg-white border border-gray-600 dark:border-gray-600 border-gray-300 rounded-lg text-white dark:text-white text-gray-900 placeholder-gray-400 dark:placeholder-gray-400 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter your email address"
            />
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-300 dark:text-gray-300 text-gray-700 mb-2">
              Password *
            </label>
            <input
              type="password"
              id="password"
              v-model="form.password"
              required
              class="w-full px-4 py-3 bg-gray-700 dark:bg-gray-700 bg-white border border-gray-600 dark:border-gray-600 border-gray-300 rounded-lg text-white dark:text-white text-gray-900 placeholder-gray-400 dark:placeholder-gray-400 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter your password"
            />
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isSubmitting"
            class="w-full bg-black dark:bg-black bg-gray-900 text-white dark:text-white py-3 px-6 rounded-lg font-medium hover:bg-gray-900 dark:hover:bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-offset-white disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
          >
            {{ isSubmitting ? 'Logging in...' : 'Log In' }}
          </button>
        </form>

        <!-- Success Message -->
        <div v-if="showSuccess" class="mt-6 p-4 bg-green-900/20 border border-green-500/30 rounded-lg">
          <p class="text-green-200 text-center">
            ✅ Successfully logged in! Welcome back to Stargate.ci.
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
        <p class="text-gray-400 dark:text-gray-400 text-gray-600">
          Don't have an account? 
          <RouterLink to="/subscribe" class="text-primary-400 dark:text-primary-400 text-primary-600 hover:text-primary-300 dark:hover:text-primary-300 hover:text-primary-700 underline">
            Subscribe to Stargate.ci
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
          <h3 class="text-lg font-semibold text-white mb-2">Access Your Content</h3>
          <p class="text-gray-400">Continue where you left off with your personalized experience</p>
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
  title: 'Login - Stargate.ci',
  meta: [
    { name: 'description', content: 'Log in to your Stargate.ci account to access exclusive content and features' }
  ]
})

// Form data
const form = ref({
  email: '',
  password: ''
})

// UI state
const isSubmitting = ref(false)
const showSuccess = ref(false)
const showError = ref(false)
const errorMessage = ref('')

// Handle form submission
const handleLogin = async () => {
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
      // Store authentication data in store
      authStore.login(response.data.user, response.data.token)
      
      // Show success message
      showSuccess.value = true
      showToast('Successfully logged in! Welcome back.', 'success')
      
      // Reset form
      form.value = {
        email: '',
        password: ''
      }

      // Redirect to home page after 2 seconds
      setTimeout(() => {
        router.push('/')
      }, 2000)
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

// Check if user is already logged in
onMounted(() => {
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
