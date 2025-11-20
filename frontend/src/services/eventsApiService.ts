// Events API Service - Real API integration

export interface Event {
  id: string
  title: string
  description: string
  category: 'stargate' | 'cristal' | 'conferences' | 'meetings' | 'announcements'
  type: 'conference' | 'meeting' | 'announcement' | 'workshop' | 'video'
  event_date: string
  event_time?: string
  location: string
  organizer: string
  icon: string
  registration_url?: string
  video_url?: string
  source: 'internal' | 'openai' | 'gemini' | 'cohere' | 'softbank' | 'oracle' | 'mgx'
  metadata?: any
  is_featured: boolean
  is_active: boolean
  last_synced_at?: string
  created_at: string
  updated_at: string
  formatted_date?: string
  formatted_time?: string
  is_upcoming?: boolean
}

export interface EventsResponse {
  success: boolean
  data: Event[]
  meta: {
    total: number
    sources?: string[]
    last_sync?: any
    filters_applied?: any
    query?: string
    category?: string
    limit?: number
  }
  error?: string
}

export interface EventCategoriesResponse {
  success: boolean
  data: Array<{
    id: string
    name: string
    icon: string
    description: string
    count: number
  }>
  meta: {
    total_categories: number
    total_events: number
  }
}

export interface SyncResponse {
  success: boolean
  message: string
  data: {
    openai: any
    gemini: any
    cohere: any
    softbank: any
    oracle: any
    mgx: any
    total_synced: number
    errors: any[]
  }
  meta: {
    total_synced: number
    sync_time: string
    force_refresh: boolean
  }
}

class EventsApiService {
  private baseUrl = (typeof window !== 'undefined' && window.location.hostname === 'localhost')
    ? 'http://localhost:8000/api/v1'
    : '/api/v1'
  private cache: Map<string, any> = new Map()
  private cacheExpiry: Map<string, number> = new Map()
  private readonly CACHE_DURATION = 5 * 60 * 1000 // 5 minutes

  // Check if cache is valid
  private isCacheValid(key: string): boolean {
    const expiry = this.cacheExpiry.get(key)
    return expiry ? Date.now() < expiry : false
  }

  // Set cache
  private setCache(key: string, data: any): void {
    this.cache.set(key, data)
    this.cacheExpiry.set(key, Date.now() + this.CACHE_DURATION)
  }

  // Get cached data
  private getCache(key: string): any {
    if (this.isCacheValid(key)) {
      return this.cache.get(key)
    }
    return null
  }

  // Make API request
  private async makeRequest(endpoint: string, options: RequestInit = {}): Promise<any> {
    try {
      const response = await fetch(`${this.baseUrl}${endpoint}`, {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...options.headers
        },
        ...options
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      return await response.json()
    } catch (error) {
      console.error('API Request Error:', error)
      throw error
    }
  }

  // Get all events
  async getAllEvents(limit: number = 20, filters: any = {}): Promise<EventsResponse> {
    // Temporarily disable cache to ensure fresh data
    // const cacheKey = `events_all_${limit}_${JSON.stringify(filters)}`
    // const cached = this.getCache(cacheKey)
    
    // if (cached) {
    //   console.log('📅 Using cached events data')
    //   return cached
    // }

    try {
      const params = new URLSearchParams({
        limit: limit.toString(),
        ...filters
      })
      
      // Remove undefined values from params
      for (const [key, value] of params.entries()) {
        if (value === 'undefined' || value === 'null') {
          params.delete(key)
        }
      }

      console.log('🔍 API URL:', `${this.baseUrl}/events?${params}`)
      console.log('🔍 Filters:', filters)

      const response = await this.makeRequest(`/events?${params}`)
      // Temporarily disable cache
      // this.setCache(cacheKey, response)
      
      console.log('📅 Loaded real events from API:', response.data?.length || 0)
      console.log('📅 API Response:', response)
      return response

    } catch (error) {
      console.error('📅 Error loading events from API:', error)
      // Fallback to static data
      return this.generateEvents('all', limit)
    }
  }

  // Get upcoming events
  async getUpcomingEvents(limit: number = 10): Promise<EventsResponse> {
    const cacheKey = `events_upcoming_${limit}`
    const cached = this.getCache(cacheKey)
    
    if (cached) {
      console.log('📅 Using cached upcoming events')
      return cached
    }

    try {
      const params = new URLSearchParams({
        limit: limit.toString()
      })

      const response = await this.makeRequest(`/events/upcoming?${params}`)
      this.setCache(cacheKey, response)
      
      console.log('📅 Loaded upcoming events from API:', response.data?.length || 0)
      return response

    } catch (error) {
      console.error('📅 Error loading upcoming events:', error)
      // Fallback to static data
      const allEvents = await this.getAllEvents(limit, { upcoming: true })
      return {
        ...allEvents,
        data: allEvents.data?.filter(event => {
          const eventDate = new Date(event.event_date)
          return eventDate >= new Date()
        }) || []
      }
    }
  }

  // Get events by category
  async getEventsByCategory(category: string, limit: number = 10): Promise<EventsResponse> {
    const cacheKey = `events_category_${category}_${limit}`
    const cached = this.getCache(cacheKey)
    
    if (cached) {
      console.log('📅 Using cached category events')
      return cached
    }

    try {
      const params = new URLSearchParams({
        limit: limit.toString()
      })

      const response = await this.makeRequest(`/events/category/${category}?${params}`)
      this.setCache(cacheKey, response)
      
      console.log('📅 Loaded category events from API:', response.data?.length || 0)
      return response

    } catch (error) {
      console.error('📅 Error loading category events:', error)
      // Fallback to static data
      return this.generateEvents(category, limit)
    }
  }

  // Search events
  async searchEvents(query: string, limit: number = 10): Promise<EventsResponse> {
    const cacheKey = `events_search_${query}_${limit}`
    const cached = this.getCache(cacheKey)
    
    if (cached) {
      console.log('📅 Using cached search results')
      return cached
    }

    try {
      const params = new URLSearchParams({
        q: query,
        limit: limit.toString()
      })

      const response = await this.makeRequest(`/events/search?${params}`)
      this.setCache(cacheKey, response)
      
      console.log('📅 Search results from API:', response.data?.length || 0)
      return response

    } catch (error) {
      console.error('📅 Error searching events:', error)
      // Fallback to static search
      const allEvents = await this.getAllEvents(50)
      const searchResults = allEvents.data?.filter(event => 
        event.title.toLowerCase().includes(query.toLowerCase()) ||
        event.description.toLowerCase().includes(query.toLowerCase()) ||
        event.organizer.toLowerCase().includes(query.toLowerCase())
      ) || []

      return {
        success: true,
        data: searchResults.slice(0, limit),
        meta: {
          total: searchResults.length,
          query: query
        }
      }
    }
  }

  // Get event categories
  async getEventCategories(): Promise<EventCategoriesResponse> {
    const cacheKey = 'events_categories'
    const cached = this.getCache(cacheKey)
    
    if (cached) {
      console.log('📅 Using cached categories')
      return cached
    }

    try {
      const response = await this.makeRequest('/events/categories')
      this.setCache(cacheKey, response)
      
      console.log('📅 Loaded categories from API:', response.data?.length || 0)
      return response

    } catch (error) {
      console.error('📅 Error loading categories:', error)
      // Fallback to static categories
      return {
        success: true,
        data: [
          {
            id: 'all',
            name: 'All Events',
            icon: '📅',
            description: 'All upcoming events and announcements',
            count: 0
          },
          {
            id: 'stargate',
            name: 'Stargate Project',
            icon: '🚀',
            description: 'Events related to the $500 billion AI infrastructure initiative',
            count: 0
          },
          {
            id: 'cristal',
            name: 'Cristal Intelligence',
            icon: '💎',
            description: 'Events about transparent AI and ethical AI development',
            count: 0
          },
          {
            id: 'conferences',
            name: 'Conferences',
            icon: '🎤',
            description: 'Major conferences and speaking engagements',
            count: 0
          },
          {
            id: 'meetings',
            name: 'Meetings',
            icon: '🤝',
            description: 'Partnership meetings and strategic discussions',
            count: 0
          },
          {
            id: 'announcements',
            name: 'Announcements',
            icon: '📢',
            description: 'Important announcements and milestone releases',
            count: 0
          }
        ],
        meta: {
          total_categories: 6,
          total_events: 0
        }
      }
    }
  }

  // Sync events from external APIs
  async syncEvents(force: boolean = false): Promise<SyncResponse> {
    try {
      const response = await this.makeRequest('/events/sync', {
        method: 'POST',
        body: JSON.stringify({ force })
      })
      
      console.log('📅 Events sync completed:', response.data?.total_synced || 0)
      
      // Clear cache after sync
      this.cache.clear()
      this.cacheExpiry.clear()
      
      return response

    } catch (error) {
      console.error('📅 Error syncing events:', error)
      throw error
    }
  }

  // Get sync status
  async getSyncStatus(): Promise<any> {
    try {
      const response = await this.makeRequest('/events/sync-status')
      console.log('📅 Sync status loaded')
      return response

    } catch (error) {
      console.error('📅 Error loading sync status:', error)
      return {
        success: false,
        data: {},
        meta: { overall_status: 'error' }
      }
    }
  }

  // Fallback: Generate events using static data (for when API is not available)
  async generateEvents(category?: string, limit: number = 10): Promise<EventsResponse> {
    try {
      const cacheKey = `events_static_${category || 'all'}_${limit}`
      
      // Check cache first
      if (this.isCacheValid(cacheKey)) {
        const cached = this.cache.get(cacheKey)
        if (cached) {
          console.log('📅 Using cached static events data')
          return cached
        }
      }

      console.log('📅 Using static events data (API fallback)')
      const staticEvents = this.createStaticEvents(category)
      const result: EventsResponse = {
        success: true,
        data: staticEvents.slice(0, limit),
        meta: {
          total: staticEvents.length,
          sources: ['internal']
        }
      }
      
      // Cache the result
      this.setCache(cacheKey, result)
      return result

    } catch (error) {
      console.error('📅 Error generating static events:', error)
      // Return fallback content on error
      const fallbackEvents = this.createFallbackEvents(category)
      return {
        success: true,
        data: fallbackEvents,
        meta: {
          total: fallbackEvents.length,
          sources: ['internal']
        }
      }
    }
  }

  // Create static events data (fallback)
  private createStaticEvents(category?: string): Event[] {
    const allEvents: Event[] = [
      {
        id: 'stargate-announcement-2024',
        title: 'Stargate Project Official Announcement',
        description: 'OpenAI, SoftBank, and Arm officially announce the Stargate Project - a $500 billion AI infrastructure initiative.',
        category: 'stargate',
        type: 'announcement',
        event_date: '2024-12-15',
        event_time: '10:00:00',
        location: 'San Francisco, CA',
        organizer: 'OpenAI',
        icon: '🚀',
        video_url: 'https://www.youtube.com/watch?v=example1',
        source: 'internal',
        is_featured: true,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      },
      {
        id: 'cristal-intelligence-summit',
        title: 'Cristal Intelligence Summit 2024',
        description: 'Exploring the future of cristalline computing and transparent AI systems.',
        category: 'cristal',
        type: 'conference',
        event_date: '2024-12-20',
        event_time: '09:00:00',
        location: 'New York, NY',
        organizer: 'AI Research Institute',
        icon: '💎',
        registration_url: 'https://www.eventbrite.com/e/cristal-intelligence-summit-2024-tickets-123456789',
        video_url: 'https://www.youtube.com/watch?v=cristal-intelligence-summit-2024',
        source: 'internal',
        is_featured: false,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      },
      {
        id: 'softbank-investor-meeting',
        title: 'SoftBank Vision Fund AI Strategy Meeting',
        description: 'SoftBank discusses their AI investment strategy and Stargate Project involvement.',
        category: 'stargate',
        type: 'meeting',
        event_date: '2024-12-25',
        event_time: '14:00:00',
        location: 'Tokyo, Japan',
        organizer: 'SoftBank Group',
        icon: '📊',
        video_url: 'https://www.youtube.com/watch?v=example2',
        source: 'internal',
        is_featured: false,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      },
      {
        id: 'arm-chip-announcement',
        title: 'ARM Next-Gen AI Chip Announcement',
        description: 'ARM unveils new AI-optimized chip architecture for Stargate Project infrastructure.',
        category: 'stargate',
        type: 'announcement',
        event_date: '2024-12-30',
        event_time: '11:00:00',
        location: 'Cambridge, UK',
        organizer: 'ARM Holdings',
        icon: '🔧',
        video_url: 'https://www.youtube.com/watch?v=example3',
        source: 'internal',
        is_featured: true,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      },
      {
        id: 'ai-ethics-workshop',
        title: 'AI Ethics and Cristal Intelligence Workshop',
        description: 'Workshop on ethical AI development and the principles of Cristal Intelligence.',
        category: 'cristal',
        type: 'workshop',
        event_date: '2025-01-05',
        event_time: '10:00:00',
        location: 'Stanford University, CA',
        organizer: 'Stanford AI Lab',
        icon: '🎓',
        registration_url: 'https://stanford.edu/ai-ethics-workshop',
        source: 'internal',
        is_featured: false,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      },
      {
        id: 'openai-developer-conference',
        title: 'OpenAI Developer Conference 2025',
        description: 'OpenAI showcases new AI models and Stargate Project developments.',
        category: 'stargate',
        type: 'conference',
        event_date: '2025-01-10',
        event_time: '09:00:00',
        location: 'San Francisco, CA',
        organizer: 'OpenAI',
        icon: '🤖',
        registration_url: 'https://openai.com/developer-conference-2025',
        source: 'internal',
        is_featured: true,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      },
      {
        id: 'cristal-transparency-seminar',
        title: 'Cristal Intelligence Transparency Seminar',
        description: 'Deep dive into transparent AI systems and cristalline computing principles.',
        category: 'cristal',
        type: 'workshop',
        event_date: '2025-01-15',
        event_time: '14:00:00',
        location: 'MIT, Cambridge, MA',
        organizer: 'MIT CSAIL',
        icon: '🔬',
        registration_url: 'https://mit.edu/cristal-transparency-seminar',
        source: 'internal',
        is_featured: false,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      },
      {
        id: 'stargate-infrastructure-update',
        title: 'Stargate Infrastructure Progress Update',
        description: 'Latest updates on Stargate Project infrastructure development and deployment.',
        category: 'stargate',
        type: 'announcement',
        event_date: '2025-01-20',
        event_time: '13:00:00',
        location: 'Virtual Event',
        organizer: 'Stargate Project Team',
        icon: '🏗️',
        video_url: 'https://www.youtube.com/watch?v=example4',
        source: 'internal',
        is_featured: false,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }
    ]

    if (category && category !== 'all') {
      return allEvents.filter(event => event.category === category)
    }

    return allEvents
  }

  // Create fallback events
  private createFallbackEvents(category?: string): Event[] {
    return [
      {
        id: 'fallback-event-1',
        title: 'AI Technology Conference 2024',
        description: 'Annual conference on artificial intelligence and machine learning technologies.',
        category: 'conferences',
        type: 'conference',
        event_date: '2024-12-15',
        event_time: '09:00:00',
        location: 'San Francisco, CA',
        organizer: 'AI Research Institute',
        icon: '🤖',
        registration_url: 'https://ai-research-institute.org/ai-conference-2024',
        source: 'internal',
        is_featured: false,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      },
      {
        id: 'fallback-event-2',
        title: 'Future of AI Workshop',
        description: 'Interactive workshop exploring the future of artificial intelligence.',
        category: 'conferences',
        type: 'workshop',
        event_date: '2024-12-20',
        event_time: '14:00:00',
        location: 'New York, NY',
        organizer: 'Tech Innovation Center',
        icon: '🎓',
        registration_url: 'https://ai-research-institute.org/ai-conference-2024',
        source: 'internal',
        is_featured: false,
        is_active: true,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }
    ]
  }

  // Clear all cache
  clearCache(): void {
    localStorage.removeItem('events_cache')
    console.log('🗑️ Cleared events cache')
  }

  // Force refresh without cache
  async forceRefreshEvents(limit: number = 20, filters: any = {}): Promise<EventsResponse> {
    this.clearCache()
    return this.getAllEvents(limit, filters)
  }
}

// Export singleton instance
export const eventsApiService = new EventsApiService()