import { apiClient } from './apiClient'

export interface JobPost {
  id: number
  company_id: number
  title: string
  description: string
  requirements?: string
  location?: string
  job_type: string
  category: string
  experience_level?: string
  salary_range?: string
  currency: string
  skills?: string[]
  benefits?: string[]
  application_email?: string
  application_url?: string
  application_deadline?: string
  is_featured: boolean
  is_active: boolean
  views_count: number
  applications_count: number
  status: string
  created_at: string
  updated_at: string
  company?: {
    id: number
    username: string
    first_name?: string
    last_name?: string
    company?: string
    avatar?: string
  }
}

export interface CreateJobRequest {
  title: string
  description: string
  requirements?: string
  location?: string
  job_type: string
  category: string
  experience_level?: string
  salary_range?: string
  currency?: string
  skills?: string[]
  benefits?: string[]
  application_email?: string
  application_url?: string
  application_deadline?: string
}

class JobService {
  private baseUrl = '/api/v1/jobs'

  async getJobs(params?: {
    category?: string
    job_type?: string
    experience_level?: string
    location?: string
    search?: string
    per_page?: number
    page?: number
  }): Promise<{
    success: boolean
    data: JobPost[]
    pagination: {
      current_page: number
      last_page: number
      per_page: number
      total: number
    }
  }> {
    const queryParams = new URLSearchParams()
    if (params?.category) queryParams.append('category', params.category)
    if (params?.job_type) queryParams.append('job_type', params.job_type)
    if (params?.experience_level) queryParams.append('experience_level', params.experience_level)
    if (params?.location) queryParams.append('location', params.location)
    if (params?.search) queryParams.append('search', params.search)
    if (params?.per_page) queryParams.append('per_page', params.per_page.toString())
    if (params?.page) queryParams.append('page', params.page.toString())

    const url = `${this.baseUrl}${queryParams.toString() ? '?' + queryParams.toString() : ''}`
    return apiClient.get(url)
  }

  async getJob(id: number): Promise<{ success: boolean; data: JobPost }> {
    return apiClient.get(`${this.baseUrl}/${id}`)
  }

  async createJob(data: CreateJobRequest): Promise<{ success: boolean; data: JobPost; message: string }> {
    return apiClient.post(`${this.baseUrl}`, data)
  }

  async updateJob(id: number, data: Partial<CreateJobRequest>): Promise<{ success: boolean; data: JobPost; message: string }> {
    return apiClient.put(`${this.baseUrl}/${id}`, data)
  }

  async deleteJob(id: number): Promise<{ success: boolean; message: string }> {
    return apiClient.delete(`${this.baseUrl}/${id}`)
  }

  async getCategories(): Promise<{ success: boolean; data: Array<{ id: string; name: string; icon: string }> }> {
    return apiClient.get(`${this.baseUrl}/categories`)
  }

  async getJobTypes(): Promise<{ success: boolean; data: Array<{ id: string; name: string }> }> {
    return apiClient.get(`${this.baseUrl}/types`)
  }

  async getExperienceLevels(): Promise<{ success: boolean; data: Array<{ id: string; name: string }> }> {
    return apiClient.get(`${this.baseUrl}/experience-levels`)
  }
}

export const jobService = new JobService()

