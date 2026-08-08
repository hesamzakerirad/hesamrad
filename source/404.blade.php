---
title: Not Found!
permalink: /404.html
robots: noindex,follow
---

@extends('_layouts.page')

@section('title', 'Not Found!')

@section('content')
    <p>Sorry, the page you are looking for does not exist. Why don't we head back <a href="{{ $page->baseUrl }}">home</a>?</p>
@endsection
