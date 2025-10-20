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
echo "1. Upload frontend/dist/ to your web hosting (cPanel/FTP)"
echo "2. Upload backend/ to your server"
echo "3. Configure environment variables on your server"
echo "4. Run database migrations: php artisan migrate"
echo ""
echo "🌐 Your Stargate.ci platform is ready for manual deployment!"
