import { defineStore } from 'pinia'
import { ref, computed, readonly } from 'vue'
import { backendApi, type ApiResponse } from '@/services/backendApi'

export interface User {
  id: number
  name: string
  email: string
  email_verified_at?: string
  created_at: string
}

export interface AuthState {
  user: User | null
  token: string | null
  isLoading: boolean
  error: string | null
}

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref<User | null>(null)
  const token = ref<string | null>(null)
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  // Getters
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isAdmin = computed(() => {
    // This will be enhanced when we have role system
    return user.value?.email === 'admin@stargate.ci'
  })

  // Actions
  const initializeAuth = async () => {
    // Check if token exists in localStorage
    const storedToken = localStorage.getItem('auth_token')
    if (storedToken) {
      token.value = storedToken
      backendApi.setAuthToken(storedToken)
      
      // Try to get user data
      try {
        const response = await backendApi.getMe()
        if (response.status === 'success' && response.data.user) {
          user.value = response.data.user
        } else {
          // Token is invalid, clear it
          clearAuth()
        }
      } catch (err) {
        // Token is invalid, clear it
        clearAuth()
      }
    }
  }

  const login = async (credentials: { email: string; password: string }) => {
    isLoading.value = true
    error.value = null

    try {
      const response = await backendApi.login(credentials)
      
      if (response.status === 'success') {
        user.value = response.data.user
        token.value = response.data.token
        
        // Token is already set by backendApi.login()
        return { success: true, data: response.data }
      } else {
        error.value = response.message || 'Login failed'
        return { success: false, message: error.value }
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Login failed'
      return { success: false, message: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const register = async (userData: {
    name: string
    email: string
    password: string
    password_confirmation: string
  }) => {
    isLoading.value = true
    error.value = null

    try {
      const response = await backendApi.register(userData)
      
      if (response.status === 'success') {
        user.value = response.data.user
        token.value = response.data.token
        
        // Token is already set by backendApi.register()
        return { success: true, data: response.data }
      } else {
        error.value = response.message || 'Registration failed'
        return { success: false, message: error.value }
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Registration failed'
      return { success: false, message: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const logout = async () => {
    isLoading.value = true
    
    try {
      await backendApi.logout()
    } catch (err) {
      console.warn('Logout request failed:', err)
    } finally {
      clearAuth()
      isLoading.value = false
    }
  }

  const clearAuth = () => {
    user.value = null
    token.value = null
    error.value = null
    backendApi.setAuthToken(null)
  }

  const updateUser = (userData: Partial<User>) => {
    if (user.value) {
      user.value = { ...user.value, ...userData }
    }
  }

  const clearError = () => {
    error.value = null
  }

  // Auto-initialize on store creation
  initializeAuth()

  return {
    // State
    user: readonly(user),
    token: readonly(token),
    isLoading: readonly(isLoading),
    error: readonly(error),
    
    // Getters
    isAuthenticated,
    isAdmin,
    
    // Actions
    initializeAuth,
    login,
    register,
    logout,
    clearAuth,
    updateUser,
    clearError
  }
})
