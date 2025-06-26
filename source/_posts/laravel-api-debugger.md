---
extends: _layouts.post
section: content
title: یک ابزار متن باز و سبک برای دیباگ API در لاراول
description: با استفاده از این پکیج متن باز مشکلات اجرایی API رو با سرعت بیشتری ریشه‌یابی کن.
robots: 
    - index
    - follow
created_at: 2025-06-22
updated_at: 2025-06-22
thumbnail: /assets/build/images/baby-duck-swimming.jpg
thumbnailCopyRightSource: https://unsplash.com/photos/a-duck-floating-on-top-of-a-body-of-water-aM7-IUvh_VY
readTime: 2
source: 
isTranslated: false
isFeatured: false
isPublished: true
---

هر توسعه‌دهنده‌ای که یک بار با فریم‌ورک لاراول کار کرده حتما با ابزاری مثل <a href="https://laraveldebugbar.com" target="_blank">Debugbar</a> برخورد داشته و می‌دونه که این ابزار چقدر در ریشه‌یابی و بررسی مشکل‌ها کمک‌کننده است؛ مشکلی که این ابزار داره اینه که پروژه‌ شما حتما باید دارای رابط گرافیکی باشد تا بتونید از این ابزار استفاده کنید و اگر در حال توسعه یک API هستید که هیچ رابط گرافیکی‌ای ندارد، امکان استفاده از Debugbar رو ندارید. در این نوشته می‌خوام ابزار متن بازی رو بهتون معرفی کنم که با استفاده از اون بتونید مشکلات API رو با راحتی بیشتری ریشه‌یابی یا دیباگ کنید.

### درباره پکیج

پکیج متن باز Laravel API Debugger یک پکیج خیلی ساده‌ست که با استفاده از اون می‌تونید روند دیباگ API محصول‌تون رو با سرعت بیشتری انجام بدید.
(<a href="https://github.com/hesamzakerirad/laravel-api-debugger" target="_blank">آدرس مخزن گیت‌هاب</a>)

### راهنمای نصب پکیج

کافی‌ست دستور زیر رو داخل محیط ترمنیال پروژه بزنید:

```
composer require hesamrad/laravel-api-debugger
```

بعد از نصب پکیج باید یک middlware سر راه درخواست قرار دهید؛ برای نمونه:

```php
Route::get('users', function () {
    return response()->json([
        'users' => User::get()
    ]);
})->middleware('debugger');
```

### خروجی پکیج

اگر به آدرس بالا درخواست بدید با چنین پاسخی رو به رو خواهید شد:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Demo User Number 1",
      "email": "user-1@test.com"
    },
    {
      "id": 2,
      "name": "Demo User Number 2",
      "email": "user-2@test.com"
    }
  ],
  "debugger": {
    "server": {
      "web_server": "nginx/1.27.5",
      "protocol": "HTTP/1.1",
      "remote_address": "192.168.73.1",
      "remote_port": "52697",
      "server_name": "test.local",
      "server_port": "80"
    },
    "app": {
      "environment": "local",
      "laravel_version": "12.18.0",
      "php_version": "8.3.22",
      "locale": "en"
    },
    "request": {
      "ip": "192.168.65.1",
      "uri": "/api",
      "method": "GET",
      "body": [],
      "headers": {
        "connection": [
          "keep-alive"
        ],
        "accept-encoding": [
          "gzip, deflate, br"
        ],
        "host": [
          "test.local"
        ],
        "cache-control": [
          "no-cache"
        ],
        "user-agent": [
          "PostmanRuntime/7.44.0"
        ],
        "accept": [
          "application/json"
        ]
      }
    },
    "session": {
      "authenticated": false,
      "token": null
    },
    "queries": {
      "count": 1,
      "data": [
        {
          "query": "select * from \"users\"",
          "bindings": [],
          "time": 1.17
        }
      ]
    }
  }
}
```

این پکیج اطلاعات مورد نیاز توسعه‌دهنده رو برای درخواستی که در حال پردازش هست جمع‌آوری می‌کنه و با کلید `debugger` داخل پاسخ برمی‌گردونه.

قسمت کاربردی این پکیج جایی هست که تمام Query هایی که در درخواست زده شده‌اند رو داخل پاسخ برمی‌گردونه و به توسعه‌دهنده کمک می‌کنه که مشکلات اجرایی رو بهتر ریشه‌یابی کنه.

البته دقت کنید که برای دریافت این خروجی، امکان debug برنامه شما باید فعال باشد. برای فعال‌سازی این امکان کافی‌ست به فایل env. رفته و کلید APP_DEBUG رو با مقدار true ویرایش کنید.

### متد dd اما برای API!

تابع دوست‌داشتنی `dd` رو که خاطرتون هست؟ این تابع یک مشکل بزرگ داره و اون هم اینه که از API پشتیبانی نمی‌کنه و خروجی رو خراب می‌کنه. این پکیج یک تابع بامزه برای حل این مشکل معرفی کرده به نام `jdd` که دقیقا همون کار `dd` رو می‌کنه اما برای API! (jdd کوتاه شده json die and dump هست.)

نحوه کارش هیچ فرقی با dd نداره؛ کافیه برای API استفاده بشه تا خروجی در پاسخ به شکل json برگردونده بشه.

```php
// api.php

$string = "This is awesome!";

jdd($string);
```

و خروجی به این شکل برمی‌گرده: (اطلاعات مربوط به جایی که تابع `jdd` فراخوانی شده هم نمایش داده می‌شود!)

```json
{
    "dump": [
        "This is awesome!"
    ],
    "trace": {
        "file": "/var/www/laravel-api-debugger/routes/api.php",
        "line": 14
    }
}
```

### هشدار

استفاده از این پکیج در محیط production می‌تواند خطرناک باشد و باعث نشت اطلاعات مهم محصول شما شود؛ لطفا در صورتی که تصمیم دارید از این پکیج در محیط production استفاده کنید، از خاموش بودن APP_DEBUG مطمئن شوید.