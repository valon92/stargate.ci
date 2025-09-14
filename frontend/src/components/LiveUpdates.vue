<template>
  <div class="live-updates">
    <!-- Live Updates Indicator -->
    <div class="flex items-center space-x-2 text-sm text-gray-400">
      <div class="flex items-center space-x-1">
        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
        <span>Live</span>
      </div>
      <span v-if="lastUpdate">Last update: {{ formatTime(lastUpdate) }}</span>
    </div>

    <!-- Updates List (if expanded) -->
    <div v-if="showUpdates" class="mt-4 space-y-2">
      <div
        v-for="update in recentUpdates"
        :key="update.timestamp"
        class="p-3 bg-gray-800/50 border border-gray-700 rounded-lg"
      >
        <div class="flex items-start space-x-3">
          <!-- Update Icon -->
          <div class="flex-shrink-0">
            <div
              class="w-6 h-6 rounded-full flex items-center justify-center"
              :class="getUpdateIconClass(update.type)"
            >
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path :d="getUpdateIconPath(update.action)"></path>
              </svg>
            </div>
          </div>

          <!-- Update Content -->
          <div class="flex-1 min-w-0">
            <p class="text-sm text-white">
              <span class="font-medium">{{ update.type }}</span>
              <span class="text-gray-400">was {{ update.action }}</span>
            </p>
            <p v-if="update.data.title" class="text-sm text-gray-300 mt-1">
              {{ update.data.title }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ formatTime(update.timestamp) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Toggle Button -->
    <button
      @click="toggleUpdates"
      class="mt-2 text-xs text-primary-400 hover:text-primary-300"
    >
      {{ showUpdates ? 'Hide' : 'Show' }} recent updates
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { websocketService, type LiveUpdateData } from '../services/websocketService'

// Reactive data
const showUpdates = ref(false)
const recentUpdates = ref<LiveUpdateData[]>([])
const lastUpdate = ref<string | null>(null)

// Methods
const toggleUpdates = () => {
  showUpdates.value = !showUpdates.value
}

const getUpdateIconClass = (type: string) => {
  switch (type) {
    case 'article':
      return 'bg-blue-500/20 text-blue-400'
    case 'faq':
      return 'bg-green-500/20 text-green-400'
    case 'user':
      return 'bg-purple-500/20 text-purple-400'
    case 'system':
      return 'bg-orange-500/20 text-orange-400'
    default:
      return 'bg-gray-500/20 text-gray-400'
  }
}

const getUpdateIconPath = (action: string) => {
  switch (action) {
    case 'created':
      return 'M10 6a4 4 0 100 8 4 4 0 000-8zM8 10a2 2 0 114 0 2 2 0 01-4 0z'
    case 'updated':
      return 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
    case 'deleted':
      return 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'
    default:
      return 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
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
const handleLiveUpdate = (update: LiveUpdateData) => {
  recentUpdates.value.unshift(update)
  lastUpdate.value = update.timestamp
  
  // Keep only last 10 updates
  if (recentUpdates.value.length > 10) {
    recentUpdates.value = recentUpdates.value.slice(0, 10)
  }
}

// Lifecycle
onMounted(() => {
  websocketService.subscribeToLiveUpdates(handleLiveUpdate)
})

onUnmounted(() => {
  websocketService.off('live_update', handleLiveUpdate)
})
</script>

<style scoped>
.live-updates {
  @apply p-4 bg-gray-800/30 border border-gray-700 rounded-lg;
}
</style>
