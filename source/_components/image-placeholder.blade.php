{{--
    An abstract stand-in for a screenshot that does not exist yet.

    Rendered inline rather than served as a file, so nothing ships to
    production for the sake of a preview. It is deliberately a wireframe and
    not a convincing mock: the point is to show how the layout behaves with
    images in it, not to invent a product that was never built.

    $label — what the real image will show
    $ratio — 'wide' (16:9 cover), 'tall' (4:3 desktop shot), 'mobile' (phone),
             'article' (16:9, for a post's header picture)
    $note  — the caption drawn inside the shape, if not the default

    'article' is deliberately not a wireframe. The others stand in for a
    screenshot of something built, so browser chrome is the honest shape; a blog
    post's header picture is a photograph or an illustration, and framing one in
    a browser window would promise the wrong thing entirely.
--}}
@php
    $ratio = $ratio ?? 'wide';

    $sizes = [
        'wide' => [1600, 900],
        'tall' => [800, 600],
        'mobile' => [400, 820],
        'article' => [1600, 900],
        'portrait' => [800, 1000],
    ];

    [$w, $h] = $sizes[$ratio] ?? $sizes['wide'];
    $isPhone = $ratio === 'mobile';
    $isArticle = $ratio === 'article';
    $isPortrait = $ratio === 'portrait';
    $note = $note ?? 'Placeholder — no screenshot yet';
@endphp

<svg class="ph ph--{{ $ratio }}" viewBox="0 0 {{ $w }} {{ $h }}" role="img"
    aria-label="Placeholder: {{ $label ?? 'screenshot' }}" preserveAspectRatio="xMidYMid slice">
    <rect width="{{ $w }}" height="{{ $h }}" class="ph__bg" />

    @if ($isPortrait)
        {{-- Head and shoulders: the avatar glyph, so the shape reads as "a
             photograph of a person belongs here" and not as a missing image. --}}
        @php
            $pcx = $w / 2;
            $head = $h * 0.10;
        @endphp

        <circle cx="{{ $pcx }}" cy="{{ $h * 0.38 }}" r="{{ $head }}" class="ph__block--strong" />

        <path class="ph__block--strong"
            d="M {{ $pcx - $head * 2.1 }} {{ $h * 0.70 }}
               a {{ $head * 2.1 }} {{ $head * 2.1 }} 0 0 1 {{ $head * 4.2 }} 0 Z" />

        <text x="{{ $pcx }}" y="{{ $h * 0.82 }}" text-anchor="middle" class="ph__label">
            {{ $note }}
        </text>
    @elseif ($isArticle)
        {{-- The horizon-and-sun glyph every image control has used for thirty
             years. It says "a picture belongs here" without inventing one. --}}
        @php
            $cx = $w / 2;
            $cy = $h * 0.44;
            $unit = $h * 0.15;
        @endphp

        <circle cx="{{ $cx - $unit * 0.85 }}" cy="{{ $cy - $unit * 0.45 }}" r="{{ $unit * 0.32 }}"
            class="ph__block--strong" />

        <path class="ph__block--strong"
            d="M {{ $cx - $unit * 1.6 }} {{ $cy + $unit * 0.8 }}
               L {{ $cx - $unit * 0.35 }} {{ $cy - $unit * 0.3 }}
               L {{ $cx + $unit * 0.5 }} {{ $cy + $unit * 0.8 }} Z" />

        <path class="ph__block"
            d="M {{ $cx - $unit * 0.2 }} {{ $cy + $unit * 0.8 }}
               L {{ $cx + $unit * 0.75 }} {{ $cy - $unit * 0.05 }}
               L {{ $cx + $unit * 1.6 }} {{ $cy + $unit * 0.8 }} Z" />

        <rect x="{{ $cx - $unit * 1.6 }}" y="{{ $cy + $unit * 0.78 }}" width="{{ $unit * 3.2 }}"
            height="{{ $h * 0.012 }}" rx="{{ $h * 0.006 }}" class="ph__block--strong" />

        <text x="{{ $cx }}" y="{{ $h * 0.78 }}" text-anchor="middle" class="ph__label">
            {{ $note }}
        </text>
    @elseif ($isPhone)
        {{-- A status bar and a notch, so the shape reads as a phone rather than
             a narrow browser. --}}
        <rect x="0" y="0" width="{{ $w }}" height="{{ $h * 0.055 }}" class="ph__chrome" />
        <rect x="{{ $w * 0.36 }}" y="{{ $h * 0.014 }}" width="{{ $w * 0.28 }}" height="{{ $h * 0.026 }}"
            rx="{{ $h * 0.013 }}" class="ph__bar" />

        {{-- Title, two lines of text, then a stack of rows: the shape almost
             every phone screen actually takes. --}}
        <rect x="{{ $w * 0.08 }}" y="{{ $h * 0.10 }}" width="{{ $w * 0.56 }}" height="{{ $h * 0.038 }}"
            rx="{{ $h * 0.008 }}" class="ph__block ph__block--strong" />
        <rect x="{{ $w * 0.08 }}" y="{{ $h * 0.16 }}" width="{{ $w * 0.78 }}" height="{{ $h * 0.018 }}"
            rx="{{ $h * 0.006 }}" class="ph__block" />

        @foreach ([0.23, 0.36, 0.49, 0.62] as $ry)
            <rect x="{{ $w * 0.08 }}" y="{{ $h * $ry }}" width="{{ $w * 0.84 }}" height="{{ $h * 0.10 }}"
                rx="{{ $h * 0.012 }}" class="ph__block" />
        @endforeach

        {{-- A bar at the foot, where a phone puts its primary action. --}}
        <rect x="{{ $w * 0.08 }}" y="{{ $h * 0.78 }}" width="{{ $w * 0.84 }}" height="{{ $h * 0.055 }}"
            rx="{{ $h * 0.028 }}" class="ph__block ph__block--strong" />

        <text x="{{ $w / 2 }}" y="{{ $h * 0.93 }}" text-anchor="middle" class="ph__label">
            {{ $note }}
        </text>
    @else
        {{-- A browser bar, so the shape reads as a screen rather than a blank box. --}}
        <rect x="0" y="0" width="{{ $w }}" height="{{ $h * 0.09 }}" class="ph__chrome" />
        @foreach ([0.028, 0.048, 0.068] as $cx)
            <circle cx="{{ $w * $cx }}" cy="{{ $h * 0.045 }}" r="{{ $h * 0.014 }}" class="ph__dot" />
        @endforeach
        <rect x="{{ $w * 0.10 }}" y="{{ $h * 0.028 }}" width="{{ $w * 0.34 }}" height="{{ $h * 0.034 }}"
            rx="{{ $h * 0.017 }}" class="ph__bar" />

        <rect x="{{ $w * 0.06 }}" y="{{ $h * 0.19 }}" width="{{ $w * 0.42 }}" height="{{ $h * 0.07 }}"
            rx="{{ $h * 0.012 }}" class="ph__block ph__block--strong" />
        <rect x="{{ $w * 0.06 }}" y="{{ $h * 0.31 }}" width="{{ $w * 0.62 }}" height="{{ $h * 0.035 }}"
            rx="{{ $h * 0.010 }}" class="ph__block" />
        <rect x="{{ $w * 0.06 }}" y="{{ $h * 0.37 }}" width="{{ $w * 0.50 }}" height="{{ $h * 0.035 }}"
            rx="{{ $h * 0.010 }}" class="ph__block" />

        @foreach ([0.06, 0.36, 0.66] as $bx)
            <rect x="{{ $w * $bx }}" y="{{ $h * 0.50 }}" width="{{ $w * 0.28 }}" height="{{ $h * 0.34 }}"
                rx="{{ $h * 0.022 }}" class="ph__block" />
        @endforeach

        <text x="{{ $w / 2 }}" y="{{ $h * 0.94 }}" text-anchor="middle" class="ph__label">
            {{ $note }}
        </text>
    @endif
</svg>
