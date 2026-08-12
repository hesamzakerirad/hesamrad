---
title: Zero to One
---

@extends('_layouts.main')

@php
    /*
     * Zero to One.
     *
     * A productized offer, deliberately separate from the consultancy work the
     * rest of the site sells. It only works if the scope holds: the moment one
     * business gets "just a small booking form" thrown in, a three-day build
     * becomes a three-week one and the price stops making sense. That is why
     * the exclusions below are on the page rather than in a private note.
     *
     * Businesses launched. `$target` is the ambition, not a promise with a
     * date on it.
     *
     * The tally deliberately does not appear until there are three. A page
     * whose most prominent feature is "0 of 25" advertises that nothing has
     * happened yet; the same page without a counter simply reads as new.
     */
    $target = 25;

    // Real businesses. Only ever things that actually launched.
    $businesses = [
        // ['name' => '', 'trade' => '', 'town' => '', 'url' => '', 'launched' => 'YYYY-MM'],
    ];

    /*
     * Placeholders, so the list and the tally can be looked at with something
     * in them. None of these exist.
     *
     * The guard is the build itself rather than a flag anyone has to remember:
     * `production` is false in config.php and true in config.production.php,
     * so these render while working locally and are physically absent from
     * anything `jigsaw build production` emits. There is no switch to forget.
     *
     * They are also marked in the markup, because a screenshot taken locally
     * should not be able to pass for a real client list either.
     */
    $placeholders = [
        ['name' => 'Sample Bakery', 'trade' => 'Bakery and coffee shop', 'town' => 'Sampleton', 'url' => '#', 'launched' => '2026-08'],
        ['name' => 'Sample Joinery', 'trade' => 'Carpentry and fitted furniture', 'town' => 'Exampleford', 'url' => '#', 'launched' => '2026-08'],
        ['name' => 'Sample Dental', 'trade' => 'Dental practice', 'town' => 'Placeholderton', 'url' => '#', 'launched' => '2026-09'],
    ];

    $launched = collect($businesses);
    $showingPlaceholders = ! $page->production && $launched->isEmpty();

    if ($showingPlaceholders) {
        $launched = $launched->concat($placeholders);
    }

    $showTally = $launched->count() >= 3;
@endphp

{{-- "Zero to One" alone tells a search result nothing. The name is kept, and
     the thing it is is appended, because this is the one place the label has to
     work for somebody who has never seen the page. --}}
@section('title', 'Zero to One — a website in about a week')

@section('description', 'Taking ' . $target . ' businesses from no website at all to one that works — a fixed price, about a week, and the whole thing handled for you.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        {{-- The name means nothing on its own, so the line under it decodes it
             literally — zero websites, then one. That is the whole explanation
             and it does not need saying twice. --}}
        <h1>Zero to One.</h1>

        <p class="lead prose">From no website at all, to one that works. A fixed price, about a week, and I handle all
            of it &mdash; including the part where somebody has to write the words.</p>

        <p class="lead prose">Plenty of good businesses still have none. Usually not because they decided against one,
            but because it is one more thing to sort out and it never quite gets to the top of the list. So I am
            taking {{ $target }} of them online.</p>

        @if ($showTally)
            <p class="mt-md"><span class="availability">{{ $launched->count() }} of {{ $target }} online so far</span></p>
        @endif

        @if ($showingPlaceholders)
            {{-- Local builds only. If this is ever visible on hesamrad.com,
                 something has gone very wrong with the deploy. --}}
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
                <p>The same thing every time. That is what keeps it quick and keeps it {{ $page->priceSetup() }} instead of five.</p>
            </div>

            @php
                $included = [
                    [
                        'title' => 'A website, up to five pages',
                        'body' => 'Built from a template I know inside out, in your colours, with your photos. It works properly on a phone, because that is where most of your customers will see it.',
                    ],
                    [
                        'title' => 'I write the words',
                        'body' => 'We talk for half an hour about what you do, and I write the site from that. You do not have to sit down and compose anything — that is the step that stalls most websites for months.',
                    ],
                    [
                        'title' => 'The domain, hosting and security',
                        'body' => 'Bought, set up and pointed at the right place. You never have to learn what any of those words mean. It is registered to you, not to me.',
                    ],
                    [
                        'title' => 'Findable on Google',
                        'body' => 'Your Google Business Profile set up or cleaned up, so you turn up in the map when someone nearby searches for what you do. Usually this matters more than the website itself.',
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

        {{-- One panel, not a row of figures floating above an unrelated list.
             A price and the terms attached to it are one object: read apart,
             the numbers are a claim and the terms are small print; read
             together they are an offer. The rule between them is what says the
             terms belong to the figures above. --}}
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
                        them &mdash; new opening hours, a price change, a few new photos. Email me and it gets
                        done.</dd>
                </div>
                <div class="price__term">
                    <dt>If you want to stop</dt>
                    <dd>Then stop. There is no minimum term. The domain is registered to you and I will hand over
                        everything so you or anyone else can take it on. You will not be held anywhere by the
                        paperwork.</dd>
                </div>
            </dl>
        </div>
    </section>

    <section class="section section--band">
        <div class="shell">
            <div class="grid grid--halves">
                <div class="section-head section-head--start">
                    <h2>How the week goes.</h2>
                    <p class="dim">Four steps, and only the first one needs anything from you.</p>
                </div>

                <ol class="steps">
                    <li>
                        <h3>A half-hour call</h3>
                        <p>You tell me what the business does, who your customers are, and what you want people to do
                            when they find you. That is the only homework there is.</p>
                    </li>
                    <li>
                        <h3>I build it</h3>
                        <p>I write the words, put the site together, and send you a link to look at. Usually two or
                            three days after the call.</p>
                    </li>
                    <li>
                        <h3>You tell me what is wrong</h3>
                        <p>One round of changes, and it needs to be honest &mdash; if a photo is bad or a sentence is
                            not how you would say it, say so.</p>
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
            <p>Said out loud, because a fixed price only stays fixed if everyone knows where the edges are.</p>
        </div>

        {{-- Two cards rather than two rows of a list, because these are not two
             items of the same kind: one is a boundary and the other is an
             invitation. Side by side they read as the pair they are — here is
             the edge, and here is what is on the other side of it. --}}
        <div class="grid grid--halves">
            <article class="card">
                <p class="card__label">Not included</p>
                <h3 class="card__title">The things a fixed price cannot carry</h3>
                <p class="card__body">A design made from scratch &mdash; this is one template, dressed in your
                    colours and your photos. Logos, branding and photography are not part of it either. If you have
                    a logo I will use it; if you do not, the site works fine without one.</p>
            </article>

            <article class="card">
                <p class="card__label">Possible, priced separately</p>
                <h3 class="card__title">Bigger things are still on the table</h3>
                <p class="card__body">Taking payments, online ordering, a booking system, a customer login,
                    something built around how your business actually runs &mdash; I do all of that. It is real work
                    rather than a box to tick, so it is not part of the {{ $page->priceSetup() }}.</p>
                <p class="card__body">Tell me what you have in mind on the call and I will give you a proper number.
                    Sometimes the answer is that you do not need it yet, and I will say that too.</p>
            </article>
        </div>
    </section>

    <section class="shell section">
        <div class="section-head">
            <h2>The businesses.</h2>
            <p>Every one, as it goes live. No selective memory.</p>
        </div>

        @if ($launched->isEmpty())
            <p class="lead prose dim">The first businesses are being set up now, and each one is listed here as it goes live. If you want yours to be among them, the form below is the whole application.</p>

            <div class="btn-row">
                <a class="btn btn--primary" href="#contact">
                    <span>Be the first</span>
                    @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                </a>
            </div>
        @else
            {{-- Cards rather than a date-keyed list. These are the proof the
                 page is built on, and a row of small print reads like a
                 changelog. Shaped like the case study cards deliberately —
                 same object, less to say about it yet — so that the day one of
                 these has a story worth telling, it can become one without the
                 page changing shape around it. --}}
            <div class="biz-list">
                @foreach ($launched as $business)
                    {{-- The dark treatment is only for a card that has a
                         screenshot behind it. The scrim exists to make white
                         type readable over an arbitrary photograph — with no
                         photograph it was darkening nothing, and a coverless
                         card came out near black on a white page. --}}
                    <article class="biz-card {{ !empty($business['cover']) ? 'biz-card--covered' : '' }}">
                        @if (!empty($business['cover']))
                            {{-- Every business here has a website by definition,
                                 so a screenshot of it is what belongs behind the
                                 card. Decorative: the name links to the same
                                 place. --}}
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
                            {{-- The same .link-arrow the case study card uses,
                                 so the two read as the same kind of object. A
                                 span, not an anchor: the name above already
                                 links to the same place and covers the card.

                                 The glyph stays the external one rather than
                                 the case study's arrow, because this link does
                                 something that one does not — it leaves the
                                 site. Same treatment, honest about where it
                                 goes. --}}
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
        @endif
    </section>

    <section class="shell section" id="contact">
        <div class="callout">
            <h2>Tell me about the business.</h2>
            <p>A couple of sentences is plenty &mdash; what you do and roughly where. I will tell you whether Zero to
                One is right for you, and if it is not, what would be.</p>

            @include('_components.contact-form')
        </div>
    </section>
@endsection
