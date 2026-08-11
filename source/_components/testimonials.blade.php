{{--
    Recommendations, pulled from config.php so the same set can appear on more
    than one page without being kept in step by hand.

    Renders nothing at all when empty. An empty testimonials section is worse
    than no section — it draws attention to the absence.

    Optional:
      $heading  — section heading (defaults below)
      $limit    — show only the first N
--}}
@php
    $quotes = collect($page->testimonials ?? []);

    /*
     * Placeholders exist so the layout can be designed and judged with
     * something in it. The guard is the build: `production` is false in
     * config.php and true in config.production.php, so these render locally
     * and are physically absent from anything `jigsaw build production`
     * emits. The names are deliberately not plausible.
     */
    $showingPlaceholders = ! $page->production && $quotes->isEmpty();

    if ($showingPlaceholders) {
        $quotes = collect([
            [
                'quote' => 'He took a system nobody wanted to touch and made it something the team could change without holding its breath. Six months later we were shipping on a Friday afternoon, which had been unthinkable before.',
                'name' => 'Sample Person',
                'role' => 'Engineering Manager, Placeholder Ltd',
                'relationship' => 'Managed Hesam directly',
                'url' => '',
            ],
            [
                'quote' => 'The thing that stood out was how much he wrote down. When he moved on, nothing stopped working and nobody had to reverse-engineer anything.',
                'name' => 'Example Colleague',
                'role' => 'Product Lead, Sampleton Software',
                'relationship' => 'Worked alongside Hesam',
                'url' => '',
            ],
            [
                'quote' => 'He said no to two things I asked for and was right about both. That is rarer and more useful than it sounds.',
                'name' => 'Placeholder Founder',
                'role' => 'Founder, Exampleford Co',
                'relationship' => 'Hired Hesam',
                'url' => '',
            ],
        ]);
    }

    if (isset($limit)) {
        $quotes = $quotes->take($limit);
    }

    // Two letters is enough to read as a person without becoming a logo.
    $initials = fn ($name) => collect(preg_split('/\s+/', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
@endphp

@if ($quotes->isNotEmpty())
    <section class="section section--band">
        <div class="shell">
            <div class="section-head">
                <h2>{{ $heading ?? 'What people who have worked with me say.' }}</h2>
            </div>

            @if ($showingPlaceholders)
                <p class="sample-notice" role="status">
                    <strong>Local preview.</strong> These quotes are invented placeholders for judging the layout.
                    Nobody said any of this, and none of it is in the published site.
                </p>
            @endif

            {{-- Stacked, one per row, each sized to its own quote.
                 Side by side, cards had to be equalised, which meant either
                 padding short ones out or scaling their type up — the first
                 looked unfinished and the second looked inconsistent. Stacking
                 removes the comparison entirely: nothing sits beside anything,
                 so nothing has to match, the type stays one size, and opening
                 one leaves every other card exactly where it was. --}}
            <div class="quotes">
                @foreach ($quotes as $index => $quote)
                    <figure class="quote">
                        <div class="quote__body">
                            <blockquote class="quote__text" id="quote-text-{{ $index }}">
                                <p>{{ $quote['quote'] }}</p>
                            </blockquote>
                        </div>

                        {{-- Added by main.js only when the text actually
                             overflows, so a short quote never grows a pointless
                             control and nothing is hidden if the script fails. --}}
                        <button class="quote__toggle" type="button" data-quote-toggle
                            aria-controls="quote-text-{{ $index }}" aria-expanded="false" hidden>
                            <span data-quote-more>Read the rest</span>
                            <span data-quote-less hidden>Show less</span>
                        </button>

                        <figcaption class="quote__author">
                            {{-- The initials sit underneath the photo rather than
                                 instead of it. A LinkedIn avatar URL is signed and
                                 expires; when it does, the image simply stops
                                 covering them and the circle still reads as a
                                 person instead of going blank. --}}
                            <span class="quote__avatar" aria-hidden="true">
                                {{ $initials($quote['name']) }}
                                @if (!empty($quote['avatar']))
                                    <img class="quote__photo" src="{{ $quote['avatar'] }}" alt=""
                                        loading="lazy" decoding="async" width="44" height="44" data-avatar>
                                @endif
                            </span>

                            <span class="quote__who">
                                <span class="quote__name">
                                    @if (!empty($quote['url']))
                                        <a href="{{ $quote['url'] }}" target="_blank" rel="noopener noreferrer">{{ $quote['name'] }}</a>
                                    @else
                                        {{ $quote['name'] }}
                                    @endif
                                </span>

                                @if (!empty($quote['role']))
                                    <span class="quote__role">{{ $quote['role'] }}</span>
                                @endif

                                @if (!empty($quote['relationship']))
                                    <span class="quote__relationship">{{ $quote['relationship'] }}</span>
                                @endif
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif
