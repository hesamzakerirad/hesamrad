@php
    /*
     * `getPath()` gives '' on the home page and 'blog/{slug}' in a post. A
     * match on the first segment keeps the mark on "Blog" while a post is
     * open.
     */
    $segment = strtok(trim($page->getPath(), '/'), '/');

    $links = [
        ['label' => 'Home', 'path' => '', 'href' => $page->baseUrl],
    ];

    // This link uses the same flag as the page's robots directive. The nav and
    // the index thus always agree about /work/.
    if ($page->workIsPublic) {
        $links[] = ['label' => 'Work', 'path' => 'work', 'href' => $page->baseUrl . '/work/'];
    }

    $links[] = ['label' => 'Zero to One', 'path' => 'zero-to-one', 'href' => $page->baseUrl . '/zero-to-one/'];
    $links[] = ['label' => 'Services', 'path' => 'services', 'href' => $page->baseUrl . '/services/'];
    $links[] = ['label' => 'Blog', 'path' => 'blog', 'href' => $page->baseUrl . '/blog/'];
@endphp

<header class="site-header" data-header>
    <div class="shell site-header__inner">
        <a class="brand" href="{{ $page->baseUrl }}" rel="home">{{ $page->siteName }}</a>

        <nav class="site-nav" id="primary-nav" aria-label="Primary">
            @foreach ($links as $link)
                @php $isActive = $segment === $link['path'] || ($link['path'] === '' && $page->isHomePage()); @endphp
                <a class="site-nav__link {{ $isActive ? 'is-active' : '' }}" href="{{ $link['href'] }}"
                    @if ($isActive) aria-current="page" @endif>{{ $link['label'] }}</a>
            @endforeach
        </nav>

        <div class="header-actions">
            {{-- The page contains both glyphs. CSS shows the glyph for the
                 current theme. The button is thus correct before the script
                 runs. --}}
            <button class="theme-toggle" type="button" data-theme-toggle aria-pressed="false">
                <span class="visually-hidden">Toggle color theme</span>
                @include('_components.icon', ['name' => 'sun', 'class' => 'theme-toggle__icon theme-toggle__icon--sun'])
                @include('_components.icon', ['name' => 'moon', 'class' => 'theme-toggle__icon theme-toggle__icon--moon'])
            </button>

            <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="primary-nav">
                <span class="visually-hidden">Toggle navigation</span>
                @include('_components.icon', ['name' => 'menu', 'class' => 'nav-toggle__icon nav-toggle__icon--open'])
                @include('_components.icon', ['name' => 'close', 'class' => 'nav-toggle__icon nav-toggle__icon--close'])
            </button>
        </div>
    </div>
</header>
