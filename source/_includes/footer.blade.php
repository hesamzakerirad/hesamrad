@php
    /*
     * Footer link groups.
     *
     * Adding a page here is one line. Each group renders as its own <nav> with
     * an accessible name taken from `title`, so a screen reader can skip a
     * group wholesale — and so the titles do not land in the page's heading
     * outline, which is what a real <h2> per column would do to every page on
     * the site.
     *
     * `when` is optional and gates a link, so /work/ appears here on exactly
     * the same condition it appears in the header rather than on a second copy
     * of the rule that can drift out of step.
     */
    $footerGroups = [
        [
            'title' => 'Work with me',
            'links' => [
                ['label' => 'Services', 'href' => $page->baseUrl . '/services/'],
                ['label' => 'Zero to One', 'href' => $page->baseUrl . '/zero-to-one/'],
                ['label' => 'Case studies', 'href' => $page->baseUrl . '/work/', 'when' => $page->workIsPublic],
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
                <p class="site-footer__pitch">I am an independent software engineer and I build complete products for businesses.</p>
                <p><span class="availability">Available for new projects</span></p>
            </div>

            @foreach ($footerGroups as $group)
                @php
                    // `when` defaults to true, so most links need no flag at all.
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
            {{-- One line, not two. There was already a copyright here; the
                 colophon sat beside it saying which static site generator built
                 the page, which is a note to other developers on a site written
                 for people buying software. The rights notice absorbs it.

                 The year is stamped at build time and refreshed by the script,
                 so a page cached across New Year's does not go stale. --}}
            <p>&copy; 2017&ndash;<span data-current-year>{{ date('Y') }}</span> {{ $page->siteName }}. All rights
                reserved.</p>
        </div>
    </div>
</footer>
