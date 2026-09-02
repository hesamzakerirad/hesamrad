---
title: Services
contactHeading: 'Describe the problem in a paragraph.'
contactIntro: "That's enough for me to tell you whether this is a week of work or a quarter, and whether I'm the right person for it."
---

@extends('_layouts.main')

@section('title', 'Services')

{{-- State what the page offers. A description that only lists the page's own
     headings gets replaced by a sentence Google picks off the page. --}}
@section('description', 'You get one engineer who builds all of it: the screens your customers use, the system behind them, and the work that keeps it running.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Bespoke web development</h1>
        <p class="lead prose">Software built for one business: yours. The screens your customers use, the system
            running behind them, and everything needed to keep it working. I build all of it myself, and I'm still
            here when it needs changing.</p>

        <p class="mt-md"><span class="availability">Available for new projects</span></p>
    </div>

    <div class="shell section">
        <div class="section-head">
            <h2>What I do, and what I don't.</h2>
            <p>Hiring the wrong person costs you a month. Here is the line, so you know which side your job falls on
                before you write to me.</p>
        </div>

        @php
            $laravel = '<a href="https://laravel.com" target="_blank" rel="noopener noreferrer">Laravel</a>';
            $react = '<a href="https://react.dev" target="_blank" rel="noopener noreferrer">React</a>';

            // The `does` items hold markup and print unescaped. Write them here
            // and nowhere else, and do not put anything from a form in them.
            $does = [
                'Websites and web applications built with ' . $laravel . ' and ' . $react,
                'Looking after a ' . $laravel . ' application you already have',
                'Bespoke admin dashboards to run the business from',
                'Turning a manual process into software',
            ];

            $doesNot = [
                'Mobile or desktop apps',
                'WordPress, Shopify, and Wix',
                'Marketing, SEO, and social media management',
                'Design and branding',
            ];
        @endphp

        <div class="grid grid--halves mt-lg">
            <article class="card">
                <h3 class="card__title">What I do</h3>

                {{-- The span keeps the item as one flex item. Without it, a link
                     inside the text makes a second item and the row gap opens a
                     hole in the sentence. --}}
                <ul class="card__list card__list--yes">
                    @foreach ($does as $item)
                        <li><span>{!! $item !!}</span></li>
                    @endforeach
                </ul>
            </article>

            <article class="card">
                <h3 class="card__title">What I don't</h3>

                <ul class="card__list card__list--no">
                    @foreach ($doesNot as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </article>
        </div>
    </div>

    <div class="shell section">
        <div class="grid grid--halves">
            <div class="section-head section-head--start">
                <h2>What working with me is like.</h2>
                <p class="dim">You should know how this goes before you commit to it. Nothing here waits until after
                the contract is signed to show up. If you're comparing this against
                <a href="/blog/agency-or-one-independent-engineer/">hiring an agency</a>, that post lays out both
                sides.</p>
            </div>

            <ol class="steps">
                <li>
                <h3>A call, then a written plan</h3>
                    <p>Thirty minutes on a call to understand the business and the problem. You get back a written
                        plan: what I'd build, in what order, what it costs, and what I think could go wrong.</p>
                    <p>The plan is yours to keep whether or not you hire me. If you take it to another developer,
                        that's a perfectly good outcome and it costs you nothing.</p>
                </li>
                <li>
                <h3>Weekly, visible progress</h3>
                <p>You see the real thing every week, in a browser. Not a screenshot of it, and no month-long
                        silence ending in a reveal.</p>
                </li>
                <li>
                <h3>I hand it over properly</h3>
                <p>Everything written down, a walkthrough with whoever will be looking after it, and a system that
                        runs without me. The handover is part of the job and it's priced that way.</p>
                </li>
                <li>
                <h3>A support window afterwards</h3>
                <p>An agreed period where defects in what I built are fixed at no extra cost. It's what standing
                        behind the work has to mean if the phrase is worth anything.</p>
                </li>
            </ol>
        </div>
    </div>

    {{-- The price comes last, after the work is described and after how the
         work goes. A reader who has got this far knows what they would be
         buying, which is the point at which a number is information rather
         than a filter.

         The home page deliberately does not carry this button. "Pricing" is
         already in the nav on every page, so a hero button would duplicate it
         and put the cost in front of somebody who has not yet been given a
         reason to want the thing.

         No figure here. /pricing/ owns every amount the site publishes and
         this block links to it. --}}
    <div class="shell section">
        {{-- `callout` and not `section-head`. A section head is centered text
             on the page background, which leaves a closing block sitting in
             empty space. The callout is the surface this site already uses to
             close /about/ and /projects/, and two buttons fill it the way they
             do there. --}}
        <div class="callout">
            <h2>What it costs.</h2>
            <p>A website has a fixed price and it's published, so you can settle that question without speaking to
                me. An application is quoted as one number once the plan is written, and the plan costs you nothing
                either way.</p>

            <div class="btn-row">
                <a class="btn btn--primary" href="{{ $page->baseUrl }}/pricing/">
                    <span>See the numbers</span>
                    @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                </a>
                <a class="btn btn--ghost" href="{{ $page->bookingUrl }}" target="_blank" rel="noopener noreferrer">
                    <span>Book a call</span>
                </a>
            </div>
        </div>
    </div>

    {{-- The questions for this page are not here. main.blade.php puts them below
         the body and above the contact block. To add one, put
         `page => '/services/'` on a question in config.php. Do not add an include
         here, and do not repeat a question that /faq/ already shows. --}}

    @include('_components.reviews', ['only' => 'clients', 'limit' => 2])

    {{-- The services, as structured data, on the one page that describes them.

         These left structured-data.blade.php, which runs on every page. An
         offer catalog on a post or on the 404 page describes a service that
         page does not sell, and the catalog held one Offer that stated no
         price, which a validator reports as a missing field.

         Each service names the Person node from the shared include as its
         provider. One person is the whole business, and `provider` accepts a
         Person.

         Every entry here must match an item in the visible `What I do` list
         above. A service in the markup that the page does not name is a claim
         with no page behind it. No entry carries a price: the site publishes
         one price, it is a Zero to One price, and it belongs on that page.

         The nodes go on `$page->schemaNodes`, which the shared include folds
         into the one @graph in the head. They were a second <script> of their
         own, and the `provider` reference then pointed at a Person node in
         another block, which a search engine reads as a separate document.

         There is no `areaServed` here. The rule the shared include states is
         that a node may only make a claim the page also makes in its text, and
         this page names no region anywhere in its copy. /faq/ is where the site
         says Europe and North America, in prose. --}}
    @php
        $servicePageUrl = $page->getCanonicalUrl();

        $offered = [
            [
                'slug' => 'web-applications',
                    'name' => 'Web application development',
                    'serviceType' => 'Web application development',
                'description' => 'Applications that live on the internet, built with Laravel and Next.js. The screens your customers use and the system behind them, both from the same person.',
            ],
            [
                'slug' => 'application-maintenance',
                'name' => 'Laravel application maintenance',
                'serviceType' => 'Software maintenance',
                'description' => 'Looking after a Laravel application that already exists: repairing what breaks, adding what it needs, and keeping it current.',
            ],
            [
                'slug' => 'admin-dashboards',
                'name' => 'Bespoke admin dashboards',
                'serviceType' => 'Custom software development',
                'description' => 'A dashboard built for the way one business runs, so the people in it stop working out of spreadsheets.',
            ],
            [
                'slug' => 'process-automation',
                'name' => 'Process automation',
                'serviceType' => 'Business process automation',
                'description' => 'Turning a manual process into software, so work that people repeat by hand happens on its own.',
            ],
        ];

        $page->schemaNodes = $page->isNoIndex() ? [] : collect($offered)->map(fn ($service) => [
            '@type' => 'Service',
            '@id' => $servicePageUrl . '#' . $service['slug'],
            'name' => $service['name'],
            'description' => $service['description'],
            'serviceType' => $service['serviceType'],
            'provider' => ['@id' => rtrim($page->baseUrl, '/') . '/#person'],
            // `subjectOf`, not `mainEntityOfPage`. The second is the inverse of
            // `mainEntity` and names the one primary subject of a page. Four
            // services each claiming it gave the page four primary subjects.
            // `subjectOf` states that the page describes the service and claims
            // nothing exclusive.
            'subjectOf' => ['@id' => $servicePageUrl . '#webpage'],
        ])->all();

        // Each service has an identifier but no address of its own, so the node
        // carries no `url`. All four pointed at this page before, which gave
        // four nodes one address and left them distinguishable only by a
        // fragment that no `url` resolved to.
    @endphp
@endsection
