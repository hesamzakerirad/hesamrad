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

    The fade is a ring, not a spotlight: transparent in the middle so nothing
    sits behind the headline, full in a band around it, and transparent again by
    the outer edge so the field never meets the edge of its own box with a hard
    line. That is what lets it live inside the shell without a seam.
--}}
@php
    $spacing = $spacing ?? 24;
    $dot = $dot ?? 1;
    $centre = $centre ?? 45;
    $clear = $clear ?? 40;
    $peak = $peak ?? 74;

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

        <mask id="{{ $id }}-mask">
            <rect width="100%" height="100%" fill="url(#{{ $id }}-fade)" />
        </mask>
    </defs>

    <rect width="100%" height="100%" fill="url(#{{ $id }}-dots)" mask="url(#{{ $id }}-mask)" />
</svg>
