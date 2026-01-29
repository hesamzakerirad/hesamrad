<!DOCTYPE html>
<html lang="{{ $page->language }}">

@php
    $title = $page->siteName . ($page->title ? ' - ' . $page->title : '');
    $description = $page->description ?? $page->siteDescription;
    $favicon = $page->baseUrl . '/favicon.ico';
    $thumbnail = $page->thumbnail ? $page->baseUrl . $page->thumbnail : $favicon;
    $pageUrl = $page->isHomePage() ? $page->baseUrl : $page->getUrlWithTrailingSlash();
@endphp

<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-H516TJZR2S"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-H516TJZR2S');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="copyright" content="{{ $page->siteName }}">
    <meta name="language" content="{{ $page->language }}">
    <meta name="theme-color" content="#2455c3">
    <meta name="robots" content="{{ $page->getRobotsStatus() }}">
    <meta name="author" content="{{ $page->siteName }}">
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $pageUrl }}">
    <title>{{ $title }}</title>

    <script>
        (function() {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('theme', isDark ? 'dark' : 'light');
        })();
    </script>

    @if ($page->isPost($page))
        @if ($page->isTranslated)
            <link rel="alternate" hreflang="en" href="{{ $page->source }}">
        @endif
        <link rel="preload" as="image" href="{{ $thumbnail }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:type" content="{{ $page->type ?? 'website' }}">
        <meta property="og:url" content="{{ $pageUrl }}">
        <meta property="og:site_name" content="{{ $page->siteName }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:locale" content="{{ $page->locale }}">
        <meta property="og:image" content="{{ $thumbnail }}">
        <meta property="og:image:alt" content="{{ $title }}">
        {{-- <meta property="article:published_time" content="{{ $page->getCreatedAtDate() }}">
        <meta property="article:modified_time" content="{{ $page->getUpdatedAtDate() }}"> --}}
        <meta property="og:image:width" content="850">
        <meta property="og:image:height" content="470">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ $thumbnail }}">
        <meta name="twitter:image:alt" content="{{ $title }}">
        <meta name="twitter:site" content="@hesamzakerirad">
        <meta name="twitter:creator" content="@hesamzakerirad">

        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Article",
                "headline": "{{ $title }}",
                "description": "{{ $description }}",
                "author": {
                  "@type": "Person",
                  "name": "{{ $page->siteName }}",
                  "url": "{{ $page->baseUrl }}"
                },
                "publisher": {
                  "@type": "Organization",
                  "name": "{{ $page->siteName }}",
                  "url": "{{ $page->baseUrl }}",
                  "logo": {
                    "@type": "ImageObject",
                    "url": "{{ $favicon }}"
                  }
                },
                {{-- "datePublished": "{{ $page->getCreatedAtDate('Y-m-d\TH:i:s\Z') }}",
                "dateModified": "{{ $page->getUpdatedAtDate('Y-m-d\TH:i:s\Z') }}", --}}
                "image": "{{ $thumbnail }}",
                "timeRequired": "PT{{ $page->getReadTime() }}M",
                "mainEntityOfPage": {
                  "@type": "WebPage",
                  "@id": "{{ $pageUrl }}"
                }
            }
        </script>
    @endif

    <link rel="home" href="{{ $page->baseUrl }}">
    <link rel="icon" href="{{ $favicon }}">
    <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
    <link rel="stylesheet" href="{{ mix('css/highlight/github.min.css', 'assets/build') }}" id="hljs-light">
    <link rel="stylesheet" href="{{ mix('css/highlight/github-dark.min.css', 'assets/build') }}" id="hljs-dark"
        disabled>
</head>

<body>
    @if (true)
        <div class="banner">
            <p>
                رهایی نیابم سرانجام ازین | خوشا باد نوشین ایران‌زمین
            </p>
        </div>
    @endif 

    <main role="main">
        @yield('body')
    </main>

    @include('_includes.footer')

    <script src="https://kit.fontawesome.com/3aa580010a.js" crossorigin="anonymous"></script>
    <script src="{{ mix('js/main.js', 'assets/build') }}"></script>
    @stack('scripts')
</body>

</html>
