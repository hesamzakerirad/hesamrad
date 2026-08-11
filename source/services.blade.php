---
title: Services
---

@extends('_layouts.main')

@section('title', 'Services')

@section('description', 'Four kinds of project I take on, how the work runs, what it costs, and what I will not take.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Work I take on.</h1>
        <p class="lead prose">I build complete products, on my own: the part your customers use, the system behind it,
            and everything needed to keep it running. Here is the kind of work I take, how it runs, and how to start.</p>

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
                'for' => 'A developer or agency started it and left. It is not finished, or not right.',
                'includes' => [
                        'An honest read on what is worth keeping and what is not',
                        'A plan to get it launched, with a date you can hold me to',
                        'The missing pieces built — usually the unglamorous ones',
                        'Everything written down, so this cannot happen a second time',
                ],
                ],
                [
                'title' => 'Make a slow product fast',
                'for' => 'It was fine at a hundred customers and it is struggling at ten thousand.',
                'includes' => [
                        'Finding what is actually slow, rather than guessing',
                        'Getting the pages your customers wait on under a second',
                        'Handling the busy periods without buying a bigger server',
                        'A written before-and-after with the numbers, not adjectives',
                ],
                ],
                [
                'title' => 'Take over software nobody maintains',
                'for' => 'It still runs the business, nobody knows how, and everyone is afraid to touch it.',
                'includes' => [
                        'An honest audit of what is salvageable',
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

                <ul class="card__list">
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
                <p class="dim">You should know how this goes before you commit to it. Nothing here only shows up after
                the contract is signed.</p>
            </div>

            <ol class="steps">
                <li>
                <h3>A call, then a written plan</h3>
                    <p>Thirty minutes on a call to understand the business and the problem. You get back a written
                        plan: what I would build, in what order, what it costs, and what I think could go wrong.</p>
                    <p>The plan is yours to keep whether or not you hire me. If you take it to another developer, that
                        is a perfectly good outcome and it costs you nothing.</p>
                </li>
                <li>
                <h3>Weekly, visible progress</h3>
                <p>You see the real thing every week, in a browser, not a screenshot. No month-long silence ending
                        in a reveal.</p>
                </li>
                <li>
                <h3>I hand it over properly</h3>
                <p>Everything written down, a walkthrough with whoever will be looking after it, and a system that
                        runs without me. The handover is part of the job, not an afterthought.</p>
                </li>
                <li>
                <h3>A support window afterwards</h3>
                <p>An agreed period where defects in what I built are fixed at no extra cost. That is what standing
                        behind the work means.</p>
                </li>
            </ol>
        </div>
    </div>

    <div class="shell section">
        <div class="section-head">
            <h2>The questions people ask.</h2>
            <p>Money and time first, then the ones about risk. If what you need to know is not here, it is a fair
                thing to open with.</p>
        </div>

        @php
            /*
             * One list, ordered by what a buyer is actually unsure about —
             * money and time first, then whether their half-formed idea is
             * even a starting point, then scope, then the risks of hiring one
             * person, and the disqualifier last.
             *
             * Merged from two sections that were both lists of questions and
             * read as one arbitrarily split in half. Two pairs were saying the
             * same thing twice: "Where I work" and "How do we work together"
             * repeated the remote-hours answer almost word for word, and
             * "Arrangement" was the second half of "Pricing".
             *
             * Answers are arrays of paragraphs and stay escaped. An answer
             * that wants to link somewhere sets `link` rather than carrying
             * markup, so nothing here can put raw HTML on the page.
             */
            $questions = [
                [
                    'q' => 'What does it cost?',
                    // Open on load. Everything else is a worry a reader may not
                    // have; the price is the question all of them arrive with,
                    // and it is the one thing collapsing this list could have
                    // cost the page.
                    'open' => true,
                    'a' => [
                        'The cheapest way in is Zero to One: a fixed website for $1,500, plus $50 a month to keep it running. For a lot of businesses that is the whole answer.',
                        'Anything past that — payments, ordering, booking, a system built around how your business actually runs — is a fixed price for a defined project, or a monthly arrangement for ongoing work. Either way the number is quoted once the plan is written, so it reflects the real work rather than an hourly guess, and you have it before you commit to anything.',
                    ],
                    'link' => ['href' => '/zero-to-one/', 'label' => 'How Zero to One works'],
                ],
                [
                    'q' => 'How long does it take?',
                    'a' => [
                        'Most projects run two to six weeks from the plan being agreed to something your customers can use. Bigger builds go longer, and I will say so in the plan rather than discover it halfway through.',
                    ],
                ],
                [
                    'q' => 'What if I do not know exactly what I want yet?',
                    'a' => [
                        'That is the normal case, and it is what the first call is for. You do not need a specification written. You need to be able to describe what is wrong today and what you want to be true instead — working out what that means in software is part of the job, not a prerequisite for starting it.',
                    ],
                ],
                [
                    'q' => 'What do you actually build?',
                    'a' => [
                        'Web applications that work as well on a phone as on a laptop. I build both halves — what your customers see and the system running behind it — so there is no seam between them and nobody to coordinate with.',
                        'Where a client already has a designer or a front-end team, I take the half they cannot do and stay out of the way of the half they can. That is usually the cheaper arrangement for the client.',
                    ],
                    'link' => ['href' => '/work/', 'label' => 'Both projects ran that way'],
                ],
                [
                    'q' => 'Do I own what you build?',
                    'a' => [
                        'All of it, from the first day. The code lives in your repository, it runs on your hosting account, and the domain stays registered to you. I work inside your accounts rather than mine, so there is nothing to prise loose at the end and nothing of yours sitting in my name.',
                    ],
                ],
                [
                    'q' => 'How do we work together?',
                    'a' => [
                        'Remotely, and I have worked this way for most of my career rather than fallen into it. My clients are across Europe and North America and I arrange my day around whichever of those you are in, so there are hours every day when you can reach me directly rather than take a queue position.',
                        'Most people settle into a short call once a week plus email in between. If you would rather have more or less than that, say so and we will do that instead.',
                    ],
                ],
                [
                    'q' => 'What happens if you are unavailable?',
                    'a' => [
                        'I am one person, so let me answer that properly rather than wave it away. There is no second developer waiting in the wings.',
                        'What there is: you own every account and every line of code from day one, and I write things down as I go — a setup another developer can run, tests that say whether something is broken, and a walkthrough at handover. If I vanished tomorrow you would not be locked out of anything, and someone competent could carry on from what is written.',
                        'That is a smaller risk than being unable to reach the agency holding your source code. It is not zero, and you should hear that from me rather than find it out later.',
                    ],
                ],
                [
                    'q' => 'What happens after it launches?',
                    'a' => [
                        'There is an agreed period where anything I built that turns out to be broken gets fixed at no extra cost. After that some people want a monthly arrangement for changes and monitoring, and some take it in-house — which is what all the documentation is for. Both are fine, and neither is assumed.',
                    ],
                ],
                [
                    'q' => 'What will you not take on?',
                    'a' => [
                        'Brand and logo design, apps written natively for iPhone and Android (I build web apps that work properly on a phone instead), and anything where the plan is to skip testing to hit a date. I will say so on the first call rather than three weeks in.',
                    ],
                ],
            ];
        @endphp

        {{-- <details>, not a div with a click handler. It is a disclosure
             widget, and the native one already has the keyboard behaviour, the
             right role, the expanded state announced to a screen reader, and —
             the part that matters most here — it still works with no
             JavaScript at all. The animation is layered on in CSS, so a
             browser that cannot do it gets an instant toggle rather than a
             broken one.

             The answers stay in the document while collapsed, so a search
             engine still reads them. --}}
        <div class="faq">
            @foreach ($questions as $question)
                <details class="faq__item" @if ($question['open'] ?? false) open @endif>
                    <summary class="faq__q">
                        <h3>{{ $question['q'] }}</h3>

                        {{-- Two rules drawn in CSS rather than a pair of icons
                             swapped on state. One of them rotates flat and the
                             plus becomes a minus in a single movement — there is
                             no frame where the mark is neither. --}}
                        <span class="faq__mark" aria-hidden="true"></span>
                    </summary>

                    <div class="faq__a">
                        @foreach ($question['a'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach

                        @if ($link = $question['link'] ?? null)
                            <p class="faq__link">
                                <a class="link-arrow" href="{{ $page->baseUrl . $link['href'] }}">
                                    <span>{{ $link['label'] }}</span>
                                    @include('_components.icon', ['name' => 'arrow-right'])
                                </a>
                            </p>
                        @endif
                    </div>
                </details>
            @endforeach
        </div>
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
    @push('scripts')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                '@id' => $page->getCanonicalUrl() . '#faq',
                'mainEntity' => collect($questions)->map(fn ($question) => [
                    '@type' => 'Question',
                    'name' => $question['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        // The answer is an array of paragraphs now, joined
                        // back into one string for the schema. strip_tags stays
                        // defensive even though nothing here carries markup.
                        'text' => html_entity_decode(strip_tags(implode(' ', $question['a'])), ENT_QUOTES, 'UTF-8'),
                    ],
                ])->all(),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush

    @include('_components.testimonials', ['limit' => 2])

    <div class="shell section" id="contact">
        <div class="callout">
            <h2>Describe the problem in a paragraph.</h2>
            <p>That is enough for me to tell you whether this is a week of work or a quarter, and whether I am the right
                person for it.</p>

            @include('_components.contact-form')
        </div>
    </div>
@endsection
