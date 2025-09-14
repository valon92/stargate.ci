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
        <div class="text-center mb-12">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">
            <span class="gradient-text">Welcome to Stargate.ci</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-2xl mx-auto">
            Access your personalized dashboard, track your learning progress, and connect with the Stargate community.
          </p>
        </div>

        <!-- Login Options -->
        <div class="max-w-4xl mx-auto">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- User Login -->
            <div class="card">
              <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span class="text-2xl">👤</span>
                </div>
                <h2 class="text-2xl font-bold mb-2">
                  <span class="gradient-text">User Access</span>
                </h2>
                <p class="text-gray-400">Access your personal dashboard and learning resources</p>
              </div>

              <form @submit.prevent="handleUserLogin" class="space-y-4">
                <div>
                  <label for="userUsername" class="block text-sm font-medium text-gray-300 mb-2">
                    Username or Email
                  </label>
                  <input
                    id="userUsername"
                    v-model="userForm.username"
                    type="text"
                    required
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
                    placeholder="Enter your username or email"
                  />
                </div>

                <div>
                  <label for="userPassword" class="block text-sm font-medium text-gray-300 mb-2">
                    Password
                  </label>
                  <input
                    id="userPassword"
                    v-model="userForm.password"
                    type="password"
                    required
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
                    placeholder="Enter your password"
                  />
                </div>

                <div v-if="userError" class="p-3 bg-red-900/20 border border-red-500/50 rounded-lg">
                  <p class="text-red-400 text-sm">{{ userError }}</p>
                </div>

                <button
                  type="submit"
                  :disabled="isUserSubmitting"
                  class="w-full btn-primary py-3 text-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="isUserSubmitting" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing In...
                  </span>
                  <span v-else>Sign In as User</span>
                </button>
              </form>

              <div class="mt-4 text-center">
                <p class="text-gray-400 text-sm">
                  Don't have an account?
                  <button @click="showUserRegistration = true" class="text-primary-400 hover:text-primary-300 font-medium">
                    Create one here
                  </button>
                </p>
              </div>

              <!-- Demo Credentials -->
              <div class="mt-4 p-3 bg-blue-900/20 border border-blue-500/50 rounded-lg">
                <h3 class="text-blue-400 font-medium mb-1 text-sm">Demo Credentials:</h3>
                <p class="text-blue-300 text-xs mb-1">Username: demo</p>
                <p class="text-blue-300 text-xs">Password: demo123</p>
              </div>
            </div>

            <!-- Admin Login -->
            <div class="card">
              <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span class="text-2xl">⚙️</span>
                </div>
                <h2 class="text-2xl font-bold mb-2">
                  <span class="gradient-text">Admin Access</span>
                </h2>
                <p class="text-gray-400">Access the admin panel for platform management</p>
              </div>

              <form @submit.prevent="handleAdminLogin" class="space-y-4">
                <div>
                  <label for="adminUsername" class="block text-sm font-medium text-gray-300 mb-2">
                    Admin Username
                  </label>
                  <input
                    id="adminUsername"
                    v-model="adminForm.username"
                    type="text"
                    required
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-transparent text-white placeholder-gray-400"
                    placeholder="Enter admin username"
                  />
                </div>

                <div>
                  <label for="adminPassword" class="block text-sm font-medium text-gray-300 mb-2">
                    Admin Password
                  </label>
                  <input
                    id="adminPassword"
                    v-model="adminForm.password"
                    type="password"
                    required
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-transparent text-white placeholder-gray-400"
                    placeholder="Enter admin password"
                  />
                </div>

                <div v-if="adminError" class="p-3 bg-red-900/20 border border-red-500/50 rounded-lg">
                  <p class="text-red-400 text-sm">{{ adminError }}</p>
                </div>

                <button
                  type="submit"
                  :disabled="isAdminSubmitting"
                  class="w-full btn-secondary py-3 text-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="isAdminSubmitting" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Signing In...
                  </span>
                  <span v-else>Sign In as Admin</span>
                </button>
              </form>

              <!-- Admin Credentials -->
              <div class="mt-4 p-3 bg-purple-900/20 border border-purple-500/50 rounded-lg">
                <h3 class="text-purple-400 font-medium mb-1 text-sm">Admin Credentials:</h3>
                <p class="text-purple-300 text-xs mb-1">Username: admin</p>
                <p class="text-purple-300 text-xs">Password: stargate2025!</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Features -->
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">📊</span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Personal Dashboard</h3>
            <p class="text-gray-400 text-sm">Track your progress and access personalized content</p>
          </div>
          
          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🎓</span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Learning Paths</h3>
            <p class="text-gray-400 text-sm">Access interactive learning experiences and assessments</p>
          </div>
          
          <div class="text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🤝</span>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Community Access</h3>
            <p class="text-gray-400 text-sm">Connect with other Stargate enthusiasts and experts</p>
          </div>
        </div>
      </div>
    </div>

    <!-- User Registration Modal -->
    <div v-if="showUserRegistration" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold gradient-text">Create Account</h3>
            <button @click="showUserRegistration = false" class="text-gray-400 hover:text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <UserRegistration 
            @switch-to-login="showUserRegistration = false"
            @registration-success="handleRegistrationSuccess"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { authService, type LoginCredentials } from '../services/authService'
import UserRegistration from '../components/UserRegistration.vue'

const router = useRouter()

// State
const showUserRegistration = ref(false)
const isUserSubmitting = ref(false)
const isAdminSubmitting = ref(false)
const userError = ref('')
const adminError = ref('')

// Forms
const userForm = reactive<LoginCredentials>({
  username: '',
  password: ''
})

const adminForm = reactive<LoginCredentials>({
  username: '',
  password: ''
})

// Handle user login
const handleUserLogin = async () => {
  userError.value = ''
  isUserSubmitting.value = true
  
  try {
    const result = await authService.loginUser(userForm)
    
    if (result.success && result.user) {
      router.push('/dashboard')
    } else {
      userError.value = result.error || 'Login failed. Please try again.'
    }
  } catch (error) {
    userError.value = 'An unexpected error occurred. Please try again.'
  } finally {
    isUserSubmitting.value = false
  }
}

// Handle admin login
const handleAdminLogin = async () => {
  adminError.value = ''
  isAdminSubmitting.value = true
  
  try {
    const result = await authService.login(adminForm)
    
    if (result.success && result.user) {
      router.push('/admin')
    } else {
      adminError.value = result.error || 'Login failed. Please try again.'
    }
  } catch (error) {
    adminError.value = 'An unexpected error occurred. Please try again.'
  } finally {
    isAdminSubmitting.value = false
  }
}

// Handle successful registration
const handleRegistrationSuccess = (user: any) => {
  showUserRegistration.value = false
  router.push('/dashboard')
}

useHead({
  title: 'Sign In - Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Sign in to your Stargate.ci account or access the admin panel. Choose between user access or admin access.'
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
