{{--
    One question.

    <details>, not a div with a click handler. It is a disclosure widget, and
    the native one already has the keyboard behaviour, the right role, the
    expanded state announced to a screen reader, and — the part that matters
    most here — it still works with no JavaScript at all. The animation is
    layered on in CSS, so a browser that cannot do it gets an instant toggle
    rather than a broken one.

    The answers stay in the document while collapsed, so a search engine still
    reads them.
--}}
<details class="faq__item" @if ($question['open'] ?? false) open @endif>
    <summary class="faq__q">
        <h3>{{ $question['q'] }}</h3>

        {{-- Two rules drawn in CSS rather than a pair of icons swapped on
             state. One of them rotates flat and the plus becomes a minus in a
             single movement — there is no frame where the mark is neither. --}}
        <span class="faq__mark" aria-hidden="true"></span>
    </summary>

    <div class="faq__a">
        @foreach ($question['a'] as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach

        @if ($link = $question['link'] ?? null)
            <p class="faq__link">
                <a class="link-arrow" href="{{ $page->baseUrl . $link['href'] }}">
                    <span>{{ $link['label'] }}</span>
                    @include('_components.icon', ['name' => 'arrow-right'])
                </a>
            </p>
        @endif
    </div>
</details>
