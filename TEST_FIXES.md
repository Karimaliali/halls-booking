# 🧪 اختبار الإصلاحات

## المشكلة 1: البحث بالموقع

### خطوات الاختبار:

1. ✅ اذهب إلى الصفحة الرئيسية
2. ✅ اختر "طلخا - الدقهليه" من قائمة المدن
3. ✅ اضغط على زر البحث
4. ✅ تحقق من ظهور 3 قاعات

### النتيجة المتوقعة:

- يجب أن تظهر 3 قاعات في طلخا:
    - قاعة الرياضة الطلخاوية (300 شخص - 6000 ج.م)
    - قاعة الأفراح الطلخاوية (250 شخص - 4500 ج.م)
    - قاعة المؤتمرات الطلخاوية (150 شخص - 3500 ج.م)

### مؤشرات النجاح:

- URL يكون: `/search?location=طلخا%20-%20الدقهليه`
- الـ results-grid يحتوي على 3 cards
- لا يوجد رسالة خطأ في console

---

## المشكلة 2: صورة البطاقة

### خطوات الاختبار:

1. ✅ اذهب إلى صفحة تفاصيل قاعة
2. ✅ اضغط على "حجز الآن"
3. ✅ ملأ النموذج
4. ✅ في الخطوة 2 (الصور)، اختر صورة للبطاقة
5. ✅ تحقق من ظهور معاينة الصورة مباشرة

### النتيجة المتوقعة:

- معاينة الصورة تظهر في حقل البطاقة فوراً
- الصورة تكون مرئية وواضحة
- يمكنك استمرار الحجز

### مؤشرات النجاح:

- `img.preview` visible في `.file-preview`
- `.upload-area` مخفي بعد اختيار الصورة
- console بدون أخطاء

---

## فحص Docker/Browser Console

### في console يجب أن لا تظهر:

```
❌ GET /halls/search - 404
❌ TypeError: Cannot read property 'data'
❌ renderHalls is not a function
```

### يجب أن تظهر:

```
✅ GET /api/halls/search?location=... - 200
✅ Response data with halls array
✅ Cards rendered successfully
```

---

## التحقق من الملفات المعدلة

```bash
# 1. تحقق من أن API endpoint صحيح
grep -n "/api/halls/search" public/front_halls_booking/script.js

# 2. تحقق من منع form submission
grep -n "preventDefault" public/front_halls_booking/script.js

# 3. تحقق من z-index
grep -n "z-index:" public/front_halls_booking/style.css | head -10
```

---

## الملاحظات:

- تأكد من مسح cache في browser (Ctrl+Shift+Delete)
- جرب في incognito mode إذا استمرت المشاكل
- تحقق من network tab في DevTools للـ requests
