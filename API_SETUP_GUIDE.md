# 🚀 API Integration Setup Guide for Stargate.ci

## 📋 Overview

This guide will help you set up all external API integrations for the Stargate.ci platform. The platform supports multiple APIs for enhanced functionality including AI features, payments, email services, and analytics.

## 🔧 Prerequisites

- Node.js 20+
- Vue.js 3 project setup
- Access to external API services
- Environment variables configuration

## 🎯 Quick Setup

### 1. Environment Variables

Copy the environment template and configure your API keys:

```bash
cp frontend/env.example frontend/.env
```

Edit `frontend/.env` with your actual API keys.

### 2. Install Dependencies

```bash
cd frontend
npm install
```

### 3. Build and Test

```bash
npm run build
npm run dev
```

## 🔌 API Integrations

### 1. 🤖 OpenAI Integration

**Purpose**: AI-powered features, content generation, chat assistance

**Setup Steps**:
1. Go to [OpenAI Platform](https://platform.openai.com/)
2. Create an account and get API key
3. Add to environment variables:
   ```env
   VITE_OPENAI_API_KEY=sk-your-openai-api-key-here
   ```

**Features Enabled**:
- ✅ AI chat assistance
- ✅ Content generation for articles/FAQs
- ✅ Search optimization with embeddings
- ✅ Content moderation
- ✅ Image generation with DALL-E

**Cost**: ~$20-100/month (based on usage)

### 2. 💳 Stripe Integration

**Purpose**: Payment processing, subscriptions, billing

**Setup Steps**:
1. Go to [Stripe Dashboard](https://dashboard.stripe.com/)
2. Get your API keys (test and live)
3. Add to environment variables:
   ```env
   VITE_STRIPE_PUBLISHABLE_KEY_TEST=pk_test_your-key-here
   VITE_STRIPE_SECRET_KEY_TEST=sk_test_your-key-here
   VITE_STRIPE_PUBLISHABLE_KEY_PROD=pk_live_your-key-here
   VITE_STRIPE_SECRET_KEY_PROD=sk_live_your-key-here
   VITE_STRIPE_WEBHOOK_SECRET=whsec_your-webhook-secret
   ```

**Features Enabled**:
- ✅ Payment processing
- ✅ Subscription management
- ✅ Customer management
- ✅ Webhook handling

**Cost**: 2.9% + 30¢ per transaction

### 3. 📧 SendGrid Integration

**Purpose**: Transactional emails, notifications, email marketing

**Setup Steps**:
1. Go to [SendGrid](https://sendgrid.com/)
2. Create account and get API key
3. Add to environment variables:
   ```env
   VITE_SENDGRID_API_KEY=SG.your-sendgrid-api-key-here
   VITE_SENDGRID_FROM_EMAIL=noreply@stargate.ci
   VITE_SENDGRID_FROM_NAME=Stargate.ci
   ```

**Features Enabled**:
- ✅ Welcome emails
- ✅ Password reset emails
- ✅ Contact form notifications
- ✅ Newsletter sending
- ✅ Email templates

**Cost**: Free tier (100 emails/day), paid plans from $14.95/month

### 4. 📊 Google Analytics Integration

**Purpose**: Website analytics, user behavior tracking, performance monitoring

**Setup Steps**:
1. Go to [Google Analytics](https://analytics.google.com/)
2. Create property and get tracking ID
3. Add to environment variables:
   ```env
   VITE_GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
   VITE_GOOGLE_TAG_MANAGER_ID=GTM-XXXXXXX
   ```

**Features Enabled**:
- ✅ Page view tracking
- ✅ Event tracking
- ✅ User behavior analytics
- ✅ Performance monitoring
- ✅ Custom event tracking

**Cost**: Free

## 🛠️ Advanced Integrations (Optional)

### 5. ☁️ AWS Integration

**Purpose**: File storage, CDN, cloud services

**Setup Steps**:
1. Go to [AWS Console](https://aws.amazon.com/)
2. Create IAM user with S3 and CloudFront permissions
3. Add to environment variables:
   ```env
   VITE_AWS_ACCESS_KEY_ID=your-aws-access-key-id
   VITE_AWS_SECRET_ACCESS_KEY=your-aws-secret-access-key
   VITE_AWS_REGION=us-east-1
   VITE_AWS_S3_BUCKET=your-s3-bucket-name
   VITE_AWS_CLOUDFRONT_DOMAIN=your-cloudfront-domain.cloudfront.net
   ```

**Features Enabled**:
- ✅ File upload to S3
- ✅ CDN delivery
- ✅ Image optimization
- ✅ Static asset hosting

### 6. 🔐 Auth0 Integration

**Purpose**: Advanced authentication, social login

**Setup Steps**:
1. Go to [Auth0](https://auth0.com/)
2. Create application and get credentials
3. Add to environment variables:
   ```env
   VITE_AUTH0_DOMAIN=your-domain.auth0.com
   VITE_AUTH0_CLIENT_ID=your-auth0-client-id
   VITE_AUTH0_CLIENT_SECRET=your-auth0-client-secret
   VITE_AUTH0_AUDIENCE=your-auth0-audience
   ```

**Features Enabled**:
- ✅ Social login (Google, Facebook, LinkedIn)
- ✅ Advanced user management
- ✅ Multi-factor authentication
- ✅ Role-based access control

### 7. 📱 Firebase Integration

**Purpose**: Push notifications, real-time features

**Setup Steps**:
1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Create project and get configuration
3. Add to environment variables:
   ```env
   VITE_FIREBASE_API_KEY=your-firebase-api-key
   VITE_FIREBASE_AUTH_DOMAIN=your-project.firebaseapp.com
   VITE_FIREBASE_PROJECT_ID=your-project-id
   VITE_FIREBASE_MESSAGING_SENDER_ID=123456789
   VITE_FIREBASE_APP_ID=1:123456789:web:abcdef123456
   ```

**Features Enabled**:
- ✅ Push notifications
- ✅ Real-time database
- ✅ Cloud messaging
- ✅ Analytics

## 🎛️ Integration Dashboard

Access the integration dashboard at `/admin/integrations` to:

- ✅ Monitor API health status
- ✅ View usage statistics
- ✅ Get setup recommendations
- ✅ Track costs and limits
- ✅ Test service connectivity

## 🔍 Testing Integrations

### Test OpenAI
```typescript
import { openaiService } from './services/openaiService'

// Test AI response
const response = await openaiService.generateStargateResponse('Hello, how can you help me?')
console.log(response)
```

### Test Stripe
```typescript
import { stripeService } from './services/stripeService'

// Test payment intent
const paymentIntent = await stripeService.createPaymentIntent({
  amount: 2000, // $20.00
  currency: 'usd',
  description: 'Test payment'
})
console.log(paymentIntent)
```

### Test SendGrid
```typescript
import { sendgridService } from './services/sendgridService'

// Test email sending
await sendgridService.sendWelcomeEmail('user@example.com', 'John Doe')
```

### Test Analytics
```typescript
import { analyticsService } from './services/analyticsService'

// Test event tracking
analyticsService.trackEvent('test_event', 'integration', 'test')
```

## 🚨 Troubleshooting

### Common Issues

1. **API Key Not Working**
   - Check if API key is correctly set in environment variables
   - Verify API key has proper permissions
   - Check if you're using test vs production keys

2. **Rate Limiting**
   - Monitor usage in integration dashboard
   - Implement proper rate limiting
   - Consider upgrading API plans

3. **CORS Issues**
   - Ensure proper CORS configuration
   - Check if API endpoints are accessible
   - Verify domain whitelist settings

4. **Environment Variables Not Loading**
   - Restart development server after changing .env
   - Check variable naming (must start with VITE_)
   - Verify .env file is in correct location

### Debug Mode

Enable debug mode to see detailed API logs:

```env
VITE_DEBUG_MODE=true
```

## 📊 Monitoring and Maintenance

### Health Checks
- Monitor API health every 5 minutes
- Set up alerts for service failures
- Track usage patterns and costs

### Cost Management
- Set up billing alerts
- Monitor usage against limits
- Optimize API calls for efficiency

### Security
- Rotate API keys regularly
- Use environment-specific keys
- Implement proper access controls

## 🎯 Next Steps

1. **Configure Core APIs** (OpenAI, Stripe, SendGrid, Analytics)
2. **Test All Integrations** using the provided test functions
3. **Set Up Monitoring** and health checks
4. **Configure Advanced Features** (AWS, Auth0, Firebase) as needed
5. **Deploy to Production** with proper environment variables

## 📞 Support

For issues with specific APIs:
- **OpenAI**: [OpenAI Help Center](https://help.openai.com/)
- **Stripe**: [Stripe Support](https://support.stripe.com/)
- **SendGrid**: [SendGrid Support](https://support.sendgrid.com/)
- **Google Analytics**: [Google Analytics Help](https://support.google.com/analytics/)

For Stargate.ci specific issues, check the integration dashboard or contact the development team.

---

**🎉 Congratulations!** You now have a fully integrated API infrastructure for Stargate.ci!
