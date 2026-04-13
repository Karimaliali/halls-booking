# إعداد الإشعارات (Notifications Setup)

## المشكلة الحالية

الإشعارات مُحدثة لكن في `.env` مضبوط على `MAIL_MAILER=log` وهذا يعني:

- الإيميلات تُسجل في السجل فقط ✅
- لا تُرسل إيميلات فعلية ❌

## الحل 1: استخدام Mailtrap (الأفضل والأسهل)

### الخطوات:

1. اذهب إلى [mailtrap.io](https://mailtrap.io)
2. أنشئ حساب مجاني
3. انسخ بيانات SMTP من Inbox الخاص بك:
    - MAIL_USERNAME
    - MAIL_PASSWORD

4. قم بتحديث `.env`:

```
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=YOUR_MAILTRAP_USERNAME
MAIL_PASSWORD=YOUR_MAILTRAP_PASSWORD
MAIL_FROM_ADDRESS="noreply@halls-booking.com"
MAIL_FROM_NAME="قاعات الفرح"
```

5. امسح الكاش:

```bash
php artisan cache:clear
```

## الحل 2: رؤية الإشعارات المسجلة (بدون Mailtrap)

إذا كنت لا تريد استخدام Mailtrap الآن:

- الإشعارات تُسجل في `storage/logs/laravel.log`
- يمكنك رؤية الإشعار المُرسلة من خلال تشغيل:

```bash
tail -f storage/logs/laravel.log
```

## تفاصيل الإشعارات المُرسلة الآن:

### 1. NewBookingNotification (لمالك القاعة والمسؤول)

عند تأكيد حجز جديد، يتلقى مالك القاعة والمسؤول:

- ✅ اسم القاعة
- ✅ اسم العميل
- ✅ بريد العميل
- ✅ تاريخ الحجز
- ✅ حالة الحجز
- ✅ رابط مباشر لعرض الحجز

### 2. BookingConfirmedNotification (للعميل)

عند تأكيد الدفع، يتلقى العميل:

- ✅ اسم القاعة
- ✅ تاريخ الحجز
- ✅ حالة الحجز
- ✅ رابط مباشر لعرض الحجز الخاص به

## إعدادات البريد الأخرى المتاحة:

### استخدام Gmail:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=ssl
```

### استخدام Sendgrid:

```
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your-api-key
```

---

**بعد تحديث الإعدادات، لا تنس:**

1. حفظ ملف `.env`
2. تشغيل `php artisan cache:clear`
3. إعادة الخادم (Refresh Server)
