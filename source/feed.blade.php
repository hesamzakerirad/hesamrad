---
permalink: /feed.xml
---
@php
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

    // The collection is sorted by -created_at, so first() is the newest post,
    // not the most recently edited one. lastBuildDate means the latter.
    $lastUpdated = $posts->max('updated_at') ?: $posts->max('created_at');
@endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>{{ $page->siteName }} — Blog</title>
        <link>{{ $page->baseUrl }}/blog/</link>
        <description>{{ $page->siteDescription }}</description>
        <language>{{ $page->postLanguage }}</language>
        <atom:link href="{{ $page->baseUrl }}/feed.xml" rel="self" type="application/rss+xml" />
        @if ($lastUpdated)
            <lastBuildDate>{{ (new DateTime('@' . $lastUpdated))->format(DATE_RSS) }}</lastBuildDate>
        @endif
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ $post->getUrlWithTrailingSlash() }}</link>
                <guid isPermaLink="true">{{ $post->getUrlWithTrailingSlash() }}</guid>
                <description>{{ strip_tags($post->description ?: $post->getExcerpt(300)) }}</description>
                <pubDate>{{ $post->getCreatedAtDateObject()->format(DATE_RSS) }}</pubDate>
                <dc:creator>{{ $post->getAuthor() }}</dc:creator>
                <dc:language>{{ $post->getLanguage() }}</dc:language>
            </item>
        @endforeach
    </channel>
</rss>
