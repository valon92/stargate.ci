<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Partnership Portal</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
            Connect directly with Stargate and Cristal Intelligence projects. Find the perfect solution for your company's needs.
          </p>
          <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-lg p-4 max-w-4xl mx-auto mb-8">
            <p class="text-yellow-200 text-sm">
              <strong>Legal Notice:</strong> This is an educational platform. We are not affiliated with OpenAI, SoftBank, or Arm. 
              We provide information only and do not charge fees or receive commissions. 
              <RouterLink to="/legal-disclaimer" class="text-yellow-400 hover:text-yellow-300 underline">Read full legal disclaimer</RouterLink>
            </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <RouterLink to="#projects" class="btn-primary">
              Explore Projects
            </RouterLink>
            <RouterLink to="/contact" class="btn-secondary">
              Get Connected
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Project Categories -->
    <section id="projects" class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Available Projects</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Discover cutting-edge AI and Cristal Intelligence solutions tailored for your industry
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Stargate AI Infrastructure -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">Stargate AI Infrastructure</h3>
            <p class="text-gray-300 mb-6">
              Enterprise-grade AI infrastructure powered by OpenAI, SoftBank, and Arm technologies.
            </p>
            <div class="space-y-3 mb-6">
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-primary-500 rounded-full mr-3"></span>
                Advanced AI Computing
              </div>
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-primary-500 rounded-full mr-3"></span>
                Enterprise Security
              </div>
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-primary-500 rounded-full mr-3"></span>
                Scalable Architecture
              </div>
            </div>
            <div class="flex flex-col gap-3">
              <button @click="openProjectModal('stargate-ai')" class="btn-primary w-full">
                View Details
              </button>
              <button @click="contactProject('stargate-ai')" class="btn-secondary w-full">
                Contact Project Team
              </button>
            </div>
          </div>

          <!-- Cristal Intelligence Platform -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-lg flex items-center justify-center mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">Cristal Intelligence Platform</h3>
            <p class="text-gray-300 mb-6">
              Next-generation cristalline computing platform for transparent and ethical AI implementation.
            </p>
            <div class="space-y-3 mb-6">
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-secondary-500 rounded-full mr-3"></span>
                Cristalline Processing
              </div>
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-secondary-500 rounded-full mr-3"></span>
                Ethical AI Framework
              </div>
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-secondary-500 rounded-full mr-3"></span>
                Real-time Analytics
              </div>
            </div>
            <div class="flex flex-col gap-3">
              <button @click="openProjectModal('cristal-intelligence')" class="btn-primary w-full">
                View Details
              </button>
              <button @click="contactProject('cristal-intelligence')" class="btn-secondary w-full">
                Contact Project Team
              </button>
            </div>
          </div>

          <!-- Quantum Cloud Computing -->
          <div class="card group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center mb-6">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
              </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">Quantum Cloud Computing</h3>
            <p class="text-gray-300 mb-6">
              Revolutionary cloud infrastructure with quantum capabilities for next-generation applications.
            </p>
            <div class="space-y-3 mb-6">
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-primary-500 rounded-full mr-3"></span>
                Quantum Processing
              </div>
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-primary-500 rounded-full mr-3"></span>
                Global Distribution
              </div>
              <div class="flex items-center text-sm text-gray-400">
                <span class="w-2 h-2 bg-primary-500 rounded-full mr-3"></span>
                Edge Computing
              </div>
            </div>
            <div class="flex flex-col gap-3">
              <button @click="openProjectModal('quantum-cloud')" class="btn-primary w-full">
                View Details
              </button>
              <button @click="contactProject('quantum-cloud')" class="btn-secondary w-full">
                Contact Project Team
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">How It Works</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Simple steps to connect with the right project for your company
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl font-bold text-white">1</span>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Explore Projects</h3>
            <p class="text-gray-400">
              Browse available Stargate and Cristal Intelligence projects tailored for your industry
            </p>
          </div>

          <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl font-bold text-white">2</span>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Get Connected</h3>
            <p class="text-gray-400">
              Contact the project team directly to discuss your specific requirements and needs
            </p>
          </div>

          <div class="text-center">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl font-bold text-white">3</span>
            </div>
            <h3 class="text-xl font-semibold mb-4 text-white">Start Partnership</h3>
            <p class="text-gray-400">
              Begin your journey with cutting-edge AI and Cristal Intelligence solutions
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Project Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-800 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-white">{{ selectedProject.title }}</h3>
            <button @click="closeModal" class="text-gray-400 hover:text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <div class="space-y-6">
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Project Overview</h4>
              <p class="text-gray-300">{{ selectedProject.overview }}</p>
            </div>
            
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Key Features</h4>
              <ul class="space-y-2">
                <li v-for="feature in selectedProject.features" :key="feature" class="flex items-center text-gray-300">
                  <span class="w-2 h-2 bg-primary-500 rounded-full mr-3"></span>
                  {{ feature }}
                </li>
              </ul>
            </div>
            
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Target Industries</h4>
              <div class="flex flex-wrap gap-2">
                <span v-for="industry in selectedProject.industries" :key="industry" 
                      class="px-3 py-1 bg-primary-500/20 text-primary-400 text-sm rounded-full">
                  {{ industry }}
                </span>
              </div>
            </div>
            
            <div>
              <h4 class="text-lg font-semibold text-white mb-3">Investment Range</h4>
              <p class="text-gray-300">{{ selectedProject.investment }}</p>
            </div>
            
            <div class="flex gap-4 pt-6">
              <button @click="contactProject(selectedProject.id)" class="btn-primary flex-1">
                Contact Project Team
              </button>
              <button @click="closeModal" class="btn-secondary flex-1">
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useHead } from '@vueuse/head'
import { stargateApi, type StargateProjectData, type CristalIntelligenceData } from '../services/stargateApi'

// Official data
const stargateData = ref<StargateProjectData | null>(null)
const cristalData = ref<CristalIntelligenceData | null>(null)
const isLoading = ref(true)

// Load official data
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

const showModal = ref(false)
const selectedProject = ref<any>({})

const projects = {
  'stargate-ai': {
    id: 'stargate-ai',
    title: 'Stargate AI Infrastructure',
    overview: 'Enterprise-grade AI infrastructure powered by the collaboration between OpenAI, SoftBank, and Arm. This project provides scalable, secure, and high-performance AI computing solutions for large-scale enterprises.',
    features: [
      'Advanced AI Computing Clusters',
      'Enterprise Security & Compliance',
      'Scalable Architecture Design',
      'Real-time AI Processing',
      'Global Infrastructure Support',
      '24/7 Technical Support'
    ],
    industries: ['Technology', 'Finance', 'Healthcare', 'Manufacturing', 'Retail'],
    investment: '$500K - $5M+ depending on scale and requirements',
    officialSource: 'https://stargateprojects.net/'
  },
  'cristal-intelligence': {
    id: 'cristal-intelligence',
    title: 'Cristal Intelligence Platform',
    overview: 'Advanced Enterprise AI that securely integrates the systems and data of individual enterprises in a way that is customized specifically for each company. Powered by OpenAI, SoftBank, and ARM partnership.',
    features: [
      'Secure enterprise data integration',
      'Customized AI solutions for each company',
      'AI agents for knowledge work automation',
      'Advanced reasoning capabilities',
      'Independent task execution',
      'Financial report generation'
    ],
    industries: ['Enterprise', 'Finance', 'Healthcare', 'Manufacturing', 'Technology'],
    investment: '$3B annually (SoftBank investment)',
    officialSource: 'https://group.softbank/en/news/press/20250203_0'
  },
  'quantum-cloud': {
    id: 'quantum-cloud',
    title: 'Quantum Cloud Computing',
    overview: 'Revolutionary cloud infrastructure with quantum computing capabilities, designed for next-generation applications that require unprecedented computational power.',
    features: [
      'Quantum Processing Units',
      'Global Edge Distribution',
      'Hybrid Classical-Quantum Computing',
      'Advanced Encryption',
      'Real-time Quantum Simulations',
      'Scalable Quantum Networks'
    ],
    industries: ['Research', 'Cryptography', 'Pharmaceuticals', 'Finance', 'Defense'],
    investment: '$1M - $10M+ for full quantum implementation',
    officialSource: 'https://stargateprojects.net/'
  }
}

const openProjectModal = (projectId: string) => {
  selectedProject.value = projects[projectId as keyof typeof projects]
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const contactProject = (projectId: string) => {
  // Redirect to contact form with pre-filled project information
  const project = projects[projectId as keyof typeof projects]
  const message = `I'm interested in learning more about the ${project.title} project. Please provide detailed information about implementation, pricing, and timeline.`
  
  // Store project info in localStorage for contact form
  localStorage.setItem('selectedProject', JSON.stringify({
    id: projectId,
    title: project.title,
    message: message
  }))
  
  // Redirect to contact page
  window.location.href = '/contact'
}

useHead({
  title: 'Partnership Portal - Stargate Cristal Intelligence',
  meta: [
    {
      name: 'description',
      content: 'Connect directly with official Stargate and Cristal Intelligence projects. Find the perfect AI solution for your company.'
    }
  ]
})
</script>
