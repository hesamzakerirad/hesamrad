{{--
    The FAQPage node for one page.

    Pass only the questions that the page shows. The data of a rich result must
    agree with the text that the visitor reads. Do not pass the full array on a
    page that shows a part of it.

    More than one page carries this node, and that is correct here. Each page
    declares a different set of questions, because a question belongs to one
    page. Do not put the same set on two pages. Two pages with one identical set
    compete with each other, and a search engine can then accept neither page.

    `@id` holds the canonical URL of the page, therefore each node has its own
    identifier.

    Parameters:
      $items — rows from $page->siteFaq
--}}
<script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        '@id' => $page->getCanonicalUrl() . '#faq',
        'mainEntity' => collect($items)->map(fn ($question) => [
            '@type' => 'Question',
            'name' => $question['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => html_entity_decode(strip_tags(implode(' ', collect($question['a'])->all())), ENT_QUOTES, 'UTF-8'),
            ],
        ])->all(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
