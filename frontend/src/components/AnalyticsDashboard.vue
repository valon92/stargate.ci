<template>
  <div class="analytics-dashboard">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Analytics Dashboard</h2>
        <p class="text-gray-400">Monitor user behavior, performance, and platform metrics</p>
      </div>

      <!-- Key Metrics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-500/20 rounded-lg">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Total Users</p>
              <p class="text-2xl font-bold text-white">{{ dashboardData.totalUsers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-500/20 rounded-lg">
              <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Active Users</p>
              <p class="text-2xl font-bold text-white">{{ dashboardData.activeUsers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-500/20 rounded-lg">
              <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Page Views</p>
              <p class="text-2xl font-bold text-white">{{ dashboardData.pageViews }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-500/20 rounded-lg">
              <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-400">Avg Session</p>
              <p class="text-2xl font-bold text-white">{{ formatDuration(dashboardData.averageSessionDuration) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Performance Metrics -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Performance Chart -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Performance Metrics</h3>
          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-gray-300">Page Load Time</span>
              <span class="text-white font-medium">{{ dashboardData.performanceMetrics.pageLoadTime }}ms</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-300">DOM Content Loaded</span>
              <span class="text-white font-medium">{{ dashboardData.performanceMetrics.domContentLoaded }}ms</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-300">First Contentful Paint</span>
              <span class="text-white font-medium">{{ dashboardData.performanceMetrics.firstContentfulPaint }}ms</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-300">Largest Contentful Paint</span>
              <span class="text-white font-medium">{{ dashboardData.performanceMetrics.largestContentfulPaint }}ms</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-300">Bundle Size</span>
              <span class="text-white font-medium">{{ formatBytes(dashboardData.performanceMetrics.bundleSize) }}</span>
            </div>
          </div>
        </div>

        <!-- Error Rate -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Error Rate</h3>
          <div class="text-center">
            <div class="text-4xl font-bold text-red-400 mb-2">{{ dashboardData.errorRate.toFixed(2) }}%</div>
            <p class="text-gray-400">Error rate over time</p>
          </div>
        </div>
      </div>

      <!-- Top Pages and Device Breakdown -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top Pages -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Top Pages</h3>
          <div class="space-y-3">
            <div v-for="page in dashboardData.topPages" :key="page.page" class="flex justify-between items-center">
              <span class="text-gray-300">{{ page.page }}</span>
              <span class="text-white font-medium">{{ page.views }} views</span>
            </div>
          </div>
        </div>

        <!-- Device Breakdown -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Device Breakdown</h3>
          <div class="space-y-3">
            <div v-for="(count, device) in dashboardData.deviceBreakdown" :key="device" class="flex justify-between items-center">
              <span class="text-gray-300 capitalize">{{ device }}</span>
              <span class="text-white font-medium">{{ count }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Browser and OS Breakdown -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Browser Breakdown -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Browser Breakdown</h3>
          <div class="space-y-3">
            <div v-for="(count, browser) in dashboardData.browserBreakdown" :key="browser" class="flex justify-between items-center">
              <span class="text-gray-300">{{ browser }}</span>
              <span class="text-white font-medium">{{ count }}</span>
            </div>
          </div>
        </div>

        <!-- OS Breakdown -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">OS Breakdown</h3>
          <div class="space-y-3">
            <div v-for="(count, os) in dashboardData.osBreakdown" :key="os" class="flex justify-between items-center">
              <span class="text-gray-300">{{ os }}</span>
              <span class="text-white font-medium">{{ count }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Real-time Events -->
      <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Recent Events</h3>
        <div class="space-y-3">
          <div v-for="event in recentEvents" :key="event.id" class="flex justify-between items-center p-3 bg-gray-700/30 rounded-lg">
            <div>
              <span class="text-white font-medium">{{ event.name }}</span>
              <span class="text-gray-400 text-sm ml-2">{{ event.type }}</span>
            </div>
            <div class="text-right">
              <div class="text-gray-300 text-sm">{{ formatTime(event.timestamp) }}</div>
              <div class="text-gray-400 text-xs">{{ event.properties.page || 'N/A' }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { analyticsService, type AnalyticsDashboard, type AnalyticsEvent } from '../services/analyticsService'

// Reactive data
const dashboardData = ref<AnalyticsDashboard>({
  totalUsers: 0,
  activeUsers: 0,
  pageViews: 0,
  sessions: 0,
  averageSessionDuration: 0,
  bounceRate: 0,
  topPages: [],
  topReferrers: [],
  deviceBreakdown: {},
  browserBreakdown: {},
  osBreakdown: {},
  performanceMetrics: {
    pageLoadTime: 0,
    domContentLoaded: 0,
    firstContentfulPaint: 0,
    largestContentfulPaint: 0,
    firstInputDelay: 0,
    cumulativeLayoutShift: 0,
    timeToInteractive: 0,
    bundleSize: 0
  },
  errorRate: 0,
  conversionRate: 0
})

const recentEvents = ref<AnalyticsEvent[]>([])
const isLoading = ref(true)

// Methods
const loadDashboardData = async () => {
  try {
    isLoading.value = true
    dashboardData.value = await analyticsService.getDashboardData()
    
    // Load recent events
    const events = analyticsService.getEvents()
    recentEvents.value = events.slice(-10).reverse()
  } catch (error) {
    console.error('Failed to load dashboard data:', error)
  } finally {
    isLoading.value = false
  }
}

const formatDuration = (ms: number): string => {
  const seconds = Math.floor(ms / 1000)
  const minutes = Math.floor(seconds / 60)
  const hours = Math.floor(minutes / 60)
  
  if (hours > 0) {
    return `${hours}h ${minutes % 60}m`
  } else if (minutes > 0) {
    return `${minutes}m ${seconds % 60}s`
  } else {
    return `${seconds}s`
  }
}

const formatBytes = (bytes: number): string => {
  if (bytes === 0) return '0 B'
  
  const k = 1024
  const sizes = ['B', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const formatTime = (timestamp: string): string => {
  return new Date(timestamp).toLocaleTimeString()
}

// Lifecycle
onMounted(async () => {
  await loadDashboardData()
  
  // Refresh data every 30 seconds
  const refreshInterval = setInterval(loadDashboardData, 30000)
  
  onUnmounted(() => {
    clearInterval(refreshInterval)
  })
})
</script>

<style scoped>
.analytics-dashboard {
  @apply p-6;
}
</style>
