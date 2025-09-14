<template>
  <div class="max-w-md mx-auto">
    <div class="card">
      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold mb-2">
          <span class="gradient-text">Create Account</span>
        </h2>
        <p class="text-gray-400">Join the Stargate.ci community</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Personal Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="firstName" class="block text-sm font-medium text-gray-300 mb-2">
              First Name
            </label>
            <input
              id="firstName"
              v-model="form.firstName"
              type="text"
              class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
              placeholder="Enter your first name"
            />
          </div>
          <div>
            <label for="lastName" class="block text-sm font-medium text-gray-300 mb-2">
              Last Name
            </label>
            <input
              id="lastName"
              v-model="form.lastName"
              type="text"
              class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
              placeholder="Enter your last name"
            />
          </div>
        </div>

        <!-- Username -->
        <div>
          <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
            Username *
          </label>
          <input
            id="username"
            v-model="form.username"
            type="text"
            required
            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
            placeholder="Choose a username"
          />
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
            Email Address *
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
            placeholder="Enter your email"
          />
        </div>

        <!-- Company -->
        <div>
          <label for="company" class="block text-sm font-medium text-gray-300 mb-2">
            Company (Optional)
          </label>
          <input
            id="company"
            v-model="form.company"
            type="text"
            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
            placeholder="Your company name"
          />
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
            Password *
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            minlength="6"
            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
            placeholder="Create a password (min 6 characters)"
          />
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="confirmPassword" class="block text-sm font-medium text-gray-300 mb-2">
            Confirm Password *
          </label>
          <input
            id="confirmPassword"
            v-model="form.confirmPassword"
            type="password"
            required
            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent text-white placeholder-gray-400"
            placeholder="Confirm your password"
          />
        </div>

        <!-- Error Message -->
        <div v-if="errorMessage" class="p-4 bg-red-900/20 border border-red-500/50 rounded-lg">
          <p class="text-red-400 text-sm">{{ errorMessage }}</p>
        </div>

        <!-- Success Message -->
        <div v-if="successMessage" class="p-4 bg-green-900/20 border border-green-500/50 rounded-lg">
          <p class="text-green-400 text-sm">{{ successMessage }}</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="isSubmitting"
          class="w-full btn-primary py-3 text-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span v-if="isSubmitting" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Creating Account...
          </span>
          <span v-else>Create Account</span>
        </button>
      </form>

      <!-- Login Link -->
      <div class="mt-6 text-center">
        <p class="text-gray-400">
          Already have an account?
          <button @click="$emit('switch-to-login')" class="text-primary-400 hover:text-primary-300 font-medium">
            Sign in here
          </button>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { authService, type RegisterCredentials } from '../services/authService'

// Emits
const emit = defineEmits<{
  'switch-to-login': []
  'registration-success': [user: any]
}>()

// Form data
const form = reactive<RegisterCredentials & { confirmPassword: string }>({
  username: '',
  email: '',
  password: '',
  confirmPassword: '',
  firstName: '',
  lastName: '',
  company: ''
})

// State
const isSubmitting = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

// Form validation
const validateForm = (): boolean => {
  if (form.password !== form.confirmPassword) {
    errorMessage.value = 'Passwords do not match'
    return false
  }
  
  if (form.password.length < 6) {
    errorMessage.value = 'Password must be at least 6 characters long'
    return false
  }
  
  if (!form.email.includes('@')) {
    errorMessage.value = 'Please enter a valid email address'
    return false
  }
  
  return true
}

// Handle form submission
const handleSubmit = async () => {
  errorMessage.value = ''
  successMessage.value = ''
  
  if (!validateForm()) {
    return
  }
  
  isSubmitting.value = true
  
  try {
    const result = await authService.registerUser({
      username: form.username,
      email: form.email,
      password: form.password,
      firstName: form.firstName,
      lastName: form.lastName,
      company: form.company
    })
    
    if (result.success && result.user) {
      successMessage.value = 'Account created successfully! You can now sign in.'
      emit('registration-success', result.user)
      
      // Clear form
      Object.assign(form, {
        username: '',
        email: '',
        password: '',
        confirmPassword: '',
        firstName: '',
        lastName: '',
        company: ''
      })
      
      // Switch to login after 2 seconds
      setTimeout(() => {
        emit('switch-to-login')
      }, 2000)
    } else {
      errorMessage.value = result.error || 'Registration failed. Please try again.'
    }
  } catch (error) {
    errorMessage.value = 'An unexpected error occurred. Please try again.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.card {
  @apply bg-gray-800/50 border border-gray-700 rounded-lg p-8 hover:border-primary-500/50 transition-all duration-300;
}

.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-3 rounded-lg font-medium hover:from-primary-600 hover:to-secondary-600 transition-all duration-300;
}

.gradient-text {
  @apply bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent;
}
</style>
