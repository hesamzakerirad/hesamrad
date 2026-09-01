{{--
    The ItemList for a listing page.

    A listing page is a hub. Without this node it is a page with links on it,
    and a search engine has to work out from the markup which links are the
    contents of the page and which are the navigation. The list states the
    contents, in the order the page shows them.

    Use this only on a page whose items are pages on this site. A list of
    addresses on other sites describes somebody else's pages.

    The page node must agree. The template that includes this component must
    also set `$page->collectionPage = true`, which retypes that node as a
    CollectionPage and points it here. Without the flag the list is a loose
    object; without this component the flag makes a reference to nothing.

    An empty set writes no node. A CollectionPage with no contents is a claim
    about a page with nothing on it.

    config.php cannot build the node, and neither can the body of this file.
    Blade compiles the word after an at sign as a directive in the template
    body, therefore json_encode runs in the block below and the body prints the
    finished string.

    Parameters:
      $items — [['name' => …, 'url' => …], …], in the order the page shows them
--}}
@php
    $listEntries = collect($items)
        ->filter(fn ($item) => trim((string) ($item['name'] ?? '')) !== '' && trim((string) ($item['url'] ?? '')) !== '')
        ->values()
        ->map(fn ($item, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'url' => $item['url'],
        ])
        ->all();

    /*
     * JSON_HEX_TAG is necessary. A name comes from front matter, and a title
     * that contains `</script>` closes this element. The rest of the document
     * then goes into the page as live markup.
     */
    $listJsonLd = $listEntries === [] ? '' : json_encode(
        [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            '@id' => $page->getCanonicalUrl() . '#itemlist',
            'numberOfItems' => count($listEntries),
            'itemListElement' => $listEntries,
        ],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
    );
@endphp

@if ($listJsonLd)
    <script type="application/ld+json">{!! $listJsonLd !!}</script>
@endif
