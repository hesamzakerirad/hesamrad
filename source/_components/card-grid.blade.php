{{--
    A grid of numbered cards.

    Three pages state a set of four things — what I build, what you get, what I
    believe — and each was spelling out the same seven lines of markup. They are
    the same object, so the numbering, the heading level and the order of the
    parts are decided here rather than in three places that have to be kept in
    step by hand.

    The cards that are *not* here are the ones that only look similar: the
    packages grid is an anchor with a tag list, and the services grid carries a
    second line and a list of inclusions. Folding those in would mean a
    parameter for every difference, which is how a component stops being one.

    $items — [['title' => …, 'body' => …], …]
    $grid  — the grid modifier: 'cards' (four across) or 'halves' (two)
--}}
@php
    $grid = $grid ?? 'cards';
@endphp

<div class="grid grid--{{ $grid }}">
    @foreach ($items as $index => $item)
        <article class="card">
            {{-- Padded to two digits so the numbers form a column rather than
                 a ragged edge, and tabular so they stay one width. --}}
            <p class="card__index tabular">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</p>
            <h3 class="card__title">{{ $item['title'] }}</h3>
            <p class="card__body">{{ $item['body'] }}</p>
        </article>
    @endforeach
</div>
