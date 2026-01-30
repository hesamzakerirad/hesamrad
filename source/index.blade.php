@extends('_layouts.main')

@php
    $page->title = $page->siteDescription;
@endphp

@section('body')
    <div class="landing">
        <div class="container">
            <h3>من، <a href="{{ $page->baseUrl }}">حسام راد</a>،‌ مهندس نرم‌افزار هستم.</h3>

            <p>از سال ۱۳۹۷ تا امروز مشغول ساخت و ساز در اینترنت بودم و هنوز هم در تلاش برای ساختن ابزارهای مفید در بستر وب
                هستم.
                هر از چند گاهی نوشته‌ای کوتاه درباره مهندسی‌ نرم‌افزار، کتاب‌های ارزشمند و زندگی در <a
                    href="{{ $page->baseUrl }}/blog/">وبلاگم</a> منتشر می‌کنم.
            </p>

            <p>من آگاهانه تلاش می‌کنم در شبکه‌های مجازی حضور نداشته باشم و ارتباط دیجیتالم رو به چند ابزار ساده و همیشگی
                محدود
                کنم؛‌ در صورت نیاز می‌تونید به من <a href="mailto:hesamrad@duck.com">ایمیل</a> بزنید یا از صفحه‌های من در <a
                    href="https://linkedin.com/in/hesamrad" target="_blank">لینکداین</a> یا <a
                    href="https://github.com/hesamzakerirad" target="_blank">گیت‌هاب</a> دیدن کنید.</p>
        </div>
    </div>
@endsection
