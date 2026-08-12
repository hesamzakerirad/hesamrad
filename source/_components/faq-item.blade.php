{{--
    One question.

    Keep the <details> element. Do not use a div with a click handler. The
    native element gives the keyboard behaviour, the correct role and the
    expanded state to a screen reader. It also operates without JavaScript. CSS
    adds the animation, therefore a browser without the animation still opens
    and closes the item.

    The answer stays in the document while the item is closed. Therefore a
    search engine can read the answer.
--}}
<details class="faq__item" @if ($question['open'] ?? false) open @endif>
    <summary class="faq__q">
        <h3>{{ $question['q'] }}</h3>

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
