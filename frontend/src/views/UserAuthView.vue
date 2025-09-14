<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="relative overflow-hidden">
      <!-- Background Effects -->
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <!-- Header -->
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
          <RouterLink to="/" class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">S</span>
            </div>
            <span class="text-xl font-bold gradient-text">Stargate.ci</span>
          </RouterLink>
          
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
            <span class="gradient-text">Join Stargate.ci</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-2xl mx-auto">
            Access your personalized dashboard, track your learning progress, and connect with the Stargate community.
          </p>
        </div>

        <!-- Auth Component -->
        <div class="flex justify-center">
          <UserLogin 
            v-if="authMode === 'login'"
            @switch-to-registration="authMode = 'register'"
            @login-success="handleLoginSuccess"
          />
          <UserRegistration 
            v-else
            @switch-to-login="authMode = 'login'"
            @registration-success="handleRegistrationSuccess"
          />
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
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import UserLogin from '../components/UserLogin.vue'
import UserRegistration from '../components/UserRegistration.vue'

const router = useRouter()

// Auth mode state
const authMode = ref<'login' | 'register'>('login')

// Handle successful login
const handleLoginSuccess = (user: any) => {
  console.log('Login successful:', user)
  // Redirect to dashboard
  router.push('/dashboard')
}

// Handle successful registration
const handleRegistrationSuccess = (user: any) => {
  console.log('Registration successful:', user)
  // Could redirect to dashboard or show success message
}

useHead({
  title: 'User Authentication - Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Sign in or create an account to access your personalized Stargate.ci dashboard and learning resources.'
    }
  ]
})
</script>

<style scoped>
.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
