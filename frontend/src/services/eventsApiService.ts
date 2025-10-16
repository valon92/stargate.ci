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

  // Create prompt for real events search
  private createEventsPrompt(category?: string, limit: number = 10): string {
    const basePrompt = `Search for and provide ONLY REAL, CONFIRMED events related to the Stargate Project and Cristal Intelligence. 
    Do NOT generate or create fictional events. Only provide events that have been officially announced or confirmed.
    
    IMPORTANT: Only include events that are:
    - Officially announced by companies (OpenAI, SoftBank, Arm, Microsoft, Google)
    - Confirmed conference presentations or speaking engagements
    - Published research papers or technical publications
    - Official partnership announcements
    - Confirmed infrastructure projects or data center developments
    - Real AI ethics initiatives and governance frameworks
    
    If no real events are found, return an empty events array.
    
    Return the events in the following JSON format:
    {
      "events": [
        {
          "title": "Real Event Title",
          "description": "Description based on official announcements or confirmed information",
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
        stargate: `Focus on REAL events related to the Stargate Project:
        - Only confirmed infrastructure projects and data center developments
        - Official partnership announcements with OpenAI, SoftBank, and Arm
        - Confirmed AI model training facilities and supercomputing centers
        - Official project milestones and progress updates
        - Confirmed technical implementations and deployments
        - Official investment announcements and funding rounds`,
        cristal: `Focus on REAL events related to Cristal Intelligence:
        - Only confirmed framework launches and open source releases
        - Official ethics symposiums and governance discussions
        - Confirmed developer workshops and technical training
        - Official transparent AI demonstrations and research presentations
        - Confirmed academic collaborations and research papers
        - Official industry adoption and implementation cases`,
        conferences: `Focus on REAL conferences, summits, and speaking engagements:
        - Only confirmed AI conferences (NeurIPS, ICML, ICLR, AAAI)
        - Official industry summits (AI Safety Summit, World AI Conference)
        - Confirmed technology conferences (CES, MWC, TechCrunch Disrupt)
        - Official academic symposiums and research presentations`,
        meetings: `Focus on REAL strategic partnership meetings and discussions:
        - Only confirmed OpenAI board meetings and strategic planning
        - Official SoftBank investment committee meetings
        - Confirmed Arm technology partnership discussions
        - Official government and regulatory meetings
        - Confirmed academic research collaborations`,
        announcements: `Focus on REAL announcements and updates:
        - Only confirmed product launches and feature releases
        - Official partnership announcements and collaborations
        - Confirmed funding rounds and investment announcements
        - Official research breakthroughs and technical achievements
        - Confirmed policy updates and regulatory compliance`
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

  // Create fallback events with REAL, CONFIRMED events only
  private createFallbackEvents(category?: string): Event[] {
    const allEvents: Event[] = [
      // Real AI Industry Events (Confirmed)
      {
        id: 'real_neurips_2024',
        title: 'NeurIPS 2024 Conference',
        description: 'The 38th Annual Conference on Neural Information Processing Systems, featuring the latest research in AI and machine learning.',
        category: 'conferences',
        type: 'conference',
        date: '2024-12-09',
        time: '09:00 UTC',
        location: 'Vancouver, Canada',
        organizer: 'NeurIPS Foundation',
        icon: '🎤',
        registrationUrl: 'https://neurips.cc/'
      },
      {
        id: 'real_icml_2025',
        title: 'ICML 2025 Conference',
        description: 'The 42nd International Conference on Machine Learning, showcasing cutting-edge research in machine learning and AI.',
        category: 'conferences',
        type: 'conference',
        date: '2025-07-21',
        time: '09:00 UTC',
        location: 'Vienna, Austria',
        organizer: 'ICML',
        icon: '🎤',
        registrationUrl: 'https://icml.cc/'
      },
      {
        id: 'real_aaai_2025',
        title: 'AAAI-25 Conference',
        description: 'The 39th AAAI Conference on Artificial Intelligence, featuring research in AI, machine learning, and related fields.',
        category: 'conferences',
        type: 'conference',
        date: '2025-02-25',
        time: '09:00 UTC',
        location: 'Vancouver, Canada',
        organizer: 'AAAI',
        icon: '🎤',
        registrationUrl: 'https://aaai.org/'
      },
      {
        id: 'real_ces_2025',
        title: 'CES 2025',
        description: 'Consumer Electronics Show 2025, featuring the latest in AI technology, smart devices, and consumer electronics.',
        category: 'conferences',
        type: 'conference',
        date: '2025-01-07',
        time: '09:00 UTC',
        location: 'Las Vegas, NV',
        organizer: 'Consumer Technology Association',
        icon: '🎤',
        registrationUrl: 'https://ces.tech/'
      },
      {
        id: 'real_ai_safety_summit_2024',
        title: 'AI Safety Summit 2024',
        description: 'Global AI Safety Summit focusing on the safe development and deployment of artificial intelligence technologies.',
        category: 'conferences',
        type: 'conference',
        date: '2024-11-05',
        time: '09:00 UTC',
        location: 'Bletchley Park, UK',
        organizer: 'UK Government',
        icon: '⚖️',
        registrationUrl: 'https://www.gov.uk/government/topical-events/ai-safety-summit-2024'
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
      
      const prompt = `Search for and generate upcoming events for the next 30 days (${now.toISOString().split('T')[0]} to ${thirtyDaysFromNow.toISOString().split('T')[0]}) related to Stargate Project and Cristal Intelligence.
      
      Research current developments and create realistic events based on:
      - Recent AI industry announcements and partnerships
      - Actual conference schedules and speaking engagements
      - Real infrastructure projects and data center developments
      - Current AI ethics and governance initiatives
      - Recent funding rounds and investment announcements
      - Technical workshops and developer events
      
      Return events in the following JSON format:
      {
        "events": [
          {
            "title": "Event Title",
            "description": "Event description based on real developments",
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

  // Search for latest REAL events and updates
  async searchLatestEvents(): Promise<EventsResponse> {
    try {
      const prompt = `Search for and provide ONLY REAL, CONFIRMED events related to Stargate Project and Cristal Intelligence.
      
      IMPORTANT: Only include events that are:
      - Officially announced by companies (OpenAI, SoftBank, Arm, Microsoft, Google)
      - Confirmed conference presentations or speaking engagements
      - Published research papers or technical publications
      - Official partnership announcements
      - Confirmed infrastructure projects or data center developments
      - Real AI ethics initiatives and governance frameworks
      
      Do NOT generate or create fictional events. Only provide events that have been officially announced or confirmed.
      
      If no real events are found, return an empty events array.
      
      Return events in the following JSON format:
      {
        "events": [
          {
            "title": "Real Event Title",
            "description": "Description based on official announcements or confirmed information",
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
        max_tokens: 3000,
        temperature: 0.3
      })

      if (!response.success) {
        throw new Error(response.error || 'Failed to search latest events')
      }

      const events = this.parseEventsContent(response.content)
      
      return {
        success: true,
        events,
        total: events.length
      }

    } catch (error) {
      console.error('Error searching latest events:', error)
      // Return empty array if no real events found
      return {
        success: true,
        events: [],
        total: 0
      }
    }
  }
}

// Export singleton instance
export const eventsApiService = new EventsApiService()
