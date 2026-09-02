@php
    /*
     * The home URL, with the trailing slash the host serves. Every node that
     * points at the site must use this one string. `baseUrl` has no trailing
     * slash, and the canonical home page does, so the raw value and the
     * canonical URL are two names for one address.
     */
    $homeUrl = rtrim($page->baseUrl, '/') . '/';

    // Build every site-wide identifier from $homeUrl. A raw concatenation of
    // `baseUrl` gives "https://example.com//#website" when the configured value
    // ends with a slash, and the identifier then disagrees with the address.
    $websiteId = $homeUrl . '#website';
    $personId = $homeUrl . '#person';

    /*
     * One person is the whole business, therefore one node carries both. There
     * is no second node for the company.
     *
     * The site had a `ProfessionalService` node beside this one. Two nodes with
     * the same name, the same address and the same social profiles give a
     * search engine two candidates for one entity, and each one gets a part of
     * the evidence. `ProfessionalService` is also a `LocalBusiness`, which must
     * carry a street address. This business has no premises. schema.org itself
     * now advises against the type.
     *
     * The service a visitor can buy is not here. This node is on every page,
     * and a page about a post does not sell anything. services.blade.php
     * declares the services, and each one names this node as its provider.
     */
    $person = [
        '@type' => 'Person',
        '@id' => $personId,
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
        // `knowsLanguage` is the property a Person accepts.
        // `availableLanguage` is not a substitute anywhere on this site: it
        // belongs to a ContactPoint or a ServiceChannel, and schema.org does
        // not define it on Person or on Service.
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
        '@type' => 'WebPage',
        '@id' => $pageUrl . '#webpage',
        'url' => $pageUrl,
        'name' => $title,
        'inLanguage' => $page->getLanguage(),
        'isPartOf' => ['@id' => $websiteId],
    ];

    // Do not add an empty description. Omit the property.
    if ($description !== '') {
        $pageNode['description'] = $description;
    }

    /*
     * The article is a node of its own, beside the page that carries it.
     *
     * The two were one node before, with the identifier of the page and the
     * type of the article. `mainEntityOfPage` then pointed at an identifier
     * that no node in the graph declared, and the reference resolved to
     * nothing. A document and the work printed in it are two things, and the
     * pair of properties below is how they name each other.
     */
    $article = null;
    $imageNode = null;

    if ($page->isPost($page)) {
        // Use the post's own title, not the document title. `name` and
        // `headline` describe the same article, and they must agree. The
        // document title, with its brand suffix, belongs to the page node.
        $article = [
            '@type' => 'BlogPosting',
            '@id' => $pageUrl . '#article',
            'name' => $page->title,
            'headline' => $page->title,
            'url' => $pageUrl,
            'inLanguage' => $page->getLanguage(),
            'datePublished' => $page->getCreatedAtDateObject()->format('c'),
            'dateModified' => $page->getUpdatedAtObject()->format('c'),
            'author' => ['@id' => $person['@id']],
            'publisher' => ['@id' => $person['@id']],
            'isPartOf' => ['@id' => $pageNode['@id']],
            // Google uses this property to connect the article to its page.
            // Without it, the BlogPosting node and the WebPage node stay
            // separate objects in the same document.
            'mainEntityOfPage' => ['@id' => $pageNode['@id']],
        ];

        if ($description !== '') {
            $article['description'] = $description;
        }

        /*
         * An article must have an `image` property to be eligible for a rich
         * result. $shareImage is the post's own image, or the default card when
         * the post has no image. The property is thus never absent.
         *
         * The image is a node and not an address. A bare address gives a search
         * engine no size, and it must then fetch the file to learn whether the
         * picture is large enough for the result it is being considered for.
         * main.blade.php has already measured the file for the share card, so
         * the numbers cost nothing and they are measured, not assumed. A remote
         * address or a missing file gives no size, and the node then carries
         * only the address.
         *
         * $shareImage is already the thumbnail when the post has one, and the
         * default card when it does not, so it needs no second test here.
         */
        $imageNode = [
            '@type' => 'ImageObject',
            '@id' => $pageUrl . '#primaryimage',
            'url' => $shareImage,
        ];

        if ($shareImageSize) {
            $imageNode['width'] = $shareImageSize['width'];
            $imageNode['height'] = $shareImageSize['height'];
        }

        $article['image'] = ['@id' => $imageNode['@id']];
        $pageNode['primaryImageOfPage'] = ['@id' => $imageNode['@id']];

        // A blank `tags:` entry in the post template gives [null]. That array
        // is truthy, but it implodes to an empty string.
        $keywords = collect($page->tags)->filter()->implode(', ');

        if ($keywords !== '') {
            $article['keywords'] = $keywords;
        }

        $pageNode['mainEntity'] = ['@id' => $article['@id']];
    }

    /*
     * `about` names the subject of the page, and only two pages have the person
     * for a subject: the home page and /about/.
     *
     * Each page that was not a post carried this property before. It told a
     * search engine that /services/ is a page about a human being, that /faq/
     * is, and that the 404 page is. A page with no one subject gets no `about`.
     * An absent property says nothing; an incorrect one says something false.
     */
    if (! $article && ($page->isHomePage() || $page->aboutsAuthor)) {
        $pageNode['about'] = ['@id' => $person['@id']];
    }

    /*
     * Nodes the page itself contributes.
     *
     * A page template fills `$page->schemaNodes` in its own @php block, which
     * Blade runs before it renders this layout. The nodes then join the one
     * @graph below.
     *
     * They must not be a second <script> of their own. A bare {"@id": …} that
     * points outside its own script block is a dangling reference: Google reads
     * each block as a separate item, so a case study that named the Person node
     * in another block had no author at all, and `author` is the one Article
     * property Google requires. One graph, one set of identifiers.
     *
     * There is no flag to keep in step with a list any more. The page node
     * takes its type and its `mainEntity` from what is actually here, so a
     * reference to a node that was never built cannot be written.
     */
    $extraNodes = collect($page->schemaNodes ?? [])
        ->filter(fn ($node) => is_array($node) && isset($node['@id']))
        ->values();

    $nodeOfType = fn (array $types) => $extraNodes
        ->first(fn ($node) => in_array($node['@type'] ?? '', $types, true));

    // The work printed on the page: a case study's Article. A post builds its
    // own above, and a page never has both.
    if (! $article && $work = $nodeOfType(['Article', 'BlogPosting'])) {
        $pageNode['mainEntity'] = ['@id' => $work['@id']];

        if (isset($work['image']['@id'])) {
            $pageNode['primaryImageOfPage'] = $work['image'];
        }
    }

    // A listing page. The contents are the subject of the page.
    if (! $article && $list = $nodeOfType(['ItemList'])) {
        $pageNode['@type'] = 'CollectionPage';
        $pageNode['mainEntity'] = ['@id' => $list['@id']];
    }

    /*
     * The breadcrumbs come from getBreadcrumbs(), which is also what
     * _components/breadcrumbs.blade.php draws. Google ignores the trail when
     * the markup and the structured data disagree, so there must be one list.
     *
     * This block used to rebuild the trail from the URL with a second section
     * map and a second humaniser. They already disagreed: config.php names
     * /projects/ "Open source" and this file did not, and `ucfirst` and
     * `Str::title` case a slug differently.
     *
     * The home page gets no trail. Google ignores a trail of one item.
     */
    $crumbs = null;

    // A page with a `noindex` directive gets no trail. The trail changes a
    // search result, and these pages get no search result.
    $isNoIndex = str_contains(strtolower($page->getRobotsStatus()), 'noindex');

    $trail = $isNoIndex ? [] : $page->getBreadcrumbs();

    if (count($trail) > 1) {
        $crumbs = [
            '@type' => 'BreadcrumbList',
            '@id' => $pageUrl . '#breadcrumbs',
            'itemListElement' => collect($trail)
                ->map(fn ($crumb, $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $crumb['name'],
                    'item' => $crumb['url'],
                ])
                ->all(),
        ];

        // The trail belongs to this page. Without the reference, the list is a
        // loose object that shares the document with the page and says nothing
        // about it.
        $pageNode['breadcrumb'] = ['@id' => $crumbs['@id']];
    }

    /*
     * JSON_HEX_TAG is necessary. `headline` contains raw front matter. A title
     * that contains `</script>` closes this element. The remainder of the
     * document then goes into the page as live markup.
     *
     * The code also encodes here and not in the template body. Blade compiles
     * `@context` in the template body as a directive. Inside a @php block it
     * does not: the compiler stores raw PHP blocks before it reads directives.
     *
     * array_filter removes $article, $imageNode and $crumbs where the page
     * built none of them.
     */
    $jsonLd = json_encode(
        [
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_merge(
                array_filter([$person, $website, $pageNode, $article, $imageNode, $crumbs]),
                $extraNodes->all()
            )),
        ],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
    );
@endphp

@if ($jsonLd)
    <script type="application/ld+json">{!! $jsonLd !!}</script>
@endif
