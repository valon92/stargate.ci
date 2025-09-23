# 📤 cPanel Upload Instructions - Step by Step

## ✅ **Current Status:**
- ✅ cPanel është i hapur (via Namecheap)
- ✅ Package gati: `stargate-ci-deploy-20250922-075852.tar.gz` (Desktop)
- ✅ Authorization confirmed

---

## 🎯 **STEP-BY-STEP Upload Process:**

### **Step 1: Navigate to File Manager**
```
✅ In cPanel Dashboard → Find "File Manager"
✅ Click "File Manager" icon
✅ Wait for File Manager to load
```

### **Step 2: Go to Website Root**
```
✅ In File Manager → Navigate to "/public_html"
✅ Make sure you're in the main website directory
✅ You should see existing files like index.html, assets/, etc.
```

### **Step 3: Upload Package**
```
✅ Look for "Upload" button (usually top-right)
✅ Click "Upload" 
✅ Click "Select File" or drag & drop
✅ Select: stargate-ci-deploy-20250922-075852.tar.gz (from Desktop)
✅ Wait for upload to complete (289KB - should be fast)
```

### **Step 4: Extract Files**
```
✅ Right-click on "stargate-ci-deploy-20250922-075852.tar.gz"
✅ Select "Extract" or "Extract Files"
✅ Extraction path should be: /public_html
✅ Check "Overwrite existing files" if prompted
✅ Click "Extract Files" to confirm
```

### **Step 5: Clean Up**
```
✅ After extraction completes
✅ Right-click on "stargate-ci-deploy-20250922-075852.tar.gz"
✅ Select "Delete" 
✅ Confirm deletion
```

### **Step 6: Verify Upload**
```
✅ Refresh File Manager (F5 or refresh button)
✅ Check that files are updated
✅ Look for new timestamps on files
✅ Verify assets/ folder contains new files
```

---

## 🧪 **Test Deployment:**

### **Immediate Test:**
```
✅ Open new browser tab
✅ Go to: https://stargate.ci
✅ Hard refresh: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
```

### **Test New Features:**
```
✅ Mobile Search: https://stargate.ci/search
   (Resize browser window to mobile size)
✅ API Data: https://stargate.ci/about
   (Should show real articles from backend)
✅ FAQs: https://stargate.ci/faq
   (Should show real FAQ data)
✅ Community: https://stargate.ci/community
   (Should show real community posts)
```

---

## 🚨 **Common Issues & Solutions:**

### **Issue: Upload button not found**
**Solution:** Look for "Upload Files" or drag files directly to File Manager

### **Issue: Extract option not available**
**Solution:** Try double-clicking the .tar.gz file, or look for "Extract" in toolbar

### **Issue: "Overwrite" prompt**
**Solution:** ALWAYS select "Yes" or "Overwrite" - this updates the site

### **Issue: Site not updated after upload**
**Solution:** 
1. Clear browser cache (Ctrl+F5)
2. Check file timestamps in File Manager
3. Verify extraction was in /public_html (not subfolder)

---

## 🎉 **Expected Results:**

### **After Successful Upload:**
```
✅ stargate.ci = localhost:5173 (identical)
✅ Mobile search page fully responsive
✅ Real API data instead of mock data
✅ New search and file upload components
✅ Performance optimizations active
```

### **Key File Changes:**
```
✅ index.html (updated)
✅ assets/ folder (new optimized files)
✅ New JavaScript chunks
✅ Updated CSS files
```

---

## 📞 **Need Help?**

If you encounter any issues:
1. **Screenshot** the problem area
2. **Check** File Manager for error messages
3. **Verify** you're in /public_html directory
4. **Try** refresh browser cache

---

**🎯 Ready? Start with Step 1: Find "File Manager" in your cPanel dashboard!**

