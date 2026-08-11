@extends('_layouts.main')

{{-- The headline is the hook; the title is what somebody types into a search
     box. They do not have to be the same sentence, and here they should not be:
     "Your business has a back office" is nobody's search query, and at 70
     characters it would be cut off in the result anyway. --}}
@section('title', 'Independent software engineer')

@section('description', 'Independent software engineer. I build the system your business actually runs on — and the website in front of it. One person, start to finish.')

@section('body')
    {{-- The section runs the full width of the page and the shell inside it
         holds the content column. The dot grid needs the whole screen, and it
         cannot get there from inside a 1120px box — nor by being pulled out of
         one with negative offsets, which overflows the viewport at the widths
         where the shell already spans it. --}}
    <section class="hero">
        {{-- Behind the headline, and deliberately not behind the words: the
             fade is a ring, so the middle of the hero — where every line of
             type sits — is clean ground. --}}
        @include('_components.dot-grid')

        <div class="shell">
            <p><span class="availability">Available for new projects</span></p>

            {{-- The headline says what the work is, not what I am, so the trade has
                 to be stated somewhere a visitor cannot miss. Anyone deciding
                 whether to email a stranger about their business wants to know what
                 kind of stranger before they read a word of the pitch. --}}
            <p class="eyebrow">Independent software engineer</p>

            <h1 class="hero__title">Your business has a back office. Somebody has to build it.</h1>

            <p class="lead hero__lede">
                Not an agency, not a template, not a platform you rent. One engineer who builds the thing, and is still reachable when it needs changing.
            </p>

            {{-- Two doors, because two very different people arrive here and the
                 page cannot serve both with one button. Someone who needs a
                 website and someone who needs a system built around how their
                 business works want different things, at different prices, and
                 asking them to work out which they are is how a visitor leaves. --}}
            <div class="btn-row">
                <a class="btn btn--primary" href="{{ $page->baseUrl }}/services/">
                    <span>I need something built</span>
                    @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                </a>
                <a class="btn btn--ghost" href="{{ $page->baseUrl }}/zero-to-one/">
                    <span>I just need a website</span>
                </a>
            </div>

            <p class="hero__route">Not sure which? <a href="#contact">Describe it in a paragraph</a> and I will tell you.</p>
        </div>
    </section>

    {{-- A panel on the shell rather than a full-bleed band. The figures read as
         one object this way — a thing lifted onto the page — instead of a
         stripe the page happens to pass through. It also gives the dot field
         above it a clean edge to end against. --}}
    <section class="shell section">
            @php
                /*
                 * Four numbers a business owner can act on.
                 *
                 * The two this replaced — open-source packages maintained, and
                 * a non-profit side project — were developer credibility on a
                 * page aimed at people who do not know what a package is. The
                 * replacements answer the two questions that actually stop
                 * someone enquiring: how long will this take, and will anyone
                 * even write back.
                 *
                 * "Free" replaced a reach figure — 300K+ people using something
                 * I built — which was the only one here a visitor could not
                 * check, and which read as "too big for me" to somebody with
                 * forty customers. It also puts the risk-reversal offer in the
                 * band under the hero instead of halfway down the page.
                 *
                 * Still aggregates only: no employers, no products, nothing
                 * that identifies a client.
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

    <section class="shell section">
        <div class="section-head">
            <h2>Most of what an agency charges for is the agency.</h2>
            <p>An account manager briefing a project manager briefing a developer &mdash; layers whose job is
                coordinating the other layers, billed to you. You get one person for the part that has to be correct
                and stay correct: the system, the data, the releases, and the day-to-day of keeping it up. Nothing
                falls between three suppliers, because there are not three suppliers.</p>
        </div>

        @php
            $capabilities = [
                [
                    'title' => 'A product built from nothing',
                    'body' => 'You have a business and an idea, and nothing built yet. I turn it into something your customers can sign up to and use: the website or app they see, the accounts, the payments, and the admin screens you run it from.',
                ],
                [
                    'title' => 'Pages that load in under a second',
                    'body' => 'If your site or app has got slow as it has grown, that is usually a matter of weeks to fix, not months. Faster pages mean fewer people give up before they buy.',
                ],
                [
                    'title' => 'Software nobody is looking after',
                    'body' => 'The developer who built it has gone, or the agency moved on. I take it over, make it safe to change again, and write down how it works — so you are never in this position twice.',
                ],
                [
                    'title' => 'Software that keeps working',
                    'body' => 'Automatic checks, a release process that takes a minute, and documentation written in plain English. So a change made on a Friday afternoon does not take the business down on Saturday.',
                ],
            ];
        @endphp

        @include('_components.card-grid', ['items' => $capabilities, 'grid' => 'cards'])
    </section>

    {{-- The low-risk way in. Someone with an idea and no specification is not
         ready to commit to a project, and until this existed they had nowhere
         to go but a contact form that assumed they already knew what they
         wanted. Naming the first step and giving away its output is what makes
         it easy to say yes to. --}}
    <section class="section section--band">
        <div class="shell">
            <div class="section-head">
                <h2>Start with a call. Keep the plan either way.</h2>
                <p>Thirty minutes to understand your business and what you are trying to build. You get back a written
                    plan: what I would build, in what order, what it would cost, and what I think could go wrong.</p>
                <p>It is yours to keep &mdash; including to take to another developer. And if I am not the right person
                    for the job, I will tell you on that call rather than three weeks in.</p>

                <div class="btn-row">
                    <a class="btn btn--primary" href="#contact">
                        <span>Ask for a plan</span>
                        @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                    </a>
                    <a class="btn btn--ghost" href="{{ $page->baseUrl }}/services/">
                        <span>How the work runs</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- The long version of this now lives on /about/. What stays here is the
         one beat the home page needs — that there is a person at the other end
         — plus the door to the rest of it. A visitor deciding whether to email
         a stranger wants to know who the stranger is; a visitor deciding
         whether to hire one wants the whole page, and that is a different
         moment. --}}
    <section class="shell section">
        <div class="section-head">
            <h2>There is a person behind this.</h2>
            <p>Eight years building web software, five of them looking after one system for the same client. A
                literary background as well as an engineering one, and a master's in English literature in progress
                &mdash; which turns out to matter, because most of this job is explaining a complicated thing
                clearly.</p>

            <p class="mt-md">
                <a class="link-arrow" href="{{ $page->baseUrl }}/about/">
                    <span>More about me</span>
                    @include('_components.icon', ['name' => 'arrow-right'])
                </a>
            </p>
        </div>
    </section>

    @include('_components.testimonials')

    <section class="shell section" id="contact">
        <div class="callout">
            <h2>Tell me what you are trying to build.</h2>
            <p>A paragraph is enough. You will get an honest answer about whether I am the right person for it,
                including the times when I am not.</p>

            @include('_components.contact-form')
        </div>
    </section>
@endsection
