@extends('_layouts.main')

{{-- This title contains the site name, therefore the layout adds no suffix. --}}
@section('title', 'Hesam Rad - Independent Software Engineer')

{{-- Keep this description below 120 characters. A phone cuts the search snippet
     at approximately that length. --}}
@section('description',
    'I build the software behind a growing business, and the website in front of it. One man, start
    to finish.')

@section('body')
    {{-- The dot field covers the hero and the figures below it. It must stay on
         this wrapper and not in the hero. At z-index -1 in this stacking
         context it shows below the content of the two sections. In the hero it
         shows above the figures, because the hero is a positioned stacking
         context and the section below it is not.

         The wrapper is also full width. The dot grid needs the full screen, and
         it cannot get that width in the 1120px shell. Do not use negative
         offsets to pull it out: they overflow the viewport at the widths where
         the shell is already full width. --}}
    <div class="hero-field">
        @include('_components.dot-grid', [
            'center' => 42,
            'clear' => 22,
            'peak' => 52,
            'tail' => 42,
            'knee' => 76,
            'floor' => 0.14,
        ])

        <section class="hero">
            <div class="shell">
                <p><span class="availability">Available for new projects</span></p>

                <p class="eyebrow">Independent software engineer</p>

                <h1 class="hero__title">Your business has a back office. Somebody has to build it.</h1>

                <p class="lead hero__lede">
                    There's no agency here and no template. One engineer who builds the product, and who's still around when
                    it needs changing.
                </p>

                {{-- The two buttons are a fork, and the fork needs the campaign.
                 "Specific" earns its place against "I just need a website"
                 beside it, and the line below asks which of the two. With the
                 campaign off, one button and a label that stands alone. --}}
                <div class="btn-row">
                    @if ($page->campaignIsLive)
                        <a class="btn btn--primary" href="{{ $page->baseUrl }}/services/">
                            <span>I need something specific</span>
                            @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                        </a>
                        <a class="btn btn--ghost" href="{{ $page->baseUrl }}/zero-to-one/">
                            <span>I just need a website</span>
                        </a>
                    @else
                        <a class="btn btn--primary" href="{{ $page->baseUrl }}/services/">
                            <span>I need something built</span>
                            @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                        </a>
                    @endif
                </div>

                <p class="hero__route">{{ $page->campaignIsLive ? 'Not sure which?' : 'Not sure where to start?' }}
                    <a href="#contact">Describe it in a paragraph</a> and I'll tell you.
                </p>
            </div>
        </section>

        {{-- The panel is opaque. The dot field therefore shows only in the margins
         around it, where the tail has made it faint. --}}
        <section class="shell section">
            @php
                /*
                 * Use aggregate figures only. Do not name an employer or a
                 * product, and do not write a figure that identifies a client.
                 */
                $stats = [
                    ['value' => $page->getMyYearsOfExperience() . '+', 'label' => 'Years building software'],
                    ['value' => '500+', 'label' => 'Businesses serviced'],
                    ['value' => '2 – 6', 'label' => 'Weeks, plan to launch'],
                    ['value' => '1 day', 'label' => 'To hear back from me'],
                ];
            @endphp

            <div class="stat-row stat-row--panel">
                @foreach ($stats as $stat)
                    <div class="stat">
                        <span class="stat__value tabular">{{ $stat['value'] }}</span>
                        <span class="stat__label">{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>{{-- /.hero-field --}}

    {{-- These four are parallel, not sequential, so they are cards and not the
         numbered "steps" list used on /services/. A visitor scans for the one
         sentence they recognize, and a 2x2 grid lets them find it without
         reading the other three.

         Cut every item on the same axis: the sentence the visitor would say out
         loud, not the service they get. An item a visitor cannot hear
         themselves saying does no work here. Each one ends on a size cue,
         because the unasked question is the price.

         The label above each quote carries the scan. The quote is what the
         visitor recognizes, and the label is what they were searching for. --}}
    @php
        $problems = [
            [
                'label' => 'Paperwork',
                'quote' => 'We lose a day a week to invoices and timesheets',
                'body' => 'Someone does it by hand, so mistakes creep in. Both jobs can mostly run themselves.',
            ],
            [
                'label' => 'Scattered info',
                'quote' => 'The same details get typed into three different places',
                'body' =>
                    'A customer\'s history lives in an inbox, a spreadsheet, and someone\'s memory. One record beats all three.',
            ],
            [
                'label' => 'Flying blind',
                'quote' => 'We find out the real numbers too late',
                'body' => 'Stock counts and reports catch up days after the fact. You should see it as it happens.',
            ],
            [
                'label' => 'Stuck at capacity',
                'quote' => 'Every new customer means more manual work, not less',
                'body' =>
                    'One person still holds it together, and the booking page loses a few more. Growing shouldn\'t take this much effort.',
            ],
        ];
    @endphp

    <section class="shell section">
        <div class="section-head">
            <h2>You describe the problem. I build the solution.</h2>
            <p>Most people write to me knowing what's costing them time, not knowing what to build. Here's what I hear
                most.</p>
        </div>

        <div class="grid grid--pairs">
            @foreach ($problems as $problem)
                <div class="card">
                    <p class="card__index">{{ $problem['label'] }}</p>
                    <h3 class="card__title card__title--quote">{{ $problem['quote'] }}</h3>
                    <p class="card__body">{{ $problem['body'] }}</p>
                </div>
            @endforeach
        </div>

        <p class="hero__route">
            Don't see your problem?
            <a href="#contact">
                <span>Explain it to me in a paragraph.</span>
            </a>
        </p>
    </section>

    <section class="section section--band">
        <div class="shell">
            <div class="section-head">
                <h2>Start with a call. <br> Keep the plan either way.</h2>
                <p>Thirty minutes to understand your business and what you're trying to build. You get back a written
                    plan: what I'd build, in what order, what it would cost, and what I think could go wrong.</p>
                <p>It's yours to keep, including to take to another developer. And if I'm not the right person for the
                    job, I'll say so on that call instead of three weeks in.</p>

                <div class="btn-row">
                    <a class="btn btn--primary" href="{{ $page->bookingUrl }}" target="_blank" rel="noopener noreferrer">
                        <span>Book a call</span>
                        @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                    </a>
                    <a class="btn btn--ghost" href="#contact">
                        <span>Write to me instead</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- No band. The section above this one is a band, and two together make
         one block of color with no edge between them. --}}
    @include('_components.testimonials', ['band' => false])
@endsection
