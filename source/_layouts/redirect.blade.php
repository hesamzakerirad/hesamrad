{{--
    A standing redirect for a URL that no longer has a page.

    GitHub Pages serves static files and cannot send a 301, so a stub page is
    the only redirect this site can issue. Search engines treat a meta refresh
    with a zero delay as a redirect and follow it, and the canonical link names
    the destination a second time so the two signals agree.

    The page carries no `noindex`. A `noindex` here would tell a crawler to drop
    the old URL instead of folding it into the new one, which is the opposite of
    what a redirect is for. The sitemap listener leaves these pages out on the
    `redirectTo` property instead.

    Nothing on this page loads a stylesheet or a script bundle. A reader is here
    for a few milliseconds, and a build asset that fails to load would leave them
    looking at an error instead of the page they asked for.
--}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moved</title>
    <link rel="canonical" href="{{ $page->baseUrl }}{{ $page->redirectTo }}">
    <meta http-equiv="refresh" content="0; url={{ $page->redirectTo }}">
    <style>
        body {
            margin: 0;
            padding: 3rem 1.5rem;
            font-family: system-ui, sans-serif;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <p>This page moved. If your browser doesn't take you there,
        <a href="{{ $page->redirectTo }}">follow this link</a>.
    </p>
</body>

</html>
