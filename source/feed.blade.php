---
permalink: /feed.xml
---
@php
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

    // lastBuildDate is the newest change of any type.
    // max('updated_at') is not sufficient, because Collection::max ignores a
    // post that has no updated_at. A new post with no revision would not count.
    // Map through property access and do not use a column name, because
    // data_get() causes an error on a front-matter key that is present but
    // blank.
    // Filter on null and do not filter on truthiness, because a created_at of
    // 1970-01-01 gives a correct timestamp of 0.
    $lastUpdated = $posts->map(fn ($post) => $post->getLastModified())
        ->filter(fn ($timestamp) => $timestamp !== null)
        ->max();
@endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>{{ $page->siteName }} · Blog</title>
        <link>{{ $page->baseUrl }}/blog/</link>
        <description>{{ $page->siteTagline }}</description>
        <language>{{ $page->postLanguage }}</language>
        <atom:link href="{{ $page->baseUrl }}/feed.xml" rel="self" type="application/rss+xml" />
        @if ($lastUpdated !== null)
            <lastBuildDate>{{ (new DateTime('@' . $lastUpdated))->format(DATE_RSS) }}</lastBuildDate>
        @endif
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ $post->getCanonicalUrl() }}</link>
                <guid isPermaLink="true">{{ $post->getCanonicalUrl() }}</guid>
                @if (($summary = $post->getSummary(300)) !== '')
                    <description>{{ $summary }}</description>
                @endif
                <pubDate>{{ $post->getCreatedAtDateObject()->format(DATE_RSS) }}</pubDate>
                <dc:creator>{{ $post->getAuthor() }}</dc:creator>
                <dc:language>{{ $post->getLanguage() }}</dc:language>
            </item>
        @endforeach
    </channel>
</rss>
