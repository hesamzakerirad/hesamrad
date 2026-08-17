@extends('_layouts.main')

@php
    /*
     * The collection filter in config.php removes a sample when the site is
     * public. No page exists then. This code controls only the warning on the
     * preview build and the robots directive.
     */
    $isSample = $page->sample ?? false;
    $page->robots = ($page->workIsPublic && ! $isSample) ? 'index,follow' : 'noindex,nofollow';

    $studies = $caseStudies->sortByDesc('year')->values();
    $position = $studies->search(fn ($study) => $study->getPath() === $page->getPath());
    $next = $position === false ? null : $studies->get($position + 1) ?? $studies->first();
@endphp

@section('title', $page->title)

@section('description', $page->description)

@section('body')
    <article class="shell section study">
        <div class="study__head">
            @include('_components.breadcrumbs')

            @if ($isSample)
                <p class="sample-notice" role="status">
                    <strong>Invented sample.</strong> No such company exists, nobody said any of this, and every figure
                    was made up. It is here to show the shape a real case study takes.
                </p>
            @endif

            <p class="case__meta">
                <span>
                    @if ($page->client && $page->clientUrl)
                        <a href="{{ $page->clientUrl }}" target="_blank" rel="noopener noreferrer">{{ $page->client }}</a>
                    @else
                        {{ $page->client ?? 'Client name withheld' }}
                    @endif
                </span>
                <span>{{ $page->sector }}</span>
                <span class="tabular">{{ $page->year }}</span>
            </p>

            <h1 class="study__title">{{ $page->title }}</h1>

            @if ($page->summary)
                <p class="lead study__summary">{{ $page->summary }}</p>
            @endif
        </div>

        {{-- Use the block form. Do not use the inline form @php(...). Blade
             matches a php block with `@php(.*?)@endphp`. In a file that also
             has a later @php block, an inline @php matches forward to that
             @endphp. Blade then compiles all the lines between the two as PHP.
             The gallery below has such a block. --}}
        @php
            $cover = $page->getCover();
        @endphp

        @if ($cover)
            <figure class="study__cover">
                {{-- The frame holds the credit above the image. It must have
                     `position: relative`, because the credit uses
                     `position: absolute`. --}}
                <div class="study__cover-frame {{ $cover['src'] && $cover['credit'] ? 'study__cover-frame--credited' : '' }}">
                    @if ($cover['src'])
                        <img src="{{ $cover['src'] }}" alt="{{ $cover['alt'] }}" loading="eager"
                            decoding="async" width="1600" height="900">
                    @else
                        @include('_components.image-placeholder', [
                            'label' => $cover['alt'] ?: 'the finished product',
                            'ratio' => 'wide',
                        ])
                    @endif

                    @if ($cover['src'] && $cover['credit'])
                        {{-- Use the block form. Do not use the inline form
                             @php(...). The gallery below has a later @php
                             block, and an inline @php matches forward to that
                             @endphp. --}}
                        @php
                            /*
                             * The link text is the host of the source address.
                             * A person who reads only the links on the page
                             * gets no information from a generic word. The
                             * generic word applies only when the address has no
                             * host.
                             *
                             * The pattern also removes a `www.` or `images.`
                             * prefix. `credit` usually points to the page of
                             * the image, but it accepts a direct address, and
                             * the host of an image file is frequently a CDN
                             * name such as `images.unsplash.com`. The name of
                             * the site is the necessary text.
                             */
                            $creditHost = parse_url($cover['credit'], PHP_URL_HOST);
                            $creditText = $creditHost ? preg_replace('/^(www|images)\./', '', $creditHost) : 'here';
                        @endphp

                        <small class="copyright">
                            Image borrowed from <a href="{{ $cover['credit'] }}" target="_blank"
                                rel="noopener noreferrer">{{ $creditText }}</a>.
                        </small>
                    @endif
                </div>

                @if ($cover['caption'])
                    <figcaption>{{ $cover['caption'] }}</figcaption>
                @endif
            </figure>
        @elseif ($page->coverNote)
            <p class="study__cover-note">{{ $page->coverNote }}</p>
        @endif

        <div class="study__body">
            <div class="study__main">
                <section class="study__section">
                    <h2>What was wrong</h2>
                    <p>{{ $page->problem }}</p>
                </section>

                @if ($page->constraints)
                    <section class="study__section">
                        <h2>What made it awkward</h2>
                        <ul class="card__list card__list--no">
                            @foreach ($page->constraints as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($page->built)
                    <section class="study__section">
                        <h2>What I built</h2>
                        <ul class="card__list card__list--yes">
                            @foreach ($page->built as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($page->gallery)
                    <div class="study__section case__gallery">
                        @foreach ($page->gallery as $shot)
                            @php
                                // Each shot sets its own `ratio` key. The default
                                // is 'tall'. The value 'mobile' also changes the
                                // `width` and `height` attributes below.
                                $shotRatio = $shot['ratio'] ?? 'tall';
                            @endphp

                            <figure class="shot shot--{{ $shotRatio }}">
                                @if (!empty($shot['src']))
                                    <img src="{{ $shot['src'] }}" alt="{{ $shot['alt'] ?? '' }}" loading="lazy"
                                        decoding="async" width="{{ $shotRatio === 'mobile' ? 400 : 800 }}"
                                        height="{{ $shotRatio === 'mobile' ? 820 : 600 }}">
                                @else
                                    @include('_components.image-placeholder', [
                                        'label' => $shot['alt'] ?? 'screen',
                                        'ratio' => $shotRatio,
                                    ])
                                @endif

                                @if (!empty($shot['caption']))
                                    <figcaption>{{ $shot['caption'] }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                @endif

                @if ($page->decisions)
                    <section class="study__section">
                        <h2>Decisions worth explaining</h2>

                        <div class="study__decisions">
                            @foreach ($page->decisions as $decision)
                                <div class="decision">
                                    <h3 class="decision__choice">{{ $decision['choice'] }}</h3>
                                    <p class="decision__why">{{ $decision['why'] }}</p>
                                    <p class="decision__cost"><strong>The trade-off:</strong> {{ $decision['cost'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($page->timeline)
                    <section class="study__section">
                        <h2>How it ran</h2>
                        <div class="rows">
                            @foreach ($page->timeline as $step)
                                <div class="row">
                                    <p class="row__key">{{ $step['phase'] }}</p>
                                    <div class="row__value">
                                        <p>{{ $step['detail'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($page->results)
                    <section class="study__section">
                        <h2>What changed</h2>
                        <div class="stat-row study__results">
                            @foreach ($page->results as $result)
                                <div class="stat">
                                    <span class="stat__value tabular">{{ $result['figure'] }}</span>
                                    <span class="stat__label">{{ $result['caption'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($page->differently)
                    <section class="study__section">
                        <h2>What I would do differently</h2>
                        <p>{{ $page->differently }}</p>
                    </section>
                @endif
            </div>

            <aside class="study__aside">
                <div class="study__facts">
                    <dl>
                        <dt>Client</dt>
                        <dd>
                            @if ($page->client && $page->clientUrl)
                                <a href="{{ $page->clientUrl }}" target="_blank" rel="noopener noreferrer">{{ $page->client }}</a>
                            @else
                                {{ $page->client ?? 'Withheld' }}
                            @endif
                        </dd>

                        <dt>Sector</dt>
                        <dd>{{ $page->sector }}</dd>

                        <dt>When</dt>
                        <dd class="tabular">{{ $page->year }}</dd>

                        @if ($page->duration)
                            <dt>How long</dt>
                            <dd>{{ $page->duration }}</dd>
                        @endif

                        @if ($page->role)
                            <dt>My part in it</dt>
                            <dd>{{ $page->role }}</dd>
                        @endif
                    </dl>

                    @if ($page->stack)
                        <ul class="tags study__stack">
                            @foreach ($page->stack as $tag)
                                <li class="tag">{{ $tag }}</li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($page->liveUrl)
                        <p class="mt-md">
                            <a class="link-arrow" href="{{ $page->liveUrl }}" target="_blank" rel="noopener noreferrer">
                                <span>See it live</span>
                                @include('_components.icon', ['name' => 'external'])
                            </a>
                        </p>
                    @endif
                </div>
            </aside>
        </div>

        {{-- The client review. A case study without a `review` in its front
             matter renders nothing here, and the page ends at the body above.
             The review closes the article rather than sitting inside the main
             column, because it is the client answering everything the article
             claims. --}}
        @php
            $review = $page->review;

            $reviewInitials = collect(preg_split('/\s+/', trim($review['name'] ?? '')))
                ->filter()
                ->take(2)
                ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                ->implode('');
        @endphp

        @if ($review && ! empty($review['quote']))
            {{-- The order here is the order a phone reads: the label, the
                 review, then the person. On a wide screen the stylesheet moves
                 the label and the person into a left rail beside the review.
                 The <figure> keeps the review and the name tied together. --}}
            <figure class="review">
                <h2 class="review__label">What the client said</h2>

                <blockquote class="review__quote">
                    <p>{{ $review['quote'] }}</p>
                </blockquote>

                <figcaption class="review__author">
                    {{-- The avatar and name classes come from the
                         recommendation cards. The chip is the same one, so a
                         person is drawn the same way everywhere. --}}
                    <span class="quote__avatar" aria-hidden="true">
                        {{ $reviewInitials }}
                        @if (!empty($review['avatar']))
                            <img class="quote__photo" src="{{ $review['avatar'] }}" alt=""
                                loading="lazy" decoding="async" width="44" height="44" data-avatar>
                        @endif
                    </span>

                    <span class="quote__who">
                        <span class="quote__name">
                            @if (!empty($review['url']))
                                <a href="{{ $review['url'] }}" target="_blank" rel="noopener noreferrer">{{ $review['name'] }}</a>
                            @else
                                {{ $review['name'] }}
                            @endif
                        </span>

                        @if (!empty($review['role']))
                            <span class="quote__role">{{ $review['role'] }}</span>
                        @endif

                        {{-- The date tells the reader how old the review is. A
                             review of work that finished years ago and a review
                             written last month are different evidence. --}}
                        @if (!empty($review['date']))
                            @php
                                /*
                                 * `datetime` takes a year, a YYYY-MM, or a
                                 * YYYY-MM-DD. The reader gets a month name
                                 * where the front matter gives a month, and
                                 * the bare year where it gives a year.
                                 */
                                $reviewDate = (string) $review['date'];
                                $reviewDateText = match (substr_count($reviewDate, '-')) {
                                    2 => date('j F Y', strtotime($reviewDate)),
                                    1 => date('F Y', strtotime($reviewDate . '-01')),
                                    default => $reviewDate,
                                };
                            @endphp

                            <time class="review__date tabular" datetime="{{ $reviewDate }}">Written in {{ $reviewDateText }}</time>
                        @endif
                    </span>
                </figcaption>
            </figure>
        @endif
    </article>

    @if ($next && $next->getPath() !== $page->getPath())
        {{-- This <nav> must keep its `aria-label`. A <nav> is a landmark. A
             screen-reader user moves between the landmarks, and the label tells
             the landmarks apart. --}}
        <nav class="shell section" aria-label="Next case study">
            <a class="next-post" href="{{ $next->getCanonicalUrl() }}">
                <span>
                    <span class="next-post__label">Next case study</span>
                    {{ $next->title }}
                </span>
                @include('_components.icon', ['name' => 'arrow-right'])
            </a>
        </nav>
    @endif

    <div class="shell section" id="contact">
        <div class="callout">
            <h2>Got something like this?</h2>
            <p>Describe it in a paragraph. You will get an honest answer about whether I am the right person for it,
                including the times when I am not.</p>

            @include('_components.contact-form')
        </div>
    </div>
@endsection
