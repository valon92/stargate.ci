#!/bin/bash

# Stargate.ci Deployment Script
echo "🚀 Starting Stargate.ci deployment..."

# Build frontend
echo "📦 Building frontend..."
cd frontend
npm install
npm run build
cd ..

# Prepare backend
echo "🔧 Preparing backend..."
cd backend
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
cd ..

echo "✅ Build complete! Ready for deployment."
echo ""
echo "📋 Next steps:"
echo "1. Deploy frontend to Vercel: vercel --prod"
echo "2. Deploy backend to Vercel: cd backend && vercel --prod"
echo "3. Update environment variables in Vercel dashboard"
echo ""
echo "🌐 Your Stargate.ci platform is ready!"
