<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- Header -->
    <div class="bg-gray-800 border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold gradient-text">API Integration Dashboard</h1>
            <p class="mt-2 text-gray-400">Manage and monitor all external API integrations</p>
          </div>
          <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2">
              <div :class="[
                'w-3 h-3 rounded-full',
                healthStatus.overall === 'healthy' ? 'bg-green-500' :
                healthStatus.overall === 'degraded' ? 'bg-yellow-500' : 'bg-red-500'
              ]"></div>
              <span class="text-sm font-medium capitalize">{{ healthStatus.overall }}</span>
            </div>
            <button
              @click="refreshStatus"
              :disabled="loading"
              class="px-4 py-2 bg-primary-500 hover:bg-primary-600 disabled:opacity-50 rounded-lg transition-colors duration-200"
            >
              <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              <span v-else>Refresh</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Health Overview -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div
          v-for="service in healthStatus.services"
          :key="service.name"
          class="bg-gray-800 rounded-lg p-6 border border-gray-700"
        >
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">{{ service.name }}</h3>
            <div :class="[
              'w-3 h-3 rounded-full',
              service.active ? 'bg-green-500' : 'bg-red-500'
            ]"></div>
          </div>
          
          <div class="space-y-2">
            <div class="flex justify-between text-sm">
              <span class="text-gray-400">Status:</span>
              <span :class="service.active ? 'text-green-400' : 'text-red-400'">
                {{ service.active ? 'Active' : 'Inactive' }}
              </span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-400">Configured:</span>
              <span :class="service.configured ? 'text-green-400' : 'text-red-400'">
                {{ service.configured ? 'Yes' : 'No' }}
              </span>
            </div>
            <div class="text-sm text-gray-400">
              Last checked: {{ formatDate(service.lastChecked) }}
            </div>
          </div>

          <div class="mt-4">
            <div class="text-xs text-gray-500 mb-2">Features:</div>
            <div class="flex flex-wrap gap-1">
              <span
                v-for="feature in service.features.slice(0, 3)"
                :key="feature"
                class="px-2 py-1 bg-gray-700 text-xs rounded"
              >
                {{ feature }}
              </span>
              <span
                v-if="service.features.length > 3"
                class="px-2 py-1 bg-gray-700 text-xs rounded"
              >
                +{{ service.features.length - 3 }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- API Usage Statistics -->
      <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 mb-8">
        <h2 class="text-xl font-semibold mb-4">API Usage Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div
            v-for="usage in apiUsage"
            :key="usage.service"
            class="bg-gray-700 rounded-lg p-4"
          >
            <h3 class="font-medium mb-2">{{ usage.service }}</h3>
            <div class="space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-400">Requests:</span>
                <span>{{ usage.requests }}/{{ usage.limit }}</span>
              </div>
              <div class="w-full bg-gray-600 rounded-full h-2">
                <div
                  class="bg-primary-500 h-2 rounded-full"
                  :style="{ width: `${(usage.requests / usage.limit) * 100}%` }"
                ></div>
              </div>
              <div class="text-xs text-gray-400">
                Resets: {{ formatDate(usage.resetTime) }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recommendations -->
      <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 mb-8">
        <h2 class="text-xl font-semibold mb-4">Recommendations</h2>
        <div class="space-y-4">
          <div
            v-for="recommendation in recommendations"
            :key="recommendation.service"
            class="flex items-start space-x-3 p-4 bg-gray-700 rounded-lg"
          >
            <div :class="[
              'w-2 h-2 rounded-full mt-2 flex-shrink-0',
              recommendation.priority === 'high' ? 'bg-red-500' :
              recommendation.priority === 'medium' ? 'bg-yellow-500' : 'bg-blue-500'
            ]"></div>
            <div class="flex-1">
              <h3 class="font-medium">{{ recommendation.service }}</h3>
              <p class="text-sm text-gray-400 mt-1">{{ recommendation.recommendation }}</p>
            </div>
            <span :class="[
              'px-2 py-1 text-xs rounded',
              recommendation.priority === 'high' ? 'bg-red-500/20 text-red-400' :
              recommendation.priority === 'medium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-blue-500/20 text-blue-400'
            ]">
              {{ recommendation.priority }}
            </span>
          </div>
        </div>
      </div>

      <!-- Cost Estimation -->
      <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
        <h2 class="text-xl font-semibold mb-4">Monthly Cost Estimation</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div
            v-for="cost in serviceCosts"
            :key="cost.service"
            class="bg-gray-700 rounded-lg p-4"
          >
            <h3 class="font-medium mb-2">{{ cost.service }}</h3>
            <div class="text-2xl font-bold text-primary-400 mb-2">
              ${{ cost.estimatedMonthlyCost }}
            </div>
            <div class="text-xs text-gray-400">
              {{ cost.costBreakdown }}
            </div>
          </div>
        </div>
        <div class="mt-6 p-4 bg-gray-700 rounded-lg">
          <div class="flex justify-between items-center">
            <span class="text-lg font-semibold">Total Estimated Monthly Cost:</span>
            <span class="text-2xl font-bold text-primary-400">
              ${{ serviceCosts.reduce((sum, cost) => sum + cost.estimatedMonthlyCost, 0) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { apiIntegrationManager } from '../services/apiIntegrationManager'

// Reactive data
const loading = ref(false)
const healthStatus = ref({
  overall: 'unhealthy' as 'healthy' | 'degraded' | 'unhealthy',
  services: [] as any[],
  lastUpdated: ''
})
const apiUsage = ref([] as any[])
const recommendations = ref([] as any[])
const serviceCosts = ref([] as any[])

// Methods
const loadDashboardData = async () => {
  loading.value = true
  try {
    const data = await apiIntegrationManager.getDashboardData()
    healthStatus.value = data.health
    apiUsage.value = data.usage
    recommendations.value = data.recommendations
    serviceCosts.value = data.costs
  } catch (error) {
    console.error('Failed to load dashboard data:', error)
  } finally {
    loading.value = false
  }
}

const refreshStatus = async () => {
  await loadDashboardData()
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleString()
}

// Lifecycle
onMounted(() => {
  loadDashboardData()
  
  // Start health monitoring
  apiIntegrationManager.startHealthMonitoring(60000) // Check every minute
})
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #0ea5e9, #a855f7);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>