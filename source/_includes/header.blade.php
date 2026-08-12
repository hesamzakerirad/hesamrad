@php
    /*
     * `getPath()` is '' on the home page and 'blog/{slug}' inside a post, so a
     * prefix match on the first segment is what keeps "Blog" marked while an
     * article is open.
     */
    $segment = strtok(trim($page->getPath(), '/'), '/');

    $links = [
        ['label' => 'Home', 'path' => '', 'href' => $page->baseUrl],
    ];

    // Gated on the same flag that sets the page's robots directive, so the nav
    // and the index can never disagree about whether /work/ is public.
    if ($page->workIsPublic) {
        $links[] = ['label' => 'Work', 'path' => 'work', 'href' => $page->baseUrl . '/work/'];
    }

    /*
     * Open source now lives in the footer instead. It was taking a header slot
     * that Work needs, and a business owner does not know what a Laravel
     * package is — it was never going to earn a click from the audience this
     * site is for.
     */
    $links[] = ['label' => 'Zero to One', 'path' => 'zero-to-one', 'href' => $page->baseUrl . '/zero-to-one/'];
    $links[] = ['label' => 'Services', 'path' => 'services', 'href' => $page->baseUrl . '/services/'];
    /*
     * About is in the footer, not here, for the same reason Open source is.
     * The header's job is the four things somebody can buy or look at before
     * deciding; who I am is what they read once they are already interested,
     * and it is reachable from the home page and every footer on the site.
     */
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
            {{-- Both glyphs ship; CSS shows the one matching the current theme,
                 so the button is already correct before the script runs. --}}
            <button class="theme-toggle" type="button" data-theme-toggle aria-pressed="false">
                <span class="visually-hidden">Toggle colour theme</span>
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
