# 🔒 Namecheap SSL Setup për stargate.ci

## 🎯 HAPA SPECIFIK PËR NAMECHEAP HOSTING

### **1. GJEJ SSL NË CPANEL (NAMECHEAP)**

#### Lokacionet e mundshme në cPanel:
1. **"Security"** section → **"SSL/TLS"**
2. **"Security"** section → **"Let's Encrypt SSL"** 
3. **"Security"** section → **"AutoSSL"**
4. **"Security"** section → **"Free SSL"**
5. **"SSL/TLS Status"** (për të parë status aktual)

### **2. METODA A: AutoSSL (MË E LEHTË)**

#### Hapat:
1. **Login** në cPanel (Namecheap)
2. **Gjej** "AutoSSL" në Security section
3. **Enable** AutoSSL për domain
4. **Përfshij** në listë: `stargate.ci` dhe `www.stargate.ci`
5. **Run AutoSSL** ose **Schedule** për automatic
6. **Prit** 5-15 minuta për instalim

#### Indikatorë të suksesit:
- ✅ Status: "AutoSSL Enabled"
- ✅ Certificate: "Active" 
- ✅ Expiry Date: 90 ditë në të ardhmen

### **3. METODA B: Manual Let's Encrypt**

#### Hapat:
1. **Shko** në "SSL/TLS" → "SSL Certificates"
2. **Kliko** "Generate a New Certificate" 
3. **Plotëso** formularin:
   - **Domains**: `stargate.ci,www.stargate.ci`
   - **Key Type**: RSA 2048-bit (default)
   - **Certificate Authority**: Let's Encrypt (nëse available)
4. **Generate Certificate**
5. **Install Certificate** automatikisht

### **4. METODA C: SSL/TLS Manager**

#### Hapat:
1. **Shko** në "SSL/TLS" → "Manage SSL Hosts"
2. **Domain**: `stargate.ci`
3. **Kliko** "Autofill by Domain" (për existing certificates)
4. **Ose** përdor "Browse Certificates" 
5. **Install Certificate**

### **5. FORCE HTTPS REDIRECT**

#### Upload .htaccess me SSL redirect:
```apache
# Force HTTPS for stargate.ci
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Vue.js routing
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /index.html [L]
```

### **6. NAMECHEAP SPECIFICS**

#### Stellar Package Features:
- ✅ **AutoSSL**: Usually included (Let's Encrypt)
- ✅ **Shared SSL**: Available on shared IP
- ✅ **SNI Support**: Modern browsers supported
- ❌ **Dedicated IP**: Not included (but not needed)

#### Known Namecheap SSL Locations:
1. **cPanel → Security → SSL/TLS**
2. **cPanel → Security → Let's Encrypt**
3. **cPanel → Advanced → SSL/TLS Status**

### **7. TROUBLESHOOTING NAMECHEAP**

#### Nëse nuk gjen SSL options:
1. **Kontakto** Namecheap Support (Live Chat)
2. **Kërko** që të aktivizojnë AutoSSL
3. **Verifikoni** që Stellar package ka SSL included

#### Common Namecheap Issues:
- **AutoSSL disabled**: Kërko activation nga support
- **Domain verification failed**: Sigurohu që DNS records janë correct
- **Mixed content**: Kontrollo që assets përdorin HTTPS

### **8. VERIFIKIMI**

#### Test SSL installation:
1. **Hap** https://www.stargate.ci
2. **Kontrollo** browser lock icon (duhet të jetë green)
3. **Test** https://stargate.ci (duhet të redirect ose work)
4. **Run** SSL checker: https://www.ssllabs.com/ssltest/

#### Expected Results:
- ✅ **Grade**: A or A+
- ✅ **Validity**: 90 days (Let's Encrypt)
- ✅ **Issuer**: Let's Encrypt Authority X3
- ✅ **SNI**: Supported

## 📞 NAMECHEAP SUPPORT

#### Nëse ke probleme:
- **Live Chat**: Në Namecheap dashboard
- **Ticket**: Submit support ticket
- **Phone**: Namecheap customer service
- **Dokumentacioni**: Namecheap knowledgebase

#### Pyetjet për support:
1. "Can you enable AutoSSL for my domain stargate.ci?"
2. "I need Let's Encrypt SSL certificate installed"
3. "SSL/TLS options are missing from my cPanel"
4. "Can you help install SSL for stargate.ci and www.stargate.ci?"

## ✅ SUCCESS CHECKLIST

- [ ] AutoSSL enabled në cPanel
- [ ] SSL certificate active për stargate.ci
- [ ] SSL certificate active për www.stargate.ci
- [ ] HTTPS redirect working (HTTP → HTTPS)
- [ ] Browser shows green lock icon
- [ ] SSL Labs test shows A/A+ grade
- [ ] No mixed content warnings

**REMEMBER**: Namecheap Stellar package usually includes free SSL via Let's Encrypt!
