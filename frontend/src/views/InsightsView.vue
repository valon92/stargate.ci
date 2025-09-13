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
            Comprehensive insights, analysis, and educational content about AI infrastructure, machine learning, and the future of technology.
          </p>
          
          <!-- Search Bar -->
          <div class="mt-8 max-w-2xl mx-auto">
            <div class="relative">
              <input
                v-model="searchQuery"
                @input="handleSearch"
                type="text"
                placeholder="Search articles, topics, and insights..."
                class="w-full px-6 py-4 bg-gray-800/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              >
              <div class="absolute inset-y-0 right-0 flex items-center pr-6">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-800/30">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div class="text-center">
            <div class="text-3xl font-bold text-primary-400">{{ articles.length }}</div>
            <div class="text-gray-400 mt-2">Expert Articles</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-secondary-400">{{ categories.length - 1 }}</div>
            <div class="text-gray-400 mt-2">Technology Categories</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-primary-400">{{ totalReadTime }}</div>
            <div class="text-gray-400 mt-2">Minutes of Content</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-secondary-400">{{ newsItems.length }}</div>
            <div class="text-gray-400 mt-2">Latest Updates</div>
          </div>
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

    <!-- Search Results -->
    <section v-if="searchMode && searchQuery" class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-8">
          Search Results for "{{ searchQuery }}"
          <span class="text-gray-400 text-lg font-normal">
            ({{ searchResults.articles.length + searchResults.faqs.length }} results)
          </span>
        </h2>

        <!-- Search Results Articles -->
        <div v-if="searchResults.articles.length > 0" class="mb-12">
          <h3 class="text-xl font-semibold mb-6 text-primary-400">Articles</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
              v-for="article in searchResults.articles"
              :key="article.id"
              class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden hover:bg-gray-800/70 transition-all duration-200 group"
            >
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-2xl">{{ article.icon }}</span>
                  <span class="px-3 py-1 text-xs rounded-full bg-primary-500/20 text-primary-400">
                    {{ article.category }}
                  </span>
                </div>
                <h3 class="text-xl font-semibold mb-3 group-hover:text-primary-400 transition-colors">
                  {{ article.title }}
                </h3>
                <p class="text-gray-400 mb-4 line-clamp-3">{{ article.excerpt }}</p>
                <div class="flex items-center justify-between text-sm text-gray-500">
                  <span>{{ article.author }}</span>
                  <span>{{ article.read_time }} min read</span>
                </div>
                <RouterLink
                  :to="`/insights/${article.slug}`"
                  class="inline-block mt-4 text-primary-400 hover:text-primary-300 font-medium"
                >
                  Read More →
                </RouterLink>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Results FAQs -->
        <div v-if="searchResults.faqs.length > 0">
          <h3 class="text-xl font-semibold mb-6 text-secondary-400">Related FAQs</h3>
          <div class="space-y-4">
            <div
              v-for="faq in searchResults.faqs"
              :key="faq.id"
              class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6"
            >
              <h4 class="text-lg font-medium mb-2 text-white">{{ faq.question }}</h4>
              <p class="text-gray-400">{{ faq.answer }}</p>
            </div>
          </div>
        </div>

        <!-- No Results -->
        <div v-if="searchResults.articles.length === 0 && searchResults.faqs.length === 0" class="text-center py-12">
          <div class="text-6xl mb-4">🔍</div>
          <h3 class="text-xl font-semibold mb-2">No results found</h3>
          <p class="text-gray-400">Try different keywords or browse our categories above.</p>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section v-else class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Featured Article -->
        <div v-if="filteredArticles.length > 0" class="mb-16">
          <h2 class="text-2xl font-bold mb-8">Featured Article</h2>
          <div class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 backdrop-blur-sm rounded-2xl overflow-hidden border border-gray-700/50">
            <div class="md:flex">
              <div class="md:w-2/3 p-8">
                <div class="flex items-center gap-4 mb-4">
                  <span class="text-3xl">{{ filteredArticles[0].icon }}</span>
                  <span class="px-3 py-1 text-sm rounded-full bg-primary-500/20 text-primary-400">
                    {{ filteredArticles[0].category }}
                  </span>
                </div>
                <h3 class="text-3xl font-bold mb-4">{{ filteredArticles[0].title }}</h3>
                <p class="text-xl text-gray-300 mb-6">{{ filteredArticles[0].excerpt }}</p>
                <div class="flex items-center gap-6 text-sm text-gray-400 mb-6">
                  <span>By {{ filteredArticles[0].author }}</span>
                  <span>{{ filteredArticles[0].read_time }} min read</span>
                  <span>{{ formatDate(filteredArticles[0].published_at) }}</span>
                </div>
                <RouterLink
                  :to="`/insights/${filteredArticles[0].slug}`"
                  class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-200"
                >
                  Read Full Article
                  <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </RouterLink>
              </div>
              <div class="md:w-1/3 bg-gradient-to-br from-primary-500/20 to-secondary-500/20 p-8 flex items-center justify-center">
                <div class="text-center">
                  <div class="text-6xl mb-4">{{ filteredArticles[0].icon }}</div>
                  <div class="text-lg font-medium text-gray-300">{{ filteredArticles[0].category }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Articles Grid -->
        <div class="mb-16">
          <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold">
              {{ selectedCategory === 'all' ? 'All Articles' : `${selectedCategory.toUpperCase()} Articles` }}
              <span class="text-gray-400 text-lg font-normal">({{ filteredArticles.length }})</span>
            </h2>
          </div>

          <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="i in 6" :key="i" class="bg-gray-800/50 rounded-xl p-6 animate-pulse">
              <div class="h-4 bg-gray-700 rounded mb-4"></div>
              <div class="h-6 bg-gray-700 rounded mb-3"></div>
              <div class="h-20 bg-gray-700 rounded mb-4"></div>
              <div class="h-4 bg-gray-700 rounded w-1/2"></div>
            </div>
          </div>

          <div v-else-if="filteredArticles.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
              v-for="article in filteredArticles.slice(1)"
              :key="article.id"
              class="bg-gray-800/50 backdrop-blur-sm rounded-xl overflow-hidden hover:bg-gray-800/70 transition-all duration-200 group border border-gray-700/50 hover:border-primary-500/30"
            >
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-2xl">{{ article.icon }}</span>
                  <span class="px-3 py-1 text-xs rounded-full bg-primary-500/20 text-primary-400">
                    {{ article.category }}
                  </span>
                </div>
                <h3 class="text-xl font-semibold mb-3 group-hover:text-primary-400 transition-colors">
                  {{ article.title }}
                </h3>
                <p class="text-gray-400 mb-4 line-clamp-3">{{ article.excerpt }}</p>
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                  <span>{{ article.author }}</span>
                  <span>{{ article.read_time }} min read</span>
                </div>
                <RouterLink
                  :to="`/insights/${article.slug}`"
                  class="inline-flex items-center text-primary-400 hover:text-primary-300 font-medium group"
                >
                  Read More
                  <svg class="ml-1 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </RouterLink>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-12">
            <div class="text-6xl mb-4">📄</div>
            <h3 class="text-xl font-semibold mb-2">No articles found</h3>
            <p class="text-gray-400">Try selecting a different category or check back later.</p>
          </div>
        </div>

        <!-- Latest News -->
        <div v-if="newsItems.length > 0" class="mb-16">
          <h2 class="text-2xl font-bold mb-8">Latest News & Updates</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
              v-for="news in newsItems"
              :key="news.id"
              class="bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50 hover:border-primary-500/30 transition-all duration-200"
            >
              <div class="flex items-center justify-between mb-3">
                <span class="px-3 py-1 text-xs rounded-full bg-secondary-500/20 text-secondary-400">
                  {{ news.category }}
                </span>
                <span class="text-sm text-gray-500">{{ formatDate(news.date) }}</span>
              </div>
              <h3 class="text-lg font-semibold mb-2">{{ news.title }}</h3>
              <p class="text-gray-400 mb-4">{{ news.summary }}</p>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">{{ news.source }}</span>
                <a
                  v-if="news.url"
                  :href="news.url"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="text-primary-400 hover:text-primary-300 text-sm font-medium"
                >
                  Read More →
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { contentApi, type Article } from '../services/contentApi'
import { RouterLink } from 'vue-router'

// Reactive data
const selectedCategory = ref('all')
const articles = ref<Article[]>([])
const searchQuery = ref('')
const searchResults = ref<{ articles: Article[]; faqs: any[] }>({ articles: [], faqs: [] })
const newsItems = ref<any[]>([])
const loading = ref(true)
const searchMode = ref(false)
const categories = ref<{ key: string; name: string }[]>([
  { key: 'all', name: 'All Articles' }
])

// Computed properties
const filteredArticles = computed(() => {
  if (selectedCategory.value === 'all') {
    return articles.value
  }
  return articles.value.filter(article => 
    article.category.toLowerCase() === selectedCategory.value.toLowerCase()
  )
})

const totalReadTime = computed(() => {
  return articles.value.reduce((total, article) => total + article.read_time, 0)
})

// Methods
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const handleSearch = async () => {
  if (searchQuery.value.trim().length > 2) {
    searchMode.value = true
    searchResults.value = await contentApi.searchContent(searchQuery.value)
  } else {
    searchMode.value = false
    searchResults.value = { articles: [], faqs: [] }
  }
}

const loadData = async () => {
  try {
    loading.value = true
    
    // Load articles
    const articlesData = await contentApi.getArticles()
    articles.value = articlesData.data
    
    // Load categories from articles
    const uniqueCategories = [...new Set(articles.value.map(article => article.category))]
    categories.value = [
      { key: 'all', name: 'All Articles' },
      ...uniqueCategories.map(cat => ({ key: cat.toLowerCase(), name: cat }))
    ]
    
    // Load news
    newsItems.value = await contentApi.getNews(4)
  } catch (error) {
    console.error('Error loading data:', error)
  } finally {
    loading.value = false
  }
}

// Watch for category changes
watch(selectedCategory, () => {
  searchMode.value = false
  searchQuery.value = ''
})

// Initialize
onMounted(() => {
  loadData()
})
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
