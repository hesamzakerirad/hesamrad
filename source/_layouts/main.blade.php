<!DOCTYPE html>
<html lang="{{ $page->language }}">

@php
    $title = $page->siteName . ($page->title ? ' - ' . $page->title : '');
    $description = $page->description ?? $page->siteDescription;
    $favicon = $page->baseUrl . '/favicon.ico';
    $thumbnail = $page->thumbnail ? $page->baseUrl . $page->thumbnail : $favicon;
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="copyright" content="{{ $page->siteName }}">
    <meta name="language" content="{{ $page->language }}">
    <meta name="theme-color" content="#2455c3" />
    <meta name="robots" content="{{ $page->getRobotsStatus() }}">
    <meta name="author" content="حسام راد, hesamzakerirad@gmail.com">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }}</title>

    @if ($page->isPost($page))
        @if ($page->isTranslated)
            <link rel="alternate" hreflang="en" href="{{ $page->source }}" />
        @endif

        <link rel="canonical" href="{{ $page->getUrl() }}" />

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ $title }}" />
        <meta property="og:type" content="{{ $page->type ?? 'website' }}" />
        <meta property="og:url" content="{{ $page->getUrl() }}" />
        <meta property="og:site_name" content="{{ $page->siteName }}">
        <meta property="og:description" content="{{ $description }}" />
        <meta property="og:locale" content="{{ $page->locale }}">
        <meta property="og:image" content="{{ $thumbnail }}">
        <meta property="og:image:width" content="850">
        <meta property="og:image:height" content="470">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ $thumbnail }}">
        <meta name="twitter:site" content="@hesamzakerirad">
        <meta name="twitter:creator" content="@hesamzakerirad">

        <!-- Structured Data -->
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Article",
                "headline": "{{ $title }}",
                "description": "{{ $description }}",
                "author": {
                  "@type": "Person",
                  "name": "{{ $page->siteName }}"
                },
                "datePublished": "{{ $page->getDate() }}",
                "image": "{{ $thumbnail }}"
            }
        </script>
    @endif

    <link rel="home" href="{{ $page->baseUrl }}">
    <link rel="icon" href="{{ $favicon }}">

    <script>
        document.documentElement.setAttribute('theme', localStorage.getItem('theme') || 'light');
    </script>
    <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
</head>

<body>
    <header>
        <div class="big-container">
            <div class="identity">
                <a href="{{ $page->baseUrl }}" class="no-decoration">
                    {{ $page->siteName }}
                </a>
                <small class="description"> {{ $page->siteDescription }} </small>
            </div>
            <div>
                <span id="theme" class="theme-toggle">
                    <svg id="theme-icon" width="24" height="24" viewBox="0 0 24 24">
                        <!-- Moon icon (dark color) -->
                        <path id="moon-icon" fill="currentColor" d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1-8.313-12.454z"/>
                        <!-- Sun icon (white color in dark mode) -->
                        <path id="sun-icon" fill="currentColor" d="M12 18a6 6 0 1 1 0-12a6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8a4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/>
                    </svg>
                </span>
            </div>
        </div>
    </header>

    <main role="main">
        @yield('body')
    </main>

    <footer>
        <section class="big-container">
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

    <script src="https://kit.fontawesome.com/3aa580010a.js" crossorigin="anonymous"></script>
    <script src="{{ mix('js/main.js', 'assets/build') }}"></script>
    @stack('scripts')
</body>

</html>
