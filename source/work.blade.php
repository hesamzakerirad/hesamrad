---
title: Work
contactHeading: "Tell me what you're trying to build."
contactIntro: "A paragraph is enough. I'll tell you whether I've done something like it before, and whether I'm the right person for this one."
---

@extends('_layouts.main')

@php
    /*
     * The collection filter in config.php removes each sample when the site is
     * public. This template does not test for samples again.
     */
    $studies = $caseStudies->sortByDesc('year')->values();
    $showingSamples = $studies->contains(fn ($study) => $study->sample ?? false);

    $page->robots = $page->workIsPublic ? 'index,follow' : 'noindex,nofollow';

    // The page lists the studies, therefore the markup does too.
    //
    // A private build is noindex, and structured data changes a search result
    // that the page will not get. The samples in a private build are also
    // invented, and inventing them again in the markup is worse. An empty
    // listing gives null, and the shared include then leaves the page a plain
    // WebPage.
    $page->schemaNodes = $page->workIsPublic ? [
        $page->collectionListNode($studies->map(fn ($study) => [
            'name' => $study->title,
            'url' => $study->getCanonicalUrl(),
        ])->all()),
    ] : [];
@endphp

@section('title', 'Work')

{{-- Say what the projects are, not which ones are on the page today. A
     description that lists the current entries goes stale the next time one is
     added, and one that only lists the page's own headings gets replaced by a
     sentence Google picks off the page. --}}
@section('description', 'I built every system here end to end, for real businesses. Every one of them shipped and went into daily use.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>What I have built, and what changed.</h1>
        <p class="lead prose">Each of these is a real project: the problem the business had, what I built for it, and
            the difference it made once it was live. Numbers wherever I have them, adjectives where I don't.</p>

        {{-- Keep this list in the page head. In a section of its own it adds a
             full `--space-section` of empty space between the lede and the
             first card. --}}
        @if ($studies->isEmpty())
            <div class="mt-lg">
                <p class="lead prose dim">I'm writing these up now. Until they're here, the fastest way to find out
                    whether I've built something like yours is to ask.</p>

                <div class="btn-row">
                    <a class="btn btn--primary" href="{{ $page->baseUrl }}/#contact">
                        <span>Ask me</span>
                        @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
                    </a>
                </div>
            </div>
        @else
            @if ($showingSamples)
                <p class="sample-notice mt-lg" role="status">
                    <strong>Preview only.</strong> Anything marked as a sample is invented. No such company exists,
                    and nobody said any of it. Samples are never generated once the site is public.
                </p>
            @endif

            <div class="work-list mt-lg">
                @foreach ($studies as $study)
                    @include('_components.work-card', ['study' => $study])
                @endforeach
            </div>
        @endif
    </div>

@endsection
