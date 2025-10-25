import { apiClient } from './apiClient'

export interface SearchResult {
  id: string | number
  type: 'article' | 'video' | 'faq' | 'user' | 'comment'
  title?: string
  question?: string
  name?: string
  excerpt?: string
  description?: string
  answer?: string
  text?: string
  author?: string
  category?: string
  content_type?: string
  url: string
  published_at?: string
  created_at?: string
  relevance_score?: number
  likes_count?: number
  comments_count?: number
  views_count?: number
  read_time?: number
  order?: number
  is_pinned?: boolean
}

export interface SearchResponse {
  success: boolean
  data: {
    query: string
    type: string
    results: SearchResult[]
    total: number
    suggestions: string[]
    filters: any
  }
}

export interface SearchSuggestions {
  success: boolean
  data: {
    suggestions: string[]
    popular: string[]
    trending: string[]
  }
}

export interface SearchFilters {
  success: boolean
  data: any
}

class SearchService {
  private baseUrl = '/api/v1/search'

  /**
   * Perform search across all content types
   */
  async search(query: string, options: {
    type?: 'all' | 'articles' | 'videos' | 'faqs' | 'users' | 'comments'
    category?: string
    author?: string
    date_from?: string
    date_to?: string
    sort?: 'relevance' | 'date' | 'title' | 'author'
    limit?: number
    page?: number
  } = {}): Promise<SearchResponse> {
    const params = new URLSearchParams({
      q: query,
      ...options
    })

    return apiClient.get(`${this.baseUrl}?${params.toString()}`)
  }

  /**
   * Get search suggestions
   */
  async getSuggestions(query: string): Promise<SearchSuggestions> {
    return apiClient.get(`${this.baseUrl}/suggestions?q=${encodeURIComponent(query)}`)
  }

  /**
   * Get available filters for search type
   */
  async getFilters(type: string = 'all'): Promise<SearchFilters> {
    return apiClient.get(`${this.baseUrl}/filters?type=${type}`)
  }

  /**
   * Get search analytics
   */
  async getAnalytics(): Promise<any> {
    return apiClient.get(`${this.baseUrl}/analytics`)
  }

  /**
   * Save search query for user
   */
  async saveSearch(query: string, filters: any = {}, name?: string): Promise<any> {
    return apiClient.post(`${this.baseUrl}/save`, {
      query,
      filters,
      name
    })
  }

  /**
   * Get user's saved searches
   */
  async getSavedSearches(): Promise<any> {
    return apiClient.get(`${this.baseUrl}/saved`)
  }

  /**
   * Get popular searches
   */
  async getPopularSearches(): Promise<string[]> {
    try {
      const response = await this.getAnalytics()
      return response.data.popular_terms || []
    } catch (error) {
      console.error('Failed to get popular searches:', error)
      return []
    }
  }

  /**
   * Get trending searches
   */
  async getTrendingSearches(): Promise<string[]> {
    try {
      const response = await this.getAnalytics()
      return response.data.trending_terms || []
    } catch (error) {
      console.error('Failed to get trending searches:', error)
      return []
    }
  }

  /**
   * Search with debouncing for real-time suggestions
   */
  debouncedSearch = this.debounce(async (query: string, options: any = {}) => {
    if (query.length < 2) {
      return { data: { results: [], total: 0, suggestions: [] } }
    }
    
    try {
      return await this.search(query, options)
    } catch (error) {
      console.error('Search error:', error)
      return { data: { results: [], total: 0, suggestions: [] } }
    }
  }, 300)

  /**
   * Get suggestions with debouncing
   */
  debouncedSuggestions = this.debounce(async (query: string) => {
    if (query.length < 2) {
      return { data: { suggestions: [], popular: [], trending: [] } }
    }
    
    try {
      return await this.getSuggestions(query)
    } catch (error) {
      console.error('Suggestions error:', error)
      return { data: { suggestions: [], popular: [], trending: [] } }
    }
  }, 200)

  /**
   * Debounce utility function
   */
  private debounce<T extends (...args: any[]) => any>(
    func: T,
    wait: number
  ): (...args: Parameters<T>) => Promise<ReturnType<T>> {
    let timeout: NodeJS.Timeout | null = null

    return (...args: Parameters<T>): Promise<ReturnType<T>> => {
      return new Promise((resolve) => {
        if (timeout) {
          clearTimeout(timeout)
        }
        
        timeout = setTimeout(() => {
          resolve(func(...args))
        }, wait)
      })
    }
  }

  /**
   * Format search result for display
   */
  formatResult(result: SearchResult): {
    title: string
    description: string
    type: string
    url: string
    metadata: any
  } {
    const typeLabels = {
      article: 'Article',
      video: 'Video',
      faq: 'FAQ',
      user: 'User',
      comment: 'Comment'
    }

    return {
      title: result.title || result.question || result.name || 'Untitled',
      description: result.excerpt || result.description || result.answer || result.text || '',
      type: typeLabels[result.type] || 'Content',
      url: result.url,
      metadata: {
        author: result.author,
        category: result.category,
        published_at: result.published_at,
        created_at: result.created_at,
        relevance_score: result.relevance_score,
        likes_count: result.likes_count,
        comments_count: result.comments_count,
        views_count: result.views_count,
        read_time: result.read_time,
        is_pinned: result.is_pinned
      }
    }
  }
}

export const searchService = new SearchService()
