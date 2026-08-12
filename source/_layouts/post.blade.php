@extends('_layouts.main')

@php
    /*
     * The post chrome follows the post's own language, not the site's.
     * `main` already flips `dir` from the post's front matter, so leaving this
     * text hardcoded would drop Persian labels into a left-to-right document
     * and point both arrows the wrong way.
     *
     * Dates follow the same rule: a Persian post is dated in Jalali, everything
     * else in the Gregorian calendar the rest of the site uses.
     */
    $isRtl = $page->getDirection() === 'rtl';

    $strings = [
        'fa' => [
            'readTime' => 'خواندن در :minutes دقیقه',
            'copyUrl' => 'کپی آدرس',
            'copied' => 'کپی شد!',
            'nextPost' => 'نوشته بعدی',
            'imageCredit' => 'نگاره از :link به امانت گرفته شده است.',
            'imageCreditLink' => 'اینجا',
            'crumbs' => ['home' => 'خانه', 'blog' => 'وبلاگ'],
            'publishedLabel' => 'انتشار :date',
            'updatedLabel' => 'ویرایش :date',
            'published' => fn ($page) => $page->getJalaliDate(),
            'date' => fn ($page) => $page->getUpdatedJalaliDate(),
        ],
        'en' => [
            'readTime' => ':minutes min read',
            'copyUrl' => 'Copy URL',
            'copied' => 'Copied',
            'nextPost' => 'Next post',
            'imageCredit' => 'Image borrowed from :link.',
            'imageCreditLink' => 'here',
            'crumbs' => [],
            'publishedLabel' => 'Published :date',
            'updatedLabel' => 'updated :date',
            'published' => fn ($page) => $page->getCreatedAtDate('j F Y'),
            'date' => fn ($page) => $page->getUpdatedAtDate('j F Y'),
        ],
    ];

    $t = $strings[$page->getBaseLanguage()] ?? $strings['en'];
@endphp

@section('title', $page->title)

@section('description', $page->getSummary(160))

@section('body')
    <article class="shell shell--article section">
        <header class="article-header">
            @include('_components.breadcrumbs', ['crumbLabels' => $t['crumbs']])

            <h1>{{ $page->title }}</h1>

            {{-- Published and updated are now separate facts. The listing shows
                 no date at all and orders by created_at, so this is the only
                 place either appears — and conflating them, as this did, meant
                 a post edited for a typo advertised itself as new.

                 The updated line only appears when the two actually differ.
                 "Published 7 August, updated 7 August" is noise. --}}
            <p class="article-meta">
                <time datetime="{{ $page->getCreatedAtDate() }}">
                    {{ str_replace(':date', $t['published']($page), $t['publishedLabel']) }}
                </time>

                @if ($page->getUpdatedAtDate() !== $page->getCreatedAtDate())
                    <span class="article-meta__sep" aria-hidden="true">/</span>
                    <time datetime="{{ $page->getUpdatedAtDate() }}">
                        {{ str_replace(':date', $t['date']($page), $t['updatedLabel']) }}
                    </time>
                @endif

                @if ($readTime = $page->getReadTime())
                    <span class="article-meta__sep" aria-hidden="true">/</span>
                    <span>{{ str_replace(':minutes', $readTime, $t['readTime']) }}</span>
                @endif
            </p>
        </header>

        {{-- The frame is always drawn. A post without a picture yet gets a
             stand-in rather than nothing, so the layout is the one it will have
             once the picture exists — and dropping a real `thumbnail:` into
             front matter replaces it with no other change to make and nothing
             to remember to remove. --}}
        <div class="thumbnail {{ $page->thumbnail && $page->thumbnailCopyRightSource ? 'thumbnail--credited' : '' }}">
            @if (! $page->thumbnail)
                @include('_components.image-placeholder', [
                    'ratio' => 'article',
                    'label' => 'header image for ' . $page->title,
                    'note' => 'Image to come',
                ])
            @else
                {{-- The title, verbatim, was the alt text — and the <h1> saying
                     the same words sits directly above it, so a screen reader
                     read the headline twice. A post can describe its own
                     picture with `thumbnailAlt`; without one the image is
                     decorative, which is what these are. --}}
                {{-- 16/9 in the attributes because that is what the CSS
                     displays it at. 850x470 is 1.809, so the box the browser
                     reserved before the image arrived was the wrong shape and
                     the page shifted when it landed.

                     fetchpriority high: this is the largest element above the
                     fold on a post, and the browser's default guess for an
                     image it has not laid out yet is "low". --}}
                <img src="{{ $page->thumbnail }}" alt="{{ $page->thumbnailAlt ?? '' }}" width="1600" height="900"
                    fetchpriority="high">

                @if ($page->thumbnailCopyRightSource)
                    @php
                        /*
                         * The source's own host as the link text, falling back
                         * to the generic word only if the URL has no host to
                         * read. "Image borrowed from here" tells somebody
                         * skimming a list of links on the page precisely
                         * nothing, and that list is how a lot of people read.
                         */
                        $creditHost = parse_url($page->thumbnailCopyRightSource, PHP_URL_HOST);
                        $creditText = $creditHost ? preg_replace('/^www\./', '', $creditHost) : $t['imageCreditLink'];

                        $creditLink = '<a href="' . e($page->thumbnailCopyRightSource) . '" target="_blank" rel="noopener noreferrer">'
                            . e($creditText) . '</a>';
                    @endphp

                    <small class="copyright">
                        {!! str_replace(':link', $creditLink, e($t['imageCredit'])) !!}
                    </small>
                @endif
            @endif
        </div>

        <div class="rich-text">
            @yield('content')
        </div>

        {{-- Authored on every post and, until now, read only by the JSON-LD
             keywords. `filter()` because the post template ships a blank entry,
             and a list of one empty string is not a list. Plain text rather
             than links: there are no tag archive pages, and a tag that looks
             clickable and is not is worse than one that does not. --}}
        @php($tags = collect($page->tags)->filter())

        @if ($tags->isNotEmpty())
            <p class="tag-row">
                <span class="visually-hidden">Filed under:</span>
                @foreach ($tags as $tag)
                    <span class="tag">{{ $tag }}</span>
                @endforeach
            </p>
        @endif

        <div class="btn-row">
            @include('_components.copy-url-button', [
                'copyLabel' => $t['copyUrl'],
                'copiedLabel' => $t['copied'],
            ])
        </div>

        {{-- `cta: false` in front matter opts a post out. The comparison is
             against false rather than falsy so an unset key still gets one —
             the default has to be "ask", or posts will quietly stop asking. --}}
        @if ($page->cta !== false)
            @include('_components.post-cta')
        @endif

        @if ($next = $page->getNext())
            <nav aria-label="{{ $t['nextPost'] }}">
                <a class="next-post" href="{{ $next->getCanonicalUrl() }}">
                    <span>
                        <span class="next-post__label">{{ $t['nextPost'] }}</span>
                        <span>{{ $next->title }}</span>
                    </span>
                    @include('_components.icon', ['name' => $isRtl ? 'arrow-left' : 'arrow-right'])
                </a>
            </nav>
        @endif
    </article>

{{-- A post that carries a `faq:` block describes it for machines too. The
     questions here mirror the ones visible in the body, which is the condition
     Google puts on this markup — schema for answers a reader cannot see is
     the thing it is meant to catch. --}}
@if ($page->faq)
    @push('scripts')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                '@id' => $page->getCanonicalUrl() . '#faq',
                'mainEntity' => collect($page->faq)->map(fn ($item) => [
                    '@type' => 'Question',
                    'name' => $item['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => html_entity_decode(strip_tags($item['a']), ENT_QUOTES, 'UTF-8'),
                    ],
                ])->all(),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
@endif

@endsection
