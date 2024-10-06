@extends('_layouts.main')

@php
    $page->type = 'article';
@endphp

@section('body')
    @if ($page->cover_image)
        @if ($page->cover_image_copyright)
            <a href="{{ $page->cover_image_copyright }}" class="no-decoration" target="_blank" >
                <img src="{{ $page->cover_image }}">
            </a>
        @else
            <img src="{{ $page->cover_image }}">
        @endif
        </div>
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
