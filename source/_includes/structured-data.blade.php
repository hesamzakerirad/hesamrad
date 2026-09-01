@php
    /*
     * The home URL, with the trailing slash the host serves. Every node that
     * points at the site must use this one string. `baseUrl` has no trailing
     * slash, and the canonical home page does, so the raw value and the
     * canonical URL are two names for one address.
     */
    $homeUrl = rtrim($page->baseUrl, '/') . '/';

    $websiteId = $page->baseUrl . '/#website';

    /*
     * One person is the whole business, therefore one node carries both. There
     * is no second node for the company.
     *
     * The site had a `ProfessionalService` node beside this one. Two nodes with
     * the same name, the same address and the same social profiles give a
     * search engine two candidates for one entity, and each one gets a part of
     * the evidence. `ProfessionalService` is also a `LocalBusiness`, which must
     * carry a street address, and there is no premises to give. schema.org
     * itself now advises against the type.
     *
     * The service a visitor can buy is not here. This node is on every page,
     * and a page about a post does not sell anything. services.blade.php
     * declares the services, and each one names this node as its provider.
     */
    $person = [
        '@type' => 'Person',
        '@id' => $page->baseUrl . '/#person',
        'name' => $page->siteAuthor,
        'url' => $homeUrl,
        'jobTitle' => $page->siteDescription,
        'email' => $page->email,
        'sameAs' => $page->socialProfiles,
        // Add only a subject the site writes about. This list tells a search
        // engine what the person is an authority on, and a claim with no page
        // behind it is an empty claim.
        'knowsAbout' => [
            'Web application development',
            'Laravel',
            'Next.js',
            'Software architecture',
        ],
        // `knowsLanguage`, not `availableLanguage`. The second belongs to a
        // service or a contact point, and a Person does not accept it.
        'knowsLanguage' => ['English', 'Persian'],
    ];

    // This node has a fixed @id. Each page must describe it in the same way.
    // A page-scoped value makes one entity give conflicting facts.
    $website = [
        '@type' => 'WebSite',
        '@id' => $websiteId,
        'url' => $homeUrl,
        'name' => $page->siteName,
        'description' => $page->siteDescription,
        'inLanguage' => $page->defaultLanguage,
        'publisher' => ['@id' => $person['@id']],
    ];

    $pageNode = [
        '@id' => $pageUrl . '#webpage',
        'url' => $pageUrl,
        'inLanguage' => $page->getLanguage(),
        'isPartOf' => ['@id' => $websiteId],
    ];

    // Do not add an empty description. Omit the property.
    if ($description !== '') {
        $pageNode['description'] = $description;
    }

    if ($page->isPost($page)) {
        // Use the post's own title, not the document title. `name` and
        // `headline` describe the same article, and they must agree.
        $pageNode = array_merge($pageNode, [
            '@type' => 'BlogPosting',
            'name' => $page->title,
            'headline' => $page->title,
            'datePublished' => $page->getCreatedAtDateObject()->format('c'),
            'dateModified' => $page->getUpdatedAtObject()->format('c'),
            'author' => ['@id' => $person['@id']],
            'publisher' => ['@id' => $person['@id']],
            // Google uses this property to connect the article to its page.
            // Without it, the BlogPosting node and the WebPage node stay
            // separate objects in the same document.
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $pageUrl],
        ]);

        /*
         * An article must have an `image` property to be eligible for a rich
         * result. $shareImage is the post's own image, or the default card when
         * the post has no image. The property is thus never absent.
         */
        $pageNode['image'] = $thumbnail ?: $shareImage;

        // A blank `tags:` entry in the post template gives [null]. That array
        // is truthy, but it implodes to an empty string.
        $keywords = collect($page->tags)->filter()->implode(', ');

        if ($keywords !== '') {
            $pageNode['keywords'] = $keywords;
        }
    } else {
        $pageNode = array_merge($pageNode, [
            '@type' => 'WebPage',
            'name' => $title,
            // Only a plain page is "about" the author of the site. An article
            // is about its own subject.
            'about' => ['@id' => $person['@id']],
        ]);
    }

    /*
     * The code makes the breadcrumbs from the URL. Google shows this trail in
     * the search result in place of the raw URL. The label of a section comes
     * from $sectionNames, not from the slug.
     *
     * The home page gets no trail. Google ignores a trail of one item.
     */
    $segments = array_values(array_filter(explode('/', trim($page->getPath(), '/'))));

    $sectionNames = [
        'blog' => 'Blog',
        'work' => 'Work',
    ];

    $crumbs = null;

    // A page with a `noindex` directive gets no trail. The trail changes a
    // search result, and these pages get no search result.
    $isNoIndex = str_contains(strtolower($page->getRobotsStatus()), 'noindex');

    if ($segments !== [] && ! $page->isHomePage() && ! $isNoIndex) {
        $items = [[
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => rtrim($page->getBaseUrl(), '/') . '/',
        ]];

        $trail = '';

        foreach ($segments as $index => $segment) {
            $trail .= '/' . $segment;
            $isLast = $index === count($segments) - 1;

            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 2,
                // The last item takes the name of the page. An intermediate
                // segment is a section, and a slug is not a readable name.
                // Use $pageTitle, not $title. $title can contain the
                // " - Hesam Rad" suffix, which puts the brand in the crumb.
                'name' => $isLast
                    ? ($page->title ?: $pageTitle)
                    : ($sectionNames[$segment] ?? Str::title(str_replace('-', ' ', $segment))),
                'item' => rtrim($page->getBaseUrl(), '/') . $trail . '/',
            ];
        }

        $crumbs = [
            '@type' => 'BreadcrumbList',
            '@id' => $pageUrl . '#breadcrumbs',
            'itemListElement' => $items,
        ];
    }

    /*
     * JSON_HEX_TAG is necessary. `headline` contains raw front matter. A title
     * that contains `</script>` closes this element. The remainder of the
     * document then goes into the page as live markup.
     *
     * The code also encodes here and not in the template body. Blade compiles
     * `@context` in the template body as a directive.
     *
     * array_filter removes $crumbs on the home page, where the value is null.
     */
    $jsonLd = json_encode(
        [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([$person, $website, $pageNode, $crumbs])),
        ],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
    );
@endphp

@if ($jsonLd)
    <script type="application/ld+json">{!! $jsonLd !!}</script>
@endif
