import { apiClient } from './apiClient'

export interface CommunityPost {
  id: number
  subscriber_id: number
  title: string
  content: string
  images?: string[]
  videos?: string[]
  media_type?: 'image' | 'video' | 'mixed' | null
  category: string
  tags?: string[]
  is_pinned: boolean
  is_locked: boolean
  views_count: number
  likes_count: number
  comments_count: number
  shares_count: number
  status: string
  created_at: string
  updated_at: string
  subscriber?: {
    id: number
    username: string
    first_name?: string
    last_name?: string
    avatar?: string
  }
  comments?: CommunityComment[]
  is_liked?: boolean
}

export interface CommunityComment {
  id: number
  community_post_id: number
  subscriber_id: number
  parent_id?: number
  content: string
  likes_count: number
  is_pinned: boolean
  status: string
  created_at: string
  updated_at: string
  subscriber?: {
    id: number
    username: string
    first_name?: string
    last_name?: string
    avatar?: string
  }
  replies?: CommunityComment[]
  is_liked?: boolean
}

export interface CreatePostRequest {
  title: string
  content: string
  category: string
  tags?: string[]
  images?: string[]
  videos?: string[]
  media_type?: 'image' | 'video' | 'mixed'
}

export interface CreateCommentRequest {
  content: string
  parent_id?: number
}

class CommunityService {
  private baseUrl = '/api/v1/community'

  async getPosts(params?: {
    category?: string
    search?: string
    per_page?: number
    page?: number
  }): Promise<{
    success: boolean
    data: CommunityPost[]
    pagination: {
      current_page: number
      last_page: number
      per_page: number
      total: number
    }
  }> {
    const queryParams = new URLSearchParams()
    if (params?.category) queryParams.append('category', params.category)
    if (params?.search) queryParams.append('search', params.search)
    if (params?.per_page) queryParams.append('per_page', params.per_page.toString())
    if (params?.page) queryParams.append('page', params.page.toString())

    const url = `${this.baseUrl}/posts${queryParams.toString() ? '?' + queryParams.toString() : ''}`
    return apiClient.get(url)
  }

  async getPost(id: number): Promise<{ success: boolean; data: CommunityPost }> {
    return apiClient.get(`${this.baseUrl}/posts/${id}`)
  }

  async createPost(data: CreatePostRequest): Promise<{ success: boolean; data: CommunityPost; message: string }> {
    return apiClient.post(`${this.baseUrl}/posts`, data)
  }

  async updatePost(id: number, data: Partial<CreatePostRequest>): Promise<{ success: boolean; data: CommunityPost; message: string }> {
    return apiClient.put(`${this.baseUrl}/posts/${id}`, data)
  }

  async deletePost(id: number): Promise<{ success: boolean; message: string }> {
    return apiClient.delete(`${this.baseUrl}/posts/${id}`)
  }

  async likePost(id: number): Promise<{ success: boolean; data: { is_liked: boolean; likes_count: number }; message: string }> {
    return apiClient.post(`${this.baseUrl}/posts/${id}/like`)
  }

  async addComment(postId: number, data: CreateCommentRequest): Promise<{ success: boolean; data: CommunityComment; message: string }> {
    return apiClient.post(`${this.baseUrl}/posts/${postId}/comments`, data)
  }

  async sharePost(id: number): Promise<{ success: boolean; data: { shares_count: number }; message: string }> {
    return apiClient.post(`${this.baseUrl}/posts/${id}/share`)
  }

  async likeComment(commentId: number): Promise<{ success: boolean; data: { is_liked: boolean; likes_count: number }; message: string }> {
    return apiClient.post(`${this.baseUrl}/comments/${commentId}/like`)
  }

  async getCategories(): Promise<{ success: boolean; data: Array<{ id: string; name: string; icon: string }> }> {
    return apiClient.get(`${this.baseUrl}/categories`)
  }
}

export const communityService = new CommunityService()

