# نظام الدفع والعربون - وثائق

## نظرة عامة

تم إضافة نظام دفع العربون (Deposit Payment) مع آلية استرجاع تلقائية بعد 24 ساعة من عدم تأكيد صاحب القاعة للحجز.

## تدفق العملية

```
1. العميل ينشئ حجز جديد
2. العميل يدفع العربون (نسبة من سعر القاعة)
3. صاحب القاعة يؤكد أو يرفض الحجز خلال 24 ساعة
4. إذا أكد → المبلغ ينتقل لصاحب القاعة
5. إذا لم يؤكد → المبلغ يُسترجع للعميل تلقائياً
```

## API Endpoints

### 1. فتح عملية دفع (Initiate Payment)

```
POST /api/payments/initiate
Content-Type: application/json

{
  "booking_id": 1,
  "deposit_percentage": 25
}

Response:
{
  "message": "Payment initiated",
  "booking_id": 1,
  "amount": 250,
  "currency": "SAR",
  "expires_at": "2026-04-15T12:00:00Z",
  "transaction_id": "TXN_abc123"
}
```

### 2. تأكيد الدفع (Confirm Payment)

```
POST /api/payments/confirm
Content-Type: application/json

{
  "booking_id": 1,
  "transaction_id": "TXN_abc123"
}

Response:
{
  "message": "Payment confirmed",
  "booking_id": 1,
  "payment_status": "completed",
  "amount": 250
}
```

### 3. الحصول على حالة الدفع (Get Payment Status)

```
GET /api/payments/1

Response:
{
  "booking_id": 1,
  "payment_status": "pending",
  "deposit_amount": 250,
  "payment_date": null,
  "expires_at": "2026-04-15T12:00:00Z",
  "is_expired": false
}
```

### 4. تأكيد الحجز من قبل صاحب القاعة (Owner Confirms Booking)

```
POST /api/bookings/1/confirm
(يتطلب role: owner)

Response:
{
  "message": "Booking confirmed and payment released to owner",
  "booking_id": 1,
  "status": "confirmed",
  "deposit_amount": 250,
  "owner_id": 5
}
```

## حالات الدفع (Payment Statuses)

| الحالة      | الوصف                        |
| ----------- | ---------------------------- |
| `pending`   | انتظار الدفع                 |
| `completed` | تم الدفع والتأكيد            |
| `failed`    | فشل الدفع                    |
| `refunded`  | تم استرجاع المبلغ            |
| `released`  | تم إطلاق المبلغ لصاحب القاعة |

## Cron Job

الأمر التالي يتم تشغيله كل ساعة تلقائياً:

```bash
php artisan payments:refund-expired
```

**الوصف:** يبحث عن جميع الحجوزات ذات الدفعات المعلقة التي انتهت صلاحيتها (> 24 ساعة) ويسترجع المبلغ تلقائياً.

## جداول قاعدة البيانات

تم إضافة الأعمدة التالية لجدول `bookings`:

```sql
ALTER TABLE bookings ADD COLUMN (
    deposit_amount DECIMAL(10, 2) NULL,
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_date TIMESTAMP NULL,
    payment_expires_at TIMESTAMP NULL,
    payment_method VARCHAR(255) NULL,
    transaction_id VARCHAR(255) NULL
);
```

## تشغيل Migration

```bash
php artisan migrate
```

## مثال على السير

### خطوة 1: العميل ينشئ حجز

```
POST /api/bookings
{
  "hall_id": 1,
  "booking_date": "2026-05-01"
}
```

### خطوة 2: العميل يدفع العربون (25%)

```
POST /api/payments/initiate
{
  "booking_id": 1,
  "deposit_percentage": 25
}
```

### خطوة 3: نظام الدفع يعود بـ transaction_id

```
{
  "transaction_id": "TXN_12345",
  "amount": 250,
  "expires_at": "2026-04-15T12:00:00Z"
}
```

### خطوة 4: بعد الدفع الفعلي، يتم تأكيد الدفع

```
POST /api/payments/confirm
{
  "booking_id": 1,
  "transaction_id": "TXN_12345"
}
```

### خطوة 5: صاحب القاعة يأكد الحجز خلال 24 ساعة

```
POST /api/bookings/1/confirm
```

### خطوة 6 (تلقائي): بعد 24 ساعة بدون تأكيد

```
php artisan payments:refund-expired
```

- الحالة تتغير من `pending` إلى `refunded`
- يتم إرجاع المبلغ للعميل

## ملاحظات مهمة

1. **بوابة الدفع:** يمكن استبدال `gateway` بـ Stripe أو PayPal أو أي خدمة دفع أخرى
2. **Cron Job:** يجب تفعيل cron job على السيرفر حتى يعمل الاسترجاع التلقائي
3. **العملة:** النظام حالياً يستخدم SAR (ريال سعودي)
4. **الأمان:** كل عمليات الدفع تتطلب مصادقة Sanctum
