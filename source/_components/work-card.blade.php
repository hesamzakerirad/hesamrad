{{--
    One case study, as a card. /work/ lists them all and /about/ shows a
    selection.

    Parameters:
      $study      — an item of the caseStudies collection
      $titleLevel — the heading level of the card title  (default 2)

    Set $titleLevel to 3 when a section heading is above the list. On /work/
    the <h1> is the page title, therefore the card title is an <h2>. On
    /about/ an <h2> introduces the list, therefore the card title is an <h3>.
--}}
@php
    // Use getCover() and do not read cover['src'] directly. Front matter writes
    // the cover as a bare URL and also as a map. A study that uses the URL form
    // shows no image.
    $cover = $study->getCover();
    $level = $titleLevel ?? 2;
@endphp

<article class="work-card">
    {{-- The image is decorative. The title anchor covers the whole card. --}}
    @if ($cover && $cover['src'])
        <img class="work-card__bg" src="{{ $cover['src'] }}" alt="" aria-hidden="true"
            loading="lazy" decoding="async" width="1600" height="900">
    @endif

    @if ($study->sample ?? false)
        <p class="case__flag">Invented sample, not real work</p>
    @endif

    <h{{ $level }} class="work-card__title">
        {{-- The ::after of this anchor covers the whole card. Keep one link
             only in the card. --}}
        <a href="{{ $study->getCanonicalUrl() }}">{{ $study->title }}</a>
    </h{{ $level }}>

    <p class="work-card__summary">{{ $study->summary }}</p>

    <p class="work-card__more">
        <span class="link-arrow">
            <span>Read the whole story</span>
            @include('_components.icon', ['name' => 'arrow-right'])
        </span>
    </p>
</article>
