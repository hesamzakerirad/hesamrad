@extends('_layouts.main')

@section('body')
    <h1>{{ $page->title }}</h1>

    <div>
        @yield('content')
    </div>

    @foreach ($page->posts($posts) as $post)
        @include('_components.post-preview-inline')

        @if (!$loop->last)
            <hr>
        @endif
    @endforeach
@stop
