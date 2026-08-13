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

    <section class="shell section">
        <div class="grid grid--halves">
            <div class="about__portrait">
                {{-- To add the photograph, replace this include with an <img>.
                     The layout does not change. --}}
                @include('_components.image-placeholder', [
                    'ratio' => 'portrait',
                    'label' => 'photograph of Hesam Rad',
                    'note' => 'Photograph to come',
                ])
            </div>

            <div class="section-head section-head--start">
                <h2>How I ended up working alone.</h2>

                <p class="dim">I spent five years as lead engineer on a digital menu platform, and most of another
                    building a monitoring system for a brokerage. Both were the same shape of work: one engineer who
                    knew the whole system, answerable for it, still there a year later.</p>

                <p class="dim">That's the arrangement I kept choosing, so eventually I stopped pretending it needed a
                    company around it. There's no agency here and no team to brief. The person you email is the person
                    who writes the code, and the person who fixes it at eleven at night.</p>

                <p class="dim">I take on the work I'm good at. When something isn't that, I say so on the first call
                    instead of three weeks in. It costs me the occasional project and it has never once cost me a
                    client.</p>
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

    <section class="shell section">
        <div class="grid grid--halves">
            <div class="section-head section-head--start">
                <h2>There's a person behind this.</h2>

                <p class="dim">I have a literary background as well as an engineering one, and I'm reading for a
                    master's in English literature. Most of the time I'm not writing code, I'm reading.</p>

                <p class="dim">It's less of a detour than it sounds. Both jobs are mostly about saying a complicated
                    thing clearly to somebody who has no reason to be patient with you.</p>

                <p class="dim">I'm on very few platforms, by choice. Email reaches me faster than anything else
                    does.</p>
            </div>

            <div class="rows">
                <div class="row">
                    <p class="row__key">Since 2017</p>
                    <div class="row__value">
                        <p>Building web software, starting while I was still a computer engineering student.</p>
                    </div>
                </div>
                <div class="row">
                    <p class="row__key">Where I work</p>
                    <div class="row__value">
                        <p>Remote, with clients across Europe and North America. I arrange my day around whichever
                            of those you're in, so there are hours every day when you can reach me.</p>
                    </div>
                </div>
                <div class="row">
                    <p class="row__key">Open source</p>
                    <div class="row__value">
                        <p>Four small tools I kept needing and eventually published, plus a free URL shortener built as
                            a non-profit. <a href="{{ $page->baseUrl }}/projects/">See the code</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
