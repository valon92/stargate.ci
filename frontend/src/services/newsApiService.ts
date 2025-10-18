// News API Service - Static data only

export interface NewsArticle {
  id: string
  title: string
  content: string
  excerpt: string
  author: string
  publishedAt: string
  category: 'stargate' | 'cristal' | 'openai' | 'softbank' | 'arm' | 'ai' | 'technology'
  source: string
  url: string
  imageUrl?: string
  tags: string[]
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

  // Generate news using static data
  async generateNews(category?: string, limit: number = 10): Promise<NewsResponse> {
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

      console.log('📰 Using static news data')
      const staticNews = this.createStaticNews(category)
      const result: NewsResponse = {
        success: true,
        articles: staticNews.slice(0, limit),
        total: staticNews.length
      }
      
      // Cache the result
      this.cache.set(cacheKey, result)
      this.cacheExpiry.set(cacheKey, Date.now() + this.CACHE_DURATION)
      return result

    } catch (error) {
      console.error('📰 Error generating news:', error)
      // Return fallback content on error
      const fallbackNews = this.createFallbackNews(category)
      return {
        success: true,
        articles: fallbackNews,
        total: fallbackNews.length
      }
    }
  }

  // Create static news data
  private createStaticNews(category?: string): NewsArticle[] {
    const allNews: NewsArticle[] = [
      {
        id: 'stargate-announcement-2024',
        title: 'OpenAI, SoftBank, and Arm Announce $500 Billion Stargate Project',
        content: 'The Stargate Project represents a revolutionary $500 billion AI infrastructure initiative that will reshape the future of artificial intelligence. This massive undertaking brings together three industry giants to create next-generation AI computing capabilities.',
        excerpt: 'Revolutionary $500 billion AI infrastructure project announced by OpenAI, SoftBank, and Arm.',
        author: 'AI Research Team',
        publishedAt: '2024-12-15T10:00:00Z',
        category: 'stargate',
        source: 'Official Announcement',
        url: 'https://example.com/stargate-announcement',
        imageUrl: 'https://example.com/stargate-image.jpg',
        tags: ['stargate', 'openai', 'softbank', 'arm', 'ai-infrastructure']
      },
      {
        id: 'cristal-intelligence-breakthrough',
        title: 'Cristal Intelligence: A New Paradigm in Transparent AI',
        content: 'Cristal Intelligence represents a breakthrough in AI transparency and interpretability. This new paradigm focuses on creating AI systems that are as clear and understandable as crystal, enabling better decision-making and ethical AI development.',
        excerpt: 'New paradigm in AI transparency and interpretability unveiled.',
        author: 'Dr. Sarah Chen',
        publishedAt: '2024-12-10T14:30:00Z',
        category: 'cristal',
        source: 'AI Research Journal',
        url: 'https://example.com/cristal-intelligence',
        imageUrl: 'https://example.com/cristal-image.jpg',
        tags: ['cristal-intelligence', 'ai-transparency', 'ethical-ai', 'interpretability']
      },
      {
        id: 'openai-gpt-5-development',
        title: 'OpenAI Accelerates GPT-5 Development with Stargate Infrastructure',
        content: 'OpenAI is leveraging the Stargate Project infrastructure to accelerate the development of GPT-5, their next-generation language model. This collaboration will enable unprecedented scale and capability in AI model training.',
        excerpt: 'OpenAI uses Stargate infrastructure to accelerate GPT-5 development.',
        author: 'Tech Reporter',
        publishedAt: '2024-12-08T09:15:00Z',
        category: 'openai',
        source: 'Tech News Daily',
        url: 'https://example.com/openai-gpt5',
        imageUrl: 'https://example.com/gpt5-image.jpg',
        tags: ['openai', 'gpt-5', 'stargate', 'ai-models', 'machine-learning']
      },
      {
        id: 'softbank-ai-investment-strategy',
        title: 'SoftBank Vision Fund Doubles Down on AI with Stargate Investment',
        content: 'SoftBank Vision Fund announces a significant investment in the Stargate Project, reinforcing their commitment to AI technology and infrastructure development. This investment aligns with their long-term vision for AI transformation.',
        excerpt: 'SoftBank Vision Fund makes major investment in Stargate Project.',
        author: 'Investment Analyst',
        publishedAt: '2024-12-05T16:45:00Z',
        category: 'softbank',
        source: 'Financial Times',
        url: 'https://example.com/softbank-investment',
        imageUrl: 'https://example.com/softbank-image.jpg',
        tags: ['softbank', 'investment', 'stargate', 'ai-funding', 'vision-fund']
      },
      {
        id: 'arm-ai-chip-architecture',
        title: 'ARM Unveils Next-Generation AI Chip Architecture for Stargate',
        content: 'ARM Holdings reveals their new AI-optimized chip architecture designed specifically for the Stargate Project. This breakthrough in chip design will enable unprecedented AI computing performance and efficiency.',
        excerpt: 'ARM reveals AI-optimized chip architecture for Stargate Project.',
        author: 'Hardware Engineer',
        publishedAt: '2024-12-03T11:20:00Z',
        category: 'arm',
        source: 'Hardware Weekly',
        url: 'https://example.com/arm-ai-chips',
        imageUrl: 'https://example.com/arm-image.jpg',
        tags: ['arm', 'ai-chips', 'hardware', 'stargate', 'chip-architecture']
      },
      {
        id: 'ai-ethics-cristal-intelligence',
        title: 'Cristal Intelligence Sets New Standards for AI Ethics',
        content: 'The principles of Cristal Intelligence are being adopted by leading AI companies to ensure ethical AI development. This new framework emphasizes transparency, accountability, and human-centered AI design.',
        excerpt: 'Cristal Intelligence principles adopted for ethical AI development.',
        author: 'AI Ethics Researcher',
        publishedAt: '2024-12-01T13:10:00Z',
        category: 'cristal',
        source: 'AI Ethics Journal',
        url: 'https://example.com/ai-ethics-cristal',
        imageUrl: 'https://example.com/ethics-image.jpg',
        tags: ['ai-ethics', 'cristal-intelligence', 'transparency', 'ethical-ai', 'ai-governance']
      },
      {
        id: 'stargate-infrastructure-update',
        title: 'Stargate Project Infrastructure Development Reaches Milestone',
        content: 'The Stargate Project infrastructure development has reached a significant milestone with the completion of the first phase of data centers. This achievement brings the project closer to its goal of creating the world\'s most advanced AI computing network.',
        excerpt: 'Stargate Project infrastructure development reaches major milestone.',
        author: 'Infrastructure Reporter',
        publishedAt: '2024-11-28T08:30:00Z',
        category: 'stargate',
        source: 'Infrastructure News',
        url: 'https://example.com/stargate-infrastructure',
        imageUrl: 'https://example.com/infrastructure-image.jpg',
        tags: ['stargate', 'infrastructure', 'data-centers', 'ai-computing', 'milestone']
      },
      {
        id: 'ai-research-breakthrough',
        title: 'Breakthrough in AI Research Enabled by Stargate Computing Power',
        content: 'Researchers have achieved a major breakthrough in AI research using the computing power of the Stargate Project. This advancement opens new possibilities for AI model development and scientific discovery.',
        excerpt: 'Major AI research breakthrough achieved using Stargate computing power.',
        author: 'Research Scientist',
        publishedAt: '2024-11-25T15:45:00Z',
        category: 'ai',
        source: 'Science Daily',
        url: 'https://example.com/ai-research-breakthrough',
        imageUrl: 'https://example.com/research-image.jpg',
        tags: ['ai-research', 'stargate', 'breakthrough', 'scientific-discovery', 'computing-power']
      }
    ]

    if (category && category !== 'all') {
      return allNews.filter(article => article.category === category)
    }

    return allNews
  }

  // Create fallback news
  private createFallbackNews(category?: string): NewsArticle[] {
    return [
      {
        id: 'fallback-news-1',
        title: 'AI Technology Advances in 2024',
        content: 'Artificial intelligence technology continues to advance rapidly in 2024, with new breakthroughs in machine learning and neural networks.',
        excerpt: 'AI technology advances continue in 2024.',
        author: 'Tech Reporter',
        publishedAt: '2024-12-15T10:00:00Z',
        category: 'ai',
        source: 'Tech News',
        url: 'https://example.com',
        tags: ['ai', 'technology', 'machine-learning']
      },
      {
        id: 'fallback-news-2',
        title: 'Future of Artificial Intelligence',
        content: 'The future of artificial intelligence looks promising with new developments in AI research and applications.',
        excerpt: 'Future of AI looks promising with new developments.',
        author: 'AI Researcher',
        publishedAt: '2024-12-10T14:30:00Z',
        category: 'technology',
        source: 'AI Journal',
        url: 'https://example.com',
        tags: ['ai', 'future', 'research']
      }
    ]
  }

  // Check if cache is valid
  private isCacheValid(key: string): boolean {
    const expiry = this.cacheExpiry.get(key)
    return expiry ? Date.now() < expiry : false
  }

  // Get news by category
  async getNewsByCategory(category: string, limit: number = 10): Promise<NewsResponse> {
    return this.generateNews(category, limit)
  }

  // Get all news
  async getAllNews(limit: number = 20): Promise<NewsResponse> {
    return this.generateNews('all', limit)
  }

  // Get latest news
  async getLatestNews(limit: number = 10): Promise<NewsResponse> {
    const allNews = await this.getAllNews(limit)
    
    // Sort by published date (newest first)
    const sortedNews = allNews.articles.sort((a, b) => 
      new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime()
    )

    return {
      success: true,
      articles: sortedNews.slice(0, limit),
      total: sortedNews.length
    }
  }

  // Search news
  async searchNews(query: string, limit: number = 10): Promise<NewsResponse> {
    const allNews = await this.getAllNews(50)
    
    const searchResults = allNews.articles.filter(article => 
      article.title.toLowerCase().includes(query.toLowerCase()) ||
      article.content.toLowerCase().includes(query.toLowerCase()) ||
      article.excerpt.toLowerCase().includes(query.toLowerCase()) ||
      article.tags.some(tag => tag.toLowerCase().includes(query.toLowerCase()))
    )

    return {
      success: true,
      articles: searchResults.slice(0, limit),
      total: searchResults.length
    }
  }
}

// Export singleton instance
export const newsApiService = new NewsApiService()