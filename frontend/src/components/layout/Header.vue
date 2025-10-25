<template>
  <header class="sticky top-0 z-50 bg-gray-900/90 backdrop-blur-sm border-b border-gray-700/30 shadow-sm">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-14">
        <!-- Logo Section - Optimized -->
        <div class="flex items-center flex-shrink-0">
          <RouterLink to="/" class="flex items-center space-x-2 group">
            <div class="w-7 h-7 lg:w-8 lg:h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
              <span class="text-white font-bold text-sm lg:text-base">S</span>
            </div>
            <div class="block">
              <span class="text-base sm:text-lg lg:text-xl font-bold gradient-text">Stargate.ci</span>
              <div class="text-xs text-gray-400 -mt-0.5">Cristal Intelligence</div>
            </div>
          </RouterLink>
        </div>

        <!-- Search Bar - Desktop -->
        <div class="hidden lg:block flex-1 max-w-md mx-8">
          <SearchBox
            ref="searchBoxRef"
            :placeholder="'Search articles, videos, FAQs...'"
            @search="handleSearch"
            @suggestion-select="handleSuggestionSelect"
          />
        </div>

        <!-- Engagement Stats - Removed from desktop -->

        <!-- Desktop Navigation - Optimized -->
        <div class="hidden lg:block">
          <div class="flex justify-center">
            <div class="flex items-center space-x-0.5">
              <!-- Primary Navigation -->
              <div class="flex items-center space-x-0.5">
                <RouterLink
                  v-for="item in primaryNavigation"
                  :key="item.name"
                  :to="item.href"
                  class="text-gray-300 hover:text-white px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40"
                  :class="{ 
                    'text-white bg-primary-500/20 border border-primary-500/30': $route.path === item.href 
                  }"
                >
                  {{ item.name }}
                </RouterLink>
              </div>
              
              <!-- Separator (only if there are items in both sections) -->
              <div v-if="primaryNavigation.length > 0 && secondaryNavigation.length > 0" class="w-px h-4 bg-gray-600 mx-1.5"></div>
              
              <!-- Secondary Navigation -->
              <div class="flex items-center space-x-0.5">
                <RouterLink
                  v-for="item in secondaryNavigation"
                  :key="item.name"
                  :to="item.href"
                  class="text-gray-400 hover:text-white px-2.5 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40"
                  :class="{ 
                    'text-white bg-gray-800/40': $route.path === item.href 
                  }"
                >
                  {{ item.name }}
                </RouterLink>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Section - Optimized -->
        <div class="flex items-center space-x-2">
          <!-- Notification Center - Removed -->
          
          <!-- CTA Buttons - Desktop Subscribe/Login -->
          <div class="hidden md:flex items-center space-x-2">
            <template v-if="!isSubscribed">
              <RouterLink
                to="/signin"
                class="text-gray-300 hover:text-white px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40"
              >
                Sign In
              </RouterLink>
              <RouterLink
                to="/signup"
                class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-150 shadow-md hover:shadow-lg pointer-events-auto relative z-10"
              >
                Sign Up
              </RouterLink>
            </template>
            <div v-else class="flex items-center gap-2">
              <span class="text-sm text-gray-300">
                Welcome, {{ currentUser?.name || 'User' }}
              </span>
              <button
                @click="unsubscribe"
                class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:from-gray-700 hover:to-gray-800 transition-all duration-150 shadow-md hover:shadow-lg pointer-events-auto relative z-10 flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                </svg>
                Logout
              </button>
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="lg:hidden">
            <button
              @click="toggleMenu"
              class="text-gray-300 hover:text-white p-1.5 rounded-md hover:bg-gray-800/40 transition-colors duration-150"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation - Optimized -->
      <div v-if="isMenuOpen" class="lg:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-700/30">
          <!-- Mobile Search Bar -->
          <div class="px-2 py-2">
            <SearchBox
              ref="mobileSearchBoxRef"
              :placeholder="'Search articles, videos, FAQs...'"
              @search="handleSearch"
              @suggestion-select="handleSuggestionSelect"
            />
          </div>

          <!-- Primary Navigation Mobile -->
          <div v-if="primaryNavigation.length > 0" class="space-y-0.5">
            <RouterLink
              v-for="item in primaryNavigation"
              :key="item.name"
              :to="item.href"
              class="text-gray-300 hover:text-white block px-2 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40"
              :class="{ 'text-white bg-primary-500/20': $route.path === item.href }"
              @click="closeMenu"
            >
              {{ item.name }}
            </RouterLink>
          </div>

          <!-- Secondary Navigation Mobile -->
          <div class="space-y-0.5" :class="{ 'pt-1': primaryNavigation.length > 0 }">
            <RouterLink
              v-for="item in secondaryNavigation"
              :key="item.name"
              :to="item.href"
              class="text-gray-400 hover:text-white block px-2 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40"
              :class="{ 'text-white bg-gray-800/40': $route.path === item.href }"
              @click="closeMenu"
            >
              {{ item.name }}
            </RouterLink>
            
            <!-- Removed community link as it's unnecessary for educational platform -->
          </div>

          <!-- Mobile CTA - Compact -->
          <div class="pt-2 border-t border-gray-700/30 space-y-1">
            <template v-if="!isSubscribed">
              <RouterLink
                to="/signin"
                class="text-gray-300 hover:text-white block px-2 py-2 rounded-md text-sm font-medium text-center transition-colors duration-150 hover:bg-gray-800/40"
                @click="closeMenu"
              >
                Sign In
              </RouterLink>
              <RouterLink
                to="/signup"
                class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white block px-2 py-2 rounded-md text-sm font-medium text-center hover:from-primary-600 hover:to-secondary-600 transition-all duration-150"
                @click="closeMenu"
              >
                Sign Up
              </RouterLink>
            </template>
            <button
              v-else
              @click="unsubscribe"
              class="bg-gradient-to-r from-gray-600 to-gray-700 text-white block px-2 py-2 rounded-md text-sm font-medium text-center hover:from-gray-700 hover:to-gray-800 transition-all duration-150 w-full flex items-center justify-center gap-2"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
              </svg>
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import SearchBox from '../SearchBox.vue'

const route = useRoute()
const router = useRouter()

// Use authentication store
const authStore = useAuthStore()

// Performance optimized state
const isMenuOpen = ref(false)

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}

const closeMenu = () => {
  isMenuOpen.value = false
}

// Memoized navigation - computed for performance
const primaryNavigation = computed(() => [
  { name: 'Home', href: '/' },
])

const secondaryNavigation = computed(() => [
  { name: 'About', href: '/about' },
  { name: 'Events', href: '/events' },
  { name: 'News', href: '/news' },
  { name: 'FAQ', href: '/faq' },
  { name: 'Contact', href: '/contact' },
])

// Subscription status - reactive
const subscriptionStatus = ref(false)

const isSubscribed = computed(() => {
  return authStore.isAuthenticated
})

const currentUser = computed(() => {
  return authStore.currentUser
})

// Update subscription status
const updateSubscriptionStatus = () => {
  subscriptionStatus.value = authStore.isAuthenticated
}


// Search handler
const handleSearch = (query: string) => {
  if (query.trim()) {
    // Close mobile menu if open
    closeMenu()
    // Navigate to search page with query
    router.push(`/search?q=${encodeURIComponent(query.trim())}`)
  }
}

// Handle suggestion selection
const handleSuggestionSelect = (suggestion: string) => {
  handleSearch(suggestion)
}

// Logout functionality
const unsubscribe = async () => {
  if (confirm('Are you sure you want to logout from Stargate.ci?')) {
    await authStore.logout()
  }
}

// Close mobile menu on route change
watch(() => route.path, () => {
  closeMenu()
})

// Watch for auth store changes
watch(() => authStore.isAuthenticated, (newValue) => {
  updateSubscriptionStatus()
})

// Watch for localStorage changes to update subscription status
// This function is no longer needed as we use the auth store

// Initialize on mount
onMounted(() => {
  // Set English as default language
  document.documentElement.setAttribute('dir', 'ltr')
  
  // Initialize auth store
  authStore.initialize()
  
  // Initialize subscription status
  updateSubscriptionStatus()
  
  // Listen for storage events (when localStorage changes in other tabs)
  window.addEventListener('storage', () => {
    updateSubscriptionStatus()
  })
  
  // Listen for custom events (when localStorage changes in same tab)
  window.addEventListener('auth-changed', () => {
    updateSubscriptionStatus()
  })
  document.documentElement.setAttribute('lang', 'en')
})

// Cleanup event listeners on unmount
onUnmounted(() => {
  window.removeEventListener('auth-changed', updateSubscriptionStatus)
  window.removeEventListener('storage', updateSubscriptionStatus)
})
</script>
