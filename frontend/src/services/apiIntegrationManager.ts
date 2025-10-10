// API Integration Manager for Stargate.ci
// Centralized management of all external API integrations

import { apiConfig, checkApiStatus } from '../config/apiConfig';
import { openaiService } from './openaiService';
import { stripeService } from './stripeService';
import { sendgridService } from './sendgridService';
import { analyticsService } from './analyticsService';

export interface IntegrationStatus {
  name: string;
  configured: boolean;
  active: boolean;
  lastChecked: string;
  error?: string;
  features: string[];
}

export interface IntegrationHealth {
  overall: 'healthy' | 'degraded' | 'unhealthy';
  services: IntegrationStatus[];
  lastUpdated: string;
}

export interface ApiUsage {
  service: string;
  requests: number;
  limit: number;
  resetTime: string;
  cost: number;
}

export interface IntegrationConfig {
  openai: {
    enabled: boolean;
    features: string[];
    rateLimit: number;
  };
  stripe: {
    enabled: boolean;
    features: string[];
    testMode: boolean;
  };
  sendgrid: {
    enabled: boolean;
    features: string[];
    dailyLimit: number;
  };
  analytics: {
    enabled: boolean;
    features: string[];
    trackingId: string;
  };
}

class ApiIntegrationManager {
  private integrationConfig: IntegrationConfig;
  private healthStatus: IntegrationHealth;
  private usageStats: Map<string, ApiUsage> = new Map();

  constructor() {
    this.integrationConfig = {
      openai: {
        enabled: true,
        features: ['chat', 'content_generation', 'embeddings', 'moderation', 'image_generation'],
        rateLimit: 60
      },
      stripe: {
        enabled: true,
        features: ['payments', 'subscriptions', 'customers', 'webhooks'],
        testMode: import.meta.env.MODE !== 'production'
      },
      sendgrid: {
        enabled: true,
        features: ['transactional_emails', 'templates', 'contact_lists', 'analytics'],
        dailyLimit: 100
      },
      analytics: {
        enabled: true,
        features: ['page_tracking', 'event_tracking', 'user_analytics', 'performance'],
        trackingId: apiConfig.google.analyticsId
      }
    };

    this.healthStatus = {
      overall: 'unhealthy',
      services: [],
      lastUpdated: new Date().toISOString()
    };

    this.initializeIntegrations();
  }

  // Initialize all integrations
  private async initializeIntegrations(): Promise<void> {
    try {
      // Initialize analytics first (needed for tracking other services)
      if (this.integrationConfig.analytics.enabled) {
        await analyticsService.initialize();
      }

      // Check health of all services
      await this.checkAllServicesHealth();

      console.log('API Integration Manager initialized');
    } catch (error) {
      console.error('Failed to initialize API integrations:', error);
    }
  }

  // Check health of all services
  public async checkAllServicesHealth(): Promise<IntegrationHealth> {
    const services: IntegrationStatus[] = [];

    // Check OpenAI
    services.push({
      name: 'OpenAI',
      configured: openaiService.isConfigured(),
      active: openaiService.isConfigured() && this.integrationConfig.openai.enabled,
      lastChecked: new Date().toISOString(),
      features: this.integrationConfig.openai.features
    });

    // Check Stripe
    services.push({
      name: 'Stripe',
      configured: stripeService.isConfigured(),
      active: stripeService.isConfigured() && this.integrationConfig.stripe.enabled,
      lastChecked: new Date().toISOString(),
      features: this.integrationConfig.stripe.features
    });

    // Check SendGrid
    services.push({
      name: 'SendGrid',
      configured: sendgridService.isConfigured(),
      active: sendgridService.isConfigured() && this.integrationConfig.sendgrid.enabled,
      lastChecked: new Date().toISOString(),
      features: this.integrationConfig.sendgrid.features
    });

    // Check Analytics
    services.push({
      name: 'Google Analytics',
      configured: analyticsService.isConfigured(),
      active: analyticsService.isConfigured() && this.integrationConfig.analytics.enabled,
      lastChecked: new Date().toISOString(),
      features: this.integrationConfig.analytics.features
    });

    // Determine overall health
    const activeServices = services.filter(s => s.active).length;
    const totalServices = services.length;
    const healthRatio = activeServices / totalServices;

    let overall: 'healthy' | 'degraded' | 'unhealthy';
    if (healthRatio >= 0.8) {
      overall = 'healthy';
    } else if (healthRatio >= 0.5) {
      overall = 'degraded';
    } else {
      overall = 'unhealthy';
    }

    this.healthStatus = {
      overall,
      services,
      lastUpdated: new Date().toISOString()
    };

    return this.healthStatus;
  }

  // Get integration status
  public getIntegrationStatus(): IntegrationHealth {
    return this.healthStatus;
  }

  // Get service status by name
  public getServiceStatus(serviceName: string): IntegrationStatus | null {
    return this.healthStatus.services.find(s => s.name.toLowerCase() === serviceName.toLowerCase()) || null;
  }

  // Enable/disable service
  public toggleService(serviceName: string, enabled: boolean): void {
    const service = serviceName.toLowerCase();
    
    switch (service) {
      case 'openai':
        this.integrationConfig.openai.enabled = enabled;
        break;
      case 'stripe':
        this.integrationConfig.stripe.enabled = enabled;
        break;
      case 'sendgrid':
        this.integrationConfig.sendgrid.enabled = enabled;
        break;
      case 'analytics':
        this.integrationConfig.analytics.enabled = enabled;
        break;
    }

    // Recheck health after configuration change
    this.checkAllServicesHealth();
  }

  // Get API usage statistics
  public async getApiUsage(): Promise<ApiUsage[]> {
    const usage: ApiUsage[] = [];

    // OpenAI usage (mock - in real implementation, get from OpenAI API)
    if (openaiService.isConfigured()) {
      usage.push({
        service: 'OpenAI',
        requests: 0, // Get from actual API
        limit: this.integrationConfig.openai.rateLimit,
        resetTime: new Date(Date.now() + 60 * 60 * 1000).toISOString(), // 1 hour from now
        cost: 0 // Calculate based on usage
      });
    }

    // Stripe usage (mock - in real implementation, get from Stripe API)
    if (stripeService.isConfigured()) {
      usage.push({
        service: 'Stripe',
        requests: 0, // Get from actual API
        limit: 100, // Stripe rate limit
        resetTime: new Date(Date.now() + 60 * 1000).toISOString(), // 1 minute from now
        cost: 0 // Calculate based on transactions
      });
    }

    // SendGrid usage (mock - in real implementation, get from SendGrid API)
    if (sendgridService.isConfigured()) {
      usage.push({
        service: 'SendGrid',
        requests: 0, // Get from actual API
        limit: this.integrationConfig.sendgrid.dailyLimit,
        resetTime: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString(), // 24 hours from now
        cost: 0 // Calculate based on emails sent
      });
    }

    return usage;
  }

  // Test service connectivity
  public async testService(serviceName: string): Promise<boolean> {
    try {
      switch (serviceName.toLowerCase()) {
        case 'openai':
          if (!openaiService.isConfigured()) return false;
          // Test with a simple request
          await openaiService.generateStargateResponse('Test message');
          return true;

        case 'stripe':
          if (!stripeService.isConfigured()) return false;
          // Test by getting publishable key
          const key = stripeService.getPublishableKey();
          return !!key;

        case 'sendgrid':
          if (!sendgridService.isConfigured()) return false;
          // Test email validation
          return await sendgridService.verifyEmail('test@example.com');

        case 'analytics':
          if (!analyticsService.isConfigured()) return false;
          // Test by tracking a test event
          analyticsService.trackEvent('test_event', 'integration_test', 'connectivity');
          return true;

        default:
          return false;
      }
    } catch (error) {
      console.error(`Service test failed for ${serviceName}:`, error);
      return false;
    }
  }

  // Get integration configuration
  public getIntegrationConfig(): IntegrationConfig {
    return this.integrationConfig;
  }

  // Update integration configuration
  public updateIntegrationConfig(config: Partial<IntegrationConfig>): void {
    this.integrationConfig = { ...this.integrationConfig, ...config };
    this.checkAllServicesHealth();
  }

  // Get service recommendations
  public getServiceRecommendations(): Array<{
    service: string;
    recommendation: string;
    priority: 'high' | 'medium' | 'low';
  }> {
    const recommendations = [];

    // Check OpenAI
    if (!openaiService.isConfigured()) {
      recommendations.push({
        service: 'OpenAI',
        recommendation: 'Configure OpenAI API key to enable AI-powered features like content generation and chat assistance.',
        priority: 'high' as const
      });
    }

    // Check Stripe
    if (!stripeService.isConfigured()) {
      recommendations.push({
        service: 'Stripe',
        recommendation: 'Configure Stripe API keys to enable payment processing and subscription management.',
        priority: 'high' as const
      });
    }

    // Check SendGrid
    if (!sendgridService.isConfigured()) {
      recommendations.push({
        service: 'SendGrid',
        recommendation: 'Configure SendGrid API key to enable transactional emails and notifications.',
        priority: 'medium' as const
      });
    }

    // Check Analytics
    if (!analyticsService.isConfigured()) {
      recommendations.push({
        service: 'Google Analytics',
        recommendation: 'Configure Google Analytics to track user behavior and website performance.',
        priority: 'medium' as const
      });
    }

    return recommendations;
  }

  // Get service costs estimation
  public getServiceCosts(): Array<{
    service: string;
    estimatedMonthlyCost: number;
    costBreakdown: string;
  }> {
    return [
      {
        service: 'OpenAI',
        estimatedMonthlyCost: 50,
        costBreakdown: 'Based on ~1000 API calls per month for content generation and chat features'
      },
      {
        service: 'Stripe',
        estimatedMonthlyCost: 30,
        costBreakdown: '2.9% + 30¢ per transaction, estimated based on 10 transactions per month'
      },
      {
        service: 'SendGrid',
        estimatedMonthlyCost: 15,
        costBreakdown: 'Free tier: 100 emails/day, paid plans start at $14.95/month'
      },
      {
        service: 'Google Analytics',
        estimatedMonthlyCost: 0,
        costBreakdown: 'Free service with no usage limits'
      }
    ];
  }

  // Monitor API health continuously
  public startHealthMonitoring(intervalMs: number = 5 * 60 * 1000): void {
    setInterval(async () => {
      await this.checkAllServicesHealth();
      
      // Log health status
      if (this.healthStatus.overall !== 'healthy') {
        console.warn('API Integration Health Warning:', this.healthStatus);
      }
    }, intervalMs);
  }

  // Get integration dashboard data
  public async getDashboardData(): Promise<{
    health: IntegrationHealth;
    usage: ApiUsage[];
    recommendations: Array<{
      service: string;
      recommendation: string;
      priority: 'high' | 'medium' | 'low';
    }>;
    costs: Array<{
      service: string;
      estimatedMonthlyCost: number;
      costBreakdown: string;
    }>;
  }> {
    const [usage] = await Promise.all([
      this.getApiUsage()
    ]);

    return {
      health: this.healthStatus,
      usage,
      recommendations: this.getServiceRecommendations(),
      costs: this.getServiceCosts()
    };
  }
}

// Export singleton instance
export const apiIntegrationManager = new ApiIntegrationManager();
