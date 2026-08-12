{{--
    Pixel art as an inline SVG. The SVG is sharp at each zoom level, and it uses
    `currentColor`. Therefore one file is sufficient for the two themes.

    Parameters:
      $pixels — an array of strings of equal length; '#' is a filled cell
      $label  — the accessible name       (default 'pixel mark')
      $class  — an extra class on the <svg>  (optional)

    The loop below joins each run of filled cells in a row into one rect. The
    result is the same picture with less markup.
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
    {{-- Keep `shape-rendering="crispEdges"`. Without it, the browser
         antialiases the edges of the rects at fractional scales, and each pixel
         gets a soft grey edge. --}}
    <svg class="pixel-mark {{ $class ?? '' }}" viewBox="0 0 {{ $width }} {{ $height }}"
        role="img" aria-label="{{ $label ?? 'pixel mark' }}" shape-rendering="crispEdges"
        fill="currentColor" focusable="false">
        @foreach ($shapes as [$x, $y, $run])
            <rect x="{{ $x }}" y="{{ $y }}" width="{{ $run }}" height="1" />
        @endforeach
    </svg>
@endif
