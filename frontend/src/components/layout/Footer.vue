<template>
  <footer class="bg-gray-800 border-t border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Brand Section -->
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center space-x-2 mb-4">
            <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
              <span class="text-white font-bold text-lg">S</span>
            </div>
            <span class="text-xl font-bold gradient-text">Stargate.ci</span>
          </div>
          <p class="text-gray-400 mb-4 max-w-md">
            Independent educational platform connecting companies with Stargate Project and Cristal Intelligence opportunities.
          </p>
          <div class="flex items-center text-sm text-gray-500">
            <span class="w-2 h-2 bg-primary-500 rounded-full mr-2"></span>
            Not affiliated with OpenAI, SoftBank, or ARM
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
      </div>

      <!-- Bottom Section -->
      <div class="mt-8 pt-8 border-t border-gray-700">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="text-gray-400 text-sm">
            <p>&copy; {{ currentYear }} Stargate.ci. All rights reserved.</p>
            <p class="mt-1">
              Independent educational platform. Not affiliated with OpenAI, SoftBank, or ARM.
            </p>
          </div>
          <div class="mt-4 md:mt-0">
            <div class="flex space-x-6 text-sm">
              <RouterLink to="/disclaimer" class="text-gray-400 hover:text-white transition-colors duration-200">Disclaimer</RouterLink>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { RouterLink } from 'vue-router'

const currentYear = computed(() => new Date().getFullYear())

const platformLinks = [
  { name: 'About', href: '/about' },
  { name: 'Events', href: '/events' },
  { name: 'News', href: '/news' },
  { name: 'FAQ', href: '/faq' },
  { name: 'Contact', href: '/contact' },
  { name: 'Subscribe', href: '/subscribe' },
]
</script>
