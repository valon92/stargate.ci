<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">About stargate.ci</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            The bridge between companies and the revolutionary Stargate Project and Cristal Intelligence initiatives. We facilitate, integrate, and preserve authorship while guiding organizations toward ethical AI implementation.
          </p>
        </div>
      </div>
    </section>

    <!-- Platform Mission Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
              <span class="gradient-text">Our Platform Mission</span>
            </h2>
            <p class="text-lg text-gray-300 leading-relaxed mb-6">
              {{ platformMission }}
            </p>
            <div class="space-y-4">
              <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <p class="text-gray-300">Facilitate connections between companies and official projects</p>
              </div>
              <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <p class="text-gray-300">Provide accurate information from official sources</p>
              </div>
              <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <p class="text-gray-300">Preserve intellectual property rights and legal compliance</p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="p-6">
              <h3 class="text-xl font-bold text-white mb-4">Domain Meaning</h3>
              <div class="space-y-4">
                <div class="p-4 bg-primary-500/10 rounded-lg border border-primary-500/20">
                  <h4 class="font-semibold text-primary-400 mb-2">stargate</h4>
                  <p class="text-gray-300 text-sm">The revolutionary project from OpenAI, SoftBank, and ARM - a $500B investment in next-generation AI computing infrastructure.</p>
                </div>
                <div class="p-4 bg-secondary-500/10 rounded-lg border border-secondary-500/20">
                  <h4 class="font-semibold text-secondary-400 mb-2">.ci</h4>
                  <p class="text-gray-300 text-sm">Cristal Intelligence - Advanced Enterprise AI that securely integrates systems and data, customized for each company.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Platform Vision Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div class="order-2 lg:order-1">
            <div class="card">
              <div class="p-6">
                <h3 class="text-xl font-bold text-white mb-4">Official Project Data</h3>
                <div class="space-y-4">
                  <div v-if="stargateData" class="p-4 bg-primary-500/10 rounded-lg border border-primary-500/20">
                    <h4 class="font-semibold text-primary-400 mb-2">Stargate Project</h4>
                    <p class="text-gray-300 text-sm mb-2">{{ stargateData.description }}</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                      <div><span class="text-primary-400">Investment:</span> {{ stargateData.totalInvestment }}</div>
                      <div><span class="text-primary-400">Timeline:</span> {{ stargateData.projectTimeline }}</div>
                      <div><span class="text-primary-400">Jobs:</span> {{ stargateData.jobs }}</div>
                      <div><span class="text-primary-400">Partners:</span> {{ stargateData.partners }}</div>
                    </div>
                  </div>
                  <div v-if="cristalData" class="p-4 bg-secondary-500/10 rounded-lg border border-secondary-500/20">
                    <h4 class="font-semibold text-secondary-400 mb-2">Cristal Intelligence</h4>
                    <p class="text-gray-300 text-sm mb-2">{{ cristalData.description }}</p>
                    <div class="text-xs">
                      <div><span class="text-secondary-400">Investment:</span> {{ cristalData.partnership.investment }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="order-1 lg:order-2">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">
              <span class="gradient-text">Our Platform Vision</span>
            </h2>
            <p class="text-lg text-gray-300 leading-relaxed mb-6">
              {{ platformVision }}
            </p>
            <div class="space-y-4">
              <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <p class="text-gray-300">Premier platform connecting businesses with AI future</p>
              </div>
              <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <p class="text-gray-300">Fostering innovation while maintaining transparency</p>
              </div>
              <div class="flex items-start space-x-3">
                <div class="w-6 h-6 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                  <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </div>
                <p class="text-gray-300">Legal compliance and intellectual property respect</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Platform Values Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Our Platform Values</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            The principles that guide our platform operations and ensure ethical, transparent, and compliant service delivery
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(value, index) in platformValues" :key="index" class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">{{ value }}</h3>
            <p class="text-gray-400">
              {{ getValueDescription(value) }}
            </p>
          </div>
        </div>
      </div>
    </section>


    <!-- Legal Disclaimer Section -->
    <section class="py-24 bg-gradient-to-r from-yellow-900/20 to-orange-900/20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card border-yellow-500/20">
          <div class="flex items-start space-x-4">
            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-semibold mb-4 text-white">Legal Disclaimer</h3>
              <p class="text-gray-300 leading-relaxed mb-4">
                stargate.ci is an independent platform that serves as a bridge between companies and official Stargate and Cristal Intelligence projects. We are not affiliated with, endorsed by, or sponsored by OpenAI, SoftBank, ARM, or any other official project partners.
              </p>
              <div class="space-y-2 text-sm text-gray-400">
                <p>• We provide information from publicly available official sources</p>
                <p>• We facilitate connections but do not guarantee project acceptance</p>
                <p>• All trademarks and copyrights belong to their respective owners</p>
                <p>• We operate as a non-profit educational platform</p>
                <p>• We respect all intellectual property rights and legal requirements</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { ref, onMounted } from 'vue'
import { stargateApi, type StargateProjectData, type CristalIntelligenceData } from '../services/stargateApi'

// Reactive data
const stargateData = ref<StargateProjectData | null>(null)
const cristalData = ref<CristalIntelligenceData | null>(null)
const isLoading = ref(true)

// Platform mission and vision from API
const platformMission = ref('')
const platformVision = ref('')
const platformValues = ref<string[]>([])

// Load official data on component mount
onMounted(async () => {
  try {
    const [stargate, cristal] = await Promise.all([
      stargateApi.getStargateProjectData(),
      stargateApi.getCristalIntelligenceData()
    ])

    stargateData.value = stargate
    cristalData.value = cristal
    
    // Get platform mission, vision, and values
    platformMission.value = stargateApi.getPlatformMission()
    platformVision.value = stargateApi.getPlatformVision()
    platformValues.value = stargateApi.getPlatformValues()
  } catch (error) {
    console.error('Error loading official data:', error)
  } finally {
    isLoading.value = false
  }
})

// Get value description
const getValueDescription = (value: string): string => {
  const descriptions: Record<string, string> = {
    'Transparency and Accuracy': 'We provide accurate information from official sources and maintain complete transparency in our operations.',
    'Legal Compliance': 'We ensure all activities comply with legal requirements and respect intellectual property rights.',
    'Ethical AI Implementation': 'We guide organizations toward ethical AI adoption while preserving human values and rights.',
    'Intellectual Property Respect': 'We respect and protect all intellectual property rights of official project partners.',
    'Educational Excellence': 'We provide comprehensive educational resources to help organizations understand AI technologies.',
    'Innovation Facilitation': 'We facilitate connections and innovation while maintaining ethical standards and legal compliance.'
  }
  return descriptions[value] || 'Core platform value that guides our operations.'
}

useHead({
  title: 'About stargate.ci - Platform Mission & Vision',
  meta: [
    {
      name: 'description',
      content: 'Learn about stargate.ci platform mission, vision, and values. The bridge between companies and official Stargate and Cristal Intelligence projects.'
    }
  ]
})
</script>

<style scoped>
.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-6 hover:border-primary-500/50 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>