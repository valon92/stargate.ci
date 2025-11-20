<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <h3 class="text-xl font-bold text-gray-900">
          📅 Register for Event
        </h3>
        <button 
          @click="$emit('close')"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Event Info -->
      <div class="p-6 border-b bg-gray-50">
        <h4 class="font-semibold text-gray-900 mb-2">{{ event?.title || 'No Event Title' }}</h4>
        <div class="text-sm text-gray-600 space-y-1">
          <div class="flex items-center">
            <span class="mr-2">📅</span>
            <span>{{ event?.event_date ? formatDate(event.event_date) : 'No Date' }}</span>
            <span v-if="event?.event_time" class="ml-2">{{ formatTime(event.event_time) }}</span>
          </div>
          <div class="flex items-center">
            <span class="mr-2">📍</span>
            <span>{{ event?.location || 'No Location' }}</span>
          </div>
          <div class="flex items-center">
            <span class="mr-2">🏢</span>
            <span>{{ event?.organizer || 'No Organizer' }}</span>
          </div>
        </div>
        
      </div>

      <!-- Registration Form -->
      <form @submit.prevent="handleRegistration" class="p-6">
        <div class="space-y-4">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-800 mb-1">
              Email Address *
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white placeholder-gray-500"
              placeholder="your@email.com"
            />
          </div>

          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-800 mb-1">
              Full Name
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white placeholder-gray-500"
              placeholder="Your full name"
            />
          </div>

          <!-- Phone -->
          <div>
            <label for="phone" class="block text-sm font-medium text-gray-800 mb-1">
              Phone Number
            </label>
            <input
              id="phone"
              v-model="form.phone"
              type="tel"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 bg-white placeholder-gray-500"
              placeholder="+1 (555) 123-4567"
            />
          </div>

          <!-- Preferences -->
          <div>
            <label class="block text-sm font-medium text-gray-800 mb-2">
              Notification Preferences
            </label>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  v-model="form.preferences.emailReminders"
                  type="checkbox"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <span class="ml-2 text-sm text-gray-800">Email reminders before the event</span>
              </label>
              <label class="flex items-center">
                <input
                  v-model="form.preferences.smsReminders"
                  type="checkbox"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <span class="ml-2 text-sm text-gray-800">SMS reminders (if phone provided)</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex gap-3">
          <button
            type="button"
            @click="$emit('close')"
            class="flex-1 px-4 py-2 border border-gray-400 text-gray-800 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors font-medium"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="isLoading"
            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium"
          >
            <span v-if="isLoading">Registering...</span>
            <span v-else>Register for Event</span>
          </button>
        </div>
      </form>

      <!-- Success Message -->
      <div v-if="successMessage" class="p-6 bg-green-50 border-t">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <p class="text-green-700 font-medium">{{ successMessage }}</p>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="p-6 bg-red-50 border-t">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p class="text-red-700 font-medium">{{ errorMessage }}</p>
        </div>
      </div>

      <!-- Form Debug Info -->
      <div v-if="isLoading" class="p-6 bg-yellow-50 border-t">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-yellow-500 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <p class="text-yellow-700 font-medium">Submitting registration...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { eventRegistrationService } from '@/services/eventRegistrationService'

interface Props {
  show: boolean
  event: any
}

interface Emits {
  (e: 'close'): void
  (e: 'registered', registration: any): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emits>()

const isLoading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const form = reactive({
  email: '',
  name: '',
  phone: '',
  preferences: {
    emailReminders: true,
    smsReminders: false
  }
})

// Reset form when modal opens/closes
watch(() => props.show, (newShow) => {
  if (newShow) {
    resetForm()
  }
})

const resetForm = () => {
  form.email = ''
  form.name = ''
  form.phone = ''
  form.preferences.emailReminders = true
  form.preferences.smsReminders = false
  successMessage.value = ''
  errorMessage.value = ''
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatTime = (timeString: string) => {
  return new Date(`2000-01-01T${timeString}`).toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}

const handleRegistration = async () => {
  // Check if email is provided
  if (!form.email) {
    errorMessage.value = 'Email address is required'
    return
  }
  
  isLoading.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    const registrationData = {
      event_id: props.event?.id,
      email: form.email,
      name: form.name || undefined,
      phone: form.phone || undefined,
      preferences: form.preferences
    }

    const response = await eventRegistrationService.registerForEvent(registrationData)

    if (response.success) {
      successMessage.value = response.message
      emit('registered', response.data)
      
      // Close modal after 2 seconds
      setTimeout(() => {
        emit('close')
      }, 2000)
    } else {
      errorMessage.value = response.message || 'Registration failed'
    }
  } catch (error) {
    console.error('Registration error:', error)
    errorMessage.value = 'An unexpected error occurred. Please try again.'
  } finally {
    isLoading.value = false
  }
}
</script>
