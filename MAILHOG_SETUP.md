# إعداد MailHog للعمل مع Laravel

## 1. تثبيت MailHog

### باستخدام Docker:
```bash
docker run -d --name mailhog -p 1025:1025 -p 8025:8025 mailhog/mailhog
```

### باستخدام Go:
```bash
go install github.com/mailhog/MailHog@latest
```

## 2. إعدادات ملف .env

أضف هذه الإعدادات إلى ملف `.env`:

```env
# Mail Configuration for MailHog
MAIL_MAILER=mailhog
MAILHOG_HOST=localhost
MAILHOG_PORT=1025

# Alternative: Use SMTP with MailHog
# MAIL_MAILER=smtp
# MAIL_HOST=localhost
# MAIL_PORT=1025
# MAIL_USERNAME=null
# MAIL_PASSWORD=null
# MAIL_ENCRYPTION=null

# Mail From Address
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="اسم التطبيق"
```

## 3. الوصول إلى واجهة MailHog

بعد تشغيل MailHog، يمكنك الوصول إلى واجهة الويب على:
- http://localhost:8025

## 4. اختبار إرسال البريد

الآن يمكنك اختبار إرسال البريد من التطبيق، وسيتم استقباله في MailHog بدلاً من إرساله فعلياً.

## 5. إعادة تشغيل التطبيق

بعد تغيير الإعدادات، تأكد من:
- مسح الكاش: `php artisan config:clear`
- إعادة تشغيل الخادم إذا كان يعمل
