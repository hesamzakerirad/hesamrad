{{--
    A grid of dots with a radial fade.

    This component uses an SVG pattern and not a CSS `radial-gradient`. The
    spacing, the dot size and the fade shape are parameters. The dots use
    `currentColor`, therefore one file is sufficient for the two themes.

    The SVG has no `viewBox`, and CSS sets the size to 100%. One user unit is
    always one CSS pixel, and the browser does not scale the pattern. If the
    browser scales a grid of this density, a moiré pattern occurs.

    Parameters:
      $spacing — pixels between dot centers              (default 24)
      $dot     — dot radius in pixels                    (default 1)
      $center  — vertical position of the clear area     (default 45)
      $clear   — radius of the clear area, per cent      (default 40)
      $peak    — position of full strength, per cent     (default 74)
      $tail    — start of the vertical fade, per cent    (default 100, off)
      $knee    — position where the fade gets $floor     (default 76)
      $floor   — strength after $knee, 0 to 1            (default 0.14)

    The radial fade is a ring. The middle is transparent, therefore no dots are
    behind the headline. A band around the middle is at full strength. The outer
    edge is transparent, therefore the pattern shows no hard line at the edge of
    the box.

    $tail adds a vertical fade. Use it when the field is taller than the hero.
    The two masks multiply. The dots keep the ring shape at the top and decrease
    to $floor lower down. Set $tail to 100 to use the ring alone.

    The vertical fade decreases to $floor at $knee, then decreases slowly to
    zero. A straight fade keeps the dots at approximately one third of full
    strength at the middle of the box, where the pattern is still legible.
--}}
@php
    $spacing = $spacing ?? 24;
    $dot = $dot ?? 1;
    $center = $center ?? 45;
    $clear = $clear ?? 40;
    $peak = $peak ?? 74;
    $tail = $tail ?? 100;
    $knee = $knee ?? 76;
    $floor = $floor ?? 0.14;

    // A page can contain two dot grids. If two SVG ids are the same, the second
    // grid uses the pattern of the first grid, and no error occurs.
    $id = 'dg' . substr(md5($spacing . '-' . $dot . '-' . $center . '-' . uniqid('', true)), 0, 8);
@endphp

{{-- Do not add `width` or `height` attributes. They set the CSS width and
     height, and they override the left and right insets in the stylesheet. The
     box then gets the width of the shell, and the grid stops at the content
     column. CSS insets must set the size. `preserveAspectRatio` has no function
     without a `viewBox`. --}}
<svg class="dot-grid" aria-hidden="true" focusable="false">
    <defs>
        <pattern id="{{ $id }}-dots" width="{{ $spacing }}" height="{{ $spacing }}"
            patternUnits="userSpaceOnUse">
            <circle cx="{{ $dot }}" cy="{{ $dot }}" r="{{ $dot }}" fill="currentColor" />
        </pattern>

        <radialGradient id="{{ $id }}-fade" cx="50%" cy="{{ $center }}%" r="72%">
            <stop offset="0%" stop-color="#fff" stop-opacity="0" />
            <stop offset="{{ $clear }}%" stop-color="#fff" stop-opacity="0" />
            <stop offset="{{ $peak }}%" stop-color="#fff" stop-opacity="1" />
            <stop offset="100%" stop-color="#fff" stop-opacity="0" />
        </radialGradient>

        {{-- The vertical fade. It stays at full strength to $tail, then
             decreases to $floor at $knee, then decreases to zero at the bottom
             edge. --}}
        <linearGradient id="{{ $id }}-tail" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#fff" stop-opacity="1" />
            <stop offset="{{ $tail }}%" stop-color="#fff" stop-opacity="1" />
            <stop offset="{{ $knee }}%" stop-color="#fff" stop-opacity="{{ $floor }}" />
            <stop offset="100%" stop-color="#fff" stop-opacity="0" />
        </linearGradient>

        <mask id="{{ $id }}-tail-mask">
            <rect width="100%" height="100%" fill="url(#{{ $id }}-tail)" />
        </mask>

        {{-- The masks must stay nested. Two rects in one mask add their
             luminance and make the overlap brighter. Nested masks multiply
             their luminance, which is the necessary result. --}}
        <mask id="{{ $id }}-mask">
            <g mask="url(#{{ $id }}-tail-mask)">
                <rect width="100%" height="100%" fill="url(#{{ $id }}-fade)" />
            </g>
        </mask>
    </defs>

    <rect width="100%" height="100%" fill="url(#{{ $id }}-dots)" mask="url(#{{ $id }}-mask)" />
</svg>
