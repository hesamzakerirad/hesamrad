<?php

use Illuminate\Support\Str;

return [
    'baseUrl' => 'http://localhost:3000',
    'production' => false,
    'siteName' => 'حسام راد',
    'siteDescription' => 'وب‌نوشت‌هایی از دنیای مهندسی نرم‌افزار',
    'siteAuthor' => 'حسام راد',
    'locale' => 'fa_IR',
    'language' => 'fa',

    // collections
    'collections' => [
        'posts' => [
            'author' => 'حسام راد',
            'sort' => '-created_at',
            'path' => 'blog/{filename}/',
            'filter' => fn ($post) => $post->isPublished === true,
        ],
        'pages' => [
            'path' => '{filename}/',
        ],
        'categories' => [
            'path' => '/blog/categories/{filename}/',
            'posts' => function ($page, $allPosts) {
                return $allPosts->filter(function ($post) use ($page) {
                    return $post->categories ? in_array($page->getFilename(), $post->categories, true) : false;
                });
            },
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

    'isActive' => function ($page, $path) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($path));
    },

    'getRobotsStatus' => function ($page) {
        if ($page->robots) {
            return is_array($page->robots) ?
                implode(',', $page->robots) :
                $page->robots;
        }

        return 'index,follow';
    },

    'isPost' => function ($page) {
        return str_contains($page->getPath(), 'blog');
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
];
