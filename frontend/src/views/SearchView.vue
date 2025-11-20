<template>
  <div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <div class="bg-gray-800/50 border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
          <!-- Search Box -->
          <div class="flex-1">
            <SearchBox
              ref="searchBoxRef"
              :placeholder="'Search articles, videos, FAQs, users...'"
              :auto-focus="true"
              @search="performSearch"
              @suggestion-select="handleSuggestionSelect"
            />
          </div>
          
          <!-- Filters -->
          <div class="flex flex-wrap gap-4 items-center">
            <!-- Type Filter -->
            <select
              v-model="filters.type"
              @change="applyFilters"
              class="px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
              <option value="all">All Types</option>
              <option value="articles">Articles</option>
              <option value="videos">Videos</option>
              <option value="faqs">FAQs</option>
              <option value="users">Users</option>
              <option value="comments">Comments</option>
            </select>

            <!-- Sort Filter -->
            <select
              v-model="filters.sort"
              @change="applyFilters"
              class="px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
              <option value="relevance">Relevance</option>
              <option value="date">Date</option>
              <option value="title">Title</option>
              <option value="author">Author</option>
            </select>

            <!-- Clear Filters -->
            <button
              @click="clearFilters"
              class="px-3 py-2 text-gray-400 hover:text-white text-sm transition-colors"
            >
              Clear Filters
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Search Results -->
      <div v-if="hasSearched">
        <!-- Results Header -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-white mb-2">
            Search Results
          </h1>
          <p class="text-gray-400">
            {{ totalResults }} results for "{{ currentQuery }}"
            <span v-if="isLoading" class="ml-2 text-primary-500">Searching...</span>
          </p>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
        </div>

        <!-- No Results -->
        <div v-else-if="results.length === 0" class="text-center py-12">
          <div class="text-gray-400 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-white mb-2">No results found</h3>
          <p class="text-gray-400 mb-4">Try different keywords or check your spelling</p>
          <div class="text-sm text-gray-500">
            <p>Search tips:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
              <li>Use more general keywords</li>
              <li>Check your spelling</li>
              <li>Try different search terms</li>
              <li>Use fewer filters</li>
            </ul>
          </div>
        </div>

        <!-- Results List -->
        <div v-else class="space-y-4">
          <SearchResultCard
            v-for="result in results"
            :key="`${result.type}-${result.id}`"
            :result="result"
            @click="handleResultClick"
          />
        </div>

        <!-- Load More Button -->
        <div v-if="hasMoreResults" class="mt-8 text-center">
          <button
            @click="loadMore"
            :disabled="isLoadingMore"
            class="px-6 py-3 bg-primary-600 hover:bg-primary-700 disabled:bg-gray-600 text-white rounded-lg font-medium transition-colors"
          >
            <span v-if="isLoadingMore">Loading...</span>
            <span v-else>Load More Results</span>
          </button>
        </div>
      </div>

      <!-- Initial State -->
      <div v-else class="text-center py-12">
        <div class="text-gray-400 mb-4">
          <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-white mb-4">Search Stargate.ci</h2>
        <p class="text-gray-400 mb-8 max-w-2xl mx-auto">
          Find articles, videos, FAQs, and more across our platform. Use the search box above to get started.
        </p>
        
        <!-- Popular Searches -->
        <div v-if="popularSearches.length > 0" class="max-w-2xl mx-auto">
          <h3 class="text-lg font-medium text-white mb-4">Popular Searches</h3>
          <div class="flex flex-wrap gap-2 justify-center">
            <button
              v-for="search in popularSearches.slice(0, 8)"
              :key="search"
              @click="searchForTerm(search)"
              class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white rounded-lg text-sm transition-colors"
            >
              {{ search }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import SearchBox from '@/components/SearchBox.vue'
import SearchResultCard from '@/components/SearchResultCard.vue'
import { searchService, type SearchResult } from '@/services/searchService'

const router = useRouter()
const route = useRoute()

// Meta tags
useHead({
  title: 'Search - Stargate.ci',
  meta: [
    { name: 'description', content: 'Search articles, videos, FAQs, and more on Stargate.ci platform' }
  ]
})

// Reactive data
const searchBoxRef = ref()
const currentQuery = ref('')
const results = ref<SearchResult[]>([])
const totalResults = ref(0)
const isLoading = ref(false)
const isLoadingMore = ref(false)
const hasSearched = ref(false)
const popularSearches = ref<string[]>([])

// Filters
const filters = ref({
  type: 'all',
  sort: 'relevance',
  category: '',
  author: '',
  date_from: '',
  date_to: ''
})

// Computed properties
const hasMoreResults = computed(() => {
  return results.value.length < totalResults.value
})

// Methods
const performSearch = async (query: string) => {
  if (!query.trim()) return

  currentQuery.value = query
  isLoading.value = true
  hasSearched.value = true
  results.value = []

  try {
    const response = await searchService.search(query, {
      type: filters.value.type as 'all' | 'articles' | 'videos' | 'faqs' | 'users' | 'comments',
      sort: filters.value.sort as 'relevance' | 'date' | 'title' | 'author',
      category: filters.value.category,
      author: filters.value.author,
      date_from: filters.value.date_from,
      date_to: filters.value.date_to,
      limit: 20,
      page: 1
    })

    results.value = response.data.results || []
    totalResults.value = response.data.total || 0
  } catch (error) {
    console.error('Search failed:', error)
    results.value = []
    totalResults.value = 0
  } finally {
    isLoading.value = false
  }
}

const handleSuggestionSelect = (suggestion: string) => {
  performSearch(suggestion)
}

const handleResultClick = (result: SearchResult) => {
  // Navigate to the result URL
  if (result.url.startsWith('/')) {
    router.push(result.url)
  } else {
    window.open(result.url, '_blank')
  }
}

const searchForTerm = (term: string) => {
  if (searchBoxRef.value) {
    searchBoxRef.value.setQuery(term)
  }
  performSearch(term)
}

const applyFilters = () => {
  if (currentQuery.value) {
    performSearch(currentQuery.value)
  }
}

const clearFilters = () => {
  filters.value = {
    type: 'all',
    sort: 'relevance',
    category: '',
    author: '',
    date_from: '',
    date_to: ''
  }
  if (currentQuery.value) {
    performSearch(currentQuery.value)
  }
}

const loadMore = async () => {
  if (!currentQuery.value || isLoadingMore.value) return

  isLoadingMore.value = true
  try {
    const response = await searchService.search(currentQuery.value, {
      type: filters.value.type as 'all' | 'articles' | 'videos' | 'faqs' | 'users' | 'comments',
      sort: filters.value.sort as 'relevance' | 'date' | 'title' | 'author',
      category: filters.value.category,
      author: filters.value.author,
      date_from: filters.value.date_from,
      date_to: filters.value.date_to,
      limit: 20,
      page: Math.floor(results.value.length / 20) + 1
    })

    results.value = [...results.value, ...(response.data.results || [])]
  } catch (error) {
    console.error('Load more failed:', error)
  } finally {
    isLoadingMore.value = false
  }
}

// Load popular searches on mount
onMounted(async () => {
  try {
    popularSearches.value = await searchService.getPopularSearches()
    
    // Check if there's a query in the URL
    const queryParam = route.query.q as string
    if (queryParam) {
      currentQuery.value = queryParam
      if (searchBoxRef.value) {
        searchBoxRef.value.setQuery(queryParam)
      }
      performSearch(queryParam)
    }
  } catch (error) {
    console.error('Failed to load popular searches:', error)
  }
})

// Watch for route changes
watch(() => route.query.q, (newQuery) => {
  if (newQuery && typeof newQuery === 'string') {
    currentQuery.value = newQuery
    if (searchBoxRef.value) {
      searchBoxRef.value.setQuery(newQuery)
    }
    performSearch(newQuery)
  }
})
</script>
