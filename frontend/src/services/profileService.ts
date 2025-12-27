import { apiClient } from './apiClient'

export interface ProfileStats {
  total_comments: number
  total_likes: number
  total_shares: number
  articles_read: number
  events_registered: number
  last_activity: string
}

export interface ProfileData {
  id: number
  name: string
  email: string
  username?: string
  first_name?: string
  last_name?: string
  country?: string
  profession?: string
  company?: string
  avatar?: string
  bio?: string
  interests?: string[]
  email_notifications?: boolean
  created_at: string
  updated_at: string
  subscriber_id?: number
  stats?: ProfileStats
}

export interface UpdateProfileRequest {
  name?: string
  first_name?: string
  last_name?: string
  country?: string
  profession?: string
  company?: string
  bio?: string
  interests?: string[]
  email_notifications?: boolean
}

class ProfileService {
  private baseUrl = '/api/v1'

  async getProfile(): Promise<{ success: boolean; data: ProfileData }> {
    return apiClient.get(`${this.baseUrl}/profile`)
  }

  async updateProfile(data: UpdateProfileRequest): Promise<{ success: boolean; data: ProfileData; message: string }> {
    return apiClient.put(`${this.baseUrl}/profile`, data)
  }

  async getStats(): Promise<{ success: boolean; data: ProfileStats }> {
    return apiClient.get(`${this.baseUrl}/profile/stats`)
  }

  async updatePassword(currentPassword: string, newPassword: string): Promise<{ success: boolean; message: string }> {
    return apiClient.put(`${this.baseUrl}/profile/password`, {
      current_password: currentPassword,
      new_password: newPassword
    })
  }
}

export const profileService = new ProfileService()

