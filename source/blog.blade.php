@extends('_layouts.page')

@php
    // An empty listing is thin content. Keep it out of the index until there is
    // something to list; the sitemap follows the robots meta automatically.
    $page->robots = $posts->isEmpty() ? 'noindex,follow' : 'index,follow';
@endphp

@section('title', 'Blog')

@section('description', 'Thoughts on software engineering, books and life by ' . $page->siteAuthor . '.')

@section('content')
    <div class="container blog mt-3 mb-3">
        @if ($posts->isEmpty())
            <p>No posts have been published yet.</p>
        @endif

        @foreach ($posts->where('isFeatured', true) as $post)
            <div class="post-preview featured">
                @include('_components.post-preview')
            </div>
        @endforeach

        @foreach ($posts->where('isFeatured', false) as $post)
            <div class="post-preview">
                @include('_components.post-preview')
            </div>
        @endforeach
    </div>
@endsection
