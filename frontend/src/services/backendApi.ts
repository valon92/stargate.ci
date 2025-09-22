// Backend API Service - Real Laravel API Integration
export interface LaravelApiResponse<T> {
  success: boolean;
  data: T;
  message?: string;
  errors?: Record<string, string[]>;
}

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
  private baseUrl = 'http://localhost:8003/api/v1';
  
  getApiBaseUrl(): string {
    return this.baseUrl;
  }
  private authToken: string | null = null;
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

  // Set authentication token
  setAuthToken(token: string | null) {
    this.authToken = token;
    if (token) {
      localStorage.setItem('auth_token', token);
    } else {
      localStorage.removeItem('auth_token');
    }
  }

  // Get authentication token
  getAuthToken(): string | null {
    if (!this.authToken) {
      this.authToken = localStorage.getItem('auth_token');
    }
    return this.authToken;
  }

  private async makeRequest<T>(endpoint: string, options: RequestInit = {}): Promise<ApiResponse<T>> {
    try {
      const headers: Record<string, string> = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers as Record<string, string>,
      };

      // Add auth token if available
      const token = this.getAuthToken();
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }

      const response = await fetch(`${this.baseUrl}${endpoint}`, {
        headers,
        ...options,
      });

      const data: LaravelApiResponse<T> = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || `HTTP error! status: ${response.status}`);
      }

      return {
        data: data.data,
        message: data.message,
        status: 'success'
      };
    } catch (error) {
      console.warn(`API request failed for ${endpoint}, using fallback data:`, error);
      return this.getFallbackData<T>(endpoint);
    }
  }

  // File Upload API methods

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
    const endpoint = category && category !== 'all' ? `/content/articles?category=${category}` : '/content/articles';
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

  // Authentication API
  async register(userData: {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
  }): Promise<ApiResponse<{ user: any; token: string; token_type: string }>> {
    try {
      const response = await fetch(`${this.baseUrl}/auth/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(userData),
      });

      const data: LaravelApiResponse<{ user: any; token: string; token_type: string }> = await response.json();
      
      if (!response.ok || !data.success) {
        throw new Error(data.message || 'Registration failed');
      }

      // Store token automatically
      this.setAuthToken(data.data.token);

      return {
        data: data.data,
        message: data.message,
        status: 'success'
      };
    } catch (error) {
      return {
        data: null as any,
        message: error instanceof Error ? error.message : 'Registration failed',
        status: 'error'
      };
    }
  }

  async login(credentials: {
    email: string;
    password: string;
  }): Promise<ApiResponse<{ user: any; token: string; token_type: string }>> {
    try {
      const response = await fetch(`${this.baseUrl}/auth/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(credentials),
      });

      const data: LaravelApiResponse<{ user: any; token: string; token_type: string }> = await response.json();
      
      if (!response.ok || !data.success) {
        throw new Error(data.message || 'Login failed');
      }

      // Store token automatically
      this.setAuthToken(data.data.token);

      return {
        data: data.data,
        message: data.message,
        status: 'success'
      };
    } catch (error) {
      return {
        data: null as any,
        message: error instanceof Error ? error.message : 'Login failed',
        status: 'error'
      };
    }
  }

  async logout(): Promise<ApiResponse<null>> {
    try {
      const response = await fetch(`${this.baseUrl}/auth/logout`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${this.getAuthToken()}`,
        },
      });

      const data: LaravelApiResponse<null> = await response.json();
      
      // Clear token regardless of response
      this.setAuthToken(null);

      return {
        data: null,
        message: data.message || 'Logged out successfully',
        status: 'success'
      };
    } catch (error) {
      // Clear token even if request fails
      this.setAuthToken(null);
      return {
        data: null,
        message: 'Logged out successfully',
        status: 'success'
      };
    }
  }

  async getMe(): Promise<ApiResponse<{ user: any }>> {
    return this.makeRequest<{ user: any }>('/user/profile');
  }

  // Content API (updated endpoints)
  async getContentCategories(): Promise<ApiResponse<any[]>> {
    return this.makeRequest<any[]>('/content/categories');
  }

  async getCommunityPosts(params?: { category?: string; search?: string }): Promise<ApiResponse<any>> {
    const queryParams = new URLSearchParams();
    if (params?.category) queryParams.append('category', params.category);
    if (params?.search) queryParams.append('search', params.search);
    
    const query = queryParams.toString();
    return this.makeRequest<any>(`/community/posts${query ? '?' + query : ''}`);
  }

  async getCommunityCategories(): Promise<ApiResponse<any[]>> {
    return this.makeRequest<any[]>('/community/categories');
  }

  // Search API
  async search(query: string, filters?: any): Promise<ApiResponse<any>> {
    const params = new URLSearchParams({ q: query });
    if (filters) {
      Object.entries(filters).forEach(([key, value]) => {
        if (value) params.append(key, String(value));
      });
    }
    return this.makeRequest<any>(`/search?${params.toString()}`);
  }

  // Analytics API
  async getPublicStats(): Promise<ApiResponse<any>> {
    return this.makeRequest<any>('/analytics/stats');
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
      const response = await fetch(`${this.baseUrl.replace('/v1', '')}/health`);
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
