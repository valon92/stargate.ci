// API Configuration for Stargate.ci
// Centralized configuration for all external API integrations

export interface ApiConfig {
  openai: {
    apiKey: string;
    baseUrl: string;
    model: string;
    maxTokens: number;
    temperature: number;
  };
  stripe: {
    publishableKey: string;
    secretKey: string;
    webhookSecret: string;
    currency: string;
  };
  sendgrid: {
    apiKey: string;
    fromEmail: string;
    fromName: string;
  };
  mailchimp: {
    apiKey: string;
    serverPrefix: string;
    listId: string;
  };
  google: {
    analyticsId: string;
    mapsApiKey: string;
    tagManagerId: string;
  };
  aws: {
    accessKeyId: string;
    secretAccessKey: string;
    region: string;
    s3Bucket: string;
    cloudFrontDomain: string;
  };
  firebase: {
    apiKey: string;
    authDomain: string;
    projectId: string;
    messagingSenderId: string;
    appId: string;
  };
  auth0: {
    domain: string;
    clientId: string;
    clientSecret: string;
    audience: string;
  };
}

// Environment-based configuration
const getApiConfig = (): ApiConfig => {
  const isProduction = import.meta.env.MODE === 'production';
  
  return {
    openai: {
      apiKey: import.meta.env.VITE_OPENAI_API_KEY || '',
      baseUrl: 'https://api.openai.com/v1',
      model: 'gpt-4',
      maxTokens: 2000,
      temperature: 0.7
    },
    stripe: {
      publishableKey: isProduction 
        ? import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY_PROD || ''
        : import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY_TEST || '',
      secretKey: isProduction
        ? import.meta.env.VITE_STRIPE_SECRET_KEY_PROD || ''
        : import.meta.env.VITE_STRIPE_SECRET_KEY_TEST || '',
      webhookSecret: import.meta.env.VITE_STRIPE_WEBHOOK_SECRET || '',
      currency: 'usd'
    },
    sendgrid: {
      apiKey: import.meta.env.VITE_SENDGRID_API_KEY || '',
      fromEmail: import.meta.env.VITE_SENDGRID_FROM_EMAIL || 'noreply@stargate.ci',
      fromName: import.meta.env.VITE_SENDGRID_FROM_NAME || 'Stargate.ci'
    },
    mailchimp: {
      apiKey: import.meta.env.VITE_MAILCHIMP_API_KEY || '',
      serverPrefix: import.meta.env.VITE_MAILCHIMP_SERVER_PREFIX || '',
      listId: import.meta.env.VITE_MAILCHIMP_LIST_ID || ''
    },
    google: {
      analyticsId: import.meta.env.VITE_GOOGLE_ANALYTICS_ID || '',
      mapsApiKey: import.meta.env.VITE_GOOGLE_MAPS_API_KEY || '',
      tagManagerId: import.meta.env.VITE_GOOGLE_TAG_MANAGER_ID || ''
    },
    aws: {
      accessKeyId: import.meta.env.VITE_AWS_ACCESS_KEY_ID || '',
      secretAccessKey: import.meta.env.VITE_AWS_SECRET_ACCESS_KEY || '',
      region: import.meta.env.VITE_AWS_REGION || 'us-east-1',
      s3Bucket: import.meta.env.VITE_AWS_S3_BUCKET || '',
      cloudFrontDomain: import.meta.env.VITE_AWS_CLOUDFRONT_DOMAIN || ''
    },
    firebase: {
      apiKey: import.meta.env.VITE_FIREBASE_API_KEY || '',
      authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN || '',
      projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID || '',
      messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID || '',
      appId: import.meta.env.VITE_FIREBASE_APP_ID || ''
    },
    auth0: {
      domain: import.meta.env.VITE_AUTH0_DOMAIN || '',
      clientId: import.meta.env.VITE_AUTH0_CLIENT_ID || '',
      clientSecret: import.meta.env.VITE_AUTH0_CLIENT_SECRET || '',
      audience: import.meta.env.VITE_AUTH0_AUDIENCE || ''
    }
  };
};

export const apiConfig = getApiConfig();

// API Status checker
export const checkApiStatus = async (): Promise<Record<string, boolean>> => {
  const status: Record<string, boolean> = {};
  
  // Check OpenAI
  try {
    if (apiConfig.openai.apiKey) {
      const response = await fetch(`${apiConfig.openai.baseUrl}/models`, {
        headers: {
          'Authorization': `Bearer ${apiConfig.openai.apiKey}`,
          'Content-Type': 'application/json'
        }
      });
      status.openai = response.ok;
    } else {
      status.openai = false;
    }
  } catch {
    status.openai = false;
  }
  
  // Check Stripe
  status.stripe = !!(apiConfig.stripe.publishableKey && apiConfig.stripe.secretKey);
  
  // Check SendGrid
  status.sendgrid = !!apiConfig.sendgrid.apiKey;
  
  // Check Mailchimp
  status.mailchimp = !!(apiConfig.mailchimp.apiKey && apiConfig.mailchimp.serverPrefix);
  
  // Check Google Analytics
  status.googleAnalytics = !!apiConfig.google.analyticsId;
  
  // Check AWS
  status.aws = !!(apiConfig.aws.accessKeyId && apiConfig.aws.secretAccessKey);
  
  // Check Firebase
  status.firebase = !!(apiConfig.firebase.apiKey && apiConfig.firebase.projectId);
  
  // Check Auth0
  status.auth0 = !!(apiConfig.auth0.domain && apiConfig.auth0.clientId);
  
  return status;
};

// API Rate Limiting
export const rateLimits = {
  openai: {
    requestsPerMinute: 60,
    tokensPerMinute: 150000
  },
  stripe: {
    requestsPerSecond: 100
  },
  sendgrid: {
    requestsPerSecond: 10
  },
  mailchimp: {
    requestsPerSecond: 10
  }
};

// API Endpoints
export const apiEndpoints = {
  openai: {
    chat: '/chat/completions',
    models: '/models',
    embeddings: '/embeddings',
    moderation: '/moderations'
  },
  stripe: {
    paymentIntents: '/v1/payment_intents',
    customers: '/v1/customers',
    subscriptions: '/v1/subscriptions',
    webhooks: '/v1/webhook_endpoints'
  },
  sendgrid: {
    mail: '/v3/mail/send',
    templates: '/v3/templates',
    contacts: '/v3/marketing/contacts'
  },
  mailchimp: {
    lists: '/3.0/lists',
    members: '/3.0/lists/{list_id}/members',
    campaigns: '/3.0/campaigns'
  }
};
