---
title: Work
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
@endphp

@section('title', 'Work')

@section('description', 'Selected projects: what the business needed, what I built, and what changed as a result.')

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

    <div class="shell section" id="contact">
        <div class="callout">
            <h2>Tell me what you're trying to build.</h2>
            <p>A paragraph is enough. I'll tell you whether I've done something like it before, and whether I'm the
                right person for this one.</p>

            @include('_components.contact-form')
        </div>
    </div>
@endsection
