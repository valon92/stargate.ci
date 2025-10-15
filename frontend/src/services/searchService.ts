// Advanced Search & Filtering Service for Stargate.ci
export interface SearchResult {
  id: string;
  type: 'post' | 'user' | 'article' | 'faq' | 'template' | 'insight';
  title: string;
  content: string;
  excerpt: string;
  author?: string;
  category?: string;
  tags: string[];
  createdAt: string;
  updatedAt: string;
  relevanceScore: number;
  url: string;
  metadata?: Record<string, any>;
}

export interface SearchFilters {
  type?: string[];
  category?: string[];
  author?: string[];
  dateRange?: {
    from: string;
    to: string;
  };
  tags?: string[];
  language?: string;
  status?: string[];
}

export interface SearchOptions {
  query: string;
  filters?: SearchFilters;
  sortBy?: 'relevance' | 'date' | 'title' | 'author';
  sortOrder?: 'asc' | 'desc';
  limit?: number;
  offset?: number;
  includeContent?: boolean;
}

export interface SearchSuggestion {
  text: string;
  type: 'query' | 'category' | 'tag' | 'author';
  count?: number;
}

export interface SearchHistory {
  id: string;
  query: string;
  filters?: SearchFilters;
  timestamp: string;
  resultCount: number;
}

export interface SavedSearch {
  id: string;
  name: string;
  query: string;
  filters?: SearchFilters;
  createdAt: string;
  lastUsed: string;
  notifications: boolean;
}

class SearchService {
  private readonly SEARCH_HISTORY_KEY = 'stargate_search_history';
  private readonly SAVED_SEARCHES_KEY = 'stargate_saved_searches';
  private readonly SEARCH_INDEX_KEY = 'stargate_search_index';

  // Main search function
  async search(options: SearchOptions): Promise<{
    results: SearchResult[];
    totalCount: number;
    suggestions: SearchSuggestion[];
    facets: Record<string, Array<{ value: string; count: number }>>;
  }> {
    const {
      query,
      filters = {},
      sortBy = 'relevance',
      sortOrder = 'desc',
      limit = 20,
      offset = 0,
      includeContent = false
    } = options;

    // Get search index
    const searchIndex = await this.getSearchIndex();
    
    // Filter and search
    let results = await this.performSearch(searchIndex, query, filters);
    
    // Sort results
    results = this.sortResults(results, sortBy, sortOrder);
    
    // Calculate total count
    const totalCount = results.length;
    
    // Apply pagination
    results = results.slice(offset, offset + limit);
    
    // Generate suggestions
    const suggestions = await this.generateSuggestions(query, filters);
    
    // Generate facets
    const facets = await this.generateFacets(searchIndex, filters);
    
    // Save to search history
    await this.saveToHistory(query, filters, totalCount);
    
    return {
      results,
      totalCount,
      suggestions,
      facets
    };
  }

  // Build search index from all content
  async buildSearchIndex(): Promise<void> {
    const index: SearchResult[] = [];
    
    try {
      // Removed community search as it's unnecessary for educational platform
      
      // Removed posts loop as community posts are no longer available

      // Removed user search as it's unnecessary for educational platform

      // Get articles from backend API
      const { backendApi } = await import('./backendApi');
      const articlesResponse = await backendApi.getArticles();
      const articles = articlesResponse.data || [];
      
      for (const article of articles) {
        index.push({
          id: article.slug,
          type: 'article',
          title: article.title,
          content: article.content,
          excerpt: this.generateExcerpt(article.content),
          author: article.author || 'Unknown',
          category: article.category || 'General',
          tags: [],
          createdAt: article.published_at,
          updatedAt: article.updated_at,
          relevanceScore: 0,
          url: `/insights/${article.slug}`,
          metadata: {
            readTime: article.read_time,
            published: article.published
          }
        });
      }

      // Get FAQs
      const faqsResponse = await backendApi.getFAQs();
      const faqs = faqsResponse.data || [];
      for (const faq of faqs) {
        index.push({
          id: faq.id.toString(),
          type: 'faq',
          title: faq.question,
          content: faq.answer,
          excerpt: this.generateExcerpt(faq.answer),
          tags: [],
          createdAt: faq.created_at,
          updatedAt: faq.updated_at,
          relevanceScore: 0,
          url: `/faq#${faq.id}`,
          metadata: {
            category: faq.category,
            order: faq.order
          }
        });
      }

      // Save index
      localStorage.setItem(this.SEARCH_INDEX_KEY, JSON.stringify(index));
      
    } catch (error) {
      console.error('Error building search index:', error);
    }
  }

  // Perform actual search
  private async performSearch(
    index: SearchResult[],
    query: string,
    filters: SearchFilters
  ): Promise<SearchResult[]> {
    if (!query.trim()) {
      return index;
    }

    const queryLower = query.toLowerCase();
    const queryWords = queryLower.split(/\s+/).filter(word => word.length > 2);
    
    let results = index.filter(item => {
      // Apply filters first
      if (filters.type && filters.type.length > 0 && !filters.type.includes(item.type)) {
        return false;
      }
      
      if (filters.category && filters.category.length > 0) {
        if (!item.category || !filters.category.includes(item.category)) {
          return false;
        }
      }
      
      if (filters.author && filters.author.length > 0) {
        if (!item.author || !filters.author.includes(item.author)) {
          return false;
        }
      }
      
      if (filters.tags && filters.tags.length > 0) {
        const hasMatchingTag = filters.tags.some(tag => 
          item.tags.some(itemTag => itemTag.toLowerCase().includes(tag.toLowerCase()))
        );
        if (!hasMatchingTag) {
          return false;
        }
      }
      
      if (filters.dateRange) {
        const itemDate = new Date(item.createdAt);
        const fromDate = new Date(filters.dateRange.from);
        const toDate = new Date(filters.dateRange.to);
        
        if (itemDate < fromDate || itemDate > toDate) {
          return false;
        }
      }

      // Apply search query
      const searchText = `${item.title} ${item.content} ${item.tags.join(' ')}`.toLowerCase();
      
      // Exact phrase match (highest priority)
      if (searchText.includes(queryLower)) {
        item.relevanceScore = 100;
        return true;
      }
      
      // Word matches
      const wordMatches = queryWords.filter(word => searchText.includes(word)).length;
      if (wordMatches > 0) {
        item.relevanceScore = wordMatches * 20;
        
        // Boost score for title matches
        if (item.title.toLowerCase().includes(queryLower)) {
          item.relevanceScore += 30;
        }
        
        // Boost score for tag matches
        const tagMatches = item.tags.filter(tag => 
          tag.toLowerCase().includes(queryLower)
        ).length;
        item.relevanceScore += tagMatches * 15;
        
        return true;
      }
      
      return false;
    });

    return results;
  }

  // Sort results
  private sortResults(
    results: SearchResult[],
    sortBy: string,
    sortOrder: string
  ): SearchResult[] {
    return results.sort((a, b) => {
      let comparison = 0;
      
      switch (sortBy) {
        case 'relevance':
          comparison = b.relevanceScore - a.relevanceScore;
          break;
        case 'date':
          comparison = new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime();
          break;
        case 'title':
          comparison = a.title.localeCompare(b.title);
          break;
        case 'author':
          comparison = (a.author || '').localeCompare(b.author || '');
          break;
        default:
          comparison = b.relevanceScore - a.relevanceScore;
      }
      
      return sortOrder === 'asc' ? comparison : -comparison;
    });
  }

  // Generate search suggestions
  private async generateSuggestions(
    query: string,
    filters: SearchFilters
  ): Promise<SearchSuggestion[]> {
    const suggestions: SearchSuggestion[] = [];
    const queryLower = query.toLowerCase();
    
    if (query.length < 2) {
      return suggestions;
    }

    try {
      // Get popular queries from history
      const history = await this.getSearchHistory();
      const popularQueries = history
        .filter(h => h.query.toLowerCase().includes(queryLower))
        .sort((a, b) => b.resultCount - a.resultCount)
        .slice(0, 3)
        .map(h => ({
          text: h.query,
          type: 'query' as const,
          count: h.resultCount
        }));
      
      suggestions.push(...popularQueries);

      // Get matching categories
      // Removed community categories as they are unnecessary for educational platform
      const matchingCategories: any[] = []
      
      suggestions.push(...matchingCategories);

      // Get matching tags
      const index = await this.getSearchIndex();
      const allTags = new Set<string>();
      index.forEach(item => {
        item.tags.forEach(tag => allTags.add(tag));
      });
      
      const matchingTags = Array.from(allTags)
        .filter(tag => tag.toLowerCase().includes(queryLower))
        .slice(0, 3)
        .map(tag => ({
          text: tag,
          type: 'tag' as const
        }));
      
      suggestions.push(...matchingTags);

    } catch (error) {
      console.error('Error generating suggestions:', error);
    }

    return suggestions;
  }

  // Generate search facets
  private async generateFacets(
    index: SearchResult[],
    currentFilters: SearchFilters
  ): Promise<Record<string, Array<{ value: string; count: number }>>> {
    const facets: Record<string, Array<{ value: string; count: number }>> = {
      type: [],
      category: [],
      author: [],
      tags: []
    };

    // Count types
    const typeCounts: Record<string, number> = {};
    const categoryCounts: Record<string, number> = {};
    const authorCounts: Record<string, number> = {};
    const tagCounts: Record<string, number> = {};

    index.forEach(item => {
      // Count types
      typeCounts[item.type] = (typeCounts[item.type] || 0) + 1;
      
      // Count categories
      if (item.category) {
        categoryCounts[item.category] = (categoryCounts[item.category] || 0) + 1;
      }
      
      // Count authors
      if (item.author) {
        authorCounts[item.author] = (authorCounts[item.author] || 0) + 1;
      }
      
      // Count tags
      item.tags.forEach(tag => {
        tagCounts[tag] = (tagCounts[tag] || 0) + 1;
      });
    });

    // Convert to arrays
    facets.type = Object.entries(typeCounts)
      .map(([value, count]) => ({ value, count }))
      .sort((a, b) => b.count - a.count);

    facets.category = Object.entries(categoryCounts)
      .map(([value, count]) => ({ value, count }))
      .sort((a, b) => b.count - a.count);

    facets.author = Object.entries(authorCounts)
      .map(([value, count]) => ({ value, count }))
      .sort((a, b) => b.count - a.count);

    facets.tags = Object.entries(tagCounts)
      .map(([value, count]) => ({ value, count }))
      .sort((a, b) => b.count - a.count)
      .slice(0, 20); // Limit tags to top 20

    return facets;
  }

  // Search history management
  async getSearchHistory(): Promise<SearchHistory[]> {
    const stored = localStorage.getItem(this.SEARCH_HISTORY_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  private async saveToHistory(
    query: string,
    filters: SearchFilters,
    resultCount: number
  ): Promise<void> {
    const history = await this.getSearchHistory();
    
    const newEntry: SearchHistory = {
      id: this.generateId(),
      query,
      filters,
      timestamp: new Date().toISOString(),
      resultCount
    };
    
    // Remove duplicates
    const filteredHistory = history.filter(h => h.query !== query);
    
    // Add new entry at the beginning
    filteredHistory.unshift(newEntry);
    
    // Keep only last 50 searches
    const limitedHistory = filteredHistory.slice(0, 50);
    
    localStorage.setItem(this.SEARCH_HISTORY_KEY, JSON.stringify(limitedHistory));
  }

  async clearSearchHistory(): Promise<void> {
    localStorage.removeItem(this.SEARCH_HISTORY_KEY);
  }

  // Saved searches management
  async getSavedSearches(): Promise<SavedSearch[]> {
    const stored = localStorage.getItem(this.SAVED_SEARCHES_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  async saveSearch(
    name: string,
    query: string,
    filters?: SearchFilters,
    notifications: boolean = false
  ): Promise<SavedSearch> {
    const savedSearches = await this.getSavedSearches();
    
    const savedSearch: SavedSearch = {
      id: this.generateId(),
      name,
      query,
      filters,
      createdAt: new Date().toISOString(),
      lastUsed: new Date().toISOString(),
      notifications
    };
    
    savedSearches.push(savedSearch);
    localStorage.setItem(this.SAVED_SEARCHES_KEY, JSON.stringify(savedSearches));
    
    return savedSearch;
  }

  async deleteSavedSearch(id: string): Promise<void> {
    const savedSearches = await this.getSavedSearches();
    const filtered = savedSearches.filter(s => s.id !== id);
    localStorage.setItem(this.SAVED_SEARCHES_KEY, JSON.stringify(filtered));
  }

  async updateSavedSearchLastUsed(id: string): Promise<void> {
    const savedSearches = await this.getSavedSearches();
    const search = savedSearches.find(s => s.id === id);
    if (search) {
      search.lastUsed = new Date().toISOString();
      localStorage.setItem(this.SAVED_SEARCHES_KEY, JSON.stringify(savedSearches));
    }
  }

  // Helper methods
  private async getSearchIndex(): Promise<SearchResult[]> {
    const stored = localStorage.getItem(this.SEARCH_INDEX_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  private generateExcerpt(content: string, maxLength: number = 150): string {
    if (!content) return '';
    
    const cleanContent = content.replace(/<[^>]*>/g, ''); // Remove HTML tags
    if (cleanContent.length <= maxLength) {
      return cleanContent;
    }
    
    return cleanContent.substring(0, maxLength).trim() + '...';
  }

  private generateId(): string {
    return Math.random().toString(36).substr(2, 9);
  }

  // Initialize search index
  async initialize(): Promise<void> {
    await this.buildSearchIndex();
    
    // Rebuild index every 5 minutes
    setInterval(() => {
      this.buildSearchIndex();
    }, 5 * 60 * 1000);
  }

  // Get search statistics
  async getSearchStats(): Promise<{
    totalIndexedItems: number;
    lastIndexUpdate: string;
    popularSearches: Array<{ query: string; count: number }>;
    searchTypes: Record<string, number>;
  }> {
    const index = await this.getSearchIndex();
    const history = await this.getSearchHistory();
    
    // Count search types
    const searchTypes: Record<string, number> = {};
    index.forEach(item => {
      searchTypes[item.type] = (searchTypes[item.type] || 0) + 1;
    });
    
    // Get popular searches
    const searchCounts: Record<string, number> = {};
    history.forEach(h => {
      searchCounts[h.query] = (searchCounts[h.query] || 0) + 1;
    });
    
    const popularSearches = Object.entries(searchCounts)
      .map(([query, count]) => ({ query, count }))
      .sort((a, b) => b.count - a.count)
      .slice(0, 10);
    
    return {
      totalIndexedItems: index.length,
      lastIndexUpdate: new Date().toISOString(),
      popularSearches,
      searchTypes
    };
  }

  // Search with highlighting
  async searchWithHighlighting(options: SearchOptions): Promise<{
    results: Array<SearchResult & { highlightedTitle: string; highlightedContent: string }>;
    totalCount: number;
    suggestions: SearchSuggestion[];
    facets: Record<string, Array<{ value: string; count: number }>>;
  }> {
    const result = await this.search(options);
    
    const highlightedResults = result.results.map(item => ({
      ...item,
      highlightedTitle: this.highlightText(item.title, options.query),
      highlightedContent: this.highlightText(item.excerpt, options.query)
    }));
    
    return {
      ...result,
      results: highlightedResults
    };
  }

  // Highlight search terms in text
  private highlightText(text: string, query: string): string {
    if (!query.trim()) return text;
    
    const queryWords = query.toLowerCase().split(/\s+/).filter(word => word.length > 2);
    let highlightedText = text;
    
    queryWords.forEach(word => {
      const regex = new RegExp(`(${word})`, 'gi');
      highlightedText = highlightedText.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>');
    });
    
    return highlightedText;
  }
}

export const searchService = new SearchService();
