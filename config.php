<?php

/*
 * Hoisted out of the array below because the case-studies collection filter
 * needs to close over it, and a PHP array literal cannot reference its own
 * keys. Flip this to true once there are real case studies to show.
 */
$workIsPublic = true;

/*
 * Hoisted for the same reason: the FAQ answers quote these figures, and an
 * array literal cannot reference its own keys.
 */
$pricing = [
    'setup' => 1500,
    'monthly' => 50,
    'turnaround' => '~1 week',
    'symbol' => '$',
    'currency' => 'USD',
];

$money = fn ($amount) => $pricing['symbol'] . number_format($amount);

return [
    'baseUrl' => 'http://localhost:8000',
    'production' => false,
    'siteName' => 'Hesam Rad',
    // Reused as the JSON-LD `jobTitle`, so it has to stay a job title. The feed
    // and anywhere else that wants a sentence uses `siteTagline` instead.
    'siteDescription' => 'Independent software engineer',
    'siteTagline' => 'Notes from the work: whatever I am building, using, or working out at the time.',
    'siteAuthor' => 'Hesam Rad',
    // One source of truth for the address. It appears in the footer, in the
    // contact form's fallback, on the thank-you page and in the structured
    // data — six places that must never disagree about where mail goes.
    'email' => 'hesamrad.dev@gmail.com',
    // Defaults. Individual pages override these via `locale`/`language` front
    // matter; posts fall back to the post-specific pair below.
    //
    // The site is English. The Persian/RTL machinery below stays wired up — a
    // post can still opt in with `language: fa` front matter — but it is no
    // longer what an unmarked post gets by default.
    'defaultLocale' => 'en_US',
    'defaultLanguage' => 'en',
    'postLocale' => 'en_US',
    'postLanguage' => 'en',
    'rtlLanguages' => ['fa', 'ar', 'he', 'iw', 'ur'],

    // Drives the nav entry, the listing page's robots directive, and whether
    // sample case studies are generated at all. Set at the top of this file.
    'workIsPublic' => $workIsPublic,

    // The contact form posts to a Cloudflare Worker (see worker/), because the
    // site is static and has no server of its own. Both of these are public by
    // design — the Turnstile *secret* and every API key live as Worker secrets
    // and never enter this repository.
    'formEndpoint' => 'https://hello.hesamrad.com',
    'turnstileSiteKey' => '0x4AAAAAAEMSHcNbdcoTgz3f',

    /*
     * Recommendations, quoted from LinkedIn.
     *
     * Real ones only. These are the strongest thing on the site precisely
     * because a visitor can click through and find the person who wrote them,
     * which stops working the moment one of them is invented.
     *
     * `url` should point at the recommendation or the author's profile — a
     * quote somebody can verify is worth several they cannot.
     *
     * `avatar` is optional. It takes any image URL, but do not leave a
     * LinkedIn one there: media.licdn.com serves signed URLs that expire,
     * usually within weeks, and blocks cross-origin embedding. Save the file
     * into source/_assets/images/ and point at that instead — then it is
     * permanent, fast, and not a request to somebody else's server on every
     * page load. If the image ever fails, the initials underneath show
     * through on their own.
     *
     * Worth asking before using someone's photograph. Quoting a public
     * recommendation is ordinary; putting their face on a commercial page is
     * a step further, and people generally say yes when asked.
     */
    /*
     * Zero to One's numbers.
     *
     * These were written out in eight places across three files — the price
     * panel, twice in the prose around it, the term list, the services FAQ and
     * the structured data. A price that appears eight times gets raised in
     * seven, and the one left behind is the one a client reads first.
     *
     * Amounts are integers, so the comma in "$1,500" is derived rather than
     * typed and cannot drift from the figure. `currency` is the ISO code the
     * structured data needs; `symbol` is what a reader sees.
     *
     * `turnaround` is display text rather than a number: it is a promise about
     * a range, and "~1 week" is doing work that "7" would not.
     */
    'pricing' => $pricing,

    /*
     * Where the full set lives. The section quotes a few; this is the door to
     * the rest, and to the fact that they are on somebody else's site under
     * their own names — which is the part that makes them worth anything.
     *
     * Set to null to drop the link without touching the component.
     */
    /*
     * Every question, in one place.
     *
     * Two pages render these: /faq/ shows all of them and carries the FAQPage
     * schema, and /services/ shows the subset a buyer asks before committing.
     * Kept here rather than in either template so the two cannot drift into
     * saying different things about the same question.
     *
     * `services => true` marks a question as buyer-critical enough to repeat on
     * the services page. `open => true` opens it on load.
     *
     * Answers are arrays of paragraphs. Anything about money reads the pricing
     * block rather than restating a figure.
     */
    'faq' => [
        // ── Before we start ──────────────────────────────────────────────
        [
            'group' => 'Before we start',
            'q' => 'What if I do not know exactly what I want yet?',
            'services' => 3,
            'a' => [
                'That\'s the normal case, and it\'s what the first call is for. You don\'t need a specification written. You need to be able to describe what\'s wrong today and what you want instead. Working out what that means in software is part of the job I\'m being paid for, not something you have to finish before we start.',
            ],
        ],
        [
            'group' => 'Before we start',
            'q' => 'What happens on the first call?',
            'a' => [
                'Thirty minutes, and you do the talking for most of it: what the business does, what isn\'t working, and what you want instead.',
                'Afterwards you get a written plan — what I\'d build, in what order, what it would cost, and what I think could go wrong. It\'s free and it\'s yours to keep, including to take to another developer. If I\'m not the right person for the job, that\'s the call where I say so.',
            ],
            'link' => ['href' => '/services/', 'label' => 'How the work runs'],
        ],
        [
            'group' => 'Before we start',
            'q' => 'How quickly will you reply?',
            'a' => [
                'Within a day, usually sooner. I read every enquiry myself, because there\'s nobody else here to read them.',
            ],
        ],
        [
            'group' => 'Before we start',
            'q' => 'What do you need from me to get started?',
            'a' => [
                'Half an hour on a call, and candour in it. For a Zero to One website that\'s the whole of your homework. I write the words from what you tell me, because sitting down to write a page about your own business is the step that stalls most websites for months.',
                'For larger work you\'ll also need to be reachable for a short call each week. Nothing else is needed up front: no specification, no wireframes, no list of features.',
            ],
        ],

        // ── What it costs ────────────────────────────────────────────────
        [
            'group' => 'What it costs',
            'q' => 'What does it cost?',
            'services' => 1,
            'open' => true,
            'a' => [
                'The cheapest way in is Zero to One: a fixed website for ' . $pricing['symbol'] . number_format($pricing['setup']) . ', plus ' . $pricing['symbol'] . number_format($pricing['monthly']) . ' a month to keep it running. For a lot of businesses that\'s the whole answer.',
                'Anything past that — payments, ordering, booking, a system built around how your business runs — is a fixed price for a defined project, or a monthly arrangement for ongoing work. Either way the number comes once the plan is written, so it reflects the work in front of us rather than an hourly guess, and you have it before you commit to anything.',
            ],
            'link' => ['href' => '/zero-to-one/', 'label' => 'How Zero to One works'],
        ],
        [
            'group' => 'What it costs',
            'q' => 'Do you charge by the hour?',
            'a' => [
                'No. You get a number for the work, quoted after the plan is written, so you know what it costs before you commit instead of watching a meter run.',
                'It also means I carry the risk if something takes longer than I thought. That\'s the right way round: I\'m the one who estimated it.',
            ],
        ],
        [
            'group' => 'What it costs',
            'q' => 'What does the monthly fee cover?',
            'a' => [
                'On Zero to One, ' . $pricing['symbol'] . number_format($pricing['monthly']) . ' a month covers hosting, the domain renewal, security updates, backups, and small changes when you need them: new opening hours, a price change, a few new photos. Email me and it gets done.',
                'On larger projects a monthly arrangement is optional, and it covers whatever we agree it covers, written down before it starts.',
            ],
        ],
        [
            'group' => 'What it costs',
            'q' => 'Can I stop paying the monthly fee?',
            'a' => [
                'Then stop. There\'s no minimum term. The domain is registered to you, and I\'ll hand over everything so you or anyone else can pick it up. Nothing in the paperwork keeps you here.',
            ],
        ],

        // ── How long it takes ────────────────────────────────────────────
        [
            'group' => 'How long it takes',
            'q' => 'How long does it take?',
            'services' => 2,
            'a' => [
                'Most projects run two to six weeks from the plan being agreed to something your customers can use. Bigger builds take longer, and I\'ll say so in the plan instead of discovering it halfway through.',
            ],
        ],
        [
            'group' => 'How long it takes',
            'q' => 'Why is Zero to One about a week when other work takes months?',
            'a' => [
                'Because it\'s the same defined list every time — a website, the words, the domain and hosting, and your Google listing — with one round of changes. The narrow scope is what makes a week possible. Nothing is being rushed to fit it.',
                'The moment a business needs online ordering or a booking system, it\'s a different job with a different number, and I\'ll tell you that instead of squeezing it in.',
            ],
            'link' => ['href' => '/zero-to-one/', 'label' => 'What is and is not included'],
        ],

        // ── What I build ─────────────────────────────────────────────────
        [
            'group' => 'What I build',
            'q' => 'What do you actually build?',
            'a' => [
                'Web applications that work as well on a phone as on a laptop. I build both halves — what your customers see and the system running behind it — so there\'s no seam between them and nobody to coordinate with.',
                'Where a client already has a designer or a front-end team, I take the half they can\'t do and stay out of the way of the half they can. That\'s usually the cheaper arrangement for them.',
            ],
            'link' => ['href' => '/work/', 'label' => 'Both projects ran that way'],
        ],
        [
            'group' => 'What I build',
            'q' => 'Do you work with my existing designer or developer?',
            'a' => [
                'Often, and it usually costs you less. If you already have a designer or a front-end team, I take the part they can\'t do and leave the part they can.',
                'What I won\'t do is join a team as an extra pair of hands with no say in how the thing gets built. That arrangement produces software nobody is responsible for.',
            ],
        ],
        [
            'group' => 'What I build',
            'q' => 'What will you not take on?',
            'a' => [
                'Brand and logo design, apps written natively for iPhone and Android (I build web apps that work properly on a phone instead), and anything where the plan is to skip testing to hit a date. I\'ll say so on the first call, not three weeks in.',
            ],
        ],
        [
            'group' => 'What I build',
            'q' => 'What is not included in Zero to One?',
            'a' => [
                'A visual identity invented from scratch. The site is built for your business, but the look isn\'t designed from nothing — that\'s a separate job at a separate price. Logos, branding and photography aren\'t part of it either.',
                'Payments, online ordering, booking systems and customer logins are all proper work rather than a box to tick, so they\'re quoted separately.',
            ],
            'link' => ['href' => '/zero-to-one/', 'label' => 'The full list'],
        ],

        // ── What you own ─────────────────────────────────────────────────
        [
            'group' => 'What you own',
            'q' => 'Do I own what you build?',
            'a' => [
                'All of it, from the first day. The code lives in your repository, it runs on your hosting account, and the domain stays registered to you. I work inside your accounts rather than mine, so there\'s nothing to prise loose at the end and nothing of yours sitting in my name.',
            ],
        ],
        [
            'group' => 'What you own',
            'q' => 'Could I hand this to another developer later?',
            'a' => [
                'Yes, and it\'s the test I hold the work to. You own the accounts and the code, the setup runs from written instructions, and the tests say whether something is broken.',
                'If handing it on would be painful, I\'ve done the job badly, whatever else is true about the software.',
            ],
        ],

        // ── Working together ─────────────────────────────────────────────
        [
            'group' => 'Working together',
            'q' => 'How do we work together?',
            'a' => [
                'Remotely, and I\'ve worked this way for most of my career by choice, not by accident. My clients are across Europe and North America, and I arrange my day around whichever of those you\'re in, so there are hours every day when you can reach me directly instead of taking a queue position.',
                'Most people settle into a short call once a week plus email in between. If you\'d rather have more or less than that, say so and we\'ll do that instead.',
            ],
        ],
        [
            'group' => 'Working together',
            'q' => 'What happens if you are unavailable?',
            'services' => 4,
            'a' => [
                'I\'m one person, so let me answer that properly instead of waving it away. There\'s no second developer waiting in the wings.',
                'What there is: you own every account and every line of code from day one, and I write things down as I go — a setup another developer can run, tests that say whether something is broken, and a walkthrough at handover. If I vanished tomorrow you wouldn\'t be locked out of anything, and someone competent could carry on from what\'s written.',
                'That\'s a smaller risk than being unable to reach the agency holding your source code. It isn\'t zero, and you should hear it from me rather than find it out later.',
            ],
        ],
        [
            'group' => 'Working together',
            'q' => 'Should I hire you or an agency?',
            'a' => [
                'Sometimes an agency. If the job needs several disciplines at once, has a deadline you can\'t move, or is large enough that coordinating it is a job in itself, buy the coordination.',
                'If it\'s one system that has to be right and stay right, the distance between you and the person building it is the thing worth protecting. I\'ve written the comparison out in full, including the parts that don\'t favour me.',
            ],
            'link' => ['href' => '/blog/agency-or-one-independent-engineer/', 'label' => 'The full comparison'],
        ],

        // ── After it launches ────────────────────────────────────────────
        [
            'group' => 'After it launches',
            'q' => 'What happens after it launches?',
            'a' => [
                'There\'s an agreed period where anything I built that turns out to be broken gets fixed at no extra cost. After that, some people want a monthly arrangement for changes and monitoring, and some take it in-house. The documentation exists so that second option is genuinely open to you. Both are fine, and neither is assumed.',
            ],
        ],
    ],

    'testimonialsUrl' => 'https://www.linkedin.com/in/hesamrad/details/recommendations/',

    'testimonials' => [
        [
            'quote' => 'I can confidently say that Hesam is one of the most disciplined person I have ever had the opportunity to work with. His leadership skills are excellent and he always ensures that his team is performing at its best. His commitment to delivering high-quality results shows in every project he handles. If you\'re looking for a Back-end development role, I highly recommend Hesam who makes a positive impact on any team he joins.',
            'name' => 'Amir Sorayaei',
            'role' => 'Senior Front-end Developer',
            'relationship' => 'Colleague and Co-founder of a startup',
            'url' => 'https://www.linkedin.com/in/amir-sorayaei',
            'avatar' => null,
        ],

        [
            'quote' => 'I had the privilege of working alongside Hesam from my very first day as an intern, and I can confidently say he played a pivotal role in shaping my growth as a developer. As a backend developer, Hesam combines deep technical expertise with a rare quality—genuine patience in mentoring others. What sets Hesam apart isn\'t just his technical skill; it\'s his willingness to stop what he\'s doing to explain a concept, debug an issue together, or share the why behind a decision—not just the how. Many of the habits and best practices I rely on today were shaped by his guidance. Beyond his technical abilities, Hesam is the kind of teammate every engineering team needs: reliable, collaborative, and genuinely invested in the success of those around him. Any team would be lucky to have him.',
            'name' => 'Shahin Behzad Rad',
            'role' => 'Full-Stack Developer',
            'relationship' => 'Colleague',
            'url' => 'https://www.linkedin.com/in/shahin-behzadrad',
            'avatar' => null,
        ],

        [
            'quote' => 'The most disciplined coworker I’ve ever had. Hesam is extremely focused and giving up is not an option for him. He’s actually the man who gets things done no matter what it takes. His enthusiasm to learn new things is unbelievable. He’s the one you can get inspired by.',
            'name' => 'Sina Nakhaei',
            'role' => 'Android Developer',
            'relationship' => 'Colleague',
            'url' => 'https://www.linkedin.com/in/sina-nakhaei',
            'avatar' => null
        ],

        [
            'quote' => 'After working with Hesam for about 1 year, I can confidently say that you will have a compassionate friend and strong character in your team.',
            'name' => 'Ramin Kheradmand',
            'role' => 'Front-end Developer',
            'relationship' => 'Colleague',
            'url' => 'https://www.linkedin.com/in/ramin-kheradmand-5733b4199',
            'avatar' => null
        ],
    ],

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
            /*
             * template.md is a scaffold for writing a post, not a post. It sat
             * in the collection with nothing but `isPublished: false` keeping
             * it out, so inverting this filter published it — an empty title
             * wrapping an empty link, pointing at a blank page.
             *
             * Excluded by name as well, because what keeps a scaffold out of
             * the site should not be a flag inside the scaffold.
             */
            'filter' => fn($post) => $post->isPublished === true
                && $post->getFilename() !== 'template',
        ],
        /*
         * One file per case study, so each gets a page with room for the whole
         * story rather than a slot in a shared list.
         *
         * The filter is the guard, and it is deliberately here rather than in a
         * template: a sample is not merely hidden when the site goes public, it
         * is never generated. There is no URL to leak, nothing in the sitemap,
         * and no page to reach by guessing the address.
         */
        'caseStudies' => [
            'path' => 'work/{filename}/',
            'sort' => '-year',
            'filter' => fn($study) => ($study->published === true)
                && !(($study->sample ?? false) && $workIsPublic),
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

        return is_string($value) && preg_match('/^-?\d+$/', $value) ? (int)$value : null;
    },

    'getCreatedAtDateObject' => function ($page): DateTime {
        $timestamp = $page->getTimestamp($page->created_at);

        if ($timestamp === null) {
            throw new InvalidArgumentException(
                "'{$page->getPath()}' needs a created_at date written as an unquoted YYYY-MM-DD."
            );
        }

        return Datetime::createFromFormat('U', (string)$timestamp);
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
            : Datetime::createFromFormat('U', (string)$timestamp);
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
        ], fn($timestamp) => $timestamp !== null);

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
        $collapsed = preg_replace('/\s+/u', ' ', (string)$text) ?? (string)$text;
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

        return rtrim($trimmed === '' ? $truncated : $trimmed) . '…';
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
        $text = html_entity_decode(strip_tags((string)$body), ENT_QUOTES, 'UTF-8');

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
        return trim((string)$page->language)
            ?: ($page->isPost($page) ? $page->postLanguage : $page->defaultLanguage);
    },

    'getLocale' => function ($page) {
        return trim((string)$page->locale)
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
        $url = rtrim($page->getBaseUrl(), '/') . '/' . ltrim($page->getPath(), '/');

        return $url . (str_ends_with($url, '/') ? '' : '/');
    },

    /**
     * The single URL a page identifies itself by — canonical, og:url, JSON-LD.
     *
     * getUrlWithTrailingSlash() is derived from the page path and does not know
     * about `permalink`, so a page that sets one (the 404 lives at /404.html)
     * would otherwise advertise a directory URL that the build never emits.
     */
    /*
     * The two money figures, formatted for reading. Structured data wants the
     * bare integer and the ISO code, so it reaches into `pricing` directly
     * rather than through these.
     */
    'priceSetup' => function ($page) {
        return $page->pricing['symbol'] . number_format($page->pricing['setup']);
    },

    'priceMonthly' => function ($page) {
        return $page->pricing['symbol'] . number_format($page->pricing['monthly']);
    },

    'getCanonicalUrl' => function ($page) {
        // With the trailing slash, because that is the URL the host actually
        // serves. Advertising the bare origin as canonical while every request
        // resolves to "/" points the canonical at a URL that redirects, and
        // every other page on the site ends in a slash.
        if ($page->isHomePage()) {
            return rtrim($page->getBaseUrl(), '/') . '/';
        }

        if ($page->permalink) {
            return rtrim($page->getBaseUrl(), '/') . '/' . ltrim($page->permalink, '/');
        }

        return $page->getUrlWithTrailingSlash();
    },

    /**
     * A case study's cover, normalised to [src, alt, caption].
     *
     * Front matter writes this two ways — `cover: 'https://…'` when there is
     * nothing to say about the picture, and a map with `src`/`alt`/`caption`
     * when there is. The templates only ever read the map form, so a study
     * using the string silently rendered no image at all: no error, no
     * placeholder, just a card that quietly ignored its own cover.
     *
     * Both shapes are valid authoring, so the fix belongs here rather than in
     * a rule about which one to type. Returns null when there is no cover.
     */
    'getCover' => function ($page) {
        $cover = $page->cover;

        if (is_string($cover)) {
            return trim($cover) === '' ? null : ['src' => trim($cover), 'alt' => '', 'caption' => ''];
        }

        // An IterableObject on a page, a plain array on a collection item.
        if (is_array($cover) || $cover instanceof Traversable) {
            $cover = collect($cover)->all();
            $src = trim((string)($cover['src'] ?? ''));

            // A map with no src is the deliberate "there will be a picture
            // here one day" marker the placeholder renders for.
            return [
                'src' => $src === '' ? null : $src,
                'alt' => (string)($cover['alt'] ?? ''),
                'caption' => (string)($cover['caption'] ?? ''),
            ];
        }

        return null;
    },

    /**
     * The trail from the home page to this one, as [name, url, current].
     *
     * One source of truth on purpose: the visible breadcrumbs and the
     * BreadcrumbList in the JSON-LD are the same list rendered twice, and
     * Google treats a mismatch between the two as a reason to ignore the
     * markup. Deriving both from here means they cannot drift.
     *
     * Empty on the home page — a one-item trail is not a trail.
     *
     * $labels lets a caller localise the fixed words; a Persian post needs a
     * Persian "Home" in the same way it already needs a Persian "Back to blog".
     */
    'getBreadcrumbs' => function ($page, array $labels = []) {
        if ($page->isHomePage()) {
            return [];
        }

        $segments = array_values(array_filter(explode('/', trim($page->getPath(), '/'))));

        if ($segments === []) {
            return [];
        }

        $base = rtrim($page->getBaseUrl(), '/');

        // Section names rather than slugs: "Open source", not "projects". A URL
        // segment is an address, and addresses make poor labels.
        $names = array_merge([
            'home' => 'Home',
            'blog' => 'Blog',
            'work' => 'Work',
            'projects' => 'Open source',
        ], $labels);

        $humanise = fn($segment) => $names[$segment]
            ?? ucfirst(str_replace('-', ' ', $segment));

        $crumbs = [['name' => $names['home'], 'url' => $base . '/', 'current' => false]];
        $trail = '';

        foreach ($segments as $index => $segment) {
            $trail .= '/' . $segment;
            $isLast = $index === count($segments) - 1;

            $crumbs[] = [
                // The leaf is named by the page itself. `title` is front matter
                // on every page that has a trail, so this is authoritative
                // rather than reconstructed from the address.
                'name' => $isLast ? ($page->title ?: $humanise($segment)) : $humanise($segment),
                'url' => $base . $trail . '/',
                'current' => $isLast,
            ];
        }

        return $crumbs;
    },

    'getMyYearsOfExperience' => function () {
        return date('Y') - 2018;
    },
];
