<template>
  <header class="sticky top-0 z-50 bg-gray-900/90 dark:bg-gray-900/90 bg-white/90 backdrop-blur-sm border-b border-gray-700/30 dark:border-gray-700/30 border-gray-200/30 shadow-sm transition-colors duration-300">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-14">
        <!-- Logo Section - Optimized -->
        <div class="flex items-center flex-shrink-0">
          <RouterLink to="/" class="flex items-center space-x-2 group">
            <div class="w-7 h-7 lg:w-8 lg:h-8 bg-black rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
              <span class="text-white font-bold text-sm lg:text-base">S</span>
            </div>
            <div class="block">
              <span class="text-base sm:text-lg lg:text-xl font-bold text-black dark:text-white">Stargate.ci</span>
              <div class="text-xs text-gray-400 dark:text-gray-400 text-gray-700 -mt-0.5">Cristal Intelligence</div>
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
                  class="text-gray-300 dark:text-gray-300 text-gray-700 hover:text-white dark:hover:text-white hover:text-gray-900 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100"
                  :class="{ 
                    'text-white dark:text-white text-gray-900 bg-primary-500/20 dark:bg-primary-500/20 border border-primary-500/30 dark:border-primary-500/30': $route.path === item.href 
                  }"
                >
                  {{ item.name }}
                </RouterLink>
              </div>
              
              <!-- Separator removed -->
              
              <!-- Secondary Navigation -->
              <div class="flex items-center space-x-0.5">
                <RouterLink
                  v-for="item in secondaryNavigation"
                  :key="item.name"
                  :to="item.href"
                  class="text-gray-400 dark:text-gray-400 text-gray-600 hover:text-white dark:hover:text-white hover:text-gray-900 px-2.5 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100"
                  :class="{ 
                    'text-white dark:text-white text-gray-900 bg-gray-800/40 dark:bg-gray-800/40 bg-gray-100': $route.path === item.href 
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
                @click="saveReturnUrl"
                class="text-gray-300 dark:text-gray-300 text-gray-700 hover:text-white dark:hover:text-white hover:text-gray-900 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100"
              >
                Sign In
              </RouterLink>
              <RouterLink
                to="/signup"
                @click="saveReturnUrl"
                class="bg-black dark:bg-white text-white dark:text-black px-3 py-1.5 rounded-md text-sm font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-150 shadow-md hover:shadow-lg pointer-events-auto relative z-10"
              >
                Sign Up
              </RouterLink>
            </template>
            <div v-else class="flex items-center gap-3">
              <RouterLink
                to="/profile"
                class="text-gray-300 dark:text-gray-300 text-gray-700 hover:text-white dark:hover:text-white hover:text-gray-900 px-3 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100 flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
              </RouterLink>
              <button
                @click="unsubscribe"
                class="bg-black dark:bg-white text-white dark:text-black px-3 py-1.5 rounded-md text-sm font-medium hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-150 shadow-md hover:shadow-lg pointer-events-auto relative z-10 flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
              </button>
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="lg:hidden">
            <button
              @click="toggleMenu"
              class="text-gray-300 dark:text-gray-300 text-gray-700 hover:text-white dark:hover:text-white hover:text-gray-900 p-1.5 rounded-md hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100 transition-colors duration-150"
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
        <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-700/30 dark:border-gray-700/30 border-gray-200/30">
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
              class="text-gray-300 dark:text-gray-300 text-gray-700 hover:text-white dark:hover:text-white hover:text-gray-900 block px-2 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100"
              :class="{ 'text-white dark:text-white text-gray-900 bg-primary-500/20 dark:bg-primary-500/20 bg-primary-100': $route.path === item.href }"
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
              class="text-gray-400 dark:text-gray-400 text-gray-600 hover:text-white dark:hover:text-white hover:text-gray-900 block px-2 py-1.5 rounded-md text-sm font-medium transition-colors duration-150 hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100"
              :class="{ 'text-white dark:text-white text-gray-900 bg-gray-800/40 dark:bg-gray-800/40 bg-gray-100': $route.path === item.href }"
              @click="closeMenu"
            >
              {{ item.name }}
            </RouterLink>
            
            <!-- Removed community link as it's unnecessary for educational platform -->
          </div>

          <!-- Mobile CTA - Compact -->
          <div class="pt-2 border-t border-gray-700/30 dark:border-gray-700/30 border-gray-200/30 space-y-1">
            <template v-if="!isSubscribed">
              <RouterLink
                to="/signin"
                @click="() => { saveReturnUrl(); closeMenu(); }"
                class="text-gray-300 dark:text-gray-300 text-gray-700 hover:text-white dark:hover:text-white hover:text-gray-900 block px-2 py-2 rounded-md text-sm font-medium text-center transition-colors duration-150 hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100"
              >
                Sign In
              </RouterLink>
              <RouterLink
                to="/signup"
                @click="() => { saveReturnUrl(); closeMenu(); }"
                class="bg-black dark:bg-white text-white dark:text-black block px-2 py-2 rounded-md text-sm font-medium text-center hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-150"
              >
                Sign Up
              </RouterLink>
            </template>
            <template v-else>
              <RouterLink
                to="/profile"
                @click="closeMenu"
                class="text-gray-300 dark:text-gray-300 text-gray-700 hover:text-white dark:hover:text-white hover:text-gray-900 block px-2 py-2 rounded-md text-sm font-medium text-center transition-colors duration-150 hover:bg-gray-800/40 dark:hover:bg-gray-800/40 hover:bg-gray-100 flex items-center justify-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profile
              </RouterLink>
              <button
                @click="unsubscribe"
                class="bg-black dark:bg-white text-white dark:text-black block px-2 py-2 rounded-md text-sm font-medium text-center hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-150 w-full flex items-center justify-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
              </button>
            </template>
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

// Save current page URL and scroll position before navigating to sign in/up
const saveReturnUrl = () => {
  sessionStorage.setItem('return_url', window.location.pathname + window.location.search)
  sessionStorage.setItem('return_scroll', window.scrollY.toString())
}

// Memoized navigation - computed for performance
const primaryNavigation = computed(() => [
  { name: 'Home', href: '/' },
])

const secondaryNavigation = computed(() => [
  { name: 'About', href: '/about' },
  { name: 'Events', href: '/events' },
  { name: 'News', href: '/news' },
  { name: 'Community', href: '/community' },
  { name: 'Jobs', href: '/jobs' },
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
