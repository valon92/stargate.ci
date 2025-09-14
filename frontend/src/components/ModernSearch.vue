<template>
  <div class="relative">
    <!-- Search Input Container -->
    <div class="relative group">
      <!-- Search Icon -->
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
      
      <!-- Search Input -->
      <input
        ref="searchInput"
        v-model="searchQuery"
        type="text"
        :placeholder="t('search.placeholder')"
        class="block w-full pl-10 pr-12 py-3 border border-gray-200 rounded-xl leading-5 bg-white/80 backdrop-blur-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 sm:text-sm transition-all duration-200 hover:bg-white hover:border-gray-300"
        @input="handleSearchInput"
        @keydown="handleKeydown"
        @focus="handleFocus"
        @blur="handleBlur"
        @click="handleInputClick"
      />
      
      <!-- Clear Button -->
      <div v-if="searchQuery" class="absolute inset-y-0 right-0 pr-3 flex items-center">
        <button
          @click="clearSearch"
          class="text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-full hover:bg-gray-100 transition-colors"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <!-- Loading Indicator -->
      <div v-if="isSearching" class="absolute inset-y-0 right-0 pr-3 flex items-center">
        <div class="animate-spin rounded-full h-4 w-4 border-2 border-primary-500 border-t-transparent"></div>
      </div>
    </div>

    <!-- Search Suggestions Dropdown -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <div
        v-if="showSuggestions && (suggestions.length > 0 || searchHistory.length > 0)"
        class="absolute z-[9999] mt-2 w-full bg-white/95 backdrop-blur-md shadow-2xl max-h-80 rounded-xl border border-gray-200/50 overflow-hidden"
      >
        <!-- Search Suggestions -->
        <div v-if="suggestions.length > 0" class="border-b border-gray-100">
          <div class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide bg-gray-50/50">
            {{ t('search.suggestions') }}
          </div>
          <div
            v-for="(suggestion, index) in suggestions"
            :key="`suggestion-${index}`"
            class="cursor-pointer select-none relative py-3 px-4 hover:bg-primary-50/50 transition-colors group"
            :class="{ 'bg-primary-50/50': selectedSuggestionIndex === index }"
            @click="selectSuggestion(suggestion)"
            @mouseenter="selectedSuggestionIndex = index"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0 mr-3">
                <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                  <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ suggestion.text }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ suggestion.type }}</p>
              </div>
              <div v-if="suggestion.count" class="flex-shrink-0 ml-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                  {{ suggestion.count }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Search History -->
        <div v-if="searchHistory.length > 0 && suggestions.length === 0" class="border-b border-gray-100">
          <div class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide bg-gray-50/50">
            {{ t('search.recent') }}
          </div>
          <div
            v-for="(history, index) in searchHistory.slice(0, 5)"
            :key="`history-${index}`"
            class="cursor-pointer select-none relative py-3 px-4 hover:bg-gray-50/50 transition-colors group"
            :class="{ 'bg-gray-50/50': selectedSuggestionIndex === suggestions.length + index }"
            @click="selectHistory(history)"
            @mouseenter="selectedSuggestionIndex = suggestions.length + index"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0 mr-3">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                  <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ history.query }}</p>
                <p class="text-xs text-gray-500">{{ formatRelativeTime(history.timestamp) }}</p>
              </div>
              <div class="flex-shrink-0 ml-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                  {{ history.resultCount }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="px-4 py-3 bg-gray-50/50 border-t border-gray-100">
          <div class="flex items-center justify-between text-xs text-gray-500">
            <span>{{ t('search.keyboardHint') }}</span>
            <div class="flex space-x-2">
              <kbd class="px-2 py-1 bg-white border border-gray-200 rounded text-xs">↑↓</kbd>
              <kbd class="px-2 py-1 bg-white border border-gray-200 rounded text-xs">↵</kbd>
              <kbd class="px-2 py-1 bg-white border border-gray-200 rounded text-xs">esc</kbd>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Search Results Modal -->
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <div
        v-if="showResults"
        class="fixed inset-0 z-[9999] overflow-y-auto"
        @click="closeResults"
      >
        <div class="flex items-start justify-center min-h-screen pt-16 px-4 pb-20 text-center sm:block sm:p-0">
          <!-- Backdrop -->
          <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
          
          <!-- Modal Content -->
          <div class="inline-block align-top bg-white/95 backdrop-blur-md rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200/50">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary-50 to-secondary-50 px-6 py-4 border-b border-gray-200/50">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                      {{ t('search.results') }}
                    </h3>
                    <p class="text-sm text-gray-600">"{{ searchQuery }}"</p>
                  </div>
                </div>
                <button
                  @click="closeResults"
                  class="text-gray-400 hover:text-gray-600 focus:outline-none p-2 rounded-full hover:bg-white/50 transition-colors"
                >
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6">
              <!-- Loading State -->
              <div v-if="isSearching" class="flex items-center justify-center py-12">
                <div class="flex flex-col items-center space-y-4">
                  <div class="animate-spin rounded-full h-8 w-8 border-2 border-primary-500 border-t-transparent"></div>
                  <span class="text-gray-600">{{ t('search.searching') }}</span>
                </div>
              </div>

              <!-- Search Results -->
              <div v-else-if="searchResults.length > 0" class="space-y-4">
                <div class="flex items-center justify-between mb-6">
                  <div class="text-sm text-gray-600">
                    {{ t('search.found', { count: totalResults }) }}
                  </div>
                  <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ t('search.sortBy') }}:</span>
                    <select
                      v-model="searchSortBy"
                      @change="performSearch"
                      class="text-sm border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500"
                    >
                      <option value="relevance">{{ t('search.sort.relevance') }}</option>
                      <option value="date">{{ t('search.sort.date') }}</option>
                      <option value="title">{{ t('search.sort.title') }}</option>
                    </select>
                  </div>
                </div>
                
                <div class="space-y-3">
                  <div
                    v-for="result in searchResults"
                    :key="result.id"
                    class="group p-4 border border-gray-200 rounded-xl hover:border-primary-300 hover:shadow-md transition-all duration-200 cursor-pointer bg-white/50 hover:bg-white"
                    @click="navigateToResult(result)"
                  >
                    <div class="flex items-start space-x-4">
                      <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-100 to-secondary-100 flex items-center justify-center">
                          <span class="text-xs font-semibold text-primary-700">
                            {{ getTypeIcon(result.type) }}
                          </span>
                        </div>
                      </div>
                      <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2 mb-2">
                          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                            {{ t(`search.types.${result.type}`) }}
                          </span>
                          <h4 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                            {{ result.title }}
                          </h4>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ result.excerpt }}</p>
                        
                        <div class="flex items-center text-xs text-gray-500 space-x-4">
                          <span v-if="result.author" class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            {{ result.author }}
                          </span>
                          <span v-if="result.category" class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8z" clip-rule="evenodd" />
                            </svg>
                            {{ result.category }}
                          </span>
                          <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            {{ formatDate(result.createdAt) }}
                          </span>
                        </div>
                        
                        <div v-if="result.tags.length > 0" class="mt-3">
                          <div class="flex flex-wrap gap-1">
                            <span
                              v-for="tag in result.tags.slice(0, 3)"
                              :key="tag"
                              class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-md"
                            >
                              #{{ tag }}
                            </span>
                          </div>
                        </div>
                      </div>
                      
                      <div class="flex-shrink-0 text-right">
                        <div class="text-xs text-gray-400 mb-1">
                          {{ Math.round(result.relevanceScore) }}% {{ t('search.relevance') }}
                        </div>
                        <div class="w-2 h-2 rounded-full bg-primary-500 opacity-60"></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Load More Button -->
                <div v-if="totalResults > searchResults.length" class="flex justify-center pt-6">
                  <button
                    @click="loadMoreResults"
                    :disabled="isLoadingMore"
                    class="px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-xl hover:from-primary-600 hover:to-secondary-600 disabled:opacity-50 transition-all duration-200 font-medium"
                  >
                    {{ isLoadingMore ? t('search.loading') : t('search.loadMore') }}
                  </button>
                </div>
              </div>

              <!-- No Results -->
              <div v-else-if="!isSearching" class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                  <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ t('search.noResults') }}</h3>
                <p class="text-gray-500">{{ t('search.tryDifferent') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { searchService, type SearchResult, type SearchSuggestion, type SearchHistory, type SearchFilters } from '../services/searchService'

// Composables
const router = useRouter()
const { t } = useI18n()

// Reactive data
const searchInput = ref<HTMLInputElement>()
const searchQuery = ref('')
const showSuggestions = ref(false)
const showResults = ref(false)
const isSearching = ref(false)
const isLoadingMore = ref(false)
const selectedSuggestionIndex = ref(-1)

const suggestions = ref<SearchSuggestion[]>([])
const searchHistory = ref<SearchHistory[]>([])
const searchResults = ref<SearchResult[]>([])
const totalResults = ref(0)
const searchOffset = ref(0)
const searchLimit = ref(20)
const searchSortBy = ref<'relevance' | 'date' | 'title'>('relevance')
const searchFilters = ref<SearchFilters>({})

// Debounce timer
let searchTimeout: number | null = null

// Methods
const handleSearchInput = async () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }

  if (searchQuery.value.length < 2) {
    suggestions.value = []
    showSuggestions.value = false
    return
  }

  searchTimeout = setTimeout(async () => {
    try {
      const result = await searchService.search({
        query: searchQuery.value,
        limit: 5
      })
      suggestions.value = result.suggestions
      showSuggestions.value = true
    } catch (error) {
      console.error('Error getting suggestions:', error)
    }
  }, 300)
}

const handleKeydown = (event: KeyboardEvent) => {
  const totalSuggestions = suggestions.value.length + searchHistory.value.length

  switch (event.key) {
    case 'ArrowDown':
      event.preventDefault()
      selectedSuggestionIndex.value = Math.min(selectedSuggestionIndex.value + 1, totalSuggestions - 1)
      break
    case 'ArrowUp':
      event.preventDefault()
      selectedSuggestionIndex.value = Math.max(selectedSuggestionIndex.value - 1, -1)
      break
    case 'Enter':
      event.preventDefault()
      if (selectedSuggestionIndex.value >= 0 && selectedSuggestionIndex.value < suggestions.value.length) {
        selectSuggestion(suggestions.value[selectedSuggestionIndex.value])
      } else if (selectedSuggestionIndex.value >= suggestions.value.length) {
        const historyIndex = selectedSuggestionIndex.value - suggestions.value.length
        selectHistory(searchHistory.value[historyIndex])
      } else {
        performSearch()
      }
      break
    case 'Escape':
      showSuggestions.value = false
      showResults.value = false
      selectedSuggestionIndex.value = -1
      break
  }
}

const handleFocus = () => {
  if (searchQuery.value.length >= 2) {
    showSuggestions.value = true
  }
}

const handleBlur = () => {
  // Delay hiding suggestions to allow clicks
  setTimeout(() => {
    showSuggestions.value = false
    selectedSuggestionIndex.value = -1
  }, 200)
}

const handleInputClick = () => {
  searchInput.value?.focus()
  if (searchQuery.value.length >= 2) {
    showSuggestions.value = true
  }
}

const selectSuggestion = (suggestion: SearchSuggestion) => {
  searchQuery.value = suggestion.text
  showSuggestions.value = false
  performSearch()
}

const selectHistory = (history: SearchHistory) => {
  searchQuery.value = history.query
  if (history.filters) {
    searchFilters.value = history.filters
  }
  showSuggestions.value = false
  performSearch()
}

const performSearch = async () => {
  if (!searchQuery.value.trim()) return

  isSearching.value = true
  showResults.value = true
  searchOffset.value = 0

  try {
    const result = await searchService.search({
      query: searchQuery.value,
      filters: searchFilters.value,
      sortBy: searchSortBy.value,
      limit: searchLimit.value,
      offset: searchOffset.value
    })

    searchResults.value = result.results
    totalResults.value = result.totalCount
  } catch (error) {
    console.error('Search error:', error)
  } finally {
    isSearching.value = false
  }
}

const loadMoreResults = async () => {
  if (isLoadingMore.value) return

  isLoadingMore.value = true
  searchOffset.value += searchLimit.value

  try {
    const result = await searchService.search({
      query: searchQuery.value,
      filters: searchFilters.value,
      sortBy: searchSortBy.value,
      limit: searchLimit.value,
      offset: searchOffset.value
    })

    searchResults.value = [...searchResults.value, ...result.results]
  } catch (error) {
    console.error('Load more error:', error)
  } finally {
    isLoadingMore.value = false
  }
}

const navigateToResult = (result: SearchResult) => {
  router.push(result.url)
  closeResults()
}

const clearSearch = () => {
  searchQuery.value = ''
  suggestions.value = []
  showSuggestions.value = false
  showResults.value = false
  searchResults.value = []
  selectedSuggestionIndex.value = -1
}

const closeResults = () => {
  showResults.value = false
  searchResults.value = []
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

const formatRelativeTime = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))
  
  if (diffInHours < 1) return t('search.justNow')
  if (diffInHours < 24) return t('search.hoursAgo', { count: diffInHours })
  const diffInDays = Math.floor(diffInHours / 24)
  if (diffInDays < 7) return t('search.daysAgo', { count: diffInDays })
  return formatDate(dateString)
}

const getTypeIcon = (type: string) => {
  const icons: Record<string, string> = {
    post: 'P',
    user: 'U',
    article: 'A',
    faq: 'Q',
    template: 'T',
    insight: 'I'
  }
  return icons[type] || '?'
}

// Load search history on mount
onMounted(async () => {
  try {
    searchHistory.value = await searchService.getSearchHistory()
  } catch (error) {
    console.error('Error loading search history:', error)
  }
})

// Keyboard shortcuts
const handleGlobalKeydown = (event: KeyboardEvent) => {
  // Ctrl/Cmd + K to focus search
  if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
    event.preventDefault()
    searchInput.value?.focus()
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleGlobalKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleGlobalKeydown)
  if (searchTimeout) {
    clearTimeout(searchTimeout)
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
