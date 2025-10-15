// API Configuration for Stargate.ci
// Centralized configuration for educational/informative API integrations only

export interface ApiConfig {
  openai: {
    apiKey: string;
    baseUrl: string;
    model: string;
    maxTokens: number;
    temperature: number;
  };
  google: {
    analyticsId: string;
    mapsApiKey: string;
    tagManagerId: string;
  };
}

// Environment-based configuration
const getApiConfig = (): ApiConfig => {
  return {
    openai: {
      apiKey: import.meta.env.VITE_OPENAI_API_KEY || '',
      baseUrl: 'https://api.openai.com/v1',
      model: 'gpt-4',
      maxTokens: 2000,
      temperature: 0.7
    },
    google: {
      analyticsId: import.meta.env.VITE_GOOGLE_ANALYTICS_ID || '',
      mapsApiKey: import.meta.env.VITE_GOOGLE_MAPS_API_KEY || '',
      tagManagerId: import.meta.env.VITE_GOOGLE_TAG_MANAGER_ID || ''
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
  
  // Check Google Analytics
  status.googleAnalytics = !!apiConfig.google.analyticsId;
  
  return status;
};

// API Rate Limiting
export const rateLimits = {
  openai: {
    requestsPerMinute: 60,
    tokensPerMinute: 150000
  }
};

// API Endpoints
export const apiEndpoints = {
  openai: {
    chat: '/chat/completions',
    models: '/models',
    embeddings: '/embeddings',
    moderation: '/moderations'
  }
};
