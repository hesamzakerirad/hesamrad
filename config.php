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
    'rtlLanguages' => ['fa', 'ar', 'he', 'iw', 'ur'],

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

    // A quoted `created_at: '2025-01-01'` stays a string instead of being coerced
    // to a timestamp, so check for a usable value rather than merely a present
    // one — otherwise createFromFormat returns false and the return type fatals
    // with an error that says nothing about which post is at fault.
    'getCreatedAtDateObject' => function ($page): DateTime {
        if (! is_numeric($page->created_at)) {
            throw new InvalidArgumentException(
                "'{$page->getPath()}' needs a created_at date written as an unquoted YYYY-MM-DD."
            );
        }

        return Datetime::createFromFormat('U', (string) $page->created_at);
    },

    // updated_at is optional; a post that has never been revised falls back to
    // its creation date. Returning false here would be a fatal TypeError.
    'getUpdatedAtObject' => function ($page): DateTime {
        return is_numeric($page->updated_at)
            ? Datetime::createFromFormat('U', (string) $page->updated_at)
            : $page->getCreatedAtDateObject();
    },

    /** The timestamp a page was last meaningfully changed. */
    'getLastModified' => function ($page) {
        return is_numeric($page->updated_at) ? $page->updated_at : $page->created_at;
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

    /**
     * A plain-text summary of a page's opening content.
     *
     * Every consumer (meta description, OG/Twitter cards, JSON-LD, the RSS
     * feed) needs plain text, so this strips markup and decodes entities
     * rather than leaving HTML for each caller to clean up.
     */
    'getExcerpt' => function ($page, $length = 255) {
        if ($page->excerpt) {
            return $page->excerpt;
        }

        $content = preg_split('/<!-- more -->/m', $page->getContent(), 2);
        $stripped = strip_tags(
            preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content[0])
        );

        // getContent() returns rendered HTML, so entities have to be decoded or
        // an ampersand in the body reaches the page as a literal '&amp;'.
        $cleaned = trim(preg_replace('/\s+/u', ' ', html_entity_decode($stripped, ENT_QUOTES, 'UTF-8')));

        if (count($content) > 1 || mb_strlen($cleaned) <= $length) {
            return $cleaned;
        }

        // Multibyte-aware: byte-wise truncation splits Persian characters, and
        // the resulting invalid UTF-8 makes json_encode() fail outright.
        $truncated = mb_substr($cleaned, 0, $length);

        // preg_replace returns null on malformed input; keep the hard cut then.
        return (preg_replace('/\s+?(\S+)?$/u', '', $truncated) ?? $truncated).'...';
    },

    /** The description used for meta tags, cards, JSON-LD and the feed. */
    'getSummary' => function ($page, $length = 255) {
        return $page->description ?: $page->getExcerpt($length);
    },

    'getRobotsStatus' => function ($page) {
        if (! $page->robots) {
            return 'index,follow';
        }

        // List-form front matter arrives as a plain array on collection items
        // but as an IterableObject on regular pages; stringifying the latter
        // would emit a JSON array as the directive.
        if (is_array($page->robots) || $page->robots instanceof Traversable) {
            return implode(',', collect($page->robots)->filter()->all());
        }

        return $page->robots;
    },

    // Front matter wins when set. `??` alone is not enough: a blank `language:`
    // key parses as an empty string, not null, and would emit lang="".
    'getLanguage' => function ($page) {
        return trim((string) $page->language)
            ?: ($page->isPost($page) ? $page->postLanguage : $page->defaultLanguage);
    },

    'getLocale' => function ($page) {
        return trim((string) $page->locale)
            ?: ($page->isPost($page) ? $page->postLocale : $page->defaultLocale);
    },

    // Compare on the primary subtag so 'fa-IR' resolves the same as 'fa'.
    'getDirection' => function ($page) {
        $primarySubtag = strtolower(strtok($page->getLanguage(), '-_'));

        // rtlLanguages arrives as an IterableObject (a Collection), not an array.
        return collect($page->rtlLanguages)->contains($primarySubtag) ? 'rtl' : 'ltr';
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

    /**
     * The single URL a page identifies itself by — canonical, og:url, JSON-LD.
     *
     * getUrlWithTrailingSlash() is derived from the page path and does not know
     * about `permalink`, so a page that sets one (the 404 lives at /404.html)
     * would otherwise advertise a directory URL that the build never emits.
     */
    'getCanonicalUrl' => function ($page) {
        if ($page->isHomePage()) {
            return $page->getBaseUrl();
        }

        if ($page->permalink) {
            return rtrim($page->getBaseUrl(), '/').'/'.ltrim($page->permalink, '/');
        }

        return $page->getUrlWithTrailingSlash();
    },

    'getMyYearsOfExperience' => function () {
        return date('Y') - 2018;
    },
];
