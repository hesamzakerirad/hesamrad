{{--
    The questions that belong to the current page.

    main.blade.php includes this after the body of every page. The component
    reads the `page` key of each question in config.php and keeps the questions
    whose value is the path of this page. A page with no such question gets no
    markup: no heading, no empty container, no schema. Therefore a new block of
    questions on a page needs a `page` key in config.php and no change to a
    template.

    /faq/ shows nothing from here. Its questions carry no `page` key, and that
    page renders them itself, in groups, with its own schema.

    The path comparison removes the leading and the trailing slash from both
    values. `getPath()` adds a trailing slash only when `trailing_slash` is on
    in the configuration, therefore a comparison of the raw values fails when
    that setting changes. The home page gives an empty path, and '/' in the
    configuration also becomes empty.
--}}
@php
    $currentPath = trim($page->getPath(), '/');

    $pageQuestions = collect($page->siteFaq)
        ->filter(fn ($question) => isset($question['page']) && trim($question['page'], '/') === $currentPath)
        ->values();

    /*
     * A post with a `faq:` block in its front matter already declares FAQPage,
     * from post.blade.php, under the same `@id`. A second node here would give
     * one address two FAQPage nodes with one identifier, and that is not valid
     * data. The questions stay visible and this page adds no node.
     */
    $ownsSchema = ! $page->faq;
@endphp

@if ($pageQuestions->isNotEmpty())
    <div class="shell section">
        <div class="section-head">
            <h2>Questions</h2>
        </div>

        @include('_components.faq-list', ['items' => $pageQuestions])

        {{-- This page shows the questions that belong to it. /faq/ holds every
             question the site answers, so a reader who wants more has one
             place to go.

             The link is safe on every page that includes this component. /faq/
             itself never reaches here, because its own questions carry no
             `page` key and `$pageQuestions` is therefore empty, so the block
             above skips and this link cannot point at the page it is on.

             The visible text is short by design. The label spells it out for a
             reader listing the links on the page, where "View more" on its own
             says nothing. --}}
        <p class="faq__more">
            <a class="link-arrow" href="{{ $page->baseUrl }}/faq/" aria-label="View more questions">
                <span>View more</span>
                @include('_components.icon', ['name' => 'arrow-right'])
            </a>
        </p>
    </div>

    @if ($ownsSchema)
        @push('scripts')
            @include('_components.faq-schema', ['items' => $pageQuestions])
        @endpush
    @endif
@endif
