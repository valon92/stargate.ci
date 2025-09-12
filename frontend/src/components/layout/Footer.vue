<template>
  <footer class="bg-gray-800 border-t border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Brand Section -->
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">S</span>
            </div>
            <span class="text-xl font-bold gradient-text">Stargate.ci</span>
          </div>
          <p class="text-gray-400 mb-4 max-w-md">
            {{ platformMission }}
          </p>
          <div class="space-y-2 mb-4">
            <div class="flex items-center text-sm text-gray-500">
              <span class="w-2 h-2 bg-primary-500 rounded-full mr-2"></span>
              Independent Educational Platform
            </div>
            <div class="flex items-center text-sm text-gray-500">
              <span class="w-2 h-2 bg-secondary-500 rounded-full mr-2"></span>
              Not affiliated with OpenAI, SoftBank, or ARM
            </div>
            <div class="flex items-center text-sm text-gray-500">
              <span class="w-2 h-2 bg-accent-500 rounded-full mr-2"></span>
              Information only - No fees or commissions
            </div>
          </div>
          <div class="flex space-x-4">
            <a href="https://stargateprojects.net/" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-200">
              <span class="sr-only">Official Stargate Project</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
              </svg>
            </a>
            <a href="https://group.softbank/en/news/press/20250203_0" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-200">
              <span class="sr-only">SoftBank Press Release</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
              </svg>
            </a>
            <a href="https://openai.com/index/announcing-the-stargate-project/" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-200">
              <span class="sr-only">OpenAI Stargate Announcement</span>
              <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Platform Links -->
        <div>
          <h3 class="text-white font-semibold mb-4">Platform</h3>
          <ul class="space-y-2">
            <li v-for="link in platformLinks" :key="link.name">
              <RouterLink :to="link.href" class="text-gray-400 hover:text-white transition-colors duration-200">
                {{ link.name }}
              </RouterLink>
            </li>
          </ul>
        </div>

        <!-- Official Sources -->
        <div>
          <h3 class="text-white font-semibold mb-4">Official Sources</h3>
          <ul class="space-y-2">
            <li v-for="source in officialSources" :key="source.name">
              <a :href="source.href" target="_blank" class="text-gray-400 hover:text-white transition-colors duration-200">
                {{ source.name }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Section -->
      <div class="mt-8 pt-8 border-t border-gray-700">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="text-gray-400 text-sm">
            <p>&copy; {{ currentYear }} Stargate.ci. All rights reserved.</p>
            <p class="mt-1">
              Independent educational platform. Not affiliated with OpenAI, SoftBank, or ARM.
            </p>
            <p class="mt-1 text-xs text-gray-500">
              All information sourced from official project announcements and press releases.
            </p>
          </div>
          <div class="mt-4 md:mt-0">
            <div class="flex space-x-6 text-sm">
              <RouterLink to="/legal-disclaimer" class="text-gray-400 hover:text-white transition-colors duration-200">Legal Disclaimer</RouterLink>
              <RouterLink to="/privacy" class="text-gray-400 hover:text-white transition-colors duration-200">Privacy Policy</RouterLink>
              <RouterLink to="/terms" class="text-gray-400 hover:text-white transition-colors duration-200">Terms of Service</RouterLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { stargateApi } from '../../services/stargateApi'

const currentYear = computed(() => new Date().getFullYear())
const platformMission = ref('')

// Load platform mission from API
onMounted(() => {
  platformMission.value = stargateApi.getPlatformMission()
})

const platformLinks = [
  { name: 'About Platform', href: '/about' },
  { name: 'Services', href: '/services' },
  { name: 'Partnership', href: '/partnership' },
  { name: 'Insights', href: '/insights' },
  { name: 'FAQ', href: '/faq' },
  { name: 'Contact', href: '/contact' },
]

const officialSources = [
  { name: 'Stargate Project', href: 'https://stargateprojects.net/' },
  { name: 'SoftBank Press Release', href: 'https://group.softbank/en/news/press/20250203_0' },
  { name: 'OpenAI Announcement', href: 'https://openai.com/index/announcing-the-stargate-project/' },
  { name: 'ARM Technology', href: 'https://arm.com' },
]
</script>
