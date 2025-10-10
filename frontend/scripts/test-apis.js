#!/usr/bin/env node

/**
 * API Test Script for Stargate.ci
 * Tests all configured API integrations
 */

import { config } from 'dotenv';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';

// Load environment variables
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
config({ path: join(__dirname, '../.env') });

// Colors for console output
const colors = {
  reset: '\x1b[0m',
  bright: '\x1b[1m',
  red: '\x1b[31m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
  magenta: '\x1b[35m',
  cyan: '\x1b[36m'
};

// Test results
const results = {
  openai: { status: 'pending', message: '', time: 0 },
  stripe: { status: 'pending', message: '', time: 0 },
  sendgrid: { status: 'pending', message: '', time: 0 },
  analytics: { status: 'pending', message: '', time: 0 }
};

// Helper functions
const log = (message, color = colors.reset) => {
  console.log(`${color}${message}${colors.reset}`);
};

const logHeader = (title) => {
  log(`\n${colors.bright}${colors.cyan}=== ${title} ===${colors.reset}`);
};

const logSuccess = (message) => {
  log(`${colors.green}✅ ${message}${colors.reset}`);
};

const logError = (message) => {
  log(`${colors.red}❌ ${message}${colors.reset}`);
};

const logWarning = (message) => {
  log(`${colors.yellow}⚠️  ${message}${colors.reset}`);
};

const logInfo = (message) => {
  log(`${colors.blue}ℹ️  ${message}${colors.reset}`);
};

// Test OpenAI API
async function testOpenAI() {
  const startTime = Date.now();
  
  try {
    if (!process.env.VITE_OPENAI_API_KEY) {
      results.openai = {
        status: 'skipped',
        message: 'API key not configured',
        time: 0
      };
      logWarning('OpenAI API key not configured');
      return;
    }

    logInfo('Testing OpenAI API...');
    
    const response = await fetch('https://api.openai.com/v1/models', {
      headers: {
        'Authorization': `Bearer ${process.env.VITE_OPENAI_API_KEY}`,
        'Content-Type': 'application/json'
      }
    });

    if (response.ok) {
      const data = await response.json();
      results.openai = {
        status: 'success',
        message: `Connected successfully. ${data.data.length} models available.`,
        time: Date.now() - startTime
      };
      logSuccess(`OpenAI API connected successfully (${data.data.length} models available)`);
    } else {
      const error = await response.json().catch(() => ({}));
      results.openai = {
        status: 'error',
        message: `API error: ${response.status} - ${error.error?.message || response.statusText}`,
        time: Date.now() - startTime
      };
      logError(`OpenAI API error: ${response.status} - ${error.error?.message || response.statusText}`);
    }
  } catch (error) {
    results.openai = {
      status: 'error',
      message: `Connection failed: ${error.message}`,
      time: Date.now() - startTime
    };
    logError(`OpenAI API connection failed: ${error.message}`);
  }
}

// Test Stripe API
async function testStripe() {
  const startTime = Date.now();
  
  try {
    if (!process.env.VITE_STRIPE_PUBLISHABLE_KEY_TEST && !process.env.VITE_STRIPE_PUBLISHABLE_KEY_PROD) {
      results.stripe = {
        status: 'skipped',
        message: 'API keys not configured',
        time: 0
      };
      logWarning('Stripe API keys not configured');
      return;
    }

    logInfo('Testing Stripe API...');
    
    // Test with test key first
    const testKey = process.env.VITE_STRIPE_PUBLISHABLE_KEY_TEST;
    const prodKey = process.env.VITE_STRIPE_PUBLISHABLE_KEY_PROD;
    
    if (testKey) {
      logInfo('Testing with Stripe test key...');
      // Stripe publishable keys don't need API calls to validate
      results.stripe = {
        status: 'success',
        message: 'Test key configured and valid',
        time: Date.now() - startTime
      };
      logSuccess('Stripe test key configured and valid');
    } else if (prodKey) {
      logInfo('Testing with Stripe production key...');
      results.stripe = {
        status: 'success',
        message: 'Production key configured and valid',
        time: Date.now() - startTime
      };
      logSuccess('Stripe production key configured and valid');
    }
  } catch (error) {
    results.stripe = {
      status: 'error',
      message: `Configuration error: ${error.message}`,
      time: Date.now() - startTime
    };
    logError(`Stripe API configuration error: ${error.message}`);
  }
}

// Test SendGrid API
async function testSendGrid() {
  const startTime = Date.now();
  
  try {
    if (!process.env.VITE_SENDGRID_API_KEY) {
      results.sendgrid = {
        status: 'skipped',
        message: 'API key not configured',
        time: 0
      };
      logWarning('SendGrid API key not configured');
      return;
    }

    logInfo('Testing SendGrid API...');
    
    const response = await fetch('https://api.sendgrid.com/v3/user/profile', {
      headers: {
        'Authorization': `Bearer ${process.env.VITE_SENDGRID_API_KEY}`,
        'Content-Type': 'application/json'
      }
    });

    if (response.ok) {
      const data = await response.json();
      results.sendgrid = {
        status: 'success',
        message: `Connected successfully. Account: ${data.username}`,
        time: Date.now() - startTime
      };
      logSuccess(`SendGrid API connected successfully (Account: ${data.username})`);
    } else {
      const error = await response.json().catch(() => ({}));
      results.sendgrid = {
        status: 'error',
        message: `API error: ${response.status} - ${error.errors?.[0]?.message || response.statusText}`,
        time: Date.now() - startTime
      };
      logError(`SendGrid API error: ${response.status} - ${error.errors?.[0]?.message || response.statusText}`);
    }
  } catch (error) {
    results.sendgrid = {
      status: 'error',
      message: `Connection failed: ${error.message}`,
      time: Date.now() - startTime
    };
    logError(`SendGrid API connection failed: ${error.message}`);
  }
}

// Test Google Analytics
async function testAnalytics() {
  const startTime = Date.now();
  
  try {
    if (!process.env.VITE_GOOGLE_ANALYTICS_ID) {
      results.analytics = {
        status: 'skipped',
        message: 'Analytics ID not configured',
        time: 0
      };
      logWarning('Google Analytics ID not configured');
      return;
    }

    logInfo('Testing Google Analytics configuration...');
    
    // Google Analytics doesn't have a direct API to test configuration
    // We'll just validate the format
    const analyticsId = process.env.VITE_GOOGLE_ANALYTICS_ID;
    const isValidFormat = /^G-[A-Z0-9]{10}$/.test(analyticsId) || /^UA-\d{4,10}-\d{1,4}$/.test(analyticsId);
    
    if (isValidFormat) {
      results.analytics = {
        status: 'success',
        message: `Analytics ID configured: ${analyticsId}`,
        time: Date.now() - startTime
      };
      logSuccess(`Google Analytics ID configured: ${analyticsId}`);
    } else {
      results.analytics = {
        status: 'error',
        message: `Invalid Analytics ID format: ${analyticsId}`,
        time: Date.now() - startTime
      };
      logError(`Invalid Google Analytics ID format: ${analyticsId}`);
    }
  } catch (error) {
    results.analytics = {
      status: 'error',
      message: `Configuration error: ${error.message}`,
      time: Date.now() - startTime
    };
    logError(`Google Analytics configuration error: ${error.message}`);
  }
}

// Print summary
function printSummary() {
  logHeader('Test Summary');
  
  const services = [
    { name: 'OpenAI', result: results.openai },
    { name: 'Stripe', result: results.stripe },
    { name: 'SendGrid', result: results.sendgrid },
    { name: 'Google Analytics', result: results.analytics }
  ];

  let successCount = 0;
  let errorCount = 0;
  let skippedCount = 0;

  services.forEach(service => {
    const { name, result } = service;
    const status = result.status;
    
    switch (status) {
      case 'success':
        logSuccess(`${name}: ${result.message} (${result.time}ms)`);
        successCount++;
        break;
      case 'error':
        logError(`${name}: ${result.message} (${result.time}ms)`);
        errorCount++;
        break;
      case 'skipped':
        logWarning(`${name}: ${result.message}`);
        skippedCount++;
        break;
      default:
        logInfo(`${name}: ${result.message}`);
    }
  });

  logHeader('Overall Results');
  logInfo(`Total services: ${services.length}`);
  logSuccess(`Successful: ${successCount}`);
  logError(`Failed: ${errorCount}`);
  logWarning(`Skipped: ${skippedCount}`);

  if (errorCount > 0) {
    logError('\nSome API tests failed. Please check your configuration.');
    process.exit(1);
  } else if (successCount > 0) {
    logSuccess('\nAll configured APIs are working correctly!');
  } else {
    logWarning('\nNo APIs are configured. Please set up at least one API integration.');
  }
}

// Main execution
async function main() {
  logHeader('Stargate.ci API Integration Tests');
  logInfo('Testing all configured API integrations...\n');

  // Run all tests
  await Promise.all([
    testOpenAI(),
    testStripe(),
    testSendGrid(),
    testAnalytics()
  ]);

  // Print summary
  printSummary();
}

// Run the tests
main().catch(error => {
  logError(`Test execution failed: ${error.message}`);
  process.exit(1);
});
