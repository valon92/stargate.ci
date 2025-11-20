#!/bin/bash

# Script për përditësimin e Voice Actions SDK
# Përdorim: ./scripts/update-voice-sdk.sh [version|local|link]

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
FRONTEND_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

cd "$FRONTEND_DIR"

echo "🎤 Voice Actions SDK Update Script"
echo "=================================="
echo ""

# Funksion për të shfaqur help
show_help() {
    echo "Përdorim: $0 [option]"
    echo ""
    echo "Opsionet:"
    echo "  latest    - Përditëso në versionin më të ri nga npm (default)"
    echo "  version   - Shfaq versionin aktual"
    echo "  local     - Përdor version lokal (file:../voice-actions-sdk)"
    echo "  link      - Linko paketën lokale me npm link"
    echo "  unlink    - Unlinko paketën lokale"
    echo "  check     - Kontrollo statusin e SDK-së"
    echo "  help      - Shfaq këtë mesazh"
    echo ""
}

# Funksion për të kontrolluar versionin
check_version() {
    echo "📦 Versioni aktual i Voice Actions SDK:"
    npm list @valon92/voice-actions-sdk 2>/dev/null || echo "❌ Paketa nuk është instaluar"
    echo ""
}

# Funksion për të kontrolluar statusin
check_status() {
    echo "🔍 Kontrollimi i statusit të SDK-së..."
    echo ""
    
    # Kontrollo versionin
    check_version
    
    # Kontrollo nëse backend është i startuar
    echo "🌐 Kontrollimi i backend server-it..."
    if curl -s http://localhost:8000/api/v1/commands?platform_name=stargate-ci > /dev/null 2>&1; then
        echo "✅ Backend server është aktiv në http://localhost:8000"
    else
        echo "⚠️  Backend server nuk është aktiv. Starto me: cd backend && php artisan serve"
    fi
    echo ""
}

# Funksion për përditësim të versionit më të ri
update_latest() {
    echo "⬆️  Përditësimi i Voice Actions SDK në versionin më të ri..."
    echo ""
    
    # Unlink nëse është linked
    if npm list @valon92/voice-actions-sdk 2>/dev/null | grep -q "linked"; then
        echo "🔗 Unlinking versionin e vjetër..."
        npm unlink @valon92/voice-actions-sdk 2>/dev/null || true
    fi
    
    # Përditëso paketën
    npm install @valon92/voice-actions-sdk@latest
    
    echo ""
    echo "✅ Përditësimi u krye me sukses!"
    check_version
}

# Funksion për përdorim të versionit lokal
use_local() {
    echo "📁 Konfigurimi për përdorim të versionit lokal..."
    echo ""
    
    # Kontrollo nëse folder-i ekziston
    LOCAL_SDK_PATH="../voice-actions-sdk"
    if [ ! -d "$LOCAL_SDK_PATH" ]; then
        echo "❌ Folder-i $LOCAL_SDK_PATH nuk ekziston!"
        echo "   Krijoni folder-in ose specifikoni path-in e saktë."
        exit 1
    fi
    
    # Unlink nëse është linked
    if npm list @valon92/voice-actions-sdk 2>/dev/null | grep -q "linked"; then
        echo "🔗 Unlinking versionin e vjetër..."
        npm unlink @valon92/voice-actions-sdk 2>/dev/null || true
    fi
    
    # Install nga path lokal
    npm install "file:$LOCAL_SDK_PATH"
    
    echo ""
    echo "✅ Konfigurimi për version lokal u krye me sukses!"
    check_version
}

# Funksion për linking
link_sdk() {
    echo "🔗 Linking paketën lokale..."
    echo ""
    
    # Kontrollo nëse folder-i ekziston
    LOCAL_SDK_PATH="../voice-actions-sdk"
    if [ ! -d "$LOCAL_SDK_PATH" ]; then
        echo "❌ Folder-i $LOCAL_SDK_PATH nuk ekziston!"
        echo "   Krijoni folder-in ose specifikoni path-in e saktë."
        exit 1
    fi
    
    # Unlink nëse është linked
    if npm list @valon92/voice-actions-sdk 2>/dev/null | grep -q "linked"; then
        echo "🔗 Unlinking versionin e vjetër..."
        npm unlink @valon92/voice-actions-sdk 2>/dev/null || true
    fi
    
    # Linko paketën
    cd "$LOCAL_SDK_PATH"
    npm link
    cd "$FRONTEND_DIR"
    npm link @valon92/voice-actions-sdk
    
    echo ""
    echo "✅ Linking u krye me sukses!"
    check_version
}

# Funksion për unlinking
unlink_sdk() {
    echo "🔗 Unlinking paketën lokale..."
    echo ""
    
    npm unlink @valon92/voice-actions-sdk 2>/dev/null || true
    
    # Reinstall nga npm
    npm install @valon92/voice-actions-sdk@latest
    
    echo ""
    echo "✅ Unlinking u krye me sukses dhe u riinstalua versioni nga npm!"
    check_version
}

# Main logic
case "${1:-latest}" in
    latest)
        update_latest
        ;;
    version)
        check_version
        ;;
    local)
        use_local
        ;;
    link)
        link_sdk
        ;;
    unlink)
        unlink_sdk
        ;;
    check)
        check_status
        ;;
    help|--help|-h)
        show_help
        ;;
    *)
        echo "❌ Opsion i panjohur: $1"
        echo ""
        show_help
        exit 1
        ;;
esac

