@extends('_layouts.main')

@php
    $page->title = $page->siteDescription;
@endphp

@section('body')
    <div class="wrapper blog">
        @foreach ($posts->where('isFeatured', true) as $post)
            <div class="post-preview featured">
                @include('_components.post-preview-inline')
            </div>

            @if (!$loop->last)
                <hr>
            @endif
        @endforeach

        @foreach ($posts->where('isFeatured', false) as $post)
            <div class="post-preview">
                @include('_components.post-preview-inline')
            </div>


            @if (!$loop->last)
                <hr>
            @endif
        @endforeach
    </div>
@endsection
