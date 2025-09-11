<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">stargate.ci Platform Services</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Comprehensive services connecting your organization with official Stargate and Cristal Intelligence projects. We facilitate, integrate, and preserve authorship while guiding you toward ethical AI implementation.
          </p>
        </div>
      </div>
    </section>

    <!-- Platform Services Grid -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Our Platform Services</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Comprehensive services designed to connect your organization with official Stargate and Cristal Intelligence projects
          </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
          <!-- Project Matching Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">Smart Project Matching</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              Our intelligent algorithm matches your organization with the most suitable Stargate and Cristal Intelligence projects based on your company size, investment capacity, and industry focus.
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Company size analysis</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Investment capacity matching</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Industry relevance scoring</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Interest-based recommendations</span>
              </div>
            </div>
          </div>

          <!-- Investment Opportunities Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">Investment Opportunities</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              Access to official investment opportunities in Stargate AI Infrastructure, Cristal Intelligence Enterprise Solutions, and Quantum Computing projects with direct contact to project teams.
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Stargate AI Infrastructure ($1M - $100M+)</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Cristal Intelligence Enterprise ($500K - $50M+)</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Quantum Computing Infrastructure ($10M - $500M+)</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Direct official project contact</span>
              </div>
            </div>
          </div>

          <!-- Connection Facilitation Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">Connection Facilitation</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              We facilitate direct connections between your organization and official project teams, handling the initial communication and ensuring proper information exchange while maintaining legal compliance.
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">User profile management</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Connection request tracking</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Lead generation for projects</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Legal compliance assurance</span>
              </div>
            </div>
          </div>

          <!-- Educational Platform Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">Educational Platform</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              Comprehensive educational resources about Stargate and Cristal Intelligence projects, providing accurate information from official sources while maintaining transparency and legal compliance.
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Official project information</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Technology insights and analysis</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Ethical AI implementation guidance</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Source attribution and compliance</span>
              </div>
            </div>
          </div>

          <!-- Legal Compliance Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">Legal Compliance</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              We ensure all interactions and information sharing comply with legal requirements, respect intellectual property rights, and maintain transparency in our role as an independent platform.
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Intellectual property respect</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Transparency and accuracy</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">Independent platform status</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full"></div>
                <span class="text-gray-300">No profit exploitation</span>
              </div>
            </div>
          </div>

          <!-- Platform Analytics Service -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-6">
              <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-xl flex items-center justify-center mr-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-white">Platform Analytics</h2>
            </div>
            <p class="text-gray-300 mb-6 leading-relaxed">
              Comprehensive analytics and insights about platform usage, connection success rates, and project matching effectiveness to continuously improve our services.
            </p>
            <div class="space-y-3">
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">User engagement tracking</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Connection success rates</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Project matching effectiveness</span>
              </div>
              <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full"></div>
                <span class="text-gray-300">Platform performance metrics</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Official Technology Stack -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Official Technology Stack</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            The foundational technologies powering the official Stargate and Cristal Intelligence projects
          </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div v-for="tech in officialTechnologies" :key="tech.name" class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <div class="text-2xl">{{ tech.icon }}</div>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">{{ tech.name }}</h3>
            <p class="text-sm text-gray-400">{{ tech.description }}</p>
            <p class="text-xs text-primary-400 mt-2">{{ tech.source }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Platform Benefits -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Why Choose stargate.ci?</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Our platform provides unique advantages for organizations seeking to connect with official Stargate and Cristal Intelligence projects
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="card">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Official Source Access</h3>
            <p class="text-gray-400">
              Direct access to official project information, contact details, and investment opportunities from verified sources.
            </p>
          </div>
          
          <div class="card">
            <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Intelligent Matching</h3>
            <p class="text-gray-400">
              Advanced algorithms match your organization with the most suitable projects based on size, capacity, and industry focus.
            </p>
          </div>
          
          <div class="card">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">Legal Compliance</h3>
            <p class="text-gray-400">
              Full compliance with legal requirements, intellectual property respect, and transparent operations as an independent platform.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Ready to Connect?</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Start your journey with official Stargate and Cristal Intelligence projects. Let us help you find the perfect match for your organization.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <RouterLink to="/partnership" class="btn-primary">
            Explore Opportunities
          </RouterLink>
          <RouterLink to="/contact" class="btn-secondary">
            Get Started
          </RouterLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useHead } from '@vueuse/head'
import { ref, onMounted } from 'vue'
import { stargateApi, type StargateProjectData, type CristalIntelligenceData } from '../services/stargateApi'

const { t } = useI18n()

// Official technologies from Stargate and Cristal Intelligence projects
const officialTechnologies = [
  { name: 'Advanced AI Computing', icon: '🤖', description: 'Next-generation AI infrastructure', source: 'Stargate Project' },
  { name: 'Quantum Computing', icon: '🔬', description: 'Revolutionary computational power', source: 'Stargate Project' },
  { name: 'Edge Computing', icon: '⚡', description: 'Distributed processing networks', source: 'Stargate Project' },
  { name: 'Cloud Infrastructure', icon: '☁️', description: 'Scalable enterprise solutions', source: 'Cristal Intelligence' },
  { name: 'Machine Learning', icon: '🧠', description: 'Deep learning algorithms', source: 'OpenAI Partnership' },
  { name: 'Neural Networks', icon: '🕸️', description: 'Advanced reasoning models', source: 'Cristal Intelligence' },
  { name: 'Data Processing', icon: '📊', description: 'Enterprise data integration', source: 'Cristal Intelligence' },
  { name: 'ARM Architecture', icon: '🔧', description: 'Performance & efficiency', source: 'ARM Partnership' },
]

// Load official data
const stargateData = ref<StargateProjectData | null>(null)
const cristalData = ref<CristalIntelligenceData | null>(null)
const isLoading = ref(true)

onMounted(async () => {
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
})

useHead({
  title: 'Platform Services - Stargate Cristal Intelligence',
  meta: [
    {
      name: 'description',
      content: 'Comprehensive services connecting your organization with official Stargate and Cristal Intelligence projects. Smart matching, investment opportunities, and legal compliance.'
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

.btn-secondary {
  @apply bg-gray-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
