// OpenAI Service for Stargate.ci
// Handles all OpenAI API integrations including GPT-4, DALL-E, and embeddings

import { apiConfig, rateLimits } from '../config/apiConfig';

export interface ChatMessage {
  role: 'system' | 'user' | 'assistant';
  content: string;
}

export interface ChatCompletionRequest {
  model: string;
  messages: ChatMessage[];
  max_tokens?: number;
  temperature?: number;
  top_p?: number;
  frequency_penalty?: number;
  presence_penalty?: number;
  stream?: boolean;
}

export interface ChatCompletionResponse {
  id: string;
  object: string;
  created: number;
  model: string;
  choices: Array<{
    index: number;
    message: ChatMessage;
    finish_reason: string;
  }>;
  usage: {
    prompt_tokens: number;
    completion_tokens: number;
    total_tokens: number;
  };
}

export interface EmbeddingRequest {
  input: string | string[];
  model: string;
  encoding_format?: string;
}

export interface EmbeddingResponse {
  object: string;
  data: Array<{
    object: string;
    index: number;
    embedding: number[];
  }>;
  model: string;
  usage: {
    prompt_tokens: number;
    total_tokens: number;
  };
}

export interface ModerationRequest {
  input: string | string[];
  model?: string;
}

export interface ModerationResponse {
  id: string;
  model: string;
  results: Array<{
    flagged: boolean;
    categories: Record<string, boolean>;
    category_scores: Record<string, number>;
  }>;
}

export interface ImageGenerationRequest {
  prompt: string;
  n?: number;
  size?: '256x256' | '512x512' | '1024x1024' | '1792x1024' | '1024x1792';
  quality?: 'standard' | 'hd';
  style?: 'vivid' | 'natural';
  response_format?: 'url' | 'b64_json';
  user?: string;
}

export interface ImageGenerationResponse {
  created: number;
  data: Array<{
    url?: string;
    b64_json?: string;
    revised_prompt?: string;
  }>;
}

class OpenAIService {
  private apiKey: string;
  private baseUrl: string;
  private rateLimitTracker: Map<string, { count: number; resetTime: number }> = new Map();

  constructor() {
    this.apiKey = apiConfig.openai.apiKey;
    this.baseUrl = apiConfig.openai.baseUrl;
  }

  // Check if API key is configured
  public isConfigured(): boolean {
    return !!this.apiKey;
  }

  // Rate limiting helper
  private async checkRateLimit(endpoint: string): Promise<boolean> {
    const now = Date.now();
    const limit = rateLimits.openai.requestsPerMinute;
    const windowMs = 60 * 1000; // 1 minute

    const key = `openai_${endpoint}`;
    const current = this.rateLimitTracker.get(key);

    if (!current || now > current.resetTime) {
      this.rateLimitTracker.set(key, { count: 1, resetTime: now + windowMs });
      return true;
    }

    if (current.count >= limit) {
      return false;
    }

    current.count++;
    return true;
  }

  // Make authenticated request to OpenAI API
  private async makeRequest<T>(endpoint: string, data: any): Promise<T> {
    if (!this.isConfigured()) {
      throw new Error('OpenAI API key not configured');
    }

    const canProceed = await this.checkRateLimit(endpoint);
    if (!canProceed) {
      throw new Error('Rate limit exceeded. Please try again later.');
    }

    const response = await fetch(`${this.baseUrl}${endpoint}`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${this.apiKey}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data)
    });

    if (!response.ok) {
      const error = await response.json().catch(() => ({}));
      throw new Error(`OpenAI API error: ${response.status} - ${error.error?.message || response.statusText}`);
    }

    return response.json();
  }

  // Chat completions (GPT-4, GPT-3.5)
  public async createChatCompletion(request: ChatCompletionRequest): Promise<ChatCompletionResponse> {
    const defaultRequest = {
      model: apiConfig.openai.model,
      max_tokens: apiConfig.openai.maxTokens,
      temperature: apiConfig.openai.temperature,
      ...request
    };

    return this.makeRequest<ChatCompletionResponse>('/chat/completions', defaultRequest);
  }

  // Generate AI response for Stargate.ci content
  public async generateStargateResponse(userMessage: string, context?: string): Promise<string> {
    const systemMessage = `You are an AI assistant for Stargate.ci, an educational platform about the Stargate project and Cristal Intelligence. 

Your role is to:
- Provide accurate, educational information about AI, the Stargate project, and related technologies
- Help users understand complex AI concepts in simple terms
- Guide companies toward ethical AI implementation
- Maintain transparency and accuracy in all responses
- Respect intellectual property rights

${context ? `Context: ${context}` : ''}

Always be helpful, accurate, and educational. If you don't know something, say so rather than guessing.`;

    const messages: ChatMessage[] = [
      { role: 'system', content: systemMessage },
      { role: 'user', content: userMessage }
    ];

    try {
      const response = await this.createChatCompletion({ messages });
      return response.choices[0]?.message?.content || 'Sorry, I could not generate a response.';
    } catch (error) {
      console.error('OpenAI API error:', error);
      return 'I apologize, but I\'m currently unable to process your request. Please try again later.';
    }
  }

  // Generate content for articles, FAQs, etc.
  public async generateContent(type: 'article' | 'faq' | 'summary', topic: string, requirements?: string): Promise<string> {
    const prompts = {
      article: `Write a comprehensive, educational article about "${topic}" for the Stargate.ci platform. Focus on accuracy, clarity, and educational value. ${requirements || ''}`,
      faq: `Create a helpful FAQ entry about "${topic}" for the Stargate.ci platform. Make it clear, concise, and educational. ${requirements || ''}`,
      summary: `Provide a clear, concise summary of "${topic}" for the Stargate.ci platform. Focus on key points and educational value. ${requirements || ''}`
    };

    const messages: ChatMessage[] = [
      { 
        role: 'system', 
        content: 'You are a content writer for Stargate.ci, an educational platform about AI and the Stargate project. Write clear, accurate, and educational content that helps users understand complex topics.' 
      },
      { role: 'user', content: prompts[type] }
    ];

    try {
      const response = await this.createChatCompletion({ messages });
      return response.choices[0]?.message?.content || 'Content generation failed.';
    } catch (error) {
      console.error('Content generation error:', error);
      return 'Content generation is currently unavailable.';
    }
  }

  // Create embeddings for search optimization
  public async createEmbeddings(request: EmbeddingRequest): Promise<EmbeddingResponse> {
    const defaultRequest = {
      model: 'text-embedding-3-small',
      encoding_format: 'float',
      ...request
    };

    return this.makeRequest<EmbeddingResponse>('/embeddings', defaultRequest);
  }

  // Moderate content for safety
  public async moderateContent(input: string | string[]): Promise<ModerationResponse> {
    const request: ModerationRequest = {
      input,
      model: 'text-moderation-latest'
    };

    return this.makeRequest<ModerationResponse>('/moderations', request);
  }

  // Generate images with DALL-E
  public async generateImage(request: ImageGenerationRequest): Promise<ImageGenerationResponse> {
    const defaultRequest = {
      model: 'dall-e-3',
      n: 1,
      size: '1024x1024',
      quality: 'standard',
      style: 'natural',
      response_format: 'url',
      ...request
    };

    return this.makeRequest<ImageGenerationResponse>('/images/generations', defaultRequest);
  }

  // Generate Stargate.ci themed images
  public async generateStargateImage(description: string): Promise<string | null> {
    const prompt = `Create a professional, futuristic image for Stargate.ci platform: ${description}. Style: clean, modern, tech-focused, blue and purple color scheme, professional quality.`;

    try {
      const response = await this.generateImage({ prompt });
      return response.data[0]?.url || null;
    } catch (error) {
      console.error('Image generation error:', error);
      return null;
    }
  }

  // Get available models
  public async getModels(): Promise<any[]> {
    try {
      const response = await fetch(`${this.baseUrl}/models`, {
        headers: {
          'Authorization': `Bearer ${this.apiKey}`,
          'Content-Type': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`Failed to fetch models: ${response.statusText}`);
      }

      const data = await response.json();
      return data.data || [];
    } catch (error) {
      console.error('Error fetching models:', error);
      return [];
    }
  }

  // Usage tracking
  public async getUsage(): Promise<any> {
    try {
      const response = await fetch(`${this.baseUrl}/usage`, {
        headers: {
          'Authorization': `Bearer ${this.apiKey}`,
          'Content-Type': 'application/json'
        }
      });

      if (!response.ok) {
        throw new Error(`Failed to fetch usage: ${response.statusText}`);
      }

      return response.json();
    } catch (error) {
      console.error('Error fetching usage:', error);
      return null;
    }
  }
}

// Export singleton instance
export const openaiService = new OpenAIService();
