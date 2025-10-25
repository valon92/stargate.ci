<template>
  <div class="relative">
    <!-- Search Input -->
    <div class="relative">
      <input
        ref="searchInput"
        v-model="query"
        @input="handleInput"
        @keydown="handleKeydown"
        @focus="showSuggestions = true"
        @blur="handleBlur"
        type="text"
        :placeholder="placeholder"
        class="w-full px-4 py-3 pl-12 pr-12 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200"
        :class="{ 'rounded-b-none': showSuggestions && (suggestions.length > 0 || popularSearches.length > 0) }"
      >
      
      <!-- Search Icon -->
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>

      <!-- Clear Button -->
      <button
        v-if="query"
        @click="clearSearch"
        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white transition-colors"
      >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <!-- Loading Spinner -->
      <div v-if="isLoading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-primary-500"></div>
      </div>
    </div>

    <!-- Suggestions Dropdown -->
    <div
      v-if="showSuggestions && (suggestions.length > 0 || popularSearches.length > 0)"
      class="absolute top-full left-0 right-0 bg-gray-800 border border-gray-600 border-t-0 rounded-b-lg shadow-lg z-50 max-h-96 overflow-y-auto"
    >
      <!-- Search Suggestions -->
      <div v-if="suggestions.length > 0" class="p-2">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 py-1 mb-2">
          Suggestions
        </div>
        <button
          v-for="(suggestion, index) in suggestions"
          :key="index"
          @click="selectSuggestion(suggestion)"
          class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md transition-colors"
          :class="{ 'bg-gray-700': selectedIndex === index }"
        >
          <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            {{ suggestion }}
          </div>
        </button>
      </div>

      <!-- Popular Searches -->
      <div v-if="popularSearches.length > 0 && suggestions.length === 0" class="p-2">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 py-1 mb-2">
          Popular Searches
        </div>
        <button
          v-for="(search, index) in popularSearches.slice(0, 5)"
          :key="index"
          @click="selectSuggestion(search)"
          class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md transition-colors"
        >
          <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            {{ search }}
          </div>
        </button>
      </div>

      <!-- Trending Searches -->
      <div v-if="trendingSearches.length > 0 && suggestions.length === 0 && popularSearches.length === 0" class="p-2">
        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 py-1 mb-2">
          Trending
        </div>
        <button
          v-for="(search, index) in trendingSearches.slice(0, 5)"
          :key="index"
          @click="selectSuggestion(search)"
          class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-md transition-colors"
        >
          <div class="flex items-center gap-2">
            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            {{ search }}
          </div>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue'
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
    searchInput.value.focus()
  }
})

// Handle input changes
const handleInput = async () => {
  if (query.value.length < 2) {
    suggestions.value = []
    showSuggestions.value = query.value.length > 0
    return
  }

  isLoading.value = true
  try {
    const response = await searchService.debouncedSuggestions(query.value)
    suggestions.value = response.data.suggestions || []
    showSuggestions.value = true
  } catch (error) {
    console.error('Failed to get suggestions:', error)
  } finally {
    isLoading.value = false
  }
}

// Handle keyboard navigation
const handleKeydown = (event: KeyboardEvent) => {
  if (!showSuggestions.value) {
    if (event.key === 'Enter') {
      performSearch()
    }
    return
  }

  const totalItems = suggestions.value.length + popularSearches.value.length + trendingSearches.value.length

  switch (event.key) {
    case 'ArrowDown':
      event.preventDefault()
      selectedIndex.value = Math.min(selectedIndex.value + 1, totalItems - 1)
      break
    case 'ArrowUp':
      event.preventDefault()
      selectedIndex.value = Math.max(selectedIndex.value - 1, -1)
      break
    case 'Enter':
      event.preventDefault()
      if (selectedIndex.value >= 0) {
        selectSuggestionByIndex(selectedIndex.value)
      } else {
        performSearch()
      }
      break
    case 'Escape':
      showSuggestions.value = false
      selectedIndex.value = -1
      break
  }
}

// Handle blur with delay to allow clicks
const handleBlur = () => {
  setTimeout(() => {
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
  
  selectSuggestion(suggestion)
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
  }
}

// Clear search
const clearSearch = () => {
  query.value = ''
  suggestions.value = []
  showSuggestions.value = false
  selectedIndex.value = -1
}

// Watch for external query changes
watch(() => query.value, (newQuery) => {
  if (newQuery === '') {
    suggestions.value = []
    showSuggestions.value = false
  }
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
