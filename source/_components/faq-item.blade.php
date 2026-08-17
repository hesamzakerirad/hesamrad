{{--
    One question.

    Keep the <details> element. Do not use a div with a click handler. The
    native element gives the keyboard behavior, the correct role and the
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
            @php
                /*
                 * `href` accepts a path on this site ('/services/') or a full
                 * URL ('https://laravel.com'). Add the base URL only to a path.
                 * Without the test, a full URL joins to the host and gives
                 * 'http://localhost:8000https://laravel.com'.
                 *
                 * The first test finds any scheme and a protocol-relative URL,
                 * therefore mailto: and tel: also stay as written. The second
                 * finds the subset that leaves the site, and only those links
                 * open in a new tab.
                 */
                $hasScheme = (bool) preg_match('#^([a-z][a-z0-9+.-]*:|//)#i', $link['href']);
                $isExternal = (bool) preg_match('#^(https?:)?//#i', $link['href']);
                $href = $hasScheme ? $link['href'] : $page->baseUrl . $link['href'];
            @endphp

            <p class="faq__link">
                <a class="link-arrow" href="{{ $href }}"
                    @if ($isExternal) target="_blank" rel="noopener noreferrer" @endif>
                    <span>{{ $link['label'] }}</span>
                    @include('_components.icon', ['name' => 'arrow-right'])
                </a>
            </p>
        @endif
    </div>
</details>
