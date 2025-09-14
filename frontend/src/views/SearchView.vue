<template>
  <div class="min-h-screen bg-gray-50 relative z-20">
    <!-- Header -->
    <div class="bg-white shadow relative z-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ t('search.title') }}</h1>
            <p class="mt-2 text-gray-600">{{ t('search.subtitle') }}</p>
          </div>
          
          <!-- Quick Actions -->
          <div class="flex space-x-3">
            <button
              @click="showSavedSearches = !showSavedSearches"
              class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
            >
              {{ t('search.savedSearches') }}
            </button>
            <button
              @click="clearSearchHistory"
              class="px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors"
            >
              {{ t('search.clearHistory') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-20">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Search Filters Sidebar -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow p-6 sticky top-8 z-30">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t('search.filters') }}</h3>
            
            <!-- Search Type Filter -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('search.filterByType') }}
              </label>
              <div class="space-y-2">
                <label
                  v-for="type in searchTypes"
                  :key="type.value"
                  class="flex items-center"
                >
                  <input
                    v-model="filters.type"
                    :value="type.value"
                    type="checkbox"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                  />
                  <span class="ml-2 text-sm text-gray-700">
                    {{ type.label }} ({{ type.count }})
                  </span>
                </label>
              </div>
            </div>

            <!-- Category Filter -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('search.filterByCategory') }}
              </label>
              <div class="space-y-2 max-h-40 overflow-y-auto">
                <label
                  v-for="category in searchFacets.category"
                  :key="category.value"
                  class="flex items-center"
                >
                  <input
                    v-model="filters.category"
                    :value="category.value"
                    type="checkbox"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                  />
                  <span class="ml-2 text-sm text-gray-700">
                    {{ category.value }} ({{ category.count }})
                  </span>
                </label>
              </div>
            </div>

            <!-- Author Filter -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('search.filterByAuthor') }}
              </label>
              <div class="space-y-2 max-h-40 overflow-y-auto">
                <label
                  v-for="author in searchFacets.author"
                  :key="author.value"
                  class="flex items-center"
                >
                  <input
                    v-model="filters.author"
                    :value="author.value"
                    type="checkbox"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                  />
                  <span class="ml-2 text-sm text-gray-700">
                    {{ author.value }} ({{ author.count }})
                  </span>
                </label>
              </div>
            </div>

            <!-- Tags Filter -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('search.filterByTags') }}
              </label>
              <div class="space-y-2 max-h-40 overflow-y-auto">
                <label
                  v-for="tag in searchFacets.tags"
                  :key="tag.value"
                  class="flex items-center"
                >
                  <input
                    v-model="filters.tags"
                    :value="tag.value"
                    type="checkbox"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                  />
                  <span class="ml-2 text-sm text-gray-700">
                    #{{ tag.value }} ({{ tag.count }})
                  </span>
                </label>
              </div>
            </div>

            <!-- Date Range Filter -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                {{ t('search.filterByDate') }}
              </label>
              <div class="space-y-2">
                <input
                  v-model="filters.dateRange!.from"
                  type="date"
                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                />
                <input
                  v-model="filters.dateRange!.to"
                  type="date"
                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                />
              </div>
            </div>

            <!-- Clear Filters -->
            <button
              @click="clearFiltersHandler"
              class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
            >
              {{ t('search.clearFilters') }}
            </button>
          </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 relative z-20">
          <!-- Search Input -->
          <div class="bg-white rounded-lg shadow p-6 mb-6">
            <SearchInput 
              :placeholder="t('search.placeholder')"
              size="lg"
              variant="default"
              @search="performSearch"
            />
          </div>

          <!-- Search Results -->
          <div v-if="hasSearched" class="bg-white rounded-lg shadow relative z-20">
            <!-- Results Header -->
            <div class="px-6 py-4 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <div>
                  <h2 class="text-lg font-medium text-gray-900">
                    {{ t('search.results') }}
                  </h2>
                  <p class="text-sm text-gray-600">
                    {{ t('search.found', { count: totalResults }) }}
                  </p>
                </div>
                
                <!-- Sort Options -->
                <div class="flex items-center space-x-4">
                  <label class="text-sm text-gray-700">{{ t('search.sortBy') }}:</label>
                  <select
                    v-model="sortBy"
                    class="border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                  >
                    <option value="relevance">{{ t('search.sort.relevance') }}</option>
                    <option value="date">{{ t('search.sort.date') }}</option>
                    <option value="title">{{ t('search.sort.title') }}</option>
                    <option value="author">{{ t('search.sort.author') }}</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Loading State -->
            <div v-if="isSearching" class="flex items-center justify-center py-12">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-500"></div>
              <span class="ml-2 text-gray-600">{{ t('search.searching') }}</span>
            </div>

            <!-- Results List -->
            <div v-else-if="searchResults.length > 0" class="divide-y divide-gray-200">
              <div
                v-for="result in searchResults"
                :key="result.id"
                class="px-6 py-4 hover:bg-gray-50 cursor-pointer"
                @click="navigateToResultHandler(result)"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center mb-2">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 mr-2">
                        {{ t(`search.types.${result.type}`) }}
                      </span>
                      <h3 class="text-lg font-medium text-gray-900">{{ result.title }}</h3>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-3">{{ result.excerpt }}</p>
                    
                    <div class="flex items-center text-xs text-gray-500 space-x-4">
                      <span v-if="result.author">
                        <svg class="inline h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        {{ result.author }}
                      </span>
                      <span v-if="result.category">
                        <svg class="inline h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8z" clip-rule="evenodd" />
                        </svg>
                        {{ result.category }}
                      </span>
                      <span>
                        <svg class="inline h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        {{ formatDate(result.createdAt) }}
                      </span>
                    </div>
                    
                    <div v-if="result.tags.length > 0" class="mt-2">
                      <span
                        v-for="tag in result.tags.slice(0, 5)"
                        :key="tag"
                        class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-1 mb-1"
                      >
                        #{{ tag }}
                      </span>
                    </div>
                  </div>
                  
                  <div class="ml-4 text-right">
                    <div class="text-xs text-gray-400 mb-1">
                      {{ Math.round(result.relevanceScore) }}% {{ t('search.relevance') }}
                    </div>
                    <button
                      @click.stop="saveSearch(result)"
                      class="text-xs text-primary-600 hover:text-primary-800"
                    >
                      {{ t('search.save') }}
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- No Results -->
            <div v-else class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <h3 class="mt-2 text-sm font-medium text-gray-900">{{ t('search.noResults') }}</h3>
              <p class="mt-1 text-sm text-gray-500">{{ t('search.tryDifferent') }}</p>
            </div>

            <!-- Pagination -->
            <div v-if="totalResults > searchResults.length" class="px-6 py-4 border-t border-gray-200">
              <div class="flex items-center justify-between">
                <button
                  @click="loadMoreResultsHandler"
                  :disabled="isLoadingMore"
                  class="px-4 py-2 bg-primary-500 text-white rounded-md hover:bg-primary-600 disabled:opacity-50"
                >
                  {{ isLoadingMore ? t('search.loading') : t('search.loadMore') }}
                </button>
                
                <div class="text-sm text-gray-600">
                  {{ searchResults.length }} / {{ totalResults }} {{ t('search.results') }}
                </div>
              </div>
            </div>
          </div>

          <!-- Search Tips -->
          <div v-else class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ t('search.tips') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-2">
                <h4 class="font-medium text-gray-700">{{ t('search.tips.basic') }}</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                  <li>• {{ t('search.tips.quotes') }}</li>
                  <li>• {{ t('search.tips.operators') }}</li>
                  <li>• {{ t('search.tips.wildcards') }}</li>
                </ul>
              </div>
              <div class="space-y-2">
                <h4 class="font-medium text-gray-700">{{ t('search.tips.advanced') }}</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                  <li>• {{ t('search.tips.filters') }}</li>
                  <li>• {{ t('search.tips.sorting') }}</li>
                  <li>• {{ t('search.tips.saved') }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Saved Searches Modal -->
    <div
      v-if="showSavedSearches"
      class="fixed inset-0 z-[9999] overflow-y-auto"
      @click="showSavedSearches = false"
    >
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900">{{ t('search.savedSearches') }}</h3>
              <button
                @click="showSavedSearches = false"
                class="text-gray-400 hover:text-gray-600"
              >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <div v-if="savedSearches.length > 0" class="space-y-2">
              <div
                v-for="savedSearch in savedSearches"
                :key="savedSearch.id"
                class="flex items-center justify-between p-3 border border-gray-200 rounded-md"
              >
                <div>
                  <h4 class="font-medium text-gray-900">{{ savedSearch.name }}</h4>
                  <p class="text-sm text-gray-600">{{ savedSearch.query }}</p>
                </div>
                <div class="flex space-x-2">
                  <button
                    @click="loadSavedSearch(savedSearch)"
                    class="text-primary-600 hover:text-primary-800 text-sm"
                  >
                    {{ t('search.load') }}
                  </button>
                  <button
                    @click="deleteSavedSearch(savedSearch.id)"
                    class="text-red-600 hover:text-red-800 text-sm"
                  >
                    {{ t('search.delete') }}
                  </button>
                </div>
              </div>
            </div>
            
            <div v-else class="text-center py-4">
              <p class="text-gray-500">{{ t('search.noSavedSearches') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useSearch } from '../composables/useSearch'
import SearchInput from '../components/SearchInput.vue'
import { searchService, type SearchResult, type SearchFilters, type SavedSearch } from '../services/searchService'

// Composables
const router = useRouter()
const route = useRoute()
const { t } = useI18n()
const { 
  searchQuery,
  isSearching,
  searchResults,
  totalResults,
  searchFacets,
  filters,
  sortBy,
  hasSearched,
  searchTypes,
  performSearch: performSearchQuery,
  loadMoreResults,
  clearFilters,
  navigateToResult
} = useSearch()

// Reactive data
const isLoadingMore = ref(false)
const showSavedSearches = ref(false)
const savedSearches = ref<SavedSearch[]>([])

// Methods
const performSearch = async (query?: string) => {
  if (query) {
    searchQuery.value = query
  }
  await performSearchQuery()
}

const loadMoreResultsHandler = async () => {
  if (isLoadingMore.value) return

  isLoadingMore.value = true
  try {
    await loadMoreResults()
  } finally {
    isLoadingMore.value = false
  }
}

const navigateToResultHandler = (result: SearchResult) => {
  navigateToResult(result)
}

const saveSearch = async (result: SearchResult) => {
  try {
    await searchService.saveSearch(
      result.title,
      searchQuery.value,
      filters.value
    )
    await loadSavedSearches()
  } catch (error) {
    console.error('Error saving search:', error)
  }
}

const loadSavedSearches = async () => {
  try {
    savedSearches.value = await searchService.getSavedSearches()
  } catch (error) {
    console.error('Error loading saved searches:', error)
  }
}

const loadSavedSearch = (savedSearch: SavedSearch) => {
  searchQuery.value = savedSearch.query
  if (savedSearch.filters) {
    filters.value = savedSearch.filters
  }
  showSavedSearches.value = false
  performSearch()
}

const deleteSavedSearch = async (id: string) => {
  try {
    await searchService.deleteSavedSearch(id)
    await loadSavedSearches()
  } catch (error) {
    console.error('Error deleting saved search:', error)
  }
}

const clearFiltersHandler = () => {
  clearFilters()
}

const clearSearchHistory = async () => {
  try {
    await searchService.clearSearchHistory()
  } catch (error) {
    console.error('Error clearing search history:', error)
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

// Watch for filter changes
watch(filters, () => {
  if (hasSearched.value) {
    performSearch()
  }
}, { deep: true })

// Initialize
onMounted(async () => {
  // Check for query parameter
  if (route.query.q) {
    await performSearch(route.query.q as string)
  }
  
  await loadSavedSearches()
})
</script>
