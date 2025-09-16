<template>
  <div class="pwa-status">
    <!-- Install Banner - Compact Version -->
    <div v-if="showInstallBanner" class="fixed bottom-4 left-4 z-50">
      <div class="bg-gray-800/90 backdrop-blur-sm border border-gray-700/50 rounded-xl p-3 shadow-xl">
        <div class="flex items-center space-x-3">
          <!-- Logo -->
          <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
            <span class="text-white font-bold text-sm">S</span>
          </div>
          
          <!-- Install Button with Arrow and Tooltip -->
          <div class="relative group">
            <button
              @click="installApp"
              :disabled="isInstalling"
              class="flex items-center space-x-2 bg-primary-500/20 hover:bg-primary-500/30 disabled:opacity-50 disabled:cursor-not-allowed text-primary-400 hover:text-primary-300 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 border border-primary-500/30 hover:border-primary-500/50"
            >
              <span v-if="isInstalling" class="flex items-center space-x-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Installing...</span>
              </span>
              <span v-else class="flex items-center space-x-2">
                <span>Install</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
              </span>
            </button>
            
            <!-- Tooltip -->
            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
              <div class="text-center">
                <div class="font-semibold">Install Stargate.ci</div>
                <div class="text-gray-300">Get quick access and offline functionality</div>
              </div>
              <!-- Tooltip arrow -->
              <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
            </div>
          </div>
          
          <!-- Dismiss Button -->
          <button
            @click="dismissInstallBanner"
            class="text-gray-400 hover:text-white p-1 rounded-full hover:bg-gray-700/50 transition-colors duration-200"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
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
    } else {
      // For mobile devices, show instructions
      const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
      const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent)
      const isAndroid = /Android/.test(navigator.userAgent)
      
      if (isMobile) {
        if (isIOS) {
          alert('To install this app on iOS:\n1. Tap the Share button\n2. Scroll down and tap "Add to Home Screen"\n3. Tap "Add"')
        } else if (isAndroid) {
          alert('To install this app on Android:\n1. Tap the menu button (⋮)\n2. Tap "Add to Home screen" or "Install app"\n3. Tap "Add" or "Install"')
        } else {
          alert('To install this app:\n1. Look for the install option in your browser menu\n2. Or add this page to your home screen')
        }
      }
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
  // Don't store dismissal - let it show again on page refresh
  // sessionStorage.setItem('pwa-install-dismissed', 'true')
}

const dismissUpdateBanner = () => {
  showUpdateBanner.value = false
}

const updateStatus = () => {
  pwaStatus.value = pwaService.getStatus()
  
  // Show install banner if installable and not installed
  // Also check for mobile devices specifically
  const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
  const shouldShowInstall = pwaStatus.value.isInstallable && !pwaStatus.value.isInstalled
  
  if (shouldShowInstall || (isMobile && !pwaStatus.value.isInstalled)) {
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
  
  // For mobile devices, show install banner after a delay
  const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
  if (isMobile && !pwaStatus.value.isInstalled) {
    setTimeout(() => {
      updateStatus()
    }, 3000) // Show after 3 seconds
  }
  
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
