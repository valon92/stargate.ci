# 📤 Manual Upload Guide për stargate.ci

## ✅ **STATUS: Gati për Manual Upload**

### **📦 Package i gatshëm:**
```
File: stargate-ci-deploy-20250922-075852.tar.gz
Location: Desktop (kopjuar automatikisht)
Size: ~289KB
Contains: Të gjitha ndryshimet e reja + mobile responsiveness + API integration
```

---

## 🎯 **STEP-BY-STEP Manual Upload:**

### **Step 1: Login në cPanel**
```
URL: https://cpanel.namecheap.com
Login: [your hosting credentials]
```

### **Step 2: File Manager**
1. ✅ Klik "File Manager"
2. ✅ Navigate to: `/public_html`
3. ✅ Verify që je në root directory i website-it

### **Step 3: Upload Package**
1. ✅ Klik "Upload" button (zakonisht lart-djathtas)
2. ✅ Select file: `stargate-ci-deploy-20250922-075852.tar.gz` (nga Desktop)
3. ✅ Wait for upload të kompletojë (289KB - shpejt)

### **Step 4: Extract Files**
1. ✅ Right-click mbi `stargate-ci-deploy-20250922-075852.tar.gz`
2. ✅ Select "Extract" ose "Extract Files"
3. ✅ Confirm extraction në `/public_html`
4. ✅ **IMPORTANT**: Select "Overwrite existing files" nëse pyet

### **Step 5: Clean Up**
1. ✅ Delete archive file: `stargate-ci-deploy-20250922-075852.tar.gz`
2. ✅ Refresh File Manager
3. ✅ Verify që files janë extracted correctly

### **Step 6: Test Website**
1. ✅ Open: https://stargate.ci
2. ✅ Test mobile search: https://stargate.ci/search
3. ✅ Check API data: https://stargate.ci/about

---

## 🔍 **Çfarë duhet të shohësh pas deployment:**

### **✅ New Features Live:**
- 📱 **Mobile responsive search page**
- 🔌 **Real API data** (Articles, FAQs, Community) 
- 🎨 **New UI components** (Search, File Upload)
- ⚡ **Performance optimizations**
- 🔗 **Backend connectivity**

### **✅ Test URLs:**
```
Homepage:     https://stargate.ci
Mobile Search: https://stargate.ci/search (test on phone)
About (API):   https://stargate.ci/about (real articles)
FAQ (API):     https://stargate.ci/faq (real FAQs)
Community:     https://stargate.ci/community (real posts)
```

### **✅ Browser Cache:**
- Press `Ctrl+F5` (Windows) ose `Cmd+Shift+R` (Mac) për hard refresh
- Clear browser cache nëse nuk sheh ndryshimet

---

## 🚨 **Troubleshooting:**

### **Problem: Website nuk ndryshoi**
**Solution:**
1. Check nëse files u extract në `/public_html` (jo në subfolder)
2. Clear browser cache: `Ctrl+F5`
3. Check file permissions në cPanel (duhet 644 për files, 755 për folders)

### **Problem: Error 500**
**Solution:**
1. Check `.htaccess` file është correct
2. File permissions: 644 për `.htaccess`
3. Contact Namecheap support nëse vazhdon

### **Problem: Files nuk u upload**
**Solution:**
1. Check available disk space në cPanel
2. Try një browser tjetër
3. Re-download package nga Desktop

---

## 📞 **Support:**

### **Namecheap Support:**
- **URL**: https://www.namecheap.com/support/
- **Live Chat**: Available 24/7
- **Phone**: Check support page for number

### **Quick Help:**
- **File Manager Tutorial**: Available në cPanel help section
- **Video Guides**: Namecheap YouTube channel

---

## 🎉 **Rezultati i Pritshëm:**

Pas successful deployment:
```
✅ stargate.ci = localhost:5173 (identical features)
✅ Mobile search plotësisht responsive  
✅ API integration working (real backend data)
✅ New components dhe optimizations live
✅ Performance improvements active
```

---

🎯 **Ready? Shko tek Desktop, gjej `stargate-ci-deploy-20250922-075852.tar.gz` dhe fillo upload-in!** 🎯

