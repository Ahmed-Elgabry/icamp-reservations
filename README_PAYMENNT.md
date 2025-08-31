# 🚀 بوابة دفع Paymennt - نظام روابط الدفع

## 📋 نظرة عامة

تم إنشاء نظام متكامل لبوابة دفع Paymennt يتيح للمستخدمين إنشاء وإدارة روابط دفع آمنة. النظام مصمم باللغة العربية ويوفر واجهة سهلة الاستخدام لإدارة المدفوعات.

## ✨ الميزات الرئيسية

### 🔗 إنشاء روابط الدفع

-   إنشاء روابط دفع فورية
-   ربط الروابط بالعملاء والطلبات
-   تحديد مبالغ دفع مخصصة
-   إعداد تواريخ انتهاء صلاحية

### 📊 إدارة المدفوعات

-   تتبع حالة الدفع في الوقت الفعلي
-   عرض تفاصيل كاملة لكل عملية دفع
-   إدارة حالات الدفع (معلق، مكتمل، ملغي، منتهي الصلاحية)
-   تحديث تلقائي للحالات عبر Webhooks

### 🛠️ أدوات متقدمة

-   إعادة إرسال روابط الدفع
-   نسخ الروابط إلى الحافظة
-   إنشاء رموز QR (قريباً)
-   إلغاء الروابط عند الحاجة

## 🏗️ البنية التقنية

### الملفات الرئيسية

```
app/
├── Models/
│   └── PaymentLink.php              # نموذج رابط الدفع
├── Controllers/
│   ├── Dashboard/PaymentLinkController.php    # كونترولر لوحة التحكم
│   └── PaymentWebhookController.php          # كونترولر Webhooks
├── Services/
│   └── PaymenntService.php          # خدمة Paymennt API
└── Console/Commands/
    └── UpdatePaymentLinkStatuses.php # أمر تحديث الحالات

database/
├── migrations/
│   └── create_payment_links_table.php        # ترحيل قاعدة البيانات
└── seeders/
    └── PaymentLinkSeeder.php                 # بيانات تجريبية

resources/views/dashboard/payment_links/
├── index.blade.php                  # صفحة القائمة
└── create.blade.php                 # صفحة الإنشاء

routes/
└── web.php                          # مسارات النظام
```

### النماذج (Models)

#### PaymentLink

```php
// العلاقات
public function order()           // الطلب المرتبط
public function customer()        // العميل
public function scopePending()    // الروابط المعلقة
public function scopePaid()       // الروابط المدفوعة
public function scopeExpired()    // الروابط منتهية الصلاحية

// الخصائص
public function isExpired()       // فحص انتهاء الصلاحية
public function isPaid()          // فحص حالة الدفع
public function getStatusBadgeAttribute() // شارة الحالة
```

### نوع المخيم (Services)

#### PaymenntService

```php
// الوظائف الرئيسية
public function createPaymentLink($data)    // إنشاء رابط دفع
public function getCheckoutStatus($id)      // الحصول على الحالة
public function cancelCheckout($id)         // إلغاء الدفع
public function resendCheckout($id)         // إعادة إرسال
```

## ⚙️ الإعداد والتثبيت

### 1. المتطلبات الأساسية

```bash
PHP >= 8.0
Laravel >= 8.0
MySQL >= 5.7
Composer
```

### 2. تثبيت الحزم

```bash
composer install
composer require simplesoftwareio/simple-qrcode  # اختياري
```

### 3. إعداد البيئة

```env
# ملف .env
PAYMENNT_API_KEY=your_api_key_here
PAYMENNT_API_SECRET=your_api_secret_here
PAYMENNT_TEST_MODE=true
PAYMENNT_WEBHOOK_SECRET=your_webhook_secret
```

### 4. تشغيل الترحيلات

```bash
php artisan migrate
php artisan db:seed --class=PaymentLinkSeeder  # بيانات تجريبية
```

### 5. إعداد Cron Jobs

```bash
# تحديث حالات الدفع كل 5 دقائق
* * * * * cd /path/to/your/project && php artisan payment-links:update-statuses >> /dev/null 2>&1
```

## 🚀 الاستخدام

### إنشاء رابط دفع جديد

1. **الانتقال للصفحة**

    ```
    Dashboard → روابط الدفع → إنشاء رابط دفع جديد
    ```

2. **ملء البيانات**

    - اختيار العميل
    - إدخال المبلغ
    - اختيار الطلب (اختياري)
    - إضافة وصف
    - تحديد تاريخ انتهاء الصلاحية

3. **إنشاء الرابط**
    - الضغط على "انشاء الرابط"
    - انتظار إنشاء الرابط
    - نسخ الرابط أو إرساله للعميل

### إدارة الروابط

#### عرض القائمة

```
Dashboard → روابط الدفع
```

#### الإجراءات المتاحة

-   **QR**: عرض رمز QR للرابط
-   **إعادة إرسال**: إرسال الرابط مرة أخرى
-   **مشاهدة ونسخ**: نسخ الرابط للحافظة
-   **تحديث الحالة**: فحص الحالة الحالية

## 🔌 تكامل Paymennt API

### نقاط النهاية المدعومة

| العملية        | النقطة النهائية              | الوصف                  |
| -------------- | ---------------------------- | ---------------------- |
| إنشاء رابط دفع | `POST /checkout/web`         | إنشاء صفحة دفع جديدة   |
| فحص الحالة     | `GET /checkout/{id}`         | الحصول على حالة الدفع  |
| إلغاء الدفع    | `POST /checkout/{id}/cancel` | إلغاء عملية الدفع      |
| إعادة الإرسال  | `POST /checkout/{id}/resend` | إعادة إرسال رابط الدفع |

### معالجة Webhooks

النظام يدعم Webhooks التالية:

-   `checkout.completed` - اكتمال الدفع
-   `checkout.cancelled` - إلغاء الدفع
-   `checkout.expired` - انتهاء صلاحية الدفع

## 📱 الواجهات

### صفحة القائمة الرئيسية

-   جدول شامل لجميع روابط الدفع
-   فلترة حسب الحالة والتاريخ
-   أزرار الإجراءات السريعة
-   عرض الحالات بألوان مميزة

### صفحة الإنشاء

-   نموذج سهل الاستخدام
-   اختيار العميل والطلب
-   إدخال المبلغ والوصف
-   تحديد تاريخ انتهاء الصلاحية

## 🔒 الأمان

### حماية البيانات

-   تشفير البيانات الحساسة
-   التحقق من صحة Webhooks
-   حماية CSRF
-   تسجيل جميع العمليات

### التحقق من الصلاحيات

-   نظام صلاحيات متكامل
-   التحقق من ملكية البيانات
-   حماية من الوصول غير المصرح

## 📊 التقارير والإحصائيات

### البيانات المتاحة

-   إجمالي روابط الدفع
-   نسبة المدفوعات المكتملة
-   متوسط قيمة الدفع
-   توزيع الحالات

### التصدير

-   تصدير البيانات بصيغة Excel
-   تقارير PDF مفصلة
-   إحصائيات زمنية

## 🛠️ الصيانة والتطوير

### الأوامر المتاحة

```bash
# تحديث حالات الدفع
php artisan payment-links:update-statuses

# إنشاء بيانات تجريبية
php artisan db:seed --class=PaymentLinkSeeder

# مسح الكاش
php artisan cache:clear
php artisan config:clear
```

### السجلات (Logs)

-   جميع العمليات مسجلة
-   أخطاء API مسجلة
-   Webhooks مسجلة
-   عمليات المستخدمين مسجلة

## 🚧 الميزات القادمة

### المرحلة التالية

-   [ ] رموز QR متقدمة
-   [ ] إشعارات SMS/Email
-   [ ] تقارير تفصيلية
-   [ ] دعم عملات متعددة
-   [ ] نظام اشتراكات
-   [ ] تطبيق موبايل

### التحسينات المخططة

-   [ ] واجهة API للمطورين
-   [ ] نظام قوالب للرسائل
-   [ ] تكامل مع أنظمة أخرى
-   [ ] تحليلات متقدمة

## 📞 الدعم والمساعدة

### الوثائق

-   [Paymennt API Documentation](https://docs.paymennt.com/api)
-   [Laravel Documentation](https://laravel.com/docs)

### التواصل

-   📧 البريد الإلكتروني: support@example.com
-   📱 الهاتف: +971500000000
-   💬 الدردشة المباشرة: متاحة 24/7

## 📄 الترخيص

هذا المشروع مرخص تحت رخصة MIT. راجع ملف `LICENSE` للتفاصيل.

## 🤝 المساهمة

نرحب بمساهماتكم! يرجى:

1. Fork المشروع
2. إنشاء فرع للميزة الجديدة
3. Commit التغييرات
4. Push للفرع
5. إنشاء Pull Request

---

**ملاحظة**: تأكد من اختبار النظام في بيئة التطوير قبل استخدامه في الإنتاج.

**آخر تحديث**: ديسمبر 2024
