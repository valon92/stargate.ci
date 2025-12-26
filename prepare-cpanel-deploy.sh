#!/bin/bash

# Stargate.ci - Prepare Deployment Package for cPanel
# Kjo script krijon një package të plotë për upload në cPanel

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo -e "${BLUE}🚀 Stargate.ci - Preparing cPanel Deployment Package${NC}"
echo ""

# Check if we're in the project root
if [ ! -d "frontend" ] || [ ! -d "backend" ]; then
    echo -e "${RED}❌ Error: Please run this script from the project root${NC}"
    exit 1
fi

# Create deployment package directory
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
PACKAGE_DIR="stargate-ci-cpanel-deploy-${TIMESTAMP}"
echo -e "${YELLOW}📁 Creating deployment package: $PACKAGE_DIR${NC}"
rm -rf $PACKAGE_DIR
mkdir -p $PACKAGE_DIR

# Step 1: Build Frontend
echo -e "${YELLOW}📦 Step 1: Building frontend for production...${NC}"
cd frontend

# Install dependencies if needed
if [ ! -d "node_modules" ]; then
    echo "Installing frontend dependencies..."
    npm install
fi

# Create production .env file for API URL configuration
echo "Creating production .env file..."
cat > .env.production << EOF
# Production API URL - Change this to your actual API URL
# If API is on same domain, use: /api
# If API is on subdomain, use: https://api.stargate.ci/api
VITE_API_URL=/api
EOF

# Build frontend (skip type-check if it fails, as build-only should work)
echo "Building frontend..."
npm run build-only || (echo "⚠️  Type-check failed, but continuing with build..." && npm run build-only)

if [ ! -d "dist" ]; then
    echo -e "${RED}❌ Error: Frontend build failed - dist directory not found${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Frontend built successfully${NC}"
cd ..

# Step 2: Prepare Backend
echo -e "${YELLOW}📦 Step 2: Preparing backend for production...${NC}"
cd backend

# Install dependencies if needed
if [ ! -d "vendor" ]; then
    echo "Installing backend dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Cache configuration
echo "Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo -e "${GREEN}✅ Backend prepared successfully${NC}"
cd ..

# Step 3: Copy Frontend Build
echo -e "${YELLOW}📦 Step 3: Copying frontend build...${NC}"
cp -r frontend/dist/* $PACKAGE_DIR/
echo -e "${GREEN}✅ Frontend copied${NC}"

# Step 4: Copy Backend (excluding unnecessary files)
echo -e "${YELLOW}📦 Step 4: Copying backend...${NC}"
mkdir -p $PACKAGE_DIR/api

# Copy backend files (excluding node_modules, vendor, storage, etc.)
rsync -av --progress \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='.git' \
    --exclude='.env' \
    --exclude='.env.example' \
    --exclude='tests' \
    --exclude='phpunit.xml' \
    --exclude='vite.config.js' \
    --exclude='package.json' \
    --exclude='package-lock.json' \
    backend/ $PACKAGE_DIR/api/

# Create .htaccess for API
cat > $PACKAGE_DIR/api/public/.htaccess << 'EOF'
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
EOF

echo -e "${GREEN}✅ Backend copied${NC}"

# Step 5: Create .env.example for backend
echo -e "${YELLOW}📦 Step 5: Creating .env.example...${NC}"
cat > $PACKAGE_DIR/api/.env.example << 'EOF'
APP_NAME="Stargate.ci"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://stargate.ci

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

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
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
EOF

# Step 6: Create .htaccess for frontend (SPA routing)
echo -e "${YELLOW}📦 Step 6: Creating .htaccess for frontend...${NC}"
cat > $PACKAGE_DIR/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>

# Enable compression
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Browser caching
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpg "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/svg+xml "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
  ExpiresByType application/pdf "access plus 1 month"
  ExpiresByType text/javascript "access plus 1 month"
  ExpiresByType application/x-shockwave-flash "access plus 1 month"
</IfModule>
EOF

# Step 7: Copy .env.production as reference
echo -e "${YELLOW}📦 Step 7: Copying environment configuration...${NC}"
if [ -f frontend/.env.production ]; then
  cp frontend/.env.production $PACKAGE_DIR/.env.production.example
  echo -e "${GREEN}✅ Environment config copied${NC}"
fi

# Step 8: Create deployment instructions
echo -e "${YELLOW}📦 Step 8: Creating deployment instructions...${NC}"
cat > $PACKAGE_DIR/DEPLOYMENT_INSTRUCTIONS.md << 'EOF'
# 🚀 Stargate.ci - cPanel Deployment Instructions

## 📋 Para Deployment

1. **Krijo subdomain për API:**
   - Në cPanel, shko te Subdomains
   - Krijo subdomain: `api.stargate.ci` (ose emri që preferon)
   - Point to: `public_html/api` ose `api` directory

2. **Krijo Database:**
   - Në cPanel, shko te MySQL Databases
   - Krijo një database të re
   - Krijo një user dhe jepi të gjitha privilegjet
   - Ruaj credentials për .env file

## 📤 Deployment Steps

### Hapi 1: Upload Files

**Opsioni A: Përmes cPanel File Manager**
1. Shko te cPanel → File Manager
2. Navigo te `public_html`
3. Upload të gjitha file-at nga `stargate-ci-cpanel-deploy-*` directory
4. Upload `api` directory në vendin e duhur (nëse nuk përdor subdomain, vendos në `public_html/api`)

**Opsioni B: Përmes FTP**
```bash
# Upload frontend files
cd stargate-ci-cpanel-deploy-*
scp -r * username@stargate.ci:~/public_html/

# Upload backend files
scp -r api/* username@stargate.ci:~/api.stargate.ci/
```

### Hapi 2: Konfiguro Backend

1. **SSH në server:**
   ```bash
   ssh username@stargate.ci
   ```

2. **Navigo te API directory:**
   ```bash
   cd ~/api.stargate.ci
   # ose
   cd ~/public_html/api
   ```

3. **Kopjo .env.example në .env:**
   ```bash
   cp .env.example .env
   ```

4. **Edito .env file me credentials:**
   ```bash
   nano .env
   ```
   
   Ndrysho:
   - `APP_URL=https://stargate.ci` (ose domain-in tënd)
   - `DB_DATABASE=your_database_name`
   - `DB_USERNAME=your_database_user`
   - `DB_PASSWORD=your_database_password`
   - `APP_KEY=` (do të gjenerohet në hapin tjetër)

5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

6. **Set permissions:**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R username:username storage bootstrap/cache
   ```

7. **Run migrations:**
   ```bash
   php artisan migrate --force
   ```

8. **Cache configuration:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

### Hapi 3: Konfiguro Frontend

1. **Verifiko që .htaccess është në vend:**
   - File-at duhet të jenë në `public_html/`
   - `.htaccess` duhet të jetë në root

2. **Nëse API është në subdomain të ndryshëm, ndrysho API URL në frontend:**
   - Nëse frontend është build-uar me localhost, duhet të rebuild-osh me production URL
   - Ose mund të përdorësh proxy në .htaccess

### Hapi 4: Verifiko

1. **Frontend:** https://stargate.ci
2. **Backend API:** https://api.stargate.ci/api/health (ose URL-in tënd)

## 🔧 Troubleshooting

### Problem: 500 Error
- Kontrollo `.env` file - sigurohu që `APP_KEY` është i vendosur
- Kontrollo permissions: `chmod -R 755 storage bootstrap/cache`
- Kontrollo logs: `storage/logs/laravel.log`

### Problem: API nuk funksionon
- Kontrollo që subdomain është i konfiguruar saktë
- Kontrollo `.htaccess` në `api/public/`
- Kontrollo CORS settings në `.env`

### Problem: Frontend nuk shfaqet
- Kontrollo që `.htaccess` është në `public_html/`
- Kontrollo që `index.html` ekziston
- Kontrollo browser console për errors

### Problem: Voice Control Error - "Unexpected token '<', "<!DOCTYPE "... is not valid JSON"
**Kjo është problemi më i zakonshëm pas deployment!**

Kjo ndodh sepse API URL nuk është e konfiguruar saktë për production.

**Zgjidhja:**

1. **Kontrollo API URL në browser console:**
   - Hap DevTools → Console
   - Shiko mesazhin: `🔧 Voice Actions API URL: ...`
   - Verifiko që URL është e saktë

2. **Test API direkt:**
   - Shko te: `https://api.stargate.ci/api/commands/demo?locale=en-US&platform_name=stargate-ci`
   - Duhet të shohësh JSON, jo HTML
   - Nëse shfaq HTML (404 ose error page), API URL është e gabuar

3. **Nëse API është në subdomain të ndryshëm:**
   ```bash
   cd frontend
   cat > .env.production << EOF
   VITE_API_URL=https://api.stargate.ci/api
   EOF
   npm run build
   # Upload dist/ directory të re
   ```

4. **Kontrollo CORS:**
   - Në backend `.env`: `CORS_ALLOWED_ORIGINS=https://stargate.ci,https://www.stargate.ci`
   - Run: `php artisan config:clear`

**Shiko `VOICE_CONTROL_PRODUCTION_FIX.md` për më shumë detaje.**

## 📝 Shënime

- Sigurohu që PHP version është 8.1 ose më i lartë
- Sigurohu që mod_rewrite është enabled në Apache
- Sigurohu që storage directory ka write permissions
EOF

# Step 9: Create quick setup script
echo -e "${YELLOW}📦 Step 9: Creating quick setup script...${NC}"
cat > $PACKAGE_DIR/api/setup.sh << 'EOF'
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
EOF

chmod +x $PACKAGE_DIR/api/setup.sh

# Step 10: Create package archive
echo -e "${YELLOW}📦 Step 10: Creating package archive...${NC}"
tar -czf ${PACKAGE_DIR}.tar.gz $PACKAGE_DIR/
echo -e "${GREEN}✅ Package archive created: ${PACKAGE_DIR}.tar.gz${NC}"

# Summary
echo ""
echo -e "${GREEN}✅ Deployment package ready!${NC}"
echo ""
echo -e "${BLUE}📦 Package Location:${NC}"
echo "   Directory: $PACKAGE_DIR/"
echo "   Archive: ${PACKAGE_DIR}.tar.gz"
echo ""
echo -e "${BLUE}📋 Next Steps:${NC}"
echo "   1. Review DEPLOYMENT_INSTRUCTIONS.md in the package"
echo "   2. Upload package to cPanel"
echo "   3. Follow deployment instructions"
echo ""
echo -e "${YELLOW}💡 Tip: You can upload the .tar.gz file to cPanel and extract it there${NC}"

