// SendGrid Service for Stargate.ci
// Handles transactional emails, templates, and email marketing

import { apiConfig } from '../config/apiConfig';

export interface EmailAddress {
  email: string;
  name?: string;
}

export interface EmailContent {
  type: 'text/plain' | 'text/html';
  value: string;
}

export interface EmailAttachment {
  content: string; // Base64 encoded
  type: string;
  filename: string;
  disposition?: 'attachment' | 'inline';
  content_id?: string;
}

export interface SendEmailRequest {
  to: EmailAddress[];
  from: EmailAddress;
  subject: string;
  content: EmailContent[];
  cc?: EmailAddress[];
  bcc?: EmailAddress[];
  reply_to?: EmailAddress;
  attachments?: EmailAttachment[];
  template_id?: string;
  dynamic_template_data?: Record<string, any>;
  categories?: string[];
  custom_args?: Record<string, string>;
  send_at?: number;
  batch_id?: string;
}

export interface SendEmailResponse {
  message_id: string;
}

export interface TemplateRequest {
  name: string;
  generation: 'dynamic' | 'legacy';
  subject?: string;
  html_content?: string;
  plain_content?: string;
  editor?: 'code' | 'design';
}

export interface TemplateResponse {
  id: string;
  name: string;
  generation: string;
  updated_at: string;
  versions: Array<{
    id: string;
    template_id: string;
    active: number;
    name: string;
    html_content: string;
    plain_content: string;
    generate_plain_content: boolean;
    subject: string;
    updated_at: string;
    editor: string;
    test_data: Record<string, any>;
  }>;
}

export interface ContactRequest {
  email: string;
  first_name?: string;
  last_name?: string;
  custom_fields?: Record<string, any>;
  list_ids?: string[];
}

export interface ContactResponse {
  job_id: string;
}

export interface ListRequest {
  name: string;
}

export interface ListResponse {
  id: string;
  name: string;
  contact_count: number;
}

class SendGridService {
  private apiKey: string;
  private fromEmail: string;
  private fromName: string;
  private baseUrl: string = 'https://api.sendgrid.com/v3';

  constructor() {
    this.apiKey = apiConfig.sendgrid.apiKey;
    this.fromEmail = apiConfig.sendgrid.fromEmail;
    this.fromName = apiConfig.sendgrid.fromName;
  }

  // Check if SendGrid is configured
  public isConfigured(): boolean {
    return !!this.apiKey;
  }

  // Make authenticated request to SendGrid API
  private async makeRequest<T>(endpoint: string, data: any, method: 'GET' | 'POST' | 'PUT' | 'DELETE' = 'POST'): Promise<T> {
    if (!this.isConfigured()) {
      throw new Error('SendGrid API key not configured');
    }

    const response = await fetch(`${this.baseUrl}${endpoint}`, {
      method,
      headers: {
        'Authorization': `Bearer ${this.apiKey}`,
        'Content-Type': 'application/json',
      },
      body: method !== 'GET' ? JSON.stringify(data) : undefined
    });

    if (!response.ok) {
      const error = await response.json().catch(() => ({}));
      throw new Error(`SendGrid API error: ${response.status} - ${error.errors?.[0]?.message || response.statusText}`);
    }

    return response.json();
  }

  // Send email
  public async sendEmail(request: SendEmailRequest): Promise<SendEmailResponse> {
    const emailData = {
      personalizations: [{
        to: request.to,
        ...(request.cc && { cc: request.cc }),
        ...(request.bcc && { bcc: request.bcc }),
        ...(request.dynamic_template_data && { dynamic_template_data: request.dynamic_template_data }),
        ...(request.custom_args && { custom_args: request.custom_args }),
        ...(request.send_at && { send_at: request.send_at })
      }],
      from: request.from,
      ...(request.reply_to && { reply_to: request.reply_to }),
      subject: request.subject,
      content: request.content,
      ...(request.template_id && { template_id: request.template_id }),
      ...(request.attachments && { attachments: request.attachments }),
      ...(request.categories && { categories: request.categories }),
      ...(request.batch_id && { batch_id: request.batch_id })
    };

    return this.makeRequest<SendEmailResponse>('/mail/send', emailData);
  }

  // Send simple email
  public async sendSimpleEmail(
    to: string | string[],
    subject: string,
    content: string,
    isHtml: boolean = true,
    from?: EmailAddress
  ): Promise<SendEmailResponse> {
    const toAddresses = Array.isArray(to) 
      ? to.map(email => ({ email }))
      : [{ email: to }];

    const emailRequest: SendEmailRequest = {
      to: toAddresses,
      from: from || { email: this.fromEmail, name: this.fromName },
      subject,
      content: [{
        type: isHtml ? 'text/html' : 'text/plain',
        value: content
      }],
      categories: ['stargate.ci']
    };

    return this.sendEmail(emailRequest);
  }

  // Send Stargate.ci welcome email
  public async sendWelcomeEmail(userEmail: string, userName?: string): Promise<SendEmailResponse> {
    const subject = 'Welcome to Stargate.ci - Your Gateway to AI Innovation';
    const htmlContent = `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <title>Welcome to Stargate.ci</title>
        <style>
          body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
          .container { max-width: 600px; margin: 0 auto; padding: 20px; }
          .header { background: linear-gradient(135deg, #0ea5e9, #a855f7); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
          .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
          .button { display: inline-block; background: #0ea5e9; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
          .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h1>🚀 Welcome to Stargate.ci</h1>
            <p>Your Gateway to AI Innovation</p>
          </div>
          <div class="content">
            <h2>Hello${userName ? ` ${userName}` : ''}!</h2>
            <p>Welcome to Stargate.ci, the premier educational platform connecting you with the revolutionary Stargate project and Cristal Intelligence initiatives.</p>
            
            <h3>What you can explore:</h3>
            <ul>
              <li>📚 <strong>Educational Content</strong> - Learn about AI, the Stargate project, and cutting-edge technologies</li>
              <li>🔍 <strong>Advanced Search</strong> - Find information quickly with our AI-powered search</li>
              <li>👥 <strong>Community</strong> - Connect with like-minded individuals and experts</li>
              <li>🤖 <strong>AI Integration</strong> - Experience the future of artificial intelligence</li>
            </ul>
            
            <p>Ready to get started?</p>
            <a href="https://stargate.ci" class="button">Explore Stargate.ci</a>
            
            <p>If you have any questions, feel free to reach out to our support team.</p>
          </div>
          <div class="footer">
            <p>© 2025 Stargate.ci - Where Stargate meets Cristal Intelligence</p>
            <p>This email was sent to ${userEmail}</p>
          </div>
        </div>
      </body>
      </html>
    `;

    return this.sendSimpleEmail(userEmail, subject, htmlContent, true);
  }

  // Send contact form notification
  public async sendContactNotification(
    contactData: {
      name: string;
      email: string;
      subject: string;
      message: string;
    }
  ): Promise<SendEmailResponse> {
    const subject = `New Contact Form Submission: ${contactData.subject}`;
    const htmlContent = `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <title>New Contact Form Submission</title>
        <style>
          body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
          .container { max-width: 600px; margin: 0 auto; padding: 20px; }
          .header { background: #0ea5e9; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
          .content { background: #f8f9fa; padding: 20px; border-radius: 0 0 5px 5px; }
          .field { margin: 15px 0; }
          .label { font-weight: bold; color: #0ea5e9; }
          .value { margin-top: 5px; padding: 10px; background: white; border-radius: 3px; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h2>📧 New Contact Form Submission</h2>
          </div>
          <div class="content">
            <div class="field">
              <div class="label">Name:</div>
              <div class="value">${contactData.name}</div>
            </div>
            <div class="field">
              <div class="label">Email:</div>
              <div class="value">${contactData.email}</div>
            </div>
            <div class="field">
              <div class="label">Subject:</div>
              <div class="value">${contactData.subject}</div>
            </div>
            <div class="field">
              <div class="label">Message:</div>
              <div class="value">${contactData.message.replace(/\n/g, '<br>')}</div>
            </div>
          </div>
        </div>
      </body>
      </html>
    `;

    return this.sendSimpleEmail(this.fromEmail, subject, htmlContent, true);
  }

  // Send password reset email
  public async sendPasswordResetEmail(userEmail: string, resetToken: string, userName?: string): Promise<SendEmailResponse> {
    const resetUrl = `https://stargate.ci/reset-password?token=${resetToken}`;
    const subject = 'Reset Your Stargate.ci Password';
    const htmlContent = `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8">
        <title>Reset Your Password</title>
        <style>
          body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
          .container { max-width: 600px; margin: 0 auto; padding: 20px; }
          .header { background: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
          .content { background: #f8f9fa; padding: 20px; border-radius: 0 0 5px 5px; }
          .button { display: inline-block; background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
          .warning { background: #fef2f2; border: 1px solid #fecaca; padding: 15px; border-radius: 5px; margin: 20px 0; }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <h2>🔐 Password Reset Request</h2>
          </div>
          <div class="content">
            <h3>Hello${userName ? ` ${userName}` : ''}!</h3>
            <p>We received a request to reset your password for your Stargate.ci account.</p>
            
            <p>Click the button below to reset your password:</p>
            <a href="${resetUrl}" class="button">Reset Password</a>
            
            <div class="warning">
              <strong>⚠️ Important:</strong>
              <ul>
                <li>This link will expire in 1 hour</li>
                <li>If you didn't request this reset, please ignore this email</li>
                <li>For security, don't share this link with anyone</li>
              </ul>
            </div>
            
            <p>If the button doesn't work, copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #0ea5e9;">${resetUrl}</p>
          </div>
        </div>
      </body>
      </html>
    `;

    return this.sendSimpleEmail(userEmail, subject, htmlContent, true);
  }

  // Create email template
  public async createTemplate(request: TemplateRequest): Promise<TemplateResponse> {
    return this.makeRequest<TemplateResponse>('/templates', request);
  }

  // Get templates
  public async getTemplates(): Promise<{ result: TemplateResponse[] }> {
    return this.makeRequest<{ result: TemplateResponse[] }>('/templates', {}, 'GET');
  }

  // Create contact list
  public async createList(request: ListRequest): Promise<ListResponse> {
    return this.makeRequest<ListResponse>('/marketing/lists', request);
  }

  // Add contact to list
  public async addContact(request: ContactRequest): Promise<ContactResponse> {
    return this.makeRequest<ContactResponse>('/marketing/contacts', {
      contacts: [request]
    });
  }

  // Get contact lists
  public async getLists(): Promise<{ result: ListResponse[] }> {
    return this.makeRequest<{ result: ListResponse[] }>('/marketing/lists', {}, 'GET');
  }

  // Send newsletter
  public async sendNewsletter(
    listId: string,
    subject: string,
    content: string,
    templateId?: string
  ): Promise<SendEmailResponse> {
    const emailRequest: SendEmailRequest = {
      to: [{ email: 'newsletter@stargate.ci' }], // Will be replaced by SendGrid with list members
      from: { email: this.fromEmail, name: this.fromName },
      subject,
      content: [{
        type: 'text/html',
        value: content
      }],
      ...(templateId && { template_id: templateId }),
      categories: ['stargate.ci', 'newsletter']
    };

    return this.sendEmail(emailRequest);
  }

  // Get email statistics
  public async getEmailStats(startDate: string, endDate: string): Promise<any> {
    return this.makeRequest<any>(`/stats?start_date=${startDate}&end_date=${endDate}`, {}, 'GET');
  }

  // Verify email address
  public async verifyEmail(email: string): Promise<boolean> {
    try {
      // This would typically use SendGrid's email validation API
      // For now, we'll do a basic email format check
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    } catch {
      return false;
    }
  }
}

// Export singleton instance
export const sendgridService = new SendGridService();
