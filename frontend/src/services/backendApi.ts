// Backend API Service - Simulated until Laravel backend is fully functional
export interface ApiResponse<T> {
  data: T;
  message?: string;
  status: 'success' | 'error';
}

export interface Article {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  category: string;
  icon?: string;
  author?: string;
  read_time?: number;
  published: boolean;
  published_at: string;
  created_at: string;
  updated_at: string;
}

export interface FAQ {
  id: number;
  question: string;
  answer: string;
  category: string;
  order: number;
  created_at: string;
  updated_at: string;
}

export interface ContactMessage {
  id: number;
  name: string;
  email: string;
  subject: string;
  message: string;
  read: boolean;
  created_at: string;
  updated_at: string;
}

class BackendApiService {
  private baseUrl = 'http://localhost:8000/api/v1';
  private fallbackData = {
    articles: [
      {
        id: 1,
        title: "Understanding Stargate Project",
        slug: "understanding-stargate-project",
        excerpt: "A comprehensive guide to the Stargate project by OpenAI, SoftBank, and Arm.",
        content: "The Stargate project represents a groundbreaking collaboration between OpenAI, SoftBank, and Arm to develop next-generation AI infrastructure...",
        category: "AI Infrastructure",
        icon: "🚀",
        author: "Stargate Team",
        read_time: 5,
        published: true,
        published_at: "2025-01-01T00:00:00Z",
        created_at: "2025-01-01T00:00:00Z",
        updated_at: "2025-01-01T00:00:00Z"
      },
      {
        id: 2,
        title: "Cristal Intelligence Overview",
        slug: "cristal-intelligence-overview",
        excerpt: "Exploring the concept of Cristal Intelligence and its applications.",
        content: "Cristal Intelligence represents a new paradigm in artificial intelligence, focusing on crystal-clear decision making and transparent AI processes...",
        category: "AI Concepts",
        icon: "💎",
        author: "Cristal Team",
        read_time: 7,
        published: true,
        published_at: "2025-01-02T00:00:00Z",
        created_at: "2025-01-02T00:00:00Z",
        updated_at: "2025-01-02T00:00:00Z"
      }
    ],
    faqs: [
      {
        id: 1,
        question: "What is the Stargate project?",
        answer: "The Stargate project is a collaboration between OpenAI, SoftBank, and Arm to develop advanced AI infrastructure and computing systems.",
        category: "General",
        order: 1,
        created_at: "2025-01-01T00:00:00Z",
        updated_at: "2025-01-01T00:00:00Z"
      },
      {
        id: 2,
        question: "What is Cristal Intelligence?",
        answer: "Cristal Intelligence is a concept focused on transparent, crystal-clear AI decision making and ethical AI practices.",
        category: "General",
        order: 2,
        created_at: "2025-01-01T00:00:00Z",
        updated_at: "2025-01-01T00:00:00Z"
      }
    ]
  };

  private async makeRequest<T>(endpoint: string, options: RequestInit = {}): Promise<ApiResponse<T>> {
    try {
      const response = await fetch(`${this.baseUrl}${endpoint}`, {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...options.headers,
        },
        ...options,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      return {
        data,
        status: 'success'
      };
    } catch (error) {
      console.warn(`API request failed for ${endpoint}, using fallback data:`, error);
      return this.getFallbackData<T>(endpoint);
    }
  }

  private getFallbackData<T>(endpoint: string): ApiResponse<T> {
    if (endpoint === '/articles') {
      return {
        data: this.fallbackData.articles as T,
        status: 'success',
        message: 'Using fallback data'
      };
    }
    
    if (endpoint === '/faqs') {
      return {
        data: this.fallbackData.faqs as T,
        status: 'success',
        message: 'Using fallback data'
      };
    }

    return {
      data: [] as T,
      status: 'error',
      message: 'No fallback data available'
    };
  }

  // Articles API
  async getArticles(category?: string): Promise<ApiResponse<Article[]>> {
    const endpoint = category && category !== 'all' ? `/articles?category=${category}` : '/articles';
    return this.makeRequest<Article[]>(endpoint);
  }

  async getArticle(slug: string): Promise<ApiResponse<Article>> {
    return this.makeRequest<Article>(`/articles/${slug}`);
  }

  // FAQs API
  async getFAQs(): Promise<ApiResponse<FAQ[]>> {
    return this.makeRequest<FAQ[]>('/faqs');
  }

  async getFAQ(id: number): Promise<ApiResponse<FAQ>> {
    return this.makeRequest<FAQ>(`/faqs/${id}`);
  }

  // Contact API
  async sendContactMessage(message: Omit<ContactMessage, 'id' | 'read' | 'created_at' | 'updated_at'>): Promise<ApiResponse<ContactMessage>> {
    return this.makeRequest<ContactMessage>('/contact', {
      method: 'POST',
      body: JSON.stringify(message)
    });
  }

  // Health check
  async healthCheck(): Promise<ApiResponse<{ status: string; timestamp: string }>> {
    try {
      const response = await fetch(`${this.baseUrl}/health`);
      if (response.ok) {
        const data = await response.json();
        return {
          data: {
            status: 'healthy',
            timestamp: new Date().toISOString()
          },
          status: 'success'
        };
      }
    } catch (error) {
      console.warn('Health check failed:', error);
    }

    return {
      data: {
        status: 'fallback',
        timestamp: new Date().toISOString()
      },
      status: 'success',
      message: 'Using fallback mode'
    };
  }
}

export const backendApi = new BackendApiService();
