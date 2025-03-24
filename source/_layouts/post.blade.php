@extends('_layouts.main')

@php
    $page->type = 'article';
@endphp

@section('body')
    <div class="post">
        <header {{ 'style=background-color:' . $page->getPostColor() }}>
            <div class="wrapper">
                <h1>{{ $page->title }}</h1>
                <small>خواندن در
                    <span>{{ $page->getReadTime() }} دقیقه</span>
                </small>
                <span>.</span>
                <small>آخرین بروزرسانی در
                    <time>{{ $page->getJalaliDate() }}</time>
                </small>
            </div>
        </header>

        <article>
            <div class="wrapper">
                @yield('content')
            </div>
        </article>

        @if ($next = $page->getNext())
            <section>
                <div class="wrapper">
                    <div class="next" role="navigation">
                        <span>نوشته بعدی:</span>
                        <a href="{{ $next->getUrl() }}">{{ $next->title }}</a>
                    </div>
                </div>
            </section>
        @endif

        <section>
            <div class="wrapper">
                <script src="https://utteranc.es/client.js"
                        repo="hesamzakerirad/hesamrad"
                        issue-term="title"
                        theme="github-light"
                        crossorigin="anonymous"
                        async>
                </script>
            </div>
        </section>
    </div>
@endsection
