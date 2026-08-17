---
title: Questions
contactHeading: 'Still not answered?'
contactIntro: "Ask it directly. You'll get a straight answer within a day, even when the answer is that I'm not the right person for the job."
---

@extends('_layouts.main')

@section('title', 'Questions')

@section('description', 'What it costs, how long it takes, what you own at the end, and what happens if I\'m unavailable. Every question I get asked before somebody hires me.')

@php
    /*
     * The questions with no `page` key. A question with a `page` key belongs to
     * that page and shows there only, therefore this page must not repeat it.
     * Without this filter the same question and the same FAQPage entry appear on
     * two addresses.
     */
    $questions = collect($page->siteFaq)
        ->reject(fn ($question) => isset($question['page']))
        ->values();
@endphp

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Questions</h1>

        <p class="lead prose">Everything people ask me before they commit to anything: money, timing, ownership, and
            the risks. If yours isn't here, ask it and I'll add it.</p>
    </div>

    <section class="shell section">
        @include('_components.faq-list', ['items' => $questions, 'grouped' => true])
    </section>

    {{-- The schema holds the questions this page shows and no more. A page with
         its own questions carries its own node, from the same component. Pass
         `$questions` and not `$page->siteFaq`. --}}
    @push('scripts')
        @include('_components.faq-schema', ['items' => $questions])
    @endpush
@endsection
