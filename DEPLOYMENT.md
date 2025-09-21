# 🚀 Stargate.ci Deployment Guide

## 📋 Server Information
- **Hosting Package**: Stellar18
- **Server Name**: server704
- **cPanel Version**: 126.0 (build 32)
- **Apache Version**: 2.4.65
- **Database**: MariaDB 10.6.23
- **Shared IP**: 198.177.120.15

## 🎯 Deployment Steps

### 1. Upload Files to cPanel

#### Option A: File Manager (Recommended)
1. Login to cPanel
2. Go to **File Manager**
3. Navigate to `public_html/` (or your domain's root directory)
4. Upload `stargate-ci-production.tar.gz`
5. Extract the archive in `public_html/`
6. Delete the `.tar.gz` file after extraction

#### Option B: FTP/SFTP
```bash
# Upload the compressed file
scp stargate-ci-production.tar.gz username@198.177.120.15:/home/username/public_html/

# SSH to server and extract
ssh username@198.177.120.15
cd public_html
tar -xzf stargate-ci-production.tar.gz
rm stargate-ci-production.tar.gz
```

### 2. File Structure After Upload
```
public_html/
├── index.html
├── .htaccess
├── assets/
│   ├── *.css
│   └── *.js
├── icon.svg
├── favicon.svg
├── manifest.json
└── sw.js
```

### 3. Domain Configuration

#### DNS Settings
Point your domain `stargate.ci` to:
- **A Record**: `198.177.120.15`
- **CNAME**: `www.stargate.ci` → `stargate.ci`

#### cPanel Domain Setup
1. Go to **Addon Domains** or **Subdomains**
2. Add `stargate.ci` pointing to `public_html/`
3. Enable SSL certificate (Let's Encrypt)

### 4. Backend API Setup (Optional)

If you want to deploy the Laravel backend:

#### Database Setup
1. Create MySQL database in cPanel
2. Import database schema (if needed)
3. Configure `.env` file with database credentials

#### PHP Configuration
- Ensure PHP 8.2+ is enabled
- Install Composer dependencies
- Set proper file permissions

### 5. Performance Optimization

#### Apache Configuration
The included `.htaccess` file provides:
- ✅ GZIP compression
- ✅ Browser caching
- ✅ Security headers
- ✅ SPA routing support

#### CDN Integration (Optional)
Consider using Cloudflare for:
- Global CDN
- SSL/TLS
- DDoS protection
- Performance optimization

## 🔧 Troubleshooting

### Common Issues

#### 1. 404 Errors on Refresh
- Ensure `.htaccess` is uploaded correctly
- Check Apache mod_rewrite is enabled
- Verify file permissions (644 for files, 755 for directories)

#### 2. Assets Not Loading
- Check file paths in browser dev tools
- Ensure all files are uploaded to correct directories
- Verify MIME types are set correctly

#### 3. SSL Issues
- Enable SSL certificate in cPanel
- Force HTTPS redirect in `.htaccess`
- Check mixed content warnings

### File Permissions
```bash
# Set correct permissions
find public_html -type f -exec chmod 644 {} \;
find public_html -type d -exec chmod 755 {} \;
chmod 644 public_html/.htaccess
```

## 📊 Monitoring

### Performance Checks
- Test page load speed with GTmetrix
- Check Core Web Vitals
- Monitor server response times

### Analytics Setup
- Google Analytics
- Search Console
- Performance monitoring

## 🔄 Updates

### Frontend Updates
1. Build new version: `npm run build`
2. Create new archive: `tar -czf stargate-ci-v2.tar.gz -C dist .`
3. Upload and extract on server
4. Clear browser cache

### Backend Updates
1. Update Laravel code
2. Run migrations if needed
3. Clear application cache
4. Restart PHP-FPM if necessary

## 📞 Support

For deployment issues:
- Check cPanel error logs
- Verify file permissions
- Test with browser dev tools
- Contact hosting support if needed

---

**Last Updated**: September 2025
**Version**: 1.0.0
