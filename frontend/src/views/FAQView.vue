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
            <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
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

    <!-- Share Modal -->
    <div v-if="showShareModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="closeShareModal">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-semibold text-white">Share FAQ</h3>
          <button 
            @click="closeShareModal"
            class="text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div v-if="faqToShare" class="mb-6">
          <h4 class="text-lg font-medium text-white mb-2">Q: {{ faqToShare.question }}</h4>
          <p class="text-gray-400 text-sm">A: {{ faqToShare.answer }}</p>
        </div>

        <!-- Share Options -->
        <div class="grid grid-cols-2 gap-4">
          <!-- Facebook -->
          <button 
            @click="shareToFacebook"
            class="flex items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <span>Facebook</span>
          </button>

          <!-- Twitter/X -->
          <button 
            @click="shareToTwitter"
            class="flex items-center justify-center p-4 bg-black hover:bg-gray-800 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
            <span>X (Twitter)</span>
          </button>

          <!-- LinkedIn -->
          <button 
            @click="shareToLinkedIn"
            class="flex items-center justify-center p-4 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
            <span>LinkedIn</span>
          </button>

          <!-- WhatsApp -->
          <button 
            @click="shareToWhatsApp"
            class="flex items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
            <span>WhatsApp</span>
          </button>

          <!-- Telegram -->
          <button 
            @click="shareToTelegram"
            class="flex items-center justify-center p-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
          >
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
              <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
            </svg>
            <span>Telegram</span>
          </button>

          <!-- Copy Link -->
          <button 
            @click="copyLink"
            class="flex items-center justify-center p-4 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors col-span-2"
          >
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            <span>Copy FAQ</span>
          </button>
        </div>
      </div>
    </div>
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

// Share modal state
const showShareModal = ref(false)
const faqToShare = ref<any>(null)

const shareFAQ = (faq: any) => {
  faqToShare.value = faq
  showShareModal.value = true
}

const closeShareModal = () => {
  showShareModal.value = false
  faqToShare.value = null
}

const shareToFacebook = () => {
  if (!faqToShare.value) return
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`Q: ${faqToShare.value.question}\nA: ${faqToShare.value.answer}`)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToTwitter = () => {
  if (!faqToShare.value) return
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`Q: ${faqToShare.value.question}`)
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToLinkedIn = () => {
  if (!faqToShare.value) return
  const url = encodeURIComponent(window.location.href)
  const title = encodeURIComponent(`FAQ: ${faqToShare.value.question}`)
  const summary = encodeURIComponent(faqToShare.value.answer)
  window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}&summary=${summary}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToWhatsApp = () => {
  if (!faqToShare.value) return
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`Q: ${faqToShare.value.question}\nA: ${faqToShare.value.answer}`)
  window.open(`https://wa.me/?text=${text}%20${url}`, '_blank')
  closeShareModal()
}

const shareToTelegram = () => {
  if (!faqToShare.value) return
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(`Q: ${faqToShare.value.question}\nA: ${faqToShare.value.answer}`)
  window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank')
  closeShareModal()
}

const copyLink = async () => {
  if (!faqToShare.value) return
  try {
    const text = `Q: ${faqToShare.value.question}\nA: ${faqToShare.value.answer}\n\nSource: ${window.location.href}`
    await navigator.clipboard.writeText(text)
    alert('FAQ copied to clipboard!')
    closeShareModal()
  } catch (error) {
    console.error('Failed to copy FAQ:', error)
    alert('Failed to copy FAQ. Please try again.')
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
  @apply bg-black text-white px-6 py-3 rounded-lg font-medium transition-all duration-200;
}

.gradient-text {
  @apply text-white;
}

.faq-container {
  background-color: rgba(31, 41, 55, 1);
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid rgba(55, 65, 81, 1);
}
</style>
