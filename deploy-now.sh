#!/bin/bash

# 🚀 Quick Deploy for stargate.ci
# Interactive script for deployment

set -e

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

clear
echo -e "${BLUE}"
echo "  _____ _                        _         "
echo " / ____| |                      | |        "
echo "| (___ | |_ __ _ _ __ __ _  __ _| |_ ___   "
echo " \___ \| __/ _\` | '__/ _\` |/ _\` | __/ _ \  "
echo " ____) | || (_| | | | (_| | (_| | ||  __/  "
echo "|_____/ \__\__,_|_|  \__, |\__,_|\__\___|  "
echo "                      __/ |               "
echo "                     |___/                "
echo -e "${NC}"
echo -e "${BLUE}🚀 Deployment Script for stargate.ci${NC}"
echo "========================================"

# Check if package exists
PACKAGE="stargate-ci-deploy-20250922-075852.tar.gz"
if [ ! -f "$PACKAGE" ]; then
    echo -e "${YELLOW}📦 Creating fresh deployment package...${NC}"
    cd frontend
    npm run build
    cd ..
    PACKAGE="stargate-ci-fresh-$(date +%Y%m%d-%H%M%S).tar.gz"
    tar -czf "$PACKAGE" -C frontend/dist .
    echo -e "${GREEN}✅ Created: $PACKAGE${NC}"
fi

echo ""
echo -e "${BLUE}📋 Choose deployment method:${NC}"
echo ""
echo -e "${YELLOW}1) 🔄 Automatic FTP Deploy${NC}"
echo "   Fast, requires FTP credentials"
echo ""
echo -e "${YELLOW}2) 📁 Manual cPanel Upload${NC}"
echo "   Safe, requires manual steps"
echo ""
echo -e "${YELLOW}3) 📋 Show FTP Credentials Form${NC}"
echo "   Get ready-to-use commands"
echo ""

read -p "Select option (1-3): " choice

case $choice in
    1)
        echo ""
        echo -e "${BLUE}🔐 FTP Credentials needed:${NC}"
        echo ""
        read -p "FTP Host (default: ftp.stargate.ci): " ftp_host
        ftp_host=${ftp_host:-"ftp.stargate.ci"}
        
        read -p "FTP Username: " ftp_user
        
        echo -n "FTP Password: "
        read -s ftp_pass
        echo ""
        
        if [ -z "$ftp_user" ] || [ -z "$ftp_pass" ]; then
            echo -e "${RED}❌ Username and password required${NC}"
            exit 1
        fi
        
        echo ""
        echo -e "${YELLOW}🚀 Starting automatic deployment...${NC}"
        ./ftp-deploy-stargate.sh "$ftp_host" "$ftp_user" "$ftp_pass"
        ;;
        
    2)
        echo ""
        echo -e "${GREEN}📁 Manual cPanel Deployment Instructions:${NC}"
        echo ""
        echo -e "${YELLOW}Step 1:${NC} Copy this file to Desktop:"
        echo "   📦 $PACKAGE"
        echo ""
        echo -e "${YELLOW}Step 2:${NC} Login to cPanel:"
        echo "   🌐 https://cpanel.namecheap.com"
        echo ""
        echo -e "${YELLOW}Step 3:${NC} File Manager:"
        echo "   📁 Navigate to: /public_html"
        echo ""
        echo -e "${YELLOW}Step 4:${NC} Upload & Extract:"
        echo "   ⬆️  Upload: $PACKAGE"
        echo "   📦 Right-click → Extract"
        echo "   🗑️  Delete archive after extraction"
        echo ""
        echo -e "${YELLOW}Step 5:${NC} Test website:"
        echo "   🌐 https://stargate.ci"
        echo ""
        
        # Copy package to Desktop for easy access
        if [ -d "$HOME/Desktop" ]; then
            cp "$PACKAGE" "$HOME/Desktop/"
            echo -e "${GREEN}✅ Package copied to Desktop: $PACKAGE${NC}"
        else
            echo -e "${YELLOW}💡 Package location: $(pwd)/$PACKAGE${NC}"
        fi
        ;;
        
    3)
        echo ""
        echo -e "${BLUE}📋 FTP Deployment Commands:${NC}"
        echo ""
        echo -e "${YELLOW}For immediate use:${NC}"
        echo "  ./ftp-deploy-stargate.sh ftp.stargate.ci YOUR_USERNAME YOUR_PASSWORD"
        echo ""
        echo -e "${YELLOW}Or set environment variables:${NC}"
        echo "  export FTP_HOST=ftp.stargate.ci"
        echo "  export FTP_USER=your_username"
        echo "  export FTP_PASS=your_password"
        echo "  ./ftp-deploy-stargate.sh \$FTP_HOST \$FTP_USER \$FTP_PASS"
        echo ""
        echo -e "${BLUE}📍 Common Namecheap FTP hosts:${NC}"
        echo "  • ftp.stargate.ci"
        echo "  • ftp.yourdomain.com"
        echo "  • server-hostname.namecheaphosting.com"
        echo ""
        ;;
        
    *)
        echo -e "${RED}❌ Invalid option${NC}"
        exit 1
        ;;
esac

echo ""
echo -e "${GREEN}🎯 Deployment ready!${NC}"
echo -e "${BLUE}📝 Need help? Check: DEPLOY_TO_SERVER_NOW.md${NC}"
