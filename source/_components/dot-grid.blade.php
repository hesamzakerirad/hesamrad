{{--
    A grid of dots, faded away from the middle.

    SVG rather than a CSS background: the spacing, the dot size and the shape of
    the fade are all parameters here, where in a `radial-gradient` they are
    baked into a string that has to be rewritten to change any of them. It also
    stays one file across both themes, because the dots take `currentColor`.

    No `viewBox`. The pattern is laid out in user units and the element is sized
    at 100% by CSS, so one user unit is one CSS pixel at every container size —
    a dot is exactly $dot pixels across on a phone and on a 27-inch screen, and
    nothing is ever scaled. A grid this fine that gets stretched is how you get
    a moiré that shimmers as the page scrolls.

    $spacing — pixels between dot centres        (default 24)
    $dot     — dot radius in pixels              (default 1)
    $centre  — where the clearing sits, 0–100    (default 45, just above middle)
    $clear   — radius of the clearing, %         (default 40)
    $peak    — where the dots reach full strength (default 74)
    $tail    — where the downward dissolve starts (default 100, i.e. none)
    $knee    — where the dissolve reaches $floor  (default 76)
    $floor   — strength held from $knee, 0–1      (default 0.14)

    The fade is a ring, not a spotlight: transparent in the middle so nothing
    sits behind the headline, full in a band around it, and transparent again by
    the outer edge so the field never meets the edge of its own box with a hard
    line. That is what lets it live inside the shell without a seam.

    $tail adds a second, vertical fade on top of the ring, for when the field is
    taller than the hero it started in. The two masks multiply, so the dots keep
    the ring's shape near the top and thin out on the way down: the lower part
    of the box carries the tint without carrying the pattern, and the field ends
    on air rather than on an edge. Leave $tail at 100 for a ring alone.

    The dissolve is a knee rather than a straight ramp, and that is the whole
    trick. A straight line from full to nothing across the box leaves the dots
    at about a third of strength where the figures sit — still a legible grid.
    The knee drops them to $floor before it gets there, then spends the rest of
    the box easing that whisper to zero. So the pattern stops being a pattern
    early, and the field still has somewhere to end softly.
--}}
@php
    $spacing = $spacing ?? 24;
    $dot = $dot ?? 1;
    $centre = $centre ?? 45;
    $clear = $clear ?? 40;
    $peak = $peak ?? 74;
    $tail = $tail ?? 100;
    $knee = $knee ?? 76;
    $floor = $floor ?? 0.14;

    // Two of these can appear on one page one day, and duplicate SVG ids
    // silently make the second one reference the first one's pattern.
    $id = 'dg' . substr(md5($spacing . '-' . $dot . '-' . $centre . '-' . uniqid('', true)), 0, 8);
@endphp

{{-- No width/height attributes. They map to CSS width/height, which
     over-constrains the left/right insets the stylesheet uses to push this out
     into the gutters — the box silently came back to the shell's width and the
     grid stopped at the content column. Sized entirely by CSS insets instead.
     preserveAspectRatio is likewise gone: it does nothing without a viewBox,
     and there is deliberately no viewBox. --}}
<svg class="dot-grid" aria-hidden="true" focusable="false">
    <defs>
        <pattern id="{{ $id }}-dots" width="{{ $spacing }}" height="{{ $spacing }}"
            patternUnits="userSpaceOnUse">
            <circle cx="{{ $dot }}" cy="{{ $dot }}" r="{{ $dot }}" fill="currentColor" />
        </pattern>

        <radialGradient id="{{ $id }}-fade" cx="50%" cy="{{ $centre }}%" r="72%">
            <stop offset="0%" stop-color="#fff" stop-opacity="0" />
            <stop offset="{{ $clear }}%" stop-color="#fff" stop-opacity="0" />
            <stop offset="{{ $peak }}%" stop-color="#fff" stop-opacity="1" />
            <stop offset="100%" stop-color="#fff" stop-opacity="0" />
        </radialGradient>

        {{-- Vertical dissolve. Full strength down to $tail so the ring is
             untouched where it does its work, then a fast drop to $floor by
             $knee, then a long slow run to nothing at the bottom edge. --}}
        <linearGradient id="{{ $id }}-tail" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#fff" stop-opacity="1" />
            <stop offset="{{ $tail }}%" stop-color="#fff" stop-opacity="1" />
            <stop offset="{{ $knee }}%" stop-color="#fff" stop-opacity="{{ $floor }}" />
            <stop offset="100%" stop-color="#fff" stop-opacity="0" />
        </linearGradient>

        <mask id="{{ $id }}-tail-mask">
            <rect width="100%" height="100%" fill="url(#{{ $id }}-tail)" />
        </mask>

        {{-- Nested rather than side by side: a group carrying the tail mask,
             filled with the ring. Two rects in one mask would add their
             luminance and brighten the overlap; nesting multiplies them, which
             is what "fade the ring out downward" actually means. --}}
        <mask id="{{ $id }}-mask">
            <g mask="url(#{{ $id }}-tail-mask)">
                <rect width="100%" height="100%" fill="url(#{{ $id }}-fade)" />
            </g>
        </mask>
    </defs>

    <rect width="100%" height="100%" fill="url(#{{ $id }}-dots)" mask="url(#{{ $id }}-mask)" />
</svg>
