@extends('_layouts.main')

@php
    $page->title = $page->siteDescription;
@endphp

@section('body')
    <div class="container blog mt-3 mb-3">
        <div class="mb-1">
            <a href="{{ $page->baseUrl }}" rel="home" aria-label="بازگشت به خانه">
                <i class="fa-solid fa-arrow-right ml-05"></i>
                بازگشت به خانه
            </a>
        </div>

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
