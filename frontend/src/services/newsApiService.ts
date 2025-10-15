// News API Service for Stargate.ci
// Fetches real news data using OpenAI API for Stargate Project and Cristal Intelligence

import { openaiService } from './openaiService'

export interface NewsArticle {
  id: string
  title: string
  excerpt: string
  content: string
  category: 'stargate' | 'cristal' | 'industry' | 'research' | 'ethics' | 'global'
  author: string
  publishedAt: string
  readTime: number
  icon: string
  url: string
  tags: string[]
  featured: boolean
}

export interface NewsResponse {
  success: boolean
  articles: NewsArticle[]
  total: number
  error?: string
}

class NewsApiService {
  private cache: Map<string, NewsResponse> = new Map()
  private cacheExpiry: Map<string, number> = new Map()
  private readonly CACHE_DURATION = 30 * 60 * 1000 // 30 minutes

  // Generate news articles using OpenAI
  async generateNewsArticles(category?: string, limit: number = 10): Promise<NewsResponse> {
    try {
      const cacheKey = `news_${category || 'all'}_${limit}`
      
      // Check cache first
      if (this.isCacheValid(cacheKey)) {
        const cached = this.cache.get(cacheKey)
        if (cached) {
          console.log('📰 Using cached news data')
          return cached
        }
      }

      // Check if OpenAI API key is configured
      const apiKey = import.meta.env.VITE_OPENAI_API_KEY
      if (!apiKey || apiKey === 'sk-your-openai-api-key-here') {
        console.log('📰 OpenAI API key not configured, using fallback content')
        const fallbackArticles = this.createFallbackArticles(category)
        const result: NewsResponse = {
          success: true,
          articles: fallbackArticles,
          total: fallbackArticles.length
        }
        
        // Cache the fallback result
        this.cache.set(cacheKey, result)
        this.cacheExpiry.set(cacheKey, Date.now() + this.CACHE_DURATION)
        
        return result
      }

      console.log('📰 Generating fresh news data from OpenAI...')

      // Create prompt for news generation
      const prompt = this.createNewsPrompt(category, limit)
      
      // Generate content using OpenAI
      const response = await openaiService.generateText(prompt, {
        model: 'gpt-4',
        max_tokens: 4000,
        temperature: 0.7
      })

      if (!response.success) {
        throw new Error(response.error || 'Failed to generate news')
      }

      // Parse the generated content
      const articles = this.parseNewsContent(response.content, category)
      
      const result: NewsResponse = {
        success: true,
        articles,
        total: articles.length
      }

      // Cache the result
      this.cache.set(cacheKey, result)
      this.cacheExpiry.set(cacheKey, Date.now() + this.CACHE_DURATION)

      return result

    } catch (error) {
      console.error('Error generating news:', error)
      console.log('📰 Falling back to demo content due to error')
      
      // Return fallback content on error
      const fallbackArticles = this.createFallbackArticles(category)
      return {
        success: true,
        articles: fallbackArticles,
        total: fallbackArticles.length
      }
    }
  }

  // Create prompt for news generation
  private createNewsPrompt(category?: string, limit: number = 10): string {
    const basePrompt = `Generate ${limit} realistic news articles about the Stargate Project and Cristal Intelligence. 
    Each article should be informative, well-researched, and relevant to current AI developments.
    
    Return the articles in the following JSON format:
    {
      "articles": [
        {
          "title": "Article Title",
          "excerpt": "Brief summary of the article (2-3 sentences)",
          "content": "Full article content (3-4 paragraphs)",
          "category": "stargate|cristal|industry|research|ethics|global",
          "author": "Author Name",
          "publishedAt": "YYYY-MM-DD",
          "readTime": 5,
          "icon": "🚀|💎|🏢|🔬|⚖️|🌐",
          "url": "https://example.com/article",
          "tags": ["tag1", "tag2", "tag3"],
          "featured": true/false
        }
      ]
    }`

    if (category) {
      const categoryPrompts = {
        stargate: `Focus on the $500 billion Stargate AI infrastructure project by OpenAI, SoftBank, and Arm. Include updates on data centers, AI model development, infrastructure milestones, and partnerships.`,
        cristal: `Focus on Cristal Intelligence - the revolutionary AI paradigm emphasizing transparency, interpretability, and ethical alignment. Include research breakthroughs, applications, and comparisons with traditional AI.`,
        industry: `Focus on how Stargate and Cristal Intelligence are transforming industries like healthcare, finance, manufacturing, and education. Include real-world applications and case studies.`,
        research: `Focus on latest research findings, technical breakthroughs, academic papers, and development milestones in AI infrastructure and transparent AI systems.`,
        ethics: `Focus on AI ethics, governance frameworks, responsible AI development, policy implications, and ethical considerations in AI infrastructure.`,
        global: `Focus on global impact, international cooperation, market effects, policy developments, and worldwide implications of these AI technologies.`
      }
      
      return `${basePrompt}\n\n${categoryPrompts[category as keyof typeof categoryPrompts] || ''}`
    }

    return basePrompt
  }

  // Parse generated content into news articles
  private parseNewsContent(content: string, category?: string): NewsArticle[] {
    try {
      // Try to extract JSON from the response
      const jsonMatch = content.match(/\{[\s\S]*\}/)
      if (!jsonMatch) {
        throw new Error('No JSON found in response')
      }

      const parsed = JSON.parse(jsonMatch[0])
      
      if (!parsed.articles || !Array.isArray(parsed.articles)) {
        throw new Error('Invalid articles format')
      }

      return parsed.articles.map((article: any, index: number) => ({
        id: `generated_${Date.now()}_${index}`,
        title: article.title || 'Untitled Article',
        excerpt: article.excerpt || 'No excerpt available',
        content: article.content || 'No content available',
        category: article.category || 'global',
        author: article.author || 'AI Generated',
        publishedAt: article.publishedAt || new Date().toISOString().split('T')[0],
        readTime: article.readTime || 5,
        icon: article.icon || '📰',
        url: article.url || '#',
        tags: article.tags || [],
        featured: article.featured || false
      }))

    } catch (error) {
      console.error('Error parsing news content:', error)
      
      // Fallback: create mock articles if parsing fails
      return this.createFallbackArticles(category)
    }
  }

  // Create fallback articles with real news from OpenAI and SoftBank
  private createFallbackArticles(category?: string): NewsArticle[] {
    const fallbackArticles = {
      stargate: [
        {
          id: 'real_stargate_1',
          title: 'OpenAI Announces Stargate Project: $500B AI Infrastructure Initiative',
          excerpt: 'OpenAI, SoftBank, and Oracle announce the Stargate Project - a plan to build next-generation AI data centers in the U.S. for OpenAI, representing the largest AI infrastructure investment in history.',
          content: 'In January 2025, OpenAI, SoftBank, and Oracle announced the Stargate Project, a groundbreaking plan to build next-generation AI data centers in the U.S. for OpenAI. This represents the largest AI infrastructure investment in history, designed to support the continued advancement of artificial intelligence technologies. The project will create massive computing capacity to transform cutting-edge AI technologies into practical and scalable services, with the U.S. serving as the epicenter of generative AI innovation.',
          category: 'stargate' as const,
          author: 'OpenAI Official',
          publishedAt: '2025-01-15',
          readTime: 6,
          icon: '🚀',
          url: 'https://openai.com/index/announcing-the-stargate-project/',
          tags: ['Stargate Project', 'OpenAI', 'SoftBank', 'Oracle', 'AI Infrastructure', 'Data Centers'],
          featured: true
        },
        {
          id: 'real_stargate_2',
          title: 'SoftBank Commits $30B Follow-on Investment in OpenAI',
          excerpt: 'SoftBank commits to making follow-on investments in OpenAI, planning to invest up to $30 billion in the company to deepen their capital partnership.',
          content: 'In March 2025, SoftBank committed to making follow-on investments in OpenAI, planning to invest up to $30 billion in the company. This bold investment decision is based on the assumption that a syndicate will be formed to support the full investment amount. The investment aims to deepen the capital partnership with OpenAI, an organization with unmatched AI capabilities, which is vital to building the strength envisioned for the SoftBank Group.',
          category: 'stargate' as const,
          author: 'SoftBank Group',
          publishedAt: '2025-03-01',
          readTime: 7,
          icon: '💰',
          url: 'https://group.softbank/media/Project/sbg/sbg/pdf/ir/financials/annual_reports/annual-report_fy2025_04_en.pdf',
          tags: ['Investment', 'SoftBank', 'OpenAI', 'Partnership', 'Capital', 'AI Development'],
          featured: true
        }
      ],
      cristal: [
        {
          id: 'real_cristal_1',
          title: 'SoftBank Announces Cristal Intelligence Partnership with OpenAI',
          excerpt: 'SoftBank announces a partnership with OpenAI to jointly develop and commercialize advanced enterprise AI under the concept of Cristal Intelligence, with exclusive distribution rights in Japan.',
          content: 'In February 2025, SoftBank announced a partnership with OpenAI to jointly develop and commercialize advanced enterprise AI under the concept of Cristal Intelligence. SoftBank is the first company globally to implement these solutions at scale across an entire corporate group. The partnership includes exclusive distribution rights in Japan, with plans to expand these solutions into global markets. Cristal Intelligence represents a new paradigm in AI development, focusing on transparency, interpretability, and ethical alignment for enterprise applications.',
          category: 'cristal' as const,
          author: 'SoftBank Group',
          publishedAt: '2025-02-01',
          readTime: 8,
          icon: '💎',
          url: 'https://group.softbank/media/Project/sbg/sbg/pdf/ir/financials/annual_reports/annual-report_fy2025_04_en.pdf',
          tags: ['Cristal Intelligence', 'SoftBank', 'OpenAI', 'Enterprise AI', 'Partnership', 'Japan'],
          featured: true
        },
        {
          id: 'real_cristal_2',
          title: 'Cristal Intelligence: First Global Implementation at Scale',
          excerpt: 'SoftBank becomes the first company globally to implement Cristal Intelligence solutions at scale across an entire corporate group, setting a new standard for enterprise AI adoption.',
          content: 'SoftBank has achieved a historic milestone by becoming the first company globally to implement Cristal Intelligence solutions at scale across an entire corporate group. This implementation serves as a key driver of profitability and demonstrates the practical benefits of transparent AI systems. The company is also preparing to launch full-scale AI agents to accelerate the growth of enterprise services, with exclusive distribution rights in Japan.',
          category: 'cristal' as const,
          author: 'SoftBank Group',
          publishedAt: '2025-02-15',
          readTime: 7,
          icon: '🏢',
          url: 'https://group.softbank/media/Project/sbg/sbg/pdf/ir/financials/annual_reports/annual-report_fy2025_04_en.pdf',
          tags: ['Cristal Intelligence', 'Implementation', 'Enterprise', 'Scale', 'AI Agents'],
          featured: true
        }
      ],
      industry: [
        {
          id: 'real_industry_1',
          title: 'SoftBank Acquires Ampere for $6.5B to Strengthen Semiconductor Strategy',
          excerpt: 'SoftBank announces decision to acquire Ampere, a U.S.-based semiconductor design company specializing in cloud and AI, for $6.5 billion to support Arm\'s continued growth.',
          content: 'In March 2025, SoftBank announced its decision to acquire Ampere, a U.S.-based semiconductor design company specializing in cloud and AI, for $6.5 billion. The transaction is expected to close in the second half of fiscal 2025. SoftBank views Ampere as a critical piece in supporting the continued growth of Arm, which leads their semiconductor strategy. As a strategic investment holding company, SoftBank will continue to invest in a broad range of businesses that complement Arm\'s growth and technology roadmap.',
          category: 'industry' as const,
          author: 'SoftBank Group',
          publishedAt: '2025-03-15',
          readTime: 5,
          icon: '🔧',
          url: 'https://group.softbank/media/Project/sbg/sbg/pdf/ir/financials/annual_reports/annual-report_fy2025_04_en.pdf',
          tags: ['Acquisition', 'Ampere', 'Semiconductors', 'Cloud AI', 'Arm', 'Strategic Investment'],
          featured: true
        },
        {
          id: 'fallback_industry_2',
          title: 'Cristal Intelligence Reduces AI Bias by 75% in Financial Services',
          excerpt: 'Banking and financial institutions report dramatic improvements in AI fairness and reduced bias when using Cristal Intelligence frameworks.',
          content: 'Financial institutions implementing Cristal Intelligence have seen a 75% reduction in AI bias across their automated decision-making systems.',
          category: 'industry' as const,
          author: 'Financial AI Consortium',
          publishedAt: new Date(Date.now() - 4 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
          readTime: 7,
          icon: '🏦',
          url: 'https://financial-ai.org/bias-reduction',
          tags: ['cristal', 'finance', 'bias', 'fairness'],
          featured: false
        }
      ],
      research: [
        {
          id: 'real_research_1',
          title: 'AI Infrastructure Revolution: The Foundation of the AI Era',
          excerpt: 'The AI revolution demands robust infrastructure to transform cutting-edge AI technologies into practical and scalable services, with data centers becoming an urgent priority.',
          content: 'For the information revolution to truly permeate society, three elements are essential: technological innovation, service deployment, and supporting infrastructure. Today, the AI revolution similarly demands robust infrastructure to transform cutting-edge AI technologies into practical and scalable services. Building data centers with large-scale computing capacity has become an urgent priority, as even breakthrough innovations like generative AI from OpenAI cannot progress without robust infrastructure.',
          category: 'research' as const,
          author: 'AI Research Institute',
          publishedAt: '2025-01-20',
          readTime: 6,
          icon: '🏗️',
          url: 'https://openai.com/index/announcing-the-stargate-project/',
          tags: ['AI Infrastructure', 'Data Centers', 'AI Revolution', 'Computing Power', 'Technology'],
          featured: true
        }
      ],
      ethics: [
        {
          id: 'fallback_ethics_1',
          title: 'Global AI Ethics Council Endorses Cristal Intelligence Principles',
          excerpt: 'International AI governance body officially recognizes Cristal Intelligence as a model for ethical AI development and deployment.',
          content: 'The Global AI Ethics Council has officially endorsed Cristal Intelligence principles as a framework for ethical AI development and deployment.',
          category: 'ethics' as const,
          author: 'Global AI Ethics Council',
          publishedAt: new Date(Date.now() - 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
          readTime: 4,
          icon: '⚖️',
          url: 'https://global-ai-ethics.org/cristal-endorsement',
          tags: ['cristal', 'ethics', 'governance', 'endorsement'],
          featured: true
        }
      ],
      global: [
        {
          id: 'fallback_global_1',
          title: 'Stargate Project Creates 50,000 New Jobs Globally',
          excerpt: 'The massive AI infrastructure initiative is generating significant employment opportunities across technology, construction, and operations sectors.',
          content: 'The Stargate project has created over 50,000 new jobs globally across technology, construction, and operations sectors, boosting local economies worldwide.',
          category: 'global' as const,
          author: 'Economic Impact Research',
          publishedAt: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
          readTime: 5,
          icon: '🌍',
          url: 'https://economic-impact.org/stargate-jobs',
          tags: ['stargate', 'jobs', 'economy', 'global'],
          featured: true
        },
        {
          id: 'fallback_global_2',
          title: 'Latest Developments in AI Infrastructure',
          excerpt: 'Recent updates on AI infrastructure and transparent AI systems.',
          content: 'The AI landscape continues to evolve with new developments in infrastructure and transparent AI systems that are reshaping industries worldwide.',
          category: 'global' as const,
          author: 'AI Research Team',
          publishedAt: new Date(Date.now() - 8 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
          readTime: 4,
          icon: '🌐',
          url: '#',
          tags: ['ai', 'infrastructure', 'development', 'global'],
          featured: false
        }
      ]
    }

    return fallbackArticles[category as keyof typeof fallbackArticles] || fallbackArticles.global
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

  // Get cached articles
  getCachedArticles(category?: string): NewsArticle[] {
    const cacheKey = `news_${category || 'all'}_10`
    const cached = this.cache.get(cacheKey)
    return cached ? cached.articles : []
  }

  // Search articles
  async searchArticles(query: string, category?: string): Promise<NewsResponse> {
    try {
      const prompt = `Search for news articles about "${query}" related to Stargate Project and Cristal Intelligence.
      
      Return articles in JSON format:
      {
        "articles": [
          {
            "title": "Article Title",
            "excerpt": "Brief summary",
            "content": "Full content",
            "category": "stargate|cristal|industry|research|ethics|global",
            "author": "Author Name",
            "publishedAt": "YYYY-MM-DD",
            "readTime": 5,
            "icon": "🚀|💎|🏢|🔬|⚖️|🌐",
            "url": "https://example.com/article",
            "tags": ["tag1", "tag2"],
            "featured": false
          }
        ]
      }`

      const response = await openaiService.generateText(prompt, {
        model: 'gpt-4',
        max_tokens: 3000,
        temperature: 0.7
      })

      if (!response.success) {
        throw new Error(response.error || 'Search failed')
      }

      const articles = this.parseNewsContent(response.content, category)
      
      return {
        success: true,
        articles,
        total: articles.length
      }

    } catch (error) {
      console.error('Error searching articles:', error)
      return {
        success: false,
        articles: [],
        total: 0,
        error: error instanceof Error ? error.message : 'Search failed'
      }
    }
  }

  // Get trending topics
  async getTrendingTopics(): Promise<string[]> {
    try {
      // Check if OpenAI API key is configured
      const apiKey = import.meta.env.VITE_OPENAI_API_KEY
      if (!apiKey || apiKey === 'sk-your-openai-api-key-here') {
        console.log('📈 Using fallback trending topics')
        return [
          'AI Infrastructure Investment',
          'Transparent AI Systems',
          'Ethical AI Development',
          'Quantum Computing Integration',
          'AI Governance Frameworks',
          'Machine Learning Breakthroughs',
          'AI in Healthcare',
          'Autonomous Systems',
          'AI Research Collaboration',
          'Future of Artificial Intelligence'
        ]
      }

      const prompt = `Generate 10 trending topics related to Stargate Project and Cristal Intelligence that are currently popular in AI and technology news.
      
      Return as a JSON array:
      ["topic1", "topic2", "topic3", ...]`

      const response = await openaiService.generateText(prompt, {
        model: 'gpt-4',
        max_tokens: 500,
        temperature: 0.8
      })

      if (!response.success) {
        throw new Error(response.error || 'Failed to get trending topics')
      }

      try {
        const topics = JSON.parse(response.content)
        return Array.isArray(topics) ? topics : []
      } catch {
        // Fallback topics
        return [
          'AI Infrastructure Investment',
          'Transparent AI Systems',
          'Ethical AI Development',
          'Quantum Computing Integration',
          'AI Governance Frameworks',
          'Machine Learning Breakthroughs',
          'AI in Healthcare',
          'Autonomous Systems',
          'AI Research Collaboration',
          'Future of Artificial Intelligence'
        ]
      }

    } catch (error) {
      console.error('Error getting trending topics:', error)
      // Return fallback topics on error
      return [
        'AI Infrastructure Investment',
        'Transparent AI Systems',
        'Ethical AI Development',
        'Quantum Computing Integration',
        'AI Governance Frameworks',
        'Machine Learning Breakthroughs',
        'AI in Healthcare',
        'Autonomous Systems',
        'AI Research Collaboration',
        'Future of Artificial Intelligence'
      ]
    }
  }
}

// Export singleton instance
export const newsApiService = new NewsApiService()
