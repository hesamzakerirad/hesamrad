<!DOCTYPE html>
<html lang="{{ $page->getLanguage() }}" dir="{{ $page->getDirection() }}">

@php
    // Blade's inline `@section('name', $value)` runs the value through e()
    // before storing it, so yieldContent() hands back already-escaped text.
    // Decode it once here and let each consumer escape for its own context —
    // otherwise {{ }} double-encodes and JSON-LD ships HTML entities.
    $yield = fn ($section) => html_entity_decode(trim($__env->yieldContent($section)), ENT_QUOTES, 'UTF-8');

    /*
     * Page first, brand last. Google shows roughly 60 characters, and leading
     * every title with "Hesam Rad - " spent twelve of them before saying
     * anything — three of the posts were truncated with their distinctive words
     * cut off, which is the half a searcher scans for.
     *
     * The suffix is dropped when the page title already contains the site name,
     * so the About page reads "About — Hesam Rad" rather than
     * "Hesam Rad - About Hesam Rad".
     *
     * It is also dropped when appending it would push the title past that 60
     * characters. Three post titles are 58-61 characters on their own, and the
     * suffix took them to 71-74 — so the brand was not being read either, and
     * it was taking the end of the sentence down with it. Between a title that
     * fits and a brand nobody sees, the brand is what goes: the domain is
     * already on the result line above it.
     */
    $pageTitle = $yield('title');
    $brandSuffix = ' — ' . $page->siteName;
    $titleLimit = 60;
    $needsBrand = ! $page->disableTitlePrefix
        && stripos($pageTitle, $page->siteName) === false
        && mb_strlen($pageTitle . $brandSuffix) <= $titleLimit;
    $title = $needsBrand ? $pageTitle . $brandSuffix : $pageTitle;
    $description = $yield('description');
    $favicon = $page->baseUrl . '/favicon.ico';
    /*
     * Every share needs a card. A post uses its own thumbnail; everything else
     * falls back to the default one, because a link with no image renders as a
     * bare text stub on LinkedIn, WhatsApp and X — which is precisely where
     * this site gets shared.
     */
    /*
     * Only prefix a path that needs it. `thumbnail:` in front matter takes a
     * site-relative path or a full URL, and blindly concatenating produced
     * "https://hesamrad.comhttps://images.unsplash.com/..." — wrong in
     * og:image, twitter:image, the preload hint and the BlogPosting node,
     * while the visible <img> stayed right because it uses the raw value. The
     * page looked correct and every share of it was broken.
     */
    $absoluteUrl = fn ($path) => preg_match('#^(https?:)?//#i', $path)
        ? $path
        : rtrim($page->baseUrl, '/') . '/' . ltrim($path, '/');

    $shareImage = $absoluteUrl($page->thumbnail ?: '/assets/build/images/og-default.png');
    $thumbnail = $page->thumbnail ? $absoluteUrl($page->thumbnail) : null;
    $pageUrl = $page->getCanonicalUrl();
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    {{-- No `theme-color`. It can only key off `prefers-color-scheme`, and this
         site picks its theme from an attribute the visitor can toggle — so the
         moment somebody chooses light on a dark-mode phone the address bar
         contradicts the page underneath it. `color-scheme` in tokens.css tells
         the browser what it needs without being able to lie. --}}
    <meta name="robots" content="{{ $page->getRobotsStatus() }}">
    <meta name="author" content="{{ $page->getAuthor() }}">
    {{-- An empty description tag is worse than none: omit rather than assert nothing. --}}
    @if ($description !== '')
        <meta name="description" content="{{ $description }}">
    @endif
    @unless ($page->disableCanonical)
        <link rel="canonical" href="{{ $pageUrl }}">
    @endunless

    {{-- Runs before the stylesheet is parsed so the correct palette is in
         place at first paint; deferring this to main.js flashes the wrong
         theme on every navigation. A stored choice beats the OS setting. --}}
    <script>
        (function() {
            var theme;
            try {
                theme = localStorage.getItem('theme');
            } catch (error) {
                // Private-mode Safari throws on read as well as write.
            }
            if (theme !== 'dark' && theme !== 'light') {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('theme', theme);
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

    {{-- A post's own thumbnail is worth preloading: it is the largest thing
         above the fold on that page. The default card is never rendered on
         the page itself, so preloading it would cost a request for nothing. --}}
    @if ($page->thumbnail)
        <link rel="preload" as="image" href="{{ $thumbnail }}">
    @endif

    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:alt" content="{{ $title }}">
    <meta property="og:image:width" content="{{ $page->thumbnail ? 850 : 1200 }}">
    <meta property="og:image:height" content="{{ $page->thumbnail ? 470 : 630 }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $shareImage }}">
    <meta name="twitter:image:alt" content="{{ $title }}">

    @include('_includes.structured-data')

    <link rel="home" href="{{ $page->baseUrl }}">
    <link rel="alternate" type="application/rss+xml" title="{{ $page->siteName }}"
        href="{{ $page->baseUrl }}/feed.xml">
    <link rel="icon" href="{{ $favicon }}">
    @viteRefresh()
    {{-- Inter's upright face only. It sets every word above the fold, so it is
         the one request worth racing the stylesheet; the italic and the mono
         are rare enough that preloading them would just compete with the text
         a visitor is waiting to read. `vite()` resolves any manifest key, not
         only entry points, and throws on a miss — so a renamed or deleted font
         fails the build loudly rather than silently 404ing in production. --}}
    <link rel="preload" as="font" type="font/woff2" crossorigin
        href="{{ vite('source/_assets/fonts/Inter/Inter-Variable.woff2') }}">
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
    <a class="skip-link" href="#main">Skip to content</a>

    @include('_includes.header')

    {{-- `tabindex="-1"` is what makes the skip link above work. Jumping to a
         fragment moves the scroll position, but only a focusable target moves
         focus with it — without this, Safari in particular leaves the keyboard
         exactly where it was and the next Tab goes back into the nav the
         visitor was trying to skip. --}}
    <main id="main" class="page-main" tabindex="-1">
        @yield('body')
    </main>

    @include('_includes.footer')

    {{-- type="module": the dev server serves this file with its `import`s
         intact, which a classic script rejects outright. --}}
    <script type="module" src="{{ vite('source/_assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
