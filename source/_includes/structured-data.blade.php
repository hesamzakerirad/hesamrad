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

    /*
     * The business, not just the person.
     *
     * Person/WebSite/WebPage described who owns the site and what a page is,
     * and nothing anywhere said this person sells anything — so a search
     * engine had no basis to treat the site as a service provider at all.
     *
     * Only claims the site already makes in prose: the entry price is the one
     * published on the services page, the area served is the one stated in the
     * footer and the FAQ. Nothing here is an aspiration.
     */
    $business = [
        '@type' => 'ProfessionalService',
        '@id' => $page->baseUrl . '/#business',
        'name' => $page->siteName,
        'url' => $page->baseUrl,
        'description' => $page->siteDescription,
        'founder' => ['@id' => $person['@id']],
        'sameAs' => $page->socialProfiles,
        'email' => $page->email,
        'priceRange' => '$$',
        'areaServed' => [
            ['@type' => 'Place', 'name' => 'Europe'],
            ['@type' => 'Place', 'name' => 'North America'],
        ],
        'availableLanguage' => ['English', 'Persian'],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Software development services',
            'itemListElement' => [
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => 'Zero to One',
                        'description' => 'A complete website for a business that has none: built, launched and looked after, at a fixed price.',
                        'serviceType' => 'Website design and development',
                        'url' => $page->baseUrl . '/zero-to-one/',
                    ],
                    'price' => '1500',
                    'priceCurrency' => 'USD',
                ],
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => 'Custom software',
                        'description' => 'A product built end to end: the screens customers use, the system behind them, and the release process that keeps it running.',
                        'serviceType' => 'Custom software development',
                        'url' => $page->baseUrl . '/services/',
                    ],
                    // No price: custom work is quoted after a plan, and a
                    // number here would be a claim the site does not make.
                    'priceCurrency' => 'USD',
                ],
            ],
        ],
    ];

    $pageNode = [
        '@id' => $pageUrl . '#webpage',
        'url' => $pageUrl,
        'inLanguage' => $page->getLanguage(),
        'isPartOf' => ['@id' => $websiteId],
    ];

    // An empty string is a worse claim than an absent property.
    if ($description !== '') {
        $pageNode['description'] = $description;
    }

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
            // Google reads this to tie the article to the page it lives on.
            // Without it the BlogPosting is a floating object that happens to
            // share a document with a WebPage node, and the pair are never
            // joined up.
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $pageUrl],
        ]);

        /*
         * `image` is required for an article to be eligible for a rich result,
         * and only one of the posts has a thumbnail of its own — the other four
         * were shipping BlogPosting nodes with no image at all and were
         * disqualified before anything else was considered.
         *
         * $shareImage is the post's own picture when it has one and the default
         * card when it does not, so this is never absent and never a lie: the
         * card is what a reader actually sees attached to the link.
         */
        $pageNode['image'] = $thumbnail ?: $shareImage;

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
     * Breadcrumbs, built from the URL rather than hand-maintained per page.
     *
     * Google still renders these in the result, in place of the raw URL — a
     * post currently shows "hesamrad.com › blog › social-media-is-not-a-website"
     * where it could show "Hesam Rad › Blog › Social media is not a website".
     * That is the whole reason to have them, so the labels come from the
     * section's own nav wording rather than from the slug.
     *
     * Skipped on the home page: a one-item trail is not a trail, and Google
     * ignores it.
     */
    $segments = array_values(array_filter(explode('/', trim($page->getPath(), '/'))));

    $sectionNames = [
        'blog' => 'Blog',
        'work' => 'Work',
    ];

    $crumbs = null;

    // Not on a page that asks not to be indexed: the trail exists to shape a
    // search result, and these pages will not have one.
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
                // The leaf is named by the page; an intermediate segment is a
                // section, and a slug is not a name a person would read.
                // $pageTitle, not $title: the latter carries the " — Hesam Rad"
                // suffix, which would put the brand inside the crumb as
                // "Blog — Hesam Rad" on the one page whose whole job is to
                // read as "Blog".
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
     * JSON_HEX_TAG is not optional: `headline` carries raw front matter, and a
     * title containing `</script>` would otherwise close this element and spill
     * the rest of the document out as live markup.
     *
     * Encoding here rather than in the template body also keeps '@context' out
     * of Blade's reach — it compiles `@context` as a directive.
     */
    // array_filter drops the breadcrumbs on the home page, where $crumbs is
    // null, without leaving a hole in the graph.
    $jsonLd = json_encode(
        [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter([$person, $website, $business, $pageNode, $crumbs])),
        ],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
    );
@endphp

@if ($jsonLd)
    <script type="application/ld+json">{!! $jsonLd !!}</script>
@endif
