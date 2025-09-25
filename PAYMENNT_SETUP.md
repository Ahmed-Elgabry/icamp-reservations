# 🚀 إعداد بوابة دفع Paymennt

## 📋 المتطلبات الأساسية

1. **حساب Paymennt** - احصل على API Key و API Secret
2. **Laravel 8+** - إصدار Laravel المطلوب
3. **PHP 8.0+** - إصدار PHP المطلوب

## ⚙️ خطوات الإعداد

### 1. إضافة متغيرات البيئة

أضف هذه المتغيرات إلى ملف `.env`:

```env
# Paymennt Payment Gateway Configuration
PAYMENNT_API_KEY=your_paymennt_api_key_here
PAYMENNT_API_SECRET=your_paymennt_api_secret_here
PAYMENNT_TEST_MODE=true
PAYMENNT_WEBHOOK_SECRET=your_webhook_secret_here
```

### 2. تشغيل الترحيلات

```bash
php artisan migrate
```

### 3. تثبيت حزمة QR Code (اختياري)

```bash
composer require simplesoftwareio/simple-qrcode
```

### 4. إضافة القائمة إلى Sidebar

أضف هذا الكود إلى ملف `resources/views/dashboard/layouts/sidebar.blade.php`:

```php
<!-- Payment Links -->
<li class="nav-item">
    <a href="{{ route('bookings.payment-links.index') }}" class="nav-link {{ isActiveRoute('payment-links.*') }}">
        <span class="nav-icon">
            <i class="fa fa-link"></i>
        </span>
        <span class="nav-title">روابط الدفع</span>
    </a>
</li>
```

## 🔧 الميزات المتاحة

### ✅ الميزات الأساسية
- [x] إنشاء روابط دفع
- [x] إدارة العملاء والطلبات
- [x] تتبع حالة الدفع
- [x] إعادة إرسال الروابط
- [x] إلغاء الروابط
- [x] نسخ الروابط

### 🚧 الميزات القادمة
- [ ] QR Code للروابط
- [ ] Webhooks للتحديث التلقائي
- [ ] تقارير مفصلة
- [ ] إشعارات SMS/Email

## 📱 استخدام النظام

### إنشاء رابط دفع جديد

1. اذهب إلى **روابط الدفع** → **إنشاء رابط دفع جديد**
2. اختر العميل
3. أدخل المبلغ
4. اختر الطلب (اختياري)
5. اضغط **+ انشاء الرابط**

### إدارة الروابط

- **QR**: عرض رمز QR للرابط
- **إعادة إرسال**: إعادة إرسال الرابط للعميل
- **مشاهدة ونسخ**: نسخ الرابط إلى الحافظة

## 🔒 الأمان

- جميع الطلبات محمية بـ CSRF Token
- التحقق من الصلاحيات
- تشفير البيانات الحساسة
- تسجيل جميع العمليات

## 📞 الدعم

للمساعدة أو الاستفسارات:
- 📧 البريد الإلكتروني: support@example.com
- 📱 الهاتف: +971500000000

## 📚 الوثائق

- [Paymennt API Documentation](https://docs.paymennt.com/api)
- [Laravel Documentation](https://laravel.com/docs)

---

**ملاحظة**: تأكد من اختبار النظام في بيئة التطوير قبل استخدامه في الإنتاج.
