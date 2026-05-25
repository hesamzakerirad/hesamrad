@extends('_layouts.main')

@php
    $page->title = 'Projects';
@endphp

@section('body')
    <div class="landing">
        <div class="container">
            @include('_components.backlink')

            <h1>Projects</h1>

            <p>I love open-source projects and contributing to the developer community. Over the years of working with
                <a href="https://laravel.com" target="_blank">Laravel</a>, I have come across some ideas that I keep implementing on every project. To avoid that, I have
                created usable packages to be pulled into any Laravel project. Here are a few of them:</p>

            <ul class="list-disc list-inside space-y-3">
                <li class="list-item">
                    <a href="https://github.com/hesamzakerirad/laravel-wallet" target="_blank">Laravel Wallet</a> is a minimalistic wallet
                    for
                    any Laravel application allowing logging and concurrency.
                </li>
                <li class="list-item">
                    <a href="https://github.com/hesamzakerirad/laravel-sql-logger" target="_blank">Laravel SQL Logger</a> is
                    a lightweight package to log SQL queries in your Laravel
                    application
                </li>
                <li class="list-item"><a href="https://github.com/hesamzakerirad/laravel-flashlight" target="_blank">Laravel
                        Flashlight</a> is a highly customizable Laravel package to log HTTP requests</li>
            </ul>

            <hr>

            <p>I also develop non-profit software that are specifically designed to make a positive
                impact in the internet:</p>

            <ul class="list-disc list-inside space-y-3">
                <li class="list-item">
                    PVND is a free url shortener, QR-code and profile
                    generator. 
                    <span class="text-yellow-500">(Coming soon)</span>
                </li>
            </ul>
        </div>
    </div>
@endsection
