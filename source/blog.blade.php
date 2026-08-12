---
title: Blog
---

@extends('_layouts.main')

@php
    // An empty listing is thin content. Keep it out of the index until there is
    // something to list; the sitemap follows the robots meta automatically.
    $page->robots = $posts->isEmpty() ? 'noindex,follow' : 'index,follow';

    // Featured posts lead, then the rest in the collection's own order.
    $ordered = $posts->where('isFeatured', true)->concat($posts->where('isFeatured', false));
@endphp

@section('title', 'Blog')

@section('description', 'Notes from the work: whatever I am building, using or working out at the time. Written for people who pay for software, not only those who write it.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Notes from the work.</h1>
        <p class="lead prose">Whatever I am building, using, or working out at the time. Written to be read by people
            who pay for software, not only by people who write it.</p>

        {{-- The empty state replaces the list rather than sitting inside it.
             .post-list carries a border-top to close the top edge of the first
             card, so an empty one drew a rule across the page with nothing
             underneath — the top of a table with no rows. --}}
        @if ($ordered->isEmpty())
            <p class="dim mt-lg">No posts have been published yet.</p>
        @else
            <div class="post-list mt-lg">
                @foreach ($ordered as $post)
                    @include('_components.post-preview')
                @endforeach
            </div>
        @endif
    </div>
@endsection
