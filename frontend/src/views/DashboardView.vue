<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">
            <span class="gradient-text">Your Stargate Dashboard</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
            Track your progress, discover opportunities, and stay connected with the Stargate ecosystem.
          </p>
          
          <!-- User Info & Logout -->
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <div class="flex items-center gap-3 bg-gray-800/50 backdrop-blur-sm rounded-lg px-4 py-2 border border-gray-700/50">
              <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-sm">{{ userInitials }}</span>
              </div>
              <div class="text-left">
                <p class="text-white font-medium">{{ currentUser?.username }}</p>
                <p class="text-gray-400 text-sm">{{ currentUser?.email }}</p>
              </div>
            </div>
            
            <button 
              @click="handleLogout"
              class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200 font-medium"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              Logout
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Dashboard Component -->
    <section class="py-8">
      <UserDashboard />
    </section>

    <!-- Security Settings Section -->
    <section class="py-8 bg-gray-800/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
          <h2 class="text-2xl font-bold text-white mb-2">Security Settings</h2>
          <p class="text-gray-400">Manage your account security and authentication preferences</p>
        </div>
        <SecuritySettings />
      </div>
    </section>

    <!-- Live Updates Section -->
    <section class="py-8 bg-gray-800/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
          <h2 class="text-2xl font-bold text-white mb-2">Live Updates</h2>
          <p class="text-gray-400">Stay connected with real-time platform activity</p>
        </div>
        <LiveUpdates />
      </div>
    </section>

    <!-- Dashboard Features -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Dashboard Features</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Your personalized command center for everything Stargate-related.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📊</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Progress Tracking</h3>
            <p class="text-gray-400">
              Monitor your learning progress, assessment scores, and achievement milestones in real-time.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🎯</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Personalized Recommendations</h3>
            <p class="text-gray-400">
              Get tailored suggestions for learning paths, partnerships, and next steps based on your profile.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📈</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Analytics & Insights</h3>
            <p class="text-gray-400">
              View detailed analytics about your engagement, learning patterns, and growth trajectory.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🏆</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Achievement System</h3>
            <p class="text-gray-400">
              Earn badges, certificates, and recognition as you progress through your Stargate journey.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">📰</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Latest News</h3>
            <p class="text-gray-400">
              Stay updated with the latest Stargate developments, announcements, and industry news.
            </p>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🎯</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Goal Setting</h3>
            <p class="text-gray-400">
              Set and track personal learning goals, milestones, and objectives for your Stargate journey.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Quick Access -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Quick Access</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Jump directly to the tools and resources you need most.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <RouterLink 
            to="/assessment" 
            class="card text-center group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">📋</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Readiness Assessment</h3>
            <p class="text-gray-400 text-sm">Evaluate your Stargate readiness</p>
          </RouterLink>

          <RouterLink 
            to="/learning" 
            class="card text-center group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🎓</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Learning Paths</h3>
            <p class="text-gray-400 text-sm">Interactive learning experiences</p>
          </RouterLink>

          <RouterLink 
            to="/partnership" 
            class="card text-center group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">🤝</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Partnerships</h3>
            <p class="text-gray-400 text-sm">Explore collaboration opportunities</p>
          </RouterLink>

          <RouterLink 
            to="/insights" 
            class="card text-center group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-2xl">💡</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Insights</h3>
            <p class="text-gray-400 text-sm">Latest insights and analysis</p>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gray-900/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Ready to Get Started?</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Your dashboard is your gateway to the Stargate ecosystem. Start exploring and building your future with AI infrastructure.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <RouterLink to="/assessment" class="btn-primary text-lg px-8 py-4">
            Take Assessment
          </RouterLink>
          <RouterLink to="/learning" class="btn-secondary text-lg px-8 py-4">
            Start Learning
          </RouterLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { authService, type AdminUser, type User } from '../services/authService'
import UserDashboard from '../components/UserDashboard.vue'
import LiveUpdates from '../components/LiveUpdates.vue'
import SecuritySettings from '../components/SecuritySettings.vue'

const router = useRouter()

// User state
const currentUser = ref<AdminUser | User | null>(null)

// Computed properties
const userInitials = computed(() => {
  if (!currentUser.value) return 'U'
  const username = currentUser.value.username
  return username.charAt(0).toUpperCase()
})

// Logout function
const handleLogout = () => {
  // Show confirmation dialog
  if (confirm('Are you sure you want to logout?')) {
    // Clear authentication (both user and admin)
    authService.logout()
    authService.logoutUser()
    
    // Redirect to home page
    router.push('/')
    
    // Show success message (optional)
    console.log('Logged out successfully')
  }
}

// Initialize user data
onMounted(() => {
  // Check for regular user first, then admin
  currentUser.value = authService.getCurrentUser() || authService.getCurrentAdminUser()
  
  // If no user is found, redirect to auth page
  if (!currentUser.value) {
    router.push('/auth')
  }
})

useHead({
  title: 'User Dashboard - Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Your personalized Stargate dashboard. Track progress, discover opportunities, and stay connected with the Stargate ecosystem.'
    }
  ]
})
</script>
