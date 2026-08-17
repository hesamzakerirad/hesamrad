---
title: Not Found!
permalink: /404.html
robots: noindex,follow
disableCanonical: true
disableContact: true
---

@extends('_layouts.main')

@section('title', 'Not Found')

@section('description', 'That page isn\'t here. The work, the services and the writing all still are, and there are links to each of them below.')

@section('body')
    <div class="shell section page-head">
        @include('_components.pixel-mark', [
            'class' => 'pixel-mark--404',
            'label' => '404',
            'pixels' => [
                '#...#.#.###.#.#..##',
                '.#..#.#.#.#.#.#..##',
                '..#.###.#.#.###..##',
                '.#....#.#.#...#..##',
                '#.....#.###...#..##',
            ],
        ])

        <h1>This page does not exist.</h1>
        <p class="lead prose">Either the address is wrong or I moved something without leaving a redirect. Both are
            fixable. Here are the places worth trying.</p>

        <div class="btn-row">
            <a class="btn btn--primary" href="{{ $page->baseUrl }}">
                <span>Home</span>
                @include('_components.icon', ['name' => 'arrow-right', 'class' => 'btn__icon'])
            </a>
            <a class="btn btn--ghost" href="{{ $page->baseUrl }}/services/">
                <span>Services</span>
            </a>
            <a class="btn btn--ghost" href="{{ $page->baseUrl }}/projects/">
                <span>Open source</span>
            </a>
        </div>
    </div>
@endsection
