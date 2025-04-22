@extends('_layouts.main')

@php
    $page->type = 'article';
@endphp

@section('body')
    <div class="post">
        <header>
            <div class="container">
                <div class="mb-1">
                    <a href="{{ $page->baseUrl }}" rel="home" aria-label="بازگشت به وبلاگ">
                        <i class="fa-solid fa-arrow-right ml-05"></i>
                        بازگشت به وبلاگ
                    </a>
                </div>

                <h1>{{ $page->title }}</h1>
                <span>خواندن در
                    <span>{{ $page->getReadTime() }} دقیقه</span>
                </span>
                <span class="ml-05 mr-05">-</span>
                <span>آخرین بروزرسانی در
                    <time datetime="{{ $page->getUpdatedAtDate() }}">{{ $page->getUpdatedJalaliDate() }}</time>
                </span>
                <span class="ml-05 mr-05">-</span>
                    <span id="copy-url-btn" class="copy-url-button">
                        <span class="copy-text">
                            <i class="fa-regular fa-copy ml-05"></i>
                            <span>کپی آدرس</span>
                        </span>
                        <span class="copied-text" style="display: none;">
                            <i class="fa-solid fa-check ml-05"></i>
                            <span>کپی شد!</span>
                        </span>
                    </span>
            </div>
        </header>

        @if ($page->thumbnail)
            <div class="thumbnail">
                <img src="{{ $page->thumbnail }}" alt="{{ $page->title }}">

                @if ($page->thumbnailCopyRightSource) 
                    <small class="copyright">
                        <i class="fa-regular fa-copyright ml-05"></i>
                        نگاره از <a href="{{ $page->thumbnailCopyRightSource }}" target="_blank">اینجا</a> به امانت گرفته شده است.
                    </small>
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
                        <a href="{{ $next->getUrlWithTrailingSlash() }}">
                            {{ $next->title }}
                            <i class="fa-solid fa-arrow-left mr-05"></i>
                        </a>
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection
