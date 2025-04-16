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
            'path' => 'blog/{filename}',
            'filter' => fn ($post) => $post->isPublished === true, 
        ],
        'categories' => [
            'path' => '/blog/categories/{filename}',
            'posts' => function ($page, $allPosts) {
                return $allPosts->filter(function ($post) use ($page) {
                    return $post->categories ? in_array($page->getFilename(), $post->categories, true) : false;
                });
            },
        ],
    ],

    // helpers
    'getDate' => function ($page, $format = 'Y-m-d'): string {
        $datetime = Datetime::createFromFormat('U', (string) $page->created_at);

        return $datetime->format($format);
    },

    'getUpdatedDate' => function ($page, $format = 'Y-m-d'): string {
        $datetime = Datetime::createFromFormat('U', (string) $page->updated_at);

        return $datetime->format($format);
    },

    'getJalaliDate' => function ($page, $format = '%d %B %Y'): string {
        return verta($page->getDate())->format($format);
    },

    'getUpdatedJalaliDate' => function ($page, $format = '%d %B %Y'): string {
        return verta($page->getUpdatedDate())->format($format);
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
            ? preg_replace('/\s+?(\S+)?$/', '', $truncated) . '...'
            : $cleaned;
    },

    'isActive' => function ($page, $path) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($path));
    },

    'getRobotsStatus' => function ($page) {
        if ($page->isExternal) {
            return 'noindex,nofollow';
        }

        return 'index,follow';
    },

    'isPost' => function ($page) {
        return str_contains($page->getPath(), 'blog');
    },

    'getReadTime' => function ($page) {
        return $page->readTime;
    },
];
