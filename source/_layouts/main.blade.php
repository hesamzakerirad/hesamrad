<!DOCTYPE html>
<html lang="{{ $page->getLanguage() }}" dir="{{ $page->getDirection() }}">

@php
    // Blade's inline `@section('name', $value)` runs the value through e()
    // before storing it, so yieldContent() hands back already-escaped text.
    // Decode it once here and let each consumer escape for its own context —
    // otherwise {{ }} double-encodes and JSON-LD ships HTML entities.
    $yield = fn ($section) => html_entity_decode(trim($__env->yieldContent($section)), ENT_QUOTES, 'UTF-8');

    $titlePrefix = $page->disableTitlePrefix ? '' : $page->siteName . ' - ';
    $title = $titlePrefix . $yield('title');
    $description = $yield('description');
    $favicon = $page->baseUrl . '/favicon.ico';
    $thumbnail = $page->thumbnail ? $page->baseUrl . $page->thumbnail : null;
    $pageUrl = $page->getCanonicalUrl();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="theme-color" content="#ff4d00">
    <meta name="robots" content="{{ $page->getRobotsStatus() }}">
    <meta name="author" content="{{ $page->getAuthor() }}">
    {{-- An empty description tag is worse than none: omit rather than assert nothing. --}}
    @if ($description !== '')
        <meta name="description" content="{{ $description }}">
    @endif
    @unless ($page->disableCanonical)
        <link rel="canonical" href="{{ $pageUrl }}">
    @endunless

    <script>
        (function() {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('theme', isDark ? 'dark' : 'light');
        })();
    </script>

    <meta property="og:title" content="{{ $title }}">
    <meta property="og:type" content="{{ $page->type ?: ($page->isPost($page) ? 'article' : 'website') }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:site_name" content="{{ $page->siteName }}">
    <meta property="og:locale" content="{{ $page->getLocale() }}">
    <meta name="twitter:title" content="{{ $title }}">
    @if ($description !== '')
        <meta property="og:description" content="{{ $description }}">
        <meta name="twitter:description" content="{{ $description }}">
    @endif
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
    <link rel="alternate" type="application/rss+xml" title="{{ $page->siteName }}"
        href="{{ $page->baseUrl }}/feed.xml">
    <link rel="icon" href="{{ $favicon }}">
    @viteRefresh()
    <link rel="stylesheet" href="{{ vite('source/_assets/css/main.css') }}">

    {{-- Analytics last: nothing above it should wait on a third-party request. --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-H516TJZR2S"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-H516TJZR2S');
    </script>
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

    {{-- type="module": the dev server serves this file with its `import`s
         intact, which a classic script rejects outright. --}}
    <script type="module" src="{{ vite('source/_assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
