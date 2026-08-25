@php
    /*
     * One figure and its caption.
     *
     * The component separates the number from whatever follows it, because a
     * numeral and a unit are different things and the page sets them
     * differently. `83%` gives `83` and `%`, `~1 min` gives `~1` and ` min`,
     * and `500+` gives `500` and `+`. The pattern keeps a leading
     * approximation mark with the number, where it belongs.
     *
     * A number runs to the last digit, therefore a separator inside it stays
     * inside it. `48,000` stays whole, and so does a range such as `2-6`.
     *
     * A figure that is not a number, such as a word, has no match. The whole
     * value then goes in the numeral, which leaves the caller free to put any
     * text in `figure`.
     */
    $statFigure = trim((string) $figure);

    $statNumber = $statFigure;
    $statUnit = '';

    if (preg_match('/^([~<>≈]?\s*\d(?:[\d.,\x{2013}\x{2014}-]*\d)?)(.*)$/u', $statFigure, $statParts)) {
        $statNumber = $statParts[1];
        $statUnit = $statParts[2];
    }
@endphp

<div class="stat">
    <span class="stat__value tabular">
        {{ $statNumber }}@if ($statUnit)<span class="stat__unit">{{ $statUnit }}</span>@endif
    </span>
    <span class="stat__label">{{ $caption }}</span>
</div>
