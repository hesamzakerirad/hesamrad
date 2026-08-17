---
title: Services
contactHeading: 'Describe the problem in a paragraph.'
contactIntro: "That's enough for me to tell you whether this is a week of work or a quarter, and whether I'm the right person for it."
---

@extends('_layouts.main')

@section('title', 'Services')

@section('description', 'The work I take on and the work I don\'t, how the work runs, what it costs, and how to start.')

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
            $next = '<a href="https://nextjs.org" target="_blank" rel="noopener noreferrer">Next.js</a>';

            // The `does` items hold markup and print unescaped. Write them here
            // and nowhere else, and do not put anything from a form in them.
            $does = [
                'Web applications, built with ' . $laravel . ' and ' . $next,
                'Looking after a ' . $laravel . ' application you already have',
                'Bespoke admin dashboards to run the business from',
                'Turning a manual process into software',
            ];

            $doesNot = [
                'Design and branding',
                'Marketing, SEO, and social media',
                'WordPress, Shopify, and Wix',
                'Mobile or desktop apps',
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

    {{-- The questions for this page are not here. main.blade.php puts them below
         the body and above the contact block. To add one, put
         `page => '/services/'` on a question in config.php. Do not add an include
         here, and do not repeat a question that /faq/ already shows. --}}

    @include('_components.testimonials', ['limit' => 2])
@endsection
