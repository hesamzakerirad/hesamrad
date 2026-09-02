---
title: Pricing
contactHeading: 'Tell me which of the two you need.'
contactIntro: "A paragraph about the business is enough. If it's a website you already have the number. If it's an application, I'll tell you what it would take to find out."
---

@extends('_layouts.main')

{{-- The title names both kinds of work and the money, because "Pricing" is
     not a phrase anybody searches for.

     Keep this under 48 characters. main.blade.php appends " - Hesam Rad" only
     while the whole title stays under 60, so a longer one drops the name off
     the search result without saying so. The name is the business here.

     The front matter `title` stays "Pricing". That one draws the breadcrumb,
     where a full sentence would be wrong. --}}
@section('title', 'What a Website and a Web Application Cost')

{{-- The figures belong in the description. The snippet is what somebody reads
     before deciding to click, and on a question about cost the answer is the
     reason to click.

     Read the amounts from `pricing`, the way the panels do. A number typed
     here would be a second source for a figure this site keeps in one place.

     Keep this close to the opening paragraph: a description that reads like a
     different page invites Google to write its own snippet out of the body
     copy, which is what it did with the earlier wording. --}}
@section('description', 'A website is ' . $page->priceSetup() . ' to build and ' . $page->priceMonthly() . ' a month to run. A web application is quoted as one fixed number once the plan is written, and the plan is free.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        {{-- The no-break space ties "a" to "website". Without it the line ends
             on the article and the noun starts the next one, which is the one
             break this heading must not take. The 22ch cap on `.page-head h1`
             is what puts the heading on two lines; do not raise the cap to
             avoid the wrap, mark the break here instead. --}}
        <h1>What it costs to build a&nbsp;website or a web application.</h1>

        {{-- Open on what the reader gets, not on how the page is arranged. The
             earlier version led with "two kinds of work, and they can't be
             priced the same way", which explains the layout to somebody who
             came here worried about the bill. --}}
        <p class="lead prose">You'll know what this costs before you're committed to anything. A website has a fixed
            price and it's on this page, so you can settle that question right now without speaking to me at all. A
            web application gets its number once I understand what you actually need, and working that out
            costs you nothing.</p>
    </div>

    <section class="shell section">
        {{-- The two panels carry the headings for this page. A `section-head`
             above them would name the pair, and the pair is the whole page. --}}
        <div class="plans">
            <article class="plan">
                <div class="plan__head">
                    <h2 class="plan__eyebrow" id="website">How much does a website cost?</h2>
                    <p class="plan__value tabular">{{ $page->priceSetup() }}</p>
                    <p class="plan__label">Once, to build it and put it live</p>
                </div>

                <dl class="plan__rows">
                    <div class="plan__row">
                        <dt class="tabular">{{ $page->priceMonthly() }}<span class="plan__unit">/mo</span></dt>
                        <dd>Hosting, domain, security updates, backups and small changes. No minimum term.</dd>
                    </div>
                    <div class="plan__row">
                        <dt class="tabular">{{ $page->pricing['turnaround'] }}</dt>
                        <dd>From our first call to being live.</dd>
                    </div>
                </dl>

                <div class="plan__body">
                    <p class="plan__note">What the {{ $page->priceSetup() }} covers</p>
                    <ul class="card__list card__list--yes">
                        <li>Any number of pages your business needs</li>
                        <li>Content writing and editing</li>
                        <li>Domain, hosting and security, all set up</li>
                        <li>Your Google listing, so people nearby can find you</li>
                    </ul>

                    <p class="plan__note">What sits outside it</p>
                    <ul class="card__list card__list--no">
                        <li>Logos, branding and photography</li>
                        <li>Payments, ordering, bookings and logins, quoted separately</li>
                    </ul>
                </div>
            </article>

            <article class="plan">
                <div class="plan__head">
                    <h2 class="plan__eyebrow" id="web-application">How much does a web application cost?</h2>
                    <p class="plan__value">Quoted</p>
                    <p class="plan__label">One fixed number, once the plan is written</p>
                </div>

                <dl class="plan__rows">
                    <div class="plan__row">
                        <dt class="tabular">{{ $page->priceMonthly() }}<span class="plan__unit">/mo</span></dt>
                        <dd>Hosting, domain, security updates, backups and small changes. No minimum term.</dd>
                    </div>
                    <div class="plan__row">
                        <dt class="tabular">~1 week</dt>
                        <dd>From our first call to the plan and number.</dd>
                    </div>
                </dl>

                <div class="plan__body">
                    {{-- Cost drivers a business owner can check against their
                         own situation, in the order they usually bite. The
                         earlier list said things like "how many jobs the
                         software has to do", which is how an engineer scopes a
                         build and not something the reader can answer. --}}
                    <p class="plan__note">What decides the number</p>
                    <ul class="card__list card__list--yes">
                        <li>Whether your customers use it, or only your staff</li>
                        <li>How many kinds of user, since each needs its own screens</li>
                        <li>What it has to connect to, like payments or accounting</li>
                        <li>How much existing data has to move across</li>
                    </ul>

                    {{-- Both of these used to be about how I charge, which the
                         hero, the FAQ and the card below already say. These are
                         the two ways an application build actually goes wrong.
                         Keep them at two items so the panel stays level with
                         the website one. --}}
                    <p class="plan__note">What I won't do</p>
                    <ul class="card__list card__list--no">
                        <li>Start building before the plan is written and agreed</li>
                        <li>Leave you with something only I can maintain</li>
                    </ul>
                </div>
            </article>
        </div>
    </section>

    <section class="shell section">
        <div class="section-head">
            <h2>How you get to a number.</h2>
            <p class="dim">Three steps, and you owe nothing until the end of the third.</p>
        </div>

        <ol class="steps">
            <li>
                <h3>A 30-minute call</h3>
                <p>You do most of the talking: what the business does, what isn't working, and what you want instead.
                    You don't need a specification or a list of features written beforehand.</p>
            </li>
            <li>
                <h3>A written plan, inside a week</h3>
                <p>What I'd build, in what order, how long each part takes, what it costs, and what I think could go
                    wrong. It's free and it's yours to keep, including to take to another developer.</p>
            </li>
            <li>
                <h3>You decide</h3>
                <p>One number for the work, fixed, in a contract we both sign. If it's more than you want to spend,
                    say so and we'll look at a smaller first version. If I'm not the right person for it, that's the
                    call where I say so.</p>
            </li>
        </ol>

        <div class="btn-row">
            <a class="btn btn--primary" href="{{ $page->bookingUrl }}" target="_blank" rel="noopener noreferrer">
                <span>Book a call</span>
                @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
            </a>
            <a class="btn btn--ghost" href="#contact">
                <span>Write to me instead</span>
            </a>
        </div>
    </section>

    <section class="section section--band">
        <div class="shell">
            <div class="section-head">
                <h2>True whichever one you buy.</h2>
                <p class="dim">Four things that don't change with the size of the job.</p>
            </div>

            @php
                $either = [
                    [
                        'title' => 'You own all of it, from day one',
                        'body' => 'The code lives in your repository, it runs on your hosting account, and the domain stays registered to you. I work inside your accounts, so on the last day there is nothing to pry loose and nothing of yours sitting in my name.',
                    ],
                    [
                        'title' => 'There is always a contract',
                        'body' => 'Signed by both of us before any work starts. You\'re hiring a person and not a company: I\'m a freelancer, I work for myself, and my name is the one on it.',
                    ],
                    [
                        'title' => 'The price does not move',
                        'body' => 'Once we\'ve agreed the work, the number is the number. If I underestimated it, that\'s mine to carry. I\'m the one who estimated it.',
                    ],
                    [
                        'title' => 'You can hand it to somebody else',
                        'body' => 'The setup runs from written instructions and the tests say what\'s broken. If handing the work on would be painful, I\'ve done it badly, whatever else is true about the software.',
                    ],
                ];
            @endphp

            @include('_components.card-grid', ['items' => $either, 'grid' => 'halves'])
        </div>
    </section>

    {{-- The website, as the thing this page is about.

         The Service is the node and the prices hang off it. The reverse, an
         Offer with the Service inside `itemOffered`, is legal schema and was
         what this page had, but it buries the Service as a blank node: nothing
         can reference it, /services/ cannot reuse it, and the page ends up
         about a price rather than about the work.

         The Service carries an `@id`, so structured-data.blade.php finds it
         and points `mainEntity` at it. Without that the node sits in the graph
         with nothing referring to it, and an unreferenced node is one a search
         engine is free to ignore.

         Two offers, because the page states two figures. One Offer with only
         the build price tells a crawler this is a one-time purchase, which is
         not what the page says. The monthly one uses a
         UnitPriceSpecification, because `price` alone cannot say "a month".
         `unitCode` MON is the UN/CEFACT code for a month.

         Only the website gets offers. The application has no figure, and an
         Offer with no price is worse than no Offer.

         `seller` on the Offer and `provider` on the Service. schema.org does
         not define `provider` on an Offer, and does not define `seller` on a
         Service. Both point at the Person the shared include declares, so the
         graph names one human once.

         The figures come from `pricing` in config.php, which is also what the
         panels above read. A search engine and a reader cannot be shown two
         different numbers.

         `priceValidUntil` is computed at build time and not written down. A
         date in a file goes stale the moment nobody remembers it is there;
         this one moves a year out on every deploy. --}}
    @php
        $offerPersonId = rtrim($page->baseUrl, '/') . '/#person';
        $offerValidUntil = date('Y-m-d', strtotime('+1 year'));

        $page->schemaNodes = [
            [
                '@type' => 'Service',
                '@id' => $page->getCanonicalUrl() . '#website-service',
                'name' => 'Website design and development',
                'description' => 'A website for a business: built, launched and looked after, at a fixed price.',
                'serviceType' => 'Website design and development',
                'url' => rtrim($page->baseUrl, '/') . '/services/',
                'provider' => ['@id' => $offerPersonId],
                // The work is remote and the client can be anywhere. Naming a
                // continent here would tell a search engine to stop offering
                // the page outside it, which is the opposite of the intent.
                //
                // A `Place` named "Worldwide" is the readable form and it
                // validates. The GeoShape box below is what actually says "the
                // whole planet" to a machine, and the two together mean neither
                // a person reading the source nor a crawler has to guess.
                'areaServed' => [
                    '@type' => 'Place',
                    'name' => 'Worldwide',
                    'geo' => [
                        '@type' => 'GeoShape',
                        'box' => '-90 -180 90 180',
                    ],
                ],
                'offers' => [
                    [
                        '@type' => 'Offer',
                        '@id' => $page->getCanonicalUrl() . '#offer-build',
                        'name' => 'Built and put live',
                        'url' => $page->getCanonicalUrl(),
                        'price' => (string) $page->pricing['setup'],
                        'priceCurrency' => $page->pricing['currency'],
                        'availability' => 'https://schema.org/InStock',
                        'priceValidUntil' => $offerValidUntil,
                        'seller' => ['@id' => $offerPersonId],
                    ],
                    [
                        '@type' => 'Offer',
                        '@id' => $page->getCanonicalUrl() . '#offer-care',
                        'name' => 'Hosting, security and small changes',
                        'url' => $page->getCanonicalUrl(),
                        'availability' => 'https://schema.org/InStock',
                        'seller' => ['@id' => $offerPersonId],
                        'priceSpecification' => [
                            '@type' => 'UnitPriceSpecification',
                            'price' => (string) $page->pricing['monthly'],
                            'priceCurrency' => $page->pricing['currency'],
                            'unitCode' => 'MON',
                            'billingDuration' => 1,
                            'billingIncrement' => 1,
                        ],
                    ],
                ],
            ],
        ];
    @endphp
@endsection
