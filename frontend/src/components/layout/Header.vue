<template>
  <header ref="headerRef" class="sticky top-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm transition-all duration-300">
    <nav class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
      <div class="flex justify-between items-center h-16 lg:h-14">
        <!-- Logo Section -->
        <div class="flex items-center flex-shrink-0 min-w-0 max-w-[60%] sm:max-w-none">
          <RouterLink to="/" class="flex items-center space-x-1.5 sm:space-x-2.5 group focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white rounded-lg p-1 -ml-1">
            <div class="w-8 h-8 sm:w-9 sm:h-9 lg:w-8 lg:h-8 bg-black dark:bg-white rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200 shadow-sm flex-shrink-0">
              <span class="text-white dark:text-black font-bold text-sm sm:text-base lg:text-sm">S</span>
            </div>
            <div class="block min-w-0 flex-1">
              <span class="text-xs sm:text-base lg:text-lg xl:text-xl font-bold text-black dark:text-white block truncate leading-tight">Stargate.ci</span>
              <div class="text-[8px] sm:text-[10px] lg:text-xs text-gray-600 dark:text-gray-400 -mt-0.5 truncate leading-tight">Cristal Intelligence</div>
            </div>
          </RouterLink>
        </div>

        <!-- Search Bar - Desktop & Tablet -->
        <div class="hidden md:flex flex-1 max-w-lg mx-4 lg:mx-6 xl:mx-8">
          <SearchBox
            ref="searchBoxRef"
            :placeholder="'Search articles, videos, FAQs...'"
            @search="handleSearch"
            @suggestion-select="handleSuggestionSelect"
          />
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden xl:flex items-center space-x-1">
          <!-- Primary Navigation -->
          <div class="flex items-center space-x-1">
            <RouterLink
              v-for="item in primaryNavigation"
              :key="item.name"
              :to="item.href"
              class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200"
              :class="{ 
                'text-black dark:text-white bg-gray-100 dark:bg-gray-800 font-semibold': $route.path === item.href 
              }"
            >
              {{ item.name }}
            </RouterLink>
          </div>
          
          <!-- Divider -->
          <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>
          
          <!-- Secondary Navigation -->
          <div class="flex items-center space-x-1">
            <RouterLink
              v-for="item in secondaryNavigation"
              :key="item.name"
              :to="item.href"
              class="px-2.5 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200"
              :class="{ 
                'text-black dark:text-white bg-gray-100 dark:bg-gray-800 font-semibold': $route.path === item.href 
              }"
            >
              {{ item.name }}
            </RouterLink>
          </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-2 sm:space-x-3">
          <!-- Search Icon Button - Mobile/Tablet -->
          <button
            v-if="!isMenuOpen"
            @click="toggleSearch"
            class="md:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all duration-200"
            aria-label="Search"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </button>

          <!-- CTA Buttons - Desktop -->
          <div class="hidden md:flex items-center space-x-2">
            <template v-if="!isSubscribed">
              <RouterLink
                to="/signin"
                @click="saveReturnUrl"
                class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200"
              >
                Sign In
              </RouterLink>
              <RouterLink
                to="/signup"
                @click="saveReturnUrl"
                class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg text-sm font-semibold hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-200 shadow-sm hover:shadow-md"
              >
                Sign Up
              </RouterLink>
            </template>
            <div v-else class="flex items-center gap-2">
              <RouterLink
                to="/profile"
                class="px-3 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="hidden lg:inline">Profile</span>
              </RouterLink>
              <button
                @click="unsubscribe"
                class="px-3 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg text-sm font-semibold hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="hidden lg:inline">Logout</span>
              </button>
            </div>
          </div>

          <!-- Mobile menu button -->
          <button
            @click.stop="toggleMenu"
            class="xl:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-all duration-200"
            aria-label="Toggle menu"
            :aria-expanded="isMenuOpen"
          >
            <svg v-if="!isMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Search Bar -->
      <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div v-if="showMobileSearch" class="md:hidden pb-3">
          <SearchBox
            ref="mobileSearchBoxRef"
            :placeholder="'Search articles, videos, FAQs...'"
            @search="handleSearch"
            @suggestion-select="handleSuggestionSelect"
          />
        </div>
      </Transition>

      <!-- Mobile Navigation -->
      <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div v-if="isMenuOpen" class="xl:hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900" @click.stop>
          <div class="px-3 py-3 space-y-1">
            <!-- Primary Navigation Mobile -->
            <div v-if="primaryNavigation.length > 0" class="space-y-1">
              <RouterLink
                v-for="item in primaryNavigation"
                :key="item.name"
                :to="item.href"
                class="block px-3 py-2.5 rounded-lg text-base font-medium text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200"
                :class="{ 
                  'text-black dark:text-white bg-gray-100 dark:bg-gray-800 font-semibold': $route.path === item.href 
                }"
                @click="closeMenu"
              >
                {{ item.name }}
              </RouterLink>
            </div>

            <!-- Secondary Navigation Mobile -->
            <div class="space-y-1" :class="{ 'pt-2 border-t border-gray-200 dark:border-gray-700 mt-2': primaryNavigation.length > 0 }">
              <RouterLink
                v-for="item in secondaryNavigation"
                :key="item.name"
                :to="item.href"
                class="block px-3 py-2.5 rounded-lg text-base font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200"
                :class="{ 
                  'text-black dark:text-white bg-gray-100 dark:bg-gray-800 font-semibold': $route.path === item.href 
                }"
                @click="closeMenu"
              >
                {{ item.name }}
              </RouterLink>
            </div>

            <!-- Mobile CTA -->
            <div class="pt-3 border-t border-gray-200 dark:border-gray-700 space-y-2 mt-3">
              <template v-if="!isSubscribed">
                <RouterLink
                  to="/signin"
                  @click="() => { saveReturnUrl(); closeMenu(); }"
                  class="block w-full px-4 py-3 rounded-lg text-base font-medium text-center text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200"
                >
                  Sign In
                </RouterLink>
                <RouterLink
                  to="/signup"
                  @click="() => { saveReturnUrl(); closeMenu(); }"
                  class="block w-full px-4 py-3 bg-black dark:bg-white text-white dark:text-black rounded-lg text-base font-semibold text-center hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-200 shadow-sm"
                >
                  Sign Up
                </RouterLink>
              </template>
              <template v-else>
                <RouterLink
                  to="/profile"
                  @click="closeMenu"
                  class="block w-full px-4 py-3 rounded-lg text-base font-medium text-center text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  Profile
                </RouterLink>
                <button
                  @click="unsubscribe"
                  class="w-full px-4 py-3 bg-black dark:bg-white text-white dark:text-black rounded-lg text-base font-semibold hover:bg-gray-900 dark:hover:bg-gray-100 transition-all duration-200 shadow-sm flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  Logout
                </button>
              </template>
            </div>
          </div>
        </div>
      </Transition>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { Transition } from 'vue'
import { useAuthStore } from '../../stores/auth'
import SearchBox from '../SearchBox.vue'

const route = useRoute()
const router = useRouter()

// Use authentication store
const authStore = useAuthStore()

// State
const isMenuOpen = ref(false)
const showMobileSearch = ref(false)
const searchBoxRef = ref()
const mobileSearchBoxRef = ref()
const headerRef = ref<HTMLElement | null>(null)

const isTogglingMenu = ref(false)

const toggleMenu = (event?: Event) => {
  if (event) {
    event.stopPropagation()
    event.preventDefault()
  }
  
  isTogglingMenu.value = true
  isMenuOpen.value = !isMenuOpen.value
  
  if (isMenuOpen.value) {
    showMobileSearch.value = false
  }
  
  // Reset flag after a short delay
  setTimeout(() => {
    isTogglingMenu.value = false
  }, 300)
}

const toggleSearch = () => {
  showMobileSearch.value = !showMobileSearch.value
  if (showMobileSearch.value) {
    isMenuOpen.value = false
    // Focus search input after transition
    setTimeout(() => {
      if (mobileSearchBoxRef.value?.focus) {
        mobileSearchBoxRef.value.focus()
      }
    }, 200)
  }
}

const closeMenu = () => {
  isMenuOpen.value = false
}

const closeMobileSearch = () => {
  showMobileSearch.value = false
}

// Save current page URL and scroll position before navigating to sign in/up
const saveReturnUrl = () => {
  sessionStorage.setItem('return_url', window.location.pathname + window.location.search)
  sessionStorage.setItem('return_scroll', window.scrollY.toString())
}

// Navigation items
const primaryNavigation = computed(() => [
  { name: 'Home', href: '/' },
])

const secondaryNavigation = computed(() => [
  { name: 'About', href: '/about' },
  { name: 'Events', href: '/events' },
  { name: 'News', href: '/news' },
  { name: 'Products', href: '/products' },
  { name: 'Community', href: '/community' },
  { name: 'Jobs', href: '/jobs' },
  { name: 'FAQ', href: '/faq' },
  { name: 'Contact', href: '/contact' },
])

// Subscription status
const isSubscribed = computed(() => {
  return authStore.isAuthenticated
})

// Search handler
const handleSearch = (query: string) => {
  if (query.trim()) {
    closeMenu()
    closeMobileSearch()
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
    closeMenu()
  }
}

// Close mobile menu on route change
watch(() => route.path, () => {
  closeMenu()
  closeMobileSearch()
})

// Close menu when clicking outside
let clickTimeout: ReturnType<typeof setTimeout> | null = null

const handleClickOutside = (event: MouseEvent) => {
  // Don't close if menu is being toggled
  if (isTogglingMenu.value) {
    return
  }
  
  if (!isMenuOpen.value) return
  
  const target = event.target as HTMLElement
  
  // Check if click is on menu button or inside header
  if (headerRef.value && headerRef.value.contains(target)) {
    // Don't close if clicking inside header
    return
  }
  
  // Close menu if clicking outside header
  closeMenu()
}

const delayedClickHandler = (event: MouseEvent) => {
  // Clear any existing timeout
  if (clickTimeout) {
    clearTimeout(clickTimeout)
  }
  
  // Add delay to prevent immediate closure when opening menu
  clickTimeout = setTimeout(() => {
    handleClickOutside(event)
  }, 250)
}

// Initialize on mount
onMounted(() => {
  // Set English as default language
  document.documentElement.setAttribute('dir', 'ltr')
  document.documentElement.setAttribute('lang', 'en')
  
  // Initialize auth store
  authStore.initialize()
  
  // Add click outside listener - use bubble phase instead of capture to avoid conflicts
  // Add delay to prevent immediate closure when opening menu
  setTimeout(() => {
    document.addEventListener('click', delayedClickHandler)
  }, 100)
})

// Cleanup event listeners on unmount
onUnmounted(() => {
  document.removeEventListener('click', delayedClickHandler)
  if (clickTimeout) {
    clearTimeout(clickTimeout)
  }
})
</script>

<style scoped>
/* Smooth transitions for better UX */
nav {
  will-change: transform;
}
</style>