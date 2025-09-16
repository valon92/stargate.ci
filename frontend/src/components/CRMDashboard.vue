<template>
  <div class="crm-dashboard">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">CRM Dashboard</h1>
      <p class="text-gray-400">Manage your customer relationships and sales pipeline</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Total Contacts</p>
            <p class="text-2xl font-bold text-white">{{ stats.totalContacts }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+12%</span>
          <span class="text-gray-400 ml-2">from last month</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Total Leads</p>
            <p class="text-2xl font-bold text-white">{{ stats.totalLeads }}</p>
          </div>
          <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+8%</span>
          <span class="text-gray-400 ml-2">from last month</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Pipeline Value</p>
            <p class="text-2xl font-bold text-white">${{ formatNumber(stats.pipelineValue) }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+15%</span>
          <span class="text-gray-400 ml-2">from last month</span>
        </div>
      </div>

      <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-400 text-sm font-medium">Conversion Rate</p>
            <p class="text-2xl font-bold text-white">{{ stats.conversionRate.toFixed(1) }}%</p>
          </div>
          <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
          <span class="text-green-400 font-medium">+3%</span>
          <span class="text-gray-400 ml-2">from last month</span>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">Quick Actions</h2>
      <div class="flex flex-wrap gap-4">
        <button
          @click="showAddContactModal = true"
          class="flex items-center space-x-2 bg-primary-500/20 hover:bg-primary-500/30 text-primary-400 hover:text-primary-300 px-4 py-2 rounded-lg border border-primary-500/30 hover:border-primary-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          <span>Add Contact</span>
        </button>
        
        <button
          @click="showAddLeadModal = true"
          class="flex items-center space-x-2 bg-green-500/20 hover:bg-green-500/30 text-green-400 hover:text-green-300 px-4 py-2 rounded-lg border border-green-500/30 hover:border-green-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
          </svg>
          <span>Add Lead</span>
        </button>
        
        <button
          @click="showAddActivityModal = true"
          class="flex items-center space-x-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg border border-blue-500/30 hover:border-blue-500/50 transition-all duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          <span>Log Activity</span>
        </button>
      </div>
    </div>

    <!-- Recent Contacts -->
    <div class="mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-white">Recent Contacts</h2>
        <button
          @click="loadContacts"
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
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Company</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Lead Score</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Last Contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700/50">
              <tr v-for="contact in recentContacts" :key="contact.id" class="hover:bg-gray-700/30">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                      <span class="text-white font-medium text-sm">
                        {{ contact.firstName.charAt(0) }}{{ contact.lastName.charAt(0) }}
                      </span>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-white">
                        {{ contact.firstName }} {{ contact.lastName }}
                      </div>
                      <div class="text-sm text-gray-400">{{ contact.email }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-white">{{ contact.company || 'N/A' }}</div>
                  <div class="text-sm text-gray-400">{{ contact.position || 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="getStatusClass(contact.status)"
                  >
                    {{ contact.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-16 bg-gray-700 rounded-full h-2 mr-2">
                      <div
                        class="h-2 rounded-full"
                        :class="getScoreClass(contact.leadScore)"
                        :style="{ width: `${contact.leadScore}%` }"
                      ></div>
                    </div>
                    <span class="text-sm text-white">{{ contact.leadScore }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                  {{ formatDate(contact.lastContactDate) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="viewContact(contact.id)"
                    class="text-primary-400 hover:text-primary-300 mr-3"
                  >
                    View
                  </button>
                  <button
                    @click="editContact(contact.id)"
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

    <!-- Pipeline Overview -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold text-white mb-4">Sales Pipeline</h2>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stage Distribution -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Stage Distribution</h3>
          <div class="space-y-4">
            <div
              v-for="stage in stats.stageDistribution"
              :key="stage.stage"
              class="flex items-center justify-between"
            >
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3" :class="getStageColor(stage.stage)"></div>
                <span class="text-white capitalize">{{ stage.stage.replace('-', ' ') }}</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ stage.count }}</div>
                <div class="text-gray-400 text-sm">${{ formatNumber(stage.value) }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Sources -->
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Top Sources</h3>
          <div class="space-y-4">
            <div
              v-for="source in stats.topSources"
              :key="source.source"
              class="flex items-center justify-between"
            >
              <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 bg-primary-500"></div>
                <span class="text-white capitalize">{{ source.source }}</span>
              </div>
              <div class="text-right">
                <div class="text-white font-medium">{{ source.count }}</div>
                <div class="text-gray-400 text-sm">${{ formatNumber(source.value) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Contact Modal -->
    <div
      v-if="showAddContactModal"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
      @click.self="showAddContactModal = false"
    >
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-white mb-4">Add New Contact</h3>
        
        <form @submit.prevent="addContact" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">First Name</label>
            <input
              v-model="newContact.firstName"
              type="text"
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Last Name</label>
            <input
              v-model="newContact.lastName"
              type="text"
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
            <input
              v-model="newContact.email"
              type="email"
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Company</label>
            <input
              v-model="newContact.company"
              type="text"
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Source</label>
            <select
              v-model="newContact.source"
              required
              class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
              <option value="website">Website</option>
              <option value="referral">Referral</option>
              <option value="social">Social Media</option>
              <option value="email">Email</option>
              <option value="phone">Phone</option>
              <option value="event">Event</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="showAddContactModal = false"
              class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors"
            >
              Add Contact
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { crmService, type Contact, type CRMStats } from '../services/crmService'

// Reactive data
const stats = ref<CRMStats>({
  totalContacts: 0,
  totalLeads: 0,
  totalDeals: 0,
  totalValue: 0,
  conversionRate: 0,
  averageDealSize: 0,
  pipelineValue: 0,
  thisMonthDeals: 0,
  thisMonthValue: 0,
  topSources: [],
  stageDistribution: []
})

const recentContacts = ref<Contact[]>([])
const showAddContactModal = ref(false)
const showAddLeadModal = ref(false)
const showAddActivityModal = ref(false)

const newContact = ref({
  firstName: '',
  lastName: '',
  email: '',
  company: '',
  position: '',
  phone: '',
  source: 'website' as const,
  status: 'lead' as const,
  tags: [] as string[],
  notes: ''
})

// Methods
const loadStats = async () => {
  try {
    stats.value = await crmService.getStats()
  } catch (error) {
    console.error('Failed to load CRM stats:', error)
  }
}

const loadContacts = async () => {
  try {
    const contacts = await crmService.getContacts()
    recentContacts.value = contacts.slice(0, 5) // Show only recent 5
  } catch (error) {
    console.error('Failed to load contacts:', error)
  }
}

const addContact = async () => {
  try {
    await crmService.addContact(newContact.value)
    showAddContactModal.value = false
    
    // Reset form
    newContact.value = {
      firstName: '',
      lastName: '',
      email: '',
      company: '',
      position: '',
      phone: '',
      source: 'website',
      status: 'lead',
      tags: [],
      notes: ''
    }
    
    // Reload data
    await loadStats()
    await loadContacts()
  } catch (error) {
    console.error('Failed to add contact:', error)
  }
}

const viewContact = (id: string) => {
  // Navigate to contact detail view
  console.log('View contact:', id)
}

const editContact = (id: string) => {
  // Navigate to contact edit view
  console.log('Edit contact:', id)
}

const formatNumber = (num: number): string => {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(num)
}

const formatDate = (dateString?: string): string => {
  if (!dateString) return 'Never'
  return new Date(dateString).toLocaleDateString()
}

const getStatusClass = (status: string): string => {
  const classes = {
    'lead': 'bg-blue-100 text-blue-800',
    'prospect': 'bg-yellow-100 text-yellow-800',
    'customer': 'bg-green-100 text-green-800',
    'inactive': 'bg-gray-100 text-gray-800'
  }
  return classes[status as keyof typeof classes] || 'bg-gray-100 text-gray-800'
}

const getScoreClass = (score: number): string => {
  if (score >= 80) return 'bg-green-500'
  if (score >= 60) return 'bg-yellow-500'
  if (score >= 40) return 'bg-orange-500'
  return 'bg-red-500'
}

const getStageColor = (stage: string): string => {
  const colors = {
    'new': 'bg-blue-500',
    'qualified': 'bg-yellow-500',
    'proposal': 'bg-orange-500',
    'negotiation': 'bg-purple-500',
    'closed-won': 'bg-green-500',
    'closed-lost': 'bg-red-500'
  }
  return colors[stage as keyof typeof colors] || 'bg-gray-500'
}

// Lifecycle
onMounted(async () => {
  await loadStats()
  await loadContacts()
})
</script>

<style scoped>
.crm-dashboard {
  @apply p-6;
}
</style>
