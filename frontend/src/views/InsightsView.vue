<template>
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Stargate & Cristal Intelligence Insights</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Comprehensive insights, analysis, and educational content about the official Stargate Project and Cristal Intelligence initiatives. Stay informed about the future of AI infrastructure and enterprise solutions.
          </p>
        </div>
      </div>
    </section>

    <!-- Category Filter -->
    <section class="py-12 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-4">
          <button
            v-for="category in categories"
            :key="category.key"
            @click="selectedCategory = category.key"
            class="px-6 py-3 rounded-lg font-medium transition-all duration-200"
            :class="selectedCategory === category.key 
              ? 'bg-gradient-to-r from-primary-500 to-secondary-500 text-white' 
              : 'bg-gray-700 text-gray-300 hover:bg-gray-600'"
          >
            {{ category.name }}
          </button>
        </div>
      </div>
    </section>

    <!-- Official Project Data Section -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Official Project Data</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Real-time information from official Stargate Project and Cristal Intelligence sources
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

    <!-- Articles Grid -->
    <section class="py-24 bg-gray-800/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Platform Insights & Analysis</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Comprehensive analysis and insights about Stargate and Cristal Intelligence projects
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article
            v-for="article in filteredArticles"
            :key="article.id"
            class="card group hover:scale-105 transition-transform duration-300"
          >
            <div class="aspect-w-16 aspect-h-9 mb-6">
              <div class="w-full h-48 bg-gradient-to-br from-primary-500/20 to-secondary-500/20 rounded-lg flex items-center justify-center">
                <div class="text-4xl">{{ article.icon }}</div>
              </div>
            </div>
            
            <div class="flex items-center space-x-2 mb-4">
              <span class="px-3 py-1 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 text-primary-400 text-sm rounded-full">
                {{ article.category }}
              </span>
              <span class="text-gray-400 text-sm">{{ article.date }}</span>
            </div>
            
            <h2 class="text-xl font-semibold text-white mb-3 group-hover:text-primary-400 transition-colors duration-200">
              {{ article.title }}
            </h2>
            
            <p class="text-gray-400 mb-4 leading-relaxed">
              {{ article.excerpt }}
            </p>
            
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">{{ article.readTime }} min read</span>
                <span class="text-xs text-gray-600 bg-gray-700 px-2 py-1 rounded">{{ article.source }}</span>
              </div>
              <RouterLink
                :to="`/insights/${article.slug}`"
                class="text-primary-400 hover:text-primary-300 font-medium transition-colors duration-200"
              >
                Read Analysis →
              </RouterLink>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Official Project Updates -->
    <section class="py-24 bg-gradient-to-r from-primary-900/20 to-secondary-900/20">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Stay Connected</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Get the latest updates on official Stargate Project and Cristal Intelligence developments. Connect with us to stay informed about investment opportunities and project milestones.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
          <input
            type="email"
            placeholder="Enter your email"
            class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
          <button class="btn-primary">
            Get Updates
          </button>
        </div>
        <div class="mt-6 text-sm text-gray-400">
          <p>We respect your privacy and only share official project information from verified sources.</p>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useHead } from '@vueuse/head'
import { stargateApi, type StargateProjectData, type CristalIntelligenceData } from '../services/stargateApi'

const { t } = useI18n()

const selectedCategory = ref('all')

// Platform-specific categories
const categories = [
  { key: 'all', name: 'All Insights' },
  { key: 'stargate', name: 'Stargate Project' },
  { key: 'cristal', name: 'Cristal Intelligence' },
  { key: 'investment', name: 'Investment Opportunities' },
  { key: 'technology', name: 'Technology Analysis' },
  { key: 'partnership', name: 'Partnership Insights' },
]

// Official project data
const stargateData = ref<StargateProjectData | null>(null)
const cristalData = ref<CristalIntelligenceData | null>(null)
const isLoading = ref(true)

// Platform-specific insights articles
const articles = [
  {
    id: 1,
    title: "Stargate Project: $500B Investment in Next-Generation AI Infrastructure",
    excerpt: "Deep dive into the official Stargate Project announcement, analyzing the $500B investment, 4-year timeline, and its impact on American AI leadership and job creation.",
    category: "Stargate",
    date: "Jan 11, 2025",
    readTime: 12,
    slug: "stargate-project-500b-investment-analysis",
    icon: "🚀",
    source: "Official Project Data"
  },
  {
    id: 2,
    title: "Cristal Intelligence: Advanced Enterprise AI Solutions",
    excerpt: "Comprehensive analysis of Cristal Intelligence partnership between OpenAI, SoftBank, and ARM, exploring the $3B annual investment and enterprise AI capabilities.",
    category: "Cristal",
    date: "Feb 3, 2025",
    readTime: 10,
    slug: "cristal-intelligence-enterprise-ai-solutions",
    icon: "💎",
    source: "Official Partnership Data"
  },
  {
    id: 3,
    title: "Investment Opportunities in Stargate AI Infrastructure",
    excerpt: "Detailed guide to investment opportunities in Stargate AI Infrastructure, including requirements, benefits, and direct connection to official project teams.",
    category: "Investment",
    date: "Jan 15, 2025",
    readTime: 8,
    slug: "investment-opportunities-stargate-ai-infrastructure",
    icon: "💰",
    source: "Investment Analysis"
  },
  {
    id: 4,
    title: "Quantum Computing in Stargate: Revolutionary Computational Power",
    excerpt: "Exploring the quantum computing infrastructure within the Stargate Project, analyzing its potential to revolutionize AI processing capabilities.",
    category: "Technology",
    date: "Jan 20, 2025",
    readTime: 15,
    slug: "quantum-computing-stargate-revolutionary-power",
    icon: "🔬",
    source: "Technology Analysis"
  },
  {
    id: 5,
    title: "OpenAI, SoftBank, ARM Partnership: Strategic Analysis",
    excerpt: "In-depth analysis of the strategic partnership between OpenAI, SoftBank, and ARM, examining their roles in the Stargate and Cristal Intelligence projects.",
    category: "Partnership",
    date: "Feb 5, 2025",
    readTime: 11,
    slug: "openai-softbank-arm-partnership-analysis",
    icon: "🤝",
    source: "Partnership Analysis"
  },
  {
    id: 6,
    title: "Cristal Intelligence: Enterprise Data Integration & Security",
    excerpt: "Technical deep dive into Cristal Intelligence's enterprise data integration capabilities, security measures, and customized AI solutions for companies.",
    category: "Cristal",
    date: "Feb 8, 2025",
    readTime: 13,
    slug: "cristal-intelligence-data-integration-security",
    icon: "🔐",
    source: "Technical Analysis"
  },
  {
    id: 7,
    title: "Stargate Project Phases: Infrastructure Development Timeline",
    excerpt: "Detailed breakdown of Stargate Project phases, from Texas infrastructure foundation to national expansion and global integration over 4 years.",
    category: "Stargate",
    date: "Jan 25, 2025",
    readTime: 9,
    slug: "stargate-project-phases-infrastructure-timeline",
    icon: "📅",
    source: "Project Timeline"
  },
  {
    id: 8,
    title: "AI Agents & Knowledge Work Automation in Cristal Intelligence",
    excerpt: "Exploring Cristal Intelligence's AI agents capabilities, knowledge work automation, and advanced reasoning features for enterprise applications.",
    category: "Technology",
    date: "Feb 10, 2025",
    readTime: 14,
    slug: "ai-agents-knowledge-work-automation-cristal",
    icon: "🤖",
    source: "AI Technology Analysis"
  }
]

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

const filteredArticles = computed(() => {
  if (selectedCategory.value === 'all') {
    return articles
  }
  return articles.filter(article => 
    article.category.toLowerCase() === selectedCategory.value.toLowerCase()
  )
})

useHead({
  title: 'Insights - Stargate & Cristal Intelligence Analysis',
  meta: [
    {
      name: 'description',
      content: 'Comprehensive insights and analysis about the official Stargate Project and Cristal Intelligence initiatives. Stay informed about AI infrastructure investments and enterprise solutions.'
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
