@extends('_layouts.main')

{{-- This is the fallback value for the meta description tag --}}
@section('description', $page->siteDescription)

@section('body')
    <div>
        @if (!$page->isHomePage())
            @include('_components.backlink')

            {{-- This is put inside this block to allow flexible content in home page. --}}
            <div>
                <h1>@yield('title')</h1>
            </div>
        @endif

        <div>
            @yield('content')
        </div>
    </div>
@endsection
