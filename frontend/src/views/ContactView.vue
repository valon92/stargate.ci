<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Contact stargate.ci Platform</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Connect with our platform team for inquiries about Stargate Project and Cristal Intelligence opportunities. We facilitate connections between companies and official project teams while maintaining legal compliance.
          </p>
        </div>
      </div>
    </section>

    <!-- Official Project Data Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Official Project Information</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Real-time data from official Stargate Project and Cristal Intelligence sources
          </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Stargate Project Data -->
          <div v-if="stargateData" class="card">
            <div class="flex items-center mb-6">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mr-4">
                <span class="text-2xl">🚀</span>
              </div>
              <h3 class="text-2xl font-bold text-white">Stargate Project</h3>
            </div>
            <p class="text-gray-300 mb-6">{{ stargateData.description }}</p>
            <div class="grid grid-cols-2 gap-4">
              <div class="p-4 bg-primary-500/10 rounded-lg">
                <div class="text-primary-400 font-semibold">Total Investment</div>
                <div class="text-white text-lg">{{ stargateData.totalInvestment }}</div>
              </div>
              <div class="p-4 bg-primary-500/10 rounded-lg">
                <div class="text-primary-400 font-semibold">Timeline</div>
                <div class="text-white text-lg">{{ stargateData.projectTimeline }}</div>
              </div>
              <div class="p-4 bg-primary-500/10 rounded-lg">
                <div class="text-primary-400 font-semibold">Jobs Created</div>
                <div class="text-white text-lg">{{ stargateData.jobs }}</div>
              </div>
              <div class="p-4 bg-primary-500/10 rounded-lg">
                <div class="text-primary-400 font-semibold">Partners</div>
                <div class="text-white text-lg">{{ stargateData.partners }}</div>
              </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">
              Source: <a :href="stargateData.source" target="_blank" class="text-primary-400 hover:text-primary-300 underline">Official Project</a>
            </div>
          </div>

          <!-- Cristal Intelligence Data -->
          <div v-if="cristalData" class="card">
            <div class="flex items-center mb-6">
              <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-lg flex items-center justify-center mr-4">
                <span class="text-2xl">💎</span>
              </div>
              <h3 class="text-2xl font-bold text-white">Cristal Intelligence</h3>
            </div>
            <p class="text-gray-300 mb-6">{{ cristalData.description }}</p>
            <div class="space-y-4">
              <div class="p-4 bg-secondary-500/10 rounded-lg">
                <div class="text-secondary-400 font-semibold mb-2">Partnership Investment</div>
                <div class="text-white">{{ cristalData.partnership.investment }}</div>
              </div>
              <div class="p-4 bg-secondary-500/10 rounded-lg">
                <div class="text-secondary-400 font-semibold mb-2">Key Features</div>
                <ul class="text-gray-300 text-sm space-y-1">
                  <li v-for="feature in cristalData.features.slice(0, 3)" :key="feature">• {{ feature }}</li>
                  <li class="text-gray-500">+{{ cristalData.features.length - 3 }} more features</li>
                </ul>
              </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">
              Source: <a :href="cristalData.source" target="_blank" class="text-secondary-400 hover:text-secondary-300 underline">Official Partnership</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Platform Contact Form</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Get in touch with our platform team for inquiries about Stargate and Cristal Intelligence opportunities
          </p>
        </div>
        
        <div class="card">
          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                  Full Name *
                </label>
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  required
                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  placeholder="Your full name"
                />
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                  Email Address *
                </label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  required
                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  placeholder="your.email@company.com"
                />
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="company" class="block text-sm font-medium text-gray-300 mb-2">
                  Company Name
                </label>
                <input
                  type="text"
                  id="company"
                  v-model="form.company"
                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                  placeholder="Your company name"
                />
              </div>
              <div>
                <label for="projectType" class="block text-sm font-medium text-gray-300 mb-2">
                  Project Interest
                </label>
                <select
                  id="projectType"
                  v-model="form.projectType"
                  class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                  <option value="">Select project type</option>
                  <option value="stargate">Stargate AI Infrastructure</option>
                  <option value="cristal">Cristal Intelligence Platform</option>
                  <option value="quantum">Quantum Cloud Computing</option>
                  <option value="general">General Inquiry</option>
                </select>
              </div>
            </div>
            
            <div>
              <label for="subject" class="block text-sm font-medium text-gray-300 mb-2">
                Subject *
              </label>
              <input
                type="text"
                id="subject"
                v-model="form.subject"
                required
                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                placeholder="Brief description of your inquiry"
              />
            </div>
            
            <div>
              <label for="message" class="block text-sm font-medium text-gray-300 mb-2">
                {{ $t('contact.form.message') }}
              </label>
              <textarea
                id="message"
                v-model="form.message"
                required
                rows="6"
                class="w-full px-4 py-3 bg-gray-700 border border-dark-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
                :placeholder="$t('contact.form.message')"
              ></textarea>
            </div>
            
            <div class="text-center">
              <button
                type="submit"
                :disabled="isSubmitting"
                class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="isSubmitting" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ $t('common.loading') }}
                </span>
                <span v-else>{{ $t('contact.form.submit') }}</span>
              </button>
            </div>
            
            <div v-if="submitMessage" class="text-center">
              <div v-if="submitSuccess" class="text-green-400">
                {{ $t('contact.form.success') }}
              </div>
              <div v-else class="text-red-400">
                {{ $t('contact.form.error') }}
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

    <!-- Additional Resources -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Platform Resources</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Explore our platform resources and connect with official project teams
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Platform Insights</h3>
            <p class="text-gray-400 mb-4">
              Comprehensive analysis and insights about Stargate Project and Cristal Intelligence
            </p>
            <RouterLink to="/insights" class="text-primary-400 hover:text-primary-300 transition-colors duration-200">
              Read Analysis
            </RouterLink>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Partnership Opportunities</h3>
            <p class="text-gray-400 mb-4">
              Connect directly with official project teams and explore investment opportunities
            </p>
            <RouterLink to="/partnership" class="text-primary-400 hover:text-primary-300 transition-colors duration-200">
              Explore Projects
            </RouterLink>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Platform FAQ</h3>
            <p class="text-gray-400 mb-4">
              Find answers to common questions about our platform and services
            </p>
            <RouterLink to="/faq" class="text-primary-400 hover:text-primary-300 transition-colors duration-200">
              View FAQ
            </RouterLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useHead } from '@vueuse/head'
import { stargateApi, type StargateProjectData, type CristalIntelligenceData } from '../services/stargateApi'

const { t } = useI18n()

const form = ref({
  name: '',
  email: '',
  company: '',
  projectType: '',
  subject: '',
  message: ''
})

// Official project data
const stargateData = ref<StargateProjectData | null>(null)
const cristalData = ref<CristalIntelligenceData | null>(null)
const isLoading = ref(true)

// Check for pre-filled project information
onMounted(async () => {
  // Load official data
  try {
    const [stargate, cristal] = await Promise.all([
      stargateApi.getStargateProjectData(),
      stargateApi.getCristalIntelligenceData()
    ])

    stargateData.value = stargate
    cristalData.value = cristal
  } catch (error) {
    console.error('Error loading official data:', error)
  } finally {
    isLoading.value = false
  }

  // Check for pre-filled project information
  const selectedProject = localStorage.getItem('selectedProject')
  if (selectedProject) {
    try {
      const project = JSON.parse(selectedProject)
      form.value.subject = `Inquiry about ${project.title}`
      form.value.message = project.message
      form.value.projectType = project.type || ''
      // Clear the stored project info
      localStorage.removeItem('selectedProject')
    } catch (error) {
      console.error('Error parsing selected project:', error)
    }
  }
})

const isSubmitting = ref(false)
const submitMessage = ref('')
const submitSuccess = ref(false)

const submitForm = async () => {
  isSubmitting.value = true
  submitMessage.value = ''
  
  try {
    // Simulate API call to platform contact service
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    // Reset form
    form.value = {
      name: '',
      email: '',
      company: '',
      projectType: '',
      subject: '',
      message: ''
    }
    
    submitSuccess.value = true
    submitMessage.value = 'Thank you for your inquiry! Our platform team will review your message and connect you with the appropriate project teams.'
  } catch (error) {
    submitSuccess.value = false
    submitMessage.value = 'There was an error submitting your inquiry. Please try again or contact us directly.'
  } finally {
    isSubmitting.value = false
  }
}

useHead({
  title: 'Contact - stargate.ci Platform',
  meta: [
    {
      name: 'description',
      content: 'Contact stargate.ci platform team for inquiries about Stargate Project and Cristal Intelligence opportunities. We facilitate connections between companies and official project teams.'
    }
  ]
})
</script>

<style scoped>
.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-6 hover:border-primary-500/50 transition-all duration-300;
}

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
