---
title: About
---

@extends('_layouts.main')

@section('title', 'About Hesam Rad')

@section('description', 'Independent software engineer. Eight years building web software for businesses, five of them looking after a single system for one client.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')


        <h1>Hesam Rad.</h1>

        <p class="lead prose">I build the software businesses run on, and I've been doing it since 2017. That started
            while I was still a computer engineering student, and it's been full time on my own since late 2025.</p>

        <p class="lead prose">Most of what I do is the part customers never see: the system underneath, the data in it,
            and the work of keeping both correct once real people depend on them.</p>
    </div>

    {{-- The heading holds the left column and the prose holds the right one.
         The heading stays beside the text it introduces, and the text keeps a
         readable measure without a rule or a panel around it. --}}
    <section class="shell section">
        <div class="split">
            <div class="split__aside">
                <h2>How I ended up working alone.</h2>
            </div>

            <div class="split__body">
                <p>I spent five years as lead engineer on a digital menu platform, and most of another building a
                    monitoring system for a brokerage. Both were the same shape of work: one engineer who knew the
                    whole system, answerable for it, still there a year later.</p>

                <p>That's the arrangement I kept choosing, so eventually I stopped pretending it needed a company
                    around it. There's no agency here and no team to brief. The person you email is the person who
                    writes the code, and the person who fixes it at eleven at night.</p>

                <p>I take on the work I'm good at. When something isn't that, I say so on the first call instead of
                    three weeks in. It costs me the occasional project and it has never once cost me a client.</p>
            </div>
        </div>
    </section>

    <section class="section section--band">
        <div class="shell">
            <div class="section-head">
                <h2>What eight years taught me.</h2>
                <p>Three things I didn't believe at the start and would now argue for at length.</p>
            </div>

            @php
                $beliefs = [
                    [
                        'title' => 'The hard part is deciding, not building',
                        'body' => 'Almost every project that goes badly went wrong before any code was written, in a conversation nobody had. I spend the first few days asking questions that sound obvious, because the alternative is building the wrong thing quickly.',
                    ],
                    [
                        'title' => 'Software you cannot change is already broken',
                        'body' => 'Anything worth building gets changed. If a change is frightening, the system has failed you whether or not it happens to work today. That\'s why the tests, the release process and the documentation get treated as part of the job rather than as overhead.',
                    ],
                    [
                        'title' => 'Explaining it plainly is part of the job',
                        'body' => 'If you can\'t follow what I\'m telling you, you can\'t judge whether I\'m right, and you\'re back to trusting a stranger. Every technical decision here has a plain-English version, and I\'d rather give you that than sound clever.',
                    ],
                ];
            @endphp

            @include('_components.card-grid', ['items' => $beliefs, 'grid' => 'cards'])
        </div>
    </section>

    @php
        // The collection filter in config.php removes each sample when the site
        // is public, therefore this template does not test for samples again.
        $selected = $caseStudies->sortByDesc('year')->take(2);
    @endphp

    @if ($page->workIsPublic && $selected->isNotEmpty())
        <section class="shell section">
            <div class="section-head">
                <h2>Some of what I have built.</h2>
                <p>Each one was a single engineer on a single system for years. The write-ups say what the business
                    needed, what I built, and what changed.</p>
            </div>

            <div class="work-list mt-lg">
                @foreach ($selected as $study)
                    {{-- An <h2> introduces this list, therefore the card title
                         is an <h3>. --}}
                    @include('_components.work-card', ['study' => $study, 'titleLevel' => 3])
                @endforeach
            </div>

            <p class="work__more">
                <a class="link-arrow" href="{{ $page->baseUrl }}/work/">
                    <span>All case studies</span>
                    @include('_components.icon', ['name' => 'arrow-right'])
                </a>
            </p>
        </section>
    @endif

    @include('_components.testimonials')

    <section class="shell section">
        <div class="callout">
            <h2>Still deciding whether to email a stranger?</h2>
            <p>A paragraph about what you're trying to build is enough to start. I'll tell you straight whether I'm the
                right person for it, including the times I'm not.</p>

            <div class="btn-row">
                <a class="btn btn--primary" href="{{ $page->baseUrl }}/#contact">
                    <span>Tell me what you need</span>
                    @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                </a>
                <a class="btn btn--ghost" href="{{ $page->baseUrl }}/work/">
                    <span>See what I have built</span>
                </a>
            </div>
        </div>
    </section>
@endsection
