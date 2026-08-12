{{--
    A grid of numbered cards. The component sets the numbers, the heading level
    and the sequence of the parts for each page that uses it.

    Do not add the packages grid or the services grid to this component. Those
    grids have different parts, and each difference needs one more parameter.

    Parameters:
      $items — [['title' => …, 'body' => …], …]
      $grid  — the grid modifier: 'cards' (four in a row) or 'halves' (two)
--}}
@php
    $grid = $grid ?? 'cards';
@endphp

<div class="grid grid--{{ $grid }}">
    @foreach ($items as $index => $item)
        <article class="card">
            {{-- Keep the two digits and the `tabular` class. Together they give
                 each number the same width, and the numbers align. --}}
            <p class="card__index tabular">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</p>
            <h3 class="card__title">{{ $item['title'] }}</h3>
            <p class="card__body">{{ $item['body'] }}</p>
        </article>
    @endforeach
</div>
