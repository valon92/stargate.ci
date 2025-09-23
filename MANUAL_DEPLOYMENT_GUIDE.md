# 📋 Manual Deployment Guide për stargate.ci

## 🚀 **Hap pas hapi - Si të upload-osh ndryshimet:**

### **Hapi 1: Build Project-in**
```bash
# Në terminal:
cd frontend
npm run build
```

### **Hapi 2: Krijo Package**
```bash
# Krijo .tar.gz file:
tar -czf stargate-ci-update.tar.gz -C dist .
```

### **Hapi 3: Upload në cPanel**

#### **3.1 - Hyr në cPanel:**
- Shko në: https://your-namecheap-cpanel-url.com
- Login me cPanel credentials

#### **3.2 - File Manager:**
- Klik "File Manager"
- Navigate në `/public_html`

#### **3.3 - Backup (Opsionale):**
- Selekto të gjitha files
- Klik "Compress" → Create Archive
- Emri: `backup-$(date).tar.gz`

#### **3.4 - Upload New Files:**
- Klik "Upload"
- Selekto `stargate-ci-update.tar.gz`
- Prit upload të mbarojë

#### **3.5 - Extract:**
- Right-click mbi `stargate-ci-update.tar.gz`
- Klik "Extract"
- Selekto `/public_html` si destination
- Klik "Extract Files"

#### **3.6 - Cleanup:**
- Fshi `stargate-ci-update.tar.gz`
- Check website: https://stargate.ci

---

## ⚡ **Mënyra e Shpejtë (me script):**

### **Hapi 1: Build + Package**
```bash
cd frontend && npm run deploy
```

### **Hapi 2: Manual Upload**
- File i krijuar: `stargate-ci-deploy-YYYYMMDD-HHMMSS.tar.gz`
- Upload në cPanel dhe extract

---

## 🔧 **Nëse dëshiron Automatic (FTP):**

### **Setup FTP Credentials:**
```bash
# Krijo file .env në project root:
echo "FTP_SERVER=ftp.stargate.ci" > .env
echo "FTP_USER=your_username" >> .env  
echo "FTP_PASS=your_password" >> .env
```

### **Run Auto-Deploy:**
```bash
./deploy-to-namecheap.sh
```

---

## 📱 **Verifikim:**

### **Test Website:**
- Homepage: https://stargate.ci
- Search: https://stargate.ci/search  
- Mobile test: Resize browser window

### **Check Features:**
- ✅ Mobile responsiveness
- ✅ Search functionality
- ✅ API calls working
- ✅ New assets loading

---

## 🚨 **Rollback (nëse ka probleme):**

### **Restore Backup:**
1. cPanel → File Manager
2. Upload backup file
3. Extract në `/public_html`
4. Overwrite files

### **Quick Fix:**
```bash
# Re-upload old version
curl -o old-version.tar.gz "https://backup-url.com/old-version.tar.gz"
# Upload dhe extract në cPanel
```

---

## 📞 **Need Help?**

**Common Issues:**
- **Files not showing**: Clear browser cache
- **404 errors**: Check `.htaccess` file exists
- **API not working**: Check backend server status
- **Mobile issues**: Hard refresh (Ctrl+F5)

**Files që duhen të jenë në `/public_html`:**
```
index.html
assets/
favicon.ico
icon.svg
manifest.json
.htaccess
```

---

🎯 **Summary: Build → Package → Upload → Extract → Test** 🎯
