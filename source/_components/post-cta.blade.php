@php
    /*
     * The call to action at the end of a post.
     *
     * Front matter keys, all optional:
     *   ctaTitle          — the heading
     *   ctaBody           — the paragraph
     *   ctaAction         — the label of the first button
     *   ctaSecondary      — the target of the second button, or false
     *   ctaSecondaryLabel — the label of the second button
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
