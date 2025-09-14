<template>
  <div class="two-factor-verification">
    <div class="max-w-md mx-auto">
      <!-- Header -->
      <div class="text-center mb-8">
        <div class="w-16 h-16 bg-primary-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">Two-Factor Authentication</h2>
        <p class="text-gray-400">Enter the 6-digit code from your authenticator app</p>
      </div>

      <!-- Verification Form -->
      <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
        <form @submit.prevent="verifyCode" class="space-y-6">
          <!-- Code Input -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Verification Code
            </label>
            <input
              v-model="verificationCode"
              type="text"
              maxlength="6"
              placeholder="000000"
              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white text-center text-2xl font-mono focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              @input="formatCode"
              :disabled="isVerifying"
            />
          </div>

          <!-- Error Message -->
          <div v-if="errorMessage" class="p-3 bg-red-900/20 border border-red-500/50 rounded-lg">
            <p class="text-red-400 text-sm">{{ errorMessage }}</p>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="verificationCode.length !== 6 || isVerifying"
            class="w-full bg-primary-500 hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-6 py-3 rounded-lg transition-colors duration-200 font-medium"
          >
            <span v-if="isVerifying">Verifying...</span>
            <span v-else>Verify & Continue</span>
          </button>
        </form>

        <!-- Backup Code Option -->
        <div class="mt-6 pt-6 border-t border-gray-700">
          <button
            @click="showBackupCode = !showBackupCode"
            class="text-sm text-primary-400 hover:text-primary-300 transition-colors duration-200"
          >
            {{ showBackupCode ? 'Hide' : 'Use backup code instead' }}
          </button>

          <div v-if="showBackupCode" class="mt-4 space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Backup Code
              </label>
              <input
                v-model="backupCode"
                type="text"
                placeholder="Enter backup code"
                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                :disabled="isVerifying"
              />
            </div>

            <button
              @click="verifyBackupCode"
              :disabled="!backupCode || isVerifying"
              class="w-full bg-gray-600 hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed text-white px-6 py-3 rounded-lg transition-colors duration-200 font-medium"
            >
              <span v-if="isVerifying">Verifying...</span>
              <span v-else>Verify Backup Code</span>
            </button>
          </div>
        </div>

        <!-- Help Text -->
        <div class="mt-6 text-center">
          <p class="text-xs text-gray-400">
            Having trouble? Check that your device's time is synchronized correctly.
          </p>
        </div>
      </div>

      <!-- Back to Login -->
      <div class="mt-6 text-center">
        <button
          @click="$emit('back')"
          class="text-sm text-gray-400 hover:text-white transition-colors duration-200"
        >
          ← Back to login
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { twoFactorAuth } from '../services/twoFactorAuth'

// Emits
const emit = defineEmits<{
  success: [user: any]
  back: []
}>()

// Props
const props = defineProps<{
  userId: string
}>()

// Reactive data
const verificationCode = ref('')
const backupCode = ref('')
const errorMessage = ref('')
const isVerifying = ref(false)
const showBackupCode = ref(false)

// Methods
const formatCode = (event: Event) => {
  const target = event.target as HTMLInputElement
  const value = target.value.replace(/\D/g, '')
  verificationCode.value = value
}

const verifyCode = async () => {
  if (!verificationCode.value) return
  
  isVerifying.value = true
  errorMessage.value = ''
  
  try {
    // Simulate verification delay
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // In a real implementation, this would verify with the backend
    const isValid = twoFactorAuth.verifyTwoFactorCode('stored-secret', verificationCode.value)
    
    if (isValid) {
      // Track successful login
      twoFactorAuth.trackLoginAttempt('user@example.com', true)
      
      // Emit success with user data
      emit('success', {
        id: props.userId,
        username: 'user',
        email: 'user@example.com',
        twoFactorEnabled: true
      })
    } else {
      errorMessage.value = 'Invalid verification code. Please try again.'
      twoFactorAuth.trackLoginAttempt('user@example.com', false)
    }
  } catch (error) {
    errorMessage.value = 'Verification failed. Please try again.'
    twoFactorAuth.trackLoginAttempt('user@example.com', false)
  } finally {
    isVerifying.value = false
  }
}

const verifyBackupCode = async () => {
  if (!backupCode.value) return
  
  isVerifying.value = true
  errorMessage.value = ''
  
  try {
    // Simulate verification delay
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    const isValid = twoFactorAuth.verifyBackupCode(backupCode.value)
    
    if (isValid) {
      // Track successful login
      twoFactorAuth.trackLoginAttempt('user@example.com', true)
      
      // Emit success with user data
      emit('success', {
        id: props.userId,
        username: 'user',
        email: 'user@example.com',
        twoFactorEnabled: true
      })
    } else {
      errorMessage.value = 'Invalid backup code. Please try again.'
      twoFactorAuth.trackLoginAttempt('user@example.com', false)
    }
  } catch (error) {
    errorMessage.value = 'Verification failed. Please try again.'
    twoFactorAuth.trackLoginAttempt('user@example.com', false)
  } finally {
    isVerifying.value = false
  }
}
</script>

<style scoped>
.two-factor-verification {
  @apply p-6;
}
</style>
