// CRM Integration Service for Stargate.ci
import { ref, computed } from 'vue'

export interface Contact {
  id: string
  firstName: string
  lastName: string
  email: string
  phone?: string
  company?: string
  position?: string
  source: 'website' | 'referral' | 'social' | 'email' | 'phone' | 'event' | 'other'
  status: 'lead' | 'prospect' | 'customer' | 'inactive'
  leadScore: number
  tags: string[]
  notes: string
  lastContactDate?: string
  nextFollowUpDate?: string
  createdAt: string
  updatedAt: string
  customFields?: Record<string, any>
}

export interface Lead {
  id: string
  contactId: string
  title: string
  description: string
  value: number
  currency: string
  stage: 'new' | 'qualified' | 'proposal' | 'negotiation' | 'closed-won' | 'closed-lost'
  probability: number
  expectedCloseDate?: string
  actualCloseDate?: string
  assignedTo?: string
  source: string
  createdAt: string
  updatedAt: string
}

export interface Activity {
  id: string
  contactId: string
  type: 'call' | 'email' | 'meeting' | 'note' | 'task' | 'demo' | 'proposal'
  subject: string
  description: string
  date: string
  duration?: number // in minutes
  outcome?: string
  nextAction?: string
  assignedTo?: string
  createdAt: string
}

export interface CRMStats {
  totalContacts: number
  totalLeads: number
  totalDeals: number
  totalValue: number
  conversionRate: number
  averageDealSize: number
  pipelineValue: number
  thisMonthDeals: number
  thisMonthValue: number
  topSources: Array<{ source: string; count: number; value: number }>
  stageDistribution: Array<{ stage: string; count: number; value: number }>
}

export interface CRMSettings {
  leadScoringEnabled: boolean
  autoFollowUpEnabled: boolean
  emailIntegrationEnabled: boolean
  calendarIntegrationEnabled: boolean
  customFields: Array<{ name: string; type: string; required: boolean }>
  stages: string[]
  sources: string[]
  tags: string[]
}

class CRMService {
  private contacts = ref<Contact[]>([])
  private leads = ref<Lead[]>([])
  private activities = ref<Activity[]>([])
  private settings = ref<CRMSettings>({
    leadScoringEnabled: true,
    autoFollowUpEnabled: true,
    emailIntegrationEnabled: true,
    calendarIntegrationEnabled: false,
    customFields: [
      { name: 'Industry', type: 'text', required: false },
      { name: 'Company Size', type: 'select', required: false },
      { name: 'Budget', type: 'number', required: false }
    ],
    stages: ['new', 'qualified', 'proposal', 'negotiation', 'closed-won', 'closed-lost'],
    sources: ['website', 'referral', 'social', 'email', 'phone', 'event', 'other'],
    tags: ['hot', 'warm', 'cold', 'vip', 'enterprise', 'startup']
  })

  constructor() {
    this.loadMockData()
  }

  // Contact Management
  async getContacts(): Promise<Contact[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve([...this.contacts.value])
      }, 500)
    })
  }

  async getContact(id: string): Promise<Contact | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const contact = this.contacts.value.find(c => c.id === id)
        resolve(contact || null)
      }, 300)
    })
  }

  async addContact(contactData: Omit<Contact, 'id' | 'createdAt' | 'updatedAt' | 'leadScore'>): Promise<Contact> {
    return new Promise(resolve => {
      setTimeout(() => {
        const contact: Contact = {
          ...contactData,
          id: `contact_${Date.now()}`,
          leadScore: this.calculateLeadScore(contactData),
          createdAt: new Date().toISOString(),
          updatedAt: new Date().toISOString()
        }
        
        this.contacts.value.unshift(contact)
        this.saveState()
        resolve(contact)
      }, 800)
    })
  }

  async updateContact(id: string, updates: Partial<Contact>): Promise<Contact | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const index = this.contacts.value.findIndex(c => c.id === id)
        if (index !== -1) {
          this.contacts.value[index] = {
            ...this.contacts.value[index],
            ...updates,
            updatedAt: new Date().toISOString()
          }
          this.saveState()
          resolve(this.contacts.value[index])
        }
        resolve(null)
      }, 600)
    })
  }

  async deleteContact(id: string): Promise<boolean> {
    return new Promise(resolve => {
      setTimeout(() => {
        const index = this.contacts.value.findIndex(c => c.id === id)
        if (index !== -1) {
          this.contacts.value.splice(index, 1)
          this.saveState()
          resolve(true)
        }
        resolve(false)
      }, 400)
    })
  }

  // Lead Management
  async getLeads(): Promise<Lead[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve([...this.leads.value])
      }, 500)
    })
  }

  async getLead(id: string): Promise<Lead | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const lead = this.leads.value.find(l => l.id === id)
        resolve(lead || null)
      }, 300)
    })
  }

  async addLead(leadData: Omit<Lead, 'id' | 'createdAt' | 'updatedAt'>): Promise<Lead> {
    return new Promise(resolve => {
      setTimeout(() => {
        const lead: Lead = {
          ...leadData,
          id: `lead_${Date.now()}`,
          createdAt: new Date().toISOString(),
          updatedAt: new Date().toISOString()
        }
        
        this.leads.value.unshift(lead)
        this.saveState()
        resolve(lead)
      }, 800)
    })
  }

  async updateLead(id: string, updates: Partial<Lead>): Promise<Lead | null> {
    return new Promise(resolve => {
      setTimeout(() => {
        const index = this.leads.value.findIndex(l => l.id === id)
        if (index !== -1) {
          this.leads.value[index] = {
            ...this.leads.value[index],
            ...updates,
            updatedAt: new Date().toISOString()
          }
          this.saveState()
          resolve(this.leads.value[index])
        }
        resolve(null)
      }, 600)
    })
  }

  // Activity Management
  async getActivities(contactId?: string): Promise<Activity[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        let activities = [...this.activities.value]
        if (contactId) {
          activities = activities.filter(a => a.contactId === contactId)
        }
        resolve(activities)
      }, 400)
    })
  }

  async addActivity(activityData: Omit<Activity, 'id' | 'createdAt'>): Promise<Activity> {
    return new Promise(resolve => {
      setTimeout(() => {
        const activity: Activity = {
          ...activityData,
          id: `activity_${Date.now()}`,
          createdAt: new Date().toISOString()
        }
        
        this.activities.value.unshift(activity)
        this.saveState()
        resolve(activity)
      }, 600)
    })
  }

  // Analytics and Stats
  async getStats(): Promise<CRMStats> {
    return new Promise(resolve => {
      setTimeout(() => {
        const totalContacts = this.contacts.value.length
        const totalLeads = this.leads.value.length
        const totalDeals = this.leads.value.filter(l => l.stage === 'closed-won').length
        const totalValue = this.leads.value
          .filter(l => l.stage === 'closed-won')
          .reduce((sum, l) => sum + l.value, 0)
        
        const conversionRate = totalLeads > 0 ? (totalDeals / totalLeads) * 100 : 0
        const averageDealSize = totalDeals > 0 ? totalValue / totalDeals : 0
        
        const pipelineValue = this.leads.value
          .filter(l => !['closed-won', 'closed-lost'].includes(l.stage))
          .reduce((sum, l) => sum + (l.value * l.probability / 100), 0)
        
        const thisMonth = new Date()
        thisMonth.setDate(1)
        const thisMonthDeals = this.leads.value.filter(l => 
          l.stage === 'closed-won' && 
          new Date(l.actualCloseDate || l.updatedAt) >= thisMonth
        ).length
        
        const thisMonthValue = this.leads.value
          .filter(l => l.stage === 'closed-won' && 
            new Date(l.actualCloseDate || l.updatedAt) >= thisMonth)
          .reduce((sum, l) => sum + l.value, 0)
        
        // Top sources
        const sourceStats = this.contacts.value.reduce((acc, contact) => {
          if (!acc[contact.source]) {
            acc[contact.source] = { count: 0, value: 0 }
          }
          acc[contact.source].count++
          const contactLeads = this.leads.value.filter(l => l.contactId === contact.id)
          acc[contact.source].value += contactLeads.reduce((sum, l) => sum + l.value, 0)
          return acc
        }, {} as Record<string, { count: number; value: number }>)
        
        const topSources = Object.entries(sourceStats)
          .map(([source, stats]) => ({ source, ...stats }))
          .sort((a, b) => b.value - a.value)
          .slice(0, 5)
        
        // Stage distribution
        const stageStats = this.leads.value.reduce((acc, lead) => {
          if (!acc[lead.stage]) {
            acc[lead.stage] = { count: 0, value: 0 }
          }
          acc[lead.stage].count++
          acc[lead.stage].value += lead.value
          return acc
        }, {} as Record<string, { count: number; value: number }>)
        
        const stageDistribution = Object.entries(stageStats)
          .map(([stage, stats]) => ({ stage, ...stats }))
          .sort((a, b) => b.value - a.value)
        
        const stats: CRMStats = {
          totalContacts,
          totalLeads,
          totalDeals,
          totalValue,
          conversionRate,
          averageDealSize,
          pipelineValue,
          thisMonthDeals,
          thisMonthValue,
          topSources,
          stageDistribution
        }
        
        resolve(stats)
      }, 1000)
    })
  }

  // Settings Management
  async getSettings(): Promise<CRMSettings> {
    return new Promise(resolve => {
      setTimeout(() => {
        resolve({ ...this.settings.value })
      }, 300)
    })
  }

  async updateSettings(updates: Partial<CRMSettings>): Promise<CRMSettings> {
    return new Promise(resolve => {
      setTimeout(() => {
        this.settings.value = { ...this.settings.value, ...updates }
        this.saveState()
        resolve({ ...this.settings.value })
      }, 500)
    })
  }

  // Search and Filter
  async searchContacts(query: string): Promise<Contact[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        const results = this.contacts.value.filter(contact =>
          contact.firstName.toLowerCase().includes(query.toLowerCase()) ||
          contact.lastName.toLowerCase().includes(query.toLowerCase()) ||
          contact.email.toLowerCase().includes(query.toLowerCase()) ||
          contact.company?.toLowerCase().includes(query.toLowerCase()) ||
          contact.tags.some(tag => tag.toLowerCase().includes(query.toLowerCase()))
        )
        resolve(results)
      }, 400)
    })
  }

  async filterContacts(filters: {
    status?: string
    source?: string
    tags?: string[]
    dateRange?: { start: string; end: string }
  }): Promise<Contact[]> {
    return new Promise(resolve => {
      setTimeout(() => {
        let results = [...this.contacts.value]
        
        if (filters.status) {
          results = results.filter(c => c.status === filters.status)
        }
        
        if (filters.source) {
          results = results.filter(c => c.source === filters.source)
        }
        
        if (filters.tags && filters.tags.length > 0) {
          results = results.filter(c => 
            filters.tags!.some(tag => c.tags.includes(tag))
          )
        }
        
        if (filters.dateRange) {
          const startDate = new Date(filters.dateRange.start)
          const endDate = new Date(filters.dateRange.end)
          results = results.filter(c => {
            const contactDate = new Date(c.createdAt)
            return contactDate >= startDate && contactDate <= endDate
          })
        }
        
        resolve(results)
      }, 500)
    })
  }

  // Lead Scoring
  private calculateLeadScore(contactData: Partial<Contact>): number {
    let score = 0
    
    // Email domain scoring
    if (contactData.email) {
      const domain = contactData.email.split('@')[1]
      if (['gmail.com', 'yahoo.com', 'hotmail.com'].includes(domain)) {
        score += 10
      } else {
        score += 30 // Business email
      }
    }
    
    // Company scoring
    if (contactData.company) {
      score += 20
    }
    
    // Position scoring
    if (contactData.position) {
      const seniorPositions = ['ceo', 'cto', 'director', 'manager', 'vp', 'head']
      if (seniorPositions.some(pos => contactData.position!.toLowerCase().includes(pos))) {
        score += 25
      } else {
        score += 15
      }
    }
    
    // Source scoring
    if (contactData.source) {
      const sourceScores: Record<string, number> = {
        'referral': 40,
        'website': 30,
        'social': 25,
        'email': 20,
        'phone': 15,
        'event': 35,
        'other': 10
      }
      score += sourceScores[contactData.source] || 10
    }
    
    return Math.min(score, 100) // Cap at 100
  }

  // Data Persistence
  private saveState(): void {
    try {
      localStorage.setItem('crm-contacts', JSON.stringify(this.contacts.value))
      localStorage.setItem('crm-leads', JSON.stringify(this.leads.value))
      localStorage.setItem('crm-activities', JSON.stringify(this.activities.value))
      localStorage.setItem('crm-settings', JSON.stringify(this.settings.value))
    } catch (error) {
      console.error('Failed to save CRM state:', error)
    }
  }

  private loadState(): void {
    try {
      const contacts = localStorage.getItem('crm-contacts')
      if (contacts) {
        this.contacts.value = JSON.parse(contacts)
      }
      
      const leads = localStorage.getItem('crm-leads')
      if (leads) {
        this.leads.value = JSON.parse(leads)
      }
      
      const activities = localStorage.getItem('crm-activities')
      if (activities) {
        this.activities.value = JSON.parse(activities)
      }
      
      const settings = localStorage.getItem('crm-settings')
      if (settings) {
        this.settings.value = JSON.parse(settings)
      }
    } catch (error) {
      console.error('Failed to load CRM state:', error)
    }
  }

  private loadMockData(): void {
    // Load existing data first
    this.loadState()
    
    // Add mock data if none exists
    if (this.contacts.value.length === 0) {
      const mockContacts: Contact[] = [
        {
          id: 'contact_1',
          firstName: 'John',
          lastName: 'Smith',
          email: 'john.smith@techcorp.com',
          phone: '+1-555-0123',
          company: 'TechCorp Inc.',
          position: 'CTO',
          source: 'website',
          status: 'prospect',
          leadScore: 85,
          tags: ['enterprise', 'hot'],
          notes: 'Interested in AI solutions for their development team.',
          lastContactDate: '2024-01-15T10:30:00Z',
          nextFollowUpDate: '2024-01-22T14:00:00Z',
          createdAt: '2024-01-10T09:00:00Z',
          updatedAt: '2024-01-15T10:30:00Z'
        },
        {
          id: 'contact_2',
          firstName: 'Sarah',
          lastName: 'Johnson',
          email: 'sarah.j@startup.io',
          phone: '+1-555-0456',
          company: 'StartupIO',
          position: 'CEO',
          source: 'referral',
          status: 'lead',
          leadScore: 90,
          tags: ['startup', 'vip'],
          notes: 'Referred by existing customer. Very interested in Stargate project.',
          lastContactDate: '2024-01-14T16:45:00Z',
          nextFollowUpDate: '2024-01-21T11:00:00Z',
          createdAt: '2024-01-12T14:20:00Z',
          updatedAt: '2024-01-14T16:45:00Z'
        },
        {
          id: 'contact_3',
          firstName: 'Mike',
          lastName: 'Wilson',
          email: 'mike.wilson@gmail.com',
          phone: '+1-555-0789',
          company: 'Freelance',
          position: 'Developer',
          source: 'social',
          status: 'lead',
          leadScore: 45,
          tags: ['warm'],
          notes: 'Individual developer interested in learning about AI tools.',
          createdAt: '2024-01-13T11:15:00Z',
          updatedAt: '2024-01-13T11:15:00Z'
        }
      ]
      
      this.contacts.value = mockContacts
    }
    
    if (this.leads.value.length === 0) {
      const mockLeads: Lead[] = [
        {
          id: 'lead_1',
          contactId: 'contact_1',
          title: 'AI Development Platform License',
          description: 'Enterprise license for AI development tools and Stargate integration',
          value: 50000,
          currency: 'USD',
          stage: 'proposal',
          probability: 75,
          expectedCloseDate: '2024-02-15T00:00:00Z',
          source: 'website',
          assignedTo: 'admin',
          createdAt: '2024-01-10T09:00:00Z',
          updatedAt: '2024-01-15T10:30:00Z'
        },
        {
          id: 'lead_2',
          contactId: 'contact_2',
          title: 'Stargate Project Consultation',
          description: 'Full consultation and implementation of Stargate project',
          value: 100000,
          currency: 'USD',
          stage: 'negotiation',
          probability: 60,
          expectedCloseDate: '2024-02-28T00:00:00Z',
          source: 'referral',
          assignedTo: 'admin',
          createdAt: '2024-01-12T14:20:00Z',
          updatedAt: '2024-01-14T16:45:00Z'
        },
        {
          id: 'lead_3',
          contactId: 'contact_3',
          title: 'Individual Developer Package',
          description: 'Basic package for individual developers',
          value: 500,
          currency: 'USD',
          stage: 'qualified',
          probability: 40,
          expectedCloseDate: '2024-01-30T00:00:00Z',
          source: 'social',
          assignedTo: 'admin',
          createdAt: '2024-01-13T11:15:00Z',
          updatedAt: '2024-01-13T11:15:00Z'
        }
      ]
      
      this.leads.value = mockLeads
    }
    
    if (this.activities.value.length === 0) {
      const mockActivities: Activity[] = [
        {
          id: 'activity_1',
          contactId: 'contact_1',
          type: 'call',
          subject: 'Initial Discovery Call',
          description: 'Discussed their current AI infrastructure and needs',
          date: '2024-01-15T10:30:00Z',
          duration: 45,
          outcome: 'Very interested, wants to see demo',
          nextAction: 'Schedule demo for next week',
          assignedTo: 'admin',
          createdAt: '2024-01-15T10:30:00Z'
        },
        {
          id: 'activity_2',
          contactId: 'contact_2',
          type: 'email',
          subject: 'Follow-up on Referral',
          description: 'Sent detailed information about Stargate project',
          date: '2024-01-14T16:45:00Z',
          outcome: 'Positive response, wants to discuss further',
          nextAction: 'Schedule call for next week',
          assignedTo: 'admin',
          createdAt: '2024-01-14T16:45:00Z'
        },
        {
          id: 'activity_3',
          contactId: 'contact_3',
          type: 'meeting',
          subject: 'Social Media Inquiry',
          description: 'Responded to LinkedIn message about AI tools',
          date: '2024-01-13T11:15:00Z',
          outcome: 'Interested in learning more',
          nextAction: 'Send educational materials',
          assignedTo: 'admin',
          createdAt: '2024-01-13T11:15:00Z'
        }
      ]
      
      this.activities.value = mockActivities
    }
    
    this.saveState()
  }
}

export const crmService = new CRMService()
