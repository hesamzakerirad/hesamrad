---
title: Thank you
robots: noindex,follow
---
@extends('_layouts.main')

@section('title', 'Thank you')

@section('description', 'Your message is in. I read every one myself and reply within a day, usually sooner.')

@section('body')
    {{--
        Only reached by visitors with JavaScript off: everyone else gets an
        in-place status message and never leaves the page they were on.
    --}}
    <section class="shell section page-head">
        <h1>That reached me.</h1>

        <p class="lead prose">I read everything and reply to everything, usually within a day. If you do not hear back,
            the message went astray somewhere &mdash; email me at
            <a href="mailto:{{ $page->email }}">{{ $page->email }}</a> and I will pick it up there.</p>

        <div class="btn-row">
            <a class="btn btn--ghost" href="{{ $page->baseUrl }}">
                <span>Back to the homepage</span>
            </a>
        </div>
    </section>
@endsection
