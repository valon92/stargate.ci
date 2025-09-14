// Unified Search Composable for Stargate.ci
import { ref, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { searchService, type SearchResult, type SearchSuggestion, type SearchHistory, type SearchFilters } from '../services/searchService'

export function useSearch() {
  const router = useRouter()
  
  // Reactive state
  const searchQuery = ref('')
  const isSearching = ref(false)
  const searchResults = ref<SearchResult[]>([])
  const totalResults = ref(0)
  const suggestions = ref<SearchSuggestion[]>([])
  const searchHistory = ref<SearchHistory[]>([])
  const searchFacets = ref<Record<string, Array<{ value: string; count: number }>>>({
    type: [],
    category: [],
    author: [],
    tags: []
  })
  
  const filters = ref<SearchFilters>({
    type: [],
    category: [],
    author: [],
    tags: [],
    dateRange: {
      from: '',
      to: ''
    }
  })
  
  const sortBy = ref<'relevance' | 'date' | 'title' | 'author'>('relevance')
  const searchOffset = ref(0)
  const searchLimit = ref(20)
  
  // Computed
  const hasResults = computed(() => searchResults.value.length > 0)
  const hasSearched = computed(() => searchQuery.value.trim().length > 0)
  const searchTypes = computed(() => [
    { value: 'post', label: 'Post', count: searchFacets.value.type.find(t => t.value === 'post')?.count || 0 },
    { value: 'user', label: 'User', count: searchFacets.value.type.find(t => t.value === 'user')?.count || 0 },
    { value: 'article', label: 'Article', count: searchFacets.value.type.find(t => t.value === 'article')?.count || 0 },
    { value: 'faq', label: 'FAQ', count: searchFacets.value.type.find(t => t.value === 'faq')?.count || 0 }
  ])
  
  // Methods
  const performSearch = async (query?: string) => {
    const searchTerm = query || searchQuery.value
    if (!searchTerm.trim()) return
    
    isSearching.value = true
    searchOffset.value = 0
    
    try {
      const result = await searchService.search({
        query: searchTerm,
        filters: filters.value,
        sortBy: sortBy.value,
        limit: searchLimit.value,
        offset: searchOffset.value
      })
      
      searchResults.value = result.results
      totalResults.value = result.totalCount
      searchFacets.value = result.facets
      suggestions.value = result.suggestions
    } catch (error) {
      console.error('Search error:', error)
      searchResults.value = []
      totalResults.value = 0
    } finally {
      isSearching.value = false
    }
  }
  
  const loadMoreResults = async () => {
    if (isSearching.value) return
    
    isSearching.value = true
    searchOffset.value += searchLimit.value
    
    try {
      const result = await searchService.search({
        query: searchQuery.value,
        filters: filters.value,
        sortBy: sortBy.value,
        limit: searchLimit.value,
        offset: searchOffset.value
      })
      
      searchResults.value = [...searchResults.value, ...result.results]
    } catch (error) {
      console.error('Load more error:', error)
    } finally {
      isSearching.value = false
    }
  }
  
  const getSuggestions = async (query: string) => {
    if (query.length < 2) {
      suggestions.value = []
      return
    }
    
    try {
      const result = await searchService.search({
        query,
        limit: 5
      })
      suggestions.value = result.suggestions
    } catch (error) {
      console.error('Error getting suggestions:', error)
    }
  }
  
  const clearSearch = () => {
    searchQuery.value = ''
    searchResults.value = []
    totalResults.value = 0
    suggestions.value = []
    searchOffset.value = 0
  }
  
  const clearFilters = () => {
    filters.value = {
      type: [],
      category: [],
      author: [],
      tags: [],
      dateRange: {
        from: '',
        to: ''
      }
    }
    if (hasSearched.value) {
      performSearch()
    }
  }
  
  const navigateToResult = (result: SearchResult) => {
    router.push(result.url)
  }
  
  const loadSearchHistory = async () => {
    try {
      searchHistory.value = await searchService.getSearchHistory()
    } catch (error) {
      console.error('Error loading search history:', error)
    }
  }
  
  // Watch for filter changes
  watch(filters, () => {
    if (hasSearched.value) {
      performSearch()
    }
  }, { deep: true })
  
  // Watch for sort changes
  watch(sortBy, () => {
    if (hasSearched.value) {
      performSearch()
    }
  })
  
  return {
    // State
    searchQuery,
    isSearching,
    searchResults,
    totalResults,
    suggestions,
    searchHistory,
    searchFacets,
    filters,
    sortBy,
    searchOffset,
    searchLimit,
    
    // Computed
    hasResults,
    hasSearched,
    searchTypes,
    
    // Methods
    performSearch,
    loadMoreResults,
    getSuggestions,
    clearSearch,
    clearFilters,
    navigateToResult,
    loadSearchHistory
  }
}
