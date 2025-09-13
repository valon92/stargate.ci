<template>
  <header class="sticky top-0 z-50 bg-gray-900/95 backdrop-blur-md border-b border-gray-700/50 shadow-lg">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16 lg:h-18">
        <!-- Logo Section -->
        <div class="flex items-center flex-shrink-0">
          <RouterLink to="/" class="flex items-center space-x-2 group">
            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg">
              <span class="text-white font-bold text-lg lg:text-xl">S</span>
            </div>
            <div class="block">
              <span class="text-lg sm:text-xl lg:text-2xl font-bold gradient-text">Stargate.ci</span>
              <div class="text-xs text-gray-400 -mt-1">Cristal Intelligence</div>
            </div>
          </RouterLink>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:block flex-1">
          <div class="flex justify-center">
            <div class="flex items-center space-x-1">
              <!-- Primary Navigation -->
              <div class="flex items-center space-x-1">
                <RouterLink
                  v-for="item in primaryNavigation"
                  :key="item.name"
                  :to="item.href"
                  class="text-gray-300 hover:text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-800/50"
                  :class="{ 
                    'text-white bg-gradient-to-r from-primary-500/20 to-secondary-500/20 border border-primary-500/30': $route.path === item.href 
                  }"
                >
                  {{ item.name }}
                </RouterLink>
              </div>
              
              <!-- Separator -->
              <div class="w-px h-6 bg-gray-600 mx-2"></div>
              
              <!-- Secondary Navigation -->
              <div class="flex items-center space-x-1">
                <RouterLink
                  v-for="item in secondaryNavigation"
                  :key="item.name"
                  :to="item.href"
                  class="text-gray-400 hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-800/50"
                  :class="{ 
                    'text-white bg-gray-800/50': $route.path === item.href 
                  }"
                >
                  {{ item.name }}
                </RouterLink>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-3">
          <!-- Language Switcher -->
          <div class="hidden sm:block">
            <div class="relative">
              <select
                v-model="currentLanguage"
                @change="changeLanguage"
                class="bg-gray-800/50 text-gray-300 border border-gray-600/50 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/50 transition-all duration-200 hover:bg-gray-700/50"
              >
                <option value="en">🇺🇸 EN</option>
                <option value="fr">🇫🇷 FR</option>
                <option value="de">🇩🇪 DE</option>
                <option value="es">🇪🇸 ES</option>
                <option value="it">🇮🇹 IT</option>
                <option value="ar">🇸🇦 AR</option>
              </select>
            </div>
          </div>

                      <!-- CTA Buttons -->
                      <div class="hidden md:flex items-center space-x-3">
                        <RouterLink
                          to="/dashboard"
                          class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                        >
                          Dashboard
                        </RouterLink>
                        <RouterLink
                          to="/admin/login"
                          class="text-gray-400 hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-800/50"
                        >
                          Admin
                        </RouterLink>
                      </div>

          <!-- Mobile menu button -->
          <div class="lg:hidden">
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="text-gray-300 hover:text-white p-2 rounded-lg hover:bg-gray-800/50 transition-colors duration-200"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div v-if="mobileMenuOpen" class="lg:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-700/50">
          <!-- Primary Navigation Mobile -->
          <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">Main</div>
            <RouterLink
              v-for="item in primaryNavigation"
              :key="item.name"
              :to="item.href"
              class="text-gray-300 hover:text-white block px-3 py-2 rounded-lg text-base font-medium transition-colors duration-200 hover:bg-gray-800/50"
              :class="{ 'text-white bg-gradient-to-r from-primary-500/20 to-secondary-500/20': $route.path === item.href }"
              @click="mobileMenuOpen = false"
            >
              {{ item.name }}
            </RouterLink>
          </div>

          <!-- Secondary Navigation Mobile -->
          <div class="space-y-1 pt-2">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-3 py-2">Resources</div>
            <RouterLink
              v-for="item in secondaryNavigation"
              :key="item.name"
              :to="item.href"
              class="text-gray-400 hover:text-white block px-3 py-2 rounded-lg text-base font-medium transition-colors duration-200 hover:bg-gray-800/50"
              :class="{ 'text-white bg-gray-800/50': $route.path === item.href }"
              @click="mobileMenuOpen = false"
            >
              {{ item.name }}
            </RouterLink>
          </div>

                      <!-- Mobile CTA -->
                      <div class="pt-4 border-t border-gray-700/50 space-y-2">
                        <RouterLink
                          to="/dashboard"
                          class="bg-gradient-to-r from-primary-500 to-secondary-500 text-white block px-3 py-3 rounded-lg text-base font-medium text-center hover:from-primary-600 hover:to-secondary-600 transition-all duration-200"
                          @click="mobileMenuOpen = false"
                        >
                          Go to Dashboard
                        </RouterLink>
                        <RouterLink
                          to="/admin/login"
                          class="text-gray-400 hover:text-white block px-3 py-2 rounded-lg text-base font-medium text-center transition-all duration-200 hover:bg-gray-800/50"
                          @click="mobileMenuOpen = false"
                        >
                          Admin Access
                        </RouterLink>
                      </div>

          <!-- Language Switcher Mobile -->
          <div class="px-3 py-2">
            <select
              v-model="currentLanguage"
              @change="changeLanguage"
              class="bg-gray-800/50 text-gray-300 border border-gray-600/50 rounded-lg px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500/50"
            >
              <option value="en">🇺🇸 English</option>
              <option value="fr">🇫🇷 Français</option>
              <option value="de">🇩🇪 Deutsch</option>
              <option value="es">🇪🇸 Español</option>
              <option value="it">🇮🇹 Italiano</option>
              <option value="ar">🇸🇦 العربية</option>
            </select>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { locale, t } = useI18n()

const mobileMenuOpen = ref(false)
const currentLanguage = ref(locale.value)

// Primary Navigation - Main features
const primaryNavigation = computed(() => [
  { name: t('nav.home'), href: '/' },
  { name: t('nav.templates'), href: '/templates' },
  { name: t('nav.assessment'), href: '/assessment' },
  { name: t('nav.learning'), href: '/learning' },
])

// Secondary Navigation - Resources and info
const secondaryNavigation = computed(() => [
  { name: t('nav.about'), href: '/about' },
  { name: t('nav.services'), href: '/services' },
  { name: t('nav.partners'), href: '/partnership' },
  { name: t('nav.insights'), href: '/insights' },
  { name: t('nav.faq'), href: '/faq' },
  { name: t('nav.contact'), href: '/contact' },
])

const changeLanguage = () => {
  locale.value = currentLanguage.value
  
  // Set RTL for Arabic
  if (currentLanguage.value === 'ar') {
    document.documentElement.setAttribute('dir', 'rtl')
    document.documentElement.setAttribute('lang', 'ar')
  } else {
    document.documentElement.setAttribute('dir', 'ltr')
    document.documentElement.setAttribute('lang', currentLanguage.value)
  }
  
  // Save to localStorage
  localStorage.setItem('selectedLanguage', currentLanguage.value)
}

// Load saved language on mount
onMounted(() => {
  const savedLanguage = localStorage.getItem('selectedLanguage')
  if (savedLanguage) {
    currentLanguage.value = savedLanguage
    locale.value = savedLanguage
    
    // Set RTL for Arabic
    if (savedLanguage === 'ar') {
      document.documentElement.setAttribute('dir', 'rtl')
      document.documentElement.setAttribute('lang', 'ar')
    } else {
      document.documentElement.setAttribute('dir', 'ltr')
      document.documentElement.setAttribute('lang', savedLanguage)
    }
  }
})
</script>
