---
title: برگه مورد نظر پیدا نشد.
permalink: /404.html
robots: noindex,follow
---

@extends('_layouts.main')

@section('body')
    <div class="container">
        <header class="header">
            <div class="mb-1">
                <a href="{{ $page->baseUrl }}" rel="home" aria-label="بازگشت به وبلاگ">
                    <i class="fa-solid fa-arrow-right ml-05"></i>
                    بازگشت به وبلاگ
                </a>
            </div>
            <h1>برگه مورد نظر پیدا نشد.</h1>
            <p>می‌خوای <a href="{{ $page->baseUrl }}">لیست نوشته‌ها</a> رو ببینی؟ شاید چیزی که می‌خواستی رو اونجا پیدا کردی.
            </p>
        </header>
    </div>
@endsection
