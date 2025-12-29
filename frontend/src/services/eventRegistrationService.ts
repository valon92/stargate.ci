import { apiClient } from './apiClient'

export interface EventRegistration {
  id: number
  event_id: number
  email: string
  name?: string
  phone?: string
  status: 'registered' | 'confirmed' | 'cancelled'
  preferences?: any
  registered_at: string
  reminder_sent_at?: string
  confirmed_at?: string
  created_at: string
  updated_at: string
}

export interface EventRegistrationRequest {
  event_id: number
  email: string
  name?: string
  phone?: string
  timezone?: string
  notes?: string
  preferences?: {
    email_notifications?: boolean
    sms_notifications?: boolean
    [key: string]: any
  }
}

export interface EventRegistrationResponse {
  success: boolean
  message: string
  data?: {
    registration_id: number
    event: any
    registered_at: string
  }
  errors?: any
}

export interface EventStatsResponse {
  success: boolean
  data?: {
    total_registrations: number
    confirmed_registrations: number
    pending_registrations: number
    reminders_sent: number
    event: any
  }
}

export interface CheckRegistrationResponse {
  success: boolean
  data?: {
    is_registered: boolean
    registration?: EventRegistration
    status?: string
  }
}

export interface UserEventsResponse {
  success: boolean
  data?: Array<{
    registration: EventRegistration
    event: any
    days_until_event: number
  }>
}

class EventRegistrationService {
  private baseUrl = '/api/v1/events'

  /**
   * Register for an event
   */
  async registerForEvent(registrationData: EventRegistrationRequest): Promise<EventRegistrationResponse> {
    try {
      const response = await apiClient.post(`${this.baseUrl}/register`, registrationData)
      return response as EventRegistrationResponse
    } catch (error: any) {
      console.error('Event registration failed:', error)
      
      // Extract error message from responseData if available
      const errorData = error.responseData
      const errorMessage = errorData?.message || error.message || 'Registration failed'
      
      return {
        success: false,
        message: errorMessage,
        errors: errorData?.errors
      }
    }
  }

  /**
   * Check if user is registered for an event
   */
  async checkRegistration(eventId: number, email: string): Promise<CheckRegistrationResponse> {
    try {
      const response = await apiClient.get(`${this.baseUrl}/check-registration?event_id=${eventId}&email=${encodeURIComponent(email)}`)
      return response.data
    } catch (error: any) {
      console.error('Check registration failed:', error)
      return {
        success: false,
        data: { is_registered: false }
      }
    }
  }

  /**
   * Get event registration statistics
   */
  async getEventStats(eventId: number): Promise<EventStatsResponse> {
    try {
      const response = await apiClient.get(`${this.baseUrl}/${eventId}/stats`)
      return response.data
    } catch (error: any) {
      console.error('Get event stats failed:', error)
      return {
        success: false,
        data: {
          total_registrations: 0,
          confirmed_registrations: 0,
          pending_registrations: 0,
          reminders_sent: 0,
          event: null
        }
      }
    }
  }

  /**
   * Get user's upcoming events
   */
  async getUserEvents(email: string): Promise<UserEventsResponse> {
    try {
      const response = await apiClient.get(`${this.baseUrl}/user-events?email=${encodeURIComponent(email)}`)
      return response.data
    } catch (error: any) {
      console.error('Get user events failed:', error)
      return {
        success: false,
        data: []
      }
    }
  }

  /**
   * Cancel a registration
   */
  async cancelRegistration(registrationId: number): Promise<{ success: boolean; message: string }> {
    try {
      const response = await apiClient.delete(`${this.baseUrl}/registrations/${registrationId}`)
      return response.data
    } catch (error: any) {
      console.error('Cancel registration failed:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Cancellation failed'
      }
    }
  }

  /**
   * Send test reminder (for testing)
   */
  async sendTestReminder(registrationId: number): Promise<{ success: boolean; message: string }> {
    try {
      const response = await apiClient.post(`${this.baseUrl}/test-reminder/${registrationId}`)
      return response.data
    } catch (error: any) {
      console.error('Send test reminder failed:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Test reminder failed'
      }
    }
  }
}

export const eventRegistrationService = new EventRegistrationService()
