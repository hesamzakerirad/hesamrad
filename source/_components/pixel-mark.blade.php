{{--
    Pixel art, drawn as SVG rather than shipped as an image.

    A raster at this size would have to be served at several densities to stay
    crisp and would still be a request; as geometry it is a few hundred bytes
    inline, sharp at any zoom, and takes its colour from `currentColor` so it
    follows the theme without a second file for dark mode.

    $pixels — array of equal-length strings, '#' for a filled cell
    $label  — what it says, for anyone who cannot see it
    $class  — optional extra class on the <svg>

    Runs of filled cells on a row are merged into one rect. Fifty-odd
    single-cell rects would render identically, but this is the same picture in
    a third of the markup, and the merge is four lines.
--}}
@php
    $rows = $pixels ?? [];
    $height = count($rows);
    $width = $height ? max(array_map('strlen', $rows)) : 0;

    $shapes = [];

    foreach ($rows as $y => $row) {
        $x = 0;
        $length = strlen($row);

        while ($x < $length) {
            if ($row[$x] !== '#') {
                $x++;
                continue;
            }

            $run = 0;
            while ($x + $run < $length && $row[$x + $run] === '#') {
                $run++;
            }

            $shapes[] = [$x, $y, $run];
            $x += $run;
        }
    }
@endphp

@if ($shapes)
    {{-- `shape-rendering: crispEdges` is the whole point: without it a browser
         antialiases the rect edges at fractional scales and the pixels acquire
         soft grey fringes, which is the one thing pixel art must not have. --}}
    <svg class="pixel-mark {{ $class ?? '' }}" viewBox="0 0 {{ $width }} {{ $height }}"
        role="img" aria-label="{{ $label ?? 'pixel mark' }}" shape-rendering="crispEdges"
        fill="currentColor" focusable="false">
        @foreach ($shapes as [$x, $y, $run])
            <rect x="{{ $x }}" y="{{ $y }}" width="{{ $run }}" height="1" />
        @endforeach
    </svg>
@endif
