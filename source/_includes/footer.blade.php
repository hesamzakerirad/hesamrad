@php
    /*
     * Footer link groups. Each group makes one <nav> element.
     *
     * Keys of a group:
     * - `title`: the accessible name of the <nav>. A screen reader can then
     *   skip the full group. Do not use an <h2> for this title, because it
     *   changes the heading outline of each page.
     * - `links`: the links in the group.
     *
     * Keys of a link:
     * - `label`: the visible text.
     * - `href`: the target address.
     * - `when`: optional. The link shows only when this value is true. The
     *   /work/ link uses the same flag as the header.
     * - `external`: optional. The link opens in a new tab.
     */
    $footerGroups = [
        [
            'title' => 'Work with me',
            'links' => [
                ['label' => 'Services', 'href' => $page->baseUrl . '/services/'],
                ['label' => 'Zero to One', 'href' => $page->baseUrl . '/zero-to-one/', 'when' => $page->campaignIsLive],
                ['label' => 'Case studies', 'href' => $page->baseUrl . '/work/', 'when' => $page->workIsPublic],
                ['label' => 'Questions', 'href' => $page->baseUrl . '/faq/'],
                ['label' => 'Start a conversation', 'href' => $page->baseUrl . '/#contact'],
            ],
        ],
        [
            'title' => 'More',
            'links' => [
                ['label' => 'About', 'href' => $page->baseUrl . '/about/'],
                ['label' => 'Open source', 'href' => $page->baseUrl . '/projects/'],
                ['label' => 'Blog', 'href' => $page->baseUrl . '/blog/'],
                ['label' => 'RSS feed', 'href' => $page->baseUrl . '/feed.xml'],
            ],
        ],
        [
            'title' => 'Elsewhere',
            'links' => [
                ['label' => 'Email', 'href' => 'mailto:' . $page->email],
                ['label' => 'LinkedIn', 'href' => 'https://linkedin.com/in/hesamrad', 'external' => true],
                ['label' => 'GitHub', 'href' => 'https://github.com/hesamzakerirad', 'external' => true],
            ],
        ],
    ];
@endphp

<footer class="site-footer">
    <div class="shell">
        <div class="site-footer__top">
            <div class="site-footer__brand">
                <p class="site-footer__name">{{ $page->siteName }}</p>
                <p class="site-footer__pitch">I'm an independent software engineer. I build complete products for businesses.</p>
                <p><span class="availability">Available for new projects</span></p>
            </div>

            @foreach ($footerGroups as $group)
                @php
                    $links = array_filter($group['links'], fn ($link) => $link['when'] ?? true);
                @endphp

                @if ($links)
                    <nav class="footer-group" aria-label="{{ $group['title'] }}">
                        <p class="footer-group__title">{{ $group['title'] }}</p>

                        <ul class="footer-group__list">
                            @foreach ($links as $link)
                                <li>
                                    <a href="{{ $link['href'] }}"
                                        @if ($link['external'] ?? false) target="_blank" rel="noopener noreferrer me" @endif>{{ $link['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                @endif
            @endforeach
        </div>

        <div class="site-footer__bottom">
            {{-- The build writes the year, and the script refreshes it. A page
                 in a cache thus does not show an incorrect year after 1
                 January. --}}
            <p>&copy; 2017&ndash;<span data-current-year>{{ date('Y') }}</span> {{ $page->siteName }}. All rights
                reserved.</p>
        </div>
    </div>
</footer>
