#!/bin/bash

# Stargate.ci Production Deployment Setup Script
# This script sets up the Laravel backend and Vue.js frontend for production

echo "🚀 Setting up Stargate.ci Production Deployment..."

# Set proper permissions
echo "📁 Setting up permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs

# Install PHP dependencies (production optimized)
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# Generate application key if not exists
echo "🔑 Generating application key..."
php artisan key:generate --force

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed database with initial data
echo "🌱 Seeding database..."
php artisan db:seed --force

# Clear and cache configuration
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create symbolic link for storage
echo "🔗 Creating storage link..."
php artisan storage:link

# Set up web server configuration
echo "🌐 Setting up web server configuration..."
cat > .htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>
EOF

# Create frontend .htaccess for SPA routing
echo "🌐 Setting up frontend routing..."
cat > frontend/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle Angular and Vue.js routes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.html [L]
</IfModule>

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive on
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
EOF

# Create environment configuration template
echo "⚙️ Creating environment configuration..."
cat > .env.production << 'EOF'
APP_NAME="Stargate.ci"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://stargate.ci

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stargate_ci
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@stargate.ci"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# AI Services Configuration
OPENAI_API_KEY=your_openai_api_key
GEMINI_API_KEY=your_gemini_api_key
COHERE_API_KEY=your_cohere_api_key
SOFTBANK_API_KEY=your_softbank_api_key
ORACLE_API_KEY=your_oracle_api_key
MGX_API_KEY=your_mgx_api_key
EOF

# Create deployment instructions
echo "📋 Creating deployment instructions..."
cat > DEPLOYMENT_INSTRUCTIONS.md << 'EOF'
# Stargate.ci Production Deployment Instructions

## Prerequisites
- PHP 8.1+ with extensions: BCMath, Ctype, cURL, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PCRE, PDO, Tokenizer, XML
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Web server (Apache/Nginx)

## Deployment Steps

### 1. Upload Files
Upload the entire `stargate-ci-production-deploy` folder to your web server's document root.

### 2. Database Setup
1. Create a MySQL database named `stargate_ci`
2. Update the database credentials in `.env.production`
3. Rename `.env.production` to `.env`

### 3. Run Setup Script
```bash
chmod +x setup.sh
./setup.sh
```

### 4. Configure Web Server

#### For Apache:
- Point document root to the `public` folder
- Ensure mod_rewrite is enabled
- The `.htaccess` files are already configured

#### For Nginx:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/stargate-ci-production-deploy/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 5. SSL Certificate
Install SSL certificate for HTTPS (recommended: Let's Encrypt)

### 6. Configure AI Services
Update the following environment variables with your API keys:
- OPENAI_API_KEY
- GEMINI_API_KEY
- COHERE_API_KEY
- SOFTBANK_API_KEY
- ORACLE_API_KEY
- MGX_API_KEY

### 7. Set Up Cron Jobs
Add this cron job to run every minute:
```bash
* * * * * cd /path/to/stargate-ci-production-deploy && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Frontend Configuration
The frontend is already built and optimized in the `frontend` folder.
Point your web server to serve the frontend files from the `frontend` directory.

## Post-Deployment

### Test the Application
1. Visit your domain to ensure the frontend loads
2. Test user registration and login
3. Test event interactions (likes, comments)
4. Check admin panel functionality

### Monitor Logs
- Application logs: `storage/logs/laravel.log`
- Web server error logs
- Database logs

### Performance Optimization
- Enable OPcache in PHP
- Use Redis for caching (optional)
- Set up CDN for static assets
- Configure database query caching

## Troubleshooting

### Common Issues
1. **500 Error**: Check file permissions and .env configuration
2. **Database Connection**: Verify database credentials and connection
3. **Missing Dependencies**: Run `composer install` again
4. **Cache Issues**: Clear all caches with `php artisan cache:clear`

### Support
For technical support, contact the development team.

## Security Notes
- Keep your API keys secure
- Regularly update dependencies
- Monitor for security vulnerabilities
- Use HTTPS in production
- Implement proper backup strategies
EOF

# Create a simple health check script
echo "🏥 Creating health check script..."
cat > health-check.php << 'EOF'
<?php
// Simple health check endpoint
header('Content-Type: application/json');

$checks = [
    'database' => false,
    'storage' => false,
    'cache' => false
];

// Check database connection
try {
    $pdo = new PDO(
        'mysql:host=' . env('DB_HOST', '127.0.0.1') . ';dbname=' . env('DB_DATABASE', 'stargate_ci'),
        env('DB_USERNAME', 'root'),
        env('DB_PASSWORD', '')
    );
    $checks['database'] = true;
} catch (Exception $e) {
    $checks['database'] = false;
}

// Check storage directory
$checks['storage'] = is_writable(storage_path());

// Check cache directory
$checks['cache'] = is_writable(storage_path('framework/cache'));

$all_healthy = array_reduce($checks, function($carry, $item) {
    return $carry && $item;
}, true);

http_response_code($all_healthy ? 200 : 503);

echo json_encode([
    'status' => $all_healthy ? 'healthy' : 'unhealthy',
    'checks' => $checks,
    'timestamp' => date('c')
]);
?>
EOF

echo "✅ Production deployment package created successfully!"
echo ""
echo "📁 Package location: stargate-ci-production-deploy/"
echo "📋 Instructions: See DEPLOYMENT_INSTRUCTIONS.md"
echo "🏥 Health check: health-check.php"
echo ""
echo "Next steps:"
echo "1. Upload the package to your web server"
echo "2. Configure your database"
echo "3. Run the setup script"
echo "4. Configure your web server"
echo "5. Set up SSL certificate"
echo "6. Configure AI service API keys"
echo ""
echo "🚀 Ready for production deployment!"
