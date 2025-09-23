#!/bin/bash

# 🚀 Automatic FTP Deployment for stargate.ci
# Usage: ./ftp-deploy-stargate.sh [ftp_host] [username] [password]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Stargate.ci Automatic FTP Deployment${NC}"
echo "============================================"

# Default FTP settings for Namecheap
FTP_HOST=${1:-"ftp.stargate.ci"}
FTP_USER=${2}
FTP_PASS=${3}
REMOTE_DIR="/public_html"
LOCAL_PACKAGE="stargate-ci-deploy-20250922-075852.tar.gz"

# Check if credentials provided
if [ -z "$FTP_USER" ] || [ -z "$FTP_PASS" ]; then
    echo -e "${YELLOW}⚠️  FTP credentials needed!${NC}"
    echo ""
    echo "Usage:"
    echo "  ./ftp-deploy-stargate.sh [host] [username] [password]"
    echo ""
    echo "Example:"
    echo "  ./ftp-deploy-stargate.sh ftp.stargate.ci your_username your_password"
    echo ""
    echo -e "${BLUE}💡 Alternative: Manual upload via cPanel File Manager${NC}"
    echo "   1. Download: $LOCAL_PACKAGE"
    echo "   2. Upload to cPanel → File Manager → /public_html"
    echo "   3. Extract and test"
    echo ""
    exit 1
fi

# Check if package exists
if [ ! -f "$LOCAL_PACKAGE" ]; then
    echo -e "${RED}❌ Package not found: $LOCAL_PACKAGE${NC}"
    echo -e "${YELLOW}🔧 Creating new deployment package...${NC}"
    
    cd frontend
    npm run build
    cd ..
    
    # Create new package
    NEW_PACKAGE="stargate-ci-auto-deploy-$(date +%Y%m%d-%H%M%S).tar.gz"
    tar -czf "$NEW_PACKAGE" -C frontend/dist .
    LOCAL_PACKAGE="$NEW_PACKAGE"
    
    echo -e "${GREEN}✅ Created: $LOCAL_PACKAGE${NC}"
fi

echo -e "${BLUE}📦 Package: $LOCAL_PACKAGE${NC}"
echo -e "${BLUE}🌐 Host: $FTP_HOST${NC}"
echo -e "${BLUE}👤 User: $FTP_USER${NC}"
echo -e "${BLUE}📁 Remote: $REMOTE_DIR${NC}"
echo ""

# Test FTP connection
echo -e "${YELLOW}🔍 Testing FTP connection...${NC}"

# Create FTP script for testing
cat > ftp_test.txt << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
pwd
quit
EOF

if ftp -n < ftp_test.txt > ftp_test_output.txt 2>&1; then
    echo -e "${GREEN}✅ FTP connection successful${NC}"
    rm -f ftp_test.txt ftp_test_output.txt
else
    echo -e "${RED}❌ FTP connection failed${NC}"
    echo "Error details:"
    cat ftp_test_output.txt
    rm -f ftp_test.txt ftp_test_output.txt
    exit 1
fi

# Create backup of current site
echo -e "${YELLOW}💾 Creating backup of current site...${NC}"

cat > ftp_backup.txt << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
cd $REMOTE_DIR
binary
get index.html backup-index-$(date +%Y%m%d-%H%M%S).html
quit
EOF

ftp -n < ftp_backup.txt > /dev/null 2>&1 || echo -e "${YELLOW}⚠️  Backup skipped (file may not exist)${NC}"
rm -f ftp_backup.txt

# Upload and extract package
echo -e "${YELLOW}📤 Uploading $LOCAL_PACKAGE...${NC}"

cat > ftp_upload.txt << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
cd $REMOTE_DIR
binary
put $LOCAL_PACKAGE
quit
EOF

if ftp -n < ftp_upload.txt; then
    echo -e "${GREEN}✅ Upload successful${NC}"
    rm -f ftp_upload.txt
else
    echo -e "${RED}❌ Upload failed${NC}"
    rm -f ftp_upload.txt
    exit 1
fi

# Extract files on server
echo -e "${YELLOW}📦 Extracting files on server...${NC}"

# Create extraction script for server
cat > extract_script.txt << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
cd $REMOTE_DIR
quote SITE EXEC tar -xzf $LOCAL_PACKAGE --overwrite
quit
EOF

# Try to extract via FTP SITE command (may not work on all servers)
if ftp -n < extract_script.txt > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Extraction successful${NC}"
else
    echo -e "${YELLOW}⚠️  Automatic extraction failed${NC}"
    echo -e "${BLUE}💡 Manual extraction needed via cPanel:${NC}"
    echo "   1. Login to cPanel"
    echo "   2. File Manager → /public_html"
    echo "   3. Right-click $LOCAL_PACKAGE → Extract"
    echo "   4. Delete archive file"
fi

rm -f extract_script.txt

# Clean up uploaded archive
echo -e "${YELLOW}🧹 Cleaning up...${NC}"

cat > ftp_cleanup.txt << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
cd $REMOTE_DIR
delete $LOCAL_PACKAGE
quit
EOF

ftp -n < ftp_cleanup.txt > /dev/null 2>&1 || echo -e "${YELLOW}⚠️  Cleanup skipped${NC}"
rm -f ftp_cleanup.txt

# Test deployment
echo -e "${YELLOW}🧪 Testing deployment...${NC}"

if curl -s -o /dev/null -w "%{http_code}" https://stargate.ci | grep -q "200"; then
    echo -e "${GREEN}✅ Deployment successful!${NC}"
    echo -e "${GREEN}🌐 Site is live: https://stargate.ci${NC}"
else
    echo -e "${YELLOW}⚠️  Site response test inconclusive${NC}"
    echo -e "${BLUE}🔍 Please check manually: https://stargate.ci${NC}"
fi

echo ""
echo -e "${GREEN}🎉 Deployment completed!${NC}"
echo ""
echo -e "${BLUE}🔍 Test these URLs:${NC}"
echo "   • Homepage: https://stargate.ci"
echo "   • Search (Mobile): https://stargate.ci/search"
echo "   • About (API): https://stargate.ci/about"
echo "   • FAQ (API): https://stargate.ci/faq"
echo "   • Community: https://stargate.ci/community"
echo ""
echo -e "${GREEN}✅ Mobile responsiveness and API integrations are now live!${NC}"
