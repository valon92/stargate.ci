<template>
  <div class="min-h-screen bg-gray-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <div class="mx-auto h-16 w-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mb-6">
          <span class="text-white font-bold text-2xl">S</span>
        </div>
        <h2 class="text-3xl font-bold gradient-text mb-2">Admin Access</h2>
        <p class="text-gray-400">Sign in to access the Stargate.ci admin panel</p>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" class="mt-8 space-y-6">
        <div class="space-y-4">
          <!-- Username Field -->
          <div>
            <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
              Username
            </label>
            <input
              id="username"
              v-model="credentials.username"
              type="text"
              required
              class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
              placeholder="Enter your username"
              :disabled="loading"
            >
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
              Password
            </label>
            <div class="relative">
              <input
                id="password"
                v-model="credentials.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 pr-12"
                placeholder="Enter your password"
                :disabled="loading"
              >
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-300"
                :disabled="loading"
              >
                <svg v-if="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="bg-red-500/10 border border-red-500/20 rounded-lg p-4">
          <div class="flex items-center">
            <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-red-400 text-sm">{{ error }}</span>
          </div>
        </div>

        <!-- Login Button -->
        <div>
          <button
            type="submit"
            :disabled="loading"
            class="w-full flex justify-center items-center px-4 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white font-medium rounded-lg hover:from-primary-600 hover:to-secondary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-6 p-4 bg-gray-800/50 rounded-lg border border-gray-700/50">
          <h3 class="text-sm font-medium text-gray-300 mb-3">Demo Credentials:</h3>
          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-400">Admin:</span>
              <span class="text-gray-300">admin / stargate2025!</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Super Admin:</span>
              <span class="text-gray-300">superadmin / cristal2025!</span>
            </div>
          </div>
        </div>

        <!-- Back to Site -->
        <div class="text-center">
          <RouterLink to="/" class="text-primary-400 hover:text-primary-300 text-sm font-medium">
            ← Back to Stargate.ci
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { authService, type LoginCredentials } from '../services/authService'

const router = useRouter()

// Reactive data
const credentials = ref<LoginCredentials>({
  username: '',
  password: ''
})
const loading = ref(false)
const error = ref('')
const showPassword = ref(false)

// Methods
const handleLogin = async () => {
  if (loading.value) return

  loading.value = true
  error.value = ''

  try {
    const result = await authService.login(credentials.value)
    
    if (result.success && result.user) {
      // Redirect to admin panel
      router.push('/admin')
    } else {
      error.value = result.error || 'Login failed'
    }
  } catch (err) {
    error.value = 'An unexpected error occurred'
  } finally {
    loading.value = false
  }
}

// Check if already authenticated
onMounted(() => {
  if (authService.isAuthenticated()) {
    router.push('/admin')
  }
})
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>
