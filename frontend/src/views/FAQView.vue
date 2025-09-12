<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Frequently Asked Questions</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Everything you need to know about stargate.ci platform, Stargate Project, and Cristal Intelligence initiatives. Get answers to common questions about our services and official project information.
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

    <!-- FAQ Section -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Platform FAQ</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Common questions about stargate.ci platform and our services
          </p>
        </div>
        
        <div class="space-y-6">
          <div v-for="(faq, index) in faqs" :key="index" class="card">
            <button
              @click="toggleFAQ(index)"
              class="w-full text-left flex items-center justify-between focus:outline-none"
            >
              <h3 class="text-lg font-semibold text-white pr-4">{{ faq.question }}</h3>
              <div class="flex-shrink-0">
                <svg
                  class="w-5 h-5 text-gray-400 transition-transform duration-200"
                  :class="{ 'rotate-180': openFAQs.includes(index) }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </button>
            <div
              v-if="openFAQs.includes(index)"
              class="mt-4 pt-4 border-t border-gray-700"
            >
              <p class="text-gray-300 leading-relaxed">{{ faq.answer }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Additional Resources -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Need More Information?</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Explore our platform resources or connect with official project teams
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
            <p class="text-gray-400 mb-6">
              Comprehensive analysis and insights about Stargate Project and Cristal Intelligence
            </p>
            <RouterLink to="/insights" class="btn-secondary">
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
            <p class="text-gray-400 mb-6">
              Connect directly with official project teams and explore investment opportunities
            </p>
            <RouterLink to="/partnership" class="btn-secondary">
              Explore Projects
            </RouterLink>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Contact Platform</h3>
            <p class="text-gray-400 mb-6">
              Get in touch with our platform team for specific questions about our services
            </p>
            <RouterLink to="/contact" class="btn-secondary">
              Contact Us
            </RouterLink>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useHead } from '@vueuse/head'
import { stargateApi, type StargateProjectData, type CristalIntelligenceData } from '../services/stargateApi'

const { t } = useI18n()

const openFAQs = ref<number[]>([])

// Official project data
const stargateData = ref<StargateProjectData | null>(null)
const cristalData = ref<CristalIntelligenceData | null>(null)
const isLoading = ref(true)

const toggleFAQ = (index: number) => {
  const isOpen = openFAQs.value.includes(index)
  if (isOpen) {
    openFAQs.value = openFAQs.value.filter(i => i !== index)
  } else {
    openFAQs.value.push(index)
  }
}

// Load official data on component mount
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

const faqs = [
  {
    question: "What is stargate.ci platform?",
    answer: "stargate.ci is an independent educational and connection platform that facilitates connections between companies and the official Stargate Project and Cristal Intelligence initiatives. We provide accurate information, analysis, and direct connection services while respecting all intellectual property rights and legal compliance."
  },
  {
    question: "What is the official Stargate Project?",
    answer: "The Stargate Project is a $500 billion AI infrastructure initiative announced by OpenAI, SoftBank, and ARM. It represents the largest investment in AI infrastructure, spanning 4 years and creating 100,000+ jobs. The project aims to build next-generation AI infrastructure with quantum computing capabilities and advanced data processing."
  },
  {
    question: "What is Cristal Intelligence?",
    answer: "Cristal Intelligence is a strategic partnership between OpenAI, SoftBank, and ARM with a $3 billion annual investment. It provides enterprise AI solutions including AI agents, knowledge work automation, advanced reasoning, and customized AI solutions for companies. It focuses on data integration, security, and enterprise-grade AI capabilities."
  },
  {
    question: "Is stargate.ci officially affiliated with these projects?",
    answer: "No, stargate.ci is an independent platform. We are not officially affiliated with OpenAI, SoftBank, ARM, or the Stargate Project. Our mission is to facilitate connections, provide accurate information, and preserve intellectual property rights while acting as a bridge between companies and official project teams."
  },
  {
    question: "How does the platform connect companies to projects?",
    answer: "Our platform uses intelligent matching algorithms to connect companies with relevant Stargate and Cristal Intelligence opportunities. We provide direct contact information, project requirements, and facilitate introductions while ensuring all connections respect official project guidelines and legal compliance."
  },
  {
    question: "What investment opportunities are available?",
    answer: "We showcase three main investment opportunities: Stargate AI Infrastructure ($500B project), Cristal Intelligence Platform ($3B annual partnership), and Quantum Cloud Computing infrastructure. Each opportunity has specific requirements, benefits, and direct connection paths to official project teams."
  },
  {
    question: "How does the platform ensure legal compliance?",
    answer: "We maintain strict legal compliance by using only publicly available information, respecting all copyright and intellectual property rights, providing proper source attribution, and acting as an independent educational platform. We never claim official affiliation and always direct users to official sources."
  },
  {
    question: "What technologies are involved in these projects?",
    answer: "The projects involve cutting-edge technologies including artificial intelligence, machine learning, quantum computing, cloud infrastructure, advanced data processing, AI agents, knowledge work automation, and enterprise-grade security systems. These technologies work together to create comprehensive AI ecosystems."
  },
  {
    question: "How can companies get started with these projects?",
    answer: "Companies can explore our Partnership page to view available opportunities, use our intelligent matching system to find relevant projects, and connect directly with official project teams. We provide detailed project information, requirements, and facilitate the connection process while maintaining legal compliance."
  },
  {
    question: "What makes stargate.ci different from other platforms?",
    answer: "stargate.ci is unique because we focus specifically on Stargate and Cristal Intelligence projects, provide real-time official data, maintain strict legal compliance, offer intelligent project matching, and act as a transparent bridge between companies and official project teams while preserving all intellectual property rights."
  }
]

useHead({
  title: 'FAQ - Stargate & Cristal Intelligence Questions',
  meta: [
    {
      name: 'description',
      content: 'Frequently asked questions about stargate.ci platform, Stargate Project, and Cristal Intelligence initiatives. Get answers about our services, investment opportunities, and official project information.'
    }
  ]
})
</script>

<style scoped>
.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-6 hover:border-primary-500/50 transition-all duration-300;
}

.btn-secondary {
  @apply bg-gray-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-600 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
