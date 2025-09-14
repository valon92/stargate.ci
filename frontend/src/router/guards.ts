// Route Guards for Authentication
import { authService } from '../services/authService'

export const adminGuard = (to: any, from: any, next: any) => {
  if (authService.isAuthenticated()) {
    // Admin is authenticated, allow access
    next()
  } else {
    // Admin is not authenticated, redirect to unified login
    next('/login')
  }
}

export const guestGuard = (to: any, from: any, next: any) => {
  if (authService.isAuthenticated()) {
    // Admin is already authenticated, redirect to admin panel
    next('/admin')
  } else if (authService.isUserAuthenticated()) {
    // User is already authenticated, redirect to dashboard
    next('/dashboard')
  } else {
    // No one is authenticated, allow access to login page
    next()
  }
}

export const userGuard = (to: any, from: any, next: any) => {
  // Check for both user and admin authentication
  if (authService.isUserAuthenticated() || authService.isAuthenticated()) {
    // User is authenticated, allow access to dashboard
    next()
  } else {
    // User is not authenticated, redirect to unified login
    next('/login')
  }
}
