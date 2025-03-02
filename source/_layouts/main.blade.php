<!DOCTYPE html>
<html lang="{{ $page->language }}" theme="{{ $page->theme }}">

@php
    $title = $page->siteName . ($page->title ? ' - ' . $page->title : '');
    $description = $page->description ?? $page->siteDescription;
    $favicon = $page->baseUrl . "/favicon.ico";
@endphp

<head>
    @if ($page->production)
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-PJ77P9N8');
        </script>
        <!-- End Google Tag Manager -->
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <meta name="theme-color" content="#2455c3" />
    <meta name="copyright" content="{{ $page->siteName }}">
    <meta name="language" content="{{ $page->language }}">
    <meta name="medium" content="blog">
    <meta name="coverage" content="Worldwide">
    <meta name="distribution" content="Global">
    <meta name="robots" content="{{ $page->getRobotsStatus() }}">
    <meta name="author" content="حسام راد, hesamzakerirad@gmail.com">
    <meta name="keywords" content="{{ $page->getKeyWords() }}"/>
    <meta name="description" content="{{ $description }}">
    <meta name="pagename" content="{{ $title }}">
    <title>{{ $title }}</title>

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="imagetoolbar" content="no">
    <meta http-equiv="x-dns-prefetch-control" content="off">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:type" content="{{ $page->type ?? 'website' }}" />
    <meta property="og:url" content="{{ $page->getUrl() }}" />
    <meta property="og:site_name" content="{{ $page->siteName }}">
    <meta property="og:description" content="{{ $description }}" />
    <meta property="og:locale" content="{{ $page->locale }}">
    <meta property="og:image" content="{{ $favicon }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="{{ $favicon }}">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $favicon }}">

    <!-- Structured Data -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "{{ $title }}",
            "description": "{{ $description }}",
            "url": "{{ $page->getUrl() }}"
        }
    </script>

    <link rel="home" href="{{ $page->baseUrl }}">
    <link rel="icon" href="{{ $favicon }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
</head>

<body>
    @if ($page->production)
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PJ77P9N8" height="0" width="0"
                style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

    <header>
        <section class="identity wrapper">
            <a href="{{ $page->baseUrl }}" class="no-decoration">
                {{ $page->siteName }}
            </a>
            <small class="description"> {{ $page->siteDescription }} </small>
    </header>

    <main role="main">
        @yield('body')
    </main>

    <hr>

    <footer>
        <section class="wrapper">
            <div>
                <a href="{{ $page->baseUrl }}" class="no-decoration">{{ $page->siteName }}</a>
                <span>
                    از 1397 خورشیدی
                </span>
            </div>
            <div class="contact">
                <a href="mailto:hesamzakerirad@gmail.com" class="no-decoration" target="_blank">ایمیل</a>
                <a href="https://github.com/hesamzakerirad" class="no-decoration" target="_blank">گیت‌هاب</a>
                <a href="https://linkedin.com/in/hesamrad" class="no-decoration" target="_blank">لینکداین</a>
            </div>
        </section>
    </footer>
    </section>

    <script src="{{ mix('js/main.js', 'assets/build') }}"></script>
    @stack('scripts')
</body>

</html>
