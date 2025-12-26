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

## 📝 Shënime

- Sigurohu që PHP version është 8.1 ose më i lartë
- Sigurohu që mod_rewrite është enabled në Apache
- Sigurohu që storage directory ka write permissions
