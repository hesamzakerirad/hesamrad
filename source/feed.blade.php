---
permalink: /feed.xml
disableTitlePrefix: true
---
@php
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $latest = $posts->first();
@endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>{{ $page->siteName }}</title>
        <link>{{ $page->baseUrl }}/blog/</link>
        <description>{{ $page->siteDescription }}</description>
        <language>{{ $page->postLanguage }}</language>
        <atom:link href="{{ $page->baseUrl }}/feed.xml" rel="self" type="application/rss+xml" />
        @if ($latest)
            <lastBuildDate>{{ $latest->getUpdatedAtObject()->format(DATE_RSS) }}</lastBuildDate>
        @endif
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ $post->getUrlWithTrailingSlash() }}</link>
                <guid isPermaLink="true">{{ $post->getUrlWithTrailingSlash() }}</guid>
                <description>{{ $post->description ?: $post->getExcerpt(300) }}</description>
                <pubDate>{{ $post->getCreatedAtDateObject()->format(DATE_RSS) }}</pubDate>
                <dc:creator>{{ $post->getAuthor() }}</dc:creator>
            </item>
        @endforeach
    </channel>
</rss>
