// Advanced Analytics & Monitoring Service
export interface AnalyticsEvent {
  id: string;
  type: 'page_view' | 'user_action' | 'performance' | 'error' | 'custom';
  name: string;
  properties: Record<string, any>;
  timestamp: string;
  userId?: string;
  sessionId: string;
  userAgent: string;
  url: string;
  referrer?: string;
}

export interface PerformanceMetrics {
  pageLoadTime: number;
  domContentLoaded: number;
  firstContentfulPaint: number;
  largestContentfulPaint: number;
  firstInputDelay: number;
  cumulativeLayoutShift: number;
  timeToInteractive: number;
  bundleSize: number;
  memoryUsage?: number;
}

export interface UserSession {
  id: string;
  userId?: string;
  startTime: string;
  lastActivity: string;
  pageViews: number;
  events: number;
  duration: number;
  referrer?: string;
  userAgent: string;
  device: {
    type: 'desktop' | 'mobile' | 'tablet';
    os: string;
    browser: string;
    screen: {
      width: number;
      height: number;
    };
  };
  location?: {
    country: string;
    region: string;
    city: string;
  };
}

export interface AnalyticsDashboard {
  totalUsers: number;
  activeUsers: number;
  pageViews: number;
  sessions: number;
  averageSessionDuration: number;
  bounceRate: number;
  topPages: Array<{ page: string; views: number }>;
  topReferrers: Array<{ referrer: string; visits: number }>;
  deviceBreakdown: Record<string, number>;
  browserBreakdown: Record<string, number>;
  osBreakdown: Record<string, number>;
  performanceMetrics: PerformanceMetrics;
  errorRate: number;
  conversionRate: number;
}

class AnalyticsService {
  private readonly STORAGE_KEY = 'stargate_analytics';
  private readonly SESSION_KEY = 'stargate_session';
  private readonly EVENTS_KEY = 'stargate_events';
  private readonly MAX_EVENTS = 1000;
  private readonly BATCH_SIZE = 50;
  private readonly FLUSH_INTERVAL = 30000; // 30 seconds

  private currentSession: UserSession | null = null;
  private eventQueue: AnalyticsEvent[] = [];
  private flushTimer: number | null = null;
  private performanceObserver: PerformanceObserver | null = null;

  // Initialize analytics
  async initialize(): Promise<void> {
    await this.initializeSession();
    this.setupPerformanceMonitoring();
    this.setupErrorTracking();
    this.setupPageTracking();
    this.startFlushTimer();
  }

  // Initialize user session
  private async initializeSession(): Promise<void> {
    const storedSession = localStorage.getItem(this.SESSION_KEY);
    
    if (storedSession) {
      this.currentSession = JSON.parse(storedSession);
      this.currentSession!.lastActivity = new Date().toISOString();
    } else {
      this.currentSession = await this.createNewSession();
    }

    localStorage.setItem(this.SESSION_KEY, JSON.stringify(this.currentSession));
  }

  // Create new session
  private async createNewSession(): Promise<UserSession> {
    const sessionId = this.generateId();
    const now = new Date().toISOString();
    
    const device = this.getDeviceInfo();
    const location = await this.getLocationInfo();

    return {
      id: sessionId,
      startTime: now,
      lastActivity: now,
      pageViews: 0,
      events: 0,
      duration: 0,
      userAgent: navigator.userAgent,
      device,
      location
    };
  }

  // Track page view
  trackPageView(page: string, properties: Record<string, any> = {}): void {
    if (!this.currentSession) return;

    this.currentSession.pageViews++;
    this.currentSession.lastActivity = new Date().toISOString();

    const event: AnalyticsEvent = {
      id: this.generateId(),
      type: 'page_view',
      name: 'page_view',
      properties: {
        page,
        ...properties
      },
      timestamp: new Date().toISOString(),
      sessionId: this.currentSession.id,
      userAgent: navigator.userAgent,
      url: window.location.href,
      referrer: document.referrer
    };

    this.trackEvent(event);
    this.updateSession();
  }

  // Track user action
  trackUserAction(action: string, properties: Record<string, any> = {}): void {
    if (!this.currentSession) return;

    this.currentSession.events++;
    this.currentSession.lastActivity = new Date().toISOString();

    const event: AnalyticsEvent = {
      id: this.generateId(),
      type: 'user_action',
      name: action,
      properties,
      timestamp: new Date().toISOString(),
      sessionId: this.currentSession.id,
      userAgent: navigator.userAgent,
      url: window.location.href,
      referrer: document.referrer
    };

    this.trackEvent(event);
    this.updateSession();
  }

  // Track custom event
  trackCustomEvent(name: string, properties: Record<string, any> = {}): void {
    if (!this.currentSession) return;

    const event: AnalyticsEvent = {
      id: this.generateId(),
      type: 'custom',
      name,
      properties,
      timestamp: new Date().toISOString(),
      sessionId: this.currentSession.id,
      userAgent: navigator.userAgent,
      url: window.location.href,
      referrer: document.referrer
    };

    this.trackEvent(event);
  }

  // Track error
  trackError(error: Error, context: Record<string, any> = {}): void {
    if (!this.currentSession) return;

    const event: AnalyticsEvent = {
      id: this.generateId(),
      type: 'error',
      name: 'error',
      properties: {
        message: error.message,
        stack: error.stack,
        ...context
      },
      timestamp: new Date().toISOString(),
      sessionId: this.currentSession.id,
      userAgent: navigator.userAgent,
      url: window.location.href,
      referrer: document.referrer
    };

    this.trackEvent(event);
  }

  // Track performance metrics
  trackPerformance(metrics: PerformanceMetrics): void {
    if (!this.currentSession) return;

    const event: AnalyticsEvent = {
      id: this.generateId(),
      type: 'performance',
      name: 'performance_metrics',
      properties: metrics,
      timestamp: new Date().toISOString(),
      sessionId: this.currentSession.id,
      userAgent: navigator.userAgent,
      url: window.location.href,
      referrer: document.referrer
    };

    this.trackEvent(event);
  }

  // Add event to queue
  private trackEvent(event: AnalyticsEvent): void {
    this.eventQueue.push(event);

    // Limit queue size
    if (this.eventQueue.length > this.MAX_EVENTS) {
      this.eventQueue = this.eventQueue.slice(-this.MAX_EVENTS);
    }

    // Store events in localStorage
    this.storeEvents();
  }

  // Store events in localStorage
  private storeEvents(): void {
    const storedEvents = this.getStoredEvents();
    const allEvents = [...storedEvents, ...this.eventQueue];
    
    // Keep only recent events
    const recentEvents = allEvents.slice(-this.MAX_EVENTS);
    localStorage.setItem(this.EVENTS_KEY, JSON.stringify(recentEvents));
  }

  // Get stored events
  private getStoredEvents(): AnalyticsEvent[] {
    const stored = localStorage.getItem(this.EVENTS_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  // Public method to get events
  getEvents(): AnalyticsEvent[] {
    return this.getStoredEvents();
  }

  // Update session
  private updateSession(): void {
    if (this.currentSession) {
      this.currentSession.duration = Date.now() - new Date(this.currentSession.startTime).getTime();
      localStorage.setItem(this.SESSION_KEY, JSON.stringify(this.currentSession));
    }
  }

  // Setup performance monitoring
  private setupPerformanceMonitoring(): void {
    if ('PerformanceObserver' in window) {
      this.performanceObserver = new PerformanceObserver((list) => {
        const entries = list.getEntries();
        
        for (const entry of entries) {
          if (entry.entryType === 'navigation') {
            const navEntry = entry as PerformanceNavigationTiming;
            const metrics: PerformanceMetrics = {
              pageLoadTime: navEntry.loadEventEnd - navEntry.fetchStart,
              domContentLoaded: navEntry.domContentLoadedEventEnd - navEntry.fetchStart,
              firstContentfulPaint: 0,
              largestContentfulPaint: 0,
              firstInputDelay: 0,
              cumulativeLayoutShift: 0,
              timeToInteractive: 0,
              bundleSize: this.getBundleSize()
            };

            this.trackPerformance(metrics);
          }
        }
      });

      this.performanceObserver.observe({ entryTypes: ['navigation', 'paint', 'largest-contentful-paint'] });
    }

    // Monitor memory usage
    if ('memory' in performance) {
      setInterval(() => {
        const memory = (performance as any).memory;
        if (memory) {
          this.trackCustomEvent('memory_usage', {
            usedJSHeapSize: memory.usedJSHeapSize,
            totalJSHeapSize: memory.totalJSHeapSize,
            jsHeapSizeLimit: memory.jsHeapSizeLimit
          });
        }
      }, 60000); // Every minute
    }
  }

  // Setup error tracking
  private setupErrorTracking(): void {
    window.addEventListener('error', (event) => {
      this.trackError(event.error, {
        filename: event.filename,
        lineno: event.lineno,
        colno: event.colno
      });
    });

    window.addEventListener('unhandledrejection', (event) => {
      this.trackError(new Error(event.reason), {
        type: 'unhandled_promise_rejection'
      });
    });
  }

  // Setup page tracking
  private setupPageTracking(): void {
    // Track initial page view
    this.trackPageView(window.location.pathname);

    // Track route changes (for SPA)
    window.addEventListener('popstate', () => {
      this.trackPageView(window.location.pathname);
    });

    // Track beforeunload
    window.addEventListener('beforeunload', () => {
      this.updateSession();
      this.flushEvents();
    });
  }

  // Start flush timer
  private startFlushTimer(): void {
    this.flushTimer = window.setInterval(() => {
      this.flushEvents();
    }, this.FLUSH_INTERVAL);
  }

  // Flush events to server
  async flushEvents(): Promise<void> {
    if (this.eventQueue.length === 0) return;

    try {
      const eventsToFlush = this.eventQueue.splice(0, this.BATCH_SIZE);
      
      // In a real implementation, this would send to analytics server
      console.log('Flushing analytics events:', eventsToFlush);
      
      // Simulate API call
      await this.sendEventsToServer(eventsToFlush);
      
    } catch (error) {
      console.error('Failed to flush analytics events:', error);
      // Re-add events to queue if flush failed
      this.eventQueue.unshift(...this.eventQueue);
    }
  }

  // Send events to server
  private async sendEventsToServer(events: AnalyticsEvent[]): Promise<void> {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 100));
    console.log('Events sent to server:', events.length);
  }

  // Get device info
  private getDeviceInfo(): UserSession['device'] {
    const userAgent = navigator.userAgent;
    const screenInfo = {
      width: window.screen.width,
      height: window.screen.height
    };

    let deviceType: 'desktop' | 'mobile' | 'tablet' = 'desktop';
    if (/Mobile|Android|iPhone|iPad/.test(userAgent)) {
      deviceType = 'mobile';
    } else if (/iPad|Tablet/.test(userAgent)) {
      deviceType = 'tablet';
    }

    let os = 'Unknown';
    if (/Windows/.test(userAgent)) os = 'Windows';
    else if (/Mac/.test(userAgent)) os = 'macOS';
    else if (/Linux/.test(userAgent)) os = 'Linux';
    else if (/Android/.test(userAgent)) os = 'Android';
    else if (/iPhone|iPad/.test(userAgent)) os = 'iOS';

    let browser = 'Unknown';
    if (/Chrome/.test(userAgent)) browser = 'Chrome';
    else if (/Firefox/.test(userAgent)) browser = 'Firefox';
    else if (/Safari/.test(userAgent)) browser = 'Safari';
    else if (/Edge/.test(userAgent)) browser = 'Edge';

    return {
      type: deviceType,
      os,
      browser,
      screen: screenInfo
    };
  }

  // Get location info
  private async getLocationInfo(): Promise<UserSession['location']> {
    try {
      // In a real implementation, this would use a geolocation service
      // For now, return mock data
      return {
        country: 'Unknown',
        region: 'Unknown',
        city: 'Unknown'
      };
    } catch (error) {
      return undefined;
    }
  }

  // Get bundle size
  private getBundleSize(): number {
    // Estimate bundle size based on loaded scripts
    let totalSize = 0;
    const scripts = document.querySelectorAll('script[src]');
    scripts.forEach(script => {
      const src = script.getAttribute('src');
      if (src && src.includes('assets/')) {
        // Estimate size based on filename patterns
        totalSize += 100000; // 100KB estimate per script
      }
    });
    return totalSize;
  }

  // Generate unique ID
  private generateId(): string {
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
  }

  // Get analytics dashboard data
  async getDashboardData(): Promise<AnalyticsDashboard> {
    const events = this.getStoredEvents();
    const sessions = this.getStoredSessions();

    // Calculate metrics
    const totalUsers = new Set(events.map(e => e.sessionId)).size;
    const activeUsers = new Set(sessions.filter(s => 
      Date.now() - new Date(s.lastActivity).getTime() < 24 * 60 * 60 * 1000
    ).map(s => s.id)).size;

    const pageViews = events.filter(e => e.type === 'page_view').length;
    const totalSessions = sessions.length;
    const averageSessionDuration = sessions.reduce((sum, s) => sum + s.duration, 0) / totalSessions;

    // Top pages
    const pageViewsByPage = events
      .filter(e => e.type === 'page_view')
      .reduce((acc, e) => {
        const page = e.properties.page;
        acc[page] = (acc[page] || 0) + 1;
        return acc;
      }, {} as Record<string, number>);

    const topPages = Object.entries(pageViewsByPage)
      .map(([page, views]) => ({ page, views }))
      .sort((a, b) => b.views - a.views)
      .slice(0, 10);

    // Device breakdown
    const deviceBreakdown = sessions.reduce((acc, s) => {
      acc[s.device.type] = (acc[s.device.type] || 0) + 1;
      return acc;
    }, {} as Record<string, number>);

    // Browser breakdown
    const browserBreakdown = sessions.reduce((acc, s) => {
      acc[s.device.browser] = (acc[s.device.browser] || 0) + 1;
      return acc;
    }, {} as Record<string, number>);

    // OS breakdown
    const osBreakdown = sessions.reduce((acc, s) => {
      acc[s.device.os] = (acc[s.device.os] || 0) + 1;
      return acc;
    }, {} as Record<string, number>);

    // Performance metrics
    const performanceEvents = events.filter(e => e.type === 'performance');
    const latestPerformance = performanceEvents[performanceEvents.length - 1];
    const performanceMetrics: PerformanceMetrics = {
      pageLoadTime: latestPerformance?.properties?.pageLoadTime || 0,
      domContentLoaded: latestPerformance?.properties?.domContentLoaded || 0,
      firstContentfulPaint: latestPerformance?.properties?.firstContentfulPaint || 0,
      largestContentfulPaint: latestPerformance?.properties?.largestContentfulPaint || 0,
      firstInputDelay: latestPerformance?.properties?.firstInputDelay || 0,
      cumulativeLayoutShift: latestPerformance?.properties?.cumulativeLayoutShift || 0,
      timeToInteractive: latestPerformance?.properties?.timeToInteractive || 0,
      bundleSize: latestPerformance?.properties?.bundleSize || 0
    };

    // Error rate
    const errorEvents = events.filter(e => e.type === 'error');
    const errorRate = events.length > 0 ? (errorEvents.length / events.length) * 100 : 0;

    return {
      totalUsers,
      activeUsers,
      pageViews,
      sessions: totalSessions,
      averageSessionDuration,
      bounceRate: 0, // Would need more complex calculation
      topPages,
      topReferrers: [], // Would need referrer tracking
      deviceBreakdown,
      browserBreakdown,
      osBreakdown,
      performanceMetrics,
      errorRate,
      conversionRate: 0 // Would need conversion tracking
    };
  }

  // Get stored sessions
  private getStoredSessions(): UserSession[] {
    const stored = localStorage.getItem(this.STORAGE_KEY);
    return stored ? JSON.parse(stored) : [];
  }

  // Store sessions
  private storeSessions(sessions: UserSession[]): void {
    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(sessions));
  }

  // Cleanup
  destroy(): void {
    if (this.flushTimer) {
      clearInterval(this.flushTimer);
    }
    if (this.performanceObserver) {
      this.performanceObserver.disconnect();
    }
    this.flushEvents();
  }
}

export const analyticsService = new AnalyticsService();
