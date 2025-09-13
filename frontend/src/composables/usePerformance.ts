import { ref, onMounted, onUnmounted } from 'vue'

// Performance optimization composable
export function usePerformance() {
  const isVisible = ref(false)
  const isIntersecting = ref(false)
  const scrollY = ref(0)
  const isScrolling = ref(false)
  
  let scrollTimeout: number | null = null
  let intersectionObserver: IntersectionObserver | null = null

  // Throttled scroll handler
  const handleScroll = () => {
    scrollY.value = window.scrollY
    isScrolling.value = true
    
    if (scrollTimeout) {
      clearTimeout(scrollTimeout)
    }
    
    scrollTimeout = window.setTimeout(() => {
      isScrolling.value = false
    }, 150)
  }

  // Intersection observer for lazy loading
  const observeElement = (element: Element, callback: (isIntersecting: boolean) => void) => {
    if (!intersectionObserver) {
      intersectionObserver = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            callback(entry.isIntersecting)
          })
        },
        {
          rootMargin: '50px',
          threshold: 0.1
        }
      )
    }
    
    intersectionObserver.observe(element)
  }

  // Debounced resize handler
  const handleResize = (callback: () => void, delay = 250) => {
    let resizeTimeout: number | null = null
    
    return () => {
      if (resizeTimeout) {
        clearTimeout(resizeTimeout)
      }
      
      resizeTimeout = window.setTimeout(callback, delay)
    }
  }

  // Preload critical resources
  const preloadResource = (href: string, as: string = 'script') => {
    const link = document.createElement('link')
    link.rel = 'preload'
    link.href = href
    link.as = as
    document.head.appendChild(link)
  }

  // Optimize images
  const optimizeImage = (src: string, alt: string = '', loading: 'lazy' | 'eager' = 'lazy') => {
    return {
      src,
      alt,
      loading,
      decoding: 'async' as const,
      style: 'content-visibility: auto; contain-intrinsic-size: 300px 200px;'
    }
  }

  // Memory cleanup
  const cleanup = () => {
    if (scrollTimeout) {
      clearTimeout(scrollTimeout)
    }
    
    if (intersectionObserver) {
      intersectionObserver.disconnect()
    }
    
    window.removeEventListener('scroll', handleScroll)
  }

  // Initialize
  onMounted(() => {
    window.addEventListener('scroll', handleScroll)
  })

  onUnmounted(() => {
    cleanup()
  })

  return {
    isVisible,
    isIntersecting,
    scrollY,
    isScrolling,
    observeElement,
    handleResize,
    preloadResource,
    optimizeImage,
    cleanup
  }
}

// Navigation performance composable
export function useNavigationPerformance() {
  const isMenuOpen = ref(false)
  const isAnimating = ref(false)
  
  // Smooth menu toggle
  const toggleMenu = () => {
    if (isAnimating.value) return
    
    isAnimating.value = true
    isMenuOpen.value = !isMenuOpen.value
    
    // Use requestAnimationFrame for smooth animation
    requestAnimationFrame(() => {
      setTimeout(() => {
        isAnimating.value = false
      }, 150)
    })
  }

  // Close menu on route change
  const closeMenu = () => {
    if (isMenuOpen.value) {
      isMenuOpen.value = false
    }
  }

  return {
    isMenuOpen,
    isAnimating,
    toggleMenu,
    closeMenu
  }
}

// Language switching performance
export function useLanguagePerformance() {
  const isChangingLanguage = ref(false)
  
  const changeLanguage = async (locale: string, callback: () => void) => {
    if (isChangingLanguage.value) return
    
    isChangingLanguage.value = true
    
    // Use requestIdleCallback for non-critical updates
    if ('requestIdleCallback' in window) {
      requestIdleCallback(() => {
        callback()
        isChangingLanguage.value = false
      })
    } else {
      setTimeout(() => {
        callback()
        isChangingLanguage.value = false
      }, 0)
    }
  }

  return {
    isChangingLanguage,
    changeLanguage
  }
}
