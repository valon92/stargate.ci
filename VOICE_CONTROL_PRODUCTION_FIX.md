# 🔧 Voice Control Production Fix - API URL Configuration

## ❌ Problemi

Pas deployment në cPanel, Voice Control shfaq error:
```
Unexpected token '<', "<!DOCTYPE "... is not valid JSON
```

Kjo ndodh sepse API URL nuk është e konfiguruar saktë për production.

## ✅ Zgjidhja

### Hapi 1: Konfiguro API URL në Production

Nëse API është në një subdomain të ndryshëm (p.sh. `api.stargate.ci`), duhet të konfigurosh environment variable.

**Opsioni A: Nëse API është në subdomain të ndryshëm**

1. Krijo file `.env.production` në `frontend/` directory:
   ```bash
   cd frontend
   cat > .env.production << EOF
   VITE_API_URL=https://api.stargate.ci/api
   EOF
   ```

2. Rebuild frontend:
   ```bash
   npm run build
   ```

**Opsioni B: Nëse API është në të njëjtin domain (`/api`)**

Nuk ka nevojë për konfigurim shtesë - do të përdoret automatikisht `${window.location.origin}/api`

### Hapi 2: Verifiko API Endpoint

Kontrollo që API endpoint ekziston dhe kthen JSON:

```bash
# Test API endpoint
curl https://api.stargate.ci/api/commands/demo?locale=en-US&platform_name=stargate-ci
```

Duhet të kthejë JSON, jo HTML.

### Hapi 3: Kontrollo CORS Settings

Nëse API është në subdomain të ndryshëm, sigurohu që CORS është i konfiguruar saktë në backend:

**Backend `.env`:**
```env
CORS_ALLOWED_ORIGINS=https://stargate.ci,https://www.stargate.ci
```

**Backend `config/cors.php`:**
```php
'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '')),
```

### Hapi 4: Kontrollo .htaccess për API

Sigurohu që `.htaccess` në `api/public/` është i konfiguruar saktë:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>
```

## 🔍 Debugging

### 1. Kontrollo Console për API URL

Hap browser console dhe shiko:
```
🔧 Voice Actions API URL: https://api.stargate.ci/api
```

### 2. Test API direkt në browser

Shko te: `https://api.stargate.ci/api/commands/demo?locale=en-US&platform_name=stargate-ci`

Duhet të shohësh JSON, jo HTML.

### 3. Kontrollo Network Tab

Në browser DevTools → Network tab:
- Shiko request-in për `/api/commands/demo`
- Kontrollo Response - duhet të jetë JSON
- Nëse është HTML, API URL është e gabuar ose endpoint nuk ekziston

## 📝 Përmbledhje

1. **Nëse API është në subdomain:** Shto `VITE_API_URL` në `.env.production` dhe rebuild
2. **Nëse API është në të njëjtin domain:** Nuk ka nevojë për konfigurim shtesë
3. **Kontrollo CORS:** Sigurohu që backend lejon requests nga frontend domain
4. **Test API:** Verifiko që endpoint kthen JSON, jo HTML

## 🚀 Quick Fix për Deployment

Nëse ke deploy-uar tashmë dhe nuk dëshiron të rebuild-osh:

1. **SSH në server**
2. **Edito `index.html` në `public_html/`** dhe shto:
   ```html
   <script>
     window.VOICE_ACTIONS_API_URL = 'https://api.stargate.ci/api';
   </script>
   ```
3. **Edito `voiceActions.ts`** për të lexuar këtë variable:
   ```typescript
   const apiUrl = window.VOICE_ACTIONS_API_URL || `${window.location.origin}/api`
   ```

Ose më mirë, rebuild frontend me `.env.production` dhe re-upload.

