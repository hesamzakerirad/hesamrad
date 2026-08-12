<?php

/*
 * Dev-server router, so `jigsaw serve` behaves like GitHub Pages.
 *
 * `jigsaw serve` runs `php -S` with no router, and PHP's built-in server
 * answers an unknown path with the document root's index file and a 200. The
 * effect is that every typo and every dead link looks like the home page
 * loading correctly, and the 404 page cannot be opened at all except by asking
 * for /404.html by name.
 *
 * GitHub Pages serves /404.html with a 404 status for anything it cannot find.
 * This reproduces that locally and nothing else — returning false hands the
 * request back to PHP so real files are still served by the server itself.
 *
 *     ./vendor/bin/jigsaw serve --router=router.php
 */

$root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$target = $root . urldecode($path);

// A real file: let PHP serve it, headers and all.
if (is_file($target)) {
    return false;
}

// A directory with an index: same, including the redirect PHP does when the
// trailing slash is missing.
if (is_dir($target) && is_file(rtrim($target, '/') . '/index.html')) {
    return false;
}

$notFound = $root . '/404.html';

if (! is_file($notFound)) {
    http_response_code(404);
    echo 'Not found, and no 404.html in this build.';

    return true;
}

http_response_code(404);
header('Content-Type: text/html; charset=UTF-8');
readfile($notFound);

return true;
