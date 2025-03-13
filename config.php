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
    'siteKeyWords' => [
        'مهندسی نرم‌افزار',
        'نرم‌افزار',
        'پی اچ پی',
        'لاراول',
    ],

    // collections
    'collections' => [
        'posts' => [
            'author' => 'حسام راد',
            'sort' => '-date',
            'path' => 'blog/{filename}',
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
    'getDate' => function ($page) {
        return Datetime::createFromFormat('U', $page->date);
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
    'getJalaliDate' => function ($page, $format = '%d %B %Y') {
        return verta($page->getDate())->format($format);
    },
    'getKeyWords' => function ($page) {
        $keywords = $page->pageKeyWords ?? $page->siteKeyWords;

        if ($keywords instanceof \TightenCo\Jigsaw\IterableObject) {
            $keywords = $keywords->toArray();
        }

        return implode('|', $keywords);
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
    'getPostColor' => function ($page) {
        if (! $page->isPost($page)) {
            return '';
        }

        return $page->color ?? '#dbe3f5';
    },
    'getReadTime' => function ($page) {
        return $page->readTime;
    },
];
