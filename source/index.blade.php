@extends('_layouts.main')

@php
    $page->title = $page->siteDescription;
@endphp

@section('body')
    <div class="wrapper">
        @foreach ($posts->where('featured', true) as $post)
            <div class="featured">
                @include('_components.post-preview-inline')
            </div>

            @if (!$loop->last)
                <hr>
            @endif
        @endforeach

        @foreach ($posts->where('featured', false)->take(6)->chunk(2) as $row)
            <div>
                @foreach ($row as $post)
                    <div>
                        @include('_components.post-preview-inline')
                    </div>

                    @if (!$loop->last)
                        <hr>
                    @endif
                @endforeach
            </div>

            @if (!$loop->last)
                <hr>
            @endif
        @endforeach
    </div>
@stop
