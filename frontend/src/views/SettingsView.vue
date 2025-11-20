<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Settings</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Manage your preferences and account settings
          </p>
        </div>
      </div>
    </section>

    <!-- Settings Content -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-6">
          <!-- Appearance Settings -->
          <div class="card">
            <h2 class="text-2xl font-bold text-white mb-6">Appearance</h2>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Theme</label>
                  <p class="text-gray-400 text-sm">Choose your preferred color theme</p>
                </div>
                <select 
                  v-model="settings.theme"
                  @change="saveSettings"
                  class="bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                  <option value="dark">Dark</option>
                  <option value="light">Light</option>
                  <option value="auto">Auto (System)</option>
                </select>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Language</label>
                  <p class="text-gray-400 text-sm">Select your preferred language</p>
                </div>
                <select 
                  v-model="settings.language"
                  @change="saveSettings"
                  class="bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                  <option value="en">English</option>
                  <option value="sq">Albanian</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Voice Control Settings -->
          <div class="card">
            <h2 class="text-2xl font-bold text-white mb-6">Voice Control</h2>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Enable Voice Control</label>
                  <p class="text-gray-400 text-sm">Allow voice commands to control the platform</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.voiceControl"
                    @change="saveSettings"
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Voice Control Language</label>
                  <p class="text-gray-400 text-sm">Language for voice recognition</p>
                </div>
                <select 
                  v-model="settings.voiceLanguage"
                  @change="saveSettings"
                  :disabled="!settings.voiceControl"
                  class="bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <option value="en-US">English</option>
                </select>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Voice Feedback</label>
                  <p class="text-gray-400 text-sm">Play audio feedback for voice commands</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.voiceFeedback"
                    @change="saveSettings"
                    :disabled="!settings.voiceControl"
                    class="sr-only peer disabled:opacity-50"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600 disabled:opacity-50"></div>
                </label>
              </div>
            </div>
          </div>

          <!-- Notification Settings -->
          <div class="card">
            <h2 class="text-2xl font-bold text-white mb-6">Notifications</h2>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Email Notifications</label>
                  <p class="text-gray-400 text-sm">Receive email updates about events and news</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.emailNotifications"
                    @change="saveSettings"
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Event Reminders</label>
                  <p class="text-gray-400 text-sm">Get reminders for upcoming events</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.eventReminders"
                    @change="saveSettings"
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Newsletter</label>
                  <p class="text-gray-400 text-sm">Subscribe to our newsletter for updates</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.newsletter"
                    @change="saveSettings"
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>
            </div>
          </div>

          <!-- Privacy Settings -->
          <div class="card">
            <h2 class="text-2xl font-bold text-white mb-6">Privacy</h2>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Analytics</label>
                  <p class="text-gray-400 text-sm">Help us improve by sharing anonymous usage data</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.analytics"
                    @change="saveSettings"
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Personalized Content</label>
                  <p class="text-gray-400 text-sm">Show content based on your interests</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.personalizedContent"
                    @change="saveSettings"
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-700">
              <RouterLink 
                to="/privacy" 
                class="text-primary-400 hover:text-primary-300 underline text-sm"
              >
                View Privacy Policy
              </RouterLink>
            </div>
          </div>

          <!-- Cookie Settings -->
          <div class="card">
            <h2 class="text-2xl font-bold text-white mb-6">Cookie Preferences</h2>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Essential Cookies</label>
                  <p class="text-gray-400 text-sm">Required for the platform to function</p>
                </div>
                <span class="text-gray-500 text-sm">Always Active</span>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Analytics Cookies</label>
                  <p class="text-gray-400 text-sm">Help us understand how you use the platform</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.analyticsCookies"
                    @change="saveSettings"
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Functional Cookies</label>
                  <p class="text-gray-400 text-sm">Remember your preferences and settings</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input 
                    type="checkbox" 
                    v-model="settings.functionalCookies"
                    @change="saveSettings"
                    class="sr-only peer"
                  >
                  <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-700">
              <RouterLink 
                to="/cookies" 
                class="text-primary-400 hover:text-primary-300 underline text-sm"
              >
                Learn more about cookies
              </RouterLink>
            </div>
          </div>

          <!-- Data Management -->
          <div class="card">
            <h2 class="text-2xl font-bold text-white mb-6">Data Management</h2>
            
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Export Data</label>
                  <p class="text-gray-400 text-sm">Download a copy of your data</p>
                </div>
                <button 
                  @click="exportData"
                  class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors"
                >
                  Export
                </button>
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <label class="text-white font-medium">Delete Account</label>
                  <p class="text-gray-400 text-sm">Permanently delete your account and data</p>
                </div>
                <button 
                  @click="confirmDelete"
                  class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>

          <!-- Success Message -->
          <div 
            v-if="saveMessage"
            class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50"
          >
            {{ saveMessage }}
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useHead } from '@vueuse/head'

useHead({
  title: 'Settings | Stargate.ci',
  meta: [
    {
      name: 'description',
      content: 'Manage your preferences, privacy settings, and account options on Stargate.ci'
    }
  ]
})

interface Settings {
  theme: string
  language: string
  voiceControl: boolean
  voiceLanguage: string
  voiceFeedback: boolean
  emailNotifications: boolean
  eventReminders: boolean
  newsletter: boolean
  analytics: boolean
  personalizedContent: boolean
  analyticsCookies: boolean
  functionalCookies: boolean
}

const settings = ref<Settings>({
  theme: 'dark',
  language: 'en',
  voiceControl: true,
  voiceLanguage: 'en-US',
  voiceFeedback: true,
  emailNotifications: true,
  eventReminders: true,
  newsletter: false,
  analytics: true,
  personalizedContent: true,
  analyticsCookies: true,
  functionalCookies: true,
})

const saveMessage = ref<string>('')

const notifyVoiceControlChange = () => {
  if (typeof window === 'undefined') return
  window.dispatchEvent(
    new CustomEvent('voice-control-toggle', {
      detail: {
        enabled: settings.value.voiceControl,
        language: settings.value.voiceLanguage,
      },
    })
  )
}

const loadSettings = () => {
  const saved = localStorage.getItem('stargate-settings')
  if (saved) {
    try {
      settings.value = { ...settings.value, ...JSON.parse(saved) }
    } catch (e) {
      console.error('Error loading settings:', e)
    }
  }
}

const saveSettings = () => {
  localStorage.setItem('stargate-settings', JSON.stringify(settings.value))
  notifyVoiceControlChange()
  saveMessage.value = 'Settings saved successfully!'
  setTimeout(() => {
    saveMessage.value = ''
  }, 3000)
  
  // Apply theme immediately
  if (settings.value.theme === 'dark') {
    document.documentElement.classList.add('dark')
  } else if (settings.value.theme === 'light') {
    document.documentElement.classList.remove('dark')
  } else {
    // Auto - follow system preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    if (prefersDark) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  }
}

const exportData = () => {
  const data = {
    settings: settings.value,
    exportDate: new Date().toISOString(),
  }
  const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `stargate-data-${Date.now()}.json`
  a.click()
  URL.revokeObjectURL(url)
}

const confirmDelete = () => {
  if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
    // In a real app, this would call an API
    localStorage.removeItem('stargate-settings')
    alert('Account deletion requested. Please contact support for assistance.')
  }
}

onMounted(() => {
  loadSettings()
  saveSettings() // Apply loaded settings
})
</script>

