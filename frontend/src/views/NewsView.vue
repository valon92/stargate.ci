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

    <!-- News Categories -->
    <section class="py-16 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <span class="gradient-text">News Categories</span>
          </h2>
          <p class="text-xl text-gray-300 max-w-3xl mx-auto">
            Explore news and updates organized by category to find the information most relevant to you.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🚀</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Stargate Project</h3>
            <p class="text-gray-400 mb-4">
              Latest updates on the $500 billion AI infrastructure initiative by OpenAI, SoftBank, and Arm.
            </p>
            <button @click="filterByCategory('stargate')" class="btn-outline">
              View Stargate News
            </button>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">💎</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Cristal Intelligence</h3>
            <p class="text-gray-400 mb-4">
              News and insights about the revolutionary AI paradigm emphasizing transparency and ethical alignment.
            </p>
            <button @click="filterByCategory('cristal')" class="btn-outline">
              View Cristal News
            </button>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🏢</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Industry Impact</h3>
            <p class="text-gray-400 mb-4">
              How Stargate and Cristal Intelligence are transforming industries and creating new opportunities.
            </p>
            <button @click="filterByCategory('industry')" class="btn-outline">
              View Industry News
            </button>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🔬</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Research & Development</h3>
            <p class="text-gray-400 mb-4">
              Latest research findings, technical breakthroughs, and development milestones.
            </p>
            <button @click="filterByCategory('research')" class="btn-outline">
              View Research News
            </button>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">⚖️</span>
            </div>
            <h3 class="text-xl font-bold mb-4">AI Ethics & Governance</h3>
            <p class="text-gray-400 mb-4">
              Updates on ethical AI development, governance frameworks, and responsible AI practices.
            </p>
            <button @click="filterByCategory('ethics')" class="btn-outline">
              View Ethics News
            </button>
          </div>

          <div class="card text-center group hover:scale-105 transition-transform duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6">
              <span class="text-2xl">🌐</span>
            </div>
            <h3 class="text-xl font-bold mb-4">Global Impact</h3>
            <p class="text-gray-400 mb-4">
              How these technologies are affecting global markets, policies, and international cooperation.
            </p>
            <button @click="filterByCategory('global')" class="btn-outline">
              View Global News
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest News Section -->
    <section class="py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

        <div class="flex justify-between items-center mb-12">
          <div>
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
              <span class="gradient-text">Latest News</span>
            </h2>
            <p class="text-xl text-gray-300">
              {{ filteredNews.length }} {{ selectedCategory ? selectedCategory + ' ' : '' }}articles found
            </p>
          </div>
          <div class="flex gap-4">
            <select v-model="selectedCategory" @change="filterByCategory(selectedCategory)" class="bg-gray-800 border border-gray-600 text-white rounded-lg px-4 py-2">
              <option value="">All Categories</option>
              <option value="stargate">Stargate Project</option>
              <option value="cristal">Cristal Intelligence</option>
              <option value="industry">Industry Impact</option>
              <option value="research">Research & Development</option>
              <option value="ethics">AI Ethics & Governance</option>
              <option value="global">Global Impact</option>
            </select>
            <select v-model="sortBy" class="bg-gray-800 border border-gray-600 text-white rounded-lg px-4 py-2">
              <option value="date">Latest First</option>
              <option value="title">Alphabetical</option>
              <option value="category">By Category</option>
            </select>
          </div>
        </div>

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

        <!-- News Grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <article 
            v-for="article in paginatedNews" 
            :key="article.id"
            class="card group hover:scale-105 transition-all duration-300 hover:shadow-2xl"
          >
            <div class="relative">
              <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg mb-4 flex items-center justify-center">
                <span class="text-4xl">{{ article.icon }}</span>
              </div>
              <div class="absolute top-4 left-4">
                <span :class="getCategoryBadgeClass(article.category)" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ article.category }}
                </span>
              </div>
            </div>
            
            <div class="p-6">
              <h3 class="text-xl font-bold text-white mb-3 group-hover:text-primary-400 transition-colors">
                {{ article.title }}
              </h3>
              <p class="text-gray-400 mb-4 line-clamp-3">
                {{ article.excerpt }}
              </p>
              
              <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <span>{{ formatDate(article.publishedAt) }}</span>
                <span>{{ article.readTime }} min read</span>
              </div>
              
              <!-- Interactive Content -->
              <InteractiveContent
                :content-id="article.id"
                content-type="news"
                :initial-likes="Math.floor(Math.random() * 100) + 20"
                :initial-comments="[]"
              />

              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">By {{ article.author }}</span>
                <a 
                  :href="article.url" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  class="text-primary-400 hover:text-primary-300 font-medium transition-colors"
                >
                  Read More →
                </a>
              </div>
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

    <!-- Newsletter Signup -->
    <section class="py-24 bg-gray-800/30">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          <span class="gradient-text">Stay Updated</span>
        </h2>
        <p class="text-xl text-gray-300 mb-8">
          Get the latest news and updates about Stargate Project and Cristal Intelligence delivered to your inbox.
        </p>
        
        <div class="max-w-md mx-auto">
          <div class="flex gap-4">
            <input 
              v-model="email"
              type="email" 
              placeholder="Enter your email address"
              class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            >
            <button 
              @click="subscribeNewsletter"
              :disabled="!email || isSubscribing"
              class="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ isSubscribing ? 'Subscribing...' : 'Subscribe' }}
            </button>
          </div>
          <p class="text-sm text-gray-400 mt-3">
            We respect your privacy. Unsubscribe at any time.
          </p>
        </div>
      </div>
    </section>
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
    const response = await newsApiService.generateNewsArticles(category, 15)
    
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
    trendingTopics.value = await newsApiService.getTrendingTopics()
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
    const response = await newsApiService.searchArticles(searchQuery.value, selectedCategory.value)
    
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

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
