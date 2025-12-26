import { ref, watch, onMounted } from 'vue'

export type Theme = 'light' | 'dark' | 'auto'

const theme = ref<Theme>('dark')
const isDark = ref(true)

export function useTheme() {
  const applyTheme = (newTheme: Theme) => {
    const root = document.documentElement
    
    if (newTheme === 'dark') {
      root.classList.add('dark')
      root.style.colorScheme = 'dark'
      isDark.value = true
    } else if (newTheme === 'light') {
      root.classList.remove('dark')
      root.style.colorScheme = 'light'
      isDark.value = false
    } else {
      // Auto - follow system preference
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
      if (prefersDark) {
        root.classList.add('dark')
        root.style.colorScheme = 'dark'
        isDark.value = true
      } else {
        root.classList.remove('dark')
        root.style.colorScheme = 'light'
        isDark.value = false
      }
    }
    
    theme.value = newTheme
  }

  const loadTheme = () => {
    const saved = localStorage.getItem('stargate-settings')
    if (saved) {
      try {
        const settings = JSON.parse(saved)
        if (settings.theme) {
          applyTheme(settings.theme)
          return
        }
      } catch (e) {
        console.error('Error loading theme:', e)
      }
    }
    // Default to dark theme
    applyTheme('dark')
  }

  const setTheme = (newTheme: Theme) => {
    applyTheme(newTheme)
    
    // Save to settings
    const saved = localStorage.getItem('stargate-settings')
    let settings: any = {}
    if (saved) {
      try {
        settings = JSON.parse(saved)
      } catch (e) {
        console.error('Error parsing settings:', e)
      }
    }
    settings.theme = newTheme
    localStorage.setItem('stargate-settings', JSON.stringify(settings))
    
    // Dispatch event for other components
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: newTheme } }))
  }

  const initTheme = () => {
    loadTheme()
    
    // Listen for system theme changes if auto mode is enabled
    if (theme.value === 'auto') {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
      const handleChange = (e: MediaQueryListEvent) => {
        if (theme.value === 'auto') {
          applyTheme('auto')
        }
      }
      mediaQuery.addEventListener('change', handleChange)
      
      // Cleanup on unmount would be handled by component
      return () => {
        mediaQuery.removeEventListener('change', handleChange)
      }
    }
  }

  // Watch for theme changes from settings
  const watchThemeChanges = () => {
    if (typeof window === 'undefined') return
    
    window.addEventListener('storage', (e) => {
      if (e.key === 'stargate-settings') {
        loadTheme()
      }
    })
    
    window.addEventListener('theme-changed', ((e: CustomEvent) => {
      if (e.detail?.theme) {
        applyTheme(e.detail.theme)
      }
    }) as EventListener)
  }

  return {
    theme,
    isDark,
    setTheme,
    loadTheme,
    initTheme: () => {
      initTheme()
      watchThemeChanges()
    }
  }
}

