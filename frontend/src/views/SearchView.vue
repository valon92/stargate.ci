<template>
  <div class="min-h-screen bg-gray-900 text-white">
    <!-- Search Header -->
    <div class="bg-gray-800 border-b border-gray-700">
      <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6">
        <div class="space-y-3 sm:space-y-4">
          <!-- Page Title -->
          <div class="text-center sm:text-left">
            <h1 class="text-2xl sm:text-3xl font-bold gradient-text">Search</h1>
            <p class="mt-1 text-sm sm:text-base text-gray-400">
              Find content, discussions, and community members
            </p>
          </div>

          <!-- Search Bar -->
          <div class="w-full sm:max-w-2xl">
            <SearchBar
              :search-type="searchType"
              :auto-focus="!hasQuery"
              @search="handleSearch"
              @clear="handleClear"
              ref="searchBarRef"
            />
          </div>

          <!-- Quick Filters -->
          <div class="flex flex-wrap gap-1.5 sm:gap-2 pt-2">
            <button
              v-for="filter in quickFilters"
              :key="filter.value"
              @click="setSearchType(filter.value as 'all' | 'content' | 'community' | 'users')"
              :class="[
                'px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs sm:text-sm font-medium transition-colors duration-200 flex items-center',
                searchType === filter.value
                  ? 'bg-primary-500 text-white'
                  : 'bg-gray-700 text-gray-300 hover:bg-gray-600'
              ]"
            >
              <component :is="filter.icon" class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-1.5" />
              <span class="hidden sm:inline">{{ filter.label }}</span>
              <span class="sm:hidden">{{ filter.label.charAt(0) }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Search Content -->
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-8">
      <!-- Search Results -->
      <div v-if="hasQuery">
        <SearchResults
          :query="query"
          :search-type="searchType"
          :show-relevance-score="isDev"
        />
      </div>

      <!-- Default State (No Query) -->
      <div v-else class="space-y-6 sm:space-y-8">
        <!-- Popular Searches -->
        <div>
          <h2 class="text-lg sm:text-xl font-semibold text-white mb-3 sm:mb-4">Popular Searches</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            <button
              v-for="popular in popularSearches"
              :key="popular"
              @click="searchPopular(popular)"
              class="p-3 sm:p-4 bg-gray-800 rounded-lg text-left hover:bg-gray-700 transition-colors duration-200"
            >
              <div class="flex items-center">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="text-sm sm:text-base text-gray-300 truncate">{{ popular }}</span>
              </div>
            </button>
          </div>
        </div>

        <!-- Recent Searches -->
        <div v-if="recentSearches.length > 0">
          <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h2 class="text-lg sm:text-xl font-semibold text-white">Recent Searches</h2>
            <button
              @click="clearRecentSearches"
              class="text-xs sm:text-sm text-gray-400 hover:text-white transition-colors duration-200"
            >
              Clear All
            </button>
          </div>
          <div class="flex flex-wrap gap-1.5 sm:gap-2">
            <button
              v-for="recent in recentSearches.slice(0, 10)"
              :key="recent"
              @click="searchRecent(recent)"
              class="flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 bg-gray-800 rounded-lg text-xs sm:text-sm text-gray-300 hover:bg-gray-700 transition-colors duration-200"
            >
              <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="truncate max-w-[120px] sm:max-w-none">{{ recent }}</span>
            </button>
          </div>
        </div>

        <!-- Search Tips -->
        <div>
          <h2 class="text-lg sm:text-xl font-semibold text-white mb-3 sm:mb-4">Search Tips</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="space-y-3 sm:space-y-4">
              <div class="flex items-start">
                <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-primary-500/20 rounded-lg flex items-center justify-center mt-0.5">
                  <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                </div>
                <div class="ml-2.5 sm:ml-3">
                  <h3 class="text-sm font-medium text-white">Use keywords</h3>
                  <p class="text-xs sm:text-sm text-gray-400">Search for specific terms like "AI tutorial" or "cloud computing"</p>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-green-500/20 rounded-lg flex items-center justify-center mt-0.5">
                  <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.586V4z" />
                  </svg>
                </div>
                <div class="ml-2.5 sm:ml-3">
                  <h3 class="text-sm font-medium text-white">Filter by type</h3>
                  <p class="text-xs sm:text-sm text-gray-400">Narrow down results to content, community posts, or users</p>
                </div>
              </div>
            </div>
            
            <div class="space-y-3 sm:space-y-4">
              <div class="flex items-start">
                <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-blue-500/20 rounded-lg flex items-center justify-center mt-0.5">
                  <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
                <div class="ml-2.5 sm:ml-3">
                  <h3 class="text-sm font-medium text-white">Use keyboard shortcuts</h3>
                  <p class="text-xs sm:text-sm text-gray-400 hidden sm:block">Press <kbd class="px-1.5 py-0.5 bg-gray-700 rounded text-xs">Ctrl+K</kbd> to quickly focus the search bar</p>
                  <p class="text-xs text-gray-400 sm:hidden">Use shortcuts for quick access</p>
                </div>
              </div>
              
              <div class="flex items-start">
                <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-purple-500/20 rounded-lg flex items-center justify-center mt-0.5">
                  <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                </div>
                <div class="ml-2.5 sm:ml-3">
                  <h3 class="text-sm font-medium text-white">Get suggestions</h3>
                  <p class="text-xs sm:text-sm text-gray-400">Start typing to see search suggestions and auto-complete</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch, h } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import SearchBar from '../components/Search/SearchBar.vue'
import SearchResults from '../components/Search/SearchResults.vue'

const route = useRoute()
const router = useRouter()

// Reactive state
const searchBarRef = ref(null)
const query = ref('')
const searchType = ref<'all' | 'content' | 'community' | 'users'>('all')
const recentSearches = ref<string[]>([])
const popularSearches = ref([
  'AI tutorial',
  'Cloud computing',
  'Machine learning',
  'Data science',
  'Stargate project',
  'Quantum computing',
  'Blockchain',
  'DevOps',
  'Cybersecurity',
  'Web development'
])

// Computed
const hasQuery = computed(() => query.value.trim().length > 0)
const isDev = computed(() => import.meta.env.MODE === 'development')

const quickFilters = computed(() => [
  {
    value: 'all',
    label: 'All',
    icon: h('svg', {
      fill: 'none',
      stroke: 'currentColor',
      viewBox: '0 0 24 24'
    }, [
      h('path', {
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        'stroke-width': '2',
        d: 'M4 6h16M4 10h16M4 14h16M4 18h16'
      })
    ])
  },
  {
    value: 'content',
    label: 'Content',
    icon: h('svg', {
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
  },
  {
    value: 'community',
    label: 'Community',
    icon: h('svg', {
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
  },
  {
    value: 'users',
    label: 'Users',
    icon: h('svg', {
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
  }
])

// Methods
const handleSearch = (searchQuery: string, type: string) => {
  query.value = searchQuery
  searchType.value = type as typeof searchType.value
  
  // Update URL
  router.push({
    path: '/search',
    query: {
      q: searchQuery,
      type: type
    }
  })
}

const handleClear = () => {
  query.value = ''
  router.push('/search')
}

const setSearchType = (type: typeof searchType.value) => {
  searchType.value = type
  
  if (hasQuery.value) {
    router.push({
      path: '/search',
      query: {
        q: query.value,
        type: type
      }
    })
  }
}

const searchPopular = (popularQuery: string) => {
  handleSearch(popularQuery, searchType.value)
}

const searchRecent = (recentQuery: string) => {
  handleSearch(recentQuery, searchType.value)
}

const loadRecentSearches = () => {
  try {
    const stored = localStorage.getItem('search_recent_searches')
    if (stored) {
      recentSearches.value = JSON.parse(stored)
    }
  } catch (error) {
    console.error('Error loading recent searches:', error)
    recentSearches.value = []
  }
}

const clearRecentSearches = () => {
  recentSearches.value = []
  localStorage.removeItem('search_recent_searches')
}

const syncWithRoute = () => {
  const q = route.query.q as string
  const type = route.query.type as string
  
  if (q) {
    query.value = q
    
    // Set search query in search bar
    if (searchBarRef.value) {
      (searchBarRef.value as any).setQuery(q)
    }
  }
  
  if (type && ['all', 'content', 'community', 'users'].includes(type)) {
    searchType.value = type as typeof searchType.value
  }
}

// Watchers
watch(() => route.query, syncWithRoute, { immediate: true })

// Lifecycle
onMounted(() => {
  loadRecentSearches()
  syncWithRoute()
})
</script>

<style scoped>
.gradient-text {
  background: linear-gradient(135deg, #0ea5e9 0%, #a855f7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

kbd {
  font-family: ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace;
}
</style>