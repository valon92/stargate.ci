// Route Guards for Authentication
import { authService } from '../services/authService'

export const adminGuard = (to: any, from: any, next: any) => {
  if (authService.isAuthenticated()) {
    // User is authenticated, allow access
    next()
  } else {
    // User is not authenticated, redirect to login
    next('/admin/login')
  }
}

export const guestGuard = (to: any, from: any, next: any) => {
  if (authService.isAuthenticated()) {
    // User is already authenticated, redirect to admin panel
    next('/admin')
  } else {
    // User is not authenticated, allow access to login page
    next()
  }
}

export const userGuard = (to: any, from: any, next: any) => {
  if (authService.isAuthenticated()) {
    // User is authenticated, allow access to dashboard
    next()
  } else {
    // User is not authenticated, redirect to login
    next('/admin/login')
  }
}
