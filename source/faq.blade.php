---
title: Questions
---

@extends('_layouts.main')

@section('title', 'Questions')

@section('description', 'What it costs, how long it takes, what you own at the end, and what happens if I\'m unavailable. Every question I get asked before somebody hires me.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Questions.</h1>

        <p class="lead prose">Everything people ask me before they commit to anything: money, timing, ownership, and
            the parts that are a risk rather than a selling point. If yours isn't here, ask it and I'll add it.</p>
    </div>

    <section class="shell section">
        @include('_components.faq-list', ['items' => $page->faq, 'grouped' => true])
    </section>

    <section class="shell section" id="contact">
        <div class="callout">
            <h2>Still not answered?</h2>
            <p>Ask it directly. You'll get a straight answer within a day, including the times the answer is that I'm
                not the right person for the job.</p>

            @include('_components.contact-form')
        </div>
    </section>

    {{-- This page carries the FAQPage schema for the whole site. /services/
         shows some of these questions again, but it does not declare the
         schema. If two pages declare the schema, a search engine accepts
         neither page. --}}
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
