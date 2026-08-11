@php
    /*
     * The end of a post is the warmest a reader on this site ever gets: they
     * have just spent six minutes on the problem in their own business and are
     * still here. Until this existed the page answered that with a Copy URL
     * button, and three of the five posts made up for it with a hand-typed
     * line of markdown — which meant the other two had nothing, and no two
     * were worded the same.
     *
     * Defaults suit the posts written for business owners, since that is most
     * of them. A post with a different reader overrides the wording in its own
     * front matter, and `cta: false` removes it altogether rather than making
     * a developer read a pitch about booking a shop online.
     */
    $ctaTitle = $page->ctaTitle ?: 'Is this your business?';
    $ctaBody = $page->ctaBody ?: 'Describe what you are trying to do in a paragraph. You will get an honest answer about what it would take, and whether I am the right person for it.';
    $ctaAction = $page->ctaAction ?: 'Tell me what you need';

    /*
     * `ctaSecondary: false` removes the second button. Compared against null
     * rather than written as `?: '/zero-to-one/'`, which is what this was: with
     * a non-empty fallback the guard below could never be false, so a post that
     * asked for the button to be removed got it anyway.
     *
     * ltrim on the join because a value authored without a leading slash would
     * otherwise concatenate straight onto the host — `hesamrad.comprojects/`.
     */
    $ctaSecondary = $page->ctaSecondary === null ? '/zero-to-one/' : $page->ctaSecondary;
@endphp

<aside class="callout post-cta">
    <h2>{{ $ctaTitle }}</h2>

    <p>{{ $ctaBody }}</p>

    <div class="btn-row">
        <a class="btn btn--primary" href="{{ $page->baseUrl }}/#contact">
            <span>{{ $ctaAction }}</span>
            @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
        </a>

        @if ($ctaSecondary)
            <a class="btn btn--ghost" href="{{ $page->baseUrl }}/{{ ltrim($ctaSecondary, '/') }}">
                <span>{{ $page->ctaSecondaryLabel ?: 'A website in about a week' }}</span>
            </a>
        @endif
    </div>
</aside>
