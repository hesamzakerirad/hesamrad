---
title: برگه مورد نظر پیدا نشد.
permalink: /404.html
robots: noindex,follow
---

@extends('_layouts.main')

@section('body')
    <div class="container">
        <h1>برگه مورد نظر پیدا نشد.</h1>
        <div class="mt-1 mb-1">
            <a href="{{ $page->baseUrl }}">
                <i class="fa-solid fa-arrow-right ml-05"></i>
                بازگشت به وبلاگ
            </a>
        </div>
    </div>
@endsection
