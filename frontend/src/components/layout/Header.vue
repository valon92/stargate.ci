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
        <div class="hidden md:flex items-center space-x-4">
          <button
            @click="toggleLanguage"
            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
          >
            {{ currentLanguage === 'en' ? 'FR' : 'EN' }}
          </button>
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
          <button
            @click="toggleLanguage"
            class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200 w-full text-left"
          >
            {{ currentLanguage === 'en' ? 'Français' : 'English' }}
          </button>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()

const mobileMenuOpen = ref(false)

const currentLanguage = computed(() => locale.value)

const navigation = [
  { name: 'Home', href: '/' },
  { name: 'About', href: '/about' },
  { name: 'Services', href: '/services' },
  { name: 'Partners', href: '/partners' },
  { name: 'Insights', href: '/insights' },
  { name: 'FAQ', href: '/faq' },
  { name: 'Contact', href: '/contact' },
]

const toggleLanguage = () => {
  locale.value = locale.value === 'en' ? 'fr' : 'en'
}
</script>
