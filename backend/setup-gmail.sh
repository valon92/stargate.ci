#!/bin/bash

echo "🚀 Setting up Gmail SMTP for Stargate.ci"
echo "========================================"
echo ""

echo "📧 Gmail App Password Setup Instructions:"
echo "1. Go to: https://myaccount.google.com/"
echo "2. Click 'Security' in the left sidebar"
echo "3. Enable '2-Step Verification' if not already enabled"
echo "4. Click 'App passwords'"
echo "5. Select 'Mail' as the app"
echo "6. Select 'Other' and type 'Stargate.ci'"
echo "7. Copy the 16-character password (e.g., abcd efgh ijkl mnop)"
echo ""

read -p "Enter your Gmail App Password (16 characters): " GMAIL_PASSWORD

if [ -z "$GMAIL_PASSWORD" ]; then
    echo "❌ No password entered. Exiting..."
    exit 1
fi

# Update .env file with the Gmail password
sed -i '' "s/YOUR_GMAIL_APP_PASSWORD_HERE/$GMAIL_PASSWORD/" .env

echo ""
echo "✅ Gmail SMTP configured successfully!"
echo "📧 Emails will now be sent to: svalon95@gmail.com"
echo ""

# Test email configuration
echo "🧪 Testing email configuration..."
php artisan config:clear

# Test email sending
php artisan tinker --execute="
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageNotification;
use App\Models\ContactMessage;

echo 'Testing email delivery...' . PHP_EOL;

\$message = new ContactMessage([
    'name' => 'Stargate.ci Test',
    'email' => 'test@stargate.ci',
    'subject' => 'Email System Test',
    'message' => 'This is a test email to verify that the Stargate.ci email system is working correctly.',
    'created_at' => now()
]);

try {
    Mail::to('svalon95@gmail.com')->send(new ContactMessageNotification(\$message));
    echo '✅ Email sent successfully! Check your inbox at svalon95@gmail.com' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Email failed: ' . \$e->getMessage() . PHP_EOL;
    echo 'Please check your Gmail App Password configuration.' . PHP_EOL;
}
"

echo ""
echo "🎉 Setup complete! Your contact form will now send emails to svalon95@gmail.com"
echo "📝 All contact messages will be delivered to your personal email address"
