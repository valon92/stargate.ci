<template>
  <div class="security-settings">
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Security Settings</h2>
        <p class="text-gray-400">Manage your account security and authentication preferences</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Two-Factor Authentication -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-lg font-semibold text-white">Two-Factor Authentication</h3>
              <p class="text-gray-400 text-sm">Add an extra layer of security to your account</p>
            </div>
            <div class="flex items-center space-x-3">
              <span class="text-sm text-gray-400">
                {{ isTwoFactorEnabled ? 'Enabled' : 'Disabled' }}
              </span>
              <div
                class="w-12 h-6 rounded-full transition-colors duration-200"
                :class="isTwoFactorEnabled ? 'bg-green-500' : 'bg-gray-600'"
              >
                <div
                  class="w-5 h-5 bg-white rounded-full shadow transform transition-transform duration-200"
                  :class="isTwoFactorEnabled ? 'translate-x-6' : 'translate-x-0.5'"
                ></div>
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <button
              v-if="!isTwoFactorEnabled"
              @click="enableTwoFactor"
              class="w-full bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-colors duration-200"
            >
              Enable 2FA
            </button>
            
            <div v-else class="space-y-3">
              <button
                @click="showBackupCodes"
                class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200"
              >
                View Backup Codes ({{ remainingBackupCodes }} remaining)
              </button>
              
              <button
                @click="disableTwoFactor"
                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200"
              >
                Disable 2FA
              </button>
            </div>
          </div>
        </div>

        <!-- Email Verification -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-lg font-semibold text-white">Email Verification</h3>
              <p class="text-gray-400 text-sm">Verify your email address for account security</p>
            </div>
            <div class="flex items-center space-x-3">
              <span class="text-sm text-gray-400">
                {{ isEmailVerified ? 'Verified' : 'Unverified' }}
              </span>
              <div
                class="w-12 h-6 rounded-full transition-colors duration-200"
                :class="isEmailVerified ? 'bg-green-500' : 'bg-gray-600'"
              >
                <div
                  class="w-5 h-5 bg-white rounded-full shadow transform transition-transform duration-200"
                  :class="isEmailVerified ? 'translate-x-6' : 'translate-x-0.5'"
                ></div>
              </div>
            </div>
          </div>

          <div class="space-y-4">
            <button
              v-if="!isEmailVerified"
              @click="sendVerificationEmail"
              :disabled="isSendingEmail"
              class="w-full bg-primary-500 hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg transition-colors duration-200"
            >
              <span v-if="isSendingEmail">Sending...</span>
              <span v-else>Send Verification Email</span>
            </button>
            
            <div v-else class="text-green-400 text-sm">
              ✓ Your email address is verified
            </div>
          </div>
        </div>

        <!-- Password Reset -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h3 class="text-lg font-semibold text-white">Password Reset</h3>
              <p class="text-gray-400 text-sm">Change your account password</p>
            </div>
            <div class="flex items-center space-x-3">
              <span class="text-sm text-gray-400">Enabled</span>
              <div class="w-12 h-6 rounded-full bg-green-500">
                <div class="w-5 h-5 bg-white rounded-full shadow transform translate-x-6"></div>
              </div>
            </div>
          </div>

          <button
            @click="showPasswordReset = !showPasswordReset"
            class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200"
          >
            Change Password
          </button>

          <!-- Password Reset Form -->
          <div v-if="showPasswordReset" class="mt-4 space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Current Password
              </label>
              <input
                v-model="currentPassword"
                type="password"
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                New Password
              </label>
              <input
                v-model="newPassword"
                type="password"
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Confirm New Password
              </label>
              <input
                v-model="confirmPassword"
                type="password"
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              />
            </div>
            
            <div class="flex space-x-3">
              <button
                @click="updatePassword"
                :disabled="!canUpdatePassword || isUpdatingPassword"
                class="flex-1 bg-green-500 hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg transition-colors duration-200"
              >
                <span v-if="isUpdatingPassword">Updating...</span>
                <span v-else>Update Password</span>
              </button>
              
              <button
                @click="showPasswordReset = false"
                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>

        <!-- Session Settings -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <div class="mb-4">
            <h3 class="text-lg font-semibold text-white">Session Settings</h3>
            <p class="text-gray-400 text-sm">Configure your session timeout and security</p>
          </div>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Session Timeout (minutes)
              </label>
              <select
                v-model="sessionTimeout"
                @change="updateSessionTimeout"
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              >
                <option value="15">15 minutes</option>
                <option value="30">30 minutes</option>
                <option value="60">1 hour</option>
                <option value="120">2 hours</option>
                <option value="480">8 hours</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-300 mb-2">
                Max Login Attempts
              </label>
              <select
                v-model="maxLoginAttempts"
                @change="updateMaxLoginAttempts"
                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              >
                <option value="3">3 attempts</option>
                <option value="5">5 attempts</option>
                <option value="10">10 attempts</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Security Status -->
      <div class="mt-8 bg-gray-800/50 border border-gray-700 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Security Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="flex items-center space-x-3">
            <div
              class="w-3 h-3 rounded-full"
              :class="isTwoFactorEnabled ? 'bg-green-500' : 'bg-red-500'"
            ></div>
            <span class="text-gray-300">Two-Factor Authentication</span>
          </div>
          
          <div class="flex items-center space-x-3">
            <div
              class="w-3 h-3 rounded-full"
              :class="isEmailVerified ? 'bg-green-500' : 'bg-red-500'"
            ></div>
            <span class="text-gray-300">Email Verification</span>
          </div>
          
          <div class="flex items-center space-x-3">
            <div class="w-3 h-3 rounded-full bg-green-500"></div>
            <span class="text-gray-300">Strong Password</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 2FA Setup Modal -->
    <div v-if="showTwoFactorSetup" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-gray-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-white">Setup Two-Factor Authentication</h3>
            <button
              @click="showTwoFactorSetup = false"
              class="text-gray-400 hover:text-white"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <TwoFactorSetup
            :user-email="userEmail"
            @complete="handleTwoFactorComplete"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { twoFactorAuth } from '../services/twoFactorAuth'
import TwoFactorSetup from './TwoFactorSetup.vue'

// Reactive data
const isTwoFactorEnabled = ref(false)
const isEmailVerified = ref(false)
const remainingBackupCodes = ref(0)
const showTwoFactorSetup = ref(false)
const showPasswordReset = ref(false)
const isSendingEmail = ref(false)
const isUpdatingPassword = ref(false)

// Password reset form
const currentPassword = ref('')
const newPassword = ref('')
const confirmPassword = ref('')

// Session settings
const sessionTimeout = ref(30)
const maxLoginAttempts = ref(5)

// User email (in a real app, this would come from user data)
const userEmail = ref('user@example.com')

// Computed properties
const canUpdatePassword = computed(() => {
  return currentPassword.value && 
         newPassword.value && 
         confirmPassword.value &&
         newPassword.value === confirmPassword.value &&
         newPassword.value.length >= 8
})

// Methods
const enableTwoFactor = () => {
  showTwoFactorSetup.value = true
}

const disableTwoFactor = () => {
  if (confirm('Are you sure you want to disable two-factor authentication? This will make your account less secure.')) {
    twoFactorAuth.disableTwoFactor('current-user')
    isTwoFactorEnabled.value = false
    remainingBackupCodes.value = 0
  }
}

const showBackupCodes = () => {
  // In a real implementation, this would show backup codes
  alert(`Remaining backup codes: ${remainingBackupCodes.value}`)
}

const sendVerificationEmail = async () => {
  isSendingEmail.value = true
  
  try {
    // Simulate sending email
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    // In a real implementation, this would send an actual email
    alert('Verification email sent! Please check your inbox.')
  } catch (error) {
    alert('Failed to send verification email. Please try again.')
  } finally {
    isSendingEmail.value = false
  }
}

const updatePassword = async () => {
  isUpdatingPassword.value = true
  
  try {
    // Simulate password update
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    // In a real implementation, this would update the password
    alert('Password updated successfully!')
    
    // Reset form
    currentPassword.value = ''
    newPassword.value = ''
    confirmPassword.value = ''
    showPasswordReset.value = false
  } catch (error) {
    alert('Failed to update password. Please try again.')
  } finally {
    isUpdatingPassword.value = false
  }
}

const updateSessionTimeout = () => {
  const settings = twoFactorAuth.getSecuritySettings()
  settings.sessionTimeout = parseInt(sessionTimeout.value.toString())
  twoFactorAuth.updateSecuritySettings(settings)
}

const updateMaxLoginAttempts = () => {
  const settings = twoFactorAuth.getSecuritySettings()
  settings.maxLoginAttempts = parseInt(maxLoginAttempts.value.toString())
  twoFactorAuth.updateSecuritySettings(settings)
}

const handleTwoFactorComplete = () => {
  showTwoFactorSetup.value = false
  isTwoFactorEnabled.value = true
  remainingBackupCodes.value = twoFactorAuth.getRemainingBackupCodes()
}

// Lifecycle
onMounted(() => {
  // Load current settings
  isTwoFactorEnabled.value = twoFactorAuth.isTwoFactorEnabled('current-user')
  remainingBackupCodes.value = twoFactorAuth.getRemainingBackupCodes()
  
  const settings = twoFactorAuth.getSecuritySettings()
  sessionTimeout.value = settings.sessionTimeout
  maxLoginAttempts.value = settings.maxLoginAttempts
  
  // In a real implementation, email verification status would come from the backend
  isEmailVerified.value = true // Simulated
})
</script>

<style scoped>
.security-settings {
  @apply p-6;
}
</style>
