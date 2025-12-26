#!/bin/bash

# Quick setup script for Stargate.ci API

echo "🚀 Setting up Stargate.ci API..."

# Copy .env.example to .env if .env doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "⚠️  Please edit .env file with your database credentials!"
    echo "   Then run: php artisan key:generate"
fi

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage

# Generate key if not set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "📊 Running migrations..."
php artisan migrate --force

# Cache configuration
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Setup complete!"
echo ""
echo "📋 Next steps:"
echo "1. Edit .env file with your database credentials"
echo "2. If you changed .env, run: php artisan config:clear"
echo "3. Test API: curl https://api.stargate.ci/api/health"
