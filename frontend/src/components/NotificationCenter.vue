<template>
  <div class="notification-center">
    <!-- Notification Bell -->
    <div class="relative">
      <button
        @click="toggleNotifications"
        class="relative p-2 text-gray-400 hover:text-white transition-colors duration-200"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.5 19.5L9 15H4.5v4.5zM15 7h5l-5-5v5zM4.5 4.5L9 9H4.5V4.5z"></path>
        </svg>
        
        <!-- Notification Badge -->
        <span
          v-if="unreadCount > 0"
          class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
        >
          {{ unreadCount > 99 ? '99+' : unreadCount }}
        </span>
      </button>

      <!-- Notifications Dropdown -->
      <div
        v-if="showNotifications"
        class="absolute right-0 mt-2 w-80 bg-gray-800 border border-gray-700 rounded-lg shadow-lg z-50"
      >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-700">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Notifications</h3>
            <button
              @click="markAllAsRead"
              class="text-sm text-primary-400 hover:text-primary-300"
            >
              Mark all read
            </button>
          </div>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
          <div v-if="notifications.length === 0" class="px-4 py-8 text-center text-gray-400">
            No notifications yet
          </div>
          
          <div
            v-for="notification in notifications"
            :key="notification.id"
            class="px-4 py-3 border-b border-gray-700/50 hover:bg-gray-700/30 transition-colors duration-200"
            :class="{ 'bg-gray-700/20': !notification.read }"
          >
            <div class="flex items-start space-x-3">
              <!-- Notification Icon -->
              <div class="flex-shrink-0">
                <div
                  class="w-8 h-8 rounded-full flex items-center justify-center"
                  :class="getNotificationIconClass(notification.type)"
                >
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path :d="getNotificationIconPath(notification.type)"></path>
                  </svg>
                </div>
              </div>

              <!-- Notification Content -->
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white">{{ notification.title }}</p>
                <p class="text-sm text-gray-300 mt-1">{{ notification.message }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ formatTime(notification.created_at) }}</p>
              </div>

              <!-- Mark as Read Button -->
              <button
                v-if="!notification.read"
                @click="markAsRead(notification.id)"
                class="flex-shrink-0 text-gray-400 hover:text-white"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-700">
          <button
            @click="clearAllNotifications"
            class="w-full text-sm text-gray-400 hover:text-white text-center"
          >
            Clear all notifications
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { websocketService, type NotificationData } from '../services/websocketService'

// Reactive data
const showNotifications = ref(false)
const notifications = ref<NotificationData[]>([])

// Computed properties
const unreadCount = computed(() => {
  return notifications.value.filter(n => !n.read).length
})

// Methods
const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value
}

const markAsRead = (id: string) => {
  const notification = notifications.value.find(n => n.id === id)
  if (notification) {
    notification.read = true
  }
}

const markAllAsRead = () => {
  notifications.value.forEach(notification => {
    notification.read = true
  })
}

const clearAllNotifications = () => {
  notifications.value = []
}

const getNotificationIconClass = (type: string) => {
  switch (type) {
    case 'success':
      return 'bg-green-500/20 text-green-400'
    case 'warning':
      return 'bg-yellow-500/20 text-yellow-400'
    case 'error':
      return 'bg-red-500/20 text-red-400'
    default:
      return 'bg-blue-500/20 text-blue-400'
  }
}

const getNotificationIconPath = (type: string) => {
  switch (type) {
    case 'success':
      return 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'
    case 'warning':
      return 'M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z'
    case 'error':
      return 'M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z'
    default:
      return 'M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z'
  }
}

const formatTime = (timestamp: string) => {
  const date = new Date(timestamp)
  const now = new Date()
  const diff = now.getTime() - date.getTime()
  
  if (diff < 60000) { // Less than 1 minute
    return 'Just now'
  } else if (diff < 3600000) { // Less than 1 hour
    const minutes = Math.floor(diff / 60000)
    return `${minutes}m ago`
  } else if (diff < 86400000) { // Less than 1 day
    const hours = Math.floor(diff / 3600000)
    return `${hours}h ago`
  } else {
    return date.toLocaleDateString()
  }
}

// WebSocket event handlers
const handleNotification = (notification: NotificationData) => {
  notifications.value.unshift(notification)
  
  // Keep only last 50 notifications
  if (notifications.value.length > 50) {
    notifications.value = notifications.value.slice(0, 50)
  }
}

// Lifecycle
onMounted(() => {
  websocketService.subscribeToNotifications(handleNotification)
  
  // Close notifications when clicking outside
  document.addEventListener('click', (event) => {
    const target = event.target as HTMLElement
    if (!target.closest('.notification-center')) {
      showNotifications.value = false
    }
  })
})

onUnmounted(() => {
  websocketService.off('notification', handleNotification)
})
</script>

<style scoped>
.notification-center {
  position: relative;
}
</style>
