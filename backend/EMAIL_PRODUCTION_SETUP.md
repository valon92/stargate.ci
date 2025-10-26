# 📧 Email Production Setup - Stargate.ci

## 🎯 Objektivi
Konfiguro Gmail SMTP për të dërguar email-at e kontaktit direkt në `svalon95@gmail.com` kur përdoruesit dërgojnë mesazhe përmes faqes së kontaktit.

## 🚀 Hapat për Konfigurimin

### 1. Krijo Gmail App Password

1. **Shko në Google Account:**
   - Hap [myaccount.google.com](https://myaccount.google.com/)
   - Kliko "Security" në sidebar-in majtas

2. **Aktivizo 2-Step Verification:**
   - Në "Security" → "2-Step Verification"
   - Aktivizo nëse nuk është aktivizuar
   - **E rëndësishme:** Pa 2-Step Verification, nuk mund të krijosh App Password

3. **Krijo App Password:**
   - Në "Security" → "App passwords"
   - Zgjidh "Mail" si app
   - Zgjidh "Other" dhe shkruaj "Stargate.ci"
   - **Kopjo password-in 16-karakterësh** (p.sh: `abcd efgh ijkl mnop`)

### 2. Konfiguro .env File

**Opsioni A: Automatik (Rekomanduar)**
```bash
cd backend
./setup-gmail.sh
```

**Opsioni B: Manual**
```bash
cd backend
nano .env
```

Zëvendëso këtë rresht:
```
MAIL_PASSWORD=YOUR_GMAIL_APP_PASSWORD_HERE
```

Me app password-in që kriove (pa hapësira):
```
MAIL_PASSWORD=abcdefghijklmnop
```

### 3. Testo Email-in

```bash
cd backend
php artisan config:clear
php artisan tinker
```

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageNotification;
use App\Models\ContactMessage;

$message = new ContactMessage([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'subject' => 'Test Email',
    'message' => 'This is a test email',
    'created_at' => now()
]);

Mail::to('svalon95@gmail.com')->send(new ContactMessageNotification($message));
```

## ✅ Verifikimi

### Kontrollo nëse Email-i Po Dërgohet:

1. **Testo Contact Form:**
   - Shko në `http://localhost:5173/contact`
   - Plotëso formularin
   - Dërgo mesazhin

2. **Kontrollo Email-in:**
   - Hap `svalon95@gmail.com`
   - Kërko email-in e ri
   - Verifiko që përmban të gjitha detajet

### Kontrollo Log-et:

```bash
cd backend
tail -f storage/logs/laravel.log | grep -i "mail\|email"
```

## 🔧 Troubleshooting

### Problemi: "Username and Password not accepted"

**Zgjidhja:**
1. Sigurohu që 2-Step Verification është aktivizuar
2. Përdor App Password, jo password-in e rregullt të Gmail
3. Kontrollo që nuk ka hapësira në password
4. Sigurohu që email-i është `svalon95@gmail.com`

### Problemi: "Connection refused"

**Zgjidhja:**
1. Kontrollo internet connection
2. Verifiko që Gmail SMTP është i aktivizuar
3. Kontrollo firewall settings

### Problemi: "Authentication failed"

**Zgjidhja:**
1. Krijo një App Password të ri
2. Sigurohu që 2-Step Verification është aktivizuar
3. Kontrollo që përdorësh App Password, jo password-in e rregullt

## 📊 Statusi i Sistemit

- ✅ **Email Template** - Gati dhe i bukur
- ✅ **ContactController** - Dërgon email-at
- ✅ **SMTP Configuration** - Konfiguruar për Gmail
- ✅ **Error Handling** - Menaxhon gabimet
- ❌ **Gmail App Password** - Duhet të konfigurohet manualisht

## 🎉 Pas Konfigurimit

Kur përdoruesit dërgojnë mesazhe përmes faqes së kontaktit:

1. **Mesazhi ruhet** në databazë
2. **Email-i dërgohet automatikisht** në `svalon95@gmail.com`
3. **Ti merr njoftimin** menjëherë me të gjitha detajet
4. **Mund të përgjigjesh direkt** në email-in e dërguesit

## 📝 Shënime të Rëndësishme

- **Gmail App Password** është i sigurt dhe i veçantë për aplikacionin
- **Email-at do të dërgohen** vetëm pas konfigurimit të App Password
- **Sistemi është gati** për production
- **Të gjitha mesazhet** do të arrijnë në `svalon95@gmail.com`

## 🚀 Deployment

Për të deployuar në production:

1. Konfiguro Gmail App Password
2. Testo email-in lokal
3. Deploy në server
4. Verifiko që email-at po dërgohen

**Sistemi është gati për production!** 🎉

