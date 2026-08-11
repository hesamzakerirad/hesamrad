@extends('_layouts.main')

@php
    /*
     * A sample is never generated once the site is public — the collection
     * filter in config.php drops it before a page exists. This only controls
     * the visible warnings on the preview build, and the robots directive.
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
                {{-- Linked when there is somewhere to go. A client a reader can
                     click through and verify is the whole point of naming one. --}}
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

        {{-- Block form, not @php(...). Blade matches a php block with
             `@php(.*?)@endphp`, so an inline @php in a file that also contains
             a later @php…@endphp matches forward to *that* closing tag and
             swallows every line between the two as PHP. This file has such a
             block in the gallery below, and the inline form silently ate sixty
             lines of markup until the build failed on an @endforeach it could
             no longer see. --}}
        @php
            $cover = $page->getCover();
        @endphp

        @if ($cover)
            <figure class="study__cover">
                @if ($cover['src'])
                    <img src="{{ $cover['src'] }}" alt="{{ $cover['alt'] }}" loading="eager"
                        decoding="async" width="1600" height="900">
                @else
                    @include('_components.image-placeholder', [
                        'label' => $cover['alt'] ?: 'the finished product',
                        'ratio' => 'wide',
                    ])
                @endif

                @if ($cover['caption'])
                    <figcaption>{{ $cover['caption'] }}</figcaption>
                @endif
            </figure>
        @elseif ($page->coverNote)
            {{-- A study with no cover at all. Left unexplained the page reads as
                 one that is missing its pictures; saying why turns the absence
                 into something a reader can weigh, and a client who will not
                 show a screenshot is worth more as a signal than the screenshot
                 would have been.

                 This lived on the listing card until the listing stopped
                 carrying images, at which point it was answering a question
                 nobody was asking there. Here the gap is visible. --}}
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
                        <ul class="card__list">
                            @foreach ($page->constraints as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($page->built)
                    <section class="study__section">
                        <h2>What I built</h2>
                        <ul class="card__list">
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
                                // A phone screenshot cropped into a 4:3 slot throws
                                // away most of the screen, so each shot declares its
                                // own shape rather than inheriting one.
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

                {{-- The section that separates a case study from a brochure. A
                     feature list says what exists; naming the trade-off and
                     what it cost is the only part that shows judgement. --}}
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

                @if ($page->quote)
                    <blockquote class="study__quote">
                        <p>{{ $page->quote['text'] }}</p>
                        <cite>{{ $page->quote['attribution'] }}</cite>
                    </blockquote>
                @endif

                {{-- Admitting a misjudgement reads as confidence rather than
                     weakness, and it is the section a sceptical buyer believes
                     precisely because nobody invents one. --}}
                @if ($page->differently)
                    <section class="study__section">
                        <h2>What I would do differently</h2>
                        <p>{{ $page->differently }}</p>
                    </section>
                @endif
            </div>

            {{-- The facts, pinned alongside the narrative. On a long page the
                 reader keeps the answers to "who was this for and how long did
                 it take" in view instead of scrolling back for them. --}}
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
    </article>

    @if ($next && $next->getPath() !== $page->getPath())
        {{-- A named <nav>, matching the post layout: this is one of the
             landmarks a screen-reader user jumps between, and it was the only
             one on the site not announcing what it was. --}}
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
