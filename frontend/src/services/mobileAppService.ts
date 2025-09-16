/**
 * Mobile App Service - Handles mobile app functionality and native features
 * Provides mobile-specific features, device detection, and native app integration
 */

export interface MobileDevice {
  isMobile: boolean
  isTablet: boolean
  isDesktop: boolean
  platform: 'ios' | 'android' | 'web' | 'unknown'
  userAgent: string
  screenSize: {
    width: number
    height: number
  }
  orientation: 'portrait' | 'landscape'
  touchSupport: boolean
  devicePixelRatio: number
}

export interface MobileAppConfig {
  appName: string
  version: string
  buildNumber: string
  bundleId: string
  deepLinkScheme: string
  appStoreUrl: string
  playStoreUrl: string
  features: {
    pushNotifications: boolean
    biometricAuth: boolean
    offlineMode: boolean
    cameraAccess: boolean
    locationAccess: boolean
    contactsAccess: boolean
    fileSystemAccess: boolean
  }
}

export interface MobileNotification {
  id: string
  title: string
  body: string
  icon?: string
  badge?: number
  data?: Record<string, any>
  timestamp: string
  read: boolean
  actionUrl?: string
}

export interface MobileAppState {
  isInstalled: boolean
  isOnline: boolean
  lastSync: string
  cacheSize: number
  permissions: {
    notifications: boolean
    camera: boolean
    location: boolean
    contacts: boolean
    storage: boolean
  }
  settings: {
    darkMode: boolean
    notifications: boolean
    biometricAuth: boolean
    autoSync: boolean
    offlineMode: boolean
  }
}

class MobileAppService {
  private device: MobileDevice
  private config: MobileAppConfig
  private state: MobileAppState
  private notifications: MobileNotification[] = []
  private storageKey = 'stargate_mobile_app'

  constructor() {
    this.device = this.detectDevice()
    this.config = this.getDefaultConfig()
    this.state = this.getDefaultState()
    this.initializeService()
  }

  private detectDevice(): MobileDevice {
    const userAgent = navigator.userAgent
    const screen = window.screen
    
    // Platform detection
    let platform: 'ios' | 'android' | 'web' | 'unknown' = 'web'
    if (/iPad|iPhone|iPod/.test(userAgent)) {
      platform = 'ios'
    } else if (/Android/.test(userAgent)) {
      platform = 'android'
    }

    // Device type detection
    const isMobile = /Mobi|Android/i.test(userAgent) || platform === 'ios'
    const isTablet = /iPad|Android(?!.*Mobile)/i.test(userAgent)
    const isDesktop = !isMobile && !isTablet

    // Screen size detection
    const screenSize = {
      width: screen.width,
      height: screen.height
    }

    // Orientation detection
    const orientation = screenSize.width > screenSize.height ? 'landscape' : 'portrait'

    // Touch support
    const touchSupport = 'ontouchstart' in window || navigator.maxTouchPoints > 0

    return {
      isMobile,
      isTablet,
      isDesktop,
      platform,
      userAgent,
      screenSize,
      orientation,
      touchSupport,
      devicePixelRatio: window.devicePixelRatio || 1
    }
  }

  private getDefaultConfig(): MobileAppConfig {
    return {
      appName: 'Stargate.ci',
      version: '2.0.0',
      buildNumber: '2024.01.001',
      bundleId: 'ci.stargate.app',
      deepLinkScheme: 'stargate://',
      appStoreUrl: 'https://apps.apple.com/app/stargate-ci/id123456789',
      playStoreUrl: 'https://play.google.com/store/apps/details?id=ci.stargate.app',
      features: {
        pushNotifications: true,
        biometricAuth: true,
        offlineMode: true,
        cameraAccess: true,
        locationAccess: false,
        contactsAccess: false,
        fileSystemAccess: true
      }
    }
  }

  private getDefaultState(): MobileAppState {
    return {
      isInstalled: false,
      isOnline: navigator.onLine,
      lastSync: new Date().toISOString(),
      cacheSize: 0,
      permissions: {
        notifications: false,
        camera: false,
        location: false,
        contacts: false,
        storage: false
      },
      settings: {
        darkMode: true,
        notifications: true,
        biometricAuth: false,
        autoSync: true,
        offlineMode: false
      }
    }
  }

  private initializeService() {
    this.loadState()
    this.setupEventListeners()
    this.checkInstallation()
  }

  private loadState() {
    const stored = localStorage.getItem(this.storageKey)
    if (stored) {
      const data = JSON.parse(stored)
      this.state = { ...this.state, ...data.state }
      this.notifications = data.notifications || []
    }
  }

  private saveState() {
    const data = {
      state: this.state,
      notifications: this.notifications
    }
    localStorage.setItem(this.storageKey, JSON.stringify(data))
  }

  private setupEventListeners() {
    // Online/offline status
    window.addEventListener('online', () => {
      this.state.isOnline = true
      this.saveState()
    })

    window.addEventListener('offline', () => {
      this.state.isOnline = false
      this.saveState()
    })

    // Orientation change
    window.addEventListener('orientationchange', () => {
      setTimeout(() => {
        this.device.orientation = window.screen.width > window.screen.height ? 'landscape' : 'portrait'
        this.device.screenSize = {
          width: window.screen.width,
          height: window.screen.height
        }
      }, 100)
    })

    // Resize events
    window.addEventListener('resize', () => {
      this.device.screenSize = {
        width: window.innerWidth,
        height: window.innerHeight
      }
    })
  }

  private checkInstallation() {
    // Check if app is installed (PWA or native)
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.getRegistration().then(registration => {
        this.state.isInstalled = !!registration
        this.saveState()
      })
    }

    // Check for native app installation
    if (this.device.platform === 'ios') {
      this.checkIOSInstallation()
    } else if (this.device.platform === 'android') {
      this.checkAndroidInstallation()
    }
  }

  private checkIOSInstallation() {
    // Check if iOS app is installed
    const startTime = Date.now()
    const iframe = document.createElement('iframe')
    iframe.style.display = 'none'
    iframe.src = this.config.deepLinkScheme + 'check'
    
    iframe.onload = () => {
      const loadTime = Date.now() - startTime
      if (loadTime < 2000) {
        this.state.isInstalled = true
        this.saveState()
      }
      document.body.removeChild(iframe)
    }
    
    iframe.onerror = () => {
      document.body.removeChild(iframe)
    }
    
    document.body.appendChild(iframe)
    
    // Fallback timeout
    setTimeout(() => {
      if (document.body.contains(iframe)) {
        document.body.removeChild(iframe)
      }
    }, 2000)
  }

  private checkAndroidInstallation() {
    // Check if Android app is installed
    const startTime = Date.now()
    const iframe = document.createElement('iframe')
    iframe.style.display = 'none'
    iframe.src = 'intent://check#Intent;scheme=' + this.config.deepLinkScheme + ';package=' + this.config.bundleId + ';end'
    
    iframe.onload = () => {
      const loadTime = Date.now() - startTime
      if (loadTime < 2000) {
        this.state.isInstalled = true
        this.saveState()
      }
      document.body.removeChild(iframe)
    }
    
    iframe.onerror = () => {
      document.body.removeChild(iframe)
    }
    
    document.body.appendChild(iframe)
    
    // Fallback timeout
    setTimeout(() => {
      if (document.body.contains(iframe)) {
        document.body.removeChild(iframe)
      }
    }, 2000)
  }

  // Public API Methods
  getDevice(): MobileDevice {
    return { ...this.device }
  }

  getConfig(): MobileAppConfig {
    return { ...this.config }
  }

  getState(): MobileAppState {
    return { ...this.state }
  }

  isMobile(): boolean {
    return this.device.isMobile
  }

  isTablet(): boolean {
    return this.device.isTablet
  }

  isDesktop(): boolean {
    return this.device.isDesktop
  }

  getPlatform(): 'ios' | 'android' | 'web' | 'unknown' {
    return this.device.platform
  }

  // Installation Methods
  async installApp(): Promise<boolean> {
    if (this.device.platform === 'ios') {
      return this.installIOSApp()
    } else if (this.device.platform === 'android') {
      return this.installAndroidApp()
    } else {
      return this.installPWA()
    }
  }

  private async installIOSApp(): Promise<boolean> {
    // Redirect to App Store
    window.open(this.config.appStoreUrl, '_blank')
    return true
  }

  private async installAndroidApp(): Promise<boolean> {
    // Try to open app, fallback to Play Store
    const startTime = Date.now()
    const iframe = document.createElement('iframe')
    iframe.style.display = 'none'
    iframe.src = 'intent://open#Intent;scheme=' + this.config.deepLinkScheme + ';package=' + this.config.bundleId + ';end'
    
    document.body.appendChild(iframe)
    
    setTimeout(() => {
      if (document.body.contains(iframe)) {
        document.body.removeChild(iframe)
        // App not installed, redirect to Play Store
        window.open(this.config.playStoreUrl, '_blank')
      }
    }, 1000)
    
    return true
  }

  private async installPWA(): Promise<boolean> {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
      try {
        const registration = await navigator.serviceWorker.register('/sw.js')
        this.state.isInstalled = true
        this.saveState()
        return true
      } catch (error) {
        console.error('PWA installation failed:', error)
        return false
      }
    }
    return false
  }

  // Deep Linking
  openDeepLink(path: string, params?: Record<string, string>): boolean {
    const url = this.buildDeepLinkUrl(path, params)
    
    if (this.device.platform === 'ios') {
      return this.openIOSDeepLink(url)
    } else if (this.device.platform === 'android') {
      return this.openAndroidDeepLink(url)
    }
    
    return false
  }

  private buildDeepLinkUrl(path: string, params?: Record<string, string>): string {
    let url = this.config.deepLinkScheme + path
    
    if (params) {
      const searchParams = new URLSearchParams(params)
      url += '?' + searchParams.toString()
    }
    
    return url
  }

  private openIOSDeepLink(url: string): boolean {
    const startTime = Date.now()
    const iframe = document.createElement('iframe')
    iframe.style.display = 'none'
    iframe.src = url
    
    iframe.onload = () => {
      const loadTime = Date.now() - startTime
      if (loadTime < 2000) {
        // App opened successfully
        return true
      }
      document.body.removeChild(iframe)
    }
    
    iframe.onerror = () => {
      document.body.removeChild(iframe)
    }
    
    document.body.appendChild(iframe)
    
    setTimeout(() => {
      if (document.body.contains(iframe)) {
        document.body.removeChild(iframe)
      }
    }, 2000)
    
    return false
  }

  private openAndroidDeepLink(url: string): boolean {
    const startTime = Date.now()
    const iframe = document.createElement('iframe')
    iframe.style.display = 'none'
    iframe.src = 'intent://' + url.replace(this.config.deepLinkScheme, '') + '#Intent;scheme=' + this.config.deepLinkScheme + ';package=' + this.config.bundleId + ';end'
    
    iframe.onload = () => {
      const loadTime = Date.now() - startTime
      if (loadTime < 2000) {
        // App opened successfully
        return true
      }
      document.body.removeChild(iframe)
    }
    
    iframe.onerror = () => {
      document.body.removeChild(iframe)
    }
    
    document.body.appendChild(iframe)
    
    setTimeout(() => {
      if (document.body.contains(iframe)) {
        document.body.removeChild(iframe)
      }
    }, 2000)
    
    return false
  }

  // Notifications
  async requestNotificationPermission(): Promise<boolean> {
    if (!('Notification' in window)) {
      return false
    }

    if (Notification.permission === 'granted') {
      this.state.permissions.notifications = true
      this.saveState()
      return true
    }

    if (Notification.permission === 'denied') {
      return false
    }

    const permission = await Notification.requestPermission()
    this.state.permissions.notifications = permission === 'granted'
    this.saveState()
    return this.state.permissions.notifications
  }

  async sendNotification(title: string, body: string, options?: NotificationOptions): Promise<boolean> {
    if (!this.state.permissions.notifications) {
      return false
    }

    try {
      const notification = new Notification(title, {
        body,
        icon: '/favicon-192x192.png',
        badge: '/favicon-192x192.png',
        ...options
      })

      // Store notification
      const mobileNotification: MobileNotification = {
        id: `notif_${Date.now()}`,
        title,
        body,
        icon: options?.icon as string,
        badge: typeof options?.badge === 'number' ? options.badge : undefined,
        data: options?.data,
        timestamp: new Date().toISOString(),
        read: false
      }

      this.notifications.unshift(mobileNotification)
      
      // Keep only last 50 notifications
      if (this.notifications.length > 50) {
        this.notifications = this.notifications.slice(0, 50)
      }

      this.saveState()
      return true
    } catch (error) {
      console.error('Failed to send notification:', error)
      return false
    }
  }

  getNotifications(): MobileNotification[] {
    return [...this.notifications]
  }

  markNotificationAsRead(id: string): boolean {
    const notification = this.notifications.find(n => n.id === id)
    if (notification) {
      notification.read = true
      this.saveState()
      return true
    }
    return false
  }

  clearNotifications(): void {
    this.notifications = []
    this.saveState()
  }

  // Settings
  updateSettings(settings: Partial<MobileAppState['settings']>): void {
    this.state.settings = { ...this.state.settings, ...settings }
    this.saveState()
  }

  getSettings(): MobileAppState['settings'] {
    return { ...this.state.settings }
  }

  // Cache Management
  async clearCache(): Promise<boolean> {
    try {
      if ('caches' in window) {
        const cacheNames = await caches.keys()
        await Promise.all(
          cacheNames.map(cacheName => caches.delete(cacheName))
        )
      }
      
      this.state.cacheSize = 0
      this.saveState()
      return true
    } catch (error) {
      console.error('Failed to clear cache:', error)
      return false
    }
  }

  async getCacheSize(): Promise<number> {
    try {
      if ('caches' in window) {
        const cacheNames = await caches.keys()
        let totalSize = 0
        
        for (const cacheName of cacheNames) {
          const cache = await caches.open(cacheName)
          const keys = await cache.keys()
          totalSize += keys.length
        }
        
        this.state.cacheSize = totalSize
        this.saveState()
        return totalSize
      }
      return 0
    } catch (error) {
      console.error('Failed to get cache size:', error)
      return 0
    }
  }

  // Sync
  async syncData(): Promise<boolean> {
    if (!this.state.isOnline) {
      return false
    }

    try {
      // Simulate data sync
      await new Promise(resolve => setTimeout(resolve, 1000))
      
      this.state.lastSync = new Date().toISOString()
      this.saveState()
      return true
    } catch (error) {
      console.error('Sync failed:', error)
      return false
    }
  }

  // Utility Methods
  formatFileSize(bytes: number): string {
    const sizes = ['Bytes', 'KB', 'MB', 'GB']
    if (bytes === 0) return '0 Bytes'
    const i = Math.floor(Math.log(bytes) / Math.log(1024))
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i]
  }

  getInstallPrompt(): string {
    if (this.device.platform === 'ios') {
      return 'Install from App Store'
    } else if (this.device.platform === 'android') {
      return 'Install from Play Store'
    } else {
      return 'Install App'
    }
  }

  getInstallUrl(): string {
    if (this.device.platform === 'ios') {
      return this.config.appStoreUrl
    } else if (this.device.platform === 'android') {
      return this.config.playStoreUrl
    } else {
      return window.location.origin
    }
  }
}

export const mobileAppService = new MobileAppService()
