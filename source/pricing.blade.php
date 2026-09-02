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
        <div class="section-head">
            <h2>A website.</h2>
            <p>A fixed price, because it's the same defined job most times somebody asks for it.</p>
        </div>

        <div class="price">
            <div class="price__figures">
                <div class="price__figure">
                    <span class="price__value tabular">{{ $page->priceSetup() }}</span>
                    <span class="price__label">Once, to build it and put it live</span>
                </div>
                <div class="price__figure">
                    <span class="price__value tabular">{{ $page->priceMonthly() }}<span class="price__unit">/mo</span></span>
                    <span class="price__label">To host it, keep it safe and keep it current</span>
                </div>
                <div class="price__figure">
                    <span class="price__value tabular">{{ $page->pricing['turnaround'] }}</span>
                    <span class="price__label">From our first call to being online</span>
                </div>
            </div>

            <dl class="price__terms">
                <div class="price__term">
                    <dt>What the {{ $page->priceSetup() }} covers</dt>
                    <dd>The pages your business needs, built around what you do and working properly on a phone. I
                        write the words from a half-hour call, so you never have to sit down and write about your own
                        business. The domain, the hosting and the security are set up and pointed at the right place,
                        and your Google listing is sorted out so people nearby can find you.</dd>
                </div>
                <div class="price__term">
                    <dt>What the {{ $page->priceMonthly() }} covers</dt>
                    <dd>Hosting, the domain renewal, security updates, backups, and small changes when you need them:
                        new opening hours, a price change, a few new photos. Email me and it gets done.</dd>
                </div>
                <div class="price__term">
                    <dt>If you want to stop</dt>
                    <dd>Then stop. There's no minimum term. The domain is registered to you, and I'll hand over
                        everything so you or anyone else can pick it up. Nothing in the paperwork keeps you here.</dd>
                </div>
                <div class="price__term">
                    <dt>What sits outside it</dt>
                    <dd>A visual identity invented from scratch, logos, branding and photography. Taking payments,
                        online ordering, booking systems and customer logins are all real work too, so they're quoted
                        separately. A fixed price only stays fixed when both of us can see where the edges are.</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="section section--band">
        <div class="shell">
            <div class="section-head">
                <h2>A web application.</h2>
                <p>No figure here, and be suspicious of anyone who gives you one this early.</p>
            </div>

            <div class="grid grid--halves">
                <article class="card">
                    <p class="card__label">Why there's no number on this page</p>
                    <h3 class="card__title">Nobody can price what nobody has worked out yet</h3>
                    <p class="card__body">An application is built around how your business actually runs, and no two
                        run the same way. A number quoted before anyone has looked at the problem is a guess wearing a
                        suit. It's also how projects end up costing double: the guess was low, the work was real, and
                        somebody has to pay for the difference.</p>
                    <p class="card__body">So the plan comes first, and the number comes with the plan.</p>
                </article>

                <article class="card">
                    <p class="card__label">What decides the number</p>
                    <h3 class="card__title">Four things, and you'll know all four</h3>
                    <p class="card__body">How many separate jobs the software has to do. How many kinds of people use
                        it, since staff, customers and admins each need their own screens. Whether it has to talk to
                        systems you already pay for. And how much of it has to be right on the first day rather than
                        the third month.</p>
                    <p class="card__body">The plan writes all four down in plain language, so you can see which one is
                        driving the cost and decide whether you still want it.</p>
                </article>
            </div>
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
