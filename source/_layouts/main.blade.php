<!DOCTYPE html>
<html lang="{{ $page->getLanguage() }}" dir="{{ $page->getDirection() }}">

@php
    // Blade's inline `@section('name', $value)` sends the value through e()
    // before it stores the value. yieldContent() thus gives back escaped text.
    // Decode the text one time here. Each consumer then escapes for its own
    // context. Without this, {{ }} escapes two times and JSON-LD gets HTML
    // entities.
    $yield = fn ($section) => html_entity_decode(trim($__env->yieldContent($section)), ENT_QUOTES, 'UTF-8');

    /*
     * The page title comes first. The brand suffix comes last. Google shows
     * approximately 60 characters of a title.
     *
     * The code does not add the brand suffix in these conditions:
     * - `disableTitlePrefix` is true in the front matter.
     * - The page title already contains the site name.
     * - The full title becomes more than 60 characters.
     */
    $pageTitle = $yield('title');
    $brandSuffix = ' · ' . $page->siteName;
    $titleLimit = 60;
    $needsBrand = ! $page->disableTitlePrefix
        && stripos($pageTitle, $page->siteName) === false
        && mb_strlen($pageTitle . $brandSuffix) <= $titleLimit;
    $title = $needsBrand ? $pageTitle . $brandSuffix : $pageTitle;
    $description = $yield('description');
    $favicon = $page->baseUrl . '/favicon.ico';
    /*
     * Add the base URL only to a path that needs it. The `thumbnail:` key in
     * the front matter accepts a site-relative path or a full URL. Do not
     * concatenate the base URL to a full URL. The result is an incorrect
     * address in og:image, twitter:image, the preload hint and the BlogPosting
     * node. The visible <img> stays correct, because it uses the raw value.
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
    {{-- Do not add a `theme-color` meta tag. It can use only
         `prefers-color-scheme`. This site takes its theme from an attribute
         that the visitor can change. The address bar then shows a color that
         disagrees with the page. `color-scheme` in tokens.css gives the browser
         the necessary data. --}}
    <meta name="robots" content="{{ $page->getRobotsStatus() }}">
    <meta name="author" content="{{ $page->getAuthor() }}">
    {{-- Do not write an empty description tag. Omit the tag. --}}
    @if ($description !== '')
        <meta name="description" content="{{ $description }}">
    @endif
    @unless ($page->disableCanonical)
        <link rel="canonical" href="{{ $pageUrl }}">
    @endunless

    {{-- This script must stay before the stylesheet. The correct palette is
         then in place at the first paint. The same code in main.js shows the
         incorrect theme for a moment at each navigation. A stored choice has
         priority over the OS setting. --}}
    <script>
        (function() {
            var theme;
            try {
                theme = localStorage.getItem('theme');
            } catch (error) {
                // Safari in private mode throws an error on a read and on a write.
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

    {{-- Preload only the post's own thumbnail. It is the largest element above
         the fold on that page. The page does not show the default card, and a
         preload of the default card makes an unnecessary request. --}}
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
    {{-- The .ico comes first, for a browser that reads no further. A browser
         that supports an SVG icon takes the SVG, which stays sharp at each size
         and follows the color scheme of the reader. --}}
    <link rel="icon" href="{{ $favicon }}" sizes="16x16 32x32 48x48">
    {{-- A plain path. `viteStaticCopy` copies source/_assets/images verbatim,
         therefore the file has no manifest key and `vite()` throws on it. --}}
    <link rel="icon" type="image/svg+xml" href="{{ $page->baseUrl }}/assets/build/images/logo.svg">
    @viteRefresh()
    {{-- Preload only the upright face of Inter. It sets all the text above the
         fold. Do not preload the italic face or the mono face. They are rare,
         and a preload of them competes with the text. `vite()` resolves any
         manifest key, not only an entry point, and it throws an error on a
         miss. A renamed or deleted font thus stops the build. --}}
    <link rel="preload" as="font" type="font/woff2" crossorigin
        href="{{ vite('source/_assets/fonts/Inter/Inter-Variable.woff2') }}">
    <link rel="stylesheet" href="{{ vite('source/_assets/css/main.css') }}">

    {{-- The analytics scripts must stay last. No element above them then waits
         for a third-party request. --}}
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

    {{-- Do not remove `tabindex="-1"`. The skip link above needs it. A jump to
         a fragment moves the scroll position, but only a focusable target also
         moves the focus. Without it, Safari keeps the focus in the nav, and the
         next Tab goes back into that nav. --}}
    <main id="main" class="page-main" tabindex="-1">
        @yield('body')
    </main>

    @include('_includes.footer')

    {{-- `type="module"` is necessary. The dev server serves this file with its
         `import` statements, and a classic script rejects them. --}}
    <script type="module" src="{{ vite('source/_assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>
