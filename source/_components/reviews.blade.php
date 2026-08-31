{{--
    A set of reviews.

    Two sources feed this component and neither is a copy of the other:

      - Client reviews live in the `review` key of a case study in
        source/_caseStudies/. A client review and the work it describes belong
        together, therefore the case study owns the text and this component
        reads it. Editing the case study is the only way to change one.
      - Colleague reviews live in `reviews` in config.php, quoted from LinkedIn.

    Clients come first. A visitor deciding whether to hire me is asking what
    the people who paid for the work say, and a colleague review answers a
    different question.

    The component renders nothing when there are no reviews.

    Parameters:
      $heading — section heading                          (a default is below)
      $only    — 'clients' drops the colleague reviews. Omit it for every kind.
      $limit   — show only the first N reviews
      $more    — 'page' links to /reviews/, 'linkedin' links to the profile,
                 false shows no link                            (default 'page')
      $band    — draw the section on the raised surface        (default true)

    `$only` and `$limit` do different jobs and a page that wants client reviews
    needs the first. `limit => 2` today happens to give two clients because
    clients sort first, and it would quietly show a colleague the day a client
    review is removed.

    Set `$band` to false when the section above this one is already a band. Two
    bands together make one block of color with no edge between them.
--}}
@php
    /*
     * Client reviews, taken from the case studies. `$caseStudies` is already
     * filtered in config.php: a sample never reaches a public build, therefore
     * a sample review cannot appear here either.
     */
    $clientReviews = collect($caseStudies ?? [])
        ->filter(fn ($study) => !empty($study->review['quote'] ?? null))
        ->sortByDesc('year')
        ->map(function ($study) use ($page) {
            $review = $study->review;

            /*
             * The link to the work is only offered when the work is public.
             * With `workIsPublic` false the case studies are noindex and out of
             * the navigation, and a link here would point at a page the rest of
             * the site is hiding. The review itself stays: it is true whether
             * or not the portfolio is on display.
             */
            if ($page->workIsPublic) {
                $review['studyUrl'] = $page->baseUrl . '/work/' . $study->getFilename() . '/';
                $review['studyTitle'] = $study->title;
            }

            return $review;
        })
        ->values();

    /*
     * Clients first. The order is this concatenation and nothing else, so a
     * review carries no key saying which kind it is. `relationship` already
     * says it, in the words a reader sees.
     */
    $clientsOnly = ($only ?? null) === 'clients';

    $everyReview = $clientReviews->concat(collect($page->reviews ?? []));
    $reviews = $clientsOnly ? $clientReviews : $everyReview;

    /*
     * The placeholder reviews show the layout in a local build. `production` is
     * false in config.php and true in config.production.php. Therefore
     * `jigsaw build production` does not emit these reviews.
     */
    $showingPlaceholders = ! $page->production && $reviews->isEmpty();

    if ($showingPlaceholders) {
        $reviews = collect([
            [
                'quote' => 'He took a system nobody wanted to touch and made it something the team could change without holding its breath. Six months later we were shipping on a Friday afternoon, which had been unthinkable before.',
                'name' => 'Sample Person',
                'role' => 'Engineering Manager, Placeholder Ltd',
                'relationship' => 'Client',
                'url' => '',
            ],
            [
                'quote' => 'The thing that stood out was how much he wrote down. When he moved on, nothing stopped working and nobody had to reverse-engineer anything.',
                'name' => 'Example Colleague',
                'role' => 'Product Lead, Sampleton Software',
                'relationship' => 'Colleague',
                'url' => '',
            ],
            [
                'quote' => 'He said no to two things I asked for and was right about both. That is rarer and more useful than it sounds.',
                'name' => 'Placeholder Founder',
                'role' => 'Founder, Exampleford Co',
                'relationship' => 'Client',
                'url' => '',
            ],
        ]);

        if ($clientsOnly) {
            $reviews = $reviews->where('relationship', 'Client')->values();
        }

        $everyReview = $reviews;
    }

    /*
     * The count is of every review on /reviews/ and not of the ones this
     * section shows, because /reviews/ is where the link sends the reader. No
     * label prints the figure: it only decides whether the link is worth
     * offering, and a clients-only section of two still has four to point at.
     */
    $total = $everyReview->count();

    if (isset($limit)) {
        $reviews = $reviews->take($limit);
    }

    $more = $more ?? 'page';
    $hiddenCount = $total - $reviews->count();
@endphp

@if ($reviews->isNotEmpty())
    {{-- The inner <div class="shell"> sets the width. Do not add `shell` here. --}}
    <section class="section {{ ($band ?? true) ? 'section--band' : '' }}">
        <div class="shell">
            <div class="section-head">
                {{-- The default follows what the section was asked to show. A
                     clients-only set under "people who have worked with me"
                     reads as colleagues and drops the part that matters to a
                     buyer, which is that these people hired me. --}}
                <h2>{{ $heading ?? ($clientsOnly ? 'Clients, in their own words.' : 'What people who have worked with me say.') }}</h2>
            </div>

            @if ($showingPlaceholders)
                <p class="sample-notice" role="status">
                    <strong>Local preview.</strong> These reviews are invented placeholders for judging the layout.
                    Nobody said any of this, and none of it is in the published site.
                </p>
            @endif

            <div class="reviews">
                @foreach ($reviews as $index => $review)
                    @include('_components.review', ['review' => $review, 'variant' => 'card', 'index' => $index])
                @endforeach
            </div>

            {{-- The link is offered only when /reviews/ holds something this
                 section is not already showing, because "View more" promises
                 more and a page showing every review has none to give. --}}
            @if (! $showingPlaceholders && $more === 'page' && $hiddenCount > 0)
                <p class="reviews__more">
                    {{-- The visible text is short by design. The label spells
                         it out for a reader listing the links on the page,
                         where "View more" on its own says nothing. --}}
                    <a class="link-arrow" href="{{ $page->baseUrl }}/reviews/" aria-label="View more reviews">
                        <span>View more</span>
                        @include('_components.icon', ['name' => 'arrow-right'])
                    </a>
                </p>
            @elseif (! $showingPlaceholders && $more === 'linkedin' && $page->reviewsUrl)
                <p class="reviews__more">
                    <a class="link-arrow" href="{{ $page->reviewsUrl }}"
                        target="_blank" rel="noopener noreferrer">
                        <span>See more on LinkedIn</span>
                        @include('_components.icon', ['name' => 'external'])
                    </a>
                </p>
            @endif
        </div>
    </section>
@endif
