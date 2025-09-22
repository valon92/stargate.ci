<template>
  <div class="relative w-full max-w-none sm:max-w-lg">
    <!-- Search Input -->
    <div class="relative">
      <input
        ref="searchInput"
        v-model="query"
        type="text"
        :placeholder="placeholder"
        :disabled="loading"
        @input="handleInput"
        @keydown.enter="handleEnter"
        @keydown.down="navigateDown"
        @keydown.up="navigateUp"
        @keydown.escape="clearSuggestions"
        @focus="showSuggestions = true"
        @blur="handleBlur"
        :class="[
          'w-full pl-9 sm:pl-10 pr-10 sm:pr-12 py-2.5 sm:py-3 bg-gray-800 border border-gray-600 rounded-lg',
          'text-sm sm:text-base text-white placeholder-gray-400',
          'focus:ring-2 focus:ring-primary-500 focus:border-transparent',
          'transition-colors duration-200',
          loading ? 'cursor-not-allowed opacity-50' : ''
        ]"
      />
      
      <!-- Search Icon -->
      <div class="absolute inset-y-0 left-0 flex items-center pl-2.5 sm:pl-3">
        <svg
          v-if="!loading"
          class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          />
        </svg>
        
        <!-- Loading Spinner -->
        <div v-else class="animate-spin rounded-full h-4 w-4 sm:h-5 sm:w-5 border-b-2 border-primary-500"></div>
      </div>

      <!-- Clear Button -->
      <button
        v-if="query && !loading"
        @click="clearSearch"
        class="absolute inset-y-0 right-0 flex items-center pr-2.5 sm:pr-3 text-gray-400 hover:text-white transition-colors duration-200"
      >
        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Search Suggestions -->
    <div
      v-if="showSuggestions && (suggestions.length > 0 || recentSearches.length > 0)"
      class="absolute z-50 w-full mt-1 bg-gray-800 border border-gray-600 rounded-lg shadow-lg max-h-80 sm:max-h-96 overflow-y-auto"
    >
      <!-- Recent Searches -->
      <div v-if="recentSearches.length > 0 && !query" class="p-1.5 sm:p-2">
        <div class="px-2 sm:px-3 py-1.5 sm:py-2 text-xs font-medium text-gray-400 uppercase tracking-wide">
          Recent Searches
        </div>
        <button
          v-for="(search, index) in recentSearches.slice(0, 5)"
          :key="`recent-${index}`"
          @click="selectSuggestion(search)"
          class="w-full flex items-center px-2 sm:px-3 py-1.5 sm:py-2 text-left text-xs sm:text-sm text-gray-300 hover:bg-gray-700 rounded transition-colors duration-150"
        >
          <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-2 sm:mr-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="truncate">{{ search }}</span>
        </button>
      </div>

      <!-- Search Suggestions -->
      <div v-if="suggestions.length > 0" class="p-1.5 sm:p-2">
        <div v-if="recentSearches.length > 0 && !query" class="border-t border-gray-700 pt-1.5 sm:pt-2">
          <div class="px-2 sm:px-3 py-1.5 sm:py-2 text-xs font-medium text-gray-400 uppercase tracking-wide">
            Suggestions
          </div>
        </div>
        
        <button
          v-for="(suggestion, index) in suggestions"
          :key="`suggestion-${index}`"
                 :ref="(el: Element | ComponentPublicInstance | null) => suggestionRefs[index] = el as HTMLElement"
          @click="selectSuggestion(suggestion)"
          :class="[
            'w-full flex items-center px-2 sm:px-3 py-1.5 sm:py-2 text-left text-xs sm:text-sm rounded transition-colors duration-150',
            selectedIndex === index
              ? 'bg-primary-500 text-white'
              : 'text-gray-300 hover:bg-gray-700'
          ]"
        >
          <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-2 sm:mr-3 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <span class="flex-1 truncate">{{ suggestion }}</span>
        </button>
      </div>

      <!-- No Results -->
      <div v-if="query && suggestions.length === 0 && !loading" class="p-3 sm:p-4 text-center text-gray-500">
        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33" />
        </svg>
        <p class="text-xs sm:text-sm">No suggestions found</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick, onMounted, onUnmounted, type ComponentPublicInstance } from 'vue'
import { backendApi } from '../../services/backendApi'
import { useRouter } from 'vue-router'

interface Props {
  placeholder?: string
  searchType?: 'all' | 'content' | 'community' | 'users'
  autoFocus?: boolean
  showRecentSearches?: boolean
  debounceDelay?: number
}

interface Emits {
  (e: 'search', query: string, type: string): void
  (e: 'suggestion-selected', suggestion: string): void
  (e: 'clear'): void
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Search...',
  searchType: 'all',
  autoFocus: false,
  showRecentSearches: true,
  debounceDelay: 300
})

const emit = defineEmits<Emits>()
const router = useRouter()

// Reactive state
const searchInput = ref<HTMLInputElement | null>(null)
const suggestionRefs = ref<HTMLElement[]>([])
const query = ref('')
const suggestions = ref<string[]>([])
const recentSearches = ref<string[]>([])
const showSuggestions = ref(false)
const loading = ref(false)
const selectedIndex = ref(-1)
const debounceTimer = ref<number | null>(null)

// Computed
const hasQuery = computed(() => query.value.trim().length > 0)

// Methods
const handleInput = () => {
  selectedIndex.value = -1
  
  if (debounceTimer.value) {
    clearTimeout(debounceTimer.value)
  }

  if (query.value.length < 2) {
    suggestions.value = []
    return
  }

  debounceTimer.value = window.setTimeout(async () => {
    await fetchSuggestions()
  }, props.debounceDelay)
}

const fetchSuggestions = async () => {
  if (query.value.length < 2) return

  try {
    loading.value = true
    const response = await backendApi.search(query.value, {
      type: props.searchType,
      per_page: '10'
    })

    if (response.status === 'success') {
      suggestions.value = response.data.suggestions || []
    }
  } catch (error) {
    console.error('Error fetching suggestions:', error)
    suggestions.value = []
  } finally {
    loading.value = false
  }
}

const handleEnter = () => {
  if (selectedIndex.value >= 0 && suggestions.value[selectedIndex.value]) {
    selectSuggestion(suggestions.value[selectedIndex.value])
  } else if (hasQuery.value) {
    performSearch(query.value)
  }
}

const handleBlur = () => {
  // Delay hiding suggestions to allow for click events
  setTimeout(() => {
    showSuggestions.value = false
    selectedIndex.value = -1
  }, 200)
}

const navigateDown = () => {
  if (suggestions.value.length > 0) {
    selectedIndex.value = Math.min(selectedIndex.value + 1, suggestions.value.length - 1)
    scrollToSelected()
  }
}

const navigateUp = () => {
  if (suggestions.value.length > 0) {
    selectedIndex.value = Math.max(selectedIndex.value - 1, -1)
    scrollToSelected()
  }
}

const scrollToSelected = () => {
  nextTick(() => {
    if (selectedIndex.value >= 0 && suggestionRefs.value[selectedIndex.value]) {
      suggestionRefs.value[selectedIndex.value].scrollIntoView({
        block: 'nearest',
        behavior: 'smooth'
      })
    }
  })
}

const selectSuggestion = (suggestion: string) => {
  query.value = suggestion
  showSuggestions.value = false
  selectedIndex.value = -1
  
  performSearch(suggestion)
  emit('suggestion-selected', suggestion)
}

const performSearch = (searchQuery: string) => {
  if (!searchQuery.trim()) return

  // Add to recent searches
  addToRecentSearches(searchQuery)
  
  // Emit search event
  emit('search', searchQuery, props.searchType)
  
  // Navigate to search results page
  router.push({
    path: '/search',
    query: {
      q: searchQuery,
      type: props.searchType
    }
  })
}

const clearSearch = () => {
  query.value = ''
  suggestions.value = []
  selectedIndex.value = -1
  emit('clear')
  searchInput.value?.focus()
}

const clearSuggestions = () => {
  showSuggestions.value = false
  selectedIndex.value = -1
}

const addToRecentSearches = (searchQuery: string) => {
  if (!props.showRecentSearches) return

  const recent = [...recentSearches.value.filter(s => s !== searchQuery)]
  recent.unshift(searchQuery)
  recentSearches.value = recent.slice(0, 10) // Keep only last 10 searches

  // Save to localStorage
  localStorage.setItem('search_recent_searches', JSON.stringify(recentSearches.value))
}

const loadRecentSearches = () => {
  if (!props.showRecentSearches) return

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

const handleKeyDown = (event: KeyboardEvent) => {
  // Handle global hotkeys
  if (event.ctrlKey || event.metaKey) {
    if (event.key === 'k') {
      event.preventDefault()
      searchInput.value?.focus()
    }
  }
}

// Lifecycle
onMounted(() => {
  loadRecentSearches()
  
  if (props.autoFocus && searchInput.value) {
    searchInput.value.focus()
  }

  // Add global keyboard listener
  document.addEventListener('keydown', handleKeyDown)
})

onUnmounted(() => {
  if (debounceTimer.value) {
    clearTimeout(debounceTimer.value)
  }
  
  document.removeEventListener('keydown', handleKeyDown)
})

// Expose methods for parent components
defineExpose({
  focus: () => searchInput.value?.focus(),
  clear: clearSearch,
  setQuery: (newQuery: string) => {
    query.value = newQuery
  }
})
</script>

<style scoped>
/* Add custom scrollbar styles for suggestions */
.max-h-96::-webkit-scrollbar {
  width: 6px;
}

.max-h-96::-webkit-scrollbar-track {
  background: #374151;
  border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb {
  background: #6B7280;
  border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
  background: #9CA3AF;
}
</style>
