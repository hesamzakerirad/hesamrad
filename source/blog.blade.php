---
title: Blog
disableContact: true
---

@extends('_layouts.main')

@php
    // An empty listing is thin content. Keep it out of the index until it has
    // posts. The sitemap obeys the robots directive automatically.
    $page->robots = $posts->isEmpty() ? 'noindex,follow' : 'index,follow';

    $ordered = $posts->where('isFeatured', true)->concat($posts->where('isFeatured', false));
@endphp

@section('title', 'Blog')

@section('description', 'Notes from the work: whatever I\'m building, using or working out at the time. Written for people who pay for software, not only those who write it.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Notes from the work.</h1>
        <p class="lead prose">Whatever I'm building, using, or working out at the time. Written to be read by people
            who pay for software, not only by the people who write it.</p>

        {{-- The empty state must replace the list and must not go inside it.
             .post-list has a border-top for the top edge of the first card. An
             empty .post-list shows a rule across the page with no content
             below it. --}}
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
