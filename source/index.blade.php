@extends('_layouts.page')

@section('title', $page->siteDescription)

@section('content')
    <p class="mb-3">Hello, I am Hesam.</p>
    <h1>I build software.</h1>

    <p>I have been building web-based software since 2017 when I was still in college. Every once in a while, I
        publish something about software engineering, books or life on my blog; if not, I am probably working on one of my
        side <a href="{{ $page->baseUrl }}/projects">projects</a>. I also have a literary background so the rest of my time
        is spent reading books.</p>

    <p>I mindfully employ digital minimalism in my life; but you can still reach me by sending an <a
            href="mailto:hesamrad.dev@gmail.com">email</a> or visiting my <a href="https://linkedin.com/in/hesamrad"
            target="_blank">LinkedIn</a> or <a href="https://github.com/hesamzakerirad" target="_blank">GitHub</a>
        page.</p>
@endsection
