{{--
    The trail from the home page to this page.

    getBreadcrumbs() in config.php gives the list. The same function gives the
    BreadcrumbList in the JSON-LD. Therefore the markup and the structured data
    always agree.

    Parameters:
      $crumbLabels — replacement words, keyed by segment ('home', 'blog', …).
                     A Persian post gives Persian words.       (optional)
--}}
@php($crumbs = $page->getBreadcrumbs($crumbLabels ?? []))

@if (count($crumbs) > 1)
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <ol class="breadcrumbs__list">
            @foreach ($crumbs as $crumb)
                <li class="breadcrumbs__item">
                    @if ($crumb['current'])
                        {{-- Do not make the current page a link. A link to the
                             same page gives the keyboard a stop with no
                             result. `aria-current` announces the position. --}}
                        <span class="breadcrumbs__current" aria-current="page">{{ $crumb['name'] }}</span>
                    @else
                        <a class="breadcrumbs__link" href="{{ $crumb['url'] }}">{{ $crumb['name'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
