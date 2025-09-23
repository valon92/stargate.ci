# 🚀 Automatic Deployment në Namecheap Hosting

## 📋 **Informacioni që na duhet nga Namecheap:**

### 1. **FTP/SFTP Credentials**
```
FTP Server: ftp.stargate.ci (ose IP address)
Username: [cpanel_username]
Password: [cpanel_password]
Port: 21 (FTP) ose 22 (SFTP)
Directory: /public_html
```

### 2. **cPanel API Credentials** (Alternativë)
```
cPanel URL: https://stargate.ci:2083
Username: [cpanel_username]
Password: [cpanel_password]
```

## ⚡ **Mënyrat e Automatic Deployment:**

### **Opcioni 1: GitHub Actions + FTP (E Rekomanduara)**

1. **Në GitHub Repository Settings:**
   - Shko në `Settings > Secrets and Variables > Actions`
   - Shto secrets:
     - `FTP_SERVER` = ftp.stargate.ci
     - `FTP_USERNAME` = [username nga Namecheap]
     - `FTP_PASSWORD` = [password nga Namecheap]

2. **Workflow i GitHub Actions:**
   - File: `.github/workflows/deploy.yml` (tashmë e krijuar)
   - Trigger: Çdo push në `main` branch
   - Action: Build + Upload automatikisht

### **Opcioni 2: FTP Script Lokal**

```bash
# Krijo file: deploy-to-namecheap.sh
#!/bin/bash

# Build project
cd frontend && npm run build

# Upload via LFTP
lftp -c "
set ftp:ssl-allow no;
open -u $FTP_USER,$FTP_PASS $FTP_SERVER;
cd public_html;
mirror -R --delete --verbose dist/ ./
"
```

### **Opcioni 3: rsync via SSH** (Nëse Namecheap suporton SSH)

```bash
rsync -avz --delete frontend/dist/ user@stargate.ci:/public_html/
```

## 🔧 **Setup i Shpejtë:**

### 1. **Gjej FTP Credentials në Namecheap:**
   - Login në Namecheap
   - Shko në `Account > Dashboard > Manage` për domain
   - cPanel > `FTP Accounts`
   - Ose përdor main cPanel account

### 2. **Test FTP Connection:**
```bash
# Test manual
ftp ftp.stargate.ci
# Shtyp username dhe password
```

### 3. **Setup GitHub Actions:**
   - Shto secrets në GitHub
   - Push kod në main branch
   - Deployment automatik!

## 📱 **Deployment me një klik:**

```bash
# Komandat e shpejta:
./auto-deploy.sh                    # Full automated script
cd frontend && npm run deploy       # Build + package
git push origin main                # Trigger GitHub Actions
```

## 🚨 **Çfarë na duhet nga ti:**

1. **FTP Details nga Namecheap cPanel**
2. **SSH access** (nëse e kanë të aktivizuar)
3. **Domain configuration** check

## 🎯 **Rezultati:**

- **Code push** → **Automatic build** → **Upload në stargate.ci**
- **Zero manual work** pas setup-it fillestar
- **Rollback** i lehtë nëse ka probleme

---

**A mund të na jepsh FTP credentials nga Namecheap cPanel që të setupojmë automatic deployment?** 🚀
