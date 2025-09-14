<template>
  <div class="pwa-status">
    <!-- Install Banner -->
    <div v-if="showInstallBanner" class="fixed bottom-4 left-4 right-4 z-50">
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 shadow-lg">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-primary-500/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-white font-medium">Install Stargate.ci</h3>
              <p class="text-gray-400 text-sm">Get quick access and offline functionality</p>
            </div>
          </div>
          <div class="flex space-x-2">
            <button
              @click="installApp"
              :disabled="isInstalling"
              class="bg-primary-500 hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
            >
              <span v-if="isInstalling">Installing...</span>
              <span v-else>Install</span>
            </button>
            <button
              @click="dismissInstallBanner"
              class="text-gray-400 hover:text-white"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Update Available Banner -->
    <div v-if="showUpdateBanner" class="fixed top-4 left-4 right-4 z-50">
      <div class="bg-blue-900/20 border border-blue-500/50 rounded-lg p-4 shadow-lg">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-white font-medium">Update Available</h3>
              <p class="text-gray-400 text-sm">A new version of Stargate.ci is ready</p>
            </div>
          </div>
          <div class="flex space-x-2">
            <button
              @click="updateApp"
              :disabled="isUpdating"
              class="bg-blue-500 hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
            >
              <span v-if="isUpdating">Updating...</span>
              <span v-else>Update</span>
            </button>
            <button
              @click="dismissUpdateBanner"
              class="text-gray-400 hover:text-white"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Offline Indicator -->
    <div v-if="!isOnline" class="fixed top-4 right-4 z-50">
      <div class="bg-red-900/20 border border-red-500/50 rounded-lg p-3 shadow-lg">
        <div class="flex items-center space-x-2">
          <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
          <span class="text-red-400 text-sm font-medium">Offline</span>
        </div>
      </div>
    </div>

    <!-- PWA Status Indicator (for development) -->
    <div v-if="showStatusIndicator" class="fixed bottom-4 right-4 z-50">
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-3 shadow-lg">
        <div class="flex items-center space-x-2">
          <div
            class="w-3 h-3 rounded-full"
            :class="isOnline ? 'bg-green-500' : 'bg-red-500'"
          ></div>
          <span class="text-gray-300 text-xs">
            {{ isOnline ? 'Online' : 'Offline' }}
          </span>
        </div>
        <div class="text-xs text-gray-400 mt-1">
          {{ isInstalled ? 'Installed' : 'Web' }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { pwaService, type PWAStatus } from '../services/pwaService'

// Reactive data
const pwaStatus = ref<PWAStatus>({
  isInstalled: false,
  isInstallable: false,
  isOnline: true,
  hasUpdate: false,
  version: '1.0.0'
})

const showInstallBanner = ref(false)
const showUpdateBanner = ref(false)
const showStatusIndicator = ref(false)
const isInstalling = ref(false)
const isUpdating = ref(false)

// Computed properties
const isOnline = computed(() => pwaStatus.value.isOnline)
const isInstalled = computed(() => pwaStatus.value.isInstalled)

// Methods
const installApp = async () => {
  isInstalling.value = true
  try {
    const success = await pwaService.showInstallPrompt()
    if (success) {
      showInstallBanner.value = false
    }
  } catch (error) {
    console.error('Install failed:', error)
  } finally {
    isInstalling.value = false
  }
}

const updateApp = async () => {
  isUpdating.value = true
  try {
    await pwaService.updateServiceWorker()
    showUpdateBanner.value = false
    // Reload the page to apply updates
    window.location.reload()
  } catch (error) {
    console.error('Update failed:', error)
  } finally {
    isUpdating.value = false
  }
}

const dismissInstallBanner = () => {
  showInstallBanner.value = false
  // Store dismissal in localStorage
  localStorage.setItem('pwa-install-dismissed', 'true')
}

const dismissUpdateBanner = () => {
  showUpdateBanner.value = false
}

const updateStatus = () => {
  pwaStatus.value = pwaService.getStatus()
  
  // Show install banner if installable and not dismissed
  const installDismissed = localStorage.getItem('pwa-install-dismissed')
  if (pwaStatus.value.isInstallable && !pwaStatus.value.isInstalled && !installDismissed) {
    showInstallBanner.value = true
  }
  
  // Show update banner if update available
  if (pwaStatus.value.hasUpdate) {
    showUpdateBanner.value = true
  }
  
  // Show status indicator in development
  if (import.meta.env.DEV) {
    showStatusIndicator.value = true
  }
}

// Event listeners
const handleInstallAvailable = () => {
  updateStatus()
}

const handleInstalled = () => {
  showInstallBanner.value = false
  updateStatus()
}

const handleOnlineStatus = (event: CustomEvent) => {
  pwaStatus.value.isOnline = event.detail.isOnline
}

const handleUpdateAvailable = () => {
  showUpdateBanner.value = true
  updateStatus()
}

// Lifecycle
onMounted(async () => {
  // Initialize PWA service
  await pwaService.initialize()
  
  // Update status
  updateStatus()
  
  // Add event listeners
  window.addEventListener('pwa-install-available', handleInstallAvailable)
  window.addEventListener('pwa-installed', handleInstalled)
  window.addEventListener('pwa-online-status', handleOnlineStatus as EventListener)
  window.addEventListener('pwa-update-available', handleUpdateAvailable)
  
  // Update status periodically
  const statusInterval = setInterval(updateStatus, 5000)
  
  onUnmounted(() => {
    clearInterval(statusInterval)
    window.removeEventListener('pwa-install-available', handleInstallAvailable)
    window.removeEventListener('pwa-installed', handleInstalled)
    window.removeEventListener('pwa-online-status', handleOnlineStatus as EventListener)
    window.removeEventListener('pwa-update-available', handleUpdateAvailable)
  })
})
</script>

<style scoped>
.pwa-status {
  /* Component styles */
}
</style>
