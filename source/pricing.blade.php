---
title: Pricing
contactHeading: 'Tell me which of the two you need.'
contactIntro: "A paragraph about the business is enough. If it's a website you already have the number. If it's an application, I'll tell you what it would take to find out."
---

@extends('_layouts.main')

@section('title', 'Pricing')

{{-- Two figures and one refusal to give a figure. Keep this description close
     to the opening paragraph: a description that reads like a different page
     invites Google to write its own snippet out of the body copy. --}}
@section('description', 'A website is a fixed price, published here. A web application is quoted as one number after the plan is written, because any figure before that is a guess.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>What it costs.</h1>

        <p class="lead prose">Two kinds of work, and they can't be priced the same way. A website is a known job, so
            it has a number and the number is on this page. A web application is built around how one business runs,
            so it gets a number once somebody has worked out what it involves. That someone is me, it takes a week,
            and it costs you nothing.</p>

        <p class="lead prose">What I don't do is charge by the hour. You'd be paying for my time instead of the thing
            you wanted, and I'd be the one holding the stopwatch.</p>
    </div>

    <section class="shell section">
        {{-- The two panels carry the headings for this page. A `section-head`
             above them would name the pair, and the pair is the whole page. --}}
        <div class="plans">
            <article class="plan">
                <div class="plan__head">
                    <h2 class="plan__eyebrow" id="website">A website</h2>
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
                        <li>Pages built for your business, and good on a phone</li>
                        <li>I write the words, from one half-hour call</li>
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
                    <h2 class="plan__eyebrow" id="web-application">A web application</h2>
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
                        <dd>From our first call to the plan and the number.</dd>
                    </div>
                </dl>

                <div class="plan__body">
                    <p class="plan__note">What decides the number</p>
                    <ul class="card__list card__list--yes">
                        <li>How many jobs the software has to do</li>
                        <li>How many kinds of user, since each needs its own screens</li>
                        <li>Whether it talks to systems you already pay for</li>
                        <li>How much has to be right on day one</li>
                    </ul>

                    <p class="plan__note">What I won't do</p>
                    <ul class="card__list card__list--no">
                        <li>Quote a figure before anyone has looked at the problem</li>
                        <li>Bill by the hour and let the meter decide</li>
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
                <h3>A half-hour call</h3>
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

    {{-- The Offer for a website, on the one page that states the price.
         structured-data.blade.php runs on every page and therefore carries no
         price: a price on /about/ or on a post is a price for a service that
         page does not describe.

         Only the website gets an Offer. An application has no figure, and an
         Offer with no price is worse than no Offer.

         `seller`, not `provider`. schema.org does not define `provider` on an
         Offer: it belongs to a Service or another CreativeWork. `seller` names
         who is offering, and it points at the Person node the shared include
         declares, so the two agree without repeating the business. The Service
         inside `itemOffered` keeps `provider`, which is valid there.

         The figures come from `pricing` in config.php, which is also what the
         price block above reads. A search engine and a reader thus cannot be
         shown two different numbers.

         The node goes on `$page->schemaNodes`, which the shared include folds
         into the one @graph in the head. A second <script> would put `seller`
         in one block and the Person it names in another, and a search engine
         reads each block on its own. --}}
    @php
        $offerPersonId = rtrim($page->baseUrl, '/') . '/#person';

        $page->schemaNodes = [
            [
                '@type' => 'Offer',
                '@id' => $page->getCanonicalUrl() . '#offer',
                'url' => $page->getCanonicalUrl(),
                'price' => (string) $page->pricing['setup'],
                'priceCurrency' => $page->pricing['currency'],
                'seller' => ['@id' => $offerPersonId],
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => 'Website design and development',
                    'description' => 'A website for a business: built, launched and looked after, at a fixed price.',
                    'serviceType' => 'Website design and development',
                    'url' => $page->getCanonicalUrl(),
                    'provider' => ['@id' => $offerPersonId],
                ],
            ],
        ];
    @endphp
@endsection
