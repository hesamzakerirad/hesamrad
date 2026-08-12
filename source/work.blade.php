---
title: Work
---

@extends('_layouts.main')

@php
    /*
     * A listing now, not the studies themselves — each one has its own page
     * under /work/{slug}/ so there is room for the whole story.
     *
     * The collection filter in config.php has already dropped any sample when
     * the site is public, so nothing here has to re-check that. Sorting is by
     * year, newest first.
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

        {{-- The list lives inside the page head, the same way the blog's does.
             Both pages are a heading followed straight by the thing the page is
             about, with no section of its own in between — and as a separate
             section this one collected a full `--space-section` of nothing
             between the lede and the first card. --}}
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
                    <strong>Preview only.</strong> Anything marked as a sample is invented &mdash; no such company,
                    nobody said any of it. Samples are never generated once the site is public.
                </p>
            @endif

            <div class="work-list mt-lg">
                {{-- No images here. One study has a cover and the other never
                     will, and a list where one card carries a picture and the
                     next carries a gap reads as an incomplete list rather than
                     a considered one. The covers still lead their own pages,
                     where there is room for them to be the point. --}}
                @foreach ($studies as $study)
                    {{-- getCover() rather than reading cover['src'] directly:
                         front matter writes the cover both as a bare URL and as
                         a map, and this template only understood the map — so a
                         study using the string form rendered no image at all
                         and said nothing about it. --}}
                    {{-- Block form for the same reason case-study.blade.php
                         uses one: an inline @php is only safe in a file with no
                         later @php…@endphp, which is a property of the file
                         rather than of this line. --}}
                    @php
                        $cover = $study->getCover();
                    @endphp

                    <article class="work-card">
                        {{-- Decorative, and the title's anchor already covers the
                             whole card, so it is hidden rather than described. The
                             day a real cover exists it drops straight in here with
                             nothing else to change. --}}
                        @if ($cover && $cover['src'])
                            <img class="work-card__bg" src="{{ $cover['src'] }}" alt="" aria-hidden="true"
                                loading="lazy" decoding="async" width="1600" height="900">
                        @endif

                        @if ($study->sample ?? false)
                            <p class="case__flag">Invented sample &mdash; not real work</p>
                        @endif

                        <h2 class="work-card__title">
                            {{-- The whole card is the hit target: this anchor's
                                 ::after covers it, so the markup keeps one
                                 honest link rather than three. --}}
                            <a href="{{ $study->getCanonicalUrl() }}">{{ $study->title }}</a>
                        </h2>

                        <p class="work-card__summary">{{ $study->summary }}</p>

                        <p class="work-card__more">
                            <span class="link-arrow">
                                <span>Read the whole story</span>
                                @include('_components.icon', ['name' => 'arrow-right'])
                            </span>
                        </p>
                    </article>
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
