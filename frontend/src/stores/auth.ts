import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService, type User } from '../services/authService'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref<User | null>(null)
  const token = ref<string | null>(null)
  const isInitialized = ref(false)

  // Getters
  const isAuthenticated = computed(() => {
    return !!token.value && !!user.value
  })

  const currentUser = computed(() => user.value)

  // Actions
  const initialize = () => {
    if (!isInitialized.value) {
      // Load from localStorage on app start
      const storedToken = authService.getToken()
      const storedUser = authService.getUser()
      
      if (storedToken && storedUser) {
        token.value = storedToken
        user.value = storedUser
      }
      
      isInitialized.value = true
    }
  }

  const login = (userData: User, authToken: string) => {
    user.value = userData
    token.value = authToken
    
    // Store in localStorage
    authService.setUser(userData)
    authService.setToken(authToken)
    
    // Dispatch event for other components
    window.dispatchEvent(new CustomEvent('auth-changed'))
  }

  const logout = async () => {
    try {
      // Call logout API
      await authService.logout()
    } catch (error) {
      console.error('Logout API error:', error)
    } finally {
      // Clear state
      user.value = null
      token.value = null
      
      // Clear localStorage
      authService.clearAuth()
      
      // Dispatch event for other components
      window.dispatchEvent(new CustomEvent('auth-changed'))
    }
  }

  const updateUser = (userData: User) => {
    user.value = userData
    authService.setUser(userData)
    window.dispatchEvent(new CustomEvent('auth-changed'))
  }

  return {
    // State
    user,
    token,
    isInitialized,
    
    // Getters
    isAuthenticated,
    currentUser,
    
    // Actions
    initialize,
    login,
    logout,
    updateUser
  }
})
