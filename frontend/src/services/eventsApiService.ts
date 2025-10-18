// Events API Service - Static data only

export interface Event {
  id: string
  title: string
  description: string
  category: 'stargate' | 'cristal' | 'conferences' | 'meetings' | 'announcements'
  type: 'conference' | 'meeting' | 'announcement' | 'workshop' | 'video'
  date: string
  time?: string
  location: string
  organizer: string
  icon: string
  registrationUrl?: string
  videoUrl?: string
}

export interface EventsResponse {
  success: boolean
  events: Event[]
  total: number
  error?: string
}

class EventsApiService {
  private cache: Map<string, EventsResponse> = new Map()
  private cacheExpiry: Map<string, number> = new Map()
  private readonly CACHE_DURATION = 60 * 60 * 1000 // 1 hour

  // Generate events using static data
  async generateEvents(category?: string, limit: number = 10): Promise<EventsResponse> {
    try {
      const cacheKey = `events_${category || 'all'}_${limit}`
      
      // Check cache first
      if (this.isCacheValid(cacheKey)) {
        const cached = this.cache.get(cacheKey)
        if (cached) {
          console.log('📅 Using cached events data')
          return cached
        }
      }

      console.log('📅 Using static events data')
      const staticEvents = this.createStaticEvents(category)
      const result: EventsResponse = {
        success: true,
        events: staticEvents.slice(0, limit),
        total: staticEvents.length
      }
      
      // Cache the result
      this.cache.set(cacheKey, result)
      this.cacheExpiry.set(cacheKey, Date.now() + this.CACHE_DURATION)
      return result

    } catch (error) {
      console.error('📅 Error generating events:', error)
      // Return fallback content on error
      const fallbackEvents = this.createFallbackEvents(category)
      return {
        success: true,
        events: fallbackEvents,
        total: fallbackEvents.length
      }
    }
  }

  // Create static events data
  private createStaticEvents(category?: string): Event[] {
    const allEvents: Event[] = [
      {
        id: 'stargate-announcement-2024',
        title: 'Stargate Project Official Announcement',
        description: 'OpenAI, SoftBank, and Arm officially announce the Stargate Project - a $500 billion AI infrastructure initiative.',
        category: 'stargate',
        type: 'announcement',
        date: '2024-12-15',
        time: '10:00 AM PST',
        location: 'San Francisco, CA',
        organizer: 'OpenAI',
        icon: '🚀',
        videoUrl: 'https://www.youtube.com/watch?v=example1'
      },
      {
        id: 'cristal-intelligence-summit',
        title: 'Cristal Intelligence Summit 2024',
        description: 'Exploring the future of cristalline computing and transparent AI systems.',
        category: 'cristal',
        type: 'conference',
        date: '2024-12-20',
        time: '9:00 AM EST',
        location: 'New York, NY',
        organizer: 'AI Research Institute',
        icon: '💎',
        registrationUrl: 'https://example.com/register'
      },
      {
        id: 'softbank-investor-meeting',
        title: 'SoftBank Vision Fund AI Strategy Meeting',
        description: 'SoftBank discusses their AI investment strategy and Stargate Project involvement.',
        category: 'stargate',
        type: 'meeting',
        date: '2024-12-25',
        time: '2:00 PM JST',
        location: 'Tokyo, Japan',
        organizer: 'SoftBank Group',
        icon: '📊',
        videoUrl: 'https://www.youtube.com/watch?v=example2'
      },
      {
        id: 'arm-chip-announcement',
        title: 'ARM Next-Gen AI Chip Announcement',
        description: 'ARM unveils new AI-optimized chip architecture for Stargate Project infrastructure.',
        category: 'stargate',
        type: 'announcement',
        date: '2024-12-30',
        time: '11:00 AM GMT',
        location: 'Cambridge, UK',
        organizer: 'ARM Holdings',
        icon: '🔧',
        videoUrl: 'https://www.youtube.com/watch?v=example3'
      },
      {
        id: 'ai-ethics-workshop',
        title: 'AI Ethics and Cristal Intelligence Workshop',
        description: 'Workshop on ethical AI development and the principles of Cristal Intelligence.',
        category: 'cristal',
        type: 'workshop',
        date: '2025-01-05',
        time: '10:00 AM PST',
        location: 'Stanford University, CA',
        organizer: 'Stanford AI Lab',
        icon: '🎓',
        registrationUrl: 'https://example.com/workshop'
      },
      {
        id: 'openai-developer-conference',
        title: 'OpenAI Developer Conference 2025',
        description: 'OpenAI showcases new AI models and Stargate Project developments.',
        category: 'stargate',
        type: 'conference',
        date: '2025-01-10',
        time: '9:00 AM PST',
        location: 'San Francisco, CA',
        organizer: 'OpenAI',
        icon: '🤖',
        registrationUrl: 'https://example.com/openai-conf'
      },
      {
        id: 'cristal-transparency-seminar',
        title: 'Cristal Intelligence Transparency Seminar',
        description: 'Deep dive into transparent AI systems and cristalline computing principles.',
        category: 'cristal',
        type: 'workshop',
        date: '2025-01-15',
        time: '2:00 PM EST',
        location: 'MIT, Cambridge, MA',
        organizer: 'MIT CSAIL',
        icon: '🔬',
        registrationUrl: 'https://example.com/mit-seminar'
      },
      {
        id: 'stargate-infrastructure-update',
        title: 'Stargate Infrastructure Progress Update',
        description: 'Latest updates on Stargate Project infrastructure development and deployment.',
        category: 'stargate',
        type: 'announcement',
        date: '2025-01-20',
        time: '1:00 PM PST',
        location: 'Virtual Event',
        organizer: 'Stargate Project Team',
        icon: '🏗️',
        videoUrl: 'https://www.youtube.com/watch?v=example4'
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
        date: '2024-12-15',
        time: '9:00 AM',
        location: 'San Francisco, CA',
        organizer: 'AI Research Institute',
        icon: '🤖',
        registrationUrl: 'https://example.com'
      },
      {
        id: 'fallback-event-2',
        title: 'Future of AI Workshop',
        description: 'Interactive workshop exploring the future of artificial intelligence.',
        category: 'conferences',
        type: 'workshop',
        date: '2024-12-20',
        time: '2:00 PM',
        location: 'New York, NY',
        organizer: 'Tech Innovation Center',
        icon: '🎓',
        registrationUrl: 'https://example.com'
      }
    ]
  }

  // Check if cache is valid
  private isCacheValid(key: string): boolean {
    const expiry = this.cacheExpiry.get(key)
    return expiry ? Date.now() < expiry : false
  }

  // Get events by category
  async getEventsByCategory(category: string, limit: number = 10): Promise<EventsResponse> {
    return this.generateEvents(category, limit)
  }

  // Get all events
  async getAllEvents(limit: number = 20): Promise<EventsResponse> {
    return this.generateEvents('all', limit)
  }

  // Get upcoming events
  async getUpcomingEvents(limit: number = 10): Promise<EventsResponse> {
    const allEvents = await this.getAllEvents(limit)
    const now = new Date()
    
    const upcomingEvents = allEvents.events.filter(event => {
      const eventDate = new Date(event.date)
      return eventDate >= now
    })

    return {
      success: true,
      events: upcomingEvents.slice(0, limit),
      total: upcomingEvents.length
    }
  }

  // Search events
  async searchEvents(query: string, limit: number = 10): Promise<EventsResponse> {
    const allEvents = await this.getAllEvents(50)
    
    const searchResults = allEvents.events.filter(event => 
      event.title.toLowerCase().includes(query.toLowerCase()) ||
      event.description.toLowerCase().includes(query.toLowerCase()) ||
      event.organizer.toLowerCase().includes(query.toLowerCase())
    )

    return {
      success: true,
      events: searchResults.slice(0, limit),
      total: searchResults.length
    }
  }
}

// Export singleton instance
export const eventsApiService = new EventsApiService()