<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">Mobile App Management</h1>
      <p class="text-gray-300">Manage mobile app features, notifications, and device integration</p>
    </div>

    <!-- Device Information -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <h2 class="text-xl font-bold text-white mb-4">Device Information</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="space-y-3">
          <h3 class="text-lg font-semibold text-white">Device Type</h3>
          <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Platform:</span>
              <span class="text-white font-medium">{{ device.platform.toUpperCase() }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Type:</span>
              <span class="text-white font-medium">
                {{ device.isMobile ? 'Mobile' : device.isTablet ? 'Tablet' : 'Desktop' }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Orientation:</span>
              <span class="text-white font-medium capitalize">{{ device.orientation }}</span>
            </div>
          </div>
        </div>

        <div class="space-y-3">
          <h3 class="text-lg font-semibold text-white">Screen Information</h3>
          <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Resolution:</span>
              <span class="text-white font-medium">{{ device.screenSize.width }} × {{ device.screenSize.height }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Pixel Ratio:</span>
              <span class="text-white font-medium">{{ device.devicePixelRatio }}x</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Touch Support:</span>
              <span class="text-white font-medium">{{ device.touchSupport ? 'Yes' : 'No' }}</span>
            </div>
          </div>
        </div>

        <div class="space-y-3">
          <h3 class="text-lg font-semibold text-white">App Status</h3>
          <div class="space-y-2 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Installed:</span>
              <span :class="appState.isInstalled ? 'text-green-400' : 'text-red-400'" class="font-medium">
                {{ appState.isInstalled ? 'Yes' : 'No' }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Online:</span>
              <span :class="appState.isOnline ? 'text-green-400' : 'text-red-400'" class="font-medium">
                {{ appState.isOnline ? 'Yes' : 'No' }}
              </span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Last Sync:</span>
              <span class="text-white font-medium">{{ formatDate(appState.lastSync) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- App Installation -->
    <div v-if="!appState.isInstalled" class="bg-gradient-to-r from-primary-500/10 to-secondary-500/10 rounded-xl shadow-lg border border-primary-500/20 p-6 mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-bold text-white mb-2">Install Mobile App</h2>
          <p class="text-gray-300 mb-4">Get the full Stargate.ci experience with our mobile app</p>
          <div class="flex items-center space-x-4 text-sm text-gray-400">
            <span>📱 Native performance</span>
            <span>🔔 Push notifications</span>
            <span>📴 Offline support</span>
            <span>🔐 Biometric security</span>
          </div>
        </div>
        <div class="flex flex-col space-y-3">
          <button
            @click="installApp"
            :disabled="isInstalling"
            class="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg hover:from-primary-600 hover:to-secondary-600 disabled:opacity-50 transition-all duration-200 font-medium"
          >
            <span v-if="isInstalling" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Installing...
            </span>
            <span v-else class="flex items-center">
              {{ mobileAppService.getInstallPrompt() }}
              <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </span>
          </button>
          <a
            :href="mobileAppService.getInstallUrl()"
            target="_blank"
            class="text-center text-sm text-gray-400 hover:text-white transition-colors"
          >
            Open in Store
          </a>
        </div>
      </div>
    </div>

    <!-- App Features -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
      <!-- Notifications -->
      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-bold text-white">Push Notifications</h2>
          <button
            @click="toggleNotifications"
            :class="[
              'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
              appState.settings.notifications
                ? 'bg-green-600 text-white hover:bg-green-700'
                : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
            ]"
          >
            {{ appState.settings.notifications ? 'Enabled' : 'Disabled' }}
          </button>
        </div>

        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-gray-300">Permission Status:</span>
            <span :class="appState.permissions.notifications ? 'text-green-400' : 'text-red-400'" class="font-medium">
              {{ appState.permissions.notifications ? 'Granted' : 'Denied' }}
            </span>
          </div>

          <div v-if="!appState.permissions.notifications" class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4">
            <div class="flex items-center">
              <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span class="text-yellow-400 text-sm">Notifications permission required</span>
            </div>
            <button
              @click="requestNotificationPermission"
              class="mt-2 px-3 py-1 bg-yellow-600 text-white rounded text-sm hover:bg-yellow-700 transition-colors"
            >
              Request Permission
            </button>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-300">Test Notification</label>
            <div class="flex space-x-2">
              <input
                v-model="testNotification.title"
                type="text"
                placeholder="Notification title"
                class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500 text-sm"
              />
              <button
                @click="sendTestNotification"
                class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors text-sm"
              >
                Send
              </button>
            </div>
          </div>

          <div v-if="notifications.length > 0" class="space-y-2">
            <h3 class="text-sm font-medium text-gray-300">Recent Notifications</h3>
            <div class="max-h-32 overflow-y-auto space-y-1">
              <div
                v-for="notification in notifications.slice(0, 5)"
                :key="notification.id"
                class="flex items-center justify-between p-2 bg-gray-700 rounded text-sm"
              >
                <div class="flex-1">
                  <div class="text-white font-medium">{{ notification.title }}</div>
                  <div class="text-gray-400 text-xs">{{ notification.body }}</div>
                </div>
                <div class="text-xs text-gray-500">{{ formatTime(notification.timestamp) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- App Settings -->
      <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
        <h2 class="text-xl font-bold text-white mb-4">App Settings</h2>

        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-300 font-medium">Dark Mode</div>
              <div class="text-gray-400 text-sm">Use dark theme</div>
            </div>
            <button
              @click="toggleSetting('darkMode')"
              :class="[
                'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                appState.settings.darkMode ? 'bg-primary-600' : 'bg-gray-600'
              ]"
            >
              <span
                :class="[
                  'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                  appState.settings.darkMode ? 'translate-x-6' : 'translate-x-1'
                ]"
              ></span>
            </button>
          </div>

          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-300 font-medium">Auto Sync</div>
              <div class="text-gray-400 text-sm">Automatically sync data</div>
            </div>
            <button
              @click="toggleSetting('autoSync')"
              :class="[
                'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                appState.settings.autoSync ? 'bg-primary-600' : 'bg-gray-600'
              ]"
            >
              <span
                :class="[
                  'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                  appState.settings.autoSync ? 'translate-x-6' : 'translate-x-1'
                ]"
              ></span>
            </button>
          </div>

          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-300 font-medium">Offline Mode</div>
              <div class="text-gray-400 text-sm">Enable offline functionality</div>
            </div>
            <button
              @click="toggleSetting('offlineMode')"
              :class="[
                'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                appState.settings.offlineMode ? 'bg-primary-600' : 'bg-gray-600'
              ]"
            >
              <span
                :class="[
                  'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                  appState.settings.offlineMode ? 'translate-x-6' : 'translate-x-1'
                ]"
              ></span>
            </button>
          </div>

          <div class="flex items-center justify-between">
            <div>
              <div class="text-gray-300 font-medium">Biometric Auth</div>
              <div class="text-gray-400 text-sm">Use fingerprint/face ID</div>
            </div>
            <button
              @click="toggleSetting('biometricAuth')"
              :class="[
                'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                appState.settings.biometricAuth ? 'bg-primary-600' : 'bg-gray-600'
              ]"
            >
              <span
                :class="[
                  'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                  appState.settings.biometricAuth ? 'translate-x-6' : 'translate-x-1'
                ]"
              ></span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Cache Management -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-white">Cache Management</h2>
        <div class="flex items-center space-x-4">
          <div class="text-sm text-gray-300">
            Cache Size: <span class="font-medium text-white">{{ formatFileSize(cacheSize) }}</span>
          </div>
          <button
            @click="clearCache"
            :disabled="isClearingCache"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors text-sm"
          >
            <span v-if="isClearingCache" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Clearing...
            </span>
            <span v-else>Clear Cache</span>
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-700 rounded-lg p-4">
          <div class="text-sm text-gray-300 mb-1">App Data</div>
          <div class="text-lg font-semibold text-white">{{ formatFileSize(appState.cacheSize) }}</div>
        </div>
        <div class="bg-gray-700 rounded-lg p-4">
          <div class="text-sm text-gray-300 mb-1">Images</div>
          <div class="text-lg font-semibold text-white">{{ formatFileSize(cacheSize * 0.6) }}</div>
        </div>
        <div class="bg-gray-700 rounded-lg p-4">
          <div class="text-sm text-gray-300 mb-1">Documents</div>
          <div class="text-lg font-semibold text-white">{{ formatFileSize(cacheSize * 0.4) }}</div>
        </div>
      </div>
    </div>

    <!-- Deep Linking -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
      <h2 class="text-xl font-bold text-white mb-4">Deep Linking</h2>
      
      <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Deep Link URL</label>
            <input
              v-model="deepLinkUrl"
              type="text"
              placeholder="stargate://dashboard"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
            />
          </div>
          <div class="flex items-end">
            <button
              @click="openDeepLink"
              class="w-full px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors"
            >
              Open Deep Link
            </button>
          </div>
        </div>

        <div class="bg-gray-700 rounded-lg p-4">
          <h3 class="text-sm font-medium text-gray-300 mb-2">Available Deep Links</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Dashboard:</span>
              <code class="text-primary-400">stargate://dashboard</code>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Profile:</span>
              <code class="text-primary-400">stargate://profile</code>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Search:</span>
              <code class="text-primary-400">stargate://search?q=query</code>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-gray-400">Settings:</span>
              <code class="text-primary-400">stargate://settings</code>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { mobileAppService, type MobileDevice, type MobileAppState, type MobileNotification } from '../services/mobileAppService'

// Reactive data
const device = ref<MobileDevice>(mobileAppService.getDevice())
const appState = ref<MobileAppState>(mobileAppService.getState())
const notifications = ref<MobileNotification[]>(mobileAppService.getNotifications())
const cacheSize = ref(0)

// UI state
const isInstalling = ref(false)
const isClearingCache = ref(false)

// Form data
const testNotification = ref({
  title: 'Test Notification',
  body: 'This is a test notification from Stargate.ci'
})

const deepLinkUrl = ref('stargate://dashboard')

// Methods
const loadData = async () => {
  device.value = mobileAppService.getDevice()
  appState.value = mobileAppService.getState()
  notifications.value = mobileAppService.getNotifications()
  cacheSize.value = await mobileAppService.getCacheSize()
}

const installApp = async () => {
  try {
    isInstalling.value = true
    const success = await mobileAppService.installApp()
    if (success) {
      await loadData()
    }
  } catch (error) {
    console.error('Installation failed:', error)
  } finally {
    isInstalling.value = false
  }
}

const toggleNotifications = () => {
  mobileAppService.updateSettings({
    notifications: !appState.value.settings.notifications
  })
  appState.value = mobileAppService.getState()
}

const requestNotificationPermission = async () => {
  const granted = await mobileAppService.requestNotificationPermission()
  if (granted) {
    appState.value = mobileAppService.getState()
  }
}

const sendTestNotification = async () => {
  const success = await mobileAppService.sendNotification(
    testNotification.value.title,
    testNotification.value.body
  )
  if (success) {
    notifications.value = mobileAppService.getNotifications()
  }
}

const toggleSetting = (setting: keyof MobileAppState['settings']) => {
  const newValue = !appState.value.settings[setting]
  mobileAppService.updateSettings({ [setting]: newValue })
  appState.value = mobileAppService.getState()
}

const clearCache = async () => {
  try {
    isClearingCache.value = true
    const success = await mobileAppService.clearCache()
    if (success) {
      cacheSize.value = 0
      appState.value = mobileAppService.getState()
    }
  } catch (error) {
    console.error('Cache clearing failed:', error)
  } finally {
    isClearingCache.value = false
  }
}

const openDeepLink = () => {
  const success = mobileAppService.openDeepLink(deepLinkUrl.value)
  if (!success) {
    console.log('Deep link opened successfully')
  }
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString()
}

const formatTime = (dateString: string): string => {
  return new Date(dateString).toLocaleTimeString()
}

const formatFileSize = (bytes: number): string => {
  return mobileAppService.formatFileSize(bytes)
}

// Lifecycle
onMounted(() => {
  loadData()
})
</script>
