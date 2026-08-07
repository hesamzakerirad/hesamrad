@php
    $person = [
        '@type' => 'Person',
        '@id' => $page->baseUrl . '/#person',
        'name' => $page->siteAuthor,
        'url' => $page->baseUrl,
        'jobTitle' => $page->siteDescription,
        'sameAs' => $page->socialProfiles,
    ];

    $graph = [
        $person,
        [
            '@type' => 'WebSite',
            '@id' => $page->baseUrl . '/#website',
            'url' => $page->baseUrl,
            'name' => $page->siteName,
            'description' => $description,
            'inLanguage' => $page->language,
            'publisher' => ['@id' => $person['@id']],
        ],
    ];

    if ($page->isPost($page)) {
        $graph[] = array_filter([
            '@type' => 'BlogPosting',
            '@id' => rtrim($pageUrl, '/') . '/#article',
            'headline' => $page->title,
            'description' => $description,
            'url' => $pageUrl,
            'datePublished' => $page->getCreatedAtDateObject()->format('c'),
            'dateModified' => $page->getUpdatedAtObject()->format('c'),
            'inLanguage' => $page->language,
            'image' => $page->thumbnail ? $thumbnail : null,
            'keywords' => $page->tags ? implode(', ', $page->tags) : null,
            'author' => ['@id' => $person['@id']],
            'publisher' => ['@id' => $person['@id']],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $pageUrl],
        ]);
    } else {
        $graph[] = [
            '@type' => 'WebPage',
            '@id' => rtrim($pageUrl, '/') . '/#webpage',
            'url' => $pageUrl,
            'name' => $title,
            'description' => $description,
            'inLanguage' => $page->language,
            'isPartOf' => ['@id' => $page->baseUrl . '/#website'],
            'about' => ['@id' => $person['@id']],
        ];
    }
@endphp

<script type="application/ld+json">
    {!! json_encode(['@' . 'context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
