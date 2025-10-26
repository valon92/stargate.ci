<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
      <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 to-secondary-900/10"></div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6">
            <span class="gradient-text">Latest News</span>
          </h1>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
            Stay updated with the latest developments, announcements, and insights about the Stargate Project and Cristal Intelligence.
          </p>
        </div>
      </div>
    </section>

    <!-- News Filter -->
    <section class="py-16 bg-gray-800/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16">
        <!-- Search Bar -->
        <div class="mb-8">
          <div class="max-w-2xl mx-auto">
            <div class="relative">
              <input
                v-model="searchQuery"
                @keyup.enter="searchArticles"
                type="text"
                placeholder="Search news articles..."
                class="w-full px-4 py-3 pl-12 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              <button
                @click="searchArticles"
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
              v-for="category in newsCategories" 
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
              <option value="date">Latest First</option>
              <option value="title">Alphabetical</option>
              <option value="category">By Category</option>
            </select>
            <button
              @click="refreshNews"
              :disabled="isLoading"
              class="px-4 py-2 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2"
            >
              <svg v-if="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
              <div v-else class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
              {{ isLoading ? 'Loading...' : 'Refresh News' }}
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- News Grid -->
    <section class="py-24">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12 xl:px-16">

        <!-- Loading State -->
        <div v-if="isLoading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
          <p class="mt-4 text-gray-400">Loading latest news...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-12">
          <div class="text-red-400 mb-4">
            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-xl font-bold mb-2">Failed to Load News</h3>
            <p class="text-gray-400 mb-4">{{ error }}</p>
            <button @click="loadNews()" class="btn-primary">
              Try Again
            </button>
          </div>
        </div>

        <!-- News Display - Single Card Style (like events on events page) -->
        <div v-else class="space-y-8">
          <article 
            v-for="article in paginatedNews" 
            :key="article.id"
            class="news-container group hover:scale-[1.02] transition-all duration-300"
          >
            <!-- News Content -->
            <div class="relative overflow-hidden rounded-lg">
              <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                <div class="text-center">
                  <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-3xl">📰</span>
                  </div>
                  <h3 class="text-2xl font-bold text-white">{{ article.title }}</h3>
                </div>
              </div>
              
              <!-- Category Badge -->
              <div class="absolute top-4 left-4">
                <span :class="getCategoryBadgeClass(article.category)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ article.category }}
                </span>
              </div>
              
              <!-- Date Badge -->
              <div class="absolute top-4 right-4">
                <span class="px-3 py-1 bg-gray-800/80 text-white rounded-full text-xs font-medium">
                  {{ formatDate(article.publishedAt) }}
                </span>
              </div>
            </div>

            <!-- News Content -->
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center text-sm text-gray-400">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  By {{ article.author }}
                </div>
                <div class="text-sm text-gray-400">
                  5 min read
                </div>
              </div>

              <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-primary-400 transition-colors">
                {{ article.title }}
              </h3>
              
              <p class="text-gray-400 mb-6 text-lg leading-relaxed">
                {{ article.excerpt }}
              </p>

              <!-- News Actions -->
              <div class="flex gap-3 mb-6">
                <RouterLink 
                  :to="`/article/${article.id}`"
                  class="flex-1 btn-primary"
                >
                  Read Full Article
                </RouterLink>
                
                <button 
                  @click="shareArticle(article)"
                  class="px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition-colors"
                  title="Share Article"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                  </svg>
                </button>
              </div>

              <!-- Interactive Content -->
              <InteractiveContent
                :content-id="article.id"
                content-type="news"
              />
            </div>
          </article>
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex justify-center mt-12">
          <nav class="flex space-x-2">
            <button 
              @click="currentPage = Math.max(1, currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              Previous
            </button>
            
            <button 
              v-for="page in visiblePages" 
              :key="page"
              @click="currentPage = page"
              :class="[
                'px-4 py-2 rounded-lg transition-colors',
                page === currentPage 
                  ? 'bg-primary-600 text-white' 
                  : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
              ]"
            >
              {{ page }}
            </button>
            
            <button 
              @click="currentPage = Math.min(totalPages, currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              Next
            </button>
          </nav>
        </div>
      </div>
    </section>

    <!-- Trending Topics -->
    <section v-if="trendingTopics.length > 0" class="py-16 bg-gray-800/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-2xl md:text-3xl font-bold mb-4">
            <span class="gradient-text">Trending Topics</span>
          </h2>
          <p class="text-gray-300">What's hot in AI and technology right now</p>
        </div>
        
        <div class="flex flex-wrap justify-center gap-3">
          <span 
            v-for="topic in trendingTopics.slice(0, 10)" 
            :key="topic"
            class="px-4 py-2 bg-gradient-to-r from-primary-500/20 to-secondary-500/20 border border-primary-500/30 rounded-full text-sm text-primary-300 hover:from-primary-500/30 hover:to-secondary-500/30 transition-all duration-200 cursor-pointer"
          >
            {{ topic }}
          </span>
        </div>
      </div>
    </section>

    <!-- Newsletter Subscription -->
    <section class="py-24">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Join Our Community</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Stay informed about the latest developments in AI infrastructure, Stargate Project updates, and Cristal Intelligence breakthroughs. Be part of the future of artificial intelligence.
        </p>
        
        <div class="max-w-md mx-auto">
          <div class="flex gap-4">
            <input 
              v-model="email"
              type="email" 
              placeholder="Your email address"
              class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
            <button 
              @click="subscribeNewsletter"
              :disabled="!email || isSubscribing"
              class="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubscribing ? 'Joining...' : 'Join Now' }}
            </button>
          </div>
          <p class="text-sm text-gray-400 mt-3">
            Join thousands of AI enthusiasts. No spam, just valuable insights.
          </p>
        </div>
      </div>
    </section>

    <!-- Share Modal -->
    <div v-if="showShareModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="closeShareModal">
      <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-xl font-semibold text-white">Share Article</h3>
          <button 
            @click="closeShareModal"
            class="text-gray-400 hover:text-white transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div v-if="articleToShare" class="mb-6">
          <h4 class="text-lg font-medium text-white mb-2">{{ articleToShare.title }}</h4>
          <p class="text-gray-400 text-sm">{{ articleToShare.excerpt }}</p>
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
            <span>Copy Link</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { newsApiService, type NewsArticle } from '../services/newsApiService'
import InteractiveContent from '../components/InteractiveContent.vue'

// Reactive data
const selectedCategory = ref('')
const sortBy = ref('date')
const currentPage = ref(1)
const itemsPerPage = 9
const email = ref('')
const isSubscribing = ref(false)
const searchQuery = ref('')
const isSearching = ref(false)

// News data
const newsArticles = ref<NewsArticle[]>([])
const isLoading = ref(false)
const error = ref('')
const trendingTopics = ref<string[]>([])

// News categories
const newsCategories = ref([
  {
    id: '',
    name: 'All News'
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
    id: 'industry',
    name: 'Industry Impact'
  },
  {
    id: 'research',
    name: 'Research & Development'
  },
  {
    id: 'ethics',
    name: 'AI Ethics & Governance'
  },
  {
    id: 'global',
    name: 'Global Impact'
  }
])

// Computed properties
const filteredNews = computed(() => {
  let filtered = newsArticles.value

  if (selectedCategory.value) {
    filtered = filtered.filter(article => article.category === selectedCategory.value)
  }

  // Sort
  switch (sortBy.value) {
    case 'title':
      filtered = filtered.sort((a, b) => a.title.localeCompare(b.title))
      break
    case 'category':
      filtered = filtered.sort((a, b) => a.category.localeCompare(b.category))
      break
    case 'date':
    default:
      filtered = filtered.sort((a, b) => new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime())
      break
  }

  return filtered
})

const totalPages = computed(() => Math.ceil(filteredNews.value.length / itemsPerPage))

const paginatedNews = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredNews.value.slice(start, end)
})

const visiblePages = computed(() => {
  const pages = []
  const start = Math.max(1, currentPage.value - 2)
  const end = Math.min(totalPages.value, currentPage.value + 2)
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

// Methods
const loadNews = async (category?: string) => {
  isLoading.value = true
  error.value = ''
  
  try {
    const response = await newsApiService.generateNews(category, 15)
    
    if (response.success) {
      newsArticles.value = response.articles
      console.log('📰 Loaded news articles:', response.articles.length)
    } else {
      error.value = response.error || 'Failed to load news'
      console.error('Error loading news:', response.error)
    }
  } catch (err) {
    error.value = 'Failed to load news articles'
    console.error('Error loading news:', err)
  } finally {
    isLoading.value = false
  }
}

const loadTrendingTopics = async () => {
  try {
    // trendingTopics.value = await newsApiService.getTrendingTopics() // Method not available
    console.log('📈 Loaded trending topics:', trendingTopics.value)
  } catch (err) {
    console.error('Error loading trending topics:', err)
  }
}

const filterByCategory = async (category: string) => {
  selectedCategory.value = category
  currentPage.value = 1
  await loadNews(category)
}

const searchArticles = async () => {
  if (!searchQuery.value.trim()) {
    await loadNews()
    return
  }
  
  isSearching.value = true
  error.value = ''
  
  try {
    const response = await newsApiService.searchNews(searchQuery.value, 10)
    
    if (response.success) {
      newsArticles.value = response.articles
      console.log('🔍 Search results:', response.articles.length)
    } else {
      error.value = response.error || 'Search failed'
    }
  } catch (err) {
    error.value = 'Search failed'
    console.error('Error searching articles:', err)
  } finally {
    isSearching.value = false
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getCategoryBadgeClass = (category: string) => {
  const classes = {
    stargate: 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
    cristal: 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
    industry: 'bg-green-500/20 text-green-400 border border-green-500/30',
    research: 'bg-orange-500/20 text-orange-400 border border-orange-500/30',
    ethics: 'bg-red-500/20 text-red-400 border border-red-500/30',
    global: 'bg-teal-500/20 text-teal-400 border border-teal-500/30'
  }
  return classes[category as keyof typeof classes] || 'bg-gray-500/20 text-gray-400 border border-gray-500/30'
}

const subscribeNewsletter = async () => {
  if (!email.value) return
  
  isSubscribing.value = true
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    // Show success message (you could use a toast notification here)
    alert('Successfully subscribed to newsletter!')
    email.value = ''
  } catch (error) {
    console.error('Newsletter subscription failed:', error)
    alert('Failed to subscribe. Please try again.')
  } finally {
    isSubscribing.value = false
  }
}

const refreshNews = async () => {
  isLoading.value = true
  try {
    await loadNews(selectedCategory.value || undefined)
    console.log('🔄 Refreshed news articles')
  } catch (error) {
    console.error('Error refreshing news:', error)
  } finally {
    isLoading.value = false
  }
}

// Share modal state
const showShareModal = ref(false)
const articleToShare = ref<NewsArticle | null>(null)

const shareArticle = (article: NewsArticle) => {
  articleToShare.value = article
  showShareModal.value = true
}

const closeShareModal = () => {
  showShareModal.value = false
  articleToShare.value = null
}

const shareToFacebook = () => {
  if (!articleToShare.value) return
  const url = encodeURIComponent(window.location.origin + `/article/${articleToShare.value.id}`)
  const text = encodeURIComponent(articleToShare.value.title)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToTwitter = () => {
  if (!articleToShare.value) return
  const url = encodeURIComponent(window.location.origin + `/article/${articleToShare.value.id}`)
  const text = encodeURIComponent(articleToShare.value.title)
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToLinkedIn = () => {
  if (!articleToShare.value) return
  const url = encodeURIComponent(window.location.origin + `/article/${articleToShare.value.id}`)
  const title = encodeURIComponent(articleToShare.value.title)
  const summary = encodeURIComponent(articleToShare.value.excerpt)
  window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}&summary=${summary}`, '_blank', 'width=600,height=400')
  closeShareModal()
}

const shareToWhatsApp = () => {
  if (!articleToShare.value) return
  const url = encodeURIComponent(window.location.origin + `/article/${articleToShare.value.id}`)
  const text = encodeURIComponent(`${articleToShare.value.title} - ${articleToShare.value.excerpt}`)
  window.open(`https://wa.me/?text=${text}%20${url}`, '_blank')
  closeShareModal()
}

const shareToTelegram = () => {
  if (!articleToShare.value) return
  const url = encodeURIComponent(window.location.origin + `/article/${articleToShare.value.id}`)
  const text = encodeURIComponent(`${articleToShare.value.title} - ${articleToShare.value.excerpt}`)
  window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank')
  closeShareModal()
}

const copyLink = async () => {
  if (!articleToShare.value) return
  try {
    await navigator.clipboard.writeText(window.location.origin + `/article/${articleToShare.value.id}`)
    alert('Link copied to clipboard!')
    closeShareModal()
  } catch (error) {
    console.error('Failed to copy link:', error)
    alert('Failed to copy link. Please try again.')
  }
}

// Lifecycle
onMounted(async () => {
  await loadNews()
  await loadTrendingTopics()
})

// Watch for category changes to reset pagination
import { watch } from 'vue'
watch(selectedCategory, () => {
  currentPage.value = 1
})

watch(sortBy, () => {
  currentPage.value = 1
})
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.card {
  @apply bg-gray-800 rounded-lg border border-gray-700 overflow-hidden;
}

.btn-outline {
  @apply border border-gray-600 text-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-700 hover:border-gray-500 transition-all duration-200;
}

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.news-container {
  background-color: rgba(31, 41, 55, 1);
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid rgba(55, 65, 81, 1);
}

.aspect-w-16 {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.aspect-h-9 {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}
</style>
