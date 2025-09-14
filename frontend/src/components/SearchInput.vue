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
        :placeholder="placeholder"
        :class="inputClasses"
        @input="handleInput"
        @keydown="handleKeydown"
        @focus="handleFocus"
        @blur="handleBlur"
        @click="handleClick"
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
            Suggestions
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
            Recent Searches
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
            <span>Use keyboard to navigate</span>
            <div class="flex space-x-2">
              <kbd class="px-2 py-1 bg-white border border-gray-200 rounded text-xs">↑↓</kbd>
              <kbd class="px-2 py-1 bg-white border border-gray-200 rounded text-xs">↵</kbd>
              <kbd class="px-2 py-1 bg-white border border-gray-200 rounded text-xs">esc</kbd>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { useSearch } from '../composables/useSearch'
import type { SearchSuggestion, SearchHistory } from '../services/searchService'

// Props
interface Props {
  placeholder?: string
  size?: 'sm' | 'md' | 'lg'
  variant?: 'default' | 'minimal' | 'filled'
  showSuggestions?: boolean
  autoFocus?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Search...',
  size: 'md',
  variant: 'default',
  showSuggestions: true,
  autoFocus: false
})

// Emits
const emit = defineEmits<{
  search: [query: string]
  select: [suggestion: SearchSuggestion | SearchHistory]
  clear: []
}>()

// Composables
const { 
  searchQuery, 
  isSearching, 
  suggestions, 
  searchHistory, 
  getSuggestions, 
  clearSearch: clearSearchQuery,
  loadSearchHistory 
} = useSearch()

// Reactive data
const searchInput = ref<HTMLInputElement>()
const showSuggestions = ref(false)
const selectedSuggestionIndex = ref(-1)

// Debounce timer
let searchTimeout: number | null = null

// Computed
const inputClasses = computed(() => {
  const baseClasses = 'block w-full pl-10 pr-12 leading-5 focus:outline-none transition-all duration-200'
  
  const sizeClasses = {
    sm: 'py-2 text-sm',
    md: 'py-3 text-sm',
    lg: 'py-4 text-base'
  }
  
  const variantClasses = {
    default: 'border border-gray-200 rounded-xl bg-white/80 backdrop-blur-sm placeholder-gray-400 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 hover:bg-white hover:border-gray-300',
    minimal: 'border-0 rounded-lg bg-gray-100/50 placeholder-gray-500 focus:ring-2 focus:ring-primary-500/20 focus:bg-white',
    filled: 'border border-gray-300 rounded-lg bg-gray-50 placeholder-gray-500 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white'
  }
  
  return `${baseClasses} ${sizeClasses[props.size]} ${variantClasses[props.variant]}`
})

// Methods
const handleInput = async () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }

  if (searchQuery.value.length < 2) {
    suggestions.value = []
    showSuggestions.value = false
    return
  }

  searchTimeout = setTimeout(async () => {
    if (props.showSuggestions) {
      await getSuggestions(searchQuery.value)
      showSuggestions.value = true
    }
    emit('search', searchQuery.value)
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
        emit('search', searchQuery.value)
      }
      break
    case 'Escape':
      showSuggestions.value = false
      selectedSuggestionIndex.value = -1
      break
  }
}

const handleFocus = () => {
  if (searchQuery.value.length >= 2 && props.showSuggestions) {
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

const handleClick = () => {
  searchInput.value?.focus()
  if (searchQuery.value.length >= 2 && props.showSuggestions) {
    showSuggestions.value = true
  }
}

const selectSuggestion = (suggestion: SearchSuggestion) => {
  searchQuery.value = suggestion.text
  showSuggestions.value = false
  emit('select', suggestion)
  emit('search', suggestion.text)
}

const selectHistory = (history: SearchHistory) => {
  searchQuery.value = history.query
  showSuggestions.value = false
  emit('select', history)
  emit('search', history.query)
}

const clearSearch = () => {
  clearSearchQuery()
  showSuggestions.value = false
  selectedSuggestionIndex.value = -1
  emit('clear')
}

const formatRelativeTime = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))
  
  if (diffInHours < 1) return 'Just now'
  if (diffInHours < 24) return `${diffInHours} hours ago`
  const diffInDays = Math.floor(diffInHours / 24)
  if (diffInDays < 7) return `${diffInDays} days ago`
  return date.toLocaleDateString()
}

// Lifecycle
onMounted(async () => {
  if (props.autoFocus) {
    await nextTick()
    searchInput.value?.focus()
  }
  
  if (props.showSuggestions) {
    await loadSearchHistory()
  }
})

onUnmounted(() => {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
})

// Expose methods
defineExpose({
  focus: () => searchInput.value?.focus(),
  clear: clearSearch
})
</script>
