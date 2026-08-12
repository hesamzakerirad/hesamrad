{{--
    The trail from the home page to this one.

    Replaces the single back-arrow this site used to carry. A backlink answers
    "how do I leave"; a breadcrumb answers that and "where am I", which on a
    site with two levels of nesting is the more useful question — and it gives
    the section above a tap target of its own.

    The list comes from getBreadcrumbs() in config.php, which also feeds the
    BreadcrumbList in the JSON-LD. Same data, rendered twice, so the markup and
    the structured data cannot disagree.

    $crumbLabels — optional overrides for the fixed words, keyed by segment
                   ('home', 'blog', …). A Persian post passes Persian ones.
--}}
@php($crumbs = $page->getBreadcrumbs($crumbLabels ?? []))

@if (count($crumbs) > 1)
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol class="breadcrumbs__list">
            @foreach ($crumbs as $crumb)
                <li class="breadcrumbs__item">
                    @if ($crumb['current'])
                        {{-- Not a link: linking a page to itself gives the
                             keyboard a stop that goes nowhere. aria-current is
                             how the position is announced instead. --}}
                        <span class="breadcrumbs__current" aria-current="page">{{ $crumb['name'] }}</span>
                    @else
                        <a class="breadcrumbs__link" href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
