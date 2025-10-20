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


    <!-- FAQ Filter -->
    <section class="py-16 bg-gray-800/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16">
        <!-- Search Bar -->
        <div class="mb-8">
          <div class="max-w-2xl mx-auto">
            <div class="relative">
              <input
                v-model="searchQuery"
                @keyup.enter="searchFAQs"
                type="text"
                placeholder="Search FAQ questions..."
                class="w-full px-4 py-3 pl-12 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              <button
                @click="searchFAQs"
                :disabled="isSearching"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <svg v-if="!isSearching" class="h-5 w-5 text-gray-400 hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <div v-else class="animate-spin rounded-full h-5 w-5 border-b-2 border-primary-500"></div>
              </button>
            </div>
          </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 justify-center items-center mb-12">
          <div class="flex flex-wrap gap-2">
            <button 
              v-for="category in faqCategories" 
              :key="category.id"
              @click="filterByCategory(category.id)"
              :class="[
                'px-4 py-2 rounded-lg font-medium transition-all duration-200',
                selectedCategory === category.id 
                  ? 'bg-primary-500 text-white' 
                  : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
              ]"
            >
              {{ category.name }}
            </button>
          </div>
          <div class="flex gap-2">
            <select v-model="sortBy" @change="filterByCategory(selectedCategory)" class="bg-gray-800 border border-gray-600 text-white rounded-lg px-4 py-2">
              <option value="relevance">Most Relevant</option>
              <option value="alphabetical">Alphabetical</option>
              <option value="category">By Category</option>
            </select>
            <button
              @click="clearFilters"
              class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
              Clear Filters
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-24">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16">
        <!-- FAQ Display - Single Card Style -->
        <div class="space-y-6">
          <div v-for="(faq, index) in filteredFAQs" :key="index" class="faq-container group hover:scale-[1.01] transition-all duration-300">
            <div class="relative overflow-hidden rounded-lg">
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <div class="flex items-center text-sm text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    FAQ {{ index + 1 }}
                  </div>
                  <div class="text-sm text-gray-400">
                    {{ faq.category || 'General' }}
                  </div>
                </div>

                <button
                  @click="toggleFAQ(index)"
                  class="w-full text-left focus:outline-none"
                >
                  <h3 class="text-xl font-bold text-white mb-4 group-hover:text-primary-400 transition-colors">
                    {{ faq.question }}
                  </h3>
                </button>
                
                <div
                  v-if="openFAQs.includes(index)"
                  class="mt-4 pt-4 border-t border-gray-700"
                >
                  <p class="text-gray-300 leading-relaxed text-lg">{{ faq.answer }}</p>
                </div>

                <!-- FAQ Actions -->
                <div class="flex gap-3 mt-6">
                  <button 
                    @click="toggleFAQ(index)"
                    class="flex-1 btn-primary"
                  >
                    {{ openFAQs.includes(index) ? 'Hide Answer' : 'Show Answer' }}
                  </button>
                  
                  <button 
                    @click="shareFAQ(faq)"
                    class="px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
                    title="Share FAQ"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Additional Resources -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">Need More Information?</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Explore our platform resources or connect with official project teams
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
            <RouterLink to="/news" class="btn-secondary">
              Read Analysis
            </RouterLink>
          </div>
          
          <div class="card text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full flex items-center justify-center mx-auto mb-6">
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
import { ref, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { useHead } from '@vueuse/head'

// Reactive data
const openFAQs = ref<number[]>([])
const searchQuery = ref('')
const selectedCategory = ref('')
const sortBy = ref('relevance')
const isSearching = ref(false)

// FAQ categories
const faqCategories = ref([
  {
    id: '',
    name: 'All FAQs'
  },
  {
    id: 'platform',
    name: 'Platform'
  },
  {
    id: 'stargate',
    name: 'Stargate Project'
  },
  {
    id: 'cristal',
    name: 'Cristal Intelligence'
  },
  {
    id: 'investment',
    name: 'Investment'
  },
  {
    id: 'legal',
    name: 'Legal & Compliance'
  },
  {
    id: 'technology',
    name: 'Technology'
  },
  {
    id: 'partnership',
    name: 'Partnership'
  }
])

const toggleFAQ = (index: number) => {
  const isOpen = openFAQs.value.includes(index)
  if (isOpen) {
    openFAQs.value = openFAQs.value.filter(i => i !== index)
  } else {
    openFAQs.value.push(index)
  }
}

const filterByCategory = (categoryId: string) => {
  selectedCategory.value = categoryId
}

const searchFAQs = () => {
  isSearching.value = true
  // Simulate search delay
  setTimeout(() => {
    isSearching.value = false
  }, 500)
}

const clearFilters = () => {
  searchQuery.value = ''
  selectedCategory.value = ''
  sortBy.value = 'relevance'
}

const shareFAQ = (faq: any) => {
  if (navigator.share) {
    navigator.share({
      title: faq.question,
      text: faq.answer,
      url: window.location.href
    })
  } else {
    // Fallback to copying to clipboard
    const text = `Q: ${faq.question}\nA: ${faq.answer}`
    navigator.clipboard.writeText(text)
    alert('FAQ copied to clipboard!')
  }
}

// Computed property for filtered FAQs
const filteredFAQs = computed(() => {
  let filtered = faqs

  // Filter by category
  if (selectedCategory.value) {
    filtered = filtered.filter(faq => faq.category === selectedCategory.value)
  }

  // Filter by search query
  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(faq => 
      faq.question.toLowerCase().includes(query) || 
      faq.answer.toLowerCase().includes(query)
    )
  }

  // Sort
  switch (sortBy.value) {
    case 'alphabetical':
      filtered = filtered.sort((a, b) => a.question.localeCompare(b.question))
      break
    case 'category':
      filtered = filtered.sort((a, b) => (a.category || '').localeCompare(b.category || ''))
      break
    case 'relevance':
    default:
      // Keep original order for relevance
      break
  }

  return filtered
})

const faqs = [
  {
    question: "What is stargate.ci platform?",
    answer: "stargate.ci is an independent educational and connection platform that facilitates connections between companies and the official Stargate Project and Cristal Intelligence initiatives. We provide accurate information, analysis, and direct connection services while respecting all intellectual property rights and legal compliance.",
    category: "platform"
  },
  {
    question: "What is the official Stargate Project?",
    answer: "The Stargate Project is a $500 billion AI infrastructure initiative announced by OpenAI, SoftBank, and ARM. It represents the largest investment in AI infrastructure, spanning 4 years and creating 100,000+ jobs. The project aims to build next-generation AI infrastructure with quantum computing capabilities and advanced data processing.",
    category: "stargate"
  },
  {
    question: "What is Cristal Intelligence?",
    answer: "Cristal Intelligence is a strategic partnership between OpenAI, SoftBank, and ARM with a $3 billion annual investment. It provides enterprise AI solutions including AI agents, knowledge work automation, advanced reasoning, and customized AI solutions for companies. It focuses on data integration, security, and enterprise-grade AI capabilities.",
    category: "cristal"
  },
  {
    question: "Is stargate.ci officially affiliated with these projects?",
    answer: "No, stargate.ci is an independent platform. We are not officially affiliated with OpenAI, SoftBank, ARM, or the Stargate Project. Our mission is to facilitate connections, provide accurate information, and preserve intellectual property rights while acting as a bridge between companies and official project teams.",
    category: "legal"
  },
  {
    question: "How does the platform connect companies to projects?",
    answer: "Our platform uses intelligent matching algorithms to connect companies with relevant Stargate and Cristal Intelligence opportunities. We provide direct contact information, project requirements, and facilitate introductions while ensuring all connections respect official project guidelines and legal compliance.",
    category: "partnership"
  },
  {
    question: "What investment opportunities are available?",
    answer: "We showcase three main investment opportunities: Stargate AI Infrastructure ($500B project), Cristal Intelligence Platform ($3B annual partnership), and Quantum Cloud Computing infrastructure. Each opportunity has specific requirements, benefits, and direct connection paths to official project teams.",
    category: "investment"
  },
  {
    question: "How does the platform ensure legal compliance?",
    answer: "We maintain strict legal compliance by using only publicly available information, respecting all copyright and intellectual property rights, providing proper source attribution, and acting as an independent educational platform. We never claim official affiliation and always direct users to official sources.",
    category: "legal"
  },
  {
    question: "What technologies are involved in these projects?",
    answer: "The projects involve cutting-edge technologies including artificial intelligence, machine learning, quantum computing, cloud infrastructure, advanced data processing, AI agents, knowledge work automation, and enterprise-grade security systems. These technologies work together to create comprehensive AI ecosystems.",
    category: "technology"
  },
  {
    question: "How can companies get started with these projects?",
    answer: "Companies can explore our Partnership page to view available opportunities, use our intelligent matching system to find relevant projects, and connect directly with official project teams. We provide detailed project information, requirements, and facilitate the connection process while maintaining legal compliance.",
    category: "partnership"
  },
  {
    question: "What makes stargate.ci different from other platforms?",
    answer: "stargate.ci is unique because we focus specifically on Stargate and Cristal Intelligence projects, provide real-time official data, maintain strict legal compliance, offer intelligent project matching, and act as a transparent bridge between companies and official project teams while preserving all intellectual property rights.",
    category: "platform"
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

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}

.faq-container {
  background-color: rgba(31, 41, 55, 1);
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid rgba(55, 65, 81, 1);
}
</style>
