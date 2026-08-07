@php
    $websiteId = $page->baseUrl . '/#website';

    $person = [
        '@type' => 'Person',
        '@id' => $page->baseUrl . '/#person',
        'name' => $page->siteAuthor,
        'url' => $page->baseUrl,
        'jobTitle' => $page->siteDescription,
        'sameAs' => $page->socialProfiles,
    ];

    // This node has a fixed @id, so every page must describe it identically —
    // page-scoped values here would make one entity assert conflicting facts.
    $website = [
        '@type' => 'WebSite',
        '@id' => $websiteId,
        'url' => $page->baseUrl,
        'name' => $page->siteName,
        'description' => $page->siteDescription,
        'inLanguage' => $page->defaultLanguage,
        'publisher' => ['@id' => $person['@id']],
    ];

    $pageNode = [
        '@type' => $page->isPost($page) ? 'BlogPosting' : 'WebPage',
        '@id' => $pageUrl . '#webpage',
        'url' => $pageUrl,
        'name' => $title,
        'description' => $description,
        'inLanguage' => $page->getLanguage(),
        'isPartOf' => ['@id' => $websiteId],
        'about' => ['@id' => $person['@id']],
    ];

    if ($page->isPost($page)) {
        $pageNode += [
            'headline' => $page->title,
            'datePublished' => $page->getCreatedAtDateObject()->format('c'),
            'dateModified' => $page->getUpdatedAtObject()->format('c'),
            'author' => ['@id' => $person['@id']],
            'publisher' => ['@id' => $person['@id']],
        ];

        if ($thumbnail) {
            $pageNode['image'] = $thumbnail;
        }

        if ($page->tags) {
            $pageNode['keywords'] = implode(', ', $page->tags);
        }
    }

    /*
     * JSON_HEX_TAG is not optional: `headline` carries raw front matter, and a
     * title containing `</script>` would otherwise close this element and spill
     * the rest of the document out as live markup.
     *
     * Encoding here rather than in the template body also keeps '@context' out
     * of Blade's reach — it compiles `@context` as a directive.
     */
    $jsonLd = json_encode(
        ['@context' => 'https://schema.org', '@graph' => [$person, $website, $pageNode]],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
    );
@endphp

@if ($jsonLd)
    <script type="application/ld+json">{!! $jsonLd !!}</script>
@endif
