# نظام الدفع Paymob - وثائق مصر

## نظرة عامة

تم دمج بوابة **Paymob** للدفع - أكبر بوابة دفع في مصر مع دعم كامل للاسترجاع التلقائي بعد 24 ساعة.

## التثبيت والإعداد

### 1. الحصول على بيانات Paymob

اذهب إلى [Paymob Dashboard](https://checkout.paymob.com):

- قم بإنشاء حساب تاجر
- احصل على:
    - **API Key** - مفتاح الـ API الخاص بك
    - **Merchant ID** - معرف التاجر
    - **Integration ID** - معرف التطبيق/الفرع

### 2. إضافة بيانات البيئة

استخدم `.env`:

```env
PAYMOB_API_KEY=your_api_key_here
PAYMOB_MERCHANT_ID=your_merchant_id
PAYMOB_INTEGRATION_ID=your_integration_id
```

أو استخدم `.env.example` كمرجع

### 3. تشغيل Migration

```bash
php artisan migrate
```

## تدفق الدفع

```
┌─────────────────┐
│  العميل         │
└────────┬────────┘
         │
         ▼ (1) يختار قاعة والتاريخ
    ┌──────────────────┐
    │  الحجز يُنشأ      │
    │ (pending payment) │
    └────────┬─────────┘
             │
             ▼ (2) يدفع العربون (25%)
    ┌──────────────────┐
    │  Paymob Payment   │
    │  iframe/page      │
    └────────┬─────────┘
             │
             ▼ (3) الدفع ينجح
    ┌──────────────────┐
    │ Paymob Webhook   │
    │ (تأكيد تلقائي)    │
    └────────┬─────────┘
             │
      ┌──────┴──────┐
      ▼             ▼
   24 ساعة      صاحب القاعة
      │         يؤكد الحجز
      │              │
      │          (4) status='confirmed'
      │              │
      ▼              ▼
   Expired      ┌──────────────┐
   Refund       │ Payment      │
   (آلي)        │ Released ✓   │
                └──────────────┘
```

## API Endpoints

### 1. بدء عملية الدفع

```
POST /api/payments/initiate
Authorization: Bearer {token}

{
  "booking_id": 1,
  "deposit_percentage": 25
}

Response:
{
  "message": "Payment initiated",
  "booking_id": 1,
  "amount": 250,
  "currency": "EGP",
  "expires_at": "2026-04-15T12:00:00Z",
  "order_id": "12345",
  "payment_key": "ZXhhbXBsZXBheW1lbnRrZXk=",
  "iframe_url": "https://accept.paymobsolutions.com/api/acceptance/iframes/12345?payment_token=ZXhhbXBsZXBheW1lbnRrZXk="
}
```

### 2. التحقق من حالة الدفع

```
GET /api/payments/{booking_id}
Authorization: Bearer {token}

Response:
{
  "booking_id": 1,
  "payment_status": "pending|completed|refunded|failed",
  "deposit_amount": 250,
  "payment_date": "2026-04-15T10:00:00Z",
  "expires_at": "2026-04-15T12:00:00Z",
  "is_expired": false
}
```

### 3. تأكيد الحجز من صاحب القاعة

```
POST /api/bookings/{booking_id}/confirm
Authorization: Bearer {token}
Role: owner

Response:
{
  "message": "Booking confirmed and payment released to owner",
  "booking_id": 1,
  "status": "confirmed",
  "deposit_amount": 250,
  "owner_id": 5
}
```

## Webhook من Paymob

Paymob يرسل webhook تلقائياً بعد أي عملية دفع:

```
POST /api/payments/webhook/paymob

{
  "obj": {
    "status": "success",
    "order": {
      "id": 12345
    },
    "transaction": {
      "id": "5555555",
      "success": true
    }
  }
}
```

في Paymob Dashboard:

- اذهب إلى Integration Settings
- أضف webhook URL:
    ```
    https://yourapi.com/api/payments/webhook/paymob
    ```

## Cron Job للاسترجاع التلقائي

الأمر يعمل كل ساعة تلقائياً:

```bash
php artisan payments:refund-expired
```

**الوصف:**

- يبحث عن جميع الحجوزات ذات الدفعات المعلقة التي انتهت 24 ساعة
- يطلب استرجاع المبلغ من Paymob
- يحدّث حالة الحجز إلى `refunded`

### لـ Railway:

أضف في `Procfile`:

```
release: php artisan migrate
scheduler: php artisan schedule:work
```

أو استخدم Railway Services المخصصة للـ background jobs.

## حالات الدفع

| الحالة      | الوصف                             |
| ----------- | --------------------------------- |
| `pending`   | انتظار الدفع (صلاحية 24 ساعة)     |
| `completed` | تم الدفع والتأكيد من Paymob       |
| `refunded`  | تم استرجاع المبلغ (انتهت 24 ساعة) |
| `failed`    | فشل الدفع                         |
| `released`  | تم إطلاق المبلغ لصاحب القاعة      |

## أمثلة العملية

### مثال 1: العميل يدفع والمالك يأكد

```bash
# 1. العميل ينشئ حجز
curl -X POST http://yourapi.com/api/bookings \
  -H "Authorization: Bearer customer_token" \
  -H "Content-Type: application/json" \
  -d '{
    "hall_id": 1,
    "booking_date": "2026-05-01"
  }'

# الرد:
# { "id": 1, "status": "pending" }

# 2. العميل يبدأ الدفع
curl -X POST http://yourapi.com/api/payments/initiate \
  -H "Authorization: Bearer customer_token" \
  -H "Content-Type: application/json" \
  -d '{
    "booking_id": 1,
    "deposit_percentage": 25
  }'

# الرد:
# {
#   "iframe_url": "https://accept.paymobsolutions.com/api/acceptance/iframes/...",
#   "payment_key": "...",
#   "amount": 250,
#   "expires_at": "2026-04-15T12:00:00Z"
# }

# 3. العميل يتجه إلى رابط الـ iframe ويدفع
# (Paymob يدير دفع البطاقة/المحفظة)

# 4. Paymob يرسل Webhook تلقائياً
# (الحالة تتغير إلى completed)

# 5. صاحب القاعة يأكد الحجز
curl -X POST http://yourapi.com/api/bookings/1/confirm \
  -H "Authorization: Bearer owner_token"

# الرد:
# {
#   "message": "Booking confirmed and payment released to owner",
#   "status": "confirmed"
# }
```

### مثال 2: الاسترجاع التلقائي بعد 24 ساعة

```bash
# 1. في الساعة 25 من الحجز
php artisan payments:refund-expired

# الكود يعمل:
# - يبحث عن جميع الحجوزات قديمة 24 ساعة
# - يطلب استرجاع من Paymob
# - تتغير الحالة إلى refunded
```

## ملاحظات أمان

1. **API Key**: احفظه في `.env` فقط، لا تضعه في الكود
2. **Webhook Signature**: يمكن التحقق من التوقيع (موجود في الكود)
3. **HTTPS**: استخدم HTTPS في كل شيء
4. **Rate Limiting**: Paymob لديها حدود على API

## الأخطاء الشائعة

| الخطأ                          | الحل                                               |
| ------------------------------ | -------------------------------------------------- |
| `Paymob Authentication Failed` | تحقق من API_KEY و MERCHANT_ID                      |
| `Failed to create order`       | تحقق من رقم الهاتف والبريد الإلكتروني للعميل       |
| `Payment not confirmed`        | انتظر webhook من Paymob أو تحقق من الاتصال         |
| `Refund failed`                | تحقق من صلاحية المعاملة (بعض المعاملات لا تُسترجع) |

## مستندات Paymob الرسمية

- [API Documentation](https://docs.paymob.com)
- [Accept Checkout](https://docs.paymob.com/docs/accept-checkout)
- [Refunds](https://docs.paymob.com/docs/refunds)
- [Webhooks](https://docs.paymob.com/docs/webhooks)

## الدعم

للأسئلة أو المشاكل:

- Paymob Support: support@paymob.com
- مرجع Paymob الرسمي
