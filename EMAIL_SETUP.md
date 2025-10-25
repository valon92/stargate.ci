# Email Configuration for Stargate.ci

## Gmail SMTP Setup

Për të dërguar email-at zyrtarisht në `svalon95@gmail.com`, duhet të konfigurosh Gmail SMTP credentials.

### 1. Gmail App Password

1. Shko në [Google Account Settings](https://myaccount.google.com/)
2. Kliko "Security" në sidebar
3. Në "2-Step Verification", sigurohu që është aktivizuar
4. Kliko "App passwords" 
5. Zgjidh "Mail" si app
6. Zgjidh "Other" dhe shkruaj "Stargate.ci"
7. Kopjo password-in që të jepet (16 karaktere)

### 2. Environment Variables

Krijo një `.env` file në `backend/` directory me këto vlera:

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=svalon95@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@stargate.ci"
MAIL_FROM_NAME="Stargate.ci"
```

### 3. Test Email

Pas konfigurimit, teston me:

```bash
cd backend
php artisan tinker
```

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageNotification;
use App\Models\ContactMessage;

$message = new ContactMessage([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'subject' => 'Test Subject',
    'message' => 'Test message content',
    'created_at' => now()
]);

Mail::to('svalon95@gmail.com')->send(new ContactMessageNotification($message));
```

### 4. Production Setup

Për production, përdor një email service si:
- **Mailgun** (rekomanduar)
- **SendGrid**
- **Amazon SES**
- **Postmark**

### 5. Current Status

✅ Email template është gati  
✅ ContactController dërgon email-at  
✅ Error handling është implementuar  
❌ SMTP credentials duhen konfiguruar  

### 6. Troubleshooting

Nëse email-at nuk dërgohen:

1. Kontrollo log-et: `tail -f storage/logs/laravel.log`
2. Verifiko credentials në `.env`
3. Sigurohu që 2FA është aktivizuar në Gmail
4. Përdor App Password, jo password-in e rregullt

### 7. Alternative: Mailtrap (Development)

Për development, mund të përdorësh Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```
