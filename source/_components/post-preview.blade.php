{{-- The listing page is English but the posts it lists may not be, so each
     preview declares its own language and direction.

     No date. Four of the posts were published within weeks of each other, and a
     column of near-identical dates was the loudest thing in each row while
     telling a reader nothing they act on — they choose a post by its title. The
     order still carries the chronology, since the collection sorts by
     created_at, and the post itself still says when it was written. --}}
<article class="post-card {{ $post->isFeatured ? 'is-featured' : '' }}" lang="{{ $post->getLanguage() }}"
    dir="{{ $post->getDirection() }}">
    {{-- Real markup, not a CSS ::before on the heading. "Featured" is content:
         drawn from a stylesheet it exists nowhere in the document, and sitting
         on the <h2> it became part of that heading's accessible name, so a
         screen reader announced the heading as "Featured <title>". --}}
    @if ($post->isFeatured)
        <p class="post-card__flag">Featured</p>
    @endif

    {{-- The anchor's ::after covers the whole card, so this stays the one
         link in the row rather than nesting a second one around it. --}}
    <h2 class="post-card__title">
        <a href="{{ $post->getCanonicalUrl() }}">{{ $post->title }}</a>
    </h2>

    <p class="post-card__excerpt">{{ $post->getSummary(170) }}</p>

    <p class="post-card__foot">
        @if ($readTime = $post->getReadTime())
            <span class="meta">{{ $readTime }} min read</span>
        @endif

        {{-- A span, not a link. The title's anchor already covers the whole row
             via its ::after, so a second anchor here would either sit under
             that overlay and never be clickable, or be lifted above it and give
             the row two links to the same page — which a screen reader reads
             out twice and a keyboard stops at twice. This is the visible
             affordance for a link that already exists.

             Hidden from assistive tech for the same reason: the title is the
             accessible name of the link, and "Read more" adds nothing to it. --}}
        <span class="post-card__more" aria-hidden="true">
            Read more
            @include('_components.icon', ['name' => 'arrow-right'])
        </span>
    </p>
</article>
