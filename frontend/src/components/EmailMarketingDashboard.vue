<template>
  <div class="email-marketing-dashboard">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">Email Marketing Dashboard</h1>
      <p class="text-gray-400">Manage your email campaigns and subscriber base</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Total Subscribers</p>
            <p class="text-2xl font-bold text-white">{{ stats.totalSubscribers }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+{{ Math.floor(Math.random() * 20) + 5 }}%</span>
          <span class="text-gray-400 ml-2">from last month</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Open Rate</p>
            <p class="text-2xl font-bold text-white">{{ stats.averageOpenRate.toFixed(1) }}%</p>
          </div>
          <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+{{ Math.floor(Math.random() * 5) + 1 }}%</span>
          <span class="text-gray-400 ml-2">from last month</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Click Rate</p>
            <p class="text-2xl font-bold text-white">{{ stats.averageClickRate.toFixed(1) }}%</p>
          </div>
          <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+{{ Math.floor(Math.random() * 3) + 1 }}%</span>
          <span class="text-gray-400 ml-2">from last month</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Emails Sent</p>
            <p class="text-2xl font-bold text-white">{{ formatNumber(stats.totalEmailsSent) }}</p>
          </div>
          <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+{{ Math.floor(Math.random() * 15) + 10 }}%</span>
          <span class="text-gray-400 ml-2">from last month</span>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">Quick Actions</h2>
      <div class="flex flex-wrap gap-4">
        <button
          @click="showCreateCampaignModal = true"
          class="flex items-center space-x-2 bg-primary-500/20 hover:bg-primary-500/30 text-primary-400 hover:text-primary-300 px-4 py-2 rounded-lg border border-primary-500/30 hover:border-primary-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Create Campaign</span>
        </button>
        
        <button
          @click="showAddSubscriberModal = true"
          class="flex items-center space-x-2 bg-green-500/20 hover:bg-green-500/30 text-green-400 hover:text-green-300 px-4 py-2 rounded-lg border border-green-500/30 hover:border-green-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
          </svg>
          <span>Add Subscriber</span>
        </button>
        
        <button
          @click="showCreateTemplateModal = true"
          class="flex items-center space-x-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg border border-blue-500/30 hover:border-blue-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Create Template</span>
        </button>
      </div>
    </div>

    <!-- Recent Campaigns -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-white">Recent Campaigns</h2>
        <button
          @click="loadCampaigns"
          class="text-gray-400 hover:text-white text-sm"
        >
          View All
        </button>
      </div>
      
      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-700/50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Campaign</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Sent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Open Rate</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Click Rate</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50">
              <tr v-for="campaign in recentCampaigns" :key="campaign.id" class="hover:bg-gray-700/30">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div>
                    <div class="text-sm font-medium text-white">{{ campaign.name }}</div>
                    <div class="text-sm text-gray-400">{{ campaign.subject }}</div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="getTypeClass(campaign.type)"
                  >
                    {{ campaign.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="getStatusClass(campaign.status)"
                  >
                    {{ campaign.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                  {{ formatNumber(campaign.stats.sent) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-16 bg-gray-700 rounded-full h-2 mr-2">
                      <div
                        class="h-2 rounded-full bg-green-500"
                        :style="{ width: `${Math.min(campaign.stats.openRate, 100)}%` }"
                      ></div>
                    </div>
                    <span class="text-sm text-white">{{ campaign.stats.openRate.toFixed(1) }}%</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-16 bg-gray-700 rounded-full h-2 mr-2">
                      <div
                        class="h-2 rounded-full bg-blue-500"
                        :style="{ width: `${Math.min(campaign.stats.clickRate, 100)}%` }"
                      ></div>
                    </div>
                    <span class="text-sm text-white">{{ campaign.stats.clickRate.toFixed(1) }}%</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="viewCampaign(campaign.id)"
                    class="text-primary-400 hover:text-primary-300 mr-3"
                  >
                    View
                  </button>
                  <button
                    @click="editCampaign(campaign.id)"
                    class="text-gray-400 hover:text-white"
                  >
                    Edit
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Analytics Overview -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">Analytics Overview</h2>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Performing Campaigns -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Top Performing Campaigns</h3>
          <div class="space-y-4">
            <div
              v-for="campaign in stats.topPerformingCampaigns"
              :key="campaign.id"
              class="flex items-center justify-between"
            >
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 bg-primary-500"></div>
                <span class="text-white">{{ campaign.name }}</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ campaign.openRate.toFixed(1) }}%</div>
                <div class="text-gray-400 text-sm">{{ formatNumber(campaign.sent) }} sent</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Source Distribution -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Subscriber Sources</h3>
          <div class="space-y-4">
            <div
              v-for="source in stats.sourceDistribution"
              :key="source.source"
              class="flex items-center justify-between"
            >
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 bg-green-500"></div>
                <span class="text-white capitalize">{{ source.source }}</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ source.count }}</div>
                <div class="text-gray-400 text-sm">{{ source.percentage.toFixed(1) }}%</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Campaign Modal -->
    <div
      v-if="showCreateCampaignModal"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
      @click.self="showCreateCampaignModal = false"
    >
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-semibold text-white mb-4">Create New Campaign</h3>
        
        <form @submit.prevent="createCampaign" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Campaign Name</label>
              <input
                v-model="newCampaign.name"
                type="text"
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
              <select
                v-model="newCampaign.type"
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option value="newsletter">Newsletter</option>
                <option value="promotional">Promotional</option>
                <option value="transactional">Transactional</option>
                <option value="welcome">Welcome</option>
                <option value="follow-up">Follow-up</option>
                <option value="announcement">Announcement</option>
              </select>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Subject Line</label>
            <input
              v-model="newCampaign.subject"
              type="text"
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Preview Text</label>
            <input
              v-model="newCampaign.previewText"
              type="text"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
            <textarea
              v-model="newCampaign.content"
              required
              rows="6"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            ></textarea>
          </div>
          
          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="showCreateCampaignModal = false"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors"
            >
              Create Campaign
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Add Subscriber Modal -->
    <div
      v-if="showAddSubscriberModal"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
      @click.self="showAddSubscriberModal = false"
    >
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-white mb-4">Add New Subscriber</h3>
        
        <form @submit.prevent="addSubscriber" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
            <input
              v-model="newSubscriber.email"
              type="email"
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">First Name</label>
              <input
                v-model="newSubscriber.firstName"
                type="text"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">Last Name</label>
              <input
                v-model="newSubscriber.lastName"
                type="text"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
              />
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Source</label>
            <select
              v-model="newSubscriber.source"
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
              <option value="website">Website</option>
              <option value="referral">Referral</option>
              <option value="social">Social Media</option>
              <option value="import">Import</option>
              <option value="manual">Manual</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="showAddSubscriberModal = false"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors"
            >
              Add Subscriber
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { emailMarketingService, type EmailCampaign, type EmailStats, type Subscriber } from '../services/emailMarketingService'

// Reactive data
const stats = ref<EmailStats>({
  totalSubscribers: 0,
  activeSubscribers: 0,
  unsubscribedSubscribers: 0,
  bouncedSubscribers: 0,
  totalCampaigns: 0,
  sentCampaigns: 0,
  totalEmailsSent: 0,
  averageOpenRate: 0,
  averageClickRate: 0,
  averageBounceRate: 0,
  averageUnsubscribeRate: 0,
  thisMonthEmailsSent: 0,
  thisMonthOpenRate: 0,
  thisMonthClickRate: 0,
  topPerformingCampaigns: [],
  subscriberGrowth: [],
  sourceDistribution: []
})

const recentCampaigns = ref<EmailCampaign[]>([])
const showCreateCampaignModal = ref(false)
const showAddSubscriberModal = ref(false)
const showCreateTemplateModal = ref(false)

const newCampaign = ref({
  name: '',
  subject: '',
  previewText: '',
  content: '',
  type: 'newsletter' as const,
  status: 'draft' as const,
  audience: {
    segments: [] as string[],
    filters: {
      tags: [] as string[],
      source: [] as string[]
    }
  },
  createdBy: 'admin'
})

const newSubscriber = ref({
  email: '',
  firstName: '',
  lastName: '',
  source: 'website' as const,
  status: 'active' as const,
  tags: [] as string[],
  customFields: {} as Record<string, any>
})

// Methods
const loadStats = async () => {
  try {
    stats.value = await emailMarketingService.getStats()
  } catch (error) {
    console.error('Failed to load email marketing stats:', error)
  }
}

const loadCampaigns = async () => {
  try {
    const campaigns = await emailMarketingService.getCampaigns()
    recentCampaigns.value = campaigns.slice(0, 5) // Show only recent 5
  } catch (error) {
    console.error('Failed to load campaigns:', error)
  }
}

const createCampaign = async () => {
  try {
    await emailMarketingService.createCampaign(newCampaign.value)
    showCreateCampaignModal.value = false
    
    // Reset form
    newCampaign.value = {
      name: '',
      subject: '',
      previewText: '',
      content: '',
      type: 'newsletter',
      status: 'draft',
      audience: {
        segments: [],
        filters: {
          tags: [],
          source: []
        }
      },
      createdBy: 'admin'
    }
    
    // Reload data
    await loadStats()
    await loadCampaigns()
  } catch (error) {
    console.error('Failed to create campaign:', error)
  }
}

const addSubscriber = async () => {
  try {
    await emailMarketingService.addSubscriber(newSubscriber.value)
    showAddSubscriberModal.value = false
    
    // Reset form
    newSubscriber.value = {
      email: '',
      firstName: '',
      lastName: '',
      source: 'website',
      status: 'active',
      tags: [],
      customFields: {}
    }
    
    // Reload data
    await loadStats()
  } catch (error) {
    console.error('Failed to add subscriber:', error)
  }
}

const viewCampaign = (id: string) => {
  // Navigate to campaign detail view
  console.log('View campaign:', id)
}

const editCampaign = (id: string) => {
  // Navigate to campaign edit view
  console.log('Edit campaign:', id)
}

const formatNumber = (num: number): string => {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(num)
}

const getTypeClass = (type: string): string => {
  const classes = {
    'newsletter': 'bg-blue-100 text-blue-800',
    'promotional': 'bg-green-100 text-green-800',
    'transactional': 'bg-yellow-100 text-yellow-800',
    'welcome': 'bg-purple-100 text-purple-800',
    'follow-up': 'bg-orange-100 text-orange-800',
    'announcement': 'bg-red-100 text-red-800'
  }
  return classes[type as keyof typeof classes] || 'bg-gray-100 text-gray-800'
}

const getStatusClass = (status: string): string => {
  const classes = {
    'draft': 'bg-gray-100 text-gray-800',
    'scheduled': 'bg-yellow-100 text-yellow-800',
    'sending': 'bg-blue-100 text-blue-800',
    'sent': 'bg-green-100 text-green-800',
    'paused': 'bg-orange-100 text-orange-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800'
}

// Lifecycle
onMounted(async () => {
  await loadStats()
  await loadCampaigns()
})
</script>

<style scoped>
.email-marketing-dashboard {
  @apply p-6;
}
</style>
