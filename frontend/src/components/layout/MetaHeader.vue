<template>
  <div class="meta-header bg-gray-900/95 backdrop-blur-sm border-b border-gray-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-10">
        <!-- Left: Project Info -->
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <span class="text-xs text-gray-300 font-medium">Stargate.ci</span>
          </div>
          <div class="hidden sm:block text-xs text-gray-400">
            Cristal Intelligence Platform
          </div>
        </div>

        <!-- Center: Status & Info -->
        <div class="hidden md:flex items-center space-x-6">
          <div class="flex items-center space-x-2">
            <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs text-gray-300">Live</span>
          </div>
          <div class="flex items-center space-x-2">
            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-xs text-gray-300">v2.0</span>
          </div>
        </div>

        <!-- Right: Quick Actions -->
        <div class="flex items-center space-x-3">
          <!-- Language Switcher (Compact) -->
          <div class="hidden lg:block">
            <select 
              v-model="currentLanguage" 
              @change="changeLanguage"
              class="bg-gray-800 border border-gray-700 text-gray-300 text-xs rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="en">🇺🇸 EN</option>
              <option value="fr">🇫🇷 FR</option>
              <option value="de">🇩🇪 DE</option>
              <option value="es">🇪🇸 ES</option>
              <option value="it">🇮🇹 IT</option>
              <option value="ar">🇸🇦 AR</option>
            </select>
          </div>

          <!-- Quick Links -->
          <div class="hidden sm:flex items-center space-x-2">
            <a 
              href="/dashboard" 
              class="text-xs text-gray-400 hover:text-white transition-colors duration-200"
            >
              Dashboard
            </a>
            <span class="text-gray-600">|</span>
            <a 
              href="/assessment" 
              class="text-xs text-gray-400 hover:text-white transition-colors duration-200"
            >
              Assessment
            </a>
          </div>

          <!-- Mobile Menu Toggle -->
          <button 
            @click="toggleMobileMenu"
            class="lg:hidden text-gray-400 hover:text-white transition-colors duration-200"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div v-if="isMenuOpen" class="lg:hidden bg-gray-800/95 border-t border-gray-700/50">
      <div class="px-4 py-3 space-y-3">
        <!-- Mobile Language Switcher -->
        <div>
          <label class="block text-xs text-gray-400 mb-1">Language</label>
          <select 
            v-model="currentLanguage" 
            @change="changeLanguage"
            class="w-full bg-gray-700 border border-gray-600 text-gray-300 text-sm rounded px-3 py-2 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
          >
            <option value="en">🇺🇸 English</option>
            <option value="fr">🇫🇷 Français</option>
            <option value="de">🇩🇪 Deutsch</option>
            <option value="es">🇪🇸 Español</option>
            <option value="it">🇮🇹 Italiano</option>
            <option value="ar">🇸🇦 العربية</option>
          </select>
        </div>

        <!-- Mobile Quick Links -->
        <div class="space-y-2">
          <a 
            href="/dashboard" 
            class="block text-sm text-gray-300 hover:text-white transition-colors duration-200"
            @click="isMenuOpen = false"
          >
            Dashboard
          </a>
          <a 
            href="/assessment" 
            class="block text-sm text-gray-300 hover:text-white transition-colors duration-200"
            @click="isMenuOpen = false"
          >
            Assessment Tool
          </a>
          <a 
            href="/learning" 
            class="block text-sm text-gray-300 hover:text-white transition-colors duration-200"
            @click="isMenuOpen = false"
          >
            Learning Paths
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useLanguagePerformance, useNavigationPerformance } from '../../composables/usePerformance'

const { locale } = useI18n()
const { isChangingLanguage, changeLanguage: optimizedChangeLanguage } = useLanguagePerformance()
const { isMenuOpen, toggleMenu } = useNavigationPerformance()

const currentLanguage = ref(locale.value)

// Optimized language switching
const changeLanguage = () => {
  optimizedChangeLanguage(currentLanguage.value, () => {
    locale.value = currentLanguage.value
    localStorage.setItem('selected-language', currentLanguage.value)
    
    // Batch DOM updates
    const isRTL = currentLanguage.value === 'ar'
    document.documentElement.setAttribute('dir', isRTL ? 'rtl' : 'ltr')
    document.documentElement.setAttribute('lang', currentLanguage.value)
  })
}

// Optimized mobile menu toggle
const toggleMobileMenu = () => {
  toggleMenu()
}

// Initialize
onMounted(() => {
  // Load saved language
  const savedLanguage = localStorage.getItem('selected-language')
  if (savedLanguage && savedLanguage !== currentLanguage.value) {
    currentLanguage.value = savedLanguage
    locale.value = savedLanguage
    
    // Apply RTL for Arabic
    const isRTL = savedLanguage === 'ar'
    document.documentElement.setAttribute('dir', isRTL ? 'rtl' : 'ltr')
    document.documentElement.setAttribute('lang', savedLanguage)
  }
})
</script>

<style scoped>
.meta-header {
  position: sticky;
  top: 0;
  z-index: 40;
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Smooth transitions */
* {
  transition: all 0.2s ease-in-out;
}

/* Custom scrollbar for select */
select::-webkit-scrollbar {
  width: 6px;
}

select::-webkit-scrollbar-track {
  background: #374151;
  border-radius: 3px;
}

select::-webkit-scrollbar-thumb {
  background: #6b7280;
  border-radius: 3px;
}

select::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>
