<?php

return [
    'baseUrl' => 'http://localhost:8000',
    'production' => false,
    'siteName' => 'Hesam Rad',
    'siteDescription' => 'Software Engineer',
    'siteAuthor' => 'Hesam Rad',
    // Defaults. Individual pages override these via `locale`/`language` front
    // matter; posts fall back to the post-specific pair below.
    'defaultLocale' => 'en_US',
    'defaultLanguage' => 'en',
    'postLocale' => 'fa_IR',
    'postLanguage' => 'fa',

    'socialProfiles' => [
        'https://linkedin.com/in/hesamrad',
        'https://github.com/hesamzakerirad',
        'https://x.com/hesamzakerirad',
    ],

    // collections
    'collections' => [
        'posts' => [
            'author' => 'Hesam Rad',
            'sort' => '-created_at',
            'path' => 'blog/{filename}/',
            'filter' => fn ($post) => $post->isPublished === true,
        ],
        'pages' => [
            'path' => '{filename}/',
        ],
    ],

    'getCreatedAtDateObject' => function ($page): DateTime {
        return Datetime::createFromFormat('U', (string) $page->created_at);
    },

    'getUpdatedAtObject' => function ($page): DateTime {
        return Datetime::createFromFormat('U', (string) $page->updated_at);
    },

    'getCreatedAtDate' => function ($page, $format = 'Y-m-d'): string {
        return $page->getCreatedAtDateObject()->format($format);
    },

    'getUpdatedAtDate' => function ($page, $format = 'Y-m-d'): string {
        return $page->getUpdatedAtObject()->format($format);
    },

    'getJalaliDate' => function ($page, $format = '%d %B %Y'): string {
        return verta($page->getCreatedAtDate())->format($format);
    },

    'getUpdatedJalaliDate' => function ($page, $format = '%d %B %Y'): string {
        return verta($page->getUpdatedAtDate())->format($format);
    },

    'getExcerpt' => function ($page, $length = 255) {
        if ($page->excerpt) {
            return $page->excerpt;
        }

        $content = preg_split('/<!-- more -->/m', $page->getContent(), 2);
        $cleaned = trim(
            strip_tags(
                preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content[0]),
                '<code>'
            )
        );

        if (count($content) > 1) {
            return $cleaned;
        }

        $truncated = substr($cleaned, 0, $length);

        if (substr_count($truncated, '<code>') > substr_count($truncated, '</code>')) {
            $truncated .= '</code>';
        }

        return strlen($cleaned) > $length
            ? preg_replace('/\s+?(\S+)?$/', '', $truncated).'...'
            : $cleaned;
    },

    'getRobotsStatus' => function ($page) {
        if ($page->robots) {
            return is_array($page->robots) ?
                implode(',', $page->robots) :
                $page->robots;
        }

        return 'index,follow';
    },

    'getLanguage' => function ($page) {
        return $page->language ?? ($page->isPost($page) ? $page->postLanguage : $page->defaultLanguage);
    },

    'getLocale' => function ($page) {
        return $page->locale ?? ($page->isPost($page) ? $page->postLocale : $page->defaultLocale);
    },

    'getDirection' => function ($page) {
        return in_array($page->getLanguage(), ['fa', 'ar', 'he', 'ur']) ? 'rtl' : 'ltr';
    },

    'getAuthor' => function ($page) {
        return $page->author ?? $page->siteName;
    },

    'isPost' => function ($page) {
        // 'blog' is the listing page; only 'blog/{slug}' is an actual post.
        return str_starts_with(trim($page->getPath(), '/'), 'blog/');
    },

    'getReadTime' => function ($page) {
        return $page->readTime;
    },

    'isHomePage' => function ($page) {
        return $page->getPath() === '' ||
               $page->getPath() === '/' ||
               $page->getPath() === 'index';
    },

    // Override URL generator (safety net)
    'getUrlWithTrailingSlash' => function ($page) {
        $url = rtrim($page->getBaseUrl(), '/').'/'.ltrim($page->getPath(), '/');

        return $url.(str_ends_with($url, '/') ? '' : '/');
    },

    'getMyYearsOfExperience' => function () {
        return date('Y') - 2018;
    },
];
