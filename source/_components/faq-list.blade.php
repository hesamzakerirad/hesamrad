{{--
    A list of questions as native disclosures.

    Extracted so /faq/ and /services/ render the same markup from the same
    data in config.php. Only one of them may carry the FAQPage schema, so
    emitting it is the caller's job rather than this component's — two pages
    both claiming to be the FAQ is how you get neither of them treated as one.

    $items    — rows from $page->faq
    $grouped  — when true, print each `group` as a heading before its questions
--}}
@php
    $grouped = $grouped ?? false;
    $rows = collect($items);
@endphp

@if ($grouped)
    @foreach ($rows->groupBy('group') as $group => $questions)
        {{-- The wrapper carries the measure, not the heading and the list
             separately. --measure-wide is in ch, and ch is the width of the
             font's zero — which changes with weight and letter-spacing, not
             just size. The heading is 600 with tracking, so the same 72ch
             resolved 34px wider on it than on the list underneath. --}}
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
