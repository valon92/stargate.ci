<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6">
            <span class="gradient-text">{{ $t('home.title') }}</span>
          </h1>
          <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto">
            {{ $t('home.subtitle') }}
          </p>
          <p class="text-lg text-gray-400 mb-8 max-w-4xl mx-auto leading-relaxed">
            {{ $t('home.description') }}
          </p>
          <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-lg p-4 max-w-4xl mx-auto mb-8">
            <p class="text-yellow-200 text-sm">
              <strong>Legal Notice:</strong> This is an independent educational platform. We are not affiliated with OpenAI, SoftBank, or Arm. 
              We provide information only and do not charge fees or receive commissions. 
              <RouterLink to="/legal-disclaimer" class="text-yellow-400 hover:text-yellow-300 underline">Read full legal disclaimer</RouterLink>
            </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <RouterLink to="/about" class="btn-primary">
              {{ $t('home.cta') }}
            </RouterLink>
            <RouterLink to="/insights" class="btn-secondary">
              {{ $t('common.readMore') }}
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Domain Explanation Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">{{ $t('home.domainExplanation.title') }}</span>
          </h2>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div class="space-y-8">
            <div class="card group hover:scale-105 transition-transform duration-300">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-xl">S</span>
                </div>
                <h3 class="text-2xl font-bold text-white">Stargate</h3>
              </div>
              <p class="text-gray-300 leading-relaxed">{{ $t('home.domainExplanation.stargate') }}</p>
            </div>
            
            <div class="card group hover:scale-105 transition-transform duration-300">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-lg flex items-center justify-center mr-4">
                  <span class="text-white font-bold text-xl">.ci</span>
                </div>
                <h3 class="text-2xl font-bold text-white">Cristal Intelligence</h3>
              </div>
              <p class="text-gray-300 leading-relaxed">{{ $t('home.domainExplanation.ci') }}</p>
            </div>
          </div>
          
          <div class="card bg-gradient-to-br from-primary-900/20 to-secondary-900/20 border border-primary-500/30">
            <div class="text-center">
              <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Our Mission</h3>
              <p class="text-gray-300 leading-relaxed">{{ $t('home.domainExplanation.mission') }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-gray-900/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">{{ $t('home.features.title') }}</span>
          </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(feature, key) in features" :key="key" class="card group hover:scale-105 transition-transform duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-4">
              <component :is="feature.icon" class="w-6 h-6 text-white" />
            </div>
            <h3 class="text-xl font-semibold mb-3 text-white">{{ $t(`home.features.${key}.title`) }}</h3>
            <p class="text-gray-400">{{ $t(`home.features.${key}.description`) }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Project Phases Section -->
    <section class="py-24 bg-gray-800/30" v-if="stargateData && !isLoading">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Stargate Project Phases</span>
          </h2>
          <p class="text-lg text-gray-300">
            Official development timeline and investment phases
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div v-for="(phase, index) in stargateData.phases" :key="phase.name" class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mr-4">
                <span class="text-white font-bold text-lg">{{ index + 1 }}</span>
              </div>
              <div>
                <h3 class="text-xl font-bold text-white">{{ phase.name }}</h3>
                <span class="text-sm text-gray-400">{{ phase.timeline }}</span>
              </div>
            </div>
            <p class="text-gray-300 mb-4">{{ phase.description }}</p>
            <div class="flex justify-between items-center">
              <span class="text-primary-400 font-semibold">{{ phase.investment }}</span>
              <span class="px-3 py-1 rounded-full text-xs font-medium" 
                    :class="{
                      'bg-green-900/30 text-green-400': phase.status === 'completed',
                      'bg-blue-900/30 text-blue-400': phase.status === 'in-progress',
                      'bg-gray-900/30 text-gray-400': phase.status === 'planned'
                    }">
                {{ phase.status.replace('-', ' ') }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Technologies Section -->
    <section class="py-24 bg-gray-800/50" v-if="stargateData && !isLoading">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Core Technologies</span>
          </h2>
          <p class="text-lg text-gray-300">
            Advanced technologies powering the Stargate Project
          </p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div v-for="technology in stargateData.technologies" :key="technology" 
               class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-12 h-12 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
              </svg>
            </div>
            <h3 class="text-sm font-semibold text-white">{{ technology }}</h3>
          </div>
        </div>
      </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-24 bg-gray-800/30" v-if="stargateData && !isLoading">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Project Benefits</span>
          </h2>
          <p class="text-lg text-gray-300">
            Economic and strategic benefits of the Stargate Project
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="benefit in stargateData.benefits" :key="benefit" 
               class="card group hover:scale-105 transition-transform duration-300">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-white">{{ benefit }}</h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Official Stargate Project Data Section -->
    <section class="py-24 bg-gray-800/30" v-if="stargateData && !isLoading">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Official Stargate Project Data</span>
          </h2>
          <p class="text-lg text-gray-300 mb-4">
            Real-time information from official sources
          </p>
          <p class="text-sm text-gray-400">
            Last updated: {{ stargateData.lastUpdated }} | Source: <a :href="stargateData.source" target="_blank" class="text-primary-400 hover:text-primary-300 underline">Official Stargate Project</a>
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="card text-center">
            <div class="text-4xl font-bold text-primary-400 mb-2">{{ stargateData.totalInvestment }}</div>
            <h3 class="text-xl font-semibold mb-2">Total Investment</h3>
            <p class="text-gray-400">Over 4 years</p>
          </div>
          
          <div class="card text-center">
            <div class="text-4xl font-bold text-secondary-400 mb-2">{{ stargateData.initialDeploy }}</div>
            <h3 class="text-xl font-semibold mb-2">Initial Deploy</h3>
            <p class="text-gray-400">Starting immediately</p>
          </div>
          
          <div class="card text-center">
            <div class="text-4xl font-bold text-accent-400 mb-2">{{ stargateData.jobs }}</div>
            <h3 class="text-xl font-semibold mb-2">Jobs Created</h3>
            <p class="text-gray-400">Nationwide impact</p>
          </div>
          
          <div class="card text-center">
            <div class="text-4xl font-bold text-primary-400 mb-2">{{ stargateData.partners }}+</div>
            <h3 class="text-xl font-semibold mb-2">Key Partners</h3>
            <p class="text-gray-400">Global leaders</p>
          </div>
          
          <div class="card text-center">
            <div class="text-4xl font-bold text-secondary-400 mb-2">{{ stargateData.projectTimeline }}</div>
            <h3 class="text-xl font-semibold mb-2">Project Timeline</h3>
            <p class="text-gray-400">Development period</p>
          </div>
          
          <div class="card text-center">
            <div class="text-4xl font-bold text-accent-400 mb-2">Texas</div>
            <h3 class="text-xl font-semibold mb-2">Starting Location</h3>
            <p class="text-gray-400">Initial deployment</p>
          </div>
        </div>
        
        <div class="mt-12 text-center">
          <p class="text-lg text-gray-300 mb-6">{{ stargateData.description }}</p>
          <a :href="stargateData.source" target="_blank" class="btn-primary">
            Visit Official Stargate Project
          </a>
        </div>
      </div>
    </section>

    <!-- Official Cristal Intelligence Data Section -->
    <section class="py-24 bg-gray-800/50" v-if="cristalData && !isLoading">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Official Cristal Intelligence Partnership</span>
          </h2>
          <p class="text-lg text-gray-300 mb-4">
            Advanced Enterprise AI by OpenAI, SoftBank, and ARM
          </p>
          <p class="text-sm text-gray-400">
            Last updated: {{ cristalData.lastUpdated }} | Source: <a :href="cristalData.source" target="_blank" class="text-primary-400 hover:text-primary-300 underline">SoftBank Official Press Release</a>
          </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <div class="space-y-6">
            <div class="card">
              <h3 class="text-2xl font-bold mb-4 text-primary-400">{{ cristalData.name }}</h3>
              <p class="text-gray-300 mb-6">{{ cristalData.description }}</p>
              
              <div class="space-y-4">
                <div class="border-l-4 border-primary-500 pl-4">
                  <h4 class="font-semibold text-white">Investment</h4>
                  <p class="text-gray-300">{{ cristalData.partnership.investment }}</p>
                </div>
                
                <div class="border-l-4 border-secondary-500 pl-4">
                  <h4 class="font-semibold text-white">Key Features</h4>
                  <ul class="text-gray-300 space-y-1">
                    <li v-for="feature in cristalData.features.slice(0, 4)" :key="feature" class="flex items-center">
                      <span class="w-2 h-2 bg-accent-400 rounded-full mr-2"></span>
                      {{ feature }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <div class="space-y-6">
            <div class="card">
              <h3 class="text-xl font-bold mb-4 text-secondary-400">Partnership Details</h3>
              
              <div class="space-y-4">
                <div class="p-4 bg-gray-800/50 rounded-lg">
                  <h4 class="font-semibold text-primary-400 mb-2">OpenAI</h4>
                  <p class="text-gray-300 text-sm">{{ cristalData.partnership.openai }}</p>
                </div>
                
                <div class="p-4 bg-gray-800/50 rounded-lg">
                  <h4 class="font-semibold text-secondary-400 mb-2">SoftBank</h4>
                  <p class="text-gray-300 text-sm">{{ cristalData.partnership.softbank }}</p>
                </div>
                
                <div class="p-4 bg-gray-800/50 rounded-lg">
                  <h4 class="font-semibold text-accent-400 mb-2">ARM</h4>
                  <p class="text-gray-300 text-sm">{{ cristalData.partnership.arm }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="mt-12 text-center">
          <a :href="cristalData.source" target="_blank" class="btn-primary">
            Read Official Press Release
          </a>
        </div>
      </div>
    </section>

    <!-- Source Attribution Section -->
    <section class="py-12 bg-gray-900/50" v-if="sourceAttribution">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h3 class="text-lg font-semibold text-gray-300 mb-4">Official Sources & Copyright</h3>
          <div class="space-y-2 text-sm text-gray-400">
            <p><strong>Sources:</strong></p>
            <ul class="space-y-1">
              <li v-for="source in sourceAttribution.sources" :key="source">
                <a :href="source" target="_blank" class="text-primary-400 hover:text-primary-300 underline">{{ source }}</a>
              </li>
            </ul>
            <p class="mt-4 text-xs text-gray-500">{{ sourceAttribution.copyright }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Investment Opportunities Section -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <InvestmentOpportunities />
      </div>
    </section>

    <!-- Platform Mission Section -->
    <section class="py-24 bg-gradient-to-br from-primary-900/30 to-secondary-900/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">stargate.ci Platform Mission</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          {{ platformMission }}
        </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                  <RouterLink to="/dashboard" class="btn-primary">
                    Go to Dashboard
                  </RouterLink>
                  <RouterLink to="/templates" class="btn-secondary">
                    Browse Templates
                  </RouterLink>
                  <RouterLink to="/assessment" class="btn-outline">
                    Take Assessment
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
import InvestmentOpportunities from '../components/InvestmentOpportunities.vue'

const { t } = useI18n()

// Reactive data
const stargateData = ref<StargateProjectData | null>(null)
const cristalData = ref<CristalIntelligenceData | null>(null)
const isLoading = ref(true)
const sourceAttribution = ref<{ sources: string[]; copyright: string } | null>(null)
const platformMission = ref('')

// Load official data on component mount
onMounted(async () => {
  try {
    const [stargate, cristal, attribution] = await Promise.all([
      stargateApi.getStargateProjectData(),
      stargateApi.getCristalIntelligenceData(),
      Promise.resolve(stargateApi.getSourceAttribution())
    ])
    
    stargateData.value = stargate
    cristalData.value = cristal
    sourceAttribution.value = attribution
    platformMission.value = stargateApi.getPlatformMission()
  } catch (error) {
    console.error('Error loading official data:', error)
  } finally {
    isLoading.value = false
  }
})

useHead({
  title: 'Stargate Cristal Intelligence',
  meta: [
    {
      name: 'description',
      content: 'Where Stargate meets Cristal Intelligence. Guiding companies toward ethical AI implementation while preserving intellectual property rights.'
    }
  ]
})

// Feature icons (using simple SVG components)
const features = {
  ai: {
    icon: 'svg',
    iconProps: {
      viewBox: '0 0 24 24',
      fill: 'currentColor'
    }
  },
  cloud: {
    icon: 'svg',
    iconProps: {
      viewBox: '0 0 24 24',
      fill: 'currentColor'
    }
  },
  data: {
    icon: 'svg',
    iconProps: {
      viewBox: '0 0 24 24',
      fill: 'currentColor'
    }
  },
  computing: {
    icon: 'svg',
    iconProps: {
      viewBox: '0 0 24 24',
      fill: 'currentColor'
    }
  }
}
</script>
