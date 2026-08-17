---
title: Thank you
robots: noindex,follow
disableContact: true
---
@extends('_layouts.main')

@section('title', 'Thank you')

@section('description', 'Your message is in. I read every one myself and reply within a day, usually sooner.')

@section('body')
    {{-- Only a visitor with JavaScript off comes to this page. The form shows a
         status message in place to all other visitors. --}}
    <section class="shell section page-head">
        <h1>That reached me.</h1>

        <p class="lead prose">I read everything and reply to everything, usually within a day. If you don't hear back,
            the message went astray somewhere. Email me at
            <a href="mailto:{{ $page->email }}">{{ $page->email }}</a> and I'll pick it up there.</p>

        <div class="btn-row">
            <a class="btn btn--ghost" href="{{ $page->baseUrl }}">
                <span>Back to the homepage</span>
            </a>
        </div>
    </section>
@endsection
