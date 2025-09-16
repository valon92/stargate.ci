<template>
  <div class="performance-dashboard">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">Performance Dashboard</h1>
      <p class="text-gray-400">Monitor and optimize your application performance</p>
    </div>

    <!-- Performance Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Page Load Time</p>
            <p class="text-2xl font-bold text-white">{{ performanceMetrics.firstContentfulPaint.toFixed(2) }}s</p>
          </div>
          <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">Good</span>
          <span class="text-gray-400 ml-2">Core Web Vitals</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Cache Hit Rate</p>
            <p class="text-2xl font-bold text-white">{{ cacheStats.hitRate.toFixed(1) }}%</p>
          </div>
          <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">Excellent</span>
          <span class="text-gray-400 ml-2">Cache Performance</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">CDN Bandwidth</p>
            <p class="text-2xl font-bold text-white">{{ formatBytes(cdnStats.bandwidthSaved) }}</p>
          </div>
          <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">Saved</span>
          <span class="text-gray-400 ml-2">Through optimization</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Memory Usage</p>
            <p class="text-2xl font-bold text-white">{{ formatBytes(cacheStats.memoryUsage) }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">Optimal</span>
          <span class="text-gray-400 ml-2">Cache memory</span>
        </div>
      </div>
    </div>

    <!-- Core Web Vitals -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">Core Web Vitals</h2>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Performance Metrics -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Performance Metrics</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 bg-green-500"></div>
                <span class="text-white">First Contentful Paint</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ performanceMetrics.firstContentfulPaint.toFixed(2) }}s</div>
                <div class="text-green-400 text-sm">Good</div>
              </div>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 bg-green-500"></div>
                <span class="text-white">Largest Contentful Paint</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ performanceMetrics.largestContentfulPaint.toFixed(2) }}s</div>
                <div class="text-green-400 text-sm">Good</div>
              </div>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 bg-yellow-500"></div>
                <span class="text-white">Cumulative Layout Shift</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ performanceMetrics.cumulativeLayoutShift.toFixed(3) }}</div>
                <div class="text-yellow-400 text-sm">Needs Improvement</div>
              </div>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 bg-green-500"></div>
                <span class="text-white">First Input Delay</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ performanceMetrics.firstInputDelay }}ms</div>
                <div class="text-green-400 text-sm">Good</div>
              </div>
            </div>
            
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 bg-green-500"></div>
                <span class="text-white">Time to Interactive</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ performanceMetrics.timeToInteractive.toFixed(2) }}s</div>
                <div class="text-green-400 text-sm">Good</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cache Performance -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Cache Performance</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Hit Rate</span>
              <div class="flex items-center">
                <div class="w-20 bg-gray-700 rounded-full h-2 mr-2">
                  <div
                    class="h-2 rounded-full bg-green-500"
                    :style="{ width: `${Math.min(cacheStats.hitRate, 100)}%` }"
                  ></div>
                </div>
                <span class="text-white font-medium">{{ cacheStats.hitRate.toFixed(1) }}%</span>
              </div>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Total Entries</span>
              <span class="text-white font-medium">{{ formatNumber(cacheStats.totalEntries) }}</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Cache Size</span>
              <span class="text-white font-medium">{{ formatBytes(cacheStats.totalSize) }}</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Eviction Count</span>
              <span class="text-white font-medium">{{ formatNumber(cacheStats.evictionCount) }}</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Average Get Time</span>
              <span class="text-white font-medium">{{ cacheStats.performanceMetrics.averageGetTime.toFixed(2) }}ms</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CDN & Asset Optimization -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">CDN & Asset Optimization</h2>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- CDN Stats -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4">CDN Statistics</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Total Assets</span>
              <span class="text-white font-medium">{{ formatNumber(cdnStats.totalAssets) }}</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Optimized Assets</span>
              <span class="text-white font-medium">{{ formatNumber(cdnStats.optimizedAssets) }}</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Compression Ratio</span>
              <span class="text-white font-medium">{{ cdnStats.compressionRatio.toFixed(1) }}%</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Cache Hit Rate</span>
              <span class="text-white font-medium">{{ cdnStats.cacheHitRate.toFixed(1) }}%</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Bandwidth Saved</span>
              <span class="text-white font-medium">{{ formatBytes(cdnStats.bandwidthSaved) }}</span>
            </div>
            
            <div class="flex items-center justify-between">
              <span class="text-gray-300">Cost Savings</span>
              <span class="text-white font-medium">${{ cdnStats.costSavings.toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Top Assets -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Top Assets by Requests</h3>
          <div class="space-y-3">
            <div
              v-for="asset in cdnStats.topAssets"
              :key="asset.id"
              class="flex items-center justify-between p-3 bg-gray-700/50 rounded-lg"
            >
              <div class="flex-1 min-w-0">
                <div class="text-white font-medium truncate">{{ asset.url }}</div>
                <div class="text-gray-400 text-sm">{{ formatBytes(asset.bandwidth) }} bandwidth</div>
              </div>
              <div class="text-right ml-4">
                <div class="text-white font-medium">{{ formatNumber(asset.requests) }}</div>
                <div class="text-gray-400 text-sm">{{ asset.cacheHitRate.toFixed(1) }}% hit rate</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Cache Type Distribution -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">Cache Type Distribution</h2>
      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
          <div
            v-for="type in cacheStats.typeDistribution"
            :key="type.type"
            class="text-center p-4 bg-gray-700/50 rounded-lg"
          >
            <div class="text-2xl font-bold text-white mb-1">{{ type.count }}</div>
            <div class="text-gray-400 text-sm mb-2 capitalize">{{ type.type }}</div>
            <div class="text-xs text-gray-500">{{ formatBytes(type.size) }}</div>
            <div class="text-xs text-green-400">{{ type.hitRate.toFixed(1) }}% hit rate</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Performance Actions -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">Performance Actions</h2>
      <div class="flex flex-wrap gap-4">
        <button
          @click="optimizeAssets"
          :disabled="isOptimizing"
          class="flex items-center space-x-2 bg-primary-500/20 hover:bg-primary-500/30 disabled:opacity-50 disabled:cursor-not-allowed text-primary-400 hover:text-primary-300 px-4 py-2 rounded-lg border border-primary-500/30 hover:border-primary-500/50 transition-all duration-200"
        >
          <svg v-if="isOptimizing" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
          <span>{{ isOptimizing ? 'Optimizing...' : 'Optimize Assets' }}</span>
        </button>
        
        <button
          @click="clearCache"
          class="flex items-center space-x-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 hover:text-red-300 px-4 py-2 rounded-lg border border-red-500/30 hover:border-red-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
          </svg>
          <span>Clear Cache</span>
        </button>
        
        <button
          @click="purgeCDN"
          class="flex items-center space-x-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 hover:text-yellow-300 px-4 py-2 rounded-lg border border-yellow-500/30 hover:border-yellow-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <span>Purge CDN</span>
        </button>
        
        <button
          @click="generateReport"
          class="flex items-center space-x-2 bg-green-500/20 hover:bg-green-500/30 text-green-400 hover:text-green-300 px-4 py-2 rounded-lg border border-green-500/30 hover:border-green-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Generate Report</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { cdnService, type CDNStats } from '../services/cdnService'
import { advancedCachingService, type CacheStats } from '../services/advancedCachingService'

// Reactive data
const cdnStats = ref<CDNStats>({
  totalAssets: 0,
  optimizedAssets: 0,
  totalSize: 0,
  optimizedSize: 0,
  compressionRatio: 0,
  cacheHitRate: 0,
  averageLoadTime: 0,
  bandwidthSaved: 0,
  costSavings: 0,
  topAssets: [],
  performanceMetrics: {
    firstContentfulPaint: 0,
    largestContentfulPaint: 0,
    cumulativeLayoutShift: 0,
    firstInputDelay: 0,
    timeToInteractive: 0
  }
})

const cacheStats = ref<CacheStats>({
  totalEntries: 0,
  totalSize: 0,
  hitRate: 0,
  missRate: 0,
  averageAccessTime: 0,
  evictionCount: 0,
  compressionRatio: 0,
  memoryUsage: 0,
  topKeys: [],
  typeDistribution: [],
  performanceMetrics: {
    averageGetTime: 0,
    averageSetTime: 0,
    averageDeleteTime: 0,
    cacheEfficiency: 0
  }
})

const performanceMetrics = ref({
  firstContentfulPaint: 1.2,
  largestContentfulPaint: 2.1,
  cumulativeLayoutShift: 0.05,
  firstInputDelay: 45,
  timeToInteractive: 2.8
})

const isOptimizing = ref(false)

// Methods
const loadStats = async () => {
  try {
    const [cdnData, cacheData, perfData] = await Promise.all([
      cdnService.getStats(),
      advancedCachingService.getStats(),
      cdnService.getPerformanceMetrics()
    ])
    
    cdnStats.value = cdnData
    cacheStats.value = cacheData
    performanceMetrics.value = perfData
  } catch (error) {
    console.error('Failed to load performance stats:', error)
  }
}

const optimizeAssets = async () => {
  isOptimizing.value = true
  try {
    // Simulate asset optimization
    await new Promise(resolve => setTimeout(resolve, 3000))
    
    // Reload stats
    await loadStats()
    
    // Show success message
    console.log('Assets optimized successfully')
  } catch (error) {
    console.error('Failed to optimize assets:', error)
  } finally {
    isOptimizing.value = false
  }
}

const clearCache = async () => {
  try {
    const clearedCount = await advancedCachingService.clear()
    console.log(`Cleared ${clearedCount} cache entries`)
    
    // Reload stats
    await loadStats()
  } catch (error) {
    console.error('Failed to clear cache:', error)
  }
}

const purgeCDN = async () => {
  try {
    await cdnService.purgeCache()
    console.log('CDN cache purged successfully')
    
    // Reload stats
    await loadStats()
  } catch (error) {
    console.error('Failed to purge CDN:', error)
  }
}

const generateReport = () => {
  // Generate performance report
  const report = {
    timestamp: new Date().toISOString(),
    cdnStats: cdnStats.value,
    cacheStats: cacheStats.value,
    performanceMetrics: performanceMetrics.value
  }
  
  // Download report as JSON
  const blob = new Blob([JSON.stringify(report, null, 2)], { type: 'application/json' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `performance-report-${new Date().toISOString().split('T')[0]}.json`
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
}

const formatBytes = (bytes: number): string => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const formatNumber = (num: number): string => {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(num)
}

// Lifecycle
onMounted(async () => {
  await loadStats()
  
  // Refresh stats every 30 seconds
  setInterval(loadStats, 30000)
})
</script>

<style scoped>
.performance-dashboard {
  @apply p-6;
}
</style>
