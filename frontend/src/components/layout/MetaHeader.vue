<template>
  <div class="meta-header bg-gray-900/95 backdrop-blur-sm border-b border-gray-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-10">
        <!-- Left: Navigation Links -->
        <div class="flex items-center space-x-1">
          <RouterLink
            v-for="item in navigationItems"
            :key="item.name"
            :to="item.href"
            class="text-gray-400 hover:text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-150 hover:bg-gray-800/40"
            :class="{ 
              'text-white bg-primary-500/20': $route.path === item.href 
            }"
          >
            {{ item.name }}
          </RouterLink>
        </div>

        <!-- Right: Language Switcher -->
        <div class="flex-shrink-0">
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
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useLanguagePerformance } from '../../composables/usePerformance'

const { locale, t } = useI18n()
const route = useRoute()
const { isChangingLanguage, changeLanguage: optimizedChangeLanguage } = useLanguagePerformance()

const currentLanguage = ref(locale.value)

// Navigation items for MetaHeader
const navigationItems = computed(() => [
  { name: t('nav.templates'), href: '/templates' },
  { name: t('nav.assessment'), href: '/assessment' },
  { name: t('nav.learning'), href: '/learning' },
  { name: t('nav.insights'), href: '/insights' },
])

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
