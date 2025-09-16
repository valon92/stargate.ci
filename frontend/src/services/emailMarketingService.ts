// Email Marketing Service for Stargate.ci
import { ref, computed } from 'vue'

export interface Subscriber {
  id: string
  email: string
  firstName?: string
  lastName?: string
  status: 'active' | 'unsubscribed' | 'bounced' | 'complained'
  source: 'website' | 'referral' | 'social' | 'import' | 'manual' | 'other'
  tags: string[]
  customFields: Record<string, any>
  subscribedAt: string
  lastActivityAt?: string
  unsubscribedAt?: string
  bounceCount: number
  complaintCount: number
  location?: {
    country?: string
    city?: string
    timezone?: string
  }
}

export interface EmailCampaign {
  id: string
  name: string
  subject: string
  previewText?: string
  content: string
  templateId?: string
  status: 'draft' | 'scheduled' | 'sending' | 'sent' | 'paused' | 'cancelled'
  type: 'newsletter' | 'promotional' | 'transactional' | 'welcome' | 'follow-up' | 'announcement'
  audience: {
    segments: string[]
    filters: {
      tags?: string[]
      source?: string[]
      dateRange?: { start: string; end: string }
      customFields?: Record<string, any>
    }
  }
  scheduleDate?: string
  sentDate?: string
  stats: {
    sent: number
    delivered: number
    opened: number
    clicked: number
    bounced: number
    unsubscribed: number
    complained: number
    openRate: number
    clickRate: number
    bounceRate: number
    unsubscribeRate: number
    complaintRate: number
  }
  createdAt: string
  updatedAt: string
  createdBy: string
}

export interface EmailTemplate {
  id: string
  name: string
  subject: string
  previewText?: string
  content: string
  type: 'newsletter' | 'promotional' | 'transactional' | 'welcome' | 'follow-up' | 'announcement'
  category: string
  tags: string[]
  isPublic: boolean
  thumbnail?: string
  variables: string[]
  createdAt: string
  updatedAt: string
  createdBy: string
}

export interface EmailSegment {
  id: string
  name: string
  description: string
  criteria: {
    tags?: string[]
    source?: string[]
    dateRange?: { start: string; end: string }
    customFields?: Record<string, any>
    activity?: {
      type: 'opened' | 'clicked' | 'bounced' | 'unsubscribed'
      campaignId?: string
      daysAgo?: number
    }
  }
  subscriberCount: number
  createdAt: string
  updatedAt: string
  createdBy: string
}

export interface EmailStats {
  totalSubscribers: number
  activeSubscribers: number
  unsubscribedSubscribers: number
  bouncedSubscribers: number
  totalCampaigns: number
  sentCampaigns: number
  totalEmailsSent: number
  averageOpenRate: number
  averageClickRate: number
  averageBounceRate: number
  averageUnsubscribeRate: number
  thisMonthEmailsSent: number
  thisMonthOpenRate: number
  thisMonthClickRate: number
  topPerformingCampaigns: Array<{
    id: string
    name: string
    openRate: number
    clickRate: number
    sent: number
  }>
  subscriberGrowth: Array<{
    date: string
    subscribers: number
    newSubscribers: number
    unsubscribed: number
  }>
  sourceDistribution: Array<{
    source: string
    count: number
    percentage: number
  }>
}

export interface EmailSettings {
  fromName: string
  fromEmail: string
  replyToEmail: string
  unsubscribeUrl: string
  trackingEnabled: boolean
  doubleOptInEnabled: boolean
  autoRespondersEnabled: boolean
  bounceHandlingEnabled: boolean
  complaintHandlingEnabled: boolean
  customFields: Array<{ name: string; type: string; required: boolean }>
  tags: string[]
  categories: string[]
}

class EmailMarketingService {
  private subscribers = ref<Subscriber[]>([])
  private campaigns = ref<EmailCampaign[]>([])
  private templates = ref<EmailTemplate[]>([])
  private segments = ref<EmailSegment[]>([])
  private settings = ref<EmailSettings>({
    fromName: 'Stargate.ci',
    fromEmail: 'noreply@stargate.ci',
    replyToEmail: 'support@stargate.ci',
    unsubscribeUrl: 'https://stargate.ci/unsubscribe',
    trackingEnabled: true,
    doubleOptInEnabled: true,
    autoRespondersEnabled: true,
    bounceHandlingEnabled: true,
    complaintHandlingEnabled: true,
    customFields: [
      { name: 'Company', type: 'text', required: false },
      { name: 'Industry', type: 'select', required: false },
      { name: 'Role', type: 'text', required: false }
    ],
    tags: ['newsletter', 'promotional', 'transactional', 'welcome', 'follow-up', 'announcement'],
    categories: ['Technology', 'Business', 'Education', 'News', 'Updates']
  })

  constructor() {
    this.loadMockData()
  }

  // Subscriber Management
  async getSubscribers(): Promise<Subscriber[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve([...this.subscribers.value])
      }, 500)
    })
  }

  async getSubscriber(id: string): Promise<Subscriber | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const subscriber = this.subscribers.value.find(s => s.id === id)
        resolve(subscriber || null)
      }, 300)
    })
  }

  async addSubscriber(subscriberData: Omit<Subscriber, 'id' | 'subscribedAt' | 'bounceCount' | 'complaintCount'>): Promise<Subscriber> {
    return new Promise(resolve => {
      setTimeout(() => {
        const subscriber: Subscriber = {
          ...subscriberData,
          id: `subscriber_${Date.now()}`,
          subscribedAt: new Date().toISOString(),
          bounceCount: 0,
          complaintCount: 0
        }
        
        this.subscribers.value.unshift(subscriber)
        this.saveState()
        resolve(subscriber)
      }, 800)
    })
  }

  async updateSubscriber(id: string, updates: Partial<Subscriber>): Promise<Subscriber | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const index = this.subscribers.value.findIndex(s => s.id === id)
        if (index !== -1) {
          this.subscribers.value[index] = {
            ...this.subscribers.value[index],
            ...updates,
            lastActivityAt: new Date().toISOString()
          }
          this.saveState()
          resolve(this.subscribers.value[index])
        }
        resolve(null)
      }, 600)
    })
  }

  async unsubscribeSubscriber(id: string): Promise<boolean> {
    return new Promise(resolve => {
      setTimeout(() => {
        const index = this.subscribers.value.findIndex(s => s.id === id)
        if (index !== -1) {
          this.subscribers.value[index] = {
            ...this.subscribers.value[index],
            status: 'unsubscribed',
            unsubscribedAt: new Date().toISOString(),
            lastActivityAt: new Date().toISOString()
          }
          this.saveState()
          resolve(true)
        }
        resolve(false)
      }, 400)
    })
  }

  async deleteSubscriber(id: string): Promise<boolean> {
    return new Promise(resolve => {
      setTimeout(() => {
        const index = this.subscribers.value.findIndex(s => s.id === id)
        if (index !== -1) {
          this.subscribers.value.splice(index, 1)
          this.saveState()
          resolve(true)
        }
        resolve(false)
      }, 400)
    })
  }

  // Campaign Management
  async getCampaigns(): Promise<EmailCampaign[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve([...this.campaigns.value])
      }, 500)
    })
  }

  async getCampaign(id: string): Promise<EmailCampaign | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const campaign = this.campaigns.value.find(c => c.id === id)
        resolve(campaign || null)
      }, 300)
    })
  }

  async createCampaign(campaignData: Omit<EmailCampaign, 'id' | 'createdAt' | 'updatedAt' | 'stats'>): Promise<EmailCampaign> {
    return new Promise(resolve => {
      setTimeout(() => {
        const campaign: EmailCampaign = {
          ...campaignData,
          id: `campaign_${Date.now()}`,
          stats: {
            sent: 0,
            delivered: 0,
            opened: 0,
            clicked: 0,
            bounced: 0,
            unsubscribed: 0,
            complained: 0,
            openRate: 0,
            clickRate: 0,
            bounceRate: 0,
            unsubscribeRate: 0,
            complaintRate: 0
          },
          createdAt: new Date().toISOString(),
          updatedAt: new Date().toISOString(),
          createdBy: 'admin'
        }
        
        this.campaigns.value.unshift(campaign)
        this.saveState()
        resolve(campaign)
      }, 800)
    })
  }

  async updateCampaign(id: string, updates: Partial<EmailCampaign>): Promise<EmailCampaign | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const index = this.campaigns.value.findIndex(c => c.id === id)
        if (index !== -1) {
          this.campaigns.value[index] = {
            ...this.campaigns.value[index],
            ...updates,
            updatedAt: new Date().toISOString()
          }
          this.saveState()
          resolve(this.campaigns.value[index])
        }
        resolve(null)
      }, 600)
    })
  }

  async sendCampaign(id: string): Promise<boolean> {
    return new Promise(resolve => {
      setTimeout(() => {
        const index = this.campaigns.value.findIndex(c => c.id === id)
        if (index !== -1) {
          const campaign = this.campaigns.value[index]
          
          // Simulate sending
          const targetSubscribers = this.getTargetSubscribers(campaign.audience)
          const sent = targetSubscribers.length
          
          // Simulate delivery stats
          const delivered = Math.floor(sent * 0.95) // 95% delivery rate
          const opened = Math.floor(delivered * 0.25) // 25% open rate
          const clicked = Math.floor(opened * 0.15) // 15% click rate
          const bounced = sent - delivered
          const unsubscribed = Math.floor(delivered * 0.01) // 1% unsubscribe rate
          const complained = Math.floor(delivered * 0.005) // 0.5% complaint rate
          
          this.campaigns.value[index] = {
            ...campaign,
            status: 'sent',
            sentDate: new Date().toISOString(),
            stats: {
              sent,
              delivered,
              opened,
              clicked,
              bounced,
              unsubscribed,
              complained,
              openRate: delivered > 0 ? (opened / delivered) * 100 : 0,
              clickRate: delivered > 0 ? (clicked / delivered) * 100 : 0,
              bounceRate: sent > 0 ? (bounced / sent) * 100 : 0,
              unsubscribeRate: delivered > 0 ? (unsubscribed / delivered) * 100 : 0,
              complaintRate: delivered > 0 ? (complained / delivered) * 100 : 0
            },
            updatedAt: new Date().toISOString()
          }
          
          this.saveState()
          resolve(true)
        }
        resolve(false)
      }, 2000)
    })
  }

  // Template Management
  async getTemplates(): Promise<EmailTemplate[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve([...this.templates.value])
      }, 500)
    })
  }

  async getTemplate(id: string): Promise<EmailTemplate | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const template = this.templates.value.find(t => t.id === id)
        resolve(template || null)
      }, 300)
    })
  }

  async createTemplate(templateData: Omit<EmailTemplate, 'id' | 'createdAt' | 'updatedAt' | 'createdBy'>): Promise<EmailTemplate> {
    return new Promise(resolve => {
      setTimeout(() => {
        const template: EmailTemplate = {
          ...templateData,
          id: `template_${Date.now()}`,
          createdAt: new Date().toISOString(),
          updatedAt: new Date().toISOString(),
          createdBy: 'admin'
        }
        
        this.templates.value.unshift(template)
        this.saveState()
        resolve(template)
      }, 800)
    })
  }

  // Segment Management
  async getSegments(): Promise<EmailSegment[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve([...this.segments.value])
      }, 500)
    })
  }

  async createSegment(segmentData: Omit<EmailSegment, 'id' | 'subscriberCount' | 'createdAt' | 'updatedAt' | 'createdBy'>): Promise<EmailSegment> {
    return new Promise(resolve => {
      setTimeout(() => {
        const segment: EmailSegment = {
          ...segmentData,
          id: `segment_${Date.now()}`,
          subscriberCount: this.calculateSegmentSize(segmentData.criteria),
          createdAt: new Date().toISOString(),
          updatedAt: new Date().toISOString(),
          createdBy: 'admin'
        }
        
        this.segments.value.unshift(segment)
        this.saveState()
        resolve(segment)
      }, 800)
    })
  }

  // Analytics and Stats
  async getStats(): Promise<EmailStats> {
    return new Promise(resolve => {
      setTimeout(() => {
        const totalSubscribers = this.subscribers.value.length
        const activeSubscribers = this.subscribers.value.filter(s => s.status === 'active').length
        const unsubscribedSubscribers = this.subscribers.value.filter(s => s.status === 'unsubscribed').length
        const bouncedSubscribers = this.subscribers.value.filter(s => s.status === 'bounced').length
        
        const totalCampaigns = this.campaigns.value.length
        const sentCampaigns = this.campaigns.value.filter(c => c.status === 'sent').length
        const totalEmailsSent = this.campaigns.value.reduce((sum, c) => sum + c.stats.sent, 0)
        
        const averageOpenRate = sentCampaigns > 0 
          ? this.campaigns.value.filter(c => c.status === 'sent')
              .reduce((sum, c) => sum + c.stats.openRate, 0) / sentCampaigns
          : 0
        
        const averageClickRate = sentCampaigns > 0
          ? this.campaigns.value.filter(c => c.status === 'sent')
              .reduce((sum, c) => sum + c.stats.clickRate, 0) / sentCampaigns
          : 0
        
        const averageBounceRate = sentCampaigns > 0
          ? this.campaigns.value.filter(c => c.status === 'sent')
              .reduce((sum, c) => sum + c.stats.bounceRate, 0) / sentCampaigns
          : 0
        
        const averageUnsubscribeRate = sentCampaigns > 0
          ? this.campaigns.value.filter(c => c.status === 'sent')
              .reduce((sum, c) => sum + c.stats.unsubscribeRate, 0) / sentCampaigns
          : 0
        
        // This month stats
        const thisMonth = new Date()
        thisMonth.setDate(1)
        const thisMonthCampaigns = this.campaigns.value.filter(c => 
          c.status === 'sent' && new Date(c.sentDate || c.updatedAt) >= thisMonth
        )
        
        const thisMonthEmailsSent = thisMonthCampaigns.reduce((sum, c) => sum + c.stats.sent, 0)
        const thisMonthOpenRate = thisMonthCampaigns.length > 0
          ? thisMonthCampaigns.reduce((sum, c) => sum + c.stats.openRate, 0) / thisMonthCampaigns.length
          : 0
        const thisMonthClickRate = thisMonthCampaigns.length > 0
          ? thisMonthCampaigns.reduce((sum, c) => sum + c.stats.clickRate, 0) / thisMonthCampaigns.length
          : 0
        
        // Top performing campaigns
        const topPerformingCampaigns = this.campaigns.value
          .filter(c => c.status === 'sent')
          .sort((a, b) => b.stats.openRate - a.stats.openRate)
          .slice(0, 5)
          .map(c => ({
            id: c.id,
            name: c.name,
            openRate: c.stats.openRate,
            clickRate: c.stats.clickRate,
            sent: c.stats.sent
          }))
        
        // Subscriber growth (last 30 days)
        const subscriberGrowth = this.generateSubscriberGrowthData()
        
        // Source distribution
        const sourceStats = this.subscribers.value.reduce((acc, subscriber) => {
          acc[subscriber.source] = (acc[subscriber.source] || 0) + 1
          return acc
        }, {} as Record<string, number>)
        
        const sourceDistribution = Object.entries(sourceStats).map(([source, count]) => ({
          source,
          count,
          percentage: (count / totalSubscribers) * 100
        }))
        
        const stats: EmailStats = {
          totalSubscribers,
          activeSubscribers,
          unsubscribedSubscribers,
          bouncedSubscribers,
          totalCampaigns,
          sentCampaigns,
          totalEmailsSent,
          averageOpenRate,
          averageClickRate,
          averageBounceRate,
          averageUnsubscribeRate,
          thisMonthEmailsSent,
          thisMonthOpenRate,
          thisMonthClickRate,
          topPerformingCampaigns,
          subscriberGrowth,
          sourceDistribution
        }
        
        resolve(stats)
      }, 1000)
    })
  }

  // Settings Management
  async getSettings(): Promise<EmailSettings> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve({ ...this.settings.value })
      }, 300)
    })
  }

  async updateSettings(updates: Partial<EmailSettings>): Promise<EmailSettings> {
    return new Promise(resolve => {
      setTimeout(() => {
        this.settings.value = { ...this.settings.value, ...updates }
        this.saveState()
        resolve({ ...this.settings.value })
      }, 500)
    })
  }

  // Helper Methods
  private getTargetSubscribers(audience: EmailCampaign['audience']): Subscriber[] {
    let subscribers = [...this.subscribers.value.filter(s => s.status === 'active')]
    
    // Filter by segments
    if (audience.segments.length > 0) {
      const segmentSubscribers = this.segments.value
        .filter(s => audience.segments.includes(s.id))
        .flatMap(segment => this.getSubscribersByCriteria(segment.criteria))
      
      subscribers = subscribers.filter(s => 
        segmentSubscribers.some(ss => ss.id === s.id)
      )
    }
    
    // Apply additional filters
    if (audience.filters.tags && audience.filters.tags.length > 0) {
      subscribers = subscribers.filter(s => 
        audience.filters.tags!.some(tag => s.tags.includes(tag))
      )
    }
    
    if (audience.filters.source && audience.filters.source.length > 0) {
      subscribers = subscribers.filter(s => 
        audience.filters.source!.includes(s.source)
      )
    }
    
    if (audience.filters.dateRange) {
      const startDate = new Date(audience.filters.dateRange.start)
      const endDate = new Date(audience.filters.dateRange.end)
      subscribers = subscribers.filter(s => {
        const subscribeDate = new Date(s.subscribedAt)
        return subscribeDate >= startDate && subscribeDate <= endDate
      })
    }
    
    return subscribers
  }

  private getSubscribersByCriteria(criteria: EmailSegment['criteria']): Subscriber[] {
    let subscribers = [...this.subscribers.value.filter(s => s.status === 'active')]
    
    if (criteria.tags && criteria.tags.length > 0) {
      subscribers = subscribers.filter(s => 
        criteria.tags!.some(tag => s.tags.includes(tag))
      )
    }
    
    if (criteria.source && criteria.source.length > 0) {
      subscribers = subscribers.filter(s => 
        criteria.source!.includes(s.source)
      )
    }
    
    if (criteria.dateRange) {
      const startDate = new Date(criteria.dateRange.start)
      const endDate = new Date(criteria.dateRange.end)
      subscribers = subscribers.filter(s => {
        const subscribeDate = new Date(s.subscribedAt)
        return subscribeDate >= startDate && subscribeDate <= endDate
      })
    }
    
    return subscribers
  }

  private calculateSegmentSize(criteria: EmailSegment['criteria']): number {
    return this.getSubscribersByCriteria(criteria).length
  }

  private generateSubscriberGrowthData(): Array<{ date: string; subscribers: number; newSubscribers: number; unsubscribed: number }> {
    const data = []
    const today = new Date()
    
    for (let i = 29; i >= 0; i--) {
      const date = new Date(today)
      date.setDate(date.getDate() - i)
      
      const dateStr = date.toISOString().split('T')[0]
      const newSubscribers = Math.floor(Math.random() * 10) + 1
      const unsubscribed = Math.floor(Math.random() * 3)
      const subscribers = Math.max(0, this.subscribers.value.length + newSubscribers - unsubscribed)
      
      data.push({
        date: dateStr,
        subscribers,
        newSubscribers,
        unsubscribed
      })
    }
    
    return data
  }

  // Data Persistence
  private saveState(): void {
    try {
      localStorage.setItem('email-subscribers', JSON.stringify(this.subscribers.value))
      localStorage.setItem('email-campaigns', JSON.stringify(this.campaigns.value))
      localStorage.setItem('email-templates', JSON.stringify(this.templates.value))
      localStorage.setItem('email-segments', JSON.stringify(this.segments.value))
      localStorage.setItem('email-settings', JSON.stringify(this.settings.value))
    } catch (error) {
      console.error('Failed to save email marketing state:', error)
    }
  }

  private loadState(): void {
    try {
      const subscribers = localStorage.getItem('email-subscribers')
      if (subscribers) {
        this.subscribers.value = JSON.parse(subscribers)
      }
      
      const campaigns = localStorage.getItem('email-campaigns')
      if (campaigns) {
        this.campaigns.value = JSON.parse(campaigns)
      }
      
      const templates = localStorage.getItem('email-templates')
      if (templates) {
        this.templates.value = JSON.parse(templates)
      }
      
      const segments = localStorage.getItem('email-segments')
      if (segments) {
        this.segments.value = JSON.parse(segments)
      }
      
      const settings = localStorage.getItem('email-settings')
      if (settings) {
        this.settings.value = JSON.parse(settings)
      }
    } catch (error) {
      console.error('Failed to load email marketing state:', error)
    }
  }

  private loadMockData(): void {
    // Load existing data first
    this.loadState()
    
    // Add mock data if none exists
    if (this.subscribers.value.length === 0) {
      const mockSubscribers: Subscriber[] = [
        {
          id: 'subscriber_1',
          email: 'john.smith@techcorp.com',
          firstName: 'John',
          lastName: 'Smith',
          status: 'active',
          source: 'website',
          tags: ['newsletter', 'enterprise'],
          customFields: { Company: 'TechCorp Inc.', Industry: 'Technology', Role: 'CTO' },
          subscribedAt: '2024-01-10T09:00:00Z',
          lastActivityAt: '2024-01-15T10:30:00Z',
          bounceCount: 0,
          complaintCount: 0,
          location: { country: 'US', city: 'San Francisco', timezone: 'America/Los_Angeles' }
        },
        {
          id: 'subscriber_2',
          email: 'sarah.j@startup.io',
          firstName: 'Sarah',
          lastName: 'Johnson',
          status: 'active',
          source: 'referral',
          tags: ['newsletter', 'startup'],
          customFields: { Company: 'StartupIO', Industry: 'Technology', Role: 'CEO' },
          subscribedAt: '2024-01-12T14:20:00Z',
          lastActivityAt: '2024-01-14T16:45:00Z',
          bounceCount: 0,
          complaintCount: 0,
          location: { country: 'US', city: 'New York', timezone: 'America/New_York' }
        },
        {
          id: 'subscriber_3',
          email: 'mike.wilson@gmail.com',
          firstName: 'Mike',
          lastName: 'Wilson',
          status: 'active',
          source: 'social',
          tags: ['newsletter'],
          customFields: { Company: 'Freelance', Industry: 'Technology', Role: 'Developer' },
          subscribedAt: '2024-01-13T11:15:00Z',
          lastActivityAt: '2024-01-13T11:15:00Z',
          bounceCount: 0,
          complaintCount: 0,
          location: { country: 'US', city: 'Austin', timezone: 'America/Chicago' }
        }
      ]
      
      this.subscribers.value = mockSubscribers
    }
    
    if (this.campaigns.value.length === 0) {
      const mockCampaigns: EmailCampaign[] = [
        {
          id: 'campaign_1',
          name: 'Welcome to Stargate.ci',
          subject: 'Welcome to the Future of AI Development',
          previewText: 'Discover how Stargate.ci can transform your development workflow',
          content: '<h1>Welcome to Stargate.ci!</h1><p>Thank you for joining our community...</p>',
          status: 'sent',
          type: 'welcome',
          audience: {
            segments: [],
            filters: { tags: ['newsletter'] }
          },
          sentDate: '2024-01-15T10:00:00Z',
          stats: {
            sent: 150,
            delivered: 142,
            opened: 35,
            clicked: 8,
            bounced: 8,
            unsubscribed: 1,
            complained: 0,
            openRate: 24.6,
            clickRate: 5.6,
            bounceRate: 5.3,
            unsubscribeRate: 0.7,
            complaintRate: 0
          },
          createdAt: '2024-01-15T09:00:00Z',
          updatedAt: '2024-01-15T10:00:00Z',
          createdBy: 'admin'
        },
        {
          id: 'campaign_2',
          name: 'Stargate Project Update',
          subject: 'Latest Updates from the Stargate Project',
          previewText: 'New features and improvements in the Stargate ecosystem',
          content: '<h1>Stargate Project Updates</h1><p>We have exciting news to share...</p>',
          status: 'sent',
          type: 'announcement',
          audience: {
            segments: [],
            filters: { tags: ['newsletter', 'enterprise'] }
          },
          sentDate: '2024-01-20T14:00:00Z',
          stats: {
            sent: 75,
            delivered: 71,
            opened: 18,
            clicked: 4,
            bounced: 4,
            unsubscribed: 0,
            complained: 0,
            openRate: 25.4,
            clickRate: 5.6,
            bounceRate: 5.3,
            unsubscribeRate: 0,
            complaintRate: 0
          },
          createdAt: '2024-01-20T13:00:00Z',
          updatedAt: '2024-01-20T14:00:00Z',
          createdBy: 'admin'
        }
      ]
      
      this.campaigns.value = mockCampaigns
    }
    
    if (this.templates.value.length === 0) {
      const mockTemplates: EmailTemplate[] = [
        {
          id: 'template_1',
          name: 'Welcome Email Template',
          subject: 'Welcome to {{company_name}}',
          previewText: 'Thank you for joining our community',
          content: '<h1>Welcome to {{company_name}}!</h1><p>Hello {{first_name}},</p><p>Thank you for joining our community...</p>',
          type: 'welcome',
          category: 'Onboarding',
          tags: ['welcome', 'onboarding'],
          isPublic: true,
          variables: ['company_name', 'first_name', 'last_name'],
          createdAt: '2024-01-10T09:00:00Z',
          updatedAt: '2024-01-10T09:00:00Z',
          createdBy: 'admin'
        },
        {
          id: 'template_2',
          name: 'Newsletter Template',
          subject: '{{newsletter_title}} - {{date}}',
          previewText: 'Latest updates and insights from our team',
          content: '<h1>{{newsletter_title}}</h1><p>Hello {{first_name}},</p><p>Here are the latest updates...</p>',
          type: 'newsletter',
          category: 'Newsletter',
          tags: ['newsletter', 'updates'],
          isPublic: true,
          variables: ['newsletter_title', 'first_name', 'date'],
          createdAt: '2024-01-12T10:00:00Z',
          updatedAt: '2024-01-12T10:00:00Z',
          createdBy: 'admin'
        }
      ]
      
      this.templates.value = mockTemplates
    }
    
    if (this.segments.value.length === 0) {
      const mockSegments: EmailSegment[] = [
        {
          id: 'segment_1',
          name: 'Enterprise Subscribers',
          description: 'Subscribers from enterprise companies',
          criteria: {
            tags: ['enterprise'],
            customFields: { Company: { $exists: true } }
          },
          subscriberCount: 25,
          createdAt: '2024-01-10T09:00:00Z',
          updatedAt: '2024-01-10T09:00:00Z',
          createdBy: 'admin'
        },
        {
          id: 'segment_2',
          name: 'Active Newsletter Readers',
          description: 'Subscribers who regularly engage with newsletters',
          criteria: {
            tags: ['newsletter'],
            activity: { type: 'opened', daysAgo: 30 }
          },
          subscriberCount: 45,
          createdAt: '2024-01-12T10:00:00Z',
          updatedAt: '2024-01-12T10:00:00Z',
          createdBy: 'admin'
        }
      ]
      
      this.segments.value = mockSegments
    }
    
    this.saveState()
  }
}

export const emailMarketingService = new EmailMarketingService()
