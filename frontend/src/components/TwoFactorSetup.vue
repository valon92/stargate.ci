<template>
  <div class="two-factor-setup">
    <div class="max-w-2xl mx-auto">
      <!-- Header -->
      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Enable Two-Factor Authentication</h2>
        <p class="text-gray-400">Add an extra layer of security to your account</p>
      </div>

      <!-- Step 1: QR Code -->
      <div v-if="currentStep === 1" class="space-y-6">
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Step 1: Scan QR Code</h3>
          <p class="text-gray-300 mb-4">
            Use your authenticator app (Google Authenticator, Authy, etc.) to scan this QR code:
          </p>
          
          <div class="flex justify-center mb-4">
            <div class="bg-white p-4 rounded-lg">
              <img :src="setupData?.qrCode" alt="2FA QR Code" class="w-48 h-48" />
            </div>
          </div>
          
          <div class="text-center">
            <p class="text-sm text-gray-400 mb-2">Or enter this code manually:</p>
            <div class="bg-gray-700 p-3 rounded-lg font-mono text-sm text-white break-all">
              {{ setupData?.secret }}
            </div>
          </div>
        </div>

        <div class="flex justify-end">
          <button
            @click="nextStep"
            class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg transition-colors duration-200"
          >
            Next Step
          </button>
        </div>
      </div>

      <!-- Step 2: Verify Code -->
      <div v-if="currentStep === 2" class="space-y-6">
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Step 2: Verify Setup</h3>
          <p class="text-gray-300 mb-4">
            Enter the 6-digit code from your authenticator app to verify the setup:
          </p>
          
          <div class="space-y-4">
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
              />
            </div>
            
            <div v-if="errorMessage" class="p-3 bg-red-900/20 border border-red-500/50 rounded-lg">
              <p class="text-red-400 text-sm">{{ errorMessage }}</p>
            </div>
          </div>
        </div>

        <div class="flex justify-between">
          <button
            @click="prevStep"
            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors duration-200"
          >
            Back
          </button>
          <button
            @click="verifyCode"
            :disabled="verificationCode.length !== 6 || isVerifying"
            class="bg-primary-500 hover:bg-primary-600 disabled:opacity-50 disabled:cursor-not-allowed text-white px-6 py-2 rounded-lg transition-colors duration-200"
          >
            <span v-if="isVerifying">Verifying...</span>
            <span v-else>Verify & Enable</span>
          </button>
        </div>
      </div>

      <!-- Step 3: Backup Codes -->
      <div v-if="currentStep === 3" class="space-y-6">
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-6">
          <h3 class="text-lg font-semibold text-white mb-4">Step 3: Save Backup Codes</h3>
          <p class="text-gray-300 mb-4">
            Save these backup codes in a safe place. You can use them to access your account if you lose your authenticator device:
          </p>
          
          <div class="bg-gray-700 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-2 gap-2 font-mono text-sm">
              <div
                v-for="(code, index) in setupData?.backupCodes || []"
                :key="index"
                class="text-white p-2 bg-gray-600 rounded text-center"
              >
                {{ code }}
              </div>
            </div>
          </div>
          
          <div class="flex items-center space-x-2 text-yellow-400 text-sm">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <span>Important: These codes can only be used once each.</span>
          </div>
        </div>

        <div class="flex justify-between">
          <button
            @click="downloadBackupCodes"
            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors duration-200"
          >
            Download Codes
          </button>
          <button
            @click="completeSetup"
            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors duration-200"
          >
            Complete Setup
          </button>
        </div>
      </div>

      <!-- Success State -->
      <div v-if="currentStep === 4" class="text-center space-y-6">
        <div class="bg-green-900/20 border border-green-500/50 rounded-lg p-6">
          <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-white mb-2">2FA Enabled Successfully!</h3>
          <p class="text-gray-300">
            Two-factor authentication has been enabled for your account. You'll need to enter a code from your authenticator app each time you sign in.
          </p>
        </div>

        <button
          @click="$emit('complete')"
          class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg transition-colors duration-200"
        >
          Continue
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { twoFactorAuth, type TwoFactorSetup } from '../services/twoFactorAuth'

// Emits
const emit = defineEmits<{
  complete: []
}>()

// Props
const props = defineProps<{
  userEmail: string
}>()

// Reactive data
const currentStep = ref(1)
const setupData = ref<TwoFactorSetup | null>(null)
const verificationCode = ref('')
const errorMessage = ref('')
const isVerifying = ref(false)

// Methods
const nextStep = () => {
  currentStep.value++
}

const prevStep = () => {
  currentStep.value--
}

const formatCode = (event: Event) => {
  const target = event.target as HTMLInputElement
  const value = target.value.replace(/\D/g, '')
  verificationCode.value = value
}

const verifyCode = async () => {
  if (!setupData.value) return
  
  isVerifying.value = true
  errorMessage.value = ''
  
  try {
    // Simulate verification delay
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    const isValid = twoFactorAuth.verifyTwoFactorCode(setupData.value.secret, verificationCode.value)
    
    if (isValid) {
      // Enable 2FA for user
      twoFactorAuth.enableTwoFactor('current-user', setupData.value.secret)
      nextStep()
    } else {
      errorMessage.value = 'Invalid verification code. Please try again.'
    }
  } catch (error) {
    errorMessage.value = 'Verification failed. Please try again.'
  } finally {
    isVerifying.value = false
  }
}

const downloadBackupCodes = () => {
  if (!setupData.value) return
  
  const content = `Stargate.ci - Backup Codes\n\nSave these codes in a safe place:\n\n${setupData.value.backupCodes.join('\n')}\n\nEach code can only be used once.`
  const blob = new Blob([content], { type: 'text/plain' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'stargate-backup-codes.txt'
  document.body.appendChild(a)
  a.click()
  document.body.removeChild(a)
  URL.revokeObjectURL(url)
}

const completeSetup = () => {
  currentStep.value = 4
}

// Lifecycle
onMounted(() => {
  // Initialize 2FA setup
  setupData.value = twoFactorAuth.setupTwoFactor(props.userEmail)
})
</script>

<style scoped>
.two-factor-setup {
  @apply p-6;
}
</style>
