# 🔒 SSL Setup Guide për stargate.ci

## 🎯 HAPA PËR TË INSTALUAR SSL NË CPANEL (NAMECHEAP HOSTING)

### **1. GJEJ LET'S ENCRYPT NË CPANEL**

#### Opsioni A: AutoSSL (Më e Lehtë)
1. **Login** në cPanel (Namecheap)
2. **Gjej** "SSL/TLS" në Security section
3. **Kliko** "AutoSSL" 
4. **Enable** AutoSSL për "stargate.ci"
5. **Prit** 5-10 minuta për instalim automatik

#### Opsioni B: Let's Encrypt Manual
1. **Login** në cPanel
2. **Gjej** "Let's Encrypt SSL" ose "Free SSL"
3. **Zgjidh** "stargate.ci" dhe "www.stargate.ci"
4. **Kliko** "Issue Certificate"
5. **Prit** për përfundim

#### Opsioni C: SSL/TLS Manager
1. **Shko** në "SSL/TLS" → "SSL Certificates"
2. **Kliko** "Generate a New Certificate"
3. **Plotëso** për "stargate.ci"
4. **Generate** dhe **Install**

### **2. ALTERNATIVISHT - CLOUDFLARE SSL (FALAS)**

#### Hapat për Cloudflare:
1. **Regjistrohu** në cloudflare.com
2. **Shto** domain "stargate.ci"
3. **Ndrysho** nameservers në Afriregister.com:
   - Nga: `dns1.namecheaphosting.com`, `dns2.namecheaphosting.com`
   - Në: Cloudflare nameservers (do të të jepen)
4. **Aktivo** SSL në Cloudflare dashboard
5. **Set** SSL mode: "Flexible" ose "Full"

### **3. FORCE HTTPS REDIRECT**

#### Upload .htaccess file:
```apache
# Force HTTPS redirect
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Vue.js SPA routing
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /index.html [L]

# Security headers
Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
```

### **4. VERIFIKIMI**

#### Testo SSL:
1. **Hap** https://www.stargate.ci
2. **Kontrollo** nëse shfaq "Secure" (lock icon)
3. **Testo** https://stargate.ci
4. **Kontrollo** redirect nga HTTP → HTTPS

#### SSL Checker Tools:
- https://www.ssllabs.com/ssltest/
- https://www.whynopadlock.com/

### **5. TROUBLESHOOTING**

#### Nëse SSL nuk funksionon:
1. **Prit** 30-60 minuta për propagation
2. **Clear** browser cache
3. **Kontrollo** .htaccess syntax
4. **Kontakto** Namecheap support

#### Mixed Content Issues:
- **Sigurohu** që të gjitha assets (CSS, JS, images) përdorin HTTPS
- **Kontrollo** browser console për errors

### **6. NAMECHEAP SPECIFIC NOTES**

#### Namecheap AutoSSL:
- **Zakonisht** aktivizohet automatikisht
- **Kontrollo** në cPanel → SSL/TLS Status
- **Nëse** nuk shihet, kontakto support

#### Shared Hosting Limitations:
- **Përdor** SNI (Server Name Indication)
- **Older browsers** (pre-2013) mund të shfaqin warning
- **Dedicated IP** nuk është e nevojshme

## ✅ SUCCESS CHECKLIST

- [ ] SSL certificate i instaluar për stargate.ci
- [ ] SSL certificate i instaluar për www.stargate.ci  
- [ ] HTTPS redirect po funksionon
- [ ] Browser po shfaq "Secure" icon
- [ ] Nuk ka mixed content warnings
- [ ] SSL Labs test: A+ rating

## 🆘 NEED HELP?

Nëse ke probleme:
1. **Screenshot** nga cPanel SSL section
2. **Copy/paste** error messages
3. **Test results** nga SSL checker tools
4. **Browser** console errors (F12)
