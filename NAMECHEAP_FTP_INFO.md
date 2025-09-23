# 🔐 Namecheap FTP Credentials Guide

## 📍 **FTP Information për stargate.ci**

### **Standard Namecheap FTP Details:**

```bash
FTP Host: ftp.stargate.ci
# OR alternative formats:
# server123.web-hosting.com
# cpanel.stargate.ci
# ftp.your-server-name.com
```

### **🔍 Si të gjesh FTP credentials:**

1. **cPanel Login**:
   - Go to: https://cpanel.namecheap.com
   - Login me hosting credentials

2. **FTP Accounts**:
   - Find: "FTP Accounts" ose "FTP Manager"
   - Create new FTP user ose use main account

3. **Information needed**:
   ```
   FTP Host: ____________
   Username: ____________  
   Password: ____________
   ```

## 🚀 **Ready-to-use Commands:**

### **Option 1: Direct FTP Deploy**
```bash
# Replace with your actual credentials:
./ftp-deploy-stargate.sh ftp.stargate.ci your_username your_password
```

### **Option 2: Interactive Deploy**
```bash
./deploy-now.sh
# Then choose option 1 and enter credentials
```

### **Option 3: Manual cPanel Upload**
```bash
./deploy-now.sh
# Then choose option 2 for step-by-step instructions
```

## 📦 **What gets deployed:**

✅ **Package**: `stargate-ci-deploy-20250922-075852.tar.gz`
✅ **Contains**:
- Mobile responsive search page
- API integrations (real backend data)
- New UI components
- Performance optimizations
- All latest features

## 🎯 **After Deployment:**

**Test these URLs:**
- 🏠 Homepage: https://stargate.ci
- 📱 Mobile Search: https://stargate.ci/search  
- 📄 About (API): https://stargate.ci/about
- ❓ FAQ (API): https://stargate.ci/faq
- 👥 Community: https://stargate.ci/community

## 🚨 **Troubleshooting:**

**FTP Connection Failed?**
- Check host format: `ftp.stargate.ci` vs `server123.web-hosting.com`
- Verify username/password
- Try cPanel File Manager instead

**Upload Success but site not updated?**
- Check if files extracted to `/public_html`
- Clear browser cache: Ctrl+F5
- Check file permissions in cPanel

---

## 🤝 **Need FTP Credentials?**

**Check these locations:**
1. **Namecheap Account**: Hosting dashboard
2. **Welcome Email**: Original hosting setup email  
3. **cPanel**: FTP Accounts section
4. **Support**: Contact Namecheap if needed

---

🎯 **Ready to deploy? Run: `./deploy-now.sh`** 🎯
