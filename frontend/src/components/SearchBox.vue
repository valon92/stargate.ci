<template>
  <div class="relative w-full">
    <!-- Search Input Container -->
    <div class="relative group">
      <input
        ref="searchInput"
        v-model="query"
        @input="handleInput"
        @keydown="handleKeydown"
        @focus="handleFocus"
        @blur="handleBlur"
        type="text"
        :placeholder="placeholder"
        class="w-full px-4 py-2.5 pl-11 pr-11 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all duration-200 text-sm"
        :class="{ 
          'rounded-b-none border-b-0 shadow-lg': showSuggestions && (suggestions.length > 0 || popularSearches.length > 0 || trendingSearches.length > 0),
          'bg-white dark:bg-gray-800': showSuggestions && (suggestions.length > 0 || popularSearches.length > 0 || trendingSearches.length > 0)
        }"
      >
      
      <!-- Search Icon -->
      <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
        <svg 
          class="h-4.5 w-4.5 text-gray-400 dark:text-gray-500 transition-colors duration-200 group-focus-within:text-black dark:group-focus-within:text-white" 
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>

      <!-- Right Side Icons Container -->
      <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center gap-2">
        <!-- Loading Spinner -->
        <div v-if="isLoading" class="flex items-center">
          <div class="animate-spin rounded-full h-4 w-4 border-2 border-gray-300 dark:border-gray-600 border-t-black dark:border-t-white"></div>
        </div>

        <!-- Clear Button -->
        <button
          v-if="query && !isLoading"
          @click.stop="clearSearch"
          class="p-1 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
          aria-label="Clear search"
        >
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>

        <!-- Keyboard Shortcut Hint (Desktop) -->
        <div v-if="!query && !isLoading" class="hidden lg:flex items-center gap-1 px-2 py-0.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded text-xs text-gray-500 dark:text-gray-400">
          <kbd class="font-mono">⌘</kbd>
          <span>K</span>
        </div>
      </div>
    </div>

    <!-- Suggestions Dropdown -->
    <Transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div
        v-if="showSuggestions && (suggestions.length > 0 || popularSearches.length > 0 || trendingSearches.length > 0)"
        class="absolute top-full left-0 right-0 mt-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 border-t-0 rounded-b-xl shadow-xl z-50 max-h-96 overflow-y-auto"
        @mousedown.prevent
      >
        <!-- Search Suggestions -->
        <div v-if="suggestions.length > 0" class="py-2">
          <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Suggestions
          </div>
          <button
            v-for="(suggestion, index) in suggestions"
            :key="index"
            :data-selected-index="index"
            @click="selectSuggestion(suggestion)"
            @mouseenter="selectedIndex = index"
            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 flex items-center gap-3 group"
            :class="{ 
              'bg-gray-50 dark:bg-gray-700/50': selectedIndex === index 
            }"
          >
            <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition-colors">
              <svg class="h-3.5 w-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </div>
            <span class="flex-1 truncate">{{ suggestion }}</span>
            <kbd v-if="selectedIndex === index" class="hidden sm:inline-flex items-center px-1.5 py-0.5 text-xs font-mono text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded">↵</kbd>
          </button>
        </div>

        <!-- Popular Searches -->
        <div v-if="popularSearches.length > 0 && suggestions.length === 0" class="py-2">
          <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Popular Searches
          </div>
          <button
            v-for="(search, index) in popularSearches.slice(0, 6)"
            :key="index"
            :data-selected-index="suggestions.length + index"
            @click="selectSuggestion(search)"
            @mouseenter="selectedIndex = suggestions.length + index"
            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 flex items-center gap-3 group"
            :class="{ 
              'bg-gray-50 dark:bg-gray-700/50': selectedIndex === suggestions.length + index 
            }"
          >
            <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded bg-blue-50 dark:bg-blue-900/30 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 transition-colors">
              <svg class="h-3.5 w-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <span class="flex-1 truncate">{{ search }}</span>
          </button>
        </div>

        <!-- Trending Searches -->
        <div v-if="trendingSearches.length > 0 && suggestions.length === 0 && popularSearches.length === 0" class="py-2">
          <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Trending
          </div>
          <button
            v-for="(search, index) in trendingSearches.slice(0, 6)"
            :key="index"
            :data-selected-index="suggestions.length + popularSearches.length + index"
            @click="selectSuggestion(search)"
            @mouseenter="selectedIndex = suggestions.length + popularSearches.length + index"
            class="w-full text-left px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 flex items-center gap-3 group"
            :class="{ 
              'bg-gray-50 dark:bg-gray-700/50': selectedIndex === suggestions.length + popularSearches.length + index 
            }"
          >
            <div class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded bg-orange-50 dark:bg-orange-900/30 group-hover:bg-orange-100 dark:group-hover:bg-orange-900/50 transition-colors">
              <svg class="h-3.5 w-3.5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <span class="flex-1 truncate">{{ search }}</span>
          </button>
        </div>

        <!-- Empty State -->
        <div v-if="query.length >= 2 && suggestions.length === 0 && !isLoading" class="px-4 py-8 text-center">
          <div class="text-gray-400 dark:text-gray-500 mb-2">
            <svg class="mx-auto h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          <p class="text-sm text-gray-500 dark:text-gray-400">No suggestions found</p>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'
import { Transition } from 'vue'
import { searchService } from '@/services/searchService'

interface Props {
  placeholder?: string
  autoFocus?: boolean
  onSearch?: (query: string) => void
  onSuggestionSelect?: (suggestion: string) => void
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Search articles, videos, FAQs...',
  autoFocus: false
})

const emit = defineEmits<{
  search: [query: string]
  suggestionSelect: [suggestion: string]
}>()

// Reactive data
const query = ref('')
const suggestions = ref<string[]>([])
const popularSearches = ref<string[]>([])
const trendingSearches = ref<string[]>([])
const showSuggestions = ref(false)
const isLoading = ref(false)
const selectedIndex = ref(-1)
const searchInput = ref<HTMLInputElement>()
const blurTimeout = ref<ReturnType<typeof setTimeout> | null>(null)

// Load popular and trending searches on mount
onMounted(async () => {
  try {
    const [popular, trending] = await Promise.all([
      searchService.getPopularSearches(),
      searchService.getTrendingSearches()
    ])
    popularSearches.value = popular
    trendingSearches.value = trending
  } catch (error) {
    console.error('Failed to load search data:', error)
  }

  if (props.autoFocus && searchInput.value) {
    setTimeout(() => {
      searchInput.value?.focus()
    }, 100)
  }

  // Keyboard shortcut (Cmd/Ctrl + K)
  const handleKeyboardShortcut = (event: KeyboardEvent) => {
    if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
      event.preventDefault()
      searchInput.value?.focus()
    }
  }

  document.addEventListener('keydown', handleKeyboardShortcut)

  onUnmounted(() => {
    document.removeEventListener('keydown', handleKeyboardShortcut)
  })
})

// Handle focus
const handleFocus = () => {
  if (blurTimeout.value) {
    clearTimeout(blurTimeout.value)
    blurTimeout.value = null
  }
  showSuggestions.value = true
}

// Handle input changes
const handleInput = async () => {
  selectedIndex.value = -1
  
  if (query.value.length < 2) {
    suggestions.value = []
    showSuggestions.value = query.value.length > 0 || popularSearches.value.length > 0 || trendingSearches.value.length > 0
    return
  }

  isLoading.value = true
  try {
    const response = await searchService.debouncedSuggestions(query.value)
    suggestions.value = response.data.suggestions || []
    showSuggestions.value = true
  } catch (error) {
    console.error('Failed to get suggestions:', error)
    suggestions.value = []
  } finally {
    isLoading.value = false
  }
}

// Handle keyboard navigation
const handleKeydown = (event: KeyboardEvent) => {
  if (event.key === 'Escape') {
    showSuggestions.value = false
    selectedIndex.value = -1
    searchInput.value?.blur()
    return
  }

  if (!showSuggestions.value) {
    if (event.key === 'Enter') {
      performSearch()
    }
    return
  }

  const totalItems = suggestions.value.length + 
    (suggestions.value.length === 0 ? popularSearches.value.length : 0) + 
    (suggestions.value.length === 0 && popularSearches.value.length === 0 ? trendingSearches.value.length : 0)

  switch (event.key) {
    case 'ArrowDown':
      event.preventDefault()
      selectedIndex.value = Math.min(selectedIndex.value + 1, totalItems - 1)
      scrollToSelected()
      break
    case 'ArrowUp':
      event.preventDefault()
      selectedIndex.value = Math.max(selectedIndex.value - 1, -1)
      scrollToSelected()
      break
    case 'Enter':
      event.preventDefault()
      if (selectedIndex.value >= 0) {
        selectSuggestionByIndex(selectedIndex.value)
      } else {
        performSearch()
      }
      break
  }
}

// Scroll to selected item
const scrollToSelected = () => {
  nextTick(() => {
    const selectedElement = document.querySelector(`[data-selected-index="${selectedIndex.value}"]`)
    if (selectedElement) {
      selectedElement.scrollIntoView({ block: 'nearest', behavior: 'smooth' })
    }
  })
}

// Handle blur with delay to allow clicks
const handleBlur = () => {
  blurTimeout.value = setTimeout(() => {
    showSuggestions.value = false
    selectedIndex.value = -1
  }, 200)
}

// Select suggestion by index
const selectSuggestionByIndex = (index: number) => {
  let suggestion = ''
  
  if (index < suggestions.value.length) {
    suggestion = suggestions.value[index]
  } else if (index < suggestions.value.length + popularSearches.value.length) {
    suggestion = popularSearches.value[index - suggestions.value.length]
  } else {
    suggestion = trendingSearches.value[index - suggestions.value.length - popularSearches.value.length]
  }
  
  if (suggestion) {
    selectSuggestion(suggestion)
  }
}

// Select a suggestion
const selectSuggestion = (suggestion: string) => {
  query.value = suggestion
  showSuggestions.value = false
  selectedIndex.value = -1
  
  emit('suggestionSelect', suggestion)
  if (props.onSuggestionSelect) {
    props.onSuggestionSelect(suggestion)
  }
  
  performSearch()
}

// Perform search
const performSearch = () => {
  if (query.value.trim()) {
    emit('search', query.value.trim())
    if (props.onSearch) {
      props.onSearch(query.value.trim())
    }
    showSuggestions.value = false
    searchInput.value?.blur()
  }
}

// Clear search
const clearSearch = () => {
  query.value = ''
  suggestions.value = []
  showSuggestions.value = false
  selectedIndex.value = -1
  searchInput.value?.focus()
}

// Watch for external query changes
watch(() => query.value, (newQuery) => {
  if (newQuery === '') {
    suggestions.value = []
  }
})

// Cleanup on unmount
onUnmounted(() => {
  if (blurTimeout.value) {
    clearTimeout(blurTimeout.value)
  }
})

// Expose methods for parent components
defineExpose({
  focus: () => {
    searchInput.value?.focus()
  },
  clear: clearSearch,
  setQuery: (newQuery: string) => {
    query.value = newQuery
  }
})
</script>

<style scoped>
/* Custom scrollbar for suggestions dropdown */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.2);
  border-radius: 3px;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.2);
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: rgba(0, 0, 0, 0.3);
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.3);
}
</style>