#!/bin/bash

echo "🔧 Gmail App Password Configuration Fix"
echo "======================================"
echo ""

echo "❌ CURRENT PROBLEM:"
echo "You're using your regular Gmail password (Aron1234)"
echo "Gmail requires a special App Password for SMTP"
echo ""

echo "✅ SOLUTION STEPS:"
echo "1. Go to: https://myaccount.google.com/"
echo "2. Click 'Security' in the left sidebar"
echo "3. Make sure '2-Step Verification' is ENABLED"
echo "4. Click 'App passwords'"
echo "5. Select 'Mail' as the app"
echo "6. Select 'Other' and type 'Stargate.ci'"
echo "7. Copy the 16-character password (like: abcd efgh ijkl mnop)"
echo ""

read -p "Enter your Gmail App Password (16 characters, no spaces): " GMAIL_APP_PASSWORD

if [ -z "$GMAIL_APP_PASSWORD" ]; then
    echo "❌ No password entered. Exiting..."
    exit 1
fi

# Update .env file with the Gmail App Password
sed -i '' "s/MAIL_PASSWORD=Aron1234/MAIL_PASSWORD=$GMAIL_APP_PASSWORD/" .env

echo ""
echo "✅ Gmail App Password updated successfully!"
echo "📧 Testing email delivery..."

# Clear config cache
php artisan config:clear

# Test email sending
php artisan tinker --execute="
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageNotification;
use App\Models\ContactMessage;

echo '🧪 Testing Gmail SMTP with App Password...' . PHP_EOL;

\$message = new ContactMessage([
    'name' => 'Stargate.ci Test',
    'email' => 'test@stargate.ci',
    'subject' => '🎉 Gmail SMTP Test - Working!',
    'message' => 'This is a test email to verify that the Gmail App Password is working correctly. Your contact form is now ready for production!',
    'created_at' => now()
]);

try {
    Mail::to('svalon95@gmail.com')->send(new ContactMessageNotification(\$message));
    echo '✅ SUCCESS! Email sent successfully to svalon95@gmail.com' . PHP_EOL;
    echo '📬 Please check your Gmail inbox for the test email' . PHP_EOL;
    echo '🎉 Your contact form is now ready for production!' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Email failed: ' . \$e->getMessage() . PHP_EOL;
    echo 'Please double-check your Gmail App Password.' . PHP_EOL;
}
"

echo ""
echo "🎉 Configuration complete!"
echo "📧 All contact form messages will now be sent to svalon95@gmail.com"

