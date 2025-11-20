import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import VoiceActionsSDK from '@valon92/voice-actions-sdk'

export interface VoiceCommand {
  id: string
  action: string
  phrases: string[]
  category?: string
}

export const useVoiceActionsStore = defineStore('voiceActions', () => {
  // Get router instance (will be set during initialization)
  let router: ReturnType<typeof useRouter> | null = null
  
  // State
  const sdk = ref<VoiceActionsSDK | null>(null)
  const isListening = ref(false)
  const isSupported = ref(false)
  const isInitialized = ref(false)
  const error = ref<string | null>(null)
  const locale = ref<string>('en-US')
  const lastCommand = ref<VoiceCommand | null>(null)
  
  // Wake word detection
  const wakeWordRecognition = ref<SpeechRecognition | null>(null)
  const isWakeWordListening = ref(false)
  const wakeWordEnabled = ref(true)
  const WAKE_WORDS = ['hey stargate', 'hey stargate ci', 'stargate', 'hey stargate dot ci']
  
  // Set router instance
  const setRouter = (routerInstance: ReturnType<typeof useRouter>) => {
    router = routerInstance
  }

  // Getters
  const canListen = computed(() => {
    return isSupported.value && isInitialized.value && !error.value
  })

  // Actions
  const initialize = async (apiKey?: string) => {
    try {
      // Check if Web Speech API is supported
      const supported = 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window
      isSupported.value = supported

      if (!supported) {
        error.value = 'Web Speech API is not supported in this browser. Please use Chrome, Edge, or Safari.'
        return false
      }

      // Determine API URL - use local backend or demo mode
      const isLocalhost = typeof window !== 'undefined' && window.location.hostname === 'localhost'
      const apiUrl = isLocalhost 
        ? 'http://localhost:8000/api' 
        : (typeof window !== 'undefined' ? `${window.location.origin}/api` : 'http://localhost:8000/api')

      // Initialize SDK with local API URL or demo mode
      sdk.value = new VoiceActionsSDK({
        apiKey: apiKey || undefined, // Use undefined for demo mode if no API key
        apiUrl: apiUrl, // Use local backend API
        platform: 'stargate-ci',
        locale: locale.value,
        debug: import.meta.env.DEV, // Enable debug only in development
        onCommand: (command: VoiceCommand) => {
          console.log('🎤 onCommand callback triggered:', command)
          console.log('🎤 Command details:', {
            id: command.id,
            action: command.action,
            phrases: command.phrases,
            category: command.category
          })
          lastCommand.value = command
          handleCommand(command)
        },
        onListeningStateChange: (listeningState: boolean) => {
          // Sync listening state with store when wake word is detected or listening is toggled
          console.log(`🎤 onListeningStateChange called with: ${listeningState}`)
          console.log(`🎤 Current isListening.value before update: ${isListening.value}`)
          isListening.value = listeningState
          console.log(`🎤 Updated isListening.value to: ${isListening.value}`)
          if (import.meta.env.DEV) {
            console.log(`✅ Listening state changed in store: ${listeningState ? 'ON' : 'OFF'}`)
          }
        },
        onError: (err: Error) => {
          // Process error message to provide better instructions
          const errorMessage = err.message
          
          console.error('❌ Voice Actions SDK Error:', err)
          console.error('❌ Error details:', {
            message: err.message,
            name: err.name,
            stack: err.stack
          })
          
          // Handle network errors from Speech Recognition API
          if (errorMessage.includes('Network error') || errorMessage.includes('network')) {
            const isLocalhost = typeof window !== 'undefined' && window.location.hostname === 'localhost'
            const isHttps = typeof window !== 'undefined' && window.location.protocol === 'https:'
            const isOnline = typeof navigator !== 'undefined' && navigator.onLine
            
            let networkErrorMsg = 'Speech Recognition service error.\n\n'
            networkErrorMsg += 'This usually happens when:\n'
            networkErrorMsg += '1. Speech Recognition service is temporarily unavailable\n'
            networkErrorMsg += '2. Browser cannot connect to the speech service\n'
            networkErrorMsg += '3. Network connectivity issues\n\n'
            
            if (!isOnline) {
              networkErrorMsg += '⚠️ You appear to be offline. Please check your internet connection.\n\n'
            }
            
            networkErrorMsg += 'Try:\n'
            networkErrorMsg += '1. Click "Try Again" button below\n'
            networkErrorMsg += '2. Refresh the page and try again\n'
            networkErrorMsg += '3. Check your internet connection\n'
            networkErrorMsg += '4. Try again in a few moments\n'
            
            if (!isHttps && !isLocalhost) {
              networkErrorMsg += '\n⚠️ Note: Speech Recognition may require HTTPS in production.'
            }
            
            console.warn('🌐 Network status:', {
              online: isOnline,
              hostname: typeof window !== 'undefined' ? window.location.hostname : 'unknown',
              protocol: typeof window !== 'undefined' ? window.location.protocol : 'unknown'
            })
            
            error.value = networkErrorMsg
            isListening.value = false
            return
          }
          
          // Use the same formatting function for consistency
          if (errorMessage.includes('permission') || errorMessage.includes('not-allowed') || errorMessage.includes('denied')) {
            // Format permission error will be set below
            const formatPermissionError = () => {
              const isChrome = /Chrome/.test(navigator.userAgent) && !/Edge/.test(navigator.userAgent)
              const isSafari = /Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent)
              const isFirefox = /Firefox/.test(navigator.userAgent)
              
              let instructions = 'Microphone permission is required.\n\n'
              
              if (isChrome || /Edge/.test(navigator.userAgent)) {
                instructions += 'Chrome/Edge:\n'
                instructions += '1. Click the lock/camera icon in the address bar\n'
                instructions += '2. Find "Microphone" and select "Allow"\n'
                instructions += '3. Refresh the page and try again'
              } else if (isSafari) {
                instructions += 'Safari:\n'
                instructions += '1. Go to Safari > Settings > Websites > Microphone\n'
                instructions += '2. Find this site and select "Allow"\n'
                instructions += '3. Refresh the page and try again'
              } else if (isFirefox) {
                instructions += 'Firefox:\n'
                instructions += '1. Click the shield icon in the address bar\n'
                instructions += '2. Click "Permissions" > "Use the Microphone" > "Allow"\n'
                instructions += '3. Refresh the page and try again'
              } else {
                instructions += '1. Click the lock/camera icon in the address bar\n'
                instructions += '2. Allow microphone access\n'
                instructions += '3. Refresh the page and try again'
              }
              
              return instructions
            }
            
            error.value = formatPermissionError()
          } else if (errorMessage.includes('microphone') || errorMessage.includes('not found')) {
            error.value = 'No microphone found. Please connect a microphone and try again.'
          } else {
            error.value = errorMessage
          }
          
          // Stop listening on any error
          isListening.value = false
        }
      })

      isInitialized.value = true
      error.value = null
      
      // Initialize wake word detection if enabled
      if (wakeWordEnabled.value) {
        initWakeWordRecognition()
      }
      
      return true
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to initialize Voice Actions SDK'
      console.error('Failed to initialize Voice Actions SDK:', err)
      return false
    }
  }
  
  // Initialize wake word recognition
  const initWakeWordRecognition = () => {
    try {
      const SpeechRecognition = (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition
      if (!SpeechRecognition) {
        console.warn('⚠️ Wake word detection not supported: SpeechRecognition API not available')
        return
      }

      wakeWordRecognition.value = new SpeechRecognition()
      wakeWordRecognition.value.continuous = true
      wakeWordRecognition.value.interimResults = true
      wakeWordRecognition.value.lang = locale.value

      wakeWordRecognition.value.onresult = (event: any) => {
        // Skip if voice control is already active
        if (isListening.value) {
          return
        }

        // Get all results (interim and final) to catch wake words quickly
        // Check both final and interim results for faster detection
        let transcript = ''
        for (let i = 0; i < event.results.length; i++) {
          const result = event.results[i]
          transcript += result[0].transcript + ' '
        }
        transcript = transcript.toLowerCase().trim()

        if (import.meta.env.DEV && transcript) {
          console.log('🔍 Wake word check:', transcript)
        }

        // Check if any wake word is detected (exact match or contains)
        const detectedWakeWord = WAKE_WORDS.some(wakeWord => {
          const lowerWakeWord = wakeWord.toLowerCase()
          // Check for exact match or if wake word is at the start/end of transcript
          return transcript === lowerWakeWord || 
                 transcript.startsWith(lowerWakeWord + ' ') ||
                 transcript.endsWith(' ' + lowerWakeWord) ||
                 transcript.includes(' ' + lowerWakeWord + ' ')
        })

        if (detectedWakeWord && !isListening.value) {
          console.log('✅ Wake word detected! Activating voice control...')
          console.log('🎤 Detected wake word in transcript:', transcript)
          
          // Stop wake word recognition temporarily
          try {
            if (wakeWordRecognition.value) {
              wakeWordRecognition.value.stop()
            }
            isWakeWordListening.value = false
          } catch (e) {
            // Ignore errors
            console.warn('⚠️ Error stopping wake word recognition:', e)
          }
          
          // Start voice control
          startListening().then(() => {
            // Restart wake word listening after voice control stops
            // This will be handled by stopListening()
          }).catch((err) => {
            console.error('❌ Failed to start voice control after wake word:', err)
            // Restart wake word listening if voice control failed to start
            if (wakeWordEnabled.value) {
              setTimeout(() => {
                startWakeWordListening()
              }, 1000)
            }
          })
        }
      }

      wakeWordRecognition.value.onerror = (event: any) => {
        // Don't show errors for wake word recognition - it's optional
        if (import.meta.env.DEV) {
          console.warn('⚠️ Wake word recognition error:', event.error)
        }
        // Try to restart wake word listening
        if (wakeWordEnabled.value && !isListening.value) {
          setTimeout(() => {
            startWakeWordListening()
          }, 1000)
        }
      }

      wakeWordRecognition.value.onend = () => {
        // Auto-restart wake word listening if it's enabled and voice control is not active
        if (wakeWordEnabled.value && !isListening.value && !isWakeWordListening.value) {
          setTimeout(() => {
            startWakeWordListening()
          }, 500)
        }
      }

      // Start wake word listening
      startWakeWordListening()
    } catch (err) {
      console.error('Failed to initialize wake word recognition:', err)
    }
  }

  // Start wake word listening
  const startWakeWordListening = () => {
    if (!wakeWordRecognition.value || isWakeWordListening.value || isListening.value) {
      return
    }

    try {
      wakeWordRecognition.value.start()
      isWakeWordListening.value = true
      if (import.meta.env.DEV) {
        console.log('👂 Wake word listening started')
      }
    } catch (err) {
      // Ignore errors (might already be started)
      if (import.meta.env.DEV) {
        console.warn('⚠️ Could not start wake word listening:', err)
      }
    }
  }

  // Stop wake word listening
  const stopWakeWordListening = () => {
    if (!wakeWordRecognition.value || !isWakeWordListening.value) {
      return
    }

    try {
      wakeWordRecognition.value.stop()
      isWakeWordListening.value = false
      if (import.meta.env.DEV) {
        console.log('🔇 Wake word listening stopped')
      }
    } catch (err) {
      // Ignore errors
    }
  }

  const formatPermissionError = (errorMessage: string): string => {
    // Detect browser for specific instructions
    const isChrome = /Chrome/.test(navigator.userAgent) && !/Edge/.test(navigator.userAgent)
    const isSafari = /Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent)
    const isFirefox = /Firefox/.test(navigator.userAgent)
    
    let instructions = 'Microphone permission is required.\n\n'
    
    if (isChrome || /Edge/.test(navigator.userAgent)) {
      instructions += 'Chrome/Edge:\n'
      instructions += '1. Click the lock/camera icon in the address bar\n'
      instructions += '2. Find "Microphone" and select "Allow"\n'
      instructions += '3. Refresh the page and try again'
    } else if (isSafari) {
      instructions += 'Safari:\n'
      instructions += '1. Go to Safari > Settings > Websites > Microphone\n'
      instructions += '2. Find this site and select "Allow"\n'
      instructions += '3. Refresh the page and try again'
    } else if (isFirefox) {
      instructions += 'Firefox:\n'
      instructions += '1. Click the shield icon in the address bar\n'
      instructions += '2. Click "Permissions" > "Use the Microphone" > "Allow"\n'
      instructions += '3. Refresh the page and try again'
    } else {
      instructions += '1. Click the lock/camera icon in the address bar\n'
      instructions += '2. Allow microphone access\n'
      instructions += '3. Refresh the page and try again'
    }
    
    return instructions
  }

  const startListening = async () => {
    if (!sdk.value || !canListen.value) {
      console.warn('⚠️ Cannot start listening:', {
        hasSDK: !!sdk.value,
        canListen: canListen.value,
        isSupported: isSupported.value,
        isInitialized: isInitialized.value,
        hasError: !!error.value
      })
      return false
    }

    try {
      // Stop wake word listening when starting voice control
      if (isWakeWordListening.value) {
        stopWakeWordListening()
      }
      
      // Clear any previous errors
      error.value = null
      
      console.log('🎤 Starting voice recognition...')
      
      // Try to start directly - browser will show permission prompt automatically
      // Don't check permission first as it might not work in all browsers
      await sdk.value.start()
      isListening.value = true
      error.value = null
      console.log('✅ Voice recognition started successfully')
      return true
    } catch (err) {
      const errorMessage = err instanceof Error ? err.message : 'Failed to start listening'
      
      console.error('❌ Failed to start voice listening:', err)
      
      // Process error message to provide better instructions
      if (errorMessage.includes('Network error') || errorMessage.includes('network')) {
        const isLocalhost = typeof window !== 'undefined' && window.location.hostname === 'localhost'
        const isHttps = typeof window !== 'undefined' && window.location.protocol === 'https:'
        const isOnline = typeof navigator !== 'undefined' && navigator.onLine
        
        let networkErrorMsg = 'Speech Recognition service error.\n\n'
        networkErrorMsg += 'This usually happens when:\n'
        networkErrorMsg += '1. Speech Recognition service is temporarily unavailable\n'
        networkErrorMsg += '2. Browser cannot connect to the speech service\n'
        networkErrorMsg += '3. Network connectivity issues\n\n'
        
        if (!isOnline) {
          networkErrorMsg += '⚠️ You appear to be offline. Please check your internet connection.\n\n'
        }
        
        networkErrorMsg += 'Try:\n'
        networkErrorMsg += '1. Click "Try Again" button below\n'
        networkErrorMsg += '2. Refresh the page and try again\n'
        networkErrorMsg += '3. Check your internet connection\n'
        networkErrorMsg += '4. Try again in a few moments\n'
        
        if (!isHttps && !isLocalhost) {
          networkErrorMsg += '\n⚠️ Note: Speech Recognition may require HTTPS in production.'
        }
        
        console.warn('🌐 Network status:', {
          online: isOnline,
          hostname: typeof window !== 'undefined' ? window.location.hostname : 'unknown',
          protocol: typeof window !== 'undefined' ? window.location.protocol : 'unknown'
        })
        
        error.value = networkErrorMsg
      } else if (errorMessage.includes('permission') || errorMessage.includes('not-allowed') || errorMessage.includes('denied')) {
        error.value = formatPermissionError(errorMessage)
      } else if (errorMessage.includes('microphone') || errorMessage.includes('not found')) {
        error.value = 'No microphone found. Please connect a microphone and try again.'
      } else {
        error.value = errorMessage
      }
      
      isListening.value = false
      return false
    }
  }

  const stopListening = () => {
    if (!sdk.value || !isListening.value) {
      return
    }

    try {
      sdk.value.stop()
      isListening.value = false
      error.value = null
      
      // Restart wake word listening after stopping voice control
      if (wakeWordEnabled.value && isInitialized.value) {
        setTimeout(() => {
          startWakeWordListening()
        }, 500)
      }
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to stop listening'
    }
  }

  const toggleListening = async () => {
    if (isListening.value) {
      stopListening()
    } else {
      await startListening()
    }
  }

  const setLocale = (newLocale: string) => {
    locale.value = newLocale
    if (sdk.value) {
      sdk.value.setLocale(newLocale)
    }
  }

  const handleCommand = (command: VoiceCommand) => {
    // Log command for debugging
    console.log('🎤 Voice command received:', command)
    console.log('🎤 Executing action:', command.action)

    // Handle platform-specific commands
    switch (command.action) {
      case 'navigate-home':
        if (router) {
          router.push('/')
        } else {
          window.location.href = '/'
        }
        break
      case 'navigate-events':
        if (router) {
          router.push('/events')
        } else {
          window.location.href = '/events'
        }
        break
      case 'navigate-news':
        if (router) {
          router.push('/news')
        } else {
          window.location.href = '/news'
        }
        break
      case 'navigate-about':
        if (router) {
          router.push('/about')
        } else {
          window.location.href = '/about'
        }
        break
      case 'navigate-faq':
        if (router) {
          router.push('/faq')
        } else {
          window.location.href = '/faq'
        }
        break
      case 'navigate-contact':
        if (router) {
          router.push('/contact')
        } else {
          window.location.href = '/contact'
        }
        break
      case 'navigate-signin':
        if (router) {
          router.push('/signin')
        } else {
          window.location.href = '/signin'
        }
        break
      case 'navigate-signup':
        if (router) {
          router.push('/signup')
        } else {
          window.location.href = '/signup'
        }
        break
      case 'navigate-subscribe':
        if (router) {
          router.push('/subscribe')
        } else {
          window.location.href = '/subscribe'
        }
        break
      case 'navigate-search':
        if (router) {
          router.push('/search')
        } else {
          window.location.href = '/search'
        }
        break
      case 'navigate-privacy':
        if (router) {
          router.push('/privacy')
        } else {
          window.location.href = '/privacy'
        }
        break
      case 'navigate-terms':
        if (router) {
          router.push('/terms')
        } else {
          window.location.href = '/terms'
        }
        break
      case 'navigate-cookies':
        if (router) {
          router.push('/cookies')
        } else {
          window.location.href = '/cookies'
        }
        break
      case 'navigate-settings':
        if (router) {
          router.push('/settings')
        } else {
          window.location.href = '/settings'
        }
        break
      case 'navigate-disclaimer':
        if (router) {
          router.push('/disclaimer')
        } else {
          window.location.href = '/disclaimer'
        }
        break
      case 'enable-voice-control':
      case 'turn-on-voice-control':
      case 'voice-control-on':
        toggleVoiceControlSetting(true)
        break
      case 'disable-voice-control':
      case 'turn-off-voice-control':
      case 'voice-control-off':
        toggleVoiceControlSetting(false)
        break
      case 'toggle-voice-control':
        toggleVoiceControlSetting()
        break
      // Alternative phrases for navigation
      case 'go-to-privacy':
      case 'open-privacy':
      case 'show-privacy':
        if (router) {
          router.push('/privacy')
        } else {
          window.location.href = '/privacy'
        }
        break
      case 'go-to-terms':
      case 'open-terms':
      case 'show-terms':
        if (router) {
          router.push('/terms')
        } else {
          window.location.href = '/terms'
        }
        break
      case 'go-to-cookies':
      case 'open-cookies':
      case 'show-cookies':
        if (router) {
          router.push('/cookies')
        } else {
          window.location.href = '/cookies'
        }
        break
      case 'go-to-disclaimer':
      case 'open-disclaimer':
      case 'show-disclaimer':
        if (router) {
          router.push('/disclaimer')
        } else {
          window.location.href = '/disclaimer'
        }
        break
      case 'go-to-settings':
      case 'open-settings':
      case 'show-settings':
        if (router) {
          router.push('/settings')
        } else {
          window.location.href = '/settings'
        }
        break
      case 'scroll-to-top':
        window.scrollTo({ top: 0, behavior: 'smooth' })
        break
      case 'scroll-to-bottom':
        window.scrollTo({ top: document.documentElement.scrollHeight, behavior: 'smooth' })
        break
      case 'scroll-down':
        // Explicitly handle scroll down to ensure it works
        window.scrollBy({ top: 300, behavior: 'smooth' })
        if (import.meta.env.DEV) {
          console.log('✅ Executed scroll down')
        }
        break
      case 'scroll-up':
        // Explicitly handle scroll up to ensure it works
        window.scrollBy({ top: -300, behavior: 'smooth' })
        if (import.meta.env.DEV) {
          console.log('✅ Executed scroll up')
        }
        break
      case 'search':
        // Focus search box if available
        const searchBox = document.querySelector('input[type="search"], input[placeholder*="Search"]') as HTMLInputElement
        if (searchBox) {
          searchBox.focus()
        }
        break
      // Video/Article Commands
      case 'like-video':
      case 'like-article':
      case 'like-content':
        handleLikeContent()
        break
      case 'comment-video':
      case 'comment-article':
      case 'comment-content':
        handleOpenCommentSection()
        break
      case 'share-video':
      case 'share-article':
      case 'share-content':
        handleOpenShareModal()
        break
      case 'play-video':
        handlePlayVideo()
        break
      case 'pause-video':
        handlePauseVideo()
        break
      case 'open-video':
      case 'view-video':
      case 'watch-video':
        handleOpenVideo()
        break
      case 'post-comment':
      case 'submit-comment':
      case 'add-comment':
        handlePostComment()
        break
      case 'share-to-facebook':
        handleShareToFacebook()
        break
      case 'share-to-x':
      case 'share-to-twitter':
        handleShareToX()
        break
      case 'share-to-whatsapp':
        handleShareToWhatsApp()
        break
      case 'share-to-messenger':
        handleShareToMessenger()
        break
      case 'copy-link':
        handleCopyLink()
        break
      default:
        // Log unhandled commands for debugging
        if (import.meta.env.DEV) {
          console.log('Unhandled voice command:', command)
        }
    }
  }

  // Video/Content Interaction Handlers
  const handleLikeContent = () => {
    // Find and click the like button
    const likeButton = document.querySelector('button[class*="like"], button:has(svg[viewBox*="24"])') as HTMLButtonElement
    if (likeButton) {
      likeButton.click()
      console.log('✅ Liked content via voice command')
    } else {
      // Try to find InteractiveContent like button
      const interactiveLike = document.querySelector('.action-buttons button:first-child') as HTMLButtonElement
      if (interactiveLike) {
        interactiveLike.click()
        console.log('✅ Liked content via voice command')
      } else {
        console.warn('⚠️ Like button not found')
      }
    }
  }

  const handleOpenCommentSection = () => {
    // Find and click the comment button to open comments section
    // Try multiple selectors
    let commentButton = document.querySelector('.action-buttons button:nth-child(2)') as HTMLButtonElement
    
    if (!commentButton) {
      commentButton = Array.from(document.querySelectorAll('button')).find(btn => 
        btn.textContent?.toLowerCase().includes('comment')
      ) as HTMLButtonElement
    }
    
    if (commentButton) {
      commentButton.click()
      // Focus on comment textarea
      setTimeout(() => {
        const textarea = document.querySelector('textarea[placeholder*="comment"], textarea[placeholder*="thoughts"], textarea[placeholder*="Share"]') as HTMLTextAreaElement
        if (textarea) {
          textarea.focus()
          console.log('✅ Opened comment section and focused textarea')
        }
      }, 300)
    } else {
      console.warn('⚠️ Comment button not found')
    }
  }

  const handleOpenShareModal = () => {
    // Find and click the share button
    // Try multiple selectors
    let shareButton = document.querySelector('.action-buttons button:nth-child(3)') as HTMLButtonElement
    
    if (!shareButton) {
      shareButton = Array.from(document.querySelectorAll('button')).find(btn => 
        btn.textContent?.toLowerCase().includes('share')
      ) as HTMLButtonElement
    }
    
    if (shareButton) {
      shareButton.click()
      console.log('✅ Opened share modal via voice command')
    } else {
      console.warn('⚠️ Share button not found')
    }
  }

  const handlePlayVideo = () => {
    // Find video element and play it
    const video = document.querySelector('video') as HTMLVideoElement
    if (video) {
      video.play()
      console.log('✅ Playing video via voice command')
    } else {
      // Try to find play button
      const playButton = document.querySelector('button[aria-label*="play"], button:has(svg[viewBox*="24"]):has-text("play")') as HTMLButtonElement
      if (playButton) {
        playButton.click()
        console.log('✅ Clicked play button via voice command')
      } else {
        console.warn('⚠️ Video or play button not found')
      }
    }
  }

  const handlePauseVideo = () => {
    // Find video element and pause it
    const video = document.querySelector('video') as HTMLVideoElement
    if (video) {
      video.pause()
      console.log('✅ Paused video via voice command')
    } else {
      // Try to find pause button
      const pauseButton = document.querySelector('button[aria-label*="pause"], button:has(svg[viewBox*="24"]):has-text("pause")') as HTMLButtonElement
      if (pauseButton) {
        pauseButton.click()
        console.log('✅ Clicked pause button via voice command')
      } else {
        console.warn('⚠️ Video or pause button not found')
      }
    }
  }

  const handleOpenVideo = () => {
    // Find first video/article link and click it
    const videoLink = document.querySelector('a[href*="/article"], a[href*="/video"], a[href*="/news"]') as HTMLAnchorElement
    if (videoLink) {
      videoLink.click()
      console.log('✅ Opened video/article via voice command')
    } else {
      console.warn('⚠️ Video/article link not found')
    }
  }

  // State for voice comment
  const isRecordingComment = ref(false)
  const voiceCommentText = ref('')
  let commentTranscript = ''

  const handlePostComment = async () => {
    // Find comment textarea
    const textarea = document.querySelector('textarea[placeholder*="comment"], textarea[placeholder*="thoughts"], textarea[placeholder*="Share"]') as HTMLTextAreaElement
    if (!textarea) {
      // Open comment section first
      handleOpenCommentSection()
      // Wait for textarea to appear
      setTimeout(() => {
        handlePostComment()
      }, 500)
      return
    }

    // If we're already recording, stop and post
    if (isRecordingComment.value && commentTranscript) {
      // Stop listening
      if (sdk.value && isListening.value) {
        await sdk.value.stop()
        isListening.value = false
      }
      
      // Set the text in textarea
      textarea.value = commentTranscript.trim()
      textarea.dispatchEvent(new Event('input', { bubbles: true }))
      
      // Find and click post button
      setTimeout(() => {
        const postButton = document.querySelector('button:has-text("Post"), button:has-text("Comment"), button[type="submit"]') as HTMLButtonElement
        if (postButton && !postButton.disabled) {
          postButton.click()
          console.log('✅ Posted comment via voice:', commentTranscript)
          commentTranscript = ''
          voiceCommentText.value = ''
          isRecordingComment.value = false
        }
      }, 100)
    } else {
      // Start recording comment - open comment section and focus textarea
      isRecordingComment.value = true
      commentTranscript = ''
      voiceCommentText.value = ''
      
      // Open comment section first
      handleOpenCommentSection()
      
      // Focus on textarea
      setTimeout(() => {
        if (textarea) {
          textarea.focus()
          console.log('🎤 Recording comment text... Say your comment now, then say "post comment"')
          
          // Start capturing transcript from recognition
          if (sdk.value && (sdk.value as any).recognition) {
            const recognition = (sdk.value as any).recognition
            const originalOnResult = recognition.onresult
            
            recognition.onresult = (event: any) => {
              // Call original handler first
              if (originalOnResult) {
                originalOnResult.call(recognition, event)
              }
              
              // Capture transcript for comment
              if (isRecordingComment.value) {
                const results = Array.from(event.results)
                const transcript = results
                  .map((result: any) => result[0].transcript)
                  .join(' ')
                  .trim()
                
                if (transcript && !transcript.toLowerCase().includes('post comment') && !transcript.toLowerCase().includes('submit comment')) {
                  commentTranscript = transcript
                  if (textarea) {
                    textarea.value = commentTranscript
                    textarea.dispatchEvent(new Event('input', { bubbles: true }))
                  }
                  console.log('📝 Comment text captured:', commentTranscript)
                }
              }
            }
          }
        }
      }, 500)
    }
  }

  const handleShareToFacebook = () => {
    // Open share modal first if not open
    const shareModal = document.querySelector('.share-modal')
    if (!shareModal) {
      handleOpenShareModal()
      // Wait for modal to open
      setTimeout(() => {
        handleShareToFacebook()
      }, 300)
      return
    }
    
    // Find Facebook share button in share modal
    const shareButtons = Array.from(document.querySelectorAll('.share-modal button, [class*="share"] button'))
    const shareButton = shareButtons.find(btn => 
      btn.textContent?.toLowerCase().includes('facebook')
    ) as HTMLButtonElement
    
    if (shareButton) {
      shareButton.click()
      console.log('✅ Shared to Facebook via voice command')
    } else {
      console.warn('⚠️ Facebook share button not found')
    }
  }

  const handleShareToX = () => {
    // Open share modal first if not open
    const shareModal = document.querySelector('.share-modal')
    if (!shareModal) {
      handleOpenShareModal()
      // Wait for modal to open
      setTimeout(() => {
        handleShareToX()
      }, 300)
      return
    }
    
    // Find X/Twitter share button in share modal
    const shareButtons = Array.from(document.querySelectorAll('.share-modal button, [class*="share"] button'))
    const shareButton = shareButtons.find(btn => 
      btn.textContent?.toLowerCase().includes('x') || 
      btn.textContent?.toLowerCase().includes('twitter')
    ) as HTMLButtonElement
    
    if (shareButton) {
      shareButton.click()
      console.log('✅ Shared to X via voice command')
    } else {
      console.warn('⚠️ X share button not found')
    }
  }

  const handleShareToWhatsApp = () => {
    // Open share modal first if not open
    const shareModal = document.querySelector('.share-modal')
    if (!shareModal) {
      handleOpenShareModal()
      // Wait for modal to open
      setTimeout(() => {
        handleShareToWhatsApp()
      }, 300)
      return
    }
    
    // Find WhatsApp share button in share modal
    const shareButtons = Array.from(document.querySelectorAll('.share-modal button, [class*="share"] button'))
    const shareButton = shareButtons.find(btn => 
      btn.textContent?.toLowerCase().includes('whatsapp')
    ) as HTMLButtonElement
    
    if (shareButton) {
      shareButton.click()
      console.log('✅ Shared to WhatsApp via voice command')
    } else {
      console.warn('⚠️ WhatsApp share button not found')
    }
  }

  const handleShareToMessenger = () => {
    // Open share modal first if not open
    const shareModal = document.querySelector('.share-modal')
    if (!shareModal) {
      handleOpenShareModal()
      // Wait for modal to open
      setTimeout(() => {
        handleShareToMessenger()
      }, 300)
      return
    }
    
    // Find Messenger share button in share modal
    const shareButtons = Array.from(document.querySelectorAll('.share-modal button, [class*="share"] button'))
    const shareButton = shareButtons.find(btn => 
      btn.textContent?.toLowerCase().includes('messenger')
    ) as HTMLButtonElement
    
    if (shareButton) {
      shareButton.click()
      console.log('✅ Shared to Messenger via voice command')
    } else {
      console.warn('⚠️ Messenger share button not found')
    }
  }

  const handleCopyLink = () => {
    // Find Copy Link button in share modal
    const shareButtons = Array.from(document.querySelectorAll('.share-modal button, [class*="share"] button'))
    const copyButton = shareButtons.find(btn => 
      btn.textContent?.toLowerCase().includes('copy')
    ) as HTMLButtonElement
    
    if (copyButton) {
      copyButton.click()
      console.log('✅ Copied link via voice command')
    } else {
      // Fallback: try to copy current URL
      navigator.clipboard.writeText(window.location.href).then(() => {
        console.log('✅ Copied current URL to clipboard')
      }).catch(err => {
        console.error('Failed to copy link:', err)
      })
    }
  }

  const toggleVoiceControlSetting = (enabled?: boolean) => {
    try {
      // Load current settings
      const saved = localStorage.getItem('stargate-settings')
      let currentSettings: any = {
        voiceControl: true,
        voiceLanguage: 'en-US'
      }
      
      if (saved) {
        try {
          currentSettings = JSON.parse(saved)
        } catch (e) {
          console.error('Error parsing settings:', e)
        }
      }
      
      // Toggle or set value
      if (enabled === undefined) {
        // Toggle
        currentSettings.voiceControl = !currentSettings.voiceControl
      } else {
        // Set specific value
        currentSettings.voiceControl = enabled
      }
      
      // Save settings
      localStorage.setItem('stargate-settings', JSON.stringify(currentSettings))
      
      // Dispatch event to notify VoiceControl component
      window.dispatchEvent(new CustomEvent('voice-control-toggle', {
        detail: {
          enabled: currentSettings.voiceControl,
          language: currentSettings.voiceLanguage || 'en-US'
        }
      }))
      
      console.log(`✅ Voice Control ${currentSettings.voiceControl ? 'enabled' : 'disabled'} via voice command`)
      
      // If disabling, stop listening and destroy SDK
      if (!currentSettings.voiceControl) {
        stopListening()
        destroy()
      } else {
        // If enabling, reinitialize SDK
        initialize()
      }
    } catch (err) {
      console.error('Error toggling voice control setting:', err)
    }
  }

  const clearError = () => {
    error.value = null
  }

  const destroy = () => {
    // Stop wake word listening
    stopWakeWordListening()
    if (wakeWordRecognition.value) {
      wakeWordRecognition.value = null
    }
    
    if (sdk.value) {
      sdk.value.destroy()
      sdk.value = null
    }
    isListening.value = false
    isInitialized.value = false
    isWakeWordListening.value = false
    error.value = null
    lastCommand.value = null
  }

  return {
    // State
    sdk,
    isListening,
    isSupported,
    isInitialized,
    error,
    locale,
    lastCommand,
    isWakeWordListening,
    wakeWordEnabled,
    
    // Getters
    canListen,
    
    // Actions
    initialize,
    startListening,
    stopListening,
    toggleListening,
    setLocale,
    setRouter,
    clearError,
    destroy,
    startWakeWordListening,
    stopWakeWordListening
  }
})

