# 🚀 دليل تشغيل المشروع من الفلاشة

## 📋 المتطلبات الأساسية

قبل البدء، تأكد من توفر:

- ✅ **PHP 8.2+**
- ✅ **Composer**
- ✅ **Git** (اختياري)

### التحقق من التثبيت:

```powershell
php --version
composer --version
```

---

## 🔧 خطوات البدء (من أي جهاز)

### 1️⃣ الخطوة الأولى: انسخ المشروع على الفلاشة

```powershell
# انسخ مجلد halls-booking على الفلاشة (مثلاً F:\)
# يمكنك نسخه من أي جهاز وستجده يعمل على أي جهاز آخر
```

### 2️⃣ الخطوة الثانية: تشغيل البرنامج التشغيلي

```powershell
# افتح PowerShell وانتقل لمجلد المشروع
cd F:\halls-booking  # أو أي مسار آخر

# قم بتشغيل البرنامج التشغيلي (يعمل من أي درايف)
.\startup.ps1
```

**ماذا يفعل البرنامج التشغيلي؟**

- ✅ التحقق من ملف `.env`
- ✅ توليد `APP_KEY` إذا كان مفقوداً
- ✅ إنشاء مجلدات قاعدة البيانات والتخزين
- ✅ تنظيف الـ Cache
- ✅ تثبيت المكتبات المفقودة تلقائياً

### 3️⃣ الخطوة الثالثة: تشغيل الخادم

```powershell
php artisan serve
```

هذا سيشغل الخادم على:

- **الموقع:** http://localhost:8000
- **API Docs:** http://localhost:8000/api/documentation

---

## 📊 معلومات قاعدة البيانات

**نوع قاعدة البيانات:** SQLite
**الموقع:** `database/database.sqlite`

✅ **لا توجد متطلبات MySQL أو PostgreSQL**
✅ **قاعدة البيانات محمولة 100%** - تنتقل مع المشروع

---

## 🔄 الانتقال بين الأجهزة

### من جهاز لآخر:

1. **انسخ مجلد المشروع** (يمكن من درايف مختلف)

```powershell
# على الجهاز الثاني
Copy-Item "F:\halls-booking" -Destination "D:\halls-booking" -Recurse
```

2. **شغّل البرنامج التشغيلي**

```powershell
cd D:\halls-booking
.\startup.ps1
```

3. **شغّل الخادم**

```powershell
php artisan serve
```

---

## 🗑️ حذف البيانات بأمان

### ⚠️ تحذير مهم:

**لا تحذف هذه الملفات:**

- `database/database.sqlite` - يحتوي على البيانات
- `.env` - إعدادات التطبيق
- `vendor/` - المكتبات

### حذف آمن للقاعات:

- استخدم API endpoint: `DELETE /api/halls/{id}`
- البيانات ستُحذف بشكل آمن من قاعدة البيانات
- لا تستخدم الحذف اليدوي

### عمل نسخة احتياطية:

```powershell
# انسخ ملف قاعدة البيانات
Copy-Item "database/database.sqlite" "database/database.sqlite.backup"
```

---

## 📱 استخدام API

### تسجيل الدخول:

```powershell
curl -X POST http://localhost:8000/api/login `
  -H "Content-Type: application/json" `
  -d '{
    "email": "owner@example.com",
    "password": "password"
  }'
```

### إضافة قاعة:

```powershell
curl -X POST http://localhost:8000/api/halls `
  -H "Authorization: Bearer YOUR_TOKEN" `
  -H "Content-Type: application/json" `
  -d '{
    "name": "اسم القاعة",
    "price": 1000,
    "capacity": 100,
    "location": "الموقع"
  }'
```

---

## 🛠️ أوامر مفيدة

### تنظيف الـ Cache:

```powershell
php artisan cache:clear
php artisan config:clear
```

### عرض logs:

```powershell
php artisan tail
```

### تشغيل اختبارات:

```powershell
php artisan test
```

### إعادة بناء vendor (إذا حدثت مشاكل):

```powershell
composer install --prefer-dist --no-progress
```

### تحديث جميع المكتبات:

```powershell
composer update
```

---

## ❓ استكشاف المشاكل

### المشكلة: "PORT 8000 is already in use"

**الحل:**

```powershell
# استخدم منفذ مختلف
php artisan serve --port=8001

# أو أغلق التطبيق الذي يستخدم المنفذ
# لإيجاد التطبيق:
Get-NetTCPConnection -LocalPort 8000 | Get-Process
```

### المشكلة: "Migration table not found"

**الحل:**

```powershell
php artisan migrate --force
```

### المشكلة: "Permission denied in storage"

**الحل:**

```powershell
# تأكد من الصلاحيات
icacls storage /grant:r "%USERNAME%:F" /t
```

---

## 📞 معلومات إضافية

- **الإصدار:** Laravel 11 + SQLite
- **أنظمة التشغيل المدعومة:** Windows, Mac, Linux
- **التوافق:** PHP 8.2+, Composer

---

**✅ كل شيء جاهز الآن!**
