#!/bin/bash

echo "🚀 Configuring stargate.ci domain..."

# Check if we have the production files
if [ ! -f "frontend/stargate-ci-production.tar.gz" ]; then
    echo "❌ Production files not found. Building..."
    cd frontend
    npm run build
    tar -czf stargate-ci-production.tar.gz -C dist .
    cd ..
fi

echo "✅ Production files ready: frontend/stargate-ci-production.tar.gz"
echo ""
echo "📋 Next steps for cPanel configuration:"
echo ""
echo "1. Go to cPanel → Addon Domains"
echo "2. Add stargate.ci as addon domain"
echo "3. Point to public_html/ (or create subfolder)"
echo "4. Upload frontend/stargate-ci-production.tar.gz"
echo "5. Extract in the domain's root directory"
echo "6. Delete the .tar.gz file"
echo ""
echo "🔧 Alternative: Use Subdomains"
echo "1. Go to cPanel → Subdomains"
echo "2. Create stargate.carwise.ai"
echo "3. Point to public_html/"
echo "4. Upload and extract files there"
echo ""
echo "🌐 DNS Configuration:"
echo "Point stargate.ci → 198.177.120.15"
echo ""
echo "🔒 SSL Configuration:"
echo "1. Go to cPanel → SSL/TLS"
echo "2. Install Let's Encrypt certificate for stargate.ci"
echo "3. Force HTTPS redirect"
echo ""
echo "✅ Ready for deployment!"
