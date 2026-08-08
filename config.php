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

    /**
     * A front-matter date as a Unix timestamp, or null if it isn't usable.
     *
     * Symfony YAML coerces an unquoted `2025-01-01` to an integer, so anything
     * else — a quoted string, a float, whitespace — is an authoring mistake.
     * is_numeric is too loose (it accepts floats and padding that
     * createFromFormat('U') then rejects, fatalling on the callers' `: DateTime`
     * return type) but ctype_digit is too strict: any date before 1970 is a
     * negative integer, and so is a Jalali year written as `1403-05-16`.
     */
    'getTimestamp' => function ($page, $value) {
        if (is_int($value)) {
            return $value;
        }

        return is_string($value) && preg_match('/^-?\d+$/', $value) ? (int) $value : null;
    },

    'getCreatedAtDateObject' => function ($page): DateTime {
        $timestamp = $page->getTimestamp($page->created_at);

        if ($timestamp === null) {
            throw new InvalidArgumentException(
                "'{$page->getPath()}' needs a created_at date written as an unquoted YYYY-MM-DD."
            );
        }

        return Datetime::createFromFormat('U', (string) $timestamp);
    },

    /**
     * updated_at is optional; a post that has never been revised falls back to
     * its creation date. Shares getLastModified's later-of-the-two rule so that
     * `dateModified` can never precede `datePublished`, which is a structured
     * data validation error.
     */
    'getUpdatedAtObject' => function ($page): DateTime {
        $timestamp = $page->getLastModified();

        return $timestamp === null
            ? $page->getCreatedAtDateObject()
            : Datetime::createFromFormat('U', (string) $timestamp);
    },

    /**
     * The timestamp a page was last meaningfully changed, or null for a page
     * that carries no dates at all (the sitemap then falls back to git).
     *
     * Takes the later of the two dates rather than trusting updated_at, so an
     * updated_at accidentally set earlier than created_at cannot publish a
     * lastmod that predates the post's own pubDate.
     */
    'getLastModified' => function ($page) {
        $dates = array_filter([
            $page->getTimestamp($page->created_at),
            $page->getTimestamp($page->updated_at),
        ], fn ($timestamp) => $timestamp !== null);

        return $dates ? max($dates) : null;
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
     * Collapses whitespace and bounds a string to $length, cutting on a word
     * boundary. Leaves the text otherwise untouched.
     */
    'toSummaryText' => function ($page, $text, $length = null) {
        // preg_replace returns null on malformed UTF-8; keeping the original is
        // better than silently collapsing the whole summary to an empty string.
        $collapsed = preg_replace('/\s+/u', ' ', (string) $text) ?? (string) $text;
        $cleaned = trim($collapsed);

        if ($length === null || mb_strlen($cleaned) <= $length) {
            return $cleaned;
        }

        // Multibyte-aware: byte-wise truncation splits Persian characters, and
        // the resulting invalid UTF-8 makes json_encode() fail outright.
        $truncated = mb_substr($cleaned, 0, $length);

        // `??`, not `?:` — a trimmed result of "0" is a valid summary, and
        // treating it as failure would fall back to the untrimmed cut.
        $trimmed = preg_replace('/\s+\S*$/u', '', $truncated) ?? $truncated;

        return rtrim($trimmed === '' ? $truncated : $trimmed).'…';
    },

    /**
     * A plain-text summary of a page's opening content.
     *
     * getContent() is rendered HTML, so tags are stripped and entities decoded
     * — every consumer (meta description, OG/Twitter cards, JSON-LD, the feed)
     * needs plain text.
     */
    'getExcerpt' => function ($page, $length = 255) {
        if ($page->excerpt) {
            return $page->toSummaryText($page->excerpt, $length);
        }

        // A <!-- more --> marker chooses where the body is cut, but the caller's
        // budget still applies — these summaries land in fixed-size meta tags.
        $content = preg_split('/<!-- more -->/m', $page->getContent(), 2);
        $body = preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content[0]);
        $text = html_entity_decode(strip_tags((string) $body), ENT_QUOTES, 'UTF-8');

        return $page->toSummaryText($text, $length);
    },

    /**
     * The description used for meta tags, cards, JSON-LD and the feed.
     *
     * A front-matter description is authored plain text, so it is bounded but
     * never stripped — removing markup an author typed deliberately would
     * silently rewrite their words.
     */
    'getSummary' => function ($page, $length = 255) {
        // Compare against '' rather than testing truthiness: a description of
        // "0" is something the author wrote and must not be thrown away.
        $description = $page->toSummaryText($page->description, $length);

        return $description !== '' ? $description : $page->getExcerpt($length);
    },

    'getRobotsStatus' => function ($page) {
        // List-form front matter arrives as a plain array on collection items
        // but as an IterableObject on regular pages; stringifying the latter
        // would emit a JSON array as the directive. A list of blank entries —
        // the shape the post template ships — filters down to nothing, so the
        // default has to be applied after the filter, not before it.
        $robots = $page->robots;

        if (is_array($robots) || $robots instanceof Traversable) {
            $directives = collect($robots)->filter()->implode(',');
        } elseif (is_string($robots)) {
            $directives = trim($robots);
        } else {
            // A YAML bool or number is not a directive; `robots: true` would
            // otherwise stringify to the meaningless content="1".
            $directives = '';
        }

        return $directives !== '' ? $directives : 'index,follow';
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

    /**
     * The primary subtag of a page's language, so 'fa-IR' resolves the same as
     * 'fa'. Anything keyed by language — direction, the post chrome's wording —
     * has to agree on what "the language" is, so they all go through this.
     */
    'getBaseLanguage' => function ($page) {
        return strtolower(strtok($page->getLanguage(), '-_'));
    },

    'getDirection' => function ($page) {
        // rtlLanguages arrives as an IterableObject (a Collection), not an array.
        return collect($page->rtlLanguages)->contains($page->getBaseLanguage()) ? 'rtl' : 'ltr';
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
