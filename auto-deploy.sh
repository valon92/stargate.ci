#!/bin/bash

# 🚀 Auto-Deploy Script për stargate.ci
# Përdorimi: ./auto-deploy.sh

set -e

echo "🚀 Starting auto-deployment to stargate.ci..."

# Colors për output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funksioni për log
log() {
    echo -e "${BLUE}[$(date +'%H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
    exit 1
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# 1. Kontrollo nëse jemi në directory e duhur
if [ ! -d "frontend" ]; then
    error "Frontend directory not found. Run this script from project root."
fi

# 2. Install dependencies (nëse duhet)
log "📦 Installing dependencies..."
cd frontend
npm ci --silent

# 3. Build project
log "🔨 Building project..."
npm run build

if [ $? -ne 0 ]; then
    error "Build failed!"
fi

# 4. Krijo package për deployment
log "📦 Creating deployment package..."
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
PACKAGE_NAME="stargate-ci-auto-deploy-${TIMESTAMP}.tar.gz"

tar -czf "../${PACKAGE_NAME}" -C dist .
cd ..

success "Package created: ${PACKAGE_NAME}"

# 5. Testo nëse ka git changes
if [ -n "$(git status --porcelain)" ]; then
    warning "You have uncommitted changes. Consider committing them first."
fi

# 6. Commit dhe push (optional)
read -p "🔄 Do you want to commit and push changes to GitHub? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    log "📤 Committing and pushing to GitHub..."
    git add .
    git commit -m "🚀 Auto-deploy: Build $(date +'%Y-%m-%d %H:%M:%S')" || true
    git push origin main
    success "Changes pushed to GitHub"
fi

# 7. Instruksione për upload
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
success "🎯 Deployment package ready!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "📋 Manual upload steps:"
echo "   1. Open cPanel File Manager"
echo "   2. Navigate to /public_html"
echo "   3. Upload: ${PACKAGE_NAME}"
echo "   4. Extract the archive"
echo "   5. Delete old files and the .tar.gz"
echo ""
echo "🌐 Your site will be updated at: https://stargate.ci"
echo ""

# 8. FTP Upload (nëse ka credentials)
if command -v lftp &> /dev/null; then
    read -p "🔌 Do you want to auto-upload via FTP? (y/N): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        read -p "FTP Server: " FTP_SERVER
        read -p "FTP Username: " FTP_USER
        read -s -p "FTP Password: " FTP_PASS
        echo
        
        log "📤 Uploading via FTP..."
        
        # Backup old files
        lftp -c "
        set ftp:ssl-allow no;
        open -u $FTP_USER,$FTP_PASS $FTP_SERVER;
        cd public_html;
        mirror -R --delete --verbose frontend/dist/ ./
        "
        
        if [ $? -eq 0 ]; then
            success "✅ Auto-upload completed!"
            echo "🌐 Check your site: https://stargate.ci"
        else
            error "❌ Upload failed. Try manual upload."
        fi
    fi
fi

echo ""
success "🚀 Auto-deploy process completed!"
echo ""
