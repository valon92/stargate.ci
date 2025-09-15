/**
 * Regional Content Service - Handles localized content and regional adaptations
 * Provides region-specific content, cultural adaptations, and local business information
 */

export interface RegionalContent {
  id: string
  region: string
  language: string
  contentType: 'business' | 'cultural' | 'legal' | 'technical' | 'marketing'
  title: string
  content: string
  metadata: {
    lastUpdated: string
    author: string
    version: string
    tags: string[]
  }
  adaptations: {
    currency?: string
    timezone?: string
    dateFormat?: string
    numberFormat?: string
    culturalNotes?: string[]
  }
}

export interface RegionalSettings {
  region: string
  language: string
  currency: string
  timezone: string
  dateFormat: string
  numberFormat: string
  businessHours: {
    timezone: string
    workingDays: string[]
    workingHours: {
      start: string
      end: string
    }
  }
  contactInfo: {
    phone?: string
    email?: string
    address?: string
    socialMedia?: Record<string, string>
  }
  legal: {
    privacyPolicy?: string
    termsOfService?: string
    cookiePolicy?: string
    gdprCompliant?: boolean
  }
}

export interface LocalizedBusinessInfo {
  region: string
  businessName: string
  description: string
  services: string[]
  industries: string[]
  certifications: string[]
  partnerships: string[]
  caseStudies: string[]
  testimonials: string[]
  contactMethods: string[]
}

class RegionalContentService {
  private storageKey = 'stargate_regional_content'
  private currentRegion = 'global'
  private currentLanguage = 'en'

  constructor() {
    this.initializeData()
    this.detectUserRegion()
  }

  private initializeData() {
    const existingData = localStorage.getItem(this.storageKey)
    if (!existingData) {
      const initialData = {
        content: this.getDefaultRegionalContent(),
        settings: this.getDefaultRegionalSettings(),
        businessInfo: this.getDefaultBusinessInfo()
      }
      localStorage.setItem(this.storageKey, JSON.stringify(initialData))
    }
  }

  private detectUserRegion() {
    // Detect user's region based on browser settings
    const language = navigator.language || 'en'
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone
    
    // Map language to region
    const regionMap: Record<string, string> = {
      'en': 'us',
      'en-US': 'us',
      'en-GB': 'uk',
      'en-CA': 'ca',
      'en-AU': 'au',
      'fr': 'fr',
      'fr-FR': 'fr',
      'fr-CA': 'ca',
      'de': 'de',
      'de-DE': 'de',
      'es': 'es',
      'es-ES': 'es',
      'es-MX': 'mx',
      'it': 'it',
      'it-IT': 'it',
      'ar': 'ae',
      'pt': 'br',
      'pt-BR': 'br',
      'pt-PT': 'pt',
      'ru': 'ru',
      'ru-RU': 'ru',
      'ja': 'jp',
      'ja-JP': 'jp',
      'zh': 'cn',
      'zh-CN': 'cn',
      'zh-TW': 'tw'
    }

    this.currentLanguage = language.split('-')[0]
    this.currentRegion = regionMap[language] || regionMap[this.currentLanguage] || 'us'
  }

  private getDefaultRegionalContent(): RegionalContent[] {
    return [
      {
        id: 'us-business',
        region: 'us',
        language: 'en',
        contentType: 'business',
        title: 'Stargate.ci - US Operations',
        content: 'Stargate.ci provides comprehensive AI and cloud solutions for US businesses, helping them leverage cutting-edge technology while maintaining compliance with US regulations.',
        metadata: {
          lastUpdated: new Date().toISOString(),
          author: 'Stargate Team',
          version: '1.0',
          tags: ['business', 'us', 'ai', 'cloud']
        },
        adaptations: {
          currency: 'USD',
          timezone: 'America/New_York',
          dateFormat: 'MM/DD/YYYY',
          numberFormat: 'en-US',
          culturalNotes: ['Direct communication style', 'Focus on ROI and efficiency']
        }
      },
      {
        id: 'eu-business',
        region: 'eu',
        language: 'en',
        contentType: 'business',
        title: 'Stargate.ci - European Operations',
        content: 'Stargate.ci offers GDPR-compliant AI solutions for European businesses, ensuring data privacy and regulatory compliance across all EU member states.',
        metadata: {
          lastUpdated: new Date().toISOString(),
          author: 'Stargate Team',
          version: '1.0',
          tags: ['business', 'eu', 'gdpr', 'privacy']
        },
        adaptations: {
          currency: 'EUR',
          timezone: 'Europe/Brussels',
          dateFormat: 'DD/MM/YYYY',
          numberFormat: 'en-GB',
          culturalNotes: ['GDPR compliance required', 'Multi-language support', 'Cultural diversity considerations']
        }
      },
      {
        id: 'asia-business',
        region: 'asia',
        language: 'en',
        contentType: 'business',
        title: 'Stargate.ci - Asia Pacific Operations',
        content: 'Stargate.ci delivers scalable AI solutions for Asia Pacific markets, supporting rapid digital transformation across diverse cultural and regulatory environments.',
        metadata: {
          lastUpdated: new Date().toISOString(),
          author: 'Stargate Team',
          version: '1.0',
          tags: ['business', 'asia', 'scalability', 'digital-transformation']
        },
        adaptations: {
          currency: 'USD',
          timezone: 'Asia/Singapore',
          dateFormat: 'DD/MM/YYYY',
          numberFormat: 'en-US',
          culturalNotes: ['Respect for hierarchy', 'Long-term relationship focus', 'Local partnership importance']
        }
      }
    ]
  }

  private getDefaultRegionalSettings(): RegionalSettings[] {
    return [
      {
        region: 'us',
        language: 'en',
        currency: 'USD',
        timezone: 'America/New_York',
        dateFormat: 'MM/DD/YYYY',
        numberFormat: 'en-US',
        businessHours: {
          timezone: 'America/New_York',
          workingDays: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
          workingHours: {
            start: '09:00',
            end: '17:00'
          }
        },
        contactInfo: {
          phone: '+1 (555) 123-4567',
          email: 'us@stargate.ci',
          address: '123 Tech Street, San Francisco, CA 94105, USA'
        },
        legal: {
          gdprCompliant: false
        }
      },
      {
        region: 'eu',
        language: 'en',
        currency: 'EUR',
        timezone: 'Europe/Brussels',
        dateFormat: 'DD/MM/YYYY',
        numberFormat: 'en-GB',
        businessHours: {
          timezone: 'Europe/Brussels',
          workingDays: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
          workingHours: {
            start: '09:00',
            end: '17:00'
          }
        },
        contactInfo: {
          phone: '+32 2 123 4567',
          email: 'eu@stargate.ci',
          address: 'Avenue Louise 123, 1050 Brussels, Belgium'
        },
        legal: {
          gdprCompliant: true
        }
      },
      {
        region: 'asia',
        language: 'en',
        currency: 'USD',
        timezone: 'Asia/Singapore',
        dateFormat: 'DD/MM/YYYY',
        numberFormat: 'en-US',
        businessHours: {
          timezone: 'Asia/Singapore',
          workingDays: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
          workingHours: {
            start: '09:00',
            end: '18:00'
          }
        },
        contactInfo: {
          phone: '+65 6123 4567',
          email: 'asia@stargate.ci',
          address: '1 Marina Bay Sands, Singapore 018956'
        },
        legal: {
          gdprCompliant: false
        }
      }
    ]
  }

  private getDefaultBusinessInfo(): LocalizedBusinessInfo[] {
    return [
      {
        region: 'us',
        businessName: 'Stargate.ci - United States',
        description: 'Leading AI and cloud solutions provider for US enterprises',
        services: [
          'AI Strategy Consulting',
          'Cloud Migration Services',
          'Data Analytics Solutions',
          'Machine Learning Implementation',
          'Cybersecurity Services'
        ],
        industries: [
          'Financial Services',
          'Healthcare',
          'Manufacturing',
          'Retail',
          'Technology'
        ],
        certifications: [
          'SOC 2 Type II',
          'ISO 27001',
          'HIPAA Compliant',
          'FedRAMP Authorized'
        ],
        partnerships: [
          'AWS Advanced Partner',
          'Microsoft Gold Partner',
          'Google Cloud Premier Partner'
        ],
        caseStudies: [
          'Fortune 500 Financial Services Transformation',
          'Healthcare Data Analytics Implementation',
          'Manufacturing IoT Integration'
        ],
        testimonials: [
          'Stargate.ci helped us achieve 40% cost reduction through AI optimization',
          'Their cloud migration strategy saved us 6 months of development time',
          'Outstanding support and expertise in AI implementation'
        ],
        contactMethods: [
          'Phone: +1 (555) 123-4567',
          'Email: us@stargate.ci',
          'LinkedIn: /company/stargate-ci-us',
          'Schedule a consultation'
        ]
      },
      {
        region: 'eu',
        businessName: 'Stargate.ci - Europe',
        description: 'GDPR-compliant AI solutions for European businesses',
        services: [
          'GDPR-Compliant AI Solutions',
          'Multi-Language Support',
          'European Cloud Infrastructure',
          'Data Privacy Consulting',
          'Cross-Border Data Solutions'
        ],
        industries: [
          'Banking & Finance',
          'Healthcare & Life Sciences',
          'Automotive',
          'Energy & Utilities',
          'Government'
        ],
        certifications: [
          'ISO 27001',
          'ISO 9001',
          'GDPR Compliant',
          'BSI Certified'
        ],
        partnerships: [
          'AWS EU Partner',
          'Microsoft EU Partner',
          'SAP Partner',
          'Oracle Partner'
        ],
        caseStudies: [
          'European Bank Digital Transformation',
          'Healthcare Data Privacy Implementation',
          'Automotive Industry AI Integration'
        ],
        testimonials: [
          'Excellent GDPR compliance and data protection measures',
          'Seamless multi-language implementation across 15 countries',
          'Outstanding local support and understanding of EU regulations'
        ],
        contactMethods: [
          'Phone: +32 2 123 4567',
          'Email: eu@stargate.ci',
          'LinkedIn: /company/stargate-ci-europe',
          'Schedule a consultation'
        ]
      }
    ]
  }

  private getData() {
    const data = localStorage.getItem(this.storageKey)
    return data ? JSON.parse(data) : {
      content: this.getDefaultRegionalContent(),
      settings: this.getDefaultRegionalSettings(),
      businessInfo: this.getDefaultBusinessInfo()
    }
  }

  private saveData(data: any) {
    localStorage.setItem(this.storageKey, JSON.stringify(data))
  }

  // Content Management
  async getRegionalContent(region?: string, contentType?: string): Promise<RegionalContent[]> {
    const data = this.getData()
    let content = data.content

    if (region) {
      content = content.filter((item: RegionalContent) => item.region === region)
    }

    if (contentType) {
      content = content.filter((item: RegionalContent) => item.contentType === contentType)
    }

    return content
  }

  async getContentForCurrentRegion(contentType?: string): Promise<RegionalContent[]> {
    return this.getRegionalContent(this.currentRegion, contentType)
  }

  async addRegionalContent(content: Omit<RegionalContent, 'id'>): Promise<RegionalContent> {
    const data = this.getData()
    const newContent: RegionalContent = {
      ...content,
      id: `content_${Date.now()}`
    }

    data.content.push(newContent)
    this.saveData(data)

    return newContent
  }

  // Regional Settings
  async getRegionalSettings(region?: string): Promise<RegionalSettings | null> {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    
    return data.settings.find((setting: RegionalSettings) => setting.region === targetRegion) || null
  }

  async getCurrentRegionSettings(): Promise<RegionalSettings | null> {
    return this.getRegionalSettings(this.currentRegion)
  }

  // Business Information
  async getBusinessInfo(region?: string): Promise<LocalizedBusinessInfo | null> {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    
    return data.businessInfo.find((info: LocalizedBusinessInfo) => info.region === targetRegion) || null
  }

  async getCurrentBusinessInfo(): Promise<LocalizedBusinessInfo | null> {
    return this.getBusinessInfo(this.currentRegion)
  }

  // Utility Methods
  getCurrentRegion(): string {
    return this.currentRegion
  }

  getCurrentLanguage(): string {
    return this.currentLanguage
  }

  setRegion(region: string): void {
    this.currentRegion = region
    localStorage.setItem('stargate_current_region', region)
  }

  setLanguage(language: string): void {
    this.currentLanguage = language
    localStorage.setItem('stargate_current_language', language)
  }

  // Formatting Helpers
  formatCurrency(amount: number, region?: string): string {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    const settings = data.settings.find((s: RegionalSettings) => s.region === targetRegion)
    if (!settings) return amount.toString()

    return new Intl.NumberFormat(settings.numberFormat, {
      style: 'currency',
      currency: settings.currency
    }).format(amount)
  }

  formatDate(date: Date, region?: string): string {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    const settings = data.settings.find((s: RegionalSettings) => s.region === targetRegion)
    if (!settings) return date.toLocaleDateString()

    return new Intl.DateTimeFormat(settings.numberFormat, {
      timeZone: settings.timezone
    }).format(date)
  }

  formatNumber(number: number, region?: string): string {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    const settings = data.settings.find((s: RegionalSettings) => s.region === targetRegion)
    if (!settings) return number.toString()

    return new Intl.NumberFormat(settings.numberFormat).format(number)
  }

  // Cultural Adaptations
  getCulturalNotes(region?: string): string[] {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    
    const culturalContent = data.content.find((item: RegionalContent) => 
      item.region === targetRegion && item.contentType === 'cultural'
    )
    return culturalContent?.adaptations.culturalNotes || []
  }

  // Business Hours
  getBusinessHours(region?: string): { timezone: string; workingDays: string[]; workingHours: { start: string; end: string } } | null {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    const settings = data.settings.find((s: RegionalSettings) => s.region === targetRegion)
    return settings?.businessHours || null
  }

  isBusinessHours(region?: string): boolean {
    const businessHours = this.getBusinessHours(region)
    if (!businessHours) return false

    const now = new Date()
    const currentDay = now.toLocaleDateString('en-US', { weekday: 'long' })
    const currentTime = now.toLocaleTimeString('en-US', { 
      hour12: false, 
      timeZone: businessHours.timezone 
    }).slice(0, 5)

    return businessHours.workingDays.includes(currentDay) &&
           currentTime >= businessHours.workingHours.start &&
           currentTime <= businessHours.workingHours.end
  }

  // Contact Information
  getContactInfo(region?: string): { phone?: string; email?: string; address?: string; socialMedia?: Record<string, string> } | null {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    const settings = data.settings.find((s: RegionalSettings) => s.region === targetRegion)
    return settings?.contactInfo || null
  }

  // Legal Compliance
  isGDPRCompliant(region?: string): boolean {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    const settings = data.settings.find((s: RegionalSettings) => s.region === targetRegion)
    return settings?.legal.gdprCompliant || false
  }

  getLegalDocuments(region?: string): { privacyPolicy?: string; termsOfService?: string; cookiePolicy?: string } | null {
    const data = this.getData()
    const targetRegion = region || this.currentRegion
    const settings = data.settings.find((s: RegionalSettings) => s.region === targetRegion)
    return settings?.legal || null
  }
}

export const regionalContentService = new RegionalContentService()
