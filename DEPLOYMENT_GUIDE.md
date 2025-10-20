# Stargate.ci Deployment Guide

## 🚀 Server Deployment Instructions

### 📦 Deployment Package
- **Package Name:** `stargate-ci-deployment-20251020-225826.tar.gz`
- **Size:** ~4.9 MB
- **Created:** October 20, 2025

### 📋 Contents
- `frontend/dist/` - Built Vue.js frontend
- `backend/` - Laravel backend with optimized dependencies
- `.htaccess` - Apache configuration for Vue.js SPA
- `deploy.sh` - Deployment script
- `README.md` - Project documentation

## 🔧 Server Setup Instructions

### 1. Upload Files
```bash
# Extract the deployment package
tar -xzf stargate-ci-deployment-20251020-225826.tar.gz

# Upload to your web server
# Frontend files go to public_html/ or www/
# Backend files go to a separate directory (e.g., api/)
```

### 2. Frontend Deployment
```bash
# Copy frontend files to web root
cp -r frontend/dist/* /path/to/public_html/

# Ensure .htaccess is in place
cp .htaccess /path/to/public_html/
```

### 3. Backend Deployment
```bash
# Copy backend to server
cp -r backend/ /path/to/api/

# Set proper permissions
chmod -R 755 /path/to/api/
chmod -R 777 /path/to/api/storage/
chmod -R 777 /path/to/api/bootstrap/cache/
```

### 4. Database Setup
```bash
# Navigate to backend directory
cd /path/to/api/

# Copy environment file
cp .env.example .env

# Edit .env with your database credentials
nano .env

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed
```

### 5. Environment Configuration
Update `.env` file with:
```env
APP_NAME="Stargate.ci"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# API Configuration
OPENAI_API_KEY=your-openai-key
```

### 6. Web Server Configuration

#### Apache (.htaccess already included)
The `.htaccess` file is included and configured for:
- Vue.js SPA routing
- Gzip compression
- Security headers
- Caching rules

#### Nginx Configuration
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/public_html;
    index index.html;

    # Frontend routes
    location / {
        try_files $uri $uri/ /index.html;
    }

    # API routes
    location /api {
        alias /path/to/api/public;
        try_files $uri $uri/ @api;
    }

    location @api {
        rewrite ^/api/(.*)$ /api/index.php?/$1 last;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
}
```

## 🔍 Post-Deployment Checklist

### ✅ Frontend
- [ ] Website loads at https://yourdomain.com
- [ ] All pages work (Home, About, Events, News, FAQ, Contact)
- [ ] YouTube videos embed correctly
- [ ] Interactive features work (likes, comments, shares)
- [ ] Sign In/Sign Up functionality works
- [ ] Mobile responsive design

### ✅ Backend
- [ ] API endpoints respond at https://yourdomain.com/api/v1/
- [ ] Database connections work
- [ ] Video interactions save correctly
- [ ] Comments system functions
- [ ] Subscriber registration works
- [ ] CORS headers configured

### ✅ Database
- [ ] All tables created
- [ ] Sample data seeded
- [ ] Video interactions table populated
- [ ] Subscribers table working

## 🛠️ Troubleshooting

### Common Issues

#### 1. 404 Errors on Page Refresh
- Ensure `.htaccess` is in place
- Check Apache mod_rewrite is enabled
- Verify file permissions

#### 2. API Connection Issues
- Check CORS configuration
- Verify API URL in frontend
- Ensure backend is accessible

#### 3. Database Connection
- Verify database credentials in `.env`
- Check database server is running
- Ensure database exists

#### 4. File Permissions
```bash
# Fix Laravel permissions
chmod -R 755 /path/to/api/
chmod -R 777 /path/to/api/storage/
chmod -R 777 /path/to/api/bootstrap/cache/
```

## 📞 Support

For deployment issues:
1. Check server error logs
2. Verify all files uploaded correctly
3. Test API endpoints individually
4. Check database connectivity

## 🎯 Features Included

### ✅ Completed Features
- **Frontend:** Vue.js SPA with modern UI
- **Backend:** Laravel API with database integration
- **Authentication:** Sign In/Sign Up system
- **Interactive Content:** Likes, comments, shares
- **Video System:** YouTube embedding with interactions
- **News System:** Real-time news integration
- **Events System:** Event management and display
- **FAQ System:** Searchable FAQ with categories
- **Responsive Design:** Mobile-first approach
- **SEO Optimized:** Meta tags and structured data

### 🔧 Technical Stack
- **Frontend:** Vue.js 3, TypeScript, Tailwind CSS
- **Backend:** Laravel 11, PHP 8.2+
- **Database:** MySQL/MariaDB
- **Build Tool:** Vite
- **Deployment:** Apache/Nginx ready

---

**Deployment Package Ready!** 🚀
Upload `stargate-ci-deployment-20251020-225826.tar.gz` to your server and follow the instructions above.
