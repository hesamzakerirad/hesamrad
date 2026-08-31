---
title: Reviews
contactHeading: "Want to be the next one?"
contactIntro: "Tell me what you're trying to build. I'll tell you whether I'm the right person for it, including the times I'm not."
---

@extends('_layouts.main')

@php
    /*
     * Indexed. Every review here is real, attributed, and most of them can be
     * checked at their source, which is a page worth finding.
     *
     * No Review or AggregateRating structured data on this page, deliberately.
     * Google treats reviews about a business, published on that business's own
     * site, as self-serving, and they are not eligible for review rich results.
     * Marking them up wins no stars and risks a structured data manual action.
     */
    $page->robots = 'index,follow';
@endphp

@section('title', 'Reviews')

{{-- Do not name a person or count the reviews here. A description built from
     the current entries goes stale the next time one is added. --}}
@section('description', 'Clients trusted me with work their business depended on, then took the time to say what that was like. I\'m grateful to every one of them.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>What people say.</h1>
        <p class="lead prose">Nobody owes me a review. The clients trusted me with work that mattered to
            their business. The colleagues worked next to me and saw how it came together on the good days and the
            rough ones. The fact that so many of them took the time to put that into words means more than I can say. I’m just grateful.</p>
    </div>

    {{-- No band. The page head above is plain, and the reviews are the page. --}}
    @include('_components.reviews', [
        'heading' => 'From the people who were there.',
        'more' => 'linkedin',
        'band' => false,
    ])
@endsection
