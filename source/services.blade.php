---
title: Services
---

@extends('_layouts.main')

@section('title', 'Services')

@section('description', 'Four kinds of project I take on, how the work runs, what it costs, and what I won\'t take.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Work I take on.</h1>
        <p class="lead prose">I build complete products, on my own: the part your customers use, the system behind it,
            and everything needed to keep it running. Here's the kind of work I take, how it runs, and how to start.</p>

        <p class="mt-md"><span class="availability">Available for new projects</span></p>
    </div>

    <div class="shell section">
        <div class="section-head">
            <h2>Four kinds of project.</h2>
        </div>

        @php
            $services = [
                [
                'title' => 'Build a new product',
                'for' => 'You have a business idea, or a process you run by hand, and nothing built yet.',
                'includes' => [
                        'Working out what it actually has to do, before a line of code exists',
                        'The screens your customers use, designed and built',
                        'Accounts, payments, emails, and the admin area you run it from',
                        'Automatic checks and a one-button release process from the first week',
                ],
                ],
                [
                'title' => 'Finish something half-built',
                'for' => 'A developer or agency started it and left. It isn\'t finished, or isn\'t right.',
                'includes' => [
                        'A straight read on what is worth keeping and what is not',
                        'A plan to get it launched, with a date you can hold me to',
                        'The missing pieces built — usually the unglamorous ones',
                        'Everything written down, so this cannot happen a second time',
                ],
                ],
                [
                'title' => 'Make a slow product fast',
                'for' => 'It was fine at a hundred customers and it\'s struggling at ten thousand.',
                'includes' => [
                        'Measuring what is slow instead of guessing at it',
                        'Getting the pages your customers wait on under a second',
                        'Handling the busy periods without buying a bigger server',
                        'A written before-and-after with the numbers, not adjectives',
                ],
                ],
                [
                'title' => 'Take over software nobody maintains',
                'for' => 'It still runs the business, nobody knows how, and everyone is afraid to touch it.',
                'includes' => [
                        'A frank audit of what is salvageable',
                        'Safety checks added before anything is changed',
                        'Moving it onto a footing where changes are routine again',
                        'Documentation written for whoever comes after me',
                ],
                ],
            ];
        @endphp

        <div class="grid grid--halves mt-lg">
            @foreach ($services as $index => $service)
                <article class="card">
                <p class="card__index tabular">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</p>
                <h3 class="card__title">{{ $service['title'] }}</h3>
                <p class="card__body dim">{{ $service['for'] }}</p>

                {{-- What the engagement includes: a yes-list. --}}
                <ul class="card__list card__list--yes">
                        @foreach ($service['includes'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                </ul>
                </article>
            @endforeach
        </div>
    </div>

    <div class="shell section">
        <div class="grid grid--halves">
            <div class="section-head section-head--start">
                <h2>What working with me is like.</h2>
                <p class="dim">You should know how this goes before you commit to it. Nothing here waits until after
                the contract is signed to show up.</p>
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

    <div class="shell section">
        <div class="section-head">
            <h2>The questions people ask.</h2>
            <p>Money and time first, then the ones about risk. If what you need to know isn't here, it's a fair thing
                to open with.</p>
        </div>


        {{-- The nine a buyer asks before committing, from the same data
             /faq/ renders in full. That page carries the FAQPage schema;
             two pages both claiming it leaves neither treated as one. --}}
        @include('_components.faq-list', [
            'items' => collect($page->faq)
                ->filter(fn ($q) => $q['services'] ?? false)
                ->sortBy('services')
                ->values(),
        ])

        {{-- Its own class rather than .faq__link, which is the left-aligned
             link inside an answer. --}}
        <p class="faq__more">
            <a class="link-arrow" href="{{ $page->baseUrl }}/faq/">
                <span>The other sixteen questions</span>
                @include('_components.icon', ['name' => 'arrow-right'])
            </a>
        </p>
    </div>

    {{--
        The same questions again, as structured data.

        Generated from `$questions` rather than written out separately, so the
        markup and the visible answers cannot drift apart — which matters here
        beyond tidiness: Google will not show a rich result for an answer a
        visitor cannot also read on the page, and marking up an answer that is
        not there is a manual-action risk rather than a missed opportunity.

        `strip_tags` is defensive. The answers are plain text today, but the
        moment one of them gains a link, JSON-LD would carry the tag.
    --}}

    @include('_components.testimonials', ['limit' => 2])

    <div class="shell section" id="contact">
        <div class="callout">
            <h2>Describe the problem in a paragraph.</h2>
            <p>That's enough for me to tell you whether this is a week of work or a quarter, and whether I'm the right
                person for it.</p>

            @include('_components.contact-form')
        </div>
    </div>
@endsection
