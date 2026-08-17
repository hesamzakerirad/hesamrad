{{--
    A list of questions as native disclosures. /faq/ and any page with its own
    questions show the same markup from the same data in config.php.

    Do not emit the FAQPage schema from this component. The caller emits it with
    _components.faq-schema, and it passes the same rows it passed here. A caller
    that shows a part of the array must not declare the whole array.

    Parameters:
      $items   — rows from $page->siteFaq
      $grouped — when true, show each `group` as a heading  (default false)
--}}
@php
    $grouped = $grouped ?? false;
    $rows = collect($items);
@endphp

@if ($grouped)
    @foreach ($rows->groupBy('group') as $group => $questions)
        {{-- Keep the measure on this wrapper. Do not put the measure on the
             heading and the list. --measure-wide uses the ch unit, and one ch
             is the width of the zero of the font. That width changes with the
             weight and the letter-spacing. The heading has weight 600 and
             tracking, therefore 72ch is 34px more on the heading than on the
             list. --}}
        <div class="faq-group">
            <h2 class="faq__group">{{ $group }}</h2>

            <div class="faq">
                @foreach ($questions as $question)
                    @include('_components.faq-item', ['question' => $question])
                @endforeach
            </div>
        </div>
    @endforeach
@else
    <div class="faq">
        @foreach ($rows as $question)
            @include('_components.faq-item', ['question' => $question])
        @endforeach
    </div>
@endif
