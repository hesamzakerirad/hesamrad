@extends('_layouts.main')

@php
    $page->type = 'article';
@endphp

@section('body')
    @if ($page->cover_image)
        <img src="{{ $page->cover_image }}" alt="{{ $page->title }}">
    @endif

    <h1>{{ $page->title }}</h1>

    <div>
        @yield('content')
    </div>

    @php
        $next = $page->getNext();
        $previous = $page->getPrevious();
    @endphp

    @if ($next || $previous)
        <nav role="navigation" class="post-navigation">
            <div>
                @if ($next)
                    <a href="{{ $next->getUrl() }}" title="{{ $next->title }}">
                        &RightArrow; {{ $next->title }}
                    </a>
                @endif
            </div>

            <div>
                @if ($previous)
                    <a href="{{ $previous->getUrl() }}" title="{{ $previous->title }}">
                        {{ $previous->title }} &LeftArrow;
                    </a>
                @endif
            </div>
        </nav>
    @endif
@endsection
