@extends('_layouts.main')

@php
    $page->type = 'article';
@endphp

@section('body')
    <div class="post">
        <header>
            <div class="container">
                <div class="mb-1">
                    <a href="{{ $page->baseUrl }}">
                        <i class="fa-solid fa-arrow-right ml-05"></i>
                        بازگشت به وبلاگ
                    </a>
                </div>

                <h1>{{ $page->title }}</h1>
                <span>خواندن در
                    <span>{{ $page->getReadTime() }} دقیقه</span>
                </span>
                <span>-</span>
                <span>آخرین بروزرسانی در
                    <time>{{ $page->getUpdatedJalaliDate() }}</time>
                </span>
            </div>
        </header>

        @if ($page->thumbnail)
            <div class="thumbnail">
                <img src="{{ $page->thumbnail }}">

                @if ($page->thumbnailCopyRightSource) 
                    <p class="copyright">
                        <i class="fa-regular fa-copyright"></i>
                        نگاره از <a href="{{ $page->thumbnailCopyRightSource }}" target="_blank">اینجا</a> به امانت گرفته شده است.
                    </p>
                @endif
            </div>
        @endif

        <article>
            <div class="container">
                @yield('content')
            </div>
        </article>

        @if ($next = $page->getNext())
            <section>
                <div class="container">
                    <div class="next" role="navigation">
                        <span>نوشته بعدی:</span>
                        <a href="{{ $next->getUrl() }}">
                            {{ $next->title }}
                            <i class="fa-solid fa-arrow-left mr-05"></i>
                        </a>
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection
