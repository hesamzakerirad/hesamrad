<!DOCTYPE html>
<html lang="{{ $page->locale }}" theme="{{ $page->theme }}">

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
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="{{ $page->description ?? $page->siteDescription }}">

    <meta property="og:title" content="{{ $page->siteName }}{{ $page->title ? ' - ' . $page->title : '' }}" />
    <meta property="og:type" content="{{ $page->type ?? 'website' }}" />
    <meta property="og:url" content="{{ $page->getUrl() }}" />
    <meta property="og:description" content="{{ $page->description ?? $page->siteDescription }}" />

    <title>{{ $page->siteName }}{{ $page->title ? ' - ' . $page->title : '' }}</title>

    <link rel="home" href="{{ $page->baseUrl }}">
    <link rel="icon" href="/favicon.ico">

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
