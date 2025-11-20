<template>
  <div class="voice-control-container fixed bottom-6 right-6 z-50">
    <!-- Voice Control Button -->
    <button
      v-if="canShowButton"
      @click="toggleListening"
      :class="buttonClasses"
      :style="buttonStyle"
      :title="isListening ? 'Stop listening' : 'Start voice control'"
      aria-label="Voice control"
      @mouseenter="handleMouseEnter"
      @mouseleave="handleMouseLeave"
    >
      <svg
        v-if="!isListening"
        class="w-6 h-6 text-white"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"
        />
      </svg>
      <svg
        v-else
        class="w-6 h-6 text-white"
        fill="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          d="M6 6h12v12H6z"
        />
      </svg>
    </button>

    <!-- Error Message -->
    <div
      v-if="error && showError"
      class="absolute bottom-full right-0 mb-2 p-4 bg-red-500 text-white rounded-lg shadow-lg max-w-sm text-sm z-50"
    >
      <div class="flex items-start gap-2">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        <div class="flex-1 min-w-0">
          <p class="font-semibold mb-2">Voice Control Error</p>
          <p class="text-xs whitespace-pre-line leading-relaxed">{{ error }}</p>
          <div class="flex gap-2 mt-3">
            <button
              v-if="error && (error.includes('permission') || error.includes('Network error') || error.includes('service error'))"
              @click="retryPermission"
              class="px-3 py-1.5 bg-white text-red-500 rounded text-xs font-semibold hover:bg-gray-100 transition"
            >
              Try Again
            </button>
            <button
              v-if="error && (error.includes('permission') || error.includes('Network error') || error.includes('service error'))"
              @click="refreshPage"
              class="px-3 py-1.5 bg-white/20 text-white rounded text-xs font-semibold hover:bg-white/30 transition border border-white/30"
            >
              Refresh Page
            </button>
          </div>
        </div>
        <button
          @click="showError = false"
          class="ml-2 text-white hover:text-gray-200 flex-shrink-0"
          aria-label="Close error"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Listening Indicator -->
    <div
      v-if="isListening"
      class="absolute -top-2 -right-2 w-4 h-4 rounded-full animate-ping"
      :style="{ backgroundColor: platformTheme.active }"
    ></div>

    <!-- Command Feedback -->
    <div
      v-if="lastCommand && showCommandFeedback"
      class="absolute bottom-full right-0 mb-2 p-2 bg-green-500 text-white rounded-lg shadow-lg text-xs"
    >
      ✓ Command: {{ lastCommand.action }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useVoiceActionsStore } from '../stores/voiceActions'

const router = useRouter()
const voiceStore = useVoiceActionsStore()

// Set router in store for navigation
voiceStore.setRouter(router)

const showError = ref(true)
const showCommandFeedback = ref(false)
const voiceControlEnabled = ref(true)

const isListening = computed(() => voiceStore.isListening)
const canListen = computed(() => voiceStore.canListen)
const error = computed(() => voiceStore.error)
const lastCommand = computed(() => voiceStore.lastCommand)
const canShowButton = computed(() => voiceControlEnabled.value && canListen.value)

// Platform-specific colors for Stargate (Indigo/Purple theme)
const platformTheme = computed(() => {
  return {
    primary: '#6366f1', // Indigo
    secondary: '#8b5cf6', // Purple
    active: '#ef4444', // Red when listening
    gradient: 'linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%)',
    activeGradient: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
    border: '#8b5cf6',
    hover: 'linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%)'
  }
})

const buttonClasses = computed(() => {
  const baseClasses = [
    'voice-control-button',
    'w-14 h-14',
    'rounded-full',
    'shadow-lg',
    'transition-all',
    'duration-300',
    'flex items-center justify-center',
    'border-2'
  ]
  
  if (isListening.value) {
    baseClasses.push('animate-pulse')
  }
  
  if (error.value) {
    baseClasses.push('border-red-500')
  }
  
  return baseClasses
})

const buttonStyle = computed(() => {
  if (isListening.value) {
    return {
      background: platformTheme.value.activeGradient,
      borderColor: platformTheme.value.active
    }
  } else {
    return {
      background: platformTheme.value.gradient,
      borderColor: platformTheme.value.border
    }
  }
})

// Watch for new commands to show feedback
watch(lastCommand, (command) => {
  if (command) {
    showCommandFeedback.value = true
    setTimeout(() => {
      showCommandFeedback.value = false
    }, 2000)
  }
})

const toggleListening = async () => {
  if (!voiceControlEnabled.value) {
    return
  }
  await voiceStore.toggleListening()
}

const retryPermission = async () => {
  // Clear error and try again
  voiceStore.clearError()
  showError.value = false
  
  // Wait a bit before retrying (especially for network errors)
  await new Promise(resolve => setTimeout(resolve, 500))
  
  await voiceStore.startListening()
}

const refreshPage = () => {
  window.location.reload()
}

const handleMouseEnter = (event: MouseEvent) => {
  const button = event.target as HTMLElement
  if (!isListening.value && button) {
    button.style.background = platformTheme.value.hover
    button.style.transform = 'scale(1.1)'
  }
}

const handleMouseLeave = (event: MouseEvent) => {
  const button = event.target as HTMLElement
  if (!isListening.value && button) {
    button.style.background = platformTheme.value.gradient
    button.style.transform = 'scale(1)'
  }
}

const loadVoiceControlSetting = () => {
  const saved = localStorage.getItem('stargate-settings')
  if (saved) {
    try {
      const parsed = JSON.parse(saved)
      voiceControlEnabled.value = parsed.voiceControl !== false
      if (parsed.voiceLanguage) {
        voiceStore.setLocale(parsed.voiceLanguage)
      }
    } catch (error) {
      console.warn('Failed to parse stargate settings:', error)
      voiceControlEnabled.value = true
    }
  }
}

const handleVoiceControlToggle = async (event: Event) => {
  const detail = (event as CustomEvent<{ enabled?: boolean; language?: string }>).detail || {}
  const enabled = detail.enabled !== false
  voiceControlEnabled.value = enabled

  if (detail.language) {
    voiceStore.setLocale(detail.language)
  }

  if (enabled) {
    if (!voiceStore.isInitialized) {
      await voiceStore.initialize()
    }
  } else {
    voiceStore.stopListening()
    voiceStore.destroy()
  }
}

onMounted(async () => {
  loadVoiceControlSetting()

  if (voiceControlEnabled.value) {
    await voiceStore.initialize()
  }

  window.addEventListener('voice-control-toggle', handleVoiceControlToggle as EventListener)
})

onUnmounted(() => {
  // Clean up on unmount
  voiceStore.destroy()
  window.removeEventListener('voice-control-toggle', handleVoiceControlToggle as EventListener)
})
</script>

<style scoped>
.voice-control-container {
  font-family: inherit;
}

.voice-control-button {
  backdrop-filter: blur(10px);
}

.voice-control-button:focus {
  outline: none;
  ring: 2px;
  ring-offset: 2px;
  ring-color: rgba(147, 51, 234, 0.5);
}

@keyframes pulse-ring {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.8;
  }
}
</style>

