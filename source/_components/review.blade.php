{{--
    One review, in either of the two treatments the site uses.

    Both treatments are the same thing, therefore they share this template and
    the `.review` class. A modifier picks the arrangement:

      card    — a box in a set. Used wherever reviews arrive several at a time.
                The text clamps and a button opens the rest.
      feature — no box, larger text, the label and the person in a left rail.
                Used once at the end of a case study, where there is one review
                and it is the last thing on the page.

    Parameters:
      $review  — one review array (refer to _components/reviews.blade.php)
      $variant — 'card' or 'feature'                       (default 'card')
      $index   — unique on the page. It builds the id the toggle controls.

    A review carries these keys. Only `quote` is required:

      quote        the text
      name         the author. Leave it null to withhold the name.
      withheld     shown in place of a name when `name` is null
      role         job title, and the company where that is public
      relationship how the author knows me: 'Client', 'Colleague'. This is what
                   tells a reader who wrote the review, so every review needs
                   one.
      url          profile or company link. The name links to it.
      avatar       image path. Initials show when it is absent.
      date         'YYYY', 'YYYY-MM' or 'YYYY-MM-DD'
      studyUrl     the case study this review belongs to
      studyTitle   the title of that case study
--}}
@php
    $variant = $variant ?? 'card';
    $index = $index ?? 0;

    $name = $review['name'] ?? null;

    /*
     * The initials stand in for a photo. A withheld name has no initials to
     * take, and inventing a letter for it would suggest a person the byline is
     * deliberately not naming, therefore the circle falls back to a neutral
     * mark.
     */
    $initials = collect(preg_split('/\s+/', trim((string) $name)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    /*
     * The date qualifies the review, so it is written out. A year on its own
     * stays a year. `strtotime` needs a day, therefore a YYYY-MM value gets one
     * before it is formatted, and the day is not printed.
     */
    $date = (string) ($review['date'] ?? '');
    $dateText = match (substr_count($date, '-')) {
        2 => date('j F Y', strtotime($date)),
        1 => date('F Y', strtotime($date . '-01')),
        default => $date,
    };
@endphp

<figure class="review review--{{ $variant }}">
    @if ($variant === 'feature')
        <h2 class="review__label">What the client said</h2>
    @endif

    <div class="review__body">
        <blockquote class="review__text" id="review-text-{{ $index }}">
            <p>{{ $review['quote'] }}</p>
        </blockquote>
    </div>

    @if ($variant === 'card')
        {{-- Keep the `hidden` attribute. main.js removes it only when the text
             overflows. If the script does not run, no review text is hidden. --}}
        <button class="review__toggle" type="button" data-review-toggle
            aria-controls="review-text-{{ $index }}" aria-expanded="false" hidden>
            <span data-review-more>Read the rest</span>
            <span data-review-less hidden>Show less</span>
        </button>
    @endif

    <figcaption class="review__author">
        {{-- Keep the initials below the photo. A LinkedIn avatar URL is signed
             and it expires. After it expires, the image does not cover the
             initials, and the circle still shows a person. --}}
        <span class="review__avatar {{ $initials ? '' : 'review__avatar--anonymous' }}" aria-hidden="true">
            {{ $initials ?: '' }}
            @if (!empty($review['avatar']))
                <img class="review__photo" src="{{ $review['avatar'] }}" alt=""
                    loading="lazy" decoding="async" width="48" height="48" data-avatar>
            @endif
        </span>

        <span class="review__who">
            <span class="review__name">
                @if ($name && !empty($review['url']))
                    <a href="{{ $review['url'] }}" target="_blank" rel="noopener noreferrer">{{ $name }}</a>
                @elseif ($name)
                    {{ $name }}
                @else
                    <span class="review__withheld">{{ $review['withheld'] ?? 'Name withheld' }}</span>
                @endif
            </span>

            @if (!empty($review['role']))
                <span class="review__role">{{ $review['role'] }}</span>
            @endif

            @if (!empty($review['relationship']))
                <span class="review__relationship">{{ $review['relationship'] }}</span>
            @endif

            {{-- Where the review can be checked, kept apart from who wrote it.
                 The three lines above answer "who is this person". These two
                 answer "how do I verify it", which is a different question, so
                 a rule separates them rather than a sixth line of grey text. --}}
            @if (!empty($review['studyUrl']) || $date)
                <span class="review__provenance">
                    {{-- Absent when the case studies are not public, because
                         the page it points at is then not built. --}}
                    @if (!empty($review['studyUrl']))
                        <a class="review__study" href="{{ $review['studyUrl'] }}">
                            <span>{{ $review['studyTitle'] ?? 'Read the case study' }}</span>
                        </a>
                    @endif

                    @if ($date)
                        {{-- Quieter than the name and the role above it. The
                             date qualifies the review. It does not identify
                             the person. --}}
                        <time class="review__date tabular" datetime="{{ $date }}">Written in {{ $dateText }}</time>
                    @endif
                </span>
            @endif
        </span>
    </figcaption>
</figure>
