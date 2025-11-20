import { apiClient } from './apiClient'

export interface User {
  id: number
  name: string
  email: string
  email_verified_at?: string
  subscriber_id?: number // Subscriber ID for interactions/comments
  created_at: string
  updated_at: string
}

export interface AuthResponse {
  success: boolean
  message: string
  data: {
    user: User
    subscriber_id?: number // Subscriber ID for interactions/comments
    token: string
    token_type: string
  }
}

export interface LoginRequest {
  email: string
  password: string
}

export interface RegisterRequest {
  name: string
  email: string
  password: string
  password_confirmation: string
}

class AuthService {
  private baseUrl = '/api/v1/auth'

  async login(credentials: LoginRequest): Promise<AuthResponse> {
    return apiClient.post(`${this.baseUrl}/login`, credentials)
  }

  async register(userData: RegisterRequest): Promise<AuthResponse> {
    return apiClient.post(`${this.baseUrl}/register`, userData)
  }

  async logout(): Promise<{ success: boolean; message: string }> {
    return apiClient.post(`${this.baseUrl}/logout`)
  }

  async getMe(): Promise<{ success: boolean; data: User }> {
    return apiClient.get(`${this.baseUrl}/me`)
  }

  // Token management
  setToken(token: string): void {
    localStorage.setItem('auth_token', token)
  }

  getToken(): string | null {
    return localStorage.getItem('auth_token')
  }

  removeToken(): void {
    localStorage.removeItem('auth_token')
  }

  isAuthenticated(): boolean {
    return !!this.getToken()
  }

  // User session management
  setUser(user: User): void {
    localStorage.setItem('user', JSON.stringify(user))
  }

  getUser(): User | null {
    const userStr = localStorage.getItem('user')
    return userStr ? JSON.parse(userStr) : null
  }

  removeUser(): void {
    localStorage.removeItem('user')
  }

  clearAuth(): void {
    this.removeToken()
    this.removeUser()
  }
}

export const authService = new AuthService()
