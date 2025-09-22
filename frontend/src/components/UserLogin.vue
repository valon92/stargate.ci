<template>
  <div class="max-w-md mx-auto">
    <div class="card">
      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold mb-2">
          <span class="gradient-text">Welcome Back</span>
        </h2>
        <p class="text-gray-400">Sign in to your Stargate.ci account</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
            Email Address *
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
            placeholder="Enter your email address"
          />
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
            Password *
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
            placeholder="Enter your password"
          />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input
              v-model="form.rememberMe"
              type="checkbox"
              class="w-4 h-4 text-primary-600 bg-gray-800 border-gray-700 rounded focus:ring-primary-500 focus:ring-2"
            />
            <span class="ml-2 text-sm text-gray-300">Remember me</span>
          </label>
          <button type="button" class="text-sm text-primary-400 hover:text-primary-300">
            Forgot password?
          </button>
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
          <p class="text-red-400 text-sm">{{ errorMessage }}</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="isSubmitting"
          class="w-full btn-primary py-3 text-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="isSubmitting" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Signing In...
          </span>
          <span v-else>Sign In</span>
        </button>
      </form>

      <!-- Demo Credentials -->
      <div class="mt-6 p-4 bg-blue-900/20 border border-blue-500/50 rounded-lg">
        <h3 class="text-blue-400 font-medium mb-2">Demo Credentials:</h3>
        <p class="text-blue-300 text-sm mb-1">Username: demo</p>
        <p class="text-blue-300 text-sm">Password: demo123</p>
      </div>

      <!-- Registration Link -->
      <div class="mt-6 text-center">
        <p class="text-gray-400">
          Don't have an account?
          <button @click="$emit('switch-to-registration')" class="text-primary-400 hover:text-primary-300 font-medium">
            Create one here
          </button>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useAuthStore } from '@/stores/auth'

// Emits
const emit = defineEmits<{
  'switch-to-registration': []
  'login-success': [user: any]
}>()

// Auth store
const authStore = useAuthStore()

// Form data
const form = reactive({
  email: '',
  password: '',
  rememberMe: false
})

// State
const isSubmitting = ref(false)
const errorMessage = ref('')

// Handle form submission
const handleSubmit = async () => {
  errorMessage.value = ''
  isSubmitting.value = true
  
  try {
    const result = await authStore.login({
      email: form.email,
      password: form.password
    })
    
    if (result.success && result.data?.user) {
      emit('login-success', result.data.user)
    } else {
      errorMessage.value = result.message || 'Login failed. Please try again.'
    }
  } catch (error) {
    errorMessage.value = 'An unexpected error occurred. Please try again.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-8 hover:border-primary-500/50 transition-all duration-300;
}

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
