@php
    /*
     * The call to action at the end of a post.
     *
     * Front matter keys, all optional:
     *   ctaTitle          — the heading
     *   ctaBody           — the paragraph
     *   ctaBooking        — false removes the booking button
     *   ctaAction         — the label of the written-request button
     *   ctaSecondary      — the target of the last button, or false
     *   ctaSecondaryLabel — the label of the last button
     *
     * Each default is below. The layout, and not this component, reads
     * `cta: false` to remove the full block.
     */
    $ctaTitle = $page->ctaTitle ?: 'Is this your business?';
    $ctaBody = $page->ctaBody ?: 'Describe what you\'re trying to do in a paragraph. I\'ll tell you what it would take, and whether I\'m the right person for it.';
    $ctaAction = $page->ctaAction ?: 'Tell me what you need';

    /*
     * `ctaSecondary: false` removes the second button.
     *
     * Compare `ctaSecondary` against null. Do not write `?: '/zero-to-one/'`. A
     * fallback that is not empty makes the guard below always true, and the
     * second button then shows on each post.
     *
     * Keep the ltrim below. A value without a leading slash joins directly to
     * the host, for example `hesamrad.comprojects/`.
     */
    $ctaSecondary = $page->ctaSecondary === null ? '/zero-to-one/' : $page->ctaSecondary;

    /*
     * The first button is the booking button on each post. A post sets
     * `ctaBooking: false` to ask for a written request in its place. The two
     * never show together: one clear first step reads better than a choice
     * between two.
     *
     * Compare against null for the same reason as above.
     */
    $ctaBooking = $page->ctaBooking === null ? true : $page->ctaBooking;
@endphp

<aside class="callout post-cta">
    <h2>{{ $ctaTitle }}</h2>

    <p>{{ $ctaBody }}</p>

    <div class="btn-row">
        @if ($ctaBooking)
            <a class="btn btn--primary" href="{{ $page->bookingUrl }}" target="_blank" rel="noopener noreferrer">
                <span>Book a call</span>
                @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
            </a>
        @else
            <a class="btn btn--primary" href="{{ $page->baseUrl }}/#contact">
                <span>{{ $ctaAction }}</span>
                @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
            </a>
        @endif

        @if ($ctaSecondary)
            <a class="btn btn--ghost" href="{{ $page->baseUrl }}/{{ ltrim($ctaSecondary, '/') }}">
                <span>{{ $page->ctaSecondaryLabel ?: 'A website in about two weeks' }}</span>
            </a>
        @endif
    </div>
</aside>
