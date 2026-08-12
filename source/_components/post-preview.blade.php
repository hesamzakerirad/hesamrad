{{-- The listing page is in English, but a post can be in a different language.
     Therefore each preview gives its own `lang` and `dir`. --}}
<article class="post-card {{ $post->isFeatured ? 'is-featured' : '' }}" lang="{{ $post->getLanguage() }}"
    dir="{{ $post->getDirection() }}">
    {{-- Keep "Featured" as markup. Do not draw it with a CSS ::before on the
         <h2>. Content from a stylesheet is not in the document. Also, a
         ::before on the <h2> becomes part of the accessible name of the
         heading, and a screen reader then says "Featured <title>". --}}
    @if ($post->isFeatured)
        <p class="post-card__flag">Featured</p>
    @endif

    {{-- The ::after of this anchor covers the full card. Keep this anchor as
         the only link in the card. --}}
    <h2 class="post-card__title">
        <a href="{{ $post->getCanonicalUrl() }}">{{ $post->title }}</a>
    </h2>

    <p class="post-card__excerpt">{{ $post->getSummary(170) }}</p>

    <p class="post-card__foot">
        @if ($readTime = $post->getReadTime())
            <span class="meta">{{ $readTime }} min read</span>
        @endif

        {{-- Keep this element a <span>. Do not make it a link. The ::after of
             the title anchor covers the full card. A second anchor stays below
             that overlay and does not operate. Above the overlay, it gives the
             card two links to the same page. A screen reader then speaks the
             same target two times, and the keyboard stops two times.

             Keep `aria-hidden`. The title is the accessible name of the link,
             and "Read more" adds nothing. --}}
        <span class="post-card__more" aria-hidden="true">
            Read more
            @include('_components.icon', ['name' => 'arrow-right'])
        </span>
    </p>
</article>
