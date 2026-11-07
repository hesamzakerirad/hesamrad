---
title: About
disableContact: true
# The subject of this page is the person. structured-data.blade.php reads this
# key and adds `about` to the page node. Only the home page and this one carry
# it. A page about work for sale is not a page about a human being.
aboutsAuthor: true
---

@extends('_layouts.main')

@section('title', 'About Hesam Rad')

{{-- Keep this on one line. A string broken across lines puts a real newline and
     the indentation inside the content attribute. --}}
@section('description', 'I build bespoke web software for real businesses, and have since late 2017. One person, start to finish, and still around when it needs changing.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')


        <h1>Hesam Rad.</h1>

        <p class="lead prose">I help businesses grow by building them software they can use and rely on
            to move forward with their digital journey and I have been doing it since 2017.
        </p>

        <p class="lead prose">Most of what I do is understanding what the problem is so I can build the right tool to fix it.
        </p>
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

                <p>
                    In my experience as an employed engineer, the software an entire business relies on is often built and
                    maintained by a handful of people who know it end to end. In my case, I was always one of those people,
                    responsible not just for writing the code, but for understanding the system, making decisions, and
                    keeping it running.
                </p>
                <p>
                    Eventually, I realized I didn't need a company around me to take that responsibility seriously. I could
                    work directly with a business, understand what it needs, and care about the software as deeply as the
                    people who depend on it.
                </p>
                <p>
                    So I started working independently in late 2025. If you're weighing one engineer against an
                    agency, I've written down
                    <a href="/blog/agency-or-one-independent-engineer/">the honest comparison</a>, including the
                    jobs an agency is the better answer for.
                </p>
            </div>
        </div>
    </section>

    <section class="section section--band">
        <div class="shell">
            <div class="section-head">
                <h2>The software is only half the job.</h2>
                <p>The other half is everything around it: how I work with you, what you can see while it happens,
                    and what you're left holding at the end.</p>
            </div>

            @php
                $risks = [
                    [
                        'title' => 'Commitment',
                        'body' =>
                            'I take on a small number of projects and I stay with them. Your work isn\'t fitted around six other clients, and I\'m not gone the week after it goes live.',
                    ],
                    [
                        'title' => 'Trust',
                        'body' =>
                            'You\'re handing over something the business depends on. One person is answerable for all of it, and when something goes wrong it gets fixed first and explained after.',
                    ],
                    [
                        'title' => 'Transparency',
                        'body' =>
                            'You always know where things stand. Progress is something you look at rather than something I report, and every decision comes with a reason in plain English.',
                    ],
                    [
                        'title' => 'Ownership',
                        'body' =>
                            'The domain, the accounts and the code are in your name from day one. Nothing runs only on my laptop, so replacing me is never a rescue operation.',
                    ],
                ];
            @endphp

            @include('_components.card-grid', ['items' => $risks, 'grid' => 'cards'])
        </div>
    </section>

    @php
        // The collection filter in config.php removes each sample when the site
        // is public, therefore this template does not test for samples again.
        //
        // Web applications only. A page that shows a selection of the work
        // shows the applications, because they are the work this page is
        // about. /work/ is the page that carries the websites as well.
        //
        // Read both keys with `get()` and a default, the way /work/ does. A
        // study that leaves either key out still builds.
        $selected = $caseStudies
            ->filter(fn ($study) => ($study->get('kind') ?: 'web-application') === 'web-application')
            ->sortByDesc(fn ($study) => (int) ($study->get('launchYear') ?: 0))
            ->take(2);
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

    @include('_components.reviews', ['only' => 'clients', 'limit' => 2])

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
