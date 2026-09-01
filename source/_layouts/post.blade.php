@extends('_layouts.main')

@php
    /*
     * The post chrome uses the language of the post, not the language of the
     * site. `main` sets `dir` from the front matter of the post. Do not
     * hardcode this text. Hardcoded text puts Persian labels in a
     * left-to-right document, and it points the arrows in the wrong direction.
     *
     * A Persian post shows a Jalali date. All other posts show a Gregorian
     * date.
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

    // The JSON-LD trail must carry the same words as the visible one. This
    // template renders before the layout, so the labels are on the page object
    // by the time _includes/structured-data.blade.php builds the list.
    $page->crumbLabels = $t['crumbs'];
@endphp

@section('title', $page->title)

@section('description', $page->getSummary(160))

@section('body')
        @php
            /*
             * The markdown parser makes <h2> and <h3> elements with no id.
             * This code adds the ids and collects the headings in one pass.
             * The contents list and the anchors thus always agree.
             */
            $body = $page->withExternalLinksInNewTab($__env->yieldContent('content'));
            $flat = [];

            // This callback finds h2 and h3 in document order. The nesting
            // code below uses this order.
            $body = preg_replace_callback('/<(h[23])\b([^>]*)>(.*?)<\/\1>/s', function ($match) use (&$flat) {
                $tag = $match[1];
                $text = trim(html_entity_decode(strip_tags($match[3]), ENT_QUOTES, 'UTF-8'));

                $slug = trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9]+/', '-', mb_strtolower($text))), '-');

                // Two headings can make the same slug. Each anchor must be unique.
                $base = $slug ?: 'section';
                $n = 2;
                while (in_array($slug, array_column($flat, 'id'), true)) {
                    $slug = $base . '-' . $n++;
                }

                $flat[] = ['id' => $slug, 'text' => $text, 'level' => (int) substr($tag, 1)];

                return '<' . $tag . ' id="' . $slug . '"' . $match[2] . '>' . $match[3] . '</' . $tag . '>';
            }, $body);

            /*
             * Each h3 goes below the h2 before it. An h3 that comes before all
             * h2 elements becomes a top-level entry. The list must name every
             * heading on the page.
             */
            $contents = [];

            foreach ($flat as $heading) {
                if ($heading['level'] === 3 && $contents) {
                    $contents[count($contents) - 1]['children'][] = $heading;
                    continue;
                }

                $heading['children'] = [];
                $contents[] = $heading;
            }

            $showContents = count($contents) >= 4;
        @endphp

    <article class="shell section post-layout">

        <header class="article-header">
            {{-- The same labels reach the BreadcrumbList through
                 `$page->crumbLabels`, which _includes/structured-data.blade.php
                 reads. Google ignores the trail when the visible list and the
                 markup disagree, and a local of this template cannot reach the
                 layout. --}}
            @include('_components.breadcrumbs', ['crumbLabels' => $t['crumbs']])

            <h1>{{ $page->title }}</h1>

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

        <div class="thumbnail {{ $page->thumbnail && $page->thumbnailCopyRightSource ? 'thumbnail--credited' : '' }}">
            @if (! $page->thumbnail)
                @include('_components.image-placeholder', [
                    'ratio' => 'article',
                    'label' => 'header image for ' . $page->title,
                    'note' => 'Image to come',
                ])
            @else
                {{-- Do not use the post title as the alt text. The <h1> above
                     the image has the same words, and a screen reader reads
                     them two times. A post can describe its own image with
                     `thumbnailAlt`. An image with no `thumbnailAlt` is
                     decorative. --}}
                {{-- The `width` and `height` attributes must keep the 16/9
                     ratio, because the CSS shows the image at 16/9. A different
                     ratio makes the browser reserve a box of the wrong shape,
                     and the page moves when the image loads.

                     `fetchpriority="high"` is necessary, because this image is
                     the largest element above the fold. The default priority of
                     the browser for an image is low. --}}
                <img src="{{ $page->thumbnail }}" alt="{{ $page->thumbnailAlt ?? '' }}" width="1600" height="900"
                    fetchpriority="high">

                @if ($page->thumbnailCopyRightSource)
                    @php
                        /*
                         * The link text is the host of the source URL. A person
                         * who reads only the links on the page gets no
                         * information from a generic word. The generic word
                         * applies only when the URL has no host.
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

        <div class="post-layout__body">
            @if ($showContents)
                <nav class="contents post-toc" aria-labelledby="contents-label">
                    <p class="contents__label" id="contents-label">Contents</p>

                    <ol class="contents__list">
                        @foreach ($contents as $entry)
                            <li>
                                <a href="#{{ $entry['id'] }}">{{ $entry['text'] }}</a>

                                @if ($entry['children'])
                                    <ol class="contents__sub">
                                        @foreach ($entry['children'] as $child)
                                            <li><a href="#{{ $child['id'] }}">{{ $child['text'] }}</a></li>
                                        @endforeach
                                    </ol>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endif

            <div class="post-layout__main">



        <div class="rich-text">
            {!! $body !!}
        </div>

        {{-- `filter()` is necessary, because the post template has a blank
             `tags:` entry. A list of one empty string is not a list. --}}
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

        {{-- `cta: false` in the front matter removes the CTA from a post. The
             comparison uses `!==` against false. A post with no `cta` key must
             keep the CTA. --}}
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
        </div>
        </div>
    </article>

{{-- A `faq:` block in the front matter makes a FAQPage node. The same
     questions and answers must also be visible in the body of the post. Google
     gives this condition for the markup.

     This `faq:` belongs to the post and has nothing to do with `siteFaq` in
     config.php. `a` is one string here and an array of paragraphs there, and
     this block writes no visible markup, because the post writes the questions
     itself. Do not pass these rows to _components.faq-list. --}}
{{-- config.php builds the node. A template cannot: Blade compiles the word
     after an at sign as a directive, and the key that carries the vocabulary
     becomes compiled PHP. --}}
@if ($page->faq)
    @push('scripts')
        <script type="application/ld+json">{!! $page->faqSchema($page->faq) !!}</script>
    @endpush
@endif

@endsection
