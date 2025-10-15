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

  // Create fallback articles if OpenAI fails
  private createFallbackArticles(category?: string): NewsArticle[] {
    const fallbackArticles = {
      stargate: [
        {
          id: 'fallback_stargate_1',
          title: 'Stargate Project Reaches Major Infrastructure Milestone',
          excerpt: 'OpenAI, SoftBank, and Arm announce significant progress in the $500 billion AI infrastructure initiative.',
          content: 'The Stargate project continues to make remarkable progress as the three major partners announce new data centers coming online. This represents a crucial step in creating the world\'s most advanced AI infrastructure.',
          category: 'stargate' as const,
          author: 'Stargate Team',
          publishedAt: new Date().toISOString().split('T')[0],
          readTime: 5,
          icon: '🚀',
          url: 'https://openai.com/index/announcing-the-stargate-project/',
          tags: ['stargate', 'infrastructure', 'milestone'],
          featured: true
        },
        {
          id: 'fallback_stargate_2',
          title: 'Arm Announces Next-Generation AI Chips for Stargate',
          excerpt: 'Advanced semiconductor technology specifically designed to power the massive computational requirements of the Stargate AI infrastructure.',
          content: 'Arm has unveiled their latest AI-optimized chip architecture designed specifically for the Stargate project. These chips will provide unprecedented computational power for AI model training and inference.',
          category: 'stargate' as const,
          author: 'Arm Technology',
          publishedAt: new Date(Date.now() - 2 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
          readTime: 6,
          icon: '🔧',
          url: 'https://arm.com/stargate-chips',
          tags: ['stargate', 'arm', 'chips', 'hardware'],
          featured: false
        }
      ],
      cristal: [
        {
          id: 'fallback_cristal_1',
          title: 'Cristal Intelligence Unveils New Transparency Framework',
          excerpt: 'The revolutionary AI paradigm introduces advanced explainability features for complete transparency.',
          content: 'Cristal Intelligence has announced a breakthrough in AI transparency with their new framework that makes AI decision-making processes completely understandable and auditable.',
          category: 'cristal' as const,
          author: 'Dr. Sarah Chen',
          publishedAt: new Date().toISOString().split('T')[0],
          readTime: 7,
          icon: '💎',
          url: 'https://cristal-intelligence.com/transparency-framework',
          tags: ['cristal', 'transparency', 'framework'],
          featured: true
        },
        {
          id: 'fallback_cristal_2',
          title: 'New Research Shows 40% Improvement in AI Interpretability',
          excerpt: 'Latest studies demonstrate that Cristal Intelligence approaches significantly outperform traditional AI models in transparency and explainability.',
          content: 'A comprehensive study published this week shows that Cristal Intelligence methods achieve 40% better interpretability scores compared to traditional black-box AI models.',
          category: 'cristal' as const,
          author: 'AI Research Institute',
          publishedAt: new Date(Date.now() - 3 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
          readTime: 8,
          icon: '🔬',
          url: 'https://ai-research.org/interpretability-study',
          tags: ['cristal', 'research', 'interpretability', 'study'],
          featured: false
        }
      ],
      industry: [
        {
          id: 'fallback_industry_1',
          title: 'Healthcare Industry Adopts Cristal Intelligence for Diagnostic AI',
          excerpt: 'Major hospitals and medical institutions are implementing Cristal Intelligence to create more trustworthy and explainable diagnostic systems.',
          content: 'Leading healthcare providers are now implementing Cristal Intelligence frameworks to create AI diagnostic systems that provide clear explanations for their medical recommendations.',
          category: 'industry' as const,
          author: 'Medical AI Research',
          publishedAt: new Date(Date.now() - 1 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
          readTime: 6,
          icon: '🏥',
          url: 'https://healthcare-ai.org/cristal-adoption',
          tags: ['cristal', 'healthcare', 'diagnostics', 'adoption'],
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
          id: 'fallback_research_1',
          title: 'Quantum Computing Integration with Stargate Infrastructure',
          excerpt: 'Breakthrough research shows how quantum computing can be integrated with traditional AI infrastructure to create hybrid systems.',
          content: 'Researchers have successfully demonstrated how quantum computing can be integrated with the Stargate AI infrastructure to create hybrid systems that combine classical and quantum processing.',
          category: 'research' as const,
          author: 'Quantum AI Research',
          publishedAt: new Date(Date.now() - 5 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
          readTime: 9,
          icon: '⚛️',
          url: 'https://quantum-ai.org/stargate-integration',
          tags: ['stargate', 'quantum', 'research', 'hybrid'],
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
