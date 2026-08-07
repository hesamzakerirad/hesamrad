@extends('_layouts.main')

@php
    /*
     * The post chrome follows the post's own language, not the site's.
     * `main` already flips `dir` from the post's front matter, so leaving this
     * text hardcoded would drop Persian labels into a left-to-right document
     * and point both arrows the wrong way.
     *
     * Dates follow the same rule: a Persian post is dated in Jalali, everything
     * else in the Gregorian calendar the rest of the site uses.
     */
    $isRtl = $page->getDirection() === 'rtl';

    $strings = [
        'fa' => [
            'backToBlog' => 'بازگشت به وبلاگ',
            'readTime' => 'خواندن در :minutes دقیقه',
            'updatedAt' => 'آخرین بروزرسانی در',
            'copyUrl' => 'کپی آدرس',
            'copied' => 'کپی شد!',
            'nextPost' => 'نوشته بعدی:',
            'imageCredit' => 'نگاره از :link به امانت گرفته شده است.',
            'imageCreditLink' => 'اینجا',
            'date' => fn ($page) => $page->getUpdatedJalaliDate(),
        ],
        'en' => [
            'backToBlog' => 'Back to blog',
            'readTime' => ':minutes min read',
            'updatedAt' => 'Last updated',
            'copyUrl' => 'Copy URL',
            'copied' => 'Copied!',
            'nextPost' => 'Next post:',
            'imageCredit' => 'Image borrowed from :link.',
            'imageCreditLink' => 'here',
            'date' => fn ($page) => $page->getUpdatedAtDate('j F Y'),
        ],
    ];

    $t = $strings[$page->getBaseLanguage()] ?? $strings['en'];
@endphp

@section('title', $page->title)

@section('description', $page->getSummary(160))

@section('body')
    <div class="post">
        <header>
            <div class="container">
                <div class="mb-1">
                    <a href="{{ $page->baseUrl }}/blog/" rel="home" aria-label="{{ $t['backToBlog'] }}">
                        <i class="fa-solid {{ $isRtl ? 'fa-arrow-right' : 'fa-arrow-left' }} me-2"></i>
                        {{ $t['backToBlog'] }}
                    </a>
                </div>

                <h1>{{ $page->title }}</h1>
                <span>{{ str_replace(':minutes', $page->getReadTime(), $t['readTime']) }}</span>
                <span class="mx-1">-</span>
                <span>{{ $t['updatedAt'] }}
                    <time datetime="{{ $page->getUpdatedAtDate() }}">{{ $t['date']($page) }}</time>
                </span>
                <span class="mx-1">-</span>
                <span id="copy-url-btn" class="copy-url-button">
                    <span class="copy-text">
                        <i class="fa-regular fa-copy me-2"></i>
                        <span>{{ $t['copyUrl'] }}</span>
                    </span>
                    <span class="copied-text" style="display: none;">
                        <i class="fa-solid fa-check me-2"></i>
                        <span>{{ $t['copied'] }}</span>
                    </span>
                </span>
            </div>
        </header>

        @if ($page->thumbnail)
            <div class="thumbnail">
                <img src="{{ $page->thumbnail }}" alt="{{ $page->title }}">

                @if ($page->thumbnailCopyRightSource)
                    @php
                        $creditLink = '<a href="' . e($page->thumbnailCopyRightSource) . '" target="_blank" rel="noopener noreferrer">'
                            . e($t['imageCreditLink']) . '</a>';
                    @endphp

                    <small class="copyright">
                        <i class="fa-regular fa-copyright me-2"></i>
                        {!! str_replace(':link', $creditLink, e($t['imageCredit'])) !!}
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
                    <a href="{{ $next->getCanonicalUrl() }}">
                        <div class="box next" role="navigation">
                            <p>{{ $t['nextPost'] }} {{ $next->title }}</p>
                            <i class="fa-solid {{ $isRtl ? 'fa-arrow-left' : 'fa-arrow-right' }} ms-2"></i>
                        </div>
                    </a>
                </div>
            </section>
        @endif
    </div>
@endsection
