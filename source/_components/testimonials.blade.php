{{--
    Recommendations. The data comes from config.php, therefore more than one
    page can show the same set.

    The component renders nothing when there are no quotes.

    Parameters:
      $heading — section heading            (a default is below)
      $limit   — show only the first N quotes
      $band    — draw the section on the raised surface  (default true)

    Set `$band` to false when the section above this one is already a band. Two
    bands together make one block of color with no edge between them.
--}}
@php
    $quotes = collect($page->testimonials ?? []);

    /*
     * The placeholder quotes show the layout in a local build. `production` is
     * false in config.php and true in config.production.php. Therefore
     * `jigsaw build production` does not emit these quotes.
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

    $initials = fn ($name) => collect(preg_split('/\s+/', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');
@endphp

@if ($quotes->isNotEmpty())
    {{-- The inner <div class="shell"> sets the width. Do not add `shell` here. --}}
    <section class="section {{ ($band ?? true) ? 'section--band' : '' }}">
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

            <div class="quotes">
                @foreach ($quotes as $index => $quote)
                    <figure class="quote">
                        <div class="quote__body">
                            <blockquote class="quote__text" id="quote-text-{{ $index }}">
                                <p>{{ $quote['quote'] }}</p>
                            </blockquote>
                        </div>

                        {{-- Keep the `hidden` attribute. main.js removes it
                             only when the quote text overflows. If the script
                             does not run, no quote text becomes hidden. --}}
                        <button class="quote__toggle" type="button" data-quote-toggle
                            aria-controls="quote-text-{{ $index }}" aria-expanded="false" hidden>
                            <span data-quote-more>Read the rest</span>
                            <span data-quote-less hidden>Show less</span>
                        </button>

                        <figcaption class="quote__author">
                            {{-- Keep the initials below the photo. A LinkedIn
                                 avatar URL is signed and it expires. After it
                                 expires, the image does not cover the initials,
                                 and the circle still shows a person. --}}
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

            @if (! $showingPlaceholders && $page->testimonialsUrl)
                <p class="quotes__more">
                    <a class="link-arrow" href="{{ $page->testimonialsUrl }}"
                        target="_blank" rel="noopener noreferrer">
                        <span>See all recommendations on LinkedIn</span>
                        @include('_components.icon', ['name' => 'external'])
                    </a>
                </p>
            @endif
        </div>
    </section>
@endif
