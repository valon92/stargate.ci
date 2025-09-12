<template>
  <header class="sticky top-0 z-50 bg-gray-900/95 backdrop-blur-sm border-b border-gray-700">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <RouterLink to="/" class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">S</span>
            </div>
            <span class="text-xl font-bold gradient-text">Stargate.ci</span>
          </RouterLink>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-8">
            <RouterLink
              v-for="item in navigation"
              :key="item.name"
              :to="item.href"
              class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
              :class="{ 'text-white bg-gray-800': $route.path === item.href }"
            >
              {{ item.name }}
            </RouterLink>
          </div>
        </div>

        <!-- Language Switcher -->
        <div class="hidden md:flex items-center space-x-2">
          <div class="relative">
            <select
              v-model="currentLanguage"
              @change="changeLanguage"
              class="bg-gray-800 text-gray-300 border border-gray-600 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
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

        <!-- Mobile menu button -->
        <div class="md:hidden">
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="text-gray-300 hover:text-white p-2 rounded-md"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div v-if="mobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-700">
          <RouterLink
            v-for="item in navigation"
            :key="item.name"
            :to="item.href"
            class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
            :class="{ 'text-white bg-gray-800': $route.path === item.href }"
            @click="mobileMenuOpen = false"
          >
            {{ item.name }}
          </RouterLink>
          <div class="px-3 py-2">
            <select
              v-model="currentLanguage"
              @change="changeLanguage"
              class="bg-gray-800 text-gray-300 border border-gray-600 rounded-md px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
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

const navigation = computed(() => [
  { name: t('nav.home'), href: '/' },
  { name: t('nav.dashboard'), href: '/dashboard' },
  { name: t('nav.templates'), href: '/templates' },
  { name: t('nav.about'), href: '/about' },
  { name: t('nav.services'), href: '/services' },
  { name: t('nav.partners'), href: '/partnership' },
  { name: t('nav.assessment'), href: '/assessment' },
  { name: t('nav.learning'), href: '/learning' },
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
