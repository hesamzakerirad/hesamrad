@extends('_layouts.main')

@php
    $page->title = $page->siteDescription;
@endphp

@section('body')
    <div class="container blog mt-3 mb-3">
        @include('_components.backlink')

        @if ($posts->where('isPublished', true))
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
