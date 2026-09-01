{{--
    The FAQPage node for one page.

    Pass only the questions that the page shows. The data of a rich result must
    agree with the text that the visitor reads. Do not pass the full array on a
    page that shows a part of it.

    More than one page carries this node, and that is correct here. Each page
    declares a different set of questions, because a question belongs to one
    page. Do not put the same set on two pages. Two pages with one identical set
    compete with each other, and a search engine can then accept neither page.

    config.php builds the node, in `faqSchema`. Blade compiles the word after an
    at sign as a directive in the body of a template, so `'@context' => ...`
    written there becomes a call to a `context` directive. It does not do this
    inside a @php block, which the compiler stores before it reads directives,
    so a template can build the node in a block. config.php holds this one
    because more than one page needs it. The identifier of the node holds the
    canonical URL of the page, therefore each node has its own.

    Parameters:
      $items — rows from $page->siteFaq
--}}
<script type="application/ld+json">{!! $page->faqSchema($items) !!}</script>
