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
          <SearchInput 
            placeholder="Search posts, users, articles, FAQs..."
            size="md"
            variant="default"
            @search="handleSearch"
          />
        </div>

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
          <!-- Notification Center -->
          <div class="hidden md:block">
            <NotificationCenter />
          </div>
          
          <!-- CTA Buttons - Compact -->
          <div class="hidden md:flex items-center space-x-2">
            <RouterLink
              to="/login"
              class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-3 py-1.5 rounded-md text-sm font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-150 shadow-md hover:shadow-lg pointer-events-auto relative z-10"
            >
              Sign In
            </RouterLink>
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
          </div>

          <!-- Mobile CTA - Compact -->
          <div class="pt-2 border-t border-gray-700/30 space-y-1">
            <RouterLink
              to="/login"
              class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white block px-2 py-2 rounded-md text-sm font-medium text-center hover:from-primary-600 hover:to-secondary-600 transition-all duration-150"
              @click="closeMenu"
            >
              Sign In
            </RouterLink>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useLanguagePerformance, useNavigationPerformance } from '../../composables/usePerformance'
import NotificationCenter from '../NotificationCenter.vue'
import SearchInput from '../SearchInput.vue'

const { locale, t } = useI18n()
const route = useRoute()

// Performance optimized state
const { isChangingLanguage, changeLanguage: optimizedChangeLanguage } = useLanguagePerformance()
const { isMenuOpen, toggleMenu, closeMenu } = useNavigationPerformance()

const currentLanguage = ref(locale.value)

// Memoized navigation - computed for performance
const primaryNavigation = computed(() => [
  { name: t('nav.home'), href: '/' },
])

const secondaryNavigation = computed(() => [
  { name: t('nav.about'), href: '/about' },
  { name: t('nav.services'), href: '/services' },
  { name: t('nav.partners'), href: '/partnership' },
  { name: 'Community', href: '/community' },
  { name: t('nav.faq'), href: '/faq' },
  { name: t('nav.contact'), href: '/contact' },
])

// Optimized language change function
const changeLanguage = () => {
  optimizedChangeLanguage(currentLanguage.value, () => {
    locale.value = currentLanguage.value
    
    // Batch DOM updates
    const isRTL = currentLanguage.value === 'ar'
    document.documentElement.setAttribute('dir', isRTL ? 'rtl' : 'ltr')
    document.documentElement.setAttribute('lang', currentLanguage.value)
    
    // Save to localStorage
    localStorage.setItem('selectedLanguage', currentLanguage.value)
  })
}

// Search handler
const handleSearch = (query: string) => {
  if (query.trim()) {
    // Navigate to search page with query
    window.location.href = `/search?q=${encodeURIComponent(query)}`
  }
}

// Close mobile menu on route change
watch(() => route.path, () => {
  closeMenu()
})

// Initialize on mount
onMounted(() => {
  const savedLanguage = localStorage.getItem('selectedLanguage')
  if (savedLanguage && savedLanguage !== currentLanguage.value) {
    currentLanguage.value = savedLanguage
    locale.value = savedLanguage
    
    // Set RTL for Arabic
    const isRTL = savedLanguage === 'ar'
    document.documentElement.setAttribute('dir', isRTL ? 'rtl' : 'ltr')
    document.documentElement.setAttribute('lang', savedLanguage)
  }
})
</script>
