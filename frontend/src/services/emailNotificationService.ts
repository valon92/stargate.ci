// Email Notification Service for Stargate.ci
// This service handles email notifications for subscribers

export interface EmailNotification {
  to: string
  subject: string
  content: string
  type: 'welcome' | 'newsletter' | 'update' | 'announcement'
}

export interface Subscriber {
  id: number
  username: string
  email: string
  fullName?: string
  organization?: string
  interests: string[]
  role?: string
  subscribedAt: string
  status: string
}

class EmailNotificationService {
  private subscribers: Subscriber[] = []

  constructor() {
    this.loadSubscribers()
  }

  // Load subscribers from localStorage
  private loadSubscribers(): void {
    const stored = localStorage.getItem('stargate_subscribers')
    if (stored) {
      this.subscribers = JSON.parse(stored)
    }
  }

  // Save subscribers to localStorage
  private saveSubscribers(): void {
    localStorage.setItem('stargate_subscribers', JSON.stringify(this.subscribers))
  }

  // Add new subscriber
  addSubscriber(subscriber: Omit<Subscriber, 'id' | 'subscribedAt' | 'status'>): Subscriber {
    const newSubscriber: Subscriber = {
      ...subscriber,
      id: Date.now(),
      subscribedAt: new Date().toISOString(),
      status: 'active'
    }
    
    this.subscribers.push(newSubscriber)
    this.saveSubscribers()
    
    // Send welcome email
    this.sendWelcomeEmail(newSubscriber)
    
    return newSubscriber
  }

  // Send welcome email to new subscriber
  private async sendWelcomeEmail(subscriber: Subscriber): Promise<void> {
    const notification: EmailNotification = {
      to: subscriber.email,
      subject: 'Welcome to Stargate.ci - Your Subscription is Active!',
      content: this.generateWelcomeEmailContent(subscriber),
      type: 'welcome'
    }
    
    await this.sendEmail(notification)
  }

  // Generate welcome email content
  private generateWelcomeEmailContent(subscriber: Subscriber): string {
    return `
      <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background-color: #1a1a1a; color: white;">
        <div style="text-align: center; margin-bottom: 30px;">
          <h1 style="color: #4f46e5; margin-bottom: 10px;">Welcome to Stargate.ci!</h1>
          <p style="color: #9ca3af; font-size: 18px;">Your subscription is now active</p>
        </div>
        
        <div style="background-color: #2a2a2a; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
          <h2 style="color: #4f46e5; margin-bottom: 15px;">Hello ${subscriber.fullName || subscriber.username}!</h2>
          <p style="line-height: 1.6; margin-bottom: 15px;">
            Thank you for subscribing to Stargate.ci! You're now part of our growing community of innovators, 
            researchers, and technology enthusiasts who are staying updated with the latest developments in 
            Stargate Project and Cristal Intelligence.
          </p>
          
          <div style="background-color: #1a1a1a; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <h3 style="color: #4f46e5; margin-bottom: 10px;">Your Subscription Details:</h3>
            <p><strong>Username:</strong> ${subscriber.username}</p>
            <p><strong>Email:</strong> ${subscriber.email}</p>
            ${subscriber.organization ? `<p><strong>Organization:</strong> ${subscriber.organization}</p>` : ''}
            ${subscriber.role ? `<p><strong>Role:</strong> ${subscriber.role}</p>` : ''}
            <p><strong>Interests:</strong> ${subscriber.interests.map(interest => this.formatInterest(interest)).join(', ')}</p>
            <p><strong>Subscription Date:</strong> ${new Date(subscriber.subscribedAt).toLocaleDateString()}</p>
          </div>
        </div>
        
        <div style="background-color: #2a2a2a; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
          <h3 style="color: #4f46e5; margin-bottom: 15px;">What to Expect:</h3>
          <ul style="line-height: 1.8;">
            <li>📰 <strong>Latest News:</strong> Stay updated with the latest developments in Stargate Project and Cristal Intelligence</li>
            <li>🎓 <strong>Educational Resources:</strong> Access comprehensive learning materials and insights about AI technologies</li>
            <li>🌐 <strong>Community Access:</strong> Connect with other innovators and technology enthusiasts</li>
            <li>🔬 <strong>Research Updates:</strong> Get notified about new research findings and technical breakthroughs</li>
          </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
          <a href="https://stargate.ci" style="background-color: #4f46e5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; display: inline-block; font-weight: bold;">
            Visit Stargate.ci
          </a>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #333;">
          <p style="color: #9ca3af; font-size: 14px;">
            This email was sent to ${subscriber.email} because you subscribed to Stargate.ci updates.<br>
            If you no longer wish to receive these emails, you can unsubscribe at any time.
          </p>
        </div>
      </div>
    `
  }

  // Format interest for display
  private formatInterest(interest: string): string {
    const interestMap: Record<string, string> = {
      'stargate': 'Stargate Project',
      'cristal': 'Cristal Intelligence',
      'ai-ethics': 'AI Ethics',
      'research': 'Research'
    }
    return interestMap[interest] || interest
  }

  // Send email notification (simulated)
  private async sendEmail(notification: EmailNotification): Promise<void> {
    // In a real application, this would integrate with an email service like SendGrid, Mailgun, etc.
    // For demo purposes, we'll simulate the email sending and log it
    
    console.log('📧 Email Notification Sent:')
    console.log('To:', notification.to)
    console.log('Subject:', notification.subject)
    console.log('Type:', notification.type)
    console.log('Content Preview:', notification.content.substring(0, 200) + '...')
    
    // Simulate email sending delay
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // In a real implementation, you would:
    // 1. Call your email service API
    // 2. Handle success/error responses
    // 3. Log email delivery status
    // 4. Store email history in database
    
    console.log('✅ Email sent successfully!')
  }

  // Send newsletter to all subscribers
  async sendNewsletter(subject: string, content: string): Promise<void> {
    const activeSubscribers = this.subscribers.filter(sub => sub.status === 'active')
    
    console.log(`📧 Sending newsletter to ${activeSubscribers.length} subscribers`)
    
    for (const subscriber of activeSubscribers) {
      const notification: EmailNotification = {
        to: subscriber.email,
        subject: subject,
        content: content,
        type: 'newsletter'
      }
      
      await this.sendEmail(notification)
      
      // Add delay between emails to avoid rate limiting
      await new Promise(resolve => setTimeout(resolve, 100))
    }
    
    console.log('✅ Newsletter sent to all subscribers!')
  }

  // Send update notification to subscribers with specific interests
  async sendUpdateNotification(interests: string[], subject: string, content: string): Promise<void> {
    const relevantSubscribers = this.subscribers.filter(sub => 
      sub.status === 'active' && 
      sub.interests.some(interest => interests.includes(interest))
    )
    
    console.log(`📧 Sending update notification to ${relevantSubscribers.length} subscribers with interests: ${interests.join(', ')}`)
    
    for (const subscriber of relevantSubscribers) {
      const notification: EmailNotification = {
        to: subscriber.email,
        subject: subject,
        content: content,
        type: 'update'
      }
      
      await this.sendEmail(notification)
      
      // Add delay between emails
      await new Promise(resolve => setTimeout(resolve, 100))
    }
    
    console.log('✅ Update notification sent!')
  }

  // Get all subscribers
  getSubscribers(): Subscriber[] {
    return this.subscribers
  }

  // Get subscriber by email
  getSubscriberByEmail(email: string): Subscriber | undefined {
    return this.subscribers.find(sub => sub.email === email)
  }

  // Get subscribers by interest
  getSubscribersByInterest(interest: string): Subscriber[] {
    return this.subscribers.filter(sub => 
      sub.status === 'active' && sub.interests.includes(interest)
    )
  }

  // Get subscription statistics
  getSubscriptionStats(): {
    total: number
    active: number
    byInterest: Record<string, number>
    byRole: Record<string, number>
  } {
    const active = this.subscribers.filter(sub => sub.status === 'active')
    
    const byInterest: Record<string, number> = {}
    const byRole: Record<string, number> = {}
    
    active.forEach(sub => {
      sub.interests.forEach(interest => {
        byInterest[interest] = (byInterest[interest] || 0) + 1
      })
      
      if (sub.role) {
        byRole[sub.role] = (byRole[sub.role] || 0) + 1
      }
    })
    
    return {
      total: this.subscribers.length,
      active: active.length,
      byInterest,
      byRole
    }
  }
}

// Export singleton instance
export const emailNotificationService = new EmailNotificationService()
