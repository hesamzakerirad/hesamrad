---
title: Zero to One
contactHeading: 'Tell me about the business.'
contactIntro: "A couple of sentences is plenty: what you do and roughly where. I'll tell you whether Zero to One is right for you, and if it isn't, what would be."
---

@extends('_layouts.main')

@php
    /*
     * The campaign switch.
     *
     * `false` keeps this URL at 200 and puts a short holding page here: no
     * price, no turnaround, no campaign name, and noindex. The URL stays alive
     * on purpose. It is indexed, links point at it, and the campaign comes
     * back to this same address.
     *
     * Front matter cannot read the switch. The values that name the campaign
     * are therefore reassigned below. case-study.blade.php assigns `robots` in
     * the same way.
     *
     * The noindex is also what keeps the URL out of the sitemap.
     * GenerateSitemap.php reads this value and drops the page for itself.
     */
    $campaignIsLive = $page->campaignIsLive;

    if (! $campaignIsLive) {
        $page->robots = 'noindex,nofollow';
        $page->title = 'A Website for Your Business';
        $page->contactIntro = 'A couple of sentences is plenty: what you do and roughly where. I\'ll tell you what it would take, and what it would cost.';
    }

    $target = 25;

    // Add a business here only after its website is live.
    $businesses = [
        // ['name' => '', 'trade' => '', 'town' => '', 'url' => '', 'launched' => 'YYYY-MM'],
    ];

    /*
     * Placeholders show the list and the tally with content in them. These
     * businesses do not exist.
     *
     * The placeholders show in a local build only, because the `production`
     * flag is false in config.php and true in config.production.php. A
     * `jigsaw build production` build does not contain them.
     *
     * The markup also marks them as placeholders. A local screenshot must not
     * look like a list of real clients.
     */
    $placeholders = [
        // ['name' => 'Sample Bakery', 'trade' => 'Bakery and coffee shop', 'town' => 'Sampleton', 'url' => '#', 'launched' => '2026-08'],
        // ['name' => 'Sample Joinery', 'trade' => 'Carpentry and fitted furniture', 'town' => 'Exampleford', 'url' => '#', 'launched' => '2026-08'],
        // ['name' => 'Sample Dental', 'trade' => 'Dental practice', 'town' => 'Placeholderton', 'url' => '#', 'launched' => '2026-09'],
    ];

    $launched = collect($businesses);

    /*
     * The last condition is necessary. If $placeholders is empty, the notice
     * tells the visitor about businesses that are not on the page.
     */
    $showingPlaceholders = ! $page->production
        && $launched->isEmpty()
        && $placeholders !== [];

    if ($showingPlaceholders) {
        $launched = $launched->concat($placeholders);
    }

    $showTally = $launched->count() >= 3;
@endphp

@section('title', $campaignIsLive ? 'Zero to One: A Website in About Two Weeks' : 'A Website for Your Business')

{{-- Keep this close to the opening paragraph on the page. A description that
     reads like a different page invites Google to write its own snippet out of
     the body copy, which is what it did with the earlier wording.

     The holding page carries its own description. The page is noindex, so no
     search result uses it, but an empty section makes the layout fall back to
     the body text and a link preview then quotes the holding note. --}}
@section('description', $campaignIsLive
    ? 'Zero to One gets ' . $target . ' businesses with no website online, properly. Yours is built around what you do, at a fixed price, in about two weeks.'
    : 'A website for a business that has none: built, launched and looked after. Tell me about the business and I will give you a price and a date.')

@section('body')
    @if ($campaignIsLive)
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Zero to One.</h1>

        <p class="lead prose">Zero to One is a campaign with one aim: get {{ $target }} businesses that have no website
            online, properly. Yours is built around what you do, at a fixed price, in about two weeks. I handle every part
            of it, including writing the words.</p>

        <p class="lead prose">Plenty of good businesses still don't have one. Usually it isn't that they decided
            against it. It's one more thing to sort out, and it never quite gets to the top of the list.</p>

        @if ($showTally)
            <p class="mt-md"><span class="availability">{{ $launched->count() }} of {{ $target }} online so far</span></p>
        @endif

        @if ($showingPlaceholders)
            <p class="sample-notice mt-md" role="status">
                <strong>Local preview.</strong> The businesses listed below are invented placeholders so the layout can
                be judged with something in it. None of them exist, and none of them are in the published site.
            </p>
        @endif
    </div>

    <section class="section section--band">
        <div class="shell">
            <div class="section-head">
                <h2>What you get.</h2>
                <p>The same list every time. That's what keeps it quick, and what keeps it {{ $page->priceSetup() }} instead of five.</p>
            </div>

            @php
                $included = [
                    [
                        'title' => 'A website, with the pages you need',
                        'body' => 'Built for your business, in your colors and with your photographs. It works properly on a phone, because that\'s where most of your customers will see it.',
                    ],
                    [
                        'title' => 'I write the words',
                        'body' => 'We talk for half an hour about what you do, and I write the site from that. You don\'t have to sit down and write anything yourself. That\'s the step that stalls most websites for months.',
                    ],
                    [
                        'title' => 'The domain, hosting and security',
                        'body' => 'Bought, set up and pointed at the right place. You never have to learn what any of those words mean. The domain is registered to you, not to me.',
                    ],
                    [
                        'title' => 'Findable on Google',
                        'body' => 'Your Google Business Profile set up or cleaned up, so you turn up in the map when someone nearby searches for what you do. For a lot of businesses this matters more than the website itself.',
                    ],
                ];
            @endphp

            @include('_components.card-grid', ['items' => $included, 'grid' => 'halves'])
        </div>
    </section>

    <section class="shell section">
        <div class="section-head">
            <h2>What it costs.</h2>
            <p>Two numbers, both of them the whole number. Nothing gets added later.</p>
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
                    <dt>What the {{ $page->priceMonthly() }} covers</dt>
                    <dd>Hosting, the domain renewal, security updates, backups, and small changes when you need
                        them: new opening hours, a price change, a few new photos. Email me and it gets done.</dd>
                </div>
                <div class="price__term">
                    <dt>If you want to stop</dt>
                    <dd>Then stop. There's no minimum term. The domain is registered to you, and I'll hand over
                        everything so you or anyone else can pick it up. Nothing in the paperwork keeps you here.</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="section section--band">
        <div class="shell">
            <div class="grid grid--halves">
                <div class="section-head section-head--start">
                    <h2>How the two weeks go.</h2>
                    <p class="dim">Four steps, and only the first one needs anything from you.</p>
                </div>

                <ol class="steps">
                    <li>
                        <h3>A half-hour call</h3>
                        <p>You tell me what the business does, who your customers are, and what you want people to do
                            when they find you. That's the only homework there is.</p>
                    </li>
                    <li>
                        <h3>I build it</h3>
                        <p>I write the words, put the site together, and send you a link to look at. Usually two or
                            three days after the call.</p>
                    </li>
                    <li>
                        <h3>You tell me what is wrong</h3>
                        <p>One round of changes, and I'd rather you were blunt about it. If a photo is bad or a
                            sentence isn't how you'd say it, tell me.</p>
                    </li>
                    <li>
                        <h3>It goes live</h3>
                        <p>Domain, hosting, Google, all handled. Then it is running and I keep it that way.</p>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="shell section">
        <div class="section-head">
            <h2>What is not in it.</h2>
            <p>Written down, because a fixed price only stays fixed if everyone can see where the edges are.</p>
        </div>

        <div class="grid grid--halves">
            <article class="card">
                <p class="card__label">Not included</p>
                <h3 class="card__title">The things a fixed price cannot carry</h3>
                <p class="card__body">A visual identity invented from scratch. The site is built for your business, but
                    the look isn't designed from nothing. That's a separate job at a separate price. Logos, branding
                    and photography aren't part of it either. If you have a logo I'll use it; if you don't, the site
                    works fine without one.</p>
            </article>

            <article class="card">
                <p class="card__label">Possible, priced separately</p>
                <h3 class="card__title">Bigger things are still on the table</h3>
                <p class="card__body">Taking payments, online ordering, a booking system, a customer login, something
                    built around how your business runs. I do all of that. It's proper work rather than a box to tick,
                    so it sits outside the {{ $page->priceSetup() }}.</p>
                <p class="card__body">Tell me what you have in mind on the call and I'll give you a real number.
                    Sometimes the answer is that you don't need it yet, and I'll say that too.</p>
            </article>
        </div>
    </section>

    <section class="shell section">
        <div class="section-head">
            <h2>From Zero to One</h2>
            <p>Shops, workshops and practices: places with a front door. Open any of them and see.</p>
        </div>

        @if ($launched->isEmpty())
            <div class="empty-state">
                <p class="lead prose dim">The first businesses are being set up now, and each one gets listed here as it goes live. If you want yours among them, take half an hour and tell me about it. The form below does the same job if you'd rather write.</p>

                <div class="btn-row">
                    <a class="btn btn--primary" href="{{ $page->bookingUrl }}" target="_blank"
                        rel="noopener noreferrer">
                        <span>Book a call</span>
                        @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                    </a>
                </div>
            </div>
        @else
            <div class="biz-list">
                @foreach ($launched as $business)
                    {{-- Apply the dark treatment only to a card that has a
                         screenshot. The scrim makes white text readable on a
                         photograph. A card with no photograph becomes almost
                         black on a white page. --}}
                    <article class="biz-card {{ !empty($business['cover']) ? 'biz-card--covered' : '' }}">
                        @if (!empty($business['cover']))
                            {{-- The image is decorative. The name below links to
                                 the same place. --}}
                            <img class="biz-card__bg" src="{{ $business['cover'] }}" alt="" aria-hidden="true"
                                loading="lazy" decoding="async" width="1600" height="900">
                        @endif

                        <h3 class="biz-card__name">
                            <a href="{{ $business['url'] }}" target="_blank" rel="noopener noreferrer">
                                {{ $business['name'] }}
                            </a>
                        </h3>

                        <p class="biz-card__trade">
                            {{ $business['trade'] }}@if (!empty($business['town'])), {{ $business['town'] }}@endif
                        </p>

                        <p class="biz-card__foot">
                            {{-- Use a span and not an anchor. The name above
                                 links to the same place and covers the card. --}}
                            <span class="link-arrow">
                                <span>Visit the site</span>
                                @include('_components.icon', ['name' => 'external'])
                            </span>
                            <span class="tabular">
                                <span class="visually-hidden">Live since </span>{{ $business['launched'] }}
                            </span>
                        </p>
                    </article>
                @endforeach
            </div>

            <div class="btn-row biz-list__more">
                <a class="btn btn--primary" href="{{ $page->bookingUrl }}" target="_blank"
                    rel="noopener noreferrer">
                    <span>Book a call</span>
                    @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                </a>
                <a class="btn btn--ghost" href="#contact">
                    <span>Write to me instead</span>
                </a>
            </div>
        @endif
    </section>

    {{-- The Offer for the campaign, with the price, on the one page that states
         the price. structured-data.blade.php runs on every page and therefore
         carries no price and no campaign: a price on /about/ or on a post is a
         price for a service that page does not describe.

         `seller`, not `provider`. schema.org does not define `provider` on an
         Offer: it belongs to a Service or another CreativeWork. `seller` is
         the property that names who is offering, and it points at the Person
         node the shared include declares, so the two agree without repeating
         the business. The Service inside `itemOffered` keeps `provider`, which
         is valid there.

         The figures come from `pricing` in config.php, which is also what the
         price block above reads. A search engine and a reader thus cannot be
         shown two different numbers. When the campaign ends, this block goes
         with the page and no shared file needs a change.

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
                    'name' => 'Zero to One',
                    'description' => 'A complete website for a business that has none: built, launched and looked after, at a fixed price.',
                    'serviceType' => 'Website design and development',
                    'url' => $page->getCanonicalUrl(),
                    'provider' => ['@id' => $offerPersonId],
                ],
            ],
        ];
    @endphp
    @else
        {{-- The holding page. It states no price and no turnaround, because
             those are the terms under revision, and it names no campaign.

             The Offer above sits inside the branch that is now closed, so the
             page publishes no structured price either.

             main.blade.php adds the contact block after this, therefore the
             page still has a way to reach me. --}}
        <div class="shell section page-head">
            @include('_components.breadcrumbs')

            <h1>A Website for Your Business.</h1>

            <p class="lead prose">I'm rebuilding how this offer works, so it's off the page for now.</p>

            <p class="lead prose">If your business doesn't have a website yet, tell me about it below and I'll give
                you a price and a date.</p>

            <p class="mt-md"><a href="{{ $page->baseUrl }}/services/">What I take on</a></p>
        </div>
    @endif
@endsection
