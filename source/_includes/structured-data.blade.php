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
        '@id' => $pageUrl . '#webpage',
        'url' => $pageUrl,
        'description' => $description,
        'inLanguage' => $page->getLanguage(),
        'isPartOf' => ['@id' => $websiteId],
    ];

    if ($page->isPost($page)) {
        // A post's own title, not the prefixed document title: `name` and
        // `headline` describe the same article and must not disagree.
        $pageNode = array_merge($pageNode, [
            '@type' => 'BlogPosting',
            'name' => $page->title,
            'headline' => $page->title,
            'datePublished' => $page->getCreatedAtDateObject()->format('c'),
            'dateModified' => $page->getUpdatedAtObject()->format('c'),
            'author' => ['@id' => $person['@id']],
            'publisher' => ['@id' => $person['@id']],
            'mainEntityOfPage' => ['@id' => $pageUrl . '#webpage'],
        ]);

        if ($thumbnail) {
            $pageNode['image'] = $thumbnail;
        }

        // A blank `tags:` entry in the post template yields [null], which is
        // truthy but implodes to an empty string.
        $keywords = collect($page->tags)->filter()->implode(', ');

        if ($keywords !== '') {
            $pageNode['keywords'] = $keywords;
        }
    } else {
        $pageNode = array_merge($pageNode, [
            '@type' => 'WebPage',
            'name' => $title,
            // Only a plain page is "about" the site's author; an article is
            // about its own subject.
            'about' => ['@id' => $person['@id']],
        ]);
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
