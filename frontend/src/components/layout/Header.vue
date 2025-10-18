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
          <div class="relative">
            <input
              type="text"
              placeholder="Search articles, FAQs..."
              class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              @keyup.enter="handleSearch"
            />
          </div>
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
          
          <!-- CTA Buttons - Desktop Subscribe -->
          <div class="hidden md:flex items-center space-x-2">
            <RouterLink
              v-if="!isSubscribed"
              to="/subscribe"
              class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-150 shadow-md hover:shadow-lg pointer-events-auto relative z-10"
            >
              Subscribe
            </RouterLink>
            <button
              v-else
              @click="unsubscribe"
              class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:from-gray-700 hover:to-gray-800 transition-all duration-150 shadow-md hover:shadow-lg pointer-events-auto relative z-10 flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 4H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-6 2.5c2.49 0 4.5 2.01 4.5 4.5S14.49 15.5 12 15.5s-4.5-2.01-4.5-4.5S9.51 6.5 12 6.5zM12 17c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Subscribed
            </button>
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
            <input
              type="text"
              placeholder="Search..."
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm"
              @keyup.enter="handleSearch"
            />
          </div>

          <!-- Primary Navigation Mobile -->
          <div v-if="primaryNavigation.length > 0" class="space-y-0.5">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 py-1">Main</div>
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
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 py-1">Resources</div>
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
            <RouterLink
              v-if="!isSubscribed"
              to="/subscribe"
              class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white block px-2 py-2 rounded-md text-sm font-medium text-center hover:from-primary-600 hover:to-secondary-600 transition-all duration-150"
              @click="closeMenu"
            >
              Subscribe
            </RouterLink>
            <button
              v-else
              @click="unsubscribe"
              class="bg-gradient-to-r from-gray-600 to-gray-700 text-white block px-2 py-2 rounded-md text-sm font-medium text-center hover:from-gray-700 hover:to-gray-800 transition-all duration-150 w-full flex items-center justify-center gap-2"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18 4H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-6 2.5c2.49 0 4.5 2.01 4.5 4.5S14.49 15.5 12 15.5s-4.5-2.01-4.5-4.5S9.51 6.5 12 6.5zM12 17c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
              </svg>
              Subscribed
            </button>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

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
  return subscriptionStatus.value
})

const currentUser = computed(() => {
  const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
  return subscribers.length > 0 ? subscribers[0] : null
})

// Update subscription status
const updateSubscriptionStatus = () => {
  const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
  subscriptionStatus.value = subscribers.length > 0
}


// Search handler
const handleSearch = (event: Event) => {
  const target = event.target as HTMLInputElement
  const query = target.value.trim()
  if (query) {
    // Close mobile menu if open
    closeMenu()
    // Simple search - could be enhanced later
    console.log('Search query:', query)
  }
}

// Unsubscribe functionality
const unsubscribe = () => {
  if (confirm('Are you sure you want to unsubscribe from Stargate.ci?')) {
    // Remove subscriber from localStorage
    localStorage.removeItem('stargate_subscribers')
    
    // Refresh the page to update UI
    window.location.reload()
  }
}

// Close mobile menu on route change
watch(() => route.path, () => {
  closeMenu()
})

// Watch for localStorage changes to update subscription status
const refreshSubscriptionStatus = () => {
  // Force reactivity update by accessing localStorage
  const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
  return subscribers.length > 0
}

// Initialize on mount
onMounted(() => {
  // Set English as default language
  document.documentElement.setAttribute('dir', 'ltr')
  
  // Initialize subscription status
  updateSubscriptionStatus()
  
  // Listen for storage events (when localStorage changes in other tabs)
  window.addEventListener('storage', () => {
    updateSubscriptionStatus()
  })
  
  // Listen for custom events (when localStorage changes in same tab)
  window.addEventListener('subscription-changed', () => {
    updateSubscriptionStatus()
  })
  document.documentElement.setAttribute('lang', 'en')
})
</script>
