<!DOCTYPE html>
<html lang="{{ $page->language }}">

@php
    $titlePrefix = $page->disableTitlePrefix ? '' : $page->siteName . ' - ';
    $title = $titlePrefix . trim($__env->yieldContent('title'));
    $description = trim($__env->yieldContent('description'));
    $favicon = $page->baseUrl . '/favicon.ico';
    $thumbnail = $page->baseUrl . $page->thumbnail;
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
    <meta name="copyright" content="{{ $page->getAuthor() }}">
    <meta name="language" content="{{ $page->language }}">
    <meta name="theme-color" content="#ff4d00">
    <meta name="robots" content="{{ $page->getRobotsStatus() }}">
    <meta name="author" content="{{ $page->getAuthor() }}">
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $pageUrl }}">
    <title>{{ $title }}</title>

    <script>
        (function() {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('theme', isDark ? 'dark' : 'light');
        })();
    </script>

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:type" content="{{ $page->type ?? 'website' }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:site_name" content="{{ $page->siteName }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:locale" content="{{ $page->locale }}">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:site" content="@hesamzakerirad">
    <meta name="twitter:creator" content="@hesamzakerirad">

    @if ($page->thumbnail)
        <link rel="preload" as="image" href="{{ $thumbnail }}">
        <meta property="og:image" content="{{ $thumbnail }}">
        <meta property="og:image:alt" content="{{ $title }}">
        <meta property="og:image:width" content="850">
        <meta property="og:image:height" content="470">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $thumbnail }}">
        <meta name="twitter:image:alt" content="{{ $title }}">
    @else
        <meta name="twitter:card" content="summary">
    @endif

    @include('_includes.structured-data')

    <link rel="home" href="{{ $page->baseUrl }}">
    <link rel="icon" href="{{ $favicon }}">
    @viteRefresh()
    <link rel="stylesheet" href="{{ vite('source/_assets/css/main.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    @if (false)
        <div class="banner">
            <p>
                //
            </p>
        </div>
    @endif

    @include('_includes.header')

    <main role="main" class="container my-12">
        @yield('body')
    </main>

    @include('_includes.footer')

    <script src="{{ vite('source/_assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
