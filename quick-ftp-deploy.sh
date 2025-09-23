#!/bin/bash

# 🚀 Quick FTP Deploy to stargate.ci
# Auto-deployment with common Namecheap settings

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}🚀 Auto-deploying to stargate.ci hosting...${NC}"
echo ""

# Check if we need to build fresh package
PACKAGE="stargate-ci-deploy-20250922-075852.tar.gz"
if [ ! -f "$PACKAGE" ]; then
    echo -e "${YELLOW}📦 Creating fresh build...${NC}"
    cd frontend
    npm run build
    cd ..
    PACKAGE="stargate-ci-auto-$(date +%Y%m%d-%H%M%S).tar.gz"
    tar -czf "$PACKAGE" -C frontend/dist .
    echo -e "${GREEN}✅ Package created: $PACKAGE${NC}"
fi

echo -e "${BLUE}📦 Package ready: $PACKAGE${NC}"
echo ""

# Common Namecheap FTP configurations to try
declare -a FTP_HOSTS=(
    "ftp.stargate.ci"
    "cpanel.stargate.ci" 
    "server.stargate.ci"
    "ftp.your-server.com"
)

echo -e "${YELLOW}🔍 Trying common FTP configurations...${NC}"

# Try to detect FTP host automatically
for host in "${FTP_HOSTS[@]}"; do
    echo -e "${BLUE}Testing: $host${NC}"
    if timeout 5 nc -z "$host" 21 2>/dev/null; then
        echo -e "${GREEN}✅ Found FTP server: $host${NC}"
        FTP_HOST="$host"
        break
    else
        echo -e "${RED}❌ Not accessible: $host${NC}"
    fi
done

if [ -z "$FTP_HOST" ]; then
    echo -e "${RED}❌ Could not auto-detect FTP server${NC}"
    echo ""
    echo -e "${YELLOW}💡 Manual deployment needed:${NC}"
    echo ""
    echo "1. Login to cPanel: https://cpanel.namecheap.com"
    echo "2. File Manager → /public_html"
    echo "3. Upload: $PACKAGE"
    echo "4. Extract files"
    echo "5. Test: https://stargate.ci"
    echo ""
    
    # Copy to desktop for manual upload
    if [ -d "$HOME/Desktop" ]; then
        cp "$PACKAGE" "$HOME/Desktop/"
        echo -e "${GREEN}📁 Package copied to Desktop for manual upload${NC}"
    fi
    
    exit 0
fi

echo ""
echo -e "${BLUE}🔐 FTP Credentials needed for: $FTP_HOST${NC}"

# Try environment variables first
if [ -n "$STARGATE_FTP_USER" ] && [ -n "$STARGATE_FTP_PASS" ]; then
    FTP_USER="$STARGATE_FTP_USER"
    FTP_PASS="$STARGATE_FTP_PASS"
    echo -e "${GREEN}✅ Using environment credentials${NC}"
else
    # Prompt for credentials
    read -p "FTP Username: " FTP_USER
    echo -n "FTP Password: "
    read -s FTP_PASS
    echo ""
fi

if [ -z "$FTP_USER" ] || [ -z "$FTP_PASS" ]; then
    echo -e "${RED}❌ Credentials required${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}🚀 Starting deployment...${NC}"

# Test FTP connection
echo -e "${BLUE}🔍 Testing connection...${NC}"

cat > /tmp/ftp_test_$$.txt << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
pwd
quit
EOF

if ftp -n < /tmp/ftp_test_$$.txt > /tmp/ftp_output_$$.txt 2>&1; then
    echo -e "${GREEN}✅ FTP connection successful${NC}"
    rm -f /tmp/ftp_test_$$.txt /tmp/ftp_output_$$.txt
else
    echo -e "${RED}❌ FTP connection failed${NC}"
    echo "Error details:"
    cat /tmp/ftp_output_$$.txt
    rm -f /tmp/ftp_test_$$.txt /tmp/ftp_output_$$.txt
    exit 1
fi

# Upload package
echo -e "${BLUE}📤 Uploading $PACKAGE...${NC}"

cat > /tmp/ftp_upload_$$.txt << EOF
open $FTP_HOST
user $FTP_USER $FTP_PASS
cd /public_html
binary
put $PACKAGE
quit
EOF

if ftp -n < /tmp/ftp_upload_$$.txt > /tmp/ftp_upload_output_$$.txt 2>&1; then
    echo -e "${GREEN}✅ Upload completed${NC}"
    rm -f /tmp/ftp_upload_$$.txt /tmp/ftp_upload_output_$$.txt
else
    echo -e "${RED}❌ Upload failed${NC}"
    cat /tmp/ftp_upload_output_$$.txt
    rm -f /tmp/ftp_upload_$$.txt /tmp/ftp_upload_output_$$.txt
    exit 1
fi

echo ""
echo -e "${GREEN}🎉 Deployment successful!${NC}"
echo ""
echo -e "${YELLOW}📋 Next steps (via cPanel):${NC}"
echo "1. Login: https://cpanel.namecheap.com"
echo "2. File Manager → /public_html"
echo "3. Right-click $PACKAGE → Extract"
echo "4. Delete archive after extraction"
echo ""
echo -e "${GREEN}🌐 Test your site: https://stargate.ci${NC}"
echo ""
echo -e "${BLUE}✅ Features now live:${NC}"
echo "  • Mobile responsive search"
echo "  • Real API data integration"
echo "  • New UI components"
echo "  • Performance optimizations"

# Clean up temp files
rm -f /tmp/ftp_*_$$.txt
