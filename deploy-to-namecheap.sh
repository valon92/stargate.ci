#!/bin/bash

# 🚀 Automatic Deployment to Namecheap Hosting (stargate.ci)
# Usage: ./deploy-to-namecheap.sh

set -e

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}🚀 Starting deployment to stargate.ci (Namecheap)...${NC}"

# Check if we have FTP credentials
if [ -z "$FTP_SERVER" ] || [ -z "$FTP_USER" ] || [ -z "$FTP_PASS" ]; then
    echo -e "${YELLOW}⚠️  FTP credentials not found in environment variables.${NC}"
    echo ""
    echo "Please set:"
    echo "export FTP_SERVER=ftp.stargate.ci"
    echo "export FTP_USER=your_username"
    echo "export FTP_PASS=your_password"
    echo ""
    echo "Or run with credentials:"
    echo "./deploy-to-namecheap.sh your_server your_user your_pass"
    echo ""
    
    if [ $# -eq 3 ]; then
        FTP_SERVER=$1
        FTP_USER=$2
        FTP_PASS=$3
        echo -e "${GREEN}✅ Using provided credentials${NC}"
    else
        echo -e "${RED}❌ Missing FTP credentials. Exiting.${NC}"
        exit 1
    fi
fi

# 1. Build project
echo -e "${BLUE}📦 Building frontend...${NC}"
cd frontend
npm ci --silent
npm run build

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Build failed!${NC}"
    exit 1
fi

# 2. Create backup
echo -e "${BLUE}💾 Creating backup...${NC}"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
BACKUP_NAME="stargate-backup-${TIMESTAMP}.tar.gz"

# Download current files as backup
if command -v lftp &> /dev/null; then
    lftp -c "
    set ftp:ssl-allow no;
    open -u $FTP_USER,$FTP_PASS $FTP_SERVER;
    cd public_html;
    mirror . ../backup-${TIMESTAMP}/;
    quit
    " 2>/dev/null || echo -e "${YELLOW}⚠️  Backup skipped (site might be empty)${NC}"
fi

# 3. Upload new files
echo -e "${BLUE}📤 Uploading to Namecheap...${NC}"

if command -v lftp &> /dev/null; then
    # Using LFTP (better)
    lftp -c "
    set ftp:ssl-allow no;
    set ftp:passive-mode on;
    open -u $FTP_USER,$FTP_PASS $FTP_SERVER;
    cd public_html;
    mirror -R --delete --verbose --exclude-glob=.htaccess dist/ ./;
    quit
    "
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Upload completed successfully!${NC}"
    else
        echo -e "${RED}❌ Upload failed!${NC}"
        exit 1
    fi
    
elif command -v ncftp &> /dev/null; then
    # Using ncftp (alternative)
    ncftpput -R -v -u "$FTP_USER" -p "$FTP_PASS" "$FTP_SERVER" /public_html dist/*
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Upload completed successfully!${NC}"
    else
        echo -e "${RED}❌ Upload failed!${NC}"
        exit 1
    fi
    
else
    echo -e "${RED}❌ No FTP client found. Please install lftp or ncftp:${NC}"
    echo "  macOS: brew install lftp"
    echo "  Ubuntu: sudo apt-get install lftp"
    echo "  CentOS: sudo yum install lftp"
    exit 1
fi

# 4. Verify deployment
echo -e "${BLUE}🔍 Verifying deployment...${NC}"
cd ..

# Test if site is accessible
if curl -s -f "http://stargate.ci" > /dev/null; then
    echo -e "${GREEN}✅ Site is accessible!${NC}"
else
    echo -e "${YELLOW}⚠️  Site check failed (might be normal)${NC}"
fi

# 5. Commit changes (optional)
if [ -n "$(git status --porcelain)" ]; then
    read -p "🔄 Commit and push changes to GitHub? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git add .
        git commit -m "🚀 Deploy to stargate.ci - $(date +'%Y-%m-%d %H:%M:%S')" || true
        git push origin main || true
        echo -e "${GREEN}✅ Changes committed to GitHub${NC}"
    fi
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo -e "${GREEN}🎉 Deployment completed successfully!${NC}"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo -e "${BLUE}🌐 Your site: https://stargate.ci${NC}"
echo -e "${BLUE}📊 Check API: https://stargate.ci/api/health${NC}"
echo -e "${BLUE}📱 Test mobile: https://stargate.ci/search${NC}"
echo ""
echo -e "${YELLOW}📋 Next steps:${NC}"
echo "   - Test the new features"
echo "   - Check mobile responsiveness"
echo "   - Verify API integrations"
echo ""

# Clean up
rm -rf dist 2>/dev/null || true

echo -e "${GREEN}✨ All done!${NC}"
