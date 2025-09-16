// PWA Service for Stargate.ci
export interface PWAInstallPrompt {
  prompt: () => Promise<void>;
  userChoice: Promise<{ outcome: 'accepted' | 'dismissed' }>;
}

export interface PWAStatus {
  isInstalled: boolean;
  isInstallable: boolean;
  isOnline: boolean;
  hasUpdate: boolean;
  version: string;
}

class PWAService {
  private installPrompt: PWAInstallPrompt | null = null;
  private swRegistration: ServiceWorkerRegistration | null = null;
  private updateAvailable = false;

  // Initialize PWA service
  async initialize(): Promise<void> {
    await this.registerServiceWorker();
    this.setupInstallPrompt();
    this.setupOnlineStatus();
    this.setupUpdateCheck();
  }

  // Register service worker
  private async registerServiceWorker(): Promise<void> {
    if ('serviceWorker' in navigator) {
      try {
        this.swRegistration = await navigator.serviceWorker.register('/sw.js');
        console.log('Service Worker registered successfully');

        // Listen for updates
        this.swRegistration.addEventListener('updatefound', () => {
          const newWorker = this.swRegistration!.installing;
          if (newWorker) {
            newWorker.addEventListener('statechange', () => {
              if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                this.updateAvailable = true;
                this.notifyUpdateAvailable();
              }
            });
          }
        });
      } catch (error) {
        console.error('Service Worker registration failed:', error);
      }
    }
  }

  // Setup install prompt
  private setupInstallPrompt(): void {
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      this.installPrompt = e as unknown as PWAInstallPrompt;
      this.notifyInstallAvailable();
    });

    window.addEventListener('appinstalled', () => {
      console.log('PWA was installed');
      this.installPrompt = null;
      this.notifyInstalled();
    });
  }

  // Setup online status monitoring
  private setupOnlineStatus(): void {
    const updateOnlineStatus = () => {
      this.notifyOnlineStatusChange(navigator.onLine);
    };

    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
  }

  // Setup update checking
  private setupUpdateCheck(): void {
    if (this.swRegistration) {
      this.swRegistration.addEventListener('updatefound', () => {
        this.updateAvailable = true;
        this.notifyUpdateAvailable();
      });
    }
  }

  // Show install prompt
  async showInstallPrompt(): Promise<boolean> {
    if (!this.installPrompt) {
      return false;
    }

    try {
      await this.installPrompt.prompt();
      const choiceResult = await this.installPrompt.userChoice;
      
      if (choiceResult.outcome === 'accepted') {
        console.log('User accepted the install prompt');
        return true;
      } else {
        console.log('User dismissed the install prompt');
        return false;
      }
    } catch (error) {
      console.error('Error showing install prompt:', error);
      return false;
    }
  }

  // Check if app is installable
  isInstallable(): boolean {
    // Check if we have the install prompt
    if (this.installPrompt !== null) {
      return true;
    }
    
    // Additional checks for mobile devices
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
    const isAndroid = /Android/.test(navigator.userAgent);
    
    // For iOS, check if it's not already installed and not in standalone mode
    if (isIOS) {
      return !this.isInstalled() && !(window.navigator as any).standalone;
    }
    
    // For Android, check if it's not already installed
    if (isAndroid) {
      return !this.isInstalled();
    }
    
    // For other devices, check if service worker is registered and not installed
    return !this.isInstalled() && this.swRegistration !== null;
  }

  // Check if app is installed
  isInstalled(): boolean {
    return window.matchMedia('(display-mode: standalone)').matches ||
           (window.navigator as any).standalone === true;
  }

  // Check if online
  isOnline(): boolean {
    return navigator.onLine;
  }

  // Get PWA status
  getStatus(): PWAStatus {
    return {
      isInstalled: this.isInstalled(),
      isInstallable: this.isInstallable(),
      isOnline: this.isOnline(),
      hasUpdate: this.updateAvailable,
      version: '1.0.0'
    };
  }

  // Update service worker
  async updateServiceWorker(): Promise<void> {
    if (this.swRegistration && this.updateAvailable) {
      try {
        await this.swRegistration.update();
        console.log('Service Worker updated');
        this.updateAvailable = false;
      } catch (error) {
        console.error('Error updating Service Worker:', error);
      }
    }
  }

  // Request notification permission
  async requestNotificationPermission(): Promise<NotificationPermission> {
    if ('Notification' in window) {
      return await Notification.requestPermission();
    }
    return 'denied';
  }

  // Show notification
  showNotification(title: string, options?: NotificationOptions): void {
    if (this.swRegistration && 'Notification' in window && Notification.permission === 'granted') {
      this.swRegistration.showNotification(title, {
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        ...options
      });
    }
  }

  // Background sync
  async requestBackgroundSync(tag: string): Promise<void> {
    if (this.swRegistration && 'sync' in window.ServiceWorkerRegistration.prototype) {
      try {
        await (this.swRegistration as any).sync.register(tag);
        console.log('Background sync registered:', tag);
      } catch (error) {
        console.error('Background sync registration failed:', error);
      }
    }
  }

  // Periodic background sync
  async requestPeriodicSync(tag: string, minInterval: number): Promise<void> {
    if (this.swRegistration && 'periodicSync' in window.ServiceWorkerRegistration.prototype) {
      try {
        await (this.swRegistration as any).periodicSync.register(tag, {
          minInterval
        });
        console.log('Periodic sync registered:', tag);
      } catch (error) {
        console.error('Periodic sync registration failed:', error);
      }
    }
  }

  // Cache management
  async clearCache(): Promise<void> {
    if ('caches' in window) {
      const cacheNames = await caches.keys();
      await Promise.all(
        cacheNames.map(cacheName => caches.delete(cacheName))
      );
      console.log('All caches cleared');
    }
  }

  // Get cache size
  async getCacheSize(): Promise<number> {
    if (!('caches' in window)) return 0;

    let totalSize = 0;
    const cacheNames = await caches.keys();

    for (const cacheName of cacheNames) {
      const cache = await caches.open(cacheName);
      const requests = await cache.keys();
      
      for (const request of requests) {
        const response = await cache.match(request);
        if (response) {
          const blob = await response.blob();
          totalSize += blob.size;
        }
      }
    }

    return totalSize;
  }

  // Notify install available
  private notifyInstallAvailable(): void {
    window.dispatchEvent(new CustomEvent('pwa-install-available'));
  }

  // Notify installed
  private notifyInstalled(): void {
    window.dispatchEvent(new CustomEvent('pwa-installed'));
  }

  // Notify online status change
  private notifyOnlineStatusChange(isOnline: boolean): void {
    window.dispatchEvent(new CustomEvent('pwa-online-status', {
      detail: { isOnline }
    }));
  }

  // Notify update available
  private notifyUpdateAvailable(): void {
    window.dispatchEvent(new CustomEvent('pwa-update-available'));
  }
}

export const pwaService = new PWAService();
