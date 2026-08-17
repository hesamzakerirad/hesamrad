@extends('_layouts.main')

{{-- This title contains the site name, therefore the layout adds no suffix. --}}
@section('title', 'Hesam Rad · Independent software engineer')

{{-- Keep this description below 120 characters. A phone cuts the search snippet
     at approximately that length. --}}
@section('description', 'I build the software behind a growing business, and the website in front of it. One man, start to finish.')

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
                There's no agency here and no template. One engineer who builds the thing, and who's still around when it needs changing.
            </p>

            <div class="btn-row">
                <a class="btn btn--primary" href="{{ $page->baseUrl }}/services/">
                    <span>I need something built</span>
                    @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                </a>
                <a class="btn btn--ghost" href="{{ $page->baseUrl }}/zero-to-one/">
                    <span>I just need a website</span>
                </a>
            </div>

            <p class="hero__route">Not sure which? <a href="#contact">Describe it in a paragraph</a> and I'll tell you.</p>
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
                    ['value' => 'Free', 'label' => 'Written plan to keep'],
                    ['value' => '2–6', 'label' => 'Weeks, plan to launch'],
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

    <section class="shell section">
        <div class="section-head">
            <h2>Most of what an agency charges for is the agency.</h2>
            <p>An account manager briefs a project manager, who briefs a developer. You pay for all three, and two of
                them exist to coordinate the third. What you get here is one person for the part that has to be right
                and stay right: the system, the data, and the day-to-day of keeping it running. Nothing falls between
                three suppliers, because there aren't three suppliers.</p>
        </div>

        @php
            $capabilities = [
                [
                    'title' => 'A product built from nothing',
                    'body' => 'You\'ve got a business and an idea, and nothing built yet. I turn that into something your customers can sign up to and use: the site or app they see, plus the accounts, the payments and the admin screens you run it from.',
                ],
                [
                    'title' => 'Pages that load in under a second',
                    'body' => 'If your site has got slower as it\'s grown, that\'s usually weeks of work to fix rather than months. Faster pages mean fewer people give up before they buy.',
                ],
                [
                    'title' => 'Software nobody is looking after',
                    'body' => 'The developer who built it has gone, or the agency moved on. I take it over, make it safe to change again, and write down how it works, so you\'re never stuck like this twice.',
                ],
                [
                    'title' => 'Software that keeps working',
                    'body' => 'Automatic checks, a release that takes about a minute, and documentation in plain English. A change made on a Friday afternoon shouldn\'t take the business down on Saturday.',
                ],
            ];
        @endphp

        @include('_components.card-grid', ['items' => $capabilities, 'grid' => 'cards'])
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
                    <a class="btn btn--primary" href="{{ $page->bookingUrl }}" target="_blank"
                        rel="noopener noreferrer">
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
