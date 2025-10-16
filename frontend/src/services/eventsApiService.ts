import { openaiService } from './openaiService'

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

  // Generate events using OpenAI
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

      // Check if OpenAI API key is configured
      const apiKey = import.meta.env.VITE_OPENAI_API_KEY
      if (!apiKey || apiKey === 'sk-your-openai-api-key-here') {
        console.log('📅 OpenAI API key not configured, using fallback events')
        const fallbackEvents = this.createFallbackEvents(category)
        const result: EventsResponse = {
          success: true,
          events: fallbackEvents,
          total: fallbackEvents.length
        }
        
        // Cache the fallback result
        this.cache.set(cacheKey, result)
        this.cacheExpiry.set(cacheKey, Date.now() + this.CACHE_DURATION)
        return result
      }

      const prompt = this.createEventsPrompt(category, limit)
      const response = await openaiService.createChatCompletion({
        model: 'gpt-4',
        messages: [
          { 
            role: 'system', 
            content: 'You are a helpful assistant that generates realistic upcoming events related to the Stargate Project and Cristal Intelligence. Focus on creating events that would realistically happen in the AI industry, including conferences, meetings, announcements, and workshops.' 
          },
          { role: 'user', content: prompt }
        ],
        max_tokens: 2000,
        temperature: 0.7
      })

      if (response && response.choices && response.choices.length > 0) {
        const generatedContent = response.choices[0].message.content
        const events = this.parseEventsContent(generatedContent, category)
        const result: EventsResponse = {
          success: true,
          events: events,
          total: events.length
        }
        this.cache.set(cacheKey, result)
        this.cacheExpiry.set(cacheKey, Date.now() + this.CACHE_DURATION)
        return result
      } else {
        console.warn('📅 OpenAI API did not return expected data, using fallback events.')
        const fallbackEvents = this.createFallbackEvents(category)
        return {
          success: true,
          events: fallbackEvents,
          total: fallbackEvents.length
        }
      }
    } catch (error) {
      console.error('📅 Error generating events with OpenAI:', error)
      // Return fallback content on error
      const fallbackEvents = this.createFallbackEvents(category)
      return {
        success: true,
        events: fallbackEvents,
        total: fallbackEvents.length
      }
    }
  }

  // Create prompt for events generation
  private createEventsPrompt(category?: string, limit: number = 10): string {
    const basePrompt = `Generate ${limit} realistic upcoming events related to the Stargate Project and Cristal Intelligence. 
    Each event should be informative, well-researched, and relevant to current AI developments.
    
    Return the events in the following JSON format:
    {
      "events": [
        {
          "title": "Event Title",
          "description": "Detailed description of the event (2-3 sentences)",
          "category": "stargate|cristal|conferences|meetings|announcements",
          "type": "conference|meeting|announcement|workshop|video",
          "date": "YYYY-MM-DD",
          "time": "HH:MM UTC",
          "location": "City, Country or Virtual Event",
          "organizer": "Organization Name",
          "icon": "🚀|💎|🎤|🤝|📢|👨‍💻|🎥|🏗️|⚖️|🔧|🎉|📦",
          "registrationUrl": "https://example.com/register (optional)",
          "videoUrl": "https://youtube.com/embed/VIDEO_ID (optional)"
        }
      ]
    }`

    if (category) {
      const categoryPrompts = {
        stargate: `Focus on events related to the Stargate Project, including infrastructure development, partnerships with OpenAI, SoftBank, and Arm, data center construction, AI model training facilities, and project milestones.`,
        cristal: `Focus on events related to Cristal Intelligence, including framework launches, ethics symposiums, developer workshops, transparent AI demonstrations, and research presentations.`,
        conferences: `Focus on major conferences, summits, and speaking engagements where Stargate Project and Cristal Intelligence topics are discussed.`,
        meetings: `Focus on strategic partnership meetings, technical discussions, and collaboration sessions between OpenAI, SoftBank, Arm, and other stakeholders.`,
        announcements: `Focus on important announcements, milestone releases, open source launches, and major updates about the projects.`
      }
      
      return `${basePrompt}\n\n${categoryPrompts[category as keyof typeof categoryPrompts] || ''}`
    }

    return basePrompt
  }

  // Parse generated content into events
  private parseEventsContent(content: string, category?: string): Event[] {
    try {
      // Try to extract JSON from the response
      const jsonMatch = content.match(/\{[\s\S]*\}/)
      if (!jsonMatch) {
        throw new Error('No JSON found in response')
      }

      const parsed = JSON.parse(jsonMatch[0])
      
      if (!parsed.events || !Array.isArray(parsed.events)) {
        throw new Error('Invalid events format')
      }

      return parsed.events.map((event: any, index: number) => ({
        id: `ai_generated_${Date.now()}_${index}`,
        title: event.title || 'Untitled Event',
        description: event.description || 'No description available',
        category: event.category || 'announcements',
        type: event.type || 'announcement',
        date: event.date || new Date().toISOString().split('T')[0],
        time: event.time,
        location: event.location || 'TBD',
        organizer: event.organizer || 'TBD',
        icon: event.icon || '📅',
        registrationUrl: event.registrationUrl,
        videoUrl: event.videoUrl
      }))
    } catch (error) {
      console.error('Error parsing events content:', error)
      return this.createFallbackEvents(category)
    }
  }

  // Create fallback events when OpenAI is not available
  private createFallbackEvents(category?: string): Event[] {
    const allEvents: Event[] = [
      // Stargate Project Events
      {
        id: 'fallback_stargate_1',
        title: 'Stargate Project Q4 2024 Progress Update',
        description: 'Comprehensive update on the Stargate Project infrastructure development, including progress on data centers, AI model training facilities, and partnership milestones.',
        category: 'stargate',
        type: 'conference',
        date: '2024-12-15',
        time: '14:00 UTC',
        location: 'Virtual Event',
        organizer: 'OpenAI',
        icon: '🚀',
        registrationUrl: 'https://openai.com/events/stargate-q4-2024'
      },
      {
        id: 'fallback_stargate_2',
        title: 'Stargate Infrastructure Summit 2025',
        description: 'Join industry leaders for a deep dive into the technical infrastructure powering the Stargate Project. Learn about data center design, energy efficiency, and scalability challenges.',
        category: 'stargate',
        type: 'conference',
        date: '2025-02-20',
        time: '09:00 UTC',
        location: 'San Francisco, CA',
        organizer: 'OpenAI & SoftBank',
        icon: '🏗️',
        registrationUrl: 'https://stargate-summit-2025.com'
      },
      {
        id: 'fallback_stargate_3',
        title: 'Stargate-Arm Partnership Announcement',
        description: 'Major announcement about the collaboration between Stargate Project and Arm for next-generation AI chip development and optimization.',
        category: 'stargate',
        type: 'announcement',
        date: '2024-11-30',
        time: '10:00 UTC',
        location: 'Virtual Event',
        organizer: 'OpenAI & Arm',
        icon: '🤝'
      },

      // Cristal Intelligence Events
      {
        id: 'fallback_cristal_1',
        title: 'Cristal Intelligence Framework Launch',
        description: 'Official launch of the Cristal Intelligence framework, introducing new paradigms for transparent and ethical AI development.',
        category: 'cristal',
        type: 'announcement',
        date: '2024-12-01',
        time: '15:00 UTC',
        location: 'Virtual Event',
        organizer: 'OpenAI Research',
        icon: '💎'
      },
      {
        id: 'fallback_cristal_2',
        title: 'Cristal Intelligence Ethics Symposium',
        description: 'Exploring the ethical implications of transparent AI systems and their impact on society, governance, and human-AI collaboration.',
        category: 'cristal',
        type: 'conference',
        date: '2025-01-25',
        time: '13:00 UTC',
        location: 'Cambridge, UK',
        organizer: 'Cambridge AI Ethics Institute',
        icon: '⚖️',
        registrationUrl: 'https://cristal-ethics-symposium.org'
      },
      {
        id: 'fallback_cristal_3',
        title: 'Cristal Intelligence Developer Workshop',
        description: 'Hands-on workshop for developers interested in implementing Cristal Intelligence principles in their AI applications.',
        category: 'cristal',
        type: 'workshop',
        date: '2025-03-10',
        time: '10:00 UTC',
        location: 'Virtual Event',
        organizer: 'OpenAI Developer Relations',
        icon: '👨‍💻',
        registrationUrl: 'https://openai.com/workshops/cristal-dev'
      },

      // Partnership Meetings
      {
        id: 'fallback_meeting_1',
        title: 'OpenAI-SoftBank Strategic Partnership Meeting',
        description: 'Quarterly strategic meeting between OpenAI and SoftBank leadership to discuss Stargate Project progress and future investments.',
        category: 'meetings',
        type: 'meeting',
        date: '2024-12-10',
        time: '08:00 UTC',
        location: 'Tokyo, Japan',
        organizer: 'OpenAI & SoftBank',
        icon: '🤝'
      },
      {
        id: 'fallback_meeting_2',
        title: 'Arm Chip Optimization for AI Workloads',
        description: 'Technical meeting focused on optimizing Arm-based processors for Stargate Project AI training and inference workloads.',
        category: 'meetings',
        type: 'meeting',
        date: '2025-01-15',
        time: '11:00 UTC',
        location: 'Cambridge, UK',
        organizer: 'Arm Technologies',
        icon: '🔧'
      },

      // Video Content
      {
        id: 'fallback_video_1',
        title: 'Behind the Scenes: Stargate Project Construction',
        description: 'Exclusive behind-the-scenes look at the construction of Stargate Project data centers and infrastructure facilities.',
        category: 'stargate',
        type: 'video',
        date: '2024-11-20',
        time: '16:00 UTC',
        location: 'YouTube',
        organizer: 'OpenAI',
        icon: '🎥',
        videoUrl: 'https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&modestbranding=1'
      },
      {
        id: 'fallback_video_2',
        title: 'Cristal Intelligence Live Demo',
        description: 'Live demonstration of Cristal Intelligence capabilities, showing transparent decision-making processes in real-time.',
        category: 'cristal',
        type: 'video',
        date: '2024-12-05',
        time: '14:30 UTC',
        location: 'YouTube',
        organizer: 'OpenAI Research',
        icon: '🎬',
        videoUrl: 'https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&modestbranding=1'
      },

      // Announcements
      {
        id: 'fallback_announcement_1',
        title: 'Stargate Project Major Milestone Achievement',
        description: 'Announcement of a significant milestone in the Stargate Project development, marking a new phase in AI infrastructure capabilities.',
        category: 'announcements',
        type: 'announcement',
        date: '2025-02-01',
        time: '12:00 UTC',
        location: 'Virtual Event',
        organizer: 'OpenAI',
        icon: '🎉'
      },
      {
        id: 'fallback_announcement_2',
        title: 'Cristal Intelligence Open Source Release',
        description: 'Major announcement about making Cristal Intelligence framework open source, enabling global collaboration on transparent AI development.',
        category: 'announcements',
        type: 'announcement',
        date: '2025-04-15',
        time: '15:00 UTC',
        location: 'Virtual Event',
        organizer: 'OpenAI',
        icon: '📦'
      }
    ]

    // If no category specified, return all events
    if (!category || category === 'all') {
      return allEvents
    }

    // Return events for specific category
    return allEvents.filter(event => event.category === category)
  }

  // Check if cache is valid
  private isCacheValid(key: string): boolean {
    const expiry = this.cacheExpiry.get(key)
    return expiry ? Date.now() < expiry : false
  }

  // Clear cache
  clearCache(): void {
    this.cache.clear()
    this.cacheExpiry.clear()
  }

  // Get cached events
  getCachedEvents(category?: string): Event[] {
    const cacheKey = `events_${category || 'all'}_10`
    const cached = this.cache.get(cacheKey)
    return cached ? cached.events : []
  }

  // Search events
  async searchEvents(query: string, category?: string): Promise<EventsResponse> {
    try {
      const prompt = `Search for events related to "${query}" in the context of Stargate Project and Cristal Intelligence.
      
      Return matching events in the following JSON format:
      {
        "events": [
          {
            "title": "Event Title",
            "description": "Event description",
            "category": "stargate|cristal|conferences|meetings|announcements",
            "type": "conference|meeting|announcement|workshop|video",
            "date": "YYYY-MM-DD",
            "time": "HH:MM UTC",
            "location": "Location",
            "organizer": "Organizer",
            "icon": "🚀|💎|🎤|🤝|📢|👨‍💻|🎥|🏗️|⚖️|🔧|🎉|📦",
            "registrationUrl": "https://example.com/register (optional)",
            "videoUrl": "https://youtube.com/embed/VIDEO_ID (optional)"
          }
        ]
      }`

      const response = await openaiService.generateText(prompt, {
        model: 'gpt-4',
        max_tokens: 2000,
        temperature: 0.7
      })

      if (!response.success) {
        throw new Error(response.error || 'Search failed')
      }

      const events = this.parseEventsContent(response.content, category)
      
      return {
        success: true,
        events,
        total: events.length
      }

    } catch (error) {
      console.error('Error searching events:', error)
      return {
        success: false,
        events: [],
        total: 0,
        error: error instanceof Error ? error.message : 'Search failed'
      }
    }
  }

  // Get upcoming events (next 30 days)
  async getUpcomingEvents(): Promise<EventsResponse> {
    try {
      const now = new Date()
      const thirtyDaysFromNow = new Date(now.getTime() + 30 * 24 * 60 * 60 * 1000)
      
      const prompt = `Generate upcoming events for the next 30 days (${now.toISOString().split('T')[0]} to ${thirtyDaysFromNow.toISOString().split('T')[0]}) related to Stargate Project and Cristal Intelligence.
      
      Focus on realistic events that could happen in the near future, including:
      - Progress updates and milestone announcements
      - Partnership meetings and strategic discussions
      - Technical workshops and developer events
      - Conference presentations and speaking engagements
      - Video releases and demonstrations
      
      Return events in the following JSON format:
      {
        "events": [
          {
            "title": "Event Title",
            "description": "Event description",
            "category": "stargate|cristal|conferences|meetings|announcements",
            "type": "conference|meeting|announcement|workshop|video",
            "date": "YYYY-MM-DD",
            "time": "HH:MM UTC",
            "location": "Location",
            "organizer": "Organizer",
            "icon": "🚀|💎|🎤|🤝|📢|👨‍💻|🎥|🏗️|⚖️|🔧|🎉|📦",
            "registrationUrl": "https://example.com/register (optional)",
            "videoUrl": "https://youtube.com/embed/VIDEO_ID (optional)"
          }
        ]
      }`

      const response = await openaiService.generateText(prompt, {
        model: 'gpt-4',
        max_tokens: 2500,
        temperature: 0.8
      })

      if (!response.success) {
        throw new Error(response.error || 'Failed to get upcoming events')
      }

      const events = this.parseEventsContent(response.content)
      
      return {
        success: true,
        events,
        total: events.length
      }

    } catch (error) {
      console.error('Error getting upcoming events:', error)
      // Return fallback events on error
      const fallbackEvents = this.createFallbackEvents()
      return {
        success: true,
        events: fallbackEvents,
        total: fallbackEvents.length
      }
    }
  }
}

// Export singleton instance
export const eventsApiService = new EventsApiService()
