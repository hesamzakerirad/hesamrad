---
title: Open source
disableContact: true
---

@extends('_layouts.main')

@section('title', 'Open source')

@section('description', 'Small open-source tools I built for my own work and published for anyone to use, plus a free URL shortener run as a non-profit.')

@section('body')
    <div class="shell section page-head">
        @include('_components.breadcrumbs')

        <h1>Code I gave away.</h1>
        <p class="lead prose">Small tools I built for my own work and published for anyone to use. They're here as
            evidence of how I work. Nothing on this page is for sale, and you don't need to understand any of it to
            hire me.</p>
    </div>

    <div class="shell section">
        <div class="section-head">
            <h2>Laravel packages.</h2>
        </div>

        @php
            $packages = [
                [
                'name' => 'Laravel Wallet',
                'url' => 'https://github.com/hesamzakerirad/laravel-wallet',
                'body' => 'A minimalistic wallet for any Laravel application, with logging and concurrency handled properly.',
                'tags' => ['Laravel', 'Payments'],
                ],
                [
                'name' => 'Laravel SQL Logger',
                'url' => 'https://github.com/hesamzakerirad/laravel-sql-logger',
                'body' => 'A lightweight package for logging the SQL queries your Laravel application actually runs.',
                'tags' => ['Laravel', 'Debugging'],
                ],
                [
                'name' => 'Laravel Flashlight',
                'url' => 'https://github.com/hesamzakerirad/laravel-flashlight',
                'body' => 'A highly customisable package for logging incoming HTTP requests.',
                'tags' => ['Laravel', 'Observability'],
                ],
                [
                'name' => 'Laravel API Debugger',
                'url' => 'https://github.com/hesamzakerirad/laravel-api-debugger',
                'body' => 'Takes the tedium out of debugging JSON APIs during development.',
                'tags' => ['Laravel', 'APIs'],
                ],
            ];
        @endphp

        <div class="grid grid--cards mt-lg">
            @foreach ($packages as $index => $package)
                <a class="card" href="{{ $package['url'] }}" target="_blank" rel="noopener noreferrer">
                <p class="card__index tabular">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</p>
                <h3 class="card__title">{{ $package['name'] }}</h3>
                <p class="card__body">{{ $package['body'] }}</p>

                <div class="card__footer cluster">
                        <ul class="tags">
                            @foreach ($package['tags'] as $tag)
                                <li class="tag">{{ $tag }}</li>
                            @endforeach
                        </ul>
                        <span class="link-arrow">
                            <span class="visually-hidden">Open {{ $package['name'] }} on GitHub</span>
                            @include('_components.icon', ['name' => 'external'])
                        </span>
                </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="shell section">
        <div class="grid grid--halves">
            <div class="section-head section-head--start">
                <h2>Software with no business model.</h2>
                <p class="dim">Some things should just exist on the internet without a pricing page attached to
                    them.</p>
            </div>

            <div class="card">
                <p class="card__index">Active development</p>
                <h3 class="card__title">
                <a href="https://pvnd.io" target="_blank" rel="noopener noreferrer">Peyvand</a>
                </h3>
                <p class="card__body">A free URL shortener, QR-code generator and profile builder. No account walls, no
                tracking pixels, no plans to add either.</p>
            </div>
        </div>
    </div>

    <div class="shell section">
        <div class="callout">
            <h2>The list is longer than the shipped part.</h2>
            <p>There are far more ideas queued up than there are hours to build them. If one of these is useful to you,
                issues and pull requests are welcome.</p>

            <div class="btn-row">
                <a class="btn btn--ghost" href="https://github.com/hesamzakerirad" target="_blank"
                rel="noopener noreferrer">
                <span>GitHub profile</span>
                </a>
            </div>
        </div>
    </div>
@endsection
