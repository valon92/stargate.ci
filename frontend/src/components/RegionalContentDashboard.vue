<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-white mb-2">Regional Content Management</h1>
      <p class="text-gray-300">Manage localized content and regional adaptations for global markets</p>
    </div>

    <!-- Current Region Info -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-white">Current Region Settings</h2>
        <div class="flex space-x-3">
          <select
            v-model="selectedRegion"
            @change="changeRegion"
            class="px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm"
          >
            <option value="us">United States</option>
            <option value="eu">Europe</option>
            <option value="asia">Asia Pacific</option>
            <option value="global">Global</option>
          </select>
          <select
            v-model="selectedLanguage"
            @change="changeLanguage"
            class="px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white text-sm"
          >
            <option value="en">English</option>
            <option value="fr">Français</option>
            <option value="de">Deutsch</option>
            <option value="es">Español</option>
            <option value="it">Italiano</option>
            <option value="ar">العربية</option>
            <option value="pt">Português</option>
            <option value="ru">Русский</option>
            <option value="ja">日本語</option>
            <option value="zh">中文</option>
          </select>
        </div>
      </div>

      <div v-if="currentSettings" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
          <h3 class="text-lg font-semibold text-white mb-2">Regional Settings</h3>
          <div class="space-y-2 text-sm">
            <p class="text-gray-300"><span class="font-medium">Region:</span> {{ currentSettings.region.toUpperCase() }}</p>
            <p class="text-gray-300"><span class="font-medium">Language:</span> {{ currentSettings.language.toUpperCase() }}</p>
            <p class="text-gray-300"><span class="font-medium">Currency:</span> {{ currentSettings.currency }}</p>
            <p class="text-gray-300"><span class="font-medium">Timezone:</span> {{ currentSettings.timezone }}</p>
            <p class="text-gray-300"><span class="font-medium">Date Format:</span> {{ currentSettings.dateFormat }}</p>
          </div>
        </div>

        <div>
          <h3 class="text-lg font-semibold text-white mb-2">Business Hours</h3>
          <div class="space-y-2 text-sm">
            <p class="text-gray-300"><span class="font-medium">Status:</span> 
              <span :class="isBusinessHours ? 'text-green-400' : 'text-red-400'">
                {{ isBusinessHours ? 'Open' : 'Closed' }}
              </span>
            </p>
            <p class="text-gray-300"><span class="font-medium">Working Days:</span> {{ currentSettings.businessHours.workingDays.join(', ') }}</p>
            <p class="text-gray-300"><span class="font-medium">Hours:</span> {{ currentSettings.businessHours.workingHours.start }} - {{ currentSettings.businessHours.workingHours.end }}</p>
            <p class="text-gray-300"><span class="font-medium">Timezone:</span> {{ currentSettings.businessHours.timezone }}</p>
          </div>
        </div>

        <div>
          <h3 class="text-lg font-semibold text-white mb-2">Contact Information</h3>
          <div class="space-y-2 text-sm">
            <p v-if="currentSettings.contactInfo.phone" class="text-gray-300">
              <span class="font-medium">Phone:</span> {{ currentSettings.contactInfo.phone }}
            </p>
            <p v-if="currentSettings.contactInfo.email" class="text-gray-300">
              <span class="font-medium">Email:</span> {{ currentSettings.contactInfo.email }}
            </p>
            <p v-if="currentSettings.contactInfo.address" class="text-gray-300">
              <span class="font-medium">Address:</span> {{ currentSettings.contactInfo.address }}
            </p>
            <p class="text-gray-300">
              <span class="font-medium">GDPR Compliant:</span> 
              <span :class="currentSettings.legal.gdprCompliant ? 'text-green-400' : 'text-red-400'">
                {{ currentSettings.legal.gdprCompliant ? 'Yes' : 'No' }}
              </span>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Business Information -->
    <div v-if="currentBusinessInfo" class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <h2 class="text-xl font-bold text-white mb-4">Business Information</h2>
      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
          <h3 class="text-lg font-semibold text-white mb-3">{{ currentBusinessInfo.businessName }}</h3>
          <p class="text-gray-300 mb-4">{{ currentBusinessInfo.description }}</p>
          
          <div class="space-y-3">
            <div>
              <h4 class="text-sm font-medium text-gray-300 mb-2">Services</h4>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="service in currentBusinessInfo.services"
                  :key="service"
                  class="px-2 py-1 bg-primary-500/20 text-primary-300 rounded text-xs"
                >
                  {{ service }}
                </span>
              </div>
            </div>

            <div>
              <h4 class="text-sm font-medium text-gray-300 mb-2">Industries</h4>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="industry in currentBusinessInfo.industries"
                  :key="industry"
                  class="px-2 py-1 bg-blue-500/20 text-blue-300 rounded text-xs"
                >
                  {{ industry }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <div>
          <div class="space-y-4">
            <div>
              <h4 class="text-sm font-medium text-gray-300 mb-2">Certifications</h4>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="cert in currentBusinessInfo.certifications"
                  :key="cert"
                  class="px-2 py-1 bg-green-500/20 text-green-300 rounded text-xs"
                >
                  {{ cert }}
                </span>
              </div>
            </div>

            <div>
              <h4 class="text-sm font-medium text-gray-300 mb-2">Partnerships</h4>
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="partnership in currentBusinessInfo.partnerships"
                  :key="partnership"
                  class="px-2 py-1 bg-purple-500/20 text-purple-300 rounded text-xs"
                >
                  {{ partnership }}
                </span>
              </div>
            </div>

            <div>
              <h4 class="text-sm font-medium text-gray-300 mb-2">Contact Methods</h4>
              <div class="space-y-1">
                <p
                  v-for="method in currentBusinessInfo.contactMethods"
                  :key="method"
                  class="text-sm text-gray-400"
                >
                  {{ method }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Regional Content -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6 mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-white">Regional Content</h2>
        <button
          @click="showAddContentModal = true"
          class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
        >
          Add Content
        </button>
      </div>

      <div class="space-y-4">
        <div
          v-for="content in regionalContent"
          :key="content.id"
          class="bg-gray-700 rounded-lg p-4 border border-gray-600"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <div class="flex items-center space-x-3 mb-2">
                <h3 class="text-lg font-semibold text-white">{{ content.title }}</h3>
                <span class="px-2 py-1 bg-gray-600 text-gray-300 rounded text-xs">
                  {{ content.contentType }}
                </span>
                <span class="px-2 py-1 bg-primary-500/20 text-primary-300 rounded text-xs">
                  {{ content.region.toUpperCase() }}
                </span>
              </div>
              <p class="text-gray-300 text-sm mb-3">{{ content.content }}</p>
              <div class="flex items-center space-x-4 text-xs text-gray-400">
                <span>Updated: {{ formatDate(content.metadata.lastUpdated) }}</span>
                <span>Author: {{ content.metadata.author }}</span>
                <span>Version: {{ content.metadata.version }}</span>
              </div>
            </div>
            <div class="flex space-x-2 ml-4">
              <button
                @click="editContent(content)"
                class="text-primary-400 hover:text-primary-300 text-sm"
              >
                Edit
              </button>
              <button
                @click="deleteContent(content.id)"
                class="text-red-400 hover:text-red-300 text-sm"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Cultural Adaptations -->
    <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-600/50 p-6">
      <h2 class="text-xl font-bold text-white mb-4">Cultural Adaptations</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h3 class="text-lg font-semibold text-white mb-3">Cultural Notes</h3>
          <div class="space-y-2">
            <div
              v-for="note in culturalNotes"
              :key="note"
              class="flex items-start space-x-2"
            >
              <svg class="h-4 w-4 text-yellow-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
              <p class="text-gray-300 text-sm">{{ note }}</p>
            </div>
          </div>
        </div>

        <div>
          <h3 class="text-lg font-semibold text-white mb-3">Formatting Examples</h3>
          <div class="space-y-3 text-sm">
            <div>
              <span class="font-medium text-gray-300">Currency:</span>
              <span class="text-gray-400 ml-2">{{ formatCurrency(1234.56) }}</span>
            </div>
            <div>
              <span class="font-medium text-gray-300">Date:</span>
              <span class="text-gray-400 ml-2">{{ formatDate(new Date().toISOString()) }}</span>
            </div>
            <div>
              <span class="font-medium text-gray-300">Number:</span>
              <span class="text-gray-400 ml-2">{{ formatNumber(1234567.89) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Content Modal -->
    <div v-if="showAddContentModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showAddContentModal = false"></div>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-white">Add Regional Content</h3>
              <button @click="showAddContentModal = false" class="text-gray-400 hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <form @submit.prevent="addContent">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Region</label>
                  <select
                    v-model="newContent.region"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                  >
                    <option value="us">United States</option>
                    <option value="eu">Europe</option>
                    <option value="asia">Asia Pacific</option>
                    <option value="global">Global</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Content Type</label>
                  <select
                    v-model="newContent.contentType"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:ring-primary-500 focus:border-primary-500"
                  >
                    <option value="business">Business</option>
                    <option value="cultural">Cultural</option>
                    <option value="legal">Legal</option>
                    <option value="technical">Technical</option>
                    <option value="marketing">Marketing</option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                  <input
                    v-model="newContent.title"
                    type="text"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
                  <textarea
                    v-model="newContent.content"
                    rows="4"
                    required
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500"
                  ></textarea>
                </div>
              </div>
              
              <div class="mt-6 flex justify-end space-x-3">
                <button
                  type="button"
                  @click="showAddContentModal = false"
                  class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="isProcessing"
                  class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors"
                >
                  <span v-if="isProcessing">Adding...</span>
                  <span v-else>Add Content</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { 
  regionalContentService, 
  type RegionalContent, 
  type RegionalSettings, 
  type LocalizedBusinessInfo 
} from '../services/regionalContentService'

const { t } = useI18n()

// Reactive data
const selectedRegion = ref('us')
const selectedLanguage = ref('en')
const currentSettings = ref<RegionalSettings | null>(null)
const currentBusinessInfo = ref<LocalizedBusinessInfo | null>(null)
const regionalContent = ref<RegionalContent[]>([])
const culturalNotes = ref<string[]>([])

// UI state
const showAddContentModal = ref(false)
const isProcessing = ref(false)

// Form data
const newContent = ref({
  region: 'us',
  language: 'en',
  contentType: 'business' as 'business' | 'cultural' | 'legal' | 'technical' | 'marketing',
  title: '',
  content: ''
})

// Computed
const isBusinessHours = computed(() => {
  return regionalContentService.isBusinessHours(selectedRegion.value)
})

// Methods
const loadData = async () => {
  try {
    currentSettings.value = await regionalContentService.getRegionalSettings(selectedRegion.value)
    currentBusinessInfo.value = await regionalContentService.getBusinessInfo(selectedRegion.value)
    regionalContent.value = await regionalContentService.getRegionalContent(selectedRegion.value)
    culturalNotes.value = regionalContentService.getCulturalNotes(selectedRegion.value)
  } catch (error) {
    console.error('Error loading data:', error)
  }
}

const changeRegion = async () => {
  regionalContentService.setRegion(selectedRegion.value)
  await loadData()
}

const changeLanguage = () => {
  regionalContentService.setLanguage(selectedLanguage.value)
  // In a real app, this would trigger a language change
  console.log('Language changed to:', selectedLanguage.value)
}

const addContent = async () => {
  try {
    isProcessing.value = true
    
    const contentData = {
      ...newContent.value,
      metadata: {
        lastUpdated: new Date().toISOString(),
        author: 'Admin',
        version: '1.0',
        tags: [newContent.value.contentType, newContent.value.region]
      },
      adaptations: {
        currency: currentSettings.value?.currency,
        timezone: currentSettings.value?.timezone,
        dateFormat: currentSettings.value?.dateFormat,
        numberFormat: currentSettings.value?.numberFormat
      }
    }

    await regionalContentService.addRegionalContent(contentData)
    
    // Reset form
    newContent.value = {
      region: 'us',
      language: 'en',
      contentType: 'business',
      title: '',
      content: ''
    }

    showAddContentModal.value = false
    await loadData()
  } catch (error) {
    console.error('Error adding content:', error)
  } finally {
    isProcessing.value = false
  }
}

const editContent = (content: RegionalContent) => {
  // TODO: Implement edit functionality
  console.log('Edit content:', content)
}

const deleteContent = async (contentId: string) => {
  if (confirm('Are you sure you want to delete this content?')) {
    // TODO: Implement delete functionality
    console.log('Delete content:', contentId)
  }
}

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleDateString()
}

const formatCurrency = (amount: number): string => {
  return regionalContentService.formatCurrency(amount, selectedRegion.value)
}

const formatNumber = (number: number): string => {
  return regionalContentService.formatNumber(number, selectedRegion.value)
}

// Lifecycle
onMounted(() => {
  selectedRegion.value = regionalContentService.getCurrentRegion()
  selectedLanguage.value = regionalContentService.getCurrentLanguage()
  loadData()
})
</script>
