@extends('_layouts.main')

@php
    $page->type = 'article';
    $next = $page->getNext();
    $previous = $page->getPrevious();
@endphp

@section('body')
    <div class="post">
        <header>
            <div class="wrapper">
                <h1>{{ $page->title }}</h1>
            </div>
        </header>

        <div class="wrapper">
            @yield('content')
        </div>

        @if ($next || $previous)
            <section>
                <nav role="navigation" class="post-navigation">
                    @if ($next)
                        <div>
                            <a href="{{ $next->getUrl() }}" title="{{ $next->title }}">
                                {{ $next->title }}
                            </a>
                        </div>
                    @endif

                    @if ($previous)
                        <div>
                            <a href="{{ $previous->getUrl() }}" title="{{ $previous->title }}">
                                {{ $previous->title }}
                            </a>
                        </div>
                    @endif
                </nav>
            </section>
        @endif
    </div>
@endsection
