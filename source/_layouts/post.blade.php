@extends('_layouts.main')

@php
    $page->type = 'article';

    $otherPosts = $posts->filter(function ($post) use ($page) {
        return !$post->isExternal && $post->title !== $page->title;
    });
    
    $tags = $page->getTags($page);
@endphp

@section('body')
    <div class="post">
        <header>
            <div class="wrapper">
                <h1>{{ $page->title }}</h1>
                <small>آخرین بروزرسانی: 
                    <time>{{ $page->getJalaliDate() }}</time>
                </small>
            </div>
        </header>

        <article>
            <div class="wrapper">
                @yield('content')
            </div>
        </article>

        @if ($otherPosts->isNotEmpty())
            <section>
                <div class="wrapper">
                    <h3>نوشته‌های دیگر</h3>
                    <ul>
                        @foreach ($otherPosts as $post)
                            <li>
                                <a href="{{ $post->getUrl() }}">{{ $post->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        <hr>

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
