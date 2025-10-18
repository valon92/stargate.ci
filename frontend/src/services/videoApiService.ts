import { apiClient } from './apiClient'

export interface Video {
  id: number
  content_id: string
  title: string
  description: string
  youtube_id: string
  youtube_url: string
  content_type: string
  likes_count: number
  comments_count: number
  shares_count: number
  views_count: number
  is_active: boolean
  metadata: any
  created_at: string
  updated_at: string
}

export interface VideoInteraction {
  video_content_id: string
  subscriber_id?: number
  session_id?: string
  interaction_type: 'like' | 'share' | 'view'
  platform?: string
  metadata?: any
}

export interface VideoComment {
  id: number
  content: string
  author_name: string
  author_avatar: string
  likes_count: number
  is_pinned: boolean
  is_edited: boolean
  created_at: string
  edited_at?: string
  replies: VideoComment[]
}

export interface Subscriber {
  id: number
  username: string
  email: string
  first_name?: string
  last_name?: string
  country?: string
  profession?: string
  company?: string
  interests: string[]
  avatar: string
  is_active: boolean
  email_notifications: boolean
  subscribed_at: string
  last_activity_at?: string
  preferences: any
}

export interface VideoStats {
  total_videos: number
  total_likes: number
  total_comments: number
  total_shares: number
  total_views: number
  videos_by_type: Record<string, number>
}

export interface SubscriberStats {
  total_subscribers: number
  subscribers_this_month: number
  countries_count: number
  top_countries: Record<string, number>
  professions_count: number
}

class VideoApiService {
  private baseUrl = import.meta.env.VITE_API_BASE_URL || '/api/v1'

  // Video methods
  async getVideos(type?: string): Promise<{ success: boolean; data: Video[]; message: string }> {
    const params = type ? `?type=${type}` : ''
    return apiClient.get(`${this.baseUrl}/videos${params}`)
  }

  async getVideo(contentId: string): Promise<{ success: boolean; data: Video; message: string }> {
    return apiClient.get(`${this.baseUrl}/videos/${contentId}`)
  }

  async createVideo(videoData: Partial<Video>): Promise<{ success: boolean; data: Video; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos`, videoData)
  }

  async updateVideoCounts(contentId: string): Promise<{ success: boolean; data: Video; message: string }> {
    return apiClient.put(`${this.baseUrl}/videos/${contentId}/counts`)
  }

  async getVideoStats(): Promise<{ success: boolean; data: VideoStats; message: string }> {
    return apiClient.get(`${this.baseUrl}/videos/stats/overview`)
  }

  // Video interaction methods
  async toggleLike(videoContentId: string, subscriberId?: number, sessionId?: string): Promise<{ success: boolean; data: { is_liked: boolean; likes_count: number }; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos/interactions/like`, {
      video_content_id: videoContentId,
      subscriber_id: subscriberId,
      session_id: sessionId
    })
  }

  async addShare(videoContentId: string, platform: string, subscriberId?: number, sessionId?: string): Promise<{ success: boolean; data: { shares_count: number }; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos/interactions/share`, {
      video_content_id: videoContentId,
      platform,
      subscriber_id: subscriberId,
      session_id: sessionId
    })
  }

  async addView(videoContentId: string, subscriberId?: number, sessionId?: string): Promise<{ success: boolean; data: { views_count: number }; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos/interactions/view`, {
      video_content_id: videoContentId,
      subscriber_id: subscriberId,
      session_id: sessionId
    })
  }

  async checkInteraction(videoContentId: string, interactionType: string, subscriberId?: number, sessionId?: string): Promise<{ success: boolean; data: { has_interacted: boolean }; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos/interactions/check`, {
      video_content_id: videoContentId,
      interaction_type: interactionType,
      subscriber_id: subscriberId,
      session_id: sessionId
    })
  }

  async getUserInteractions(videoContentId: string, subscriberId?: number, sessionId?: string): Promise<{ success: boolean; data: { interactions: string[]; is_liked: boolean; has_shared: boolean; has_viewed: boolean }; message: string }> {
    const params = new URLSearchParams({
      video_content_id: videoContentId
    })
    if (subscriberId) params.append('subscriber_id', subscriberId.toString())
    if (sessionId) params.append('session_id', sessionId)
    
    return apiClient.get(`${this.baseUrl}/videos/interactions/user?${params.toString()}`)
  }

  // Video comment methods
  async getComments(videoContentId: string): Promise<{ success: boolean; data: VideoComment[]; message: string }> {
    return apiClient.get(`${this.baseUrl}/videos/comments?video_content_id=${videoContentId}`)
  }

  async addComment(videoContentId: string, content: string, subscriberId?: number, sessionId?: string, parentId?: number): Promise<{ success: boolean; data: VideoComment; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos/comments`, {
      video_content_id: videoContentId,
      content,
      subscriber_id: subscriberId,
      session_id: sessionId,
      parent_id: parentId
    })
  }

  async updateComment(commentId: number, content: string): Promise<{ success: boolean; data: VideoComment; message: string }> {
    return apiClient.put(`${this.baseUrl}/videos/comments/${commentId}`, { content })
  }

  async deleteComment(commentId: number): Promise<{ success: boolean; message: string }> {
    return apiClient.delete(`${this.baseUrl}/videos/comments/${commentId}`)
  }

  async toggleCommentLike(commentId: number, subscriberId?: number, sessionId?: string): Promise<{ success: boolean; data: { is_liked: boolean; likes_count: number }; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos/comments/like`, {
      comment_id: commentId,
      subscriber_id: subscriberId,
      session_id: sessionId
    })
  }

  async toggleCommentPin(commentId: number): Promise<{ success: boolean; data: { is_pinned: boolean }; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos/comments/pin/${commentId}`)
  }

  async checkCommentLike(commentId: number, subscriberId?: number, sessionId?: string): Promise<{ success: boolean; data: { is_liked: boolean }; message: string }> {
    return apiClient.post(`${this.baseUrl}/videos/comments/check-like`, {
      comment_id: commentId,
      subscriber_id: subscriberId,
      session_id: sessionId
    })
  }

  // Subscriber methods
  async getSubscribers(): Promise<{ success: boolean; data: Subscriber[]; pagination: any; message: string }> {
    return apiClient.get(`${this.baseUrl}/subscribers`)
  }

  async createSubscriber(subscriberData: Partial<Subscriber>): Promise<{ success: boolean; data: Subscriber; message: string }> {
    return apiClient.post(`${this.baseUrl}/subscribers`, subscriberData)
  }

  async getSubscriber(id: number): Promise<{ success: boolean; data: Subscriber; message: string }> {
    return apiClient.get(`${this.baseUrl}/subscribers/${id}`)
  }

  async updateSubscriber(id: number, subscriberData: Partial<Subscriber>): Promise<{ success: boolean; data: Subscriber; message: string }> {
    return apiClient.put(`${this.baseUrl}/subscribers/${id}`, subscriberData)
  }

  async deleteSubscriber(id: number): Promise<{ success: boolean; message: string }> {
    return apiClient.delete(`${this.baseUrl}/subscribers/${id}`)
  }

  async getSubscriberStats(): Promise<{ success: boolean; data: SubscriberStats; message: string }> {
    return apiClient.get(`${this.baseUrl}/subscribers/stats/overview`)
  }

  async updateSubscriberActivity(id: number): Promise<{ success: boolean; data: Subscriber; message: string }> {
    return apiClient.put(`${this.baseUrl}/subscribers/${id}/activity`)
  }

  // Helper methods
  getSessionId(): string {
    let sessionId = localStorage.getItem('stargate_session_id')
    if (!sessionId) {
      sessionId = `guest_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`
      localStorage.setItem('stargate_session_id', sessionId)
    }
    console.log('getSessionId - session ID:', sessionId)
    return sessionId
  }

  getSubscriberId(): number | undefined {
    const subscribers = JSON.parse(localStorage.getItem('stargate_subscribers') || '[]')
    console.log('getSubscriberId - subscribers:', subscribers)
    if (subscribers.length > 0) {
      // Use the first subscriber's ID, but ensure it's a valid number and exists in database
      const subscriberId = subscribers[0].id
      console.log('getSubscriberId - found subscriber ID:', subscriberId)
      
      // Check if ID is valid (should be between 1-7 based on current database)
      if (typeof subscriberId === 'number' && subscriberId >= 1 && subscriberId <= 7) {
        return subscriberId
      } else {
        console.log('getSubscriberId - invalid subscriber ID, cleaning localStorage and defaulting to 1')
        // Clean up invalid subscriber data
        localStorage.removeItem('stargate_subscribers')
        return 1 // Default to ID 1 if invalid
      }
    }
    console.log('getSubscriberId - no subscribers, defaulting to 1')
    return 1 // Default to ID 1 for guest users
  }

  // Helper method to create a valid subscriber in localStorage
  createValidSubscriber(): void {
    const validSubscriber = {
      id: 1,
      username: 'ai_enthusiast_2024',
      email: 'ai.enthusiast@example.com',
      status: 'active',
      subscribed_at: new Date().toISOString()
    }
    localStorage.setItem('stargate_subscribers', JSON.stringify([validSubscriber]))
    console.log('Created valid subscriber in localStorage:', validSubscriber)
  }
}

export const videoApiService = new VideoApiService()
