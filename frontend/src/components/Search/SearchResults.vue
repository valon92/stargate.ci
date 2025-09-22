<template>
  <div class="space-y-4 sm:space-y-6">
    <!-- Search Header -->
    <div class="border-b border-gray-700 pb-3 sm:pb-4">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
        <div>
          <h1 class="text-xl sm:text-2xl font-bold text-white">
            Search Results
          </h1>
          <p class="mt-1 text-sm sm:text-base text-gray-400">
            {{ totalResults }} result{{ totalResults !== 1 ? 's' : '' }} for 
            <span class="text-primary-400">"{{ query }}"</span>
          </p>
        </div>
        
        <!-- Search Type Filter -->
        <div class="flex items-center space-x-2 sm:space-x-2">
          <label class="text-xs sm:text-sm text-gray-400 flex-shrink-0">Filter:</label>
          <select
            v-model="selectedType"
            @change="handleTypeChange"
            class="bg-gray-800 border border-gray-600 text-white rounded-lg px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent min-w-0 flex-1 sm:flex-initial"
          >
            <option value="all">All Results</option>
            <option value="content">Content</option>
            <option value="community">Community</option>
            <option value="users">Users</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="space-y-3 sm:space-y-4">
      <div v-for="n in 5" :key="n" class="animate-pulse">
        <div class="bg-gray-800 rounded-lg p-4 sm:p-6">
          <div class="flex items-start space-x-3 sm:space-x-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-700 rounded-lg flex-shrink-0"></div>
            <div class="flex-1 space-y-2 sm:space-y-3 min-w-0">
              <div class="h-4 sm:h-5 bg-gray-700 rounded w-3/4"></div>
              <div class="h-3 sm:h-4 bg-gray-700 rounded w-full"></div>
              <div class="h-3 sm:h-4 bg-gray-700 rounded w-1/2"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-red-400">Search Error</h3>
      <p class="mt-1 text-sm text-gray-500">{{ error }}</p>
      <button
        @click="retrySearch"
        class="mt-4 px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors duration-200"
      >
        Try Again
      </button>
    </div>

    <!-- No Results -->
    <div v-else-if="results.length === 0 && !loading" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-300">No results found</h3>
      <p class="mt-1 text-sm text-gray-500">
        Try adjusting your search terms or removing filters.
      </p>
      
      <!-- Search Suggestions -->
      <div v-if="suggestions.length > 0" class="mt-6">
        <p class="text-sm text-gray-400 mb-3">Try these suggestions:</p>
        <div class="flex flex-wrap justify-center gap-2">
          <button
            v-for="suggestion in suggestions.slice(0, 5)"
            :key="suggestion"
            @click="searchSuggestion(suggestion)"
            class="px-3 py-1 bg-gray-800 text-gray-300 rounded-full text-sm hover:bg-gray-700 transition-colors duration-200"
          >
            {{ suggestion }}
          </button>
        </div>
      </div>
    </div>

    <!-- Search Results -->
    <div v-else class="space-y-3 sm:space-y-4">
      <!-- Result Item -->
      <div
        v-for="result in results"
        :key="`${result.type}-${result.id}`"
        class="bg-gray-800 rounded-lg p-4 sm:p-6 hover:bg-gray-750 transition-colors duration-200"
      >
        <div class="flex items-start space-x-3 sm:space-x-4">
          <!-- Result Type Icon -->
          <div class="flex-shrink-0">
            <div :class="getTypeIconClass(result.type)" class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg flex items-center justify-center">
              <component :is="getTypeIcon(result.type)" class="w-5 h-5 sm:w-6 sm:h-6" />
            </div>
          </div>

          <!-- Result Content -->
          <div class="flex-1 min-w-0">
            <!-- Title -->
            <h3 class="text-base sm:text-lg font-semibold text-white mb-1.5 sm:mb-2">
              <RouterLink
                :to="result.url"
                class="hover:text-primary-400 transition-colors duration-200 line-clamp-2"
                v-html="highlightMatch(result.title, query)"
              />
            </h3>

            <!-- Description/Content -->
            <p
              class="text-sm sm:text-base text-gray-300 mb-2 sm:mb-3 line-clamp-2"
              v-html="highlightMatch(result.description || result.content || '', query)"
            />

            <!-- Metadata -->
            <div class="flex flex-wrap items-center gap-x-3 sm:gap-x-4 gap-y-1 text-xs sm:text-sm text-gray-500">
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                {{ result.author }}
              </span>
              
              <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                {{ result.category }}
              </span>

              <span class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ formatDate(result.updated_at) }}
              </span>

              <!-- Content-specific metadata -->
              <span v-if="result.type === 'content' && result.difficulty" class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Level {{ result.difficulty }}
              </span>

              <!-- Community-specific metadata -->
              <template v-if="result.type === 'community'">
                <span v-if="result.views" class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  {{ result.views }}
                </span>
                
                <span v-if="result.likes" class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                  {{ result.likes }}
                </span>
              </template>
            </div>

            <!-- Tags -->
            <div v-if="result.tags && result.tags.length > 0" class="mt-3 flex flex-wrap gap-2">
              <span
                v-for="tag in result.tags.slice(0, 3)"
                :key="tag"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-500/20 text-primary-300"
              >
                {{ tag }}
              </span>
              <span
                v-if="result.tags.length > 3"
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-400"
              >
                +{{ result.tags.length - 3 }} more
              </span>
            </div>
          </div>

          <!-- Relevance Score (dev only) -->
          <div v-if="showRelevanceScore" class="flex-shrink-0">
            <div class="text-xs text-gray-500 bg-gray-700 px-2 py-1 rounded">
              {{ result.relevance_score }}
            </div>
          </div>
        </div>
      </div>

      <!-- Load More Button -->
      <div v-if="hasMoreResults" class="text-center pt-6">
        <button
          @click="loadMore"
          :disabled="loadingMore"
          class="px-6 py-3 bg-primary-500 text-white rounded-lg hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
        >
          {{ loadingMore ? 'Loading...' : 'Load More Results' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, h, onMounted, watch } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { backendApi } from '../../services/backendApi'

interface SearchResult {
  id: number
  type: 'content' | 'community' | 'user'
  title: string
  description?: string
  content?: string
  url: string
  author: string
  category: string
  tags?: string[]
  updated_at: string
  relevance_score: number
  
  // Content-specific
  difficulty?: number
  duration?: number
  
  // Community-specific
  views?: number
  likes?: number
  comments?: number
  is_solved?: boolean
  is_pinned?: boolean
}

interface Props {
  query: string
  searchType?: 'all' | 'content' | 'community' | 'users'
  showRelevanceScore?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  searchType: 'all',
  showRelevanceScore: false
})

const router = useRouter()

// Reactive state
const results = ref<SearchResult[]>([])
const suggestions = ref<string[]>([])
const loading = ref(false)
const loadingMore = ref(false)
const error = ref('')
const selectedType = ref(props.searchType)
const currentPage = ref(1)
const totalResults = ref(0)
const hasMoreResults = ref(false)

// Computed
const shouldSearch = computed(() => props.query.trim().length >= 2)

// Methods
const performSearch = async (page = 1, append = false) => {
  if (!shouldSearch.value) return

  try {
    if (page === 1) {
      loading.value = true
      error.value = ''
    } else {
      loadingMore.value = true
    }

    const response = await backendApi.search(props.query, {
      type: selectedType.value,
      page: page.toString(),
      per_page: '20'
    })

    if (response.status === 'success') {
      const searchData = response.data
      
      if (append) {
        results.value.push(...searchData.results)
      } else {
        results.value = searchData.results
        suggestions.value = searchData.suggestions || []
      }
      
      totalResults.value = searchData.total
      currentPage.value = page
      hasMoreResults.value = searchData.results.length === 20 // Assume more if we got a full page
    } else {
      throw new Error(response.message || 'Search failed')
    }
  } catch (err: any) {
    error.value = err.message || 'An error occurred while searching'
    console.error('Search error:', err)
  } finally {
    loading.value = false
    loadingMore.value = false
  }
}

const loadMore = () => {
  if (!loadingMore.value && hasMoreResults.value) {
    performSearch(currentPage.value + 1, true)
  }
}

const retrySearch = () => {
  error.value = ''
  performSearch()
}

const handleTypeChange = () => {
  currentPage.value = 1
  hasMoreResults.value = false
  
  // Update URL
  router.push({
    query: {
      q: props.query,
      type: selectedType.value
    }
  })
  
  performSearch()
}

const searchSuggestion = (suggestion: string) => {
  router.push({
    path: '/search',
    query: {
      q: suggestion,
      type: selectedType.value
    }
  })
}

const getTypeIcon = (type: string) => {
  switch (type) {
    case 'content':
      return h('svg', {
        fill: 'none',
        stroke: 'currentColor',
        viewBox: '0 0 24 24'
      }, [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
        })
      ])
    case 'community':
      return h('svg', {
        fill: 'none',
        stroke: 'currentColor',
        viewBox: '0 0 24 24'
      }, [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'
        })
      ])
    case 'user':
      return h('svg', {
        fill: 'none',
        stroke: 'currentColor',
        viewBox: '0 0 24 24'
      }, [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
        })
      ])
    default:
      return h('svg', {
        fill: 'none',
        stroke: 'currentColor',
        viewBox: '0 0 24 24'
      }, [
        h('path', {
          'stroke-linecap': 'round',
          'stroke-linejoin': 'round',
          'stroke-width': '2',
          d: 'M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33'
        })
      ])
  }
}

const getTypeIconClass = (type: string) => {
  switch (type) {
    case 'content':
      return 'bg-blue-500/20 text-blue-400'
    case 'community':
      return 'bg-green-500/20 text-green-400'
    case 'user':
      return 'bg-purple-500/20 text-purple-400'
    default:
      return 'bg-gray-500/20 text-gray-400'
  }
}

const highlightMatch = (text: string, query: string): string => {
  if (!text || !query) return text
  
  const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi')
  return text.replace(regex, '<mark class="bg-yellow-200 text-yellow-900 px-1 rounded">$1</mark>')
}

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInDays = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60 * 24))
  
  if (diffInDays === 0) return 'Today'
  if (diffInDays === 1) return 'Yesterday'
  if (diffInDays < 7) return `${diffInDays} days ago`
  if (diffInDays < 30) return `${Math.floor(diffInDays / 7)} weeks ago`
  if (diffInDays < 365) return `${Math.floor(diffInDays / 30)} months ago`
  return `${Math.floor(diffInDays / 365)} years ago`
}

// Watchers
watch(() => props.query, () => {
  if (shouldSearch.value) {
    currentPage.value = 1
    hasMoreResults.value = false
    performSearch()
  } else {
    results.value = []
    suggestions.value = []
    totalResults.value = 0
  }
})

watch(() => props.searchType, (newType) => {
  selectedType.value = newType
  if (shouldSearch.value) {
    currentPage.value = 1
    hasMoreResults.value = false
    performSearch()
  }
})

// Lifecycle
onMounted(() => {
  if (shouldSearch.value) {
    performSearch()
  }
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
