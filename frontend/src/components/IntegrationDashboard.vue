<template>
  <div class="integration-dashboard">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">Integration Dashboard</h1>
      <p class="text-gray-400">Manage external services and API integrations</p>
    </div>

    <!-- Integration Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Active Integrations</p>
            <p class="text-2xl font-bold text-white">{{ activeIntegrations }}</p>
          </div>
          <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">All Systems</span>
          <span class="text-gray-400 ml-2">Operational</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">API Calls Today</p>
            <p class="text-2xl font-bold text-white">{{ formatNumber(apiCallsToday) }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+{{ Math.floor(Math.random() * 20) + 5 }}%</span>
          <span class="text-gray-400 ml-2">from yesterday</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Data Sync Status</p>
            <p class="text-2xl font-bold text-white">{{ syncStatus }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">Last sync: 2 min ago</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Error Rate</p>
            <p class="text-2xl font-bold text-white">{{ errorRate }}%</p>
          </div>
          <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">-{{ Math.floor(Math.random() * 5) + 1 }}%</span>
          <span class="text-gray-400 ml-2">from last week</span>
        </div>
      </div>
    </div>

    <!-- Available Integrations -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">Available Integrations</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- CRM Integration -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-white">CRM System</h3>
                <p class="text-gray-400 text-sm">Customer Relationship Management</p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
              <span class="text-green-400 text-sm font-medium">Active</span>
            </div>
          </div>
          <p class="text-gray-300 text-sm mb-4">Manage contacts, leads, and sales pipeline with integrated CRM functionality.</p>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-400">
              <span class="font-medium">{{ crmStats.contacts }} contacts</span>
              <span class="mx-2">•</span>
              <span class="font-medium">{{ crmStats.leads }} leads</span>
            </div>
            <button
              @click="configureIntegration('crm')"
              class="text-primary-400 hover:text-primary-300 text-sm font-medium"
            >
              Configure
            </button>
          </div>
        </div>

        <!-- Email Marketing Integration -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-white">Email Marketing</h3>
                <p class="text-gray-400 text-sm">Campaign Management</p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
              <span class="text-green-400 text-sm font-medium">Active</span>
            </div>
          </div>
          <p class="text-gray-300 text-sm mb-4">Create and manage email campaigns, track performance, and grow your subscriber base.</p>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-400">
              <span class="font-medium">{{ emailStats.subscribers }} subscribers</span>
              <span class="mx-2">•</span>
              <span class="font-medium">{{ emailStats.campaigns }} campaigns</span>
            </div>
            <button
              @click="configureIntegration('email')"
              class="text-primary-400 hover:text-primary-300 text-sm font-medium"
            >
              Configure
            </button>
          </div>
        </div>

        <!-- Analytics Integration -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-white">Analytics</h3>
                <p class="text-gray-400 text-sm">Data & Insights</p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
              <span class="text-green-400 text-sm font-medium">Active</span>
            </div>
          </div>
          <p class="text-gray-300 text-sm mb-4">Track user behavior, performance metrics, and generate actionable insights.</p>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-400">
              <span class="font-medium">{{ analyticsStats.events }} events</span>
              <span class="mx-2">•</span>
              <span class="font-medium">{{ analyticsStats.sessions }} sessions</span>
            </div>
            <button
              @click="configureIntegration('analytics')"
              class="text-primary-400 hover:text-primary-300 text-sm font-medium"
            >
              Configure
            </button>
          </div>
        </div>

        <!-- Payment Integration -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-white">Payment Gateway</h3>
                <p class="text-gray-400 text-sm">Billing & Subscriptions</p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
              <span class="text-green-400 text-sm font-medium">Active</span>
            </div>
          </div>
          <p class="text-gray-300 text-sm mb-4">Process payments, manage subscriptions, and handle billing operations.</p>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-400">
              <span class="font-medium">{{ paymentStats.transactions }} transactions</span>
              <span class="mx-2">•</span>
              <span class="font-medium">${{ formatNumber(paymentStats.revenue) }}</span>
            </div>
            <button
              @click="configureIntegration('payment')"
              class="text-primary-400 hover:text-primary-300 text-sm font-medium"
            >
              Configure
            </button>
          </div>
        </div>

        <!-- Social Media Integration -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-pink-500/20 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V1a1 1 0 011-1h2a1 1 0 011 1v3m0 0h8m-8 0v16a1 1 0 001 1h6a1 1 0 001-1V4"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-white">Social Media</h3>
                <p class="text-gray-400 text-sm">Content & Engagement</p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
              <span class="text-yellow-400 text-sm font-medium">Pending</span>
            </div>
          </div>
          <p class="text-gray-300 text-sm mb-4">Connect with social media platforms for content sharing and engagement tracking.</p>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-400">
              <span class="font-medium">Not configured</span>
            </div>
            <button
              @click="configureIntegration('social')"
              class="text-primary-400 hover:text-primary-300 text-sm font-medium"
            >
              Setup
            </button>
          </div>
        </div>

        <!-- Cloud Storage Integration -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-white">Cloud Storage</h3>
                <p class="text-gray-400 text-sm">File Management</p>
              </div>
            </div>
            <div class="flex items-center">
              <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
              <span class="text-gray-400 text-sm font-medium">Inactive</span>
            </div>
          </div>
          <p class="text-gray-300 text-sm mb-4">Integrate with cloud storage providers for file management and backup.</p>
          <div class="flex justify-between items-center">
            <div class="text-sm text-gray-400">
              <span class="font-medium">Not configured</span>
            </div>
            <button
              @click="configureIntegration('storage')"
              class="text-primary-400 hover:text-primary-300 text-sm font-medium"
            >
              Setup
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- API Management -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">API Management</h2>
      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- API Keys -->
          <div>
            <h3 class="text-lg font-semibold text-white mb-4">API Keys</h3>
            <div class="space-y-3">
              <div
                v-for="apiKey in apiKeys"
                :key="apiKey.id"
                class="flex items-center justify-between p-3 bg-gray-700/50 rounded-lg"
              >
                <div>
                  <div class="text-white font-medium">{{ apiKey.name }}</div>
                  <div class="text-gray-400 text-sm">{{ apiKey.key.substring(0, 20) }}...</div>
                </div>
                <div class="flex items-center space-x-2">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="apiKey.active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                  >
                    {{ apiKey.active ? 'Active' : 'Inactive' }}
                  </span>
                  <button
                    @click="toggleApiKey(apiKey.id)"
                    class="text-gray-400 hover:text-white"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
            <button
              @click="showCreateApiKeyModal = true"
              class="mt-4 w-full flex items-center justify-center space-x-2 bg-primary-500/20 hover:bg-primary-500/30 text-primary-400 hover:text-primary-300 px-4 py-2 rounded-lg border border-primary-500/30 hover:border-primary-500/50 transition-all duration-200"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              <span>Create New API Key</span>
            </button>
          </div>

          <!-- API Usage -->
          <div>
            <h3 class="text-lg font-semibold text-white mb-4">API Usage</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-gray-300">Requests Today</span>
                <span class="text-white font-medium">{{ formatNumber(apiCallsToday) }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-300">Rate Limit</span>
                <span class="text-white font-medium">10,000 / hour</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-gray-300">Remaining</span>
                <span class="text-white font-medium">{{ formatNumber(10000 - apiCallsToday) }}</span>
              </div>
              <div class="w-full bg-gray-700 rounded-full h-2">
                <div
                  class="bg-primary-500 h-2 rounded-full"
                  :style="{ width: `${Math.min((apiCallsToday / 10000) * 100, 100)}%` }"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create API Key Modal -->
    <div
      v-if="showCreateApiKeyModal"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
      @click.self="showCreateApiKeyModal = false"
    >
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-white mb-4">Create New API Key</h3>
        
        <form @submit.prevent="createApiKey" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">API Key Name</label>
            <input
              v-model="newApiKey.name"
              type="text"
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Permissions</label>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  v-model="newApiKey.permissions"
                  type="checkbox"
                  value="read"
                  class="rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500"
                />
                <span class="ml-2 text-gray-300">Read</span>
              </label>
              <label class="flex items-center">
                <input
                  v-model="newApiKey.permissions"
                  type="checkbox"
                  value="write"
                  class="rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500"
                />
                <span class="ml-2 text-gray-300">Write</span>
              </label>
              <label class="flex items-center">
                <input
                  v-model="newApiKey.permissions"
                  type="checkbox"
                  value="admin"
                  class="rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500"
                />
                <span class="ml-2 text-gray-300">Admin</span>
              </label>
            </div>
          </div>
          
          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="showCreateApiKeyModal = false"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors"
            >
              Create API Key
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { crmService } from '../services/crmService'
import { emailMarketingService } from '../services/emailMarketingService'

// Reactive data
const activeIntegrations = ref(4)
const apiCallsToday = ref(2847)
const syncStatus = ref('Synced')
const errorRate = ref(0.2)

const crmStats = ref({
  contacts: 0,
  leads: 0
})

const emailStats = ref({
  subscribers: 0,
  campaigns: 0
})

const analyticsStats = ref({
  events: 0,
  sessions: 0
})

const paymentStats = ref({
  transactions: 0,
  revenue: 0
})

const apiKeys = ref([
  {
    id: '1',
    name: 'CRM Integration',
    key: 'sk_live_51H1234567890abcdef',
    active: true
  },
  {
    id: '2',
    name: 'Email Marketing',
    key: 'sk_live_51H0987654321fedcba',
    active: true
  },
  {
    id: '3',
    name: 'Analytics API',
    key: 'sk_live_51H1122334455aabbcc',
    active: false
  }
])

const showCreateApiKeyModal = ref(false)
const newApiKey = ref({
  name: '',
  permissions: [] as string[]
})

// Methods
const loadStats = async () => {
  try {
    const crmData = await crmService.getStats()
    crmStats.value = {
      contacts: crmData.totalContacts,
      leads: crmData.totalLeads
    }

    const emailData = await emailMarketingService.getStats()
    emailStats.value = {
      subscribers: emailData.totalSubscribers,
      campaigns: emailData.totalCampaigns
    }

    // Mock data for other services
    analyticsStats.value = {
      events: 15420,
      sessions: 3240
    }

    paymentStats.value = {
      transactions: 156,
      revenue: 45600
    }
  } catch (error) {
    console.error('Failed to load integration stats:', error)
  }
}

const configureIntegration = (type: string) => {
  console.log('Configure integration:', type)
  // Navigate to integration configuration
}

const toggleApiKey = (id: string) => {
  const apiKey = apiKeys.value.find(key => key.id === id)
  if (apiKey) {
    apiKey.active = !apiKey.active
  }
}

const createApiKey = () => {
  const newKey = {
    id: Date.now().toString(),
    name: newApiKey.value.name,
    key: `sk_live_${Math.random().toString(36).substring(2, 15)}`,
    active: true
  }
  
  apiKeys.value.push(newKey)
  showCreateApiKeyModal.value = false
  
  // Reset form
  newApiKey.value = {
    name: '',
    permissions: []
  }
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
})
</script>

<style scoped>
.integration-dashboard {
  @apply p-6;
}
</style>
