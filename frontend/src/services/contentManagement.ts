// Content Management System Service
export interface ContentItem {
  id: string;
  type: 'article' | 'faq' | 'announcement' | 'tutorial' | 'news';
  title: string;
  content: string;
  excerpt?: string;
  author: string;
  status: 'draft' | 'published' | 'archived';
  category: string;
  tags: string[];
  featured: boolean;
  priority: number;
  publishedAt?: string;
  createdAt: string;
  updatedAt: string;
  views: number;
  likes: number;
  comments: number;
  metadata?: {
    seoTitle?: string;
    seoDescription?: string;
    imageUrl?: string;
    readTime?: number;
  };
}

export interface ContentFilter {
  type?: string;
  status?: string;
  category?: string;
  author?: string;
  featured?: boolean;
  dateFrom?: string;
  dateTo?: string;
  search?: string;
  tags?: string[];
}

export interface ContentStats {
  total: number;
  published: number;
  draft: number;
  archived: number;
  byType: Record<string, number>;
  byCategory: Record<string, number>;
  byAuthor: Record<string, number>;
  popular: ContentItem[];
  recent: ContentItem[];
}

class ContentManagementService {
  private readonly STORAGE_KEY = 'stargate_content_items';
  private readonly DRAFTS_KEY = 'stargate_content_drafts';
  private readonly STATS_KEY = 'stargate_content_stats';

  // Get all content items
  getAllContent(): ContentItem[] {
    const stored = localStorage.getItem(this.STORAGE_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  // Get content by ID
  getContentById(id: string): ContentItem | null {
    const items = this.getAllContent();
    return items.find(item => item.id === id) || null;
  }

  // Get content by type
  getContentByType(type: string): ContentItem[] {
    const items = this.getAllContent();
    return items.filter(item => item.type === type);
  }

  // Get published content
  getPublishedContent(): ContentItem[] {
    const items = this.getAllContent();
    return items.filter(item => item.status === 'published');
  }

  // Get featured content
  getFeaturedContent(): ContentItem[] {
    const items = this.getPublishedContent();
    return items.filter(item => item.featured).sort((a, b) => b.priority - a.priority);
  }

  // Search content
  searchContent(query: string): ContentItem[] {
    const items = this.getAllContent();
    const lowerQuery = query.toLowerCase();
    
    return items.filter(item => 
      item.title.toLowerCase().includes(lowerQuery) ||
      item.content.toLowerCase().includes(lowerQuery) ||
      item.excerpt?.toLowerCase().includes(lowerQuery) ||
      item.tags.some(tag => tag.toLowerCase().includes(lowerQuery))
    );
  }

  // Filter content
  filterContent(filters: ContentFilter): ContentItem[] {
    let items = this.getAllContent();

    if (filters.type) {
      items = items.filter(item => item.type === filters.type);
    }

    if (filters.status) {
      items = items.filter(item => item.status === filters.status);
    }

    if (filters.category) {
      items = items.filter(item => item.category === filters.category);
    }

    if (filters.author) {
      items = items.filter(item => item.author === filters.author);
    }

    if (filters.featured !== undefined) {
      items = items.filter(item => item.featured === filters.featured);
    }

    if (filters.dateFrom) {
      items = items.filter(item => new Date(item.createdAt) >= new Date(filters.dateFrom!));
    }

    if (filters.dateTo) {
      items = items.filter(item => new Date(item.createdAt) <= new Date(filters.dateTo!));
    }

    if (filters.search) {
      const searchQuery = filters.search.toLowerCase();
      items = items.filter(item => 
        item.title.toLowerCase().includes(searchQuery) ||
        item.content.toLowerCase().includes(searchQuery) ||
        item.excerpt?.toLowerCase().includes(searchQuery)
      );
    }

    if (filters.tags && filters.tags.length > 0) {
      items = items.filter(item => 
        filters.tags!.some(tag => item.tags.includes(tag))
      );
    }

    return items;
  }

  // Create new content
  createContent(content: Omit<ContentItem, 'id' | 'createdAt' | 'updatedAt' | 'views' | 'likes' | 'comments'>): ContentItem {
    const newContent: ContentItem = {
      ...content,
      id: this.generateId(),
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString(),
      views: 0,
      likes: 0,
      comments: 0
    };

    const items = this.getAllContent();
    items.push(newContent);
    this.saveContent(items);

    return newContent;
  }

  // Update content
  updateContent(id: string, updates: Partial<ContentItem>): ContentItem | null {
    const items = this.getAllContent();
    const index = items.findIndex(item => item.id === id);

    if (index === -1) return null;

    items[index] = {
      ...items[index],
      ...updates,
      updatedAt: new Date().toISOString()
    };

    this.saveContent(items);
    return items[index];
  }

  // Delete content
  deleteContent(id: string): boolean {
    const items = this.getAllContent();
    const filtered = items.filter(item => item.id !== id);
    
    if (filtered.length === items.length) return false;
    
    this.saveContent(filtered);
    return true;
  }

  // Publish content
  publishContent(id: string): ContentItem | null {
    return this.updateContent(id, {
      status: 'published',
      publishedAt: new Date().toISOString()
    });
  }

  // Archive content
  archiveContent(id: string): ContentItem | null {
    return this.updateContent(id, {
      status: 'archived'
    });
  }

  // Increment views
  incrementViews(id: string): void {
    const content = this.getContentById(id);
    if (content) {
      this.updateContent(id, { views: content.views + 1 });
    }
  }

  // Toggle like
  toggleLike(id: string): ContentItem | null {
    const content = this.getContentById(id);
    if (content) {
      return this.updateContent(id, { likes: content.likes + 1 });
    }
    return null;
  }

  // Get content statistics
  getContentStats(): ContentStats {
    const items = this.getAllContent();
    const published = items.filter(item => item.status === 'published');
    const draft = items.filter(item => item.status === 'draft');
    const archived = items.filter(item => item.status === 'archived');

    const byType: Record<string, number> = {};
    const byCategory: Record<string, number> = {};
    const byAuthor: Record<string, number> = {};

    items.forEach(item => {
      byType[item.type] = (byType[item.type] || 0) + 1;
      byCategory[item.category] = (byCategory[item.category] || 0) + 1;
      byAuthor[item.author] = (byAuthor[item.author] || 0) + 1;
    });

    const popular = [...published]
      .sort((a, b) => b.views - a.views)
      .slice(0, 5);

    const recent = [...published]
      .sort((a, b) => new Date(b.publishedAt || b.createdAt).getTime() - new Date(a.publishedAt || a.createdAt).getTime())
      .slice(0, 5);

    return {
      total: items.length,
      published: published.length,
      draft: draft.length,
      archived: archived.length,
      byType,
      byCategory,
      byAuthor,
      popular,
      recent
    };
  }

  // Get categories
  getCategories(): string[] {
    const items = this.getAllContent();
    const categories = new Set(items.map(item => item.category));
    return Array.from(categories).sort();
  }

  // Get tags
  getTags(): string[] {
    const items = this.getAllContent();
    const tags = new Set(items.flatMap(item => item.tags));
    return Array.from(tags).sort();
  }

  // Get authors
  getAuthors(): string[] {
    const items = this.getAllContent();
    const authors = new Set(items.map(item => item.author));
    return Array.from(authors).sort();
  }

  // Save content to localStorage
  private saveContent(items: ContentItem[]): void {
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(items));
  }

  // Generate unique ID
  private generateId(): string {
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
  }

  // Initialize with sample data
  initializeSampleData(): void {
    const existing = this.getAllContent();
    if (existing.length > 0) return;

    const sampleContent: ContentItem[] = [
      {
        id: '1',
        type: 'article',
        title: 'Getting Started with Stargate.ci',
        content: 'This comprehensive guide will help you understand the basics of Stargate.ci and how to get started with our platform.',
        excerpt: 'Learn the fundamentals of Stargate.ci and begin your journey with our platform.',
        author: 'Stargate Team',
        status: 'published',
        category: 'Getting Started',
        tags: ['tutorial', 'beginner', 'guide'],
        featured: true,
        priority: 10,
        publishedAt: '2024-01-15T10:00:00Z',
        createdAt: '2024-01-15T10:00:00Z',
        updatedAt: '2024-01-15T10:00:00Z',
        views: 1250,
        likes: 45,
        comments: 12,
        metadata: {
          seoTitle: 'Getting Started with Stargate.ci - Complete Guide',
          seoDescription: 'Learn how to get started with Stargate.ci platform',
          imageUrl: '/images/getting-started.jpg',
          readTime: 8
        }
      },
      {
        id: '2',
        type: 'tutorial',
        title: 'Advanced AI Integration Techniques',
        content: 'Explore advanced techniques for integrating AI capabilities into your applications using Stargate.ci.',
        excerpt: 'Master advanced AI integration with our comprehensive tutorial.',
        author: 'AI Expert',
        status: 'published',
        category: 'AI',
        tags: ['ai', 'integration', 'advanced', 'tutorial'],
        featured: true,
        priority: 9,
        publishedAt: '2024-01-20T14:30:00Z',
        createdAt: '2024-01-20T14:30:00Z',
        updatedAt: '2024-01-20T14:30:00Z',
        views: 890,
        likes: 32,
        comments: 8,
        metadata: {
          seoTitle: 'Advanced AI Integration Techniques - Stargate.ci',
          seoDescription: 'Learn advanced AI integration techniques',
          imageUrl: '/images/ai-integration.jpg',
          readTime: 15
        }
      },
      {
        id: '3',
        type: 'announcement',
        title: 'New Features Released - February 2024',
        content: 'We are excited to announce several new features and improvements to the Stargate.ci platform.',
        excerpt: 'Discover the latest features and improvements in our February 2024 release.',
        author: 'Product Team',
        status: 'published',
        category: 'Announcements',
        tags: ['announcement', 'features', 'update'],
        featured: false,
        priority: 8,
        publishedAt: '2024-02-01T09:00:00Z',
        createdAt: '2024-02-01T09:00:00Z',
        updatedAt: '2024-02-01T09:00:00Z',
        views: 2100,
        likes: 78,
        comments: 25,
        metadata: {
          seoTitle: 'New Features Released - February 2024',
          seoDescription: 'Latest features and improvements in Stargate.ci',
          imageUrl: '/images/new-features.jpg',
          readTime: 5
        }
      },
      {
        id: '4',
        type: 'news',
        title: 'Stargate Project Partnership Update',
        content: 'Latest updates on the Stargate project partnership between OpenAI, SoftBank, and Arm.',
        excerpt: 'Stay updated with the latest developments in the Stargate project partnership.',
        author: 'News Team',
        status: 'published',
        category: 'News',
        tags: ['partnership', 'stargate', 'openai', 'softbank', 'arm'],
        featured: true,
        priority: 10,
        publishedAt: '2024-02-05T16:45:00Z',
        createdAt: '2024-02-05T16:45:00Z',
        updatedAt: '2024-02-05T16:45:00Z',
        views: 3200,
        likes: 156,
        comments: 42,
        metadata: {
          seoTitle: 'Stargate Project Partnership Update',
          seoDescription: 'Latest updates on Stargate project partnership',
          imageUrl: '/images/partnership-update.jpg',
          readTime: 6
        }
      },
      {
        id: '5',
        type: 'faq',
        title: 'Frequently Asked Questions - Security',
        content: 'Common questions and answers about security features and best practices on Stargate.ci.',
        excerpt: 'Find answers to common security-related questions.',
        author: 'Support Team',
        status: 'published',
        category: 'FAQ',
        tags: ['faq', 'security', 'help'],
        featured: false,
        priority: 7,
        publishedAt: '2024-02-10T11:20:00Z',
        createdAt: '2024-02-10T11:20:00Z',
        updatedAt: '2024-02-10T11:20:00Z',
        views: 750,
        likes: 18,
        comments: 3,
        metadata: {
          seoTitle: 'Security FAQ - Stargate.ci',
          seoDescription: 'Common security questions and answers',
          imageUrl: '/images/security-faq.jpg',
          readTime: 4
        }
      }
    ];

    this.saveContent(sampleContent);
  }
}

export const contentManagement = new ContentManagementService();
