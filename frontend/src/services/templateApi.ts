// Template API Service - Real Laravel API Integration
import { apiClient } from './apiClient'

export interface Template {
  id: number
  name: string
  slug: string
  description: string
  icon: string
  category: string
  difficulty: 'beginner' | 'intermediate' | 'advanced'
  estimated_time: string
  team_size: string
  budget_range: string
  features: string[]
  architecture: string
  implementation_steps: string[]
  requirements: {
    technical: string[]
    team: string[]
  }
  metadata?: any
  version: string
  is_active: boolean
  is_featured: boolean
  download_count: number
  rating: number
  review_count: number
  created_by: number
  updated_by: number
  created_at: string
  updated_at: string
  creator?: {
    id: number
    name: string
    email: string
  }
  reviews?: TemplateReview[]
}

export interface TemplateReview {
  id: number
  template_id: number
  user_id: number
  rating: number
  review?: string
  pros?: string[]
  cons?: string[]
  is_verified_download: boolean
  is_helpful: boolean
  helpful_count: number
  created_at: string
  updated_at: string
  user?: {
    id: number
    name: string
    email: string
  }
}

export interface TemplateCategory {
  name: string
  slug: string
  icon: string
  count: number
}

export interface TemplateDownload {
  success: boolean
  data: any
  download_token: string
  message: string
}

export interface TemplatesResponse {
  success: boolean
  data: Template[]
  pagination: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
  message: string
}

export interface TemplateResponse {
  success: boolean
  data: Template
  message: string
}

export interface CategoriesResponse {
  success: boolean
  data: TemplateCategory[]
  message: string
}

export interface ReviewsResponse {
  success: boolean
  data: TemplateReview[]
  pagination: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
  template: {
    id: number
    name: string
    slug: string
    rating: number
    review_count: number
  }
  message: string
}

class TemplateApiService {
  private baseUrl = 'http://127.0.0.1:8000/api/v1/templates'

  private async makeRequest(endpoint: string, options: RequestInit = {}): Promise<any> {
    // Use shared apiClient for consistent headers, auth and JSON handling
    return apiClient.request(`${this.baseUrl}${endpoint}`, options)
  }

  /**
   * Get all templates with filtering and pagination
   */
  async getTemplates(params?: {
    category?: string
    difficulty?: string
    featured?: boolean
    search?: string
    sort_by?: string
    sort_order?: 'asc' | 'desc'
    per_page?: number
    page?: number
  }): Promise<TemplatesResponse> {
    const queryParams = new URLSearchParams()
    if (params) {
      Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          queryParams.append(key, value.toString())
        }
      })
    }
    const queryString = queryParams.toString()
    const endpoint = queryString ? `?${queryString}` : ''
    return this.makeRequest(endpoint)
  }

  /**
   * Get a specific template by slug
   */
  async getTemplate(slug: string): Promise<TemplateResponse> {
    return this.makeRequest(`/${slug}`)
  }

  /**
   * Get template categories
   */
  async getCategories(): Promise<CategoriesResponse> {
    return this.makeRequest('/categories')
  }

  /**
   * Get popular templates
   */
  async getPopular(): Promise<TemplateResponse> {
    return this.makeRequest('/popular')
  }

  /**
   * Get featured templates
   */
  async getFeatured(): Promise<TemplateResponse> {
    return this.makeRequest('/featured')
  }

  /**
   * Download a template (requires authentication)
   */
  async downloadTemplate(slug: string): Promise<TemplateDownload> {
    return this.makeRequest(`/${slug}/download`, {
      method: 'POST'
    })
  }

  /**
   * Create a new template (requires authentication)
   */
  async createTemplate(templateData: {
    name: string
    description: string
    category: string
    difficulty: 'beginner' | 'intermediate' | 'advanced'
    estimated_time: string
    team_size: string
    budget_range: string
    features: string[]
    architecture: string
    implementation_steps: string[]
    requirements: {
      technical: string[]
      team: string[]
    }
    icon?: string
    is_featured?: boolean
  }): Promise<TemplateResponse> {
    return this.makeRequest('', {
      method: 'POST',
      body: JSON.stringify(templateData)
    })
  }

  /**
   * Get reviews for a template
   */
  async getTemplateReviews(slug: string, params?: {
    rating?: number
    verified?: boolean
    sort_by?: string
    sort_order?: 'asc' | 'desc'
    per_page?: number
    page?: number
  }): Promise<ReviewsResponse> {
    const queryParams = new URLSearchParams()
    if (params) {
      Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          queryParams.append(key, value.toString())
        }
      })
    }
    const queryString = queryParams.toString()
    const endpoint = `/${slug}/reviews${queryString ? `?${queryString}` : ''}`
    return this.makeRequest(endpoint)
  }

  /**
   * Create a review for a template (requires authentication)
   */
  async createReview(slug: string, reviewData: {
    rating: number
    review?: string
    pros?: string[]
    cons?: string[]
  }): Promise<{ success: boolean; data: TemplateReview; message: string }> {
    return this.makeRequest(`/${slug}/reviews`, {
      method: 'POST',
      body: JSON.stringify(reviewData)
    })
  }

  /**
   * Update a review (requires authentication)
   */
  async updateReview(slug: string, reviewId: number, reviewData: {
    rating?: number
    review?: string
    pros?: string[]
    cons?: string[]
  }): Promise<{ success: boolean; data: TemplateReview; message: string }> {
    return this.makeRequest(`/${slug}/reviews/${reviewId}`, {
      method: 'PUT',
      body: JSON.stringify(reviewData)
    })
  }

  /**
   * Delete a review (requires authentication)
   */
  async deleteReview(slug: string, reviewId: number): Promise<{ success: boolean; message: string }> {
    return this.makeRequest(`/${slug}/reviews/${reviewId}`, {
      method: 'DELETE'
    })
  }

  /**
   * Mark a review as helpful (requires authentication)
   */
  async markReviewHelpful(slug: string, reviewId: number): Promise<{ success: boolean; data: { helpful_count: number }; message: string }> {
    return this.makeRequest(`/${slug}/reviews/${reviewId}/helpful`, {
      method: 'POST'
    })
  }

  /**
   * Download template as JSON file
   */
  async downloadTemplateAsFile(template: Template): Promise<void> {
    try {
      const downloadResponse = await this.downloadTemplate(template.slug)
      
      if (downloadResponse.success) {
        // Create and download JSON file
        const dataStr = JSON.stringify(downloadResponse.data, null, 2)
        const dataUri = 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr)
        
        const exportFileDefaultName = `${template.slug}-template.json`
        
        const linkElement = document.createElement('a')
        linkElement.setAttribute('href', dataUri)
        linkElement.setAttribute('download', exportFileDefaultName)
        linkElement.click()
        
        // Show success message
        console.log(`Template "${template.name}" downloaded successfully!`)
      } else {
        throw new Error(downloadResponse.message || 'Download failed')
      }
    } catch (error) {
      console.error('Failed to download template:', error)
      throw error
    }
  }

  /**
   * Get difficulty badge color
   */
  getDifficultyColor(difficulty: string): string {
    switch (difficulty) {
      case 'beginner':
        return 'green'
      case 'intermediate':
        return 'yellow'
      case 'advanced':
        return 'red'
      default:
        return 'gray'
    }
  }

  /**
   * Get category icon
   */
  getCategoryIcon(category: string): string {
    switch (category) {
      case 'AI & Machine Learning':
        return '🤖'
      case 'Data Analytics':
        return '📊'
      case 'Cloud Infrastructure':
        return '☁️'
      case 'Enterprise Solutions':
        return '🏢'
      case 'Startup Solutions':
        return '🚀'
      case 'IoT & Edge Computing':
        return '🌐'
      default:
        return '📋'
    }
  }

  /**
   * Format rating with stars
   */
  formatRating(rating: number): string {
    const fullStars = Math.floor(rating)
    const hasHalfStar = rating % 1 >= 0.5
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0)
    
    return '★'.repeat(fullStars) + 
           (hasHalfStar ? '☆' : '') + 
           '☆'.repeat(emptyStars)
  }
}

export const templateApi = new TemplateApiService()
