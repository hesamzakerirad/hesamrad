---
title: Questions
---

@extends('_layouts.main')

@section('title', 'Questions')

@section('description', 'What it costs, how long it takes, what you own at the end, and what happens if I am unavailable. Every question I get asked before somebody hires me.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Questions.</h1>

        <p class="lead prose">Everything people ask me before they commit to anything &mdash; money, timing,
            ownership, and the parts that are a risk rather than a selling point. If yours is not here, ask it
            and I will add it.</p>
    </div>

    <section class="shell section">
        {{-- Grouped, because twenty questions in one column is a wall. The
             groups are the order somebody actually worries in: what it costs
             before what happens after it launches. --}}
        @include('_components.faq-list', ['items' => $page->faq, 'grouped' => true])
    </section>

    <section class="shell section" id="contact">
        <div class="callout">
            <h2>Still not answered?</h2>
            <p>Ask it directly. You will get a straight answer within a day, including the times the answer is
                that I am not the right person for the job.</p>

            @include('_components.contact-form')
        </div>
    </section>

    {{-- This page carries the FAQPage schema for the whole site. /services/
         repeats nine of these questions for a buyer who is already reading it,
         but does not claim the markup — two pages both declaring themselves
         the FAQ leaves neither of them treated as one. --}}
    @push('scripts')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                '@id' => $page->getCanonicalUrl() . '#faq',
                'mainEntity' => collect($page->faq)->map(fn ($question) => [
                    '@type' => 'Question',
                    'name' => $question['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => html_entity_decode(strip_tags(implode(' ', collect($question['a'])->all())), ENT_QUOTES, 'UTF-8'),
                    ],
                ])->all(),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endpush
@endsection
