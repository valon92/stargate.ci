// Analytics Service for Stargate.ci
// Handles Google Analytics, custom event tracking, and user behavior analytics

import { apiConfig } from '../config/apiConfig';

export interface AnalyticsEvent {
  event_name: string;
  event_category: string;
  event_label?: string;
  value?: number;
  custom_parameters?: Record<string, any>;
}

export interface PageViewEvent {
  page_title: string;
  page_location: string;
  page_path: string;
  content_group?: string;
  custom_parameters?: Record<string, any>;
}

export interface UserProperties {
  user_id?: string;
  user_type?: 'guest' | 'registered' | 'premium' | 'enterprise';
  subscription_plan?: 'basic' | 'pro' | 'enterprise';
  registration_date?: string;
  last_login?: string;
  custom_properties?: Record<string, any>;
}

export interface EcommerceEvent {
  transaction_id: string;
  value: number;
  currency: string;
  items: Array<{
    item_id: string;
    item_name: string;
    category: string;
    quantity: number;
    price: number;
  }>;
}

export interface SearchEvent {
  search_term: string;
  results_count?: number;
  search_category?: string;
  filters_applied?: string[];
}

export interface PerformanceMetrics {
  page_load_time: number;
  first_contentful_paint: number;
  largest_contentful_paint: number;
  cumulative_layout_shift: number;
  first_input_delay: number;
  interaction_to_next_paint: number;
}

class AnalyticsService {
  private googleAnalyticsId: string;
  private googleTagManagerId: string;
  private isInitialized: boolean = false;
  private userProperties: UserProperties = {};

  constructor() {
    this.googleAnalyticsId = apiConfig.google.analyticsId;
    this.googleTagManagerId = apiConfig.google.tagManagerId;
  }

  // Check if analytics is configured
  public isConfigured(): boolean {
    return !!(this.googleAnalyticsId || this.googleTagManagerId);
  }

  // Initialize analytics
  public async initialize(): Promise<void> {
    if (!this.isConfigured() || this.isInitialized) {
      return;
    }

    try {
      // Load Google Analytics
      if (this.googleAnalyticsId) {
        await this.loadGoogleAnalytics();
      }

      // Load Google Tag Manager
      if (this.googleTagManagerId) {
        await this.loadGoogleTagManager();
      }

      // Initialize custom analytics
      this.initializeCustomAnalytics();

      this.isInitialized = true;
      console.log('Analytics initialized successfully');
    } catch (error) {
      console.error('Failed to initialize analytics:', error);
    }
  }

  // Load Google Analytics
  private async loadGoogleAnalytics(): Promise<void> {
    return new Promise((resolve, reject) => {
      // Load gtag script
      const script = document.createElement('script');
      script.async = true;
      script.src = `https://www.googletagmanager.com/gtag/js?id=${this.googleAnalyticsId}`;
      script.onload = () => {
        // Initialize gtag
        window.dataLayer = window.dataLayer || [];
        function gtag(...args: any[]) {
          window.dataLayer.push(args);
        }
        window.gtag = gtag;
        
        gtag('js', new Date());
        gtag('config', this.googleAnalyticsId, {
          page_title: document.title,
          page_location: window.location.href,
          send_page_view: false // We'll send page views manually
        });
        
        resolve();
      };
      script.onerror = reject;
      document.head.appendChild(script);
    });
  }

  // Load Google Tag Manager
  private async loadGoogleTagManager(): Promise<void> {
    return new Promise((resolve, reject) => {
      // Load GTM script
      const script = document.createElement('script');
      script.innerHTML = `
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','${this.googleTagManagerId}');
      `;
      script.onload = resolve;
      script.onerror = reject;
      document.head.appendChild(script);

      // Add noscript fallback
      const noscript = document.createElement('noscript');
      noscript.innerHTML = `<iframe src="https://www.googletagmanager.com/ns.html?id=${this.googleTagManagerId}" height="0" width="0" style="display:none;visibility:hidden"></iframe>`;
      document.body.insertBefore(noscript, document.body.firstChild);
    });
  }

  // Initialize custom analytics
  private initializeCustomAnalytics(): void {
    // Track page visibility changes
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        this.trackEvent('page_visibility', 'engagement', 'hidden');
      } else {
        this.trackEvent('page_visibility', 'engagement', 'visible');
      }
    });

    // Track scroll depth
    let maxScrollDepth = 0;
    window.addEventListener('scroll', () => {
      const scrollDepth = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
      if (scrollDepth > maxScrollDepth && scrollDepth % 25 === 0) {
        maxScrollDepth = scrollDepth;
        this.trackEvent('scroll_depth', 'engagement', `${scrollDepth}%`);
      }
    });

    // Track time on page
    const startTime = Date.now();
    window.addEventListener('beforeunload', () => {
      const timeOnPage = Math.round((Date.now() - startTime) / 1000);
      this.trackEvent('time_on_page', 'engagement', 'seconds', timeOnPage);
    });
  }

  // Track page view
  public trackPageView(event: PageViewEvent): void {
    if (!this.isInitialized) return;

    // Google Analytics
    if (window.gtag) {
      window.gtag('config', this.googleAnalyticsId, {
        page_title: event.page_title,
        page_location: event.page_location,
        page_path: event.page_path,
        ...event.custom_parameters
      });
    }

    // Custom tracking
    this.trackEvent('page_view', 'navigation', event.page_path, undefined, {
      page_title: event.page_title,
      content_group: event.content_group,
      ...event.custom_parameters
    });
  }

  // Track custom event
  public trackEvent(
    eventName: string,
    category: string,
    label?: string,
    value?: number,
    customParameters?: Record<string, any>
  ): void {
    if (!this.isInitialized) return;

    const event: AnalyticsEvent = {
      event_name: eventName,
      event_category: category,
      event_label: label,
      value: value,
      custom_parameters: customParameters
    };

    // Google Analytics
    if (window.gtag) {
      window.gtag('event', eventName, {
        event_category: category,
        event_label: label,
        value: value,
        ...customParameters
      });
    }

    // Custom tracking
    this.sendCustomEvent(event);
  }

  // Track Stargate.ci specific events
  public trackStargateEvent(
    action: 'search' | 'article_view' | 'faq_view' | 'contact_form' | 'subscription' | 'download',
    details: Record<string, any>
  ): void {
    this.trackEvent(`stargate_${action}`, 'stargate_ci', JSON.stringify(details), undefined, details);
  }

  // Track search events
  public trackSearch(event: SearchEvent): void {
    this.trackEvent('search', 'engagement', event.search_term, event.results_count, {
      search_category: event.search_category,
      filters_applied: event.filters_applied,
      results_count: event.results_count
    });

    this.trackStargateEvent('search', event);
  }

  // Track article views
  public trackArticleView(articleSlug: string, articleTitle: string, category?: string): void {
    this.trackEvent('article_view', 'content', articleSlug, undefined, {
      article_title: articleTitle,
      article_category: category
    });

    this.trackStargateEvent('article_view', {
      article_slug: articleSlug,
      article_title: articleTitle,
      category
    });
  }

  // Track FAQ views
  public trackFAQView(faqId: string, question: string, category?: string): void {
    this.trackEvent('faq_view', 'content', faqId, undefined, {
      question: question,
      faq_category: category
    });

    this.trackStargateEvent('faq_view', {
      faq_id: faqId,
      question,
      category
    });
  }

  // Track contact form submissions
  public trackContactForm(subject: string, success: boolean): void {
    this.trackEvent('contact_form', 'engagement', success ? 'success' : 'error', undefined, {
      form_subject: subject,
      success
    });

    this.trackStargateEvent('contact_form', {
      subject,
      success
    });
  }

  // Track subscription events
  public trackSubscription(planType: string, action: 'started' | 'completed' | 'cancelled', value?: number): void {
    this.trackEvent('subscription', 'ecommerce', `${planType}_${action}`, value, {
      plan_type: planType,
      action
    });

    this.trackStargateEvent('subscription', {
      plan_type: planType,
      action,
      value
    });
  }

  // Track ecommerce events
  public trackEcommerce(event: EcommerceEvent): void {
    if (window.gtag) {
      window.gtag('event', 'purchase', {
        transaction_id: event.transaction_id,
        value: event.value,
        currency: event.currency,
        items: event.items
      });
    }

    this.trackEvent('purchase', 'ecommerce', event.transaction_id, event.value, {
      currency: event.currency,
      items: event.items
    });
  }

  // Track performance metrics
  public trackPerformance(metrics: PerformanceMetrics): void {
    this.trackEvent('performance', 'technical', 'page_load', metrics.page_load_time, {
      first_contentful_paint: metrics.first_contentful_paint,
      largest_contentful_paint: metrics.largest_contentful_paint,
      cumulative_layout_shift: metrics.cumulative_layout_shift,
      first_input_delay: metrics.first_input_delay,
      interaction_to_next_paint: metrics.interaction_to_next_paint
    });
  }

  // Set user properties
  public setUserProperties(properties: UserProperties): void {
    this.userProperties = { ...this.userProperties, ...properties };

    if (window.gtag) {
      window.gtag('config', this.googleAnalyticsId, {
        user_id: properties.user_id,
        custom_map: {
          user_type: properties.user_type,
          subscription_plan: properties.subscription_plan
        }
      });
    }
  }

  // Track user engagement
  public trackEngagement(action: string, details?: Record<string, any>): void {
    this.trackEvent('user_engagement', 'engagement', action, undefined, {
      user_type: this.userProperties.user_type,
      subscription_plan: this.userProperties.subscription_plan,
      ...details
    });
  }

  // Track errors
  public trackError(error: Error, context?: string): void {
    this.trackEvent('error', 'technical', error.message, undefined, {
      error_name: error.name,
      error_stack: error.stack,
      context,
      user_agent: navigator.userAgent,
      url: window.location.href
    });
  }

  // Send custom event to backend
  private async sendCustomEvent(event: AnalyticsEvent): Promise<void> {
    try {
      // Send to your backend analytics endpoint
      await fetch('/api/v1/analytics/events', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ...event,
          timestamp: new Date().toISOString(),
          user_agent: navigator.userAgent,
          url: window.location.href,
          user_properties: this.userProperties
        })
      });
    } catch (error) {
      console.error('Failed to send custom analytics event:', error);
    }
  }

  // Get analytics data (if you have a backend endpoint)
  public async getAnalyticsData(
    startDate: string,
    endDate: string,
    metrics: string[]
  ): Promise<any> {
    try {
      const response = await fetch(`/api/v1/analytics/data?start_date=${startDate}&end_date=${endDate}&metrics=${metrics.join(',')}`);
      return response.json();
    } catch (error) {
      console.error('Failed to fetch analytics data:', error);
      return null;
    }
  }

  // Track AI interactions
  public trackAIInteraction(
    type: 'chat' | 'content_generation' | 'search_optimization',
    input: string,
    success: boolean,
    responseTime?: number
  ): void {
    this.trackEvent('ai_interaction', 'ai_features', type, responseTime, {
      input_length: input.length,
      success,
      response_time: responseTime
    });
  }

  // Track file uploads
  public trackFileUpload(
    fileName: string,
    fileSize: number,
    fileType: string,
    success: boolean
  ): void {
    this.trackEvent('file_upload', 'user_action', success ? 'success' : 'error', fileSize, {
      file_name: fileName,
      file_type: fileType,
      file_size: fileSize,
      success
    });
  }
}

// Global type declarations
declare global {
  interface Window {
    dataLayer: any[];
    gtag: (...args: any[]) => void;
  }
}

// Export singleton instance
export const analyticsService = new AnalyticsService();