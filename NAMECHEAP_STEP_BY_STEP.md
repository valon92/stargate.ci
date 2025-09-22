# 🔒 Namecheap SSL - Hap pas Hapi

## ✅ TANI QË KE HAPUR SSL/TLS MANAGER:

### **HAPI 1: KLIKO "INSTALL AND MANAGE SSL FOR YOUR SITE (HTTPS)"**
- **Gjej** "Manage SSL sites" button
- **Kliko** për të hapur SSL installation interface

### **HAPI 2: NË INSTALL SSL INTERFACE:**
1. **Domain dropdown**: Zgjidh `stargate.ci`
2. **Certificate section**: 
   - **Opsioni A**: Kliko "Autofill by Domain" (nëse ka certificate)
   - **Opsioni B**: Kliko "Browse Certificates" (për të zgjedhur existing)
   - **Opsioni C**: Manual entry (nëse ke certificate text)

### **HAPI 3: NËSE NUK KA CERTIFICATE - KRIJONI:**
1. **Kthehu** në SSL/TLS Manager
2. **Kliko** "CERTIFICATES (CRT)"
3. **Kliko** "Generate a New Certificate"
4. **Plotëso** formularin:
   - **Domains**: `stargate.ci,www.stargate.ci` 
   - **City**: Pristina
   - **State**: Kosovo
   - **Country**: Kosovo (XK) ose Albania (AL)
   - **Company**: Stargate CI
   - **Email**: admin@stargate.ci
5. **Generate** certificate
6. **Kthehu** në "Install and Manage SSL" dhe përdore

### **HAPI 4: INSTALO CERTIFICATE:**
1. **Zgjidh** `stargate.ci` nga dropdown
2. **Certificate data** duhet të plotësohet automatikisht
3. **Kliko** "Install Certificate"
4. **Prit** për konfirmim

### **HAPI 5: VERIFIKIMI:**
1. **Hap** https://www.stargate.ci në browser
2. **Kontrollo** për green lock icon
3. **Test** https://stargate.ci

### **HAPI 6: FORCE HTTPS REDIRECT:**
1. **Upload** `.htaccess` file me HTTPS redirect
2. **Copy** content nga `.htaccess-namecheap` që krijova

## 🚨 NËSE KA PROBLEME:

### **Certificate Error:**
- **Provo** të krijosh Let's Encrypt certificate
- **Kontakto** Namecheap Support për AutoSSL

### **Domain Not Found:**
- **Sigurohu** që `stargate.ci` është addon domain
- **Refresh** DNS records

### **Installation Failed:**
- **Kontrollo** që domain përkon me certificate
- **Provo** manual certificate entry

## 📞 NAMECHEAP SUPPORT:
"Hi, I need help installing SSL certificate for stargate.ci domain. Can you enable AutoSSL or help with Let's Encrypt installation?"

## ✅ SUCCESS INDICATORS:
- [ ] Certificate installed successfully
- [ ] https://www.stargate.ci works with green lock
- [ ] https://stargate.ci works (or redirects)
- [ ] No browser security warnings
