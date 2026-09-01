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

    // The page lists the posts, therefore the markup does too. The flag and the
    // collection-list include at the end of the body go together: the flag
    // makes the page node point at the list, and the include declares it.
    $page->collectionPage = $ordered->isNotEmpty();
@endphp

@section('title', 'Blog')

{{-- Say what the writing is about, not which posts are on the page today. A
     description built from the current posts goes stale the next time one is
     published, and one that only names the genre gets replaced by a sentence
     Google picks off the page. --}}
@section('description', 'I write about software development, the world of business and how the two intersect. You don\'t need a technical background to follow any of it.')

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

    {{-- The contents of the page, as structured data. The order matches the
         order above, therefore a featured post comes first here too. --}}
    @include('_components.collection-list', [
        'items' => $ordered->map(fn ($post) => [
            'name' => $post->title,
            'url' => $post->getCanonicalUrl(),
        ])->all(),
    ])
@endsection
