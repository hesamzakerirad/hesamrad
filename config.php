<?php

/*
 * A PHP array literal cannot reference its own keys. These two values are
 * therefore outside the array below. The case-studies collection filter closes
 * over $workIsPublic, and the FAQ answers read $pricing.
 */
$workIsPublic = true;

/*
 * The Zero to One prices.
 *
 * The amounts are integers. `number_format` adds the comma in "$1,500",
 * therefore the comma cannot disagree with the figure. `currency` is the ISO
 * code for the structured data. `symbol` is the character a reader sees.
 *
 * `turnaround` is display text and not a number, because it states a range.
 */
$pricing = [
    'setup' => 1500,
    'monthly' => 50,
    'turnaround' => '~2 weeks',
    'symbol' => '$',
    'currency' => 'USD',
];

$money = fn($amount) => $pricing['symbol'] . number_format($amount);

$bookingUrl = 'https://cal.com/hesamrad/30min';

return [
    'baseUrl' => 'http://localhost:8000',
    'production' => false,
    'siteName' => 'Hesam Rad',
    // The JSON-LD `jobTitle` uses this value. It must stay a job title. For a
    // sentence, use `siteTagline`.
    'siteDescription' => 'Independent software engineer',
    'siteTagline' => 'Notes from the work: whatever I am building, using, or working out at the time.',
    'siteAuthor' => 'Hesam Rad',
    // The one source for the address. The footer, the contact form fallback,
    // the thank-you page and the structured data all read it.
    'email' => 'hesamrad.dev@gmail.com',
    // The one source for the booking link. Every "Book a call" button reads it.
    // The page it opens belongs to cal.com, therefore each of those links is
    // external and opens in a new tab.
    'bookingUrl' => $bookingUrl,
    // The default locale and language. A page can override them with `locale`
    // or `language` front matter. A post without front matter gets the
    // post-specific pair below. The RTL support stays available: a post can
    // select it with `language: fa`.
    'defaultLocale' => 'en_US',
    'defaultLanguage' => 'en',
    'postLocale' => 'en_US',
    'postLanguage' => 'en',
    'rtlLanguages' => ['fa', 'ar', 'he', 'iw', 'ur'],

    // This value controls the nav entry, the robots directive on the listing
    // page, and the generation of sample case studies. Set it at the top of
    // this file.
    'workIsPublic' => $workIsPublic,

    // The site is static and has no server. The contact form therefore posts to
    // a Cloudflare Worker (refer to worker/). These two values are public. The
    // Turnstile secret and all API keys are Worker secrets. Do not put them in
    // this repository.
    'formEndpoint' => 'https://hello.hesamrad.com',
    'turnstileSiteKey' => '0x4AAAAAAEMSHcNbdcoTgz3f',

    'pricing' => $pricing,

    /*
     * The contact block that closes a page.
     *
     * main.blade.php puts this block last on every page, below the questions.
     * These two values are the wording it uses. A page writes its own pair in
     * its front matter when the default does not fit:
     *
     *     contactHeading: 'Still not answered?'
     *     contactIntro: 'Ask it directly...'
     *
     * A page that must not ask for contact sets `disableContact: true` instead.
     * A blog post never gets the block, because a post closes with the link to
     * the next post.
     *
     * Keep the heading an instruction and not a label. "Get in touch" states
     * the obvious. "Tell me what you're trying to build." states what to write.
     */
    'contact' => [
        'heading' => 'Tell me what you\'re trying to build.',
        'intro' => 'A paragraph is enough. I\'ll tell you whether I\'m the right person for it, including the times I\'m not.',
    ],

    /*
     * All questions, in one place.
     *
     * The key is `siteFaq` and not `faq`. A post declares its own questions
     * with a `faq:` block in its front matter, and front matter wins over this
     * file. A post therefore hid this whole array from itself, and no question
     * here could ever reach a post. The two structures also differ: `a` is an
     * array of paragraphs here and one string in a post. Do not rename this key
     * back.
     *
     * `page` decides where a question appears, and one question appears on one
     * page:
     *
     * - No `page` key: the question shows on /faq/ and nowhere else. Most
     *   questions belong here.
     * - `page => '/zero-to-one/'`: the question shows at the end of that page
     *   and not on /faq/. Use it for a question that makes sense only next to
     *   that page, and write the path as it appears in the address bar.
     *
     * Nothing else is needed to put a question on a page. main.blade.php asks
     * every page for its questions, and a page with none gets no heading and no
     * empty block. Do not add an include to a template.
     *
     * A page shows its questions in the order of this array, therefore move an
     * entry to move it up or down the page.
     *
     * `group` sets the heading above a run of questions on /faq/. A question
     * with a `page` key ignores it, because only /faq/ shows groups.
     *
     * `open => true` opens the question on load.
     *
     * `link` puts one link under the answer. `href` accepts a path on this site
     * or a full URL, and a full URL opens in a new tab:
     *
     *     'link' => ['href' => '/services/', 'label' => 'How the work runs'],
     *     'link' => ['href' => 'https://laravel.com', 'label' => 'Laravel'],
     *
     * Write a path with the leading slash and without the base URL. The
     * component adds the base URL to a path and leaves a full URL alone.
     *
     * Each answer is an array of paragraphs. For an amount of money, read the
     * `pricing` values. Do not write a figure again.
     */
    'siteFaq' => [
        // ── Before we start ──────────────────────────────────────────────
        [
            'group' => 'Before we start',
            'q' => 'What happens on the first call?',
            'a' => [
                'Thirty minutes, and you do the talking for most of it: what the business does, what isn\'t working, and what you want instead.',
                'Afterwards you get a written plan: what I\'d build, in what order, what it would cost, and what I think could go wrong. It\'s free and it\'s yours to keep, including to take to another developer. If I\'m not the right person for the job, that\'s the call where I say so.',
            ],
            'link' => ['href' => '/services/', 'label' => 'How the work runs'],
        ],
        [
            'group' => 'Before we start',
            'q' => 'How quickly will you reply?',
            'a' => [
                'Within a day, usually sooner. I read every inquiry myself, because there\'s nobody else here to read them.',
            ],
        ],
        [
            'group' => 'Before we start',
            'q' => 'What do you need from me to get started?',
            'a' => [
                'Half an hour on a call, and straight answers in it. You don\'t need a specification, wireframes, or a list of features written before we talk.',
                'For larger work you\'ll also need to be reachable for a short call each week. Nothing else is needed up front.',
            ],
        ],

        // ── What it costs ────────────────────────────────────────────────
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
            'q' => 'Can I pay you monthly for ongoing work?',
            'a' => [
                'On a larger project a monthly arrangement is optional. It covers whatever we agree it covers, written down before it starts.',
            ],
        ],

        // ── What I build ─────────────────────────────────────────────────
        [
            'group' => 'What I build',
            'q' => 'What do you actually build?',
            'a' => [
                'Web applications that work as well on a phone as on a laptop. I build both halves: what your customers see, and the system running behind it. There\'s no seam between them and nobody to coordinate with.',
                'Where a client already has a designer or a front-end team, I take the half they can\'t do. That\'s usually the cheaper arrangement for them.',
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

        // ── What you own ─────────────────────────────────────────────────
        [
            'group' => 'What you own',
            'q' => 'Do I own what you build?',
            'a' => [
                'All of it, from the first day. The code lives in your repository, it runs on your hosting account, and the domain stays registered to you. I work inside your accounts, so at the end there\'s nothing to pry loose and nothing of yours sitting in my name.',
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
                'Remotely. I\'ve worked this way for most of my career and I\'m good at it. My clients are across Europe and North America, and I arrange my day around whichever of those you\'re in, so there are hours every day when you can reach me directly.',
                'Most people settle into a short call once a week plus email in between. If you\'d rather have more or less than that, say so.',
            ],
        ],
        [
            'group' => 'Working together',
            'q' => 'Should I hire you or an agency?',
            'a' => [
                'Sometimes an agency. If the job needs several disciplines at once, has a deadline you can\'t move, or is large enough that coordinating it is a job in itself, buy the coordination.',
                'If it\'s one system that has to be right and stay right, the distance between you and the person building it is the thing worth protecting. I\'ve written the comparison out in full, including the parts that don\'t favor me.',
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

        // ── On /services/ only ───────────────────────────────────────────
        // These leave /faq/ and close the services page, in this order: time,
        // then scope, then the risk of one person. The `group` key is unused
        // while `page` is set.
        //
        // Money is not here. Every figure the site publishes is a Zero to One
        // figure, and it belongs on that page. Do not answer cost here with a
        // number.
        [
            'q' => 'What services do I offer?',
            'page' => '/services/',
            'a' => [
                'Bespoke web application development. I build applications that live on the internet; no mobile or desktop application.',
            ],
        ],
        [
            'q' => 'How can I see some of your previous works?',
            'page' => '/services/',
            'a' => [
                'It is natural to feel skeptical but it\'s only fair you see some of the things that I\'ve done.',
            ],
            'link' => ['href' => '/work/', 'label' => 'Read a few case studies'],
        ],
        [
            'q' => 'How do I contact you?',
            'page' => '/services/',
            'a' => [
                'There is a form right below this section you can use to write me; or you can just book a quick 30 minutes call so we can talk face to face and hear about your idea.',
            ],
            'link' => ['href' => $bookingUrl, 'label' => 'Book a call'],
        ],

        // ── On /zero-to-one/ only ────────────────────────────────────────
        // The campaign, and the only place on the site that states a price.
        // Keep it that way: an answer here can name a figure, and an answer
        // anywhere else cannot. When the campaign ends, this block and the
        // page go together and no answer elsewhere needs a rewrite.
        [
            'q' => 'What does it cost?',
            'page' => '/zero-to-one/',
            'open' => true,
            'a' => [
                $money($pricing['setup']) . ' once to build it and put it live, then ' . $money($pricing['monthly']) . ' a month to keep it running. That is the whole of it, and it does not move once we have agreed the work.',
                'Anything outside the list on this page is a different job with its own fixed price, and that number comes once the plan is written.',
            ],
        ],
        [
            'q' => 'What does the monthly fee cover?',
            'page' => '/zero-to-one/',
            'a' => [
                $money($pricing['monthly']) . ' a month covers hosting, the domain renewal, security updates, backups, and small changes when you need them: new opening hours, a price change, a few new photos. Email me and it gets done.',
            ],
        ],
        [
            'q' => 'Can I stop paying the monthly fee?',
            'page' => '/zero-to-one/',
            'a' => [
                'Then stop. There\'s no minimum term. The domain is registered to you, and I\'ll hand over everything so you or anyone else can pick it up. Nothing in the paperwork keeps you here.',
            ],
        ],
        [
            'q' => 'How much work is this for me?',
            'page' => '/zero-to-one/',
            'a' => [
                'Half an hour on a call, and that\'s the whole of your homework. I write the words from what you tell me, because sitting down to write a page about your own business is the step that stalls most websites for months.',
            ],
        ],
        [
            'q' => 'Why is this about two weeks when other work takes months?',
            'page' => '/zero-to-one/',
            'a' => [
                'Because it\'s the same defined list every time, with one round of changes: a website, the words, the domain and hosting, and your Google listing. The narrow scope is what makes two weeks possible. Nothing is being rushed to fit it.',
                'The moment a business needs online ordering or a booking system, it\'s a different job with a different number. I won\'t squeeze it in.',
            ],
        ],
        [
            'q' => 'What is not included?',
            'page' => '/zero-to-one/',
            'a' => [
                'A visual identity invented from scratch. The site is built for your business, but the look isn\'t designed from nothing. That\'s a separate job at a separate price. Logos, branding and photography aren\'t part of it either.',
                'Payments, online ordering, booking systems and customer logins are all real work, so they\'re quoted separately.',
            ],
        ],
    ],

    // The link to the full set of recommendations. Set it to null to remove the
    // link. Do not change the component.
    'testimonialsUrl' => 'https://www.linkedin.com/in/hesamrad/details/recommendations/',

    /*
     * Recommendations, quoted from LinkedIn.
     *
     * Use real recommendations only. A visitor can open the profile of the
     * author and read the recommendation there. An invented quote removes that
     * capability.
     *
     * `url` must point to the recommendation or to the profile of the author.
     *
     * `avatar` is optional and accepts any image URL. Do not use a LinkedIn
     * URL: media.licdn.com signs its URLs, the URLs expire after some weeks,
     * and the server refuses cross-origin requests. Put the file in
     * source/_assets/images/ and use that path. If the image does not load, the
     * component shows the initials.
     */
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
             * template.md is a scaffold for a new post and not a post. The
             * filter excludes it by name.
             *
             * Do not rely on `isPublished: false` in the scaffold. A change to
             * this filter then publishes an empty title and an empty link that
             * point to a blank page.
             */
            'filter' => fn($post) => $post->isPublished === true
                && $post->getFilename() !== 'template',
        ],
        /*
         * The filter must stay here and not in a template. It prevents the
         * generation of a sample when the site is public. There is then no URL,
         * no sitemap entry, and no page to find by a guess at the address.
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
     * Returns a front-matter date as a Unix timestamp, or null.
     *
     * Symfony YAML converts an unquoted `2025-01-01` to an integer. A different
     * type is therefore an authoring error.
     *
     * Do not use `is_numeric`. It accepts floats and padding.
     * `createFromFormat('U')` then rejects them, and the `: DateTime` return
     * type of the callers causes a fatal error.
     *
     * Do not use `ctype_digit`. A date before 1970 is a negative integer, and a
     * Jalali year such as `1403-05-16` is also negative.
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
     * Returns the update date. `updated_at` is optional: a post without one
     * gets its creation date. This function uses the rule of getLastModified
     * and takes the later of the two dates. `dateModified` can therefore not be
     * before `datePublished`, which is a structured data error.
     */
    'getUpdatedAtObject' => function ($page): DateTime {
        $timestamp = $page->getLastModified();

        return $timestamp === null
            ? $page->getCreatedAtDateObject()
            : Datetime::createFromFormat('U', (string)$timestamp);
    },

    /**
     * Returns the timestamp of the last change to a page, or null for a page
     * with no dates. For null, the sitemap uses the git history.
     *
     * This function takes the later of the two dates. An `updated_at` before
     * `created_at` can therefore not make a `lastmod` before the `pubDate` of
     * the post.
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
     * Collapses the whitespace in a string and cuts the string to $length at a
     * word boundary. It makes no other change to the text.
     */
    'toSummaryText' => function ($page, $text, $length = null) {
        // preg_replace returns null for invalid UTF-8. Keep the original text:
        // an empty summary is worse than an uncollapsed one.
        $collapsed = preg_replace('/\s+/u', ' ', (string)$text) ?? (string)$text;
        $cleaned = trim($collapsed);

        if ($length === null || mb_strlen($cleaned) <= $length) {
            return $cleaned;
        }

        // Use the multibyte function. A cut on a byte boundary divides a
        // Persian character, and json_encode() then fails on the invalid UTF-8.
        $truncated = mb_substr($cleaned, 0, $length);

        // Use `??` and not `?:`. A result of "0" is a valid summary, and `?:`
        // treats it as a failure and returns the untrimmed cut.
        $trimmed = preg_replace('/\s+\S*$/u', '', $truncated) ?? $truncated;

        return rtrim($trimmed === '' ? $truncated : $trimmed) . '…';
    },

    /**
     * Returns a plain-text summary of the first content on a page.
     *
     * getContent() returns HTML. This function removes the tags and decodes the
     * entities. Each consumer needs plain text: the meta description, the Open
     * Graph and Twitter cards, the JSON-LD and the feed.
     */
    'getExcerpt' => function ($page, $length = 255) {
        if ($page->excerpt) {
            return $page->toSummaryText($page->excerpt, $length);
        }

        // A <!-- more --> marker sets the cut point in the body. The $length of
        // the caller still applies, because these summaries go into meta tags
        // of a fixed size.
        $content = preg_split('/<!-- more -->/m', $page->getContent(), 2);
        $body = preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $content[0]);
        $text = html_entity_decode(strip_tags((string)$body), ENT_QUOTES, 'UTF-8');

        return $page->toSummaryText($text, $length);
    },

    /**
     * Returns the description for the meta tags, the cards, the JSON-LD and the
     * feed.
     *
     * A description in front matter is plain text that an author wrote. This
     * function cuts it to $length but removes no markup. The author typed the
     * markup, therefore the function keeps it.
     */
    'getSummary' => function ($page, $length = 255) {
        // Compare with '' and do not test for a true value. A description of
        // "0" is text that the author wrote, and the function must keep it.
        $description = $page->toSummaryText($page->description, $length);

        return $description !== '' ? $description : $page->getExcerpt($length);
    },

    'getRobotsStatus' => function ($page) {
        // A list in front matter is a plain array on a collection item, but an
        // IterableObject on a regular page. A string conversion of an
        // IterableObject writes a JSON array as the directive. The post
        // template ships a list of empty entries, and the filter removes them
        // all. Apply the default after the filter and not before it.
        $robots = $page->robots;

        if (is_array($robots) || $robots instanceof Traversable) {
            $directives = collect($robots)->filter()->implode(',');
        } elseif (is_string($robots)) {
            $directives = trim($robots);
        } else {
            // A YAML boolean or number is not a directive. `robots: true`
            // converts to the invalid content="1".
            $directives = '';
        }

        return $directives !== '' ? $directives : 'index,follow';
    },

    // Front matter has priority. `??` alone is not sufficient: an empty
    // `language:` key parses to an empty string and not to null, and the page
    // then gets lang="".
    'getLanguage' => function ($page) {
        return trim((string)$page->language)
            ?: ($page->isPost($page) ? $page->postLanguage : $page->defaultLanguage);
    },

    'getLocale' => function ($page) {
        return trim((string)$page->locale)
            ?: ($page->isPost($page) ? $page->postLocale : $page->defaultLocale);
    },

    /**
     * Returns the primary subtag of the page language. 'fa-IR' therefore gives
     * the same result as 'fa'.
     *
     * All code that uses the language must call this function. The text
     * direction and the post labels must agree on the language.
     */
    'getBaseLanguage' => function ($page) {
        return strtolower(strtok($page->getLanguage(), '-_'));
    },

    'getDirection' => function ($page) {
        // rtlLanguages is an IterableObject (a Collection) and not an array.
        return collect($page->rtlLanguages)->contains($page->getBaseLanguage()) ? 'rtl' : 'ltr';
    },

    'getAuthor' => function ($page) {
        return $page->author ?? $page->siteName;
    },

    'isPost' => function ($page) {
        // 'blog' is the listing page. Only 'blog/{slug}' is a post.
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

    // Adds a trailing slash to the page URL.
    'getUrlWithTrailingSlash' => function ($page) {
        $url = rtrim($page->getBaseUrl(), '/') . '/' . ltrim($page->getPath(), '/');

        return $url . (str_ends_with($url, '/') ? '' : '/');
    },

    /*
     * The two amounts of money, formatted for a reader. The structured data
     * needs the integer and the ISO code, therefore it reads `pricing`
     * directly and does not use these two functions.
     */
    'priceSetup' => function ($page) {
        return $page->pricing['symbol'] . number_format($page->pricing['setup']);
    },

    'priceMonthly' => function ($page) {
        return $page->pricing['symbol'] . number_format($page->pricing['monthly']);
    },

    /**
     * Returns the one URL that identifies a page. The canonical link, og:url
     * and the JSON-LD all use it.
     *
     * getUrlWithTrailingSlash() uses the page path and does not read
     * `permalink`. A page with a `permalink` (the 404 page is at /404.html)
     * would therefore show a directory URL that the build does not write.
     */
    'getCanonicalUrl' => function ($page) {
        // Add the trailing slash, because the host serves that URL. The bare
        // origin redirects to "/", and a canonical URL must not redirect. All
        // other pages on the site end with a slash.
        if ($page->isHomePage()) {
            return rtrim($page->getBaseUrl(), '/') . '/';
        }

        if ($page->permalink) {
            return rtrim($page->getBaseUrl(), '/') . '/' . ltrim($page->permalink, '/');
        }

        return $page->getUrlWithTrailingSlash();
    },

    /**
     * Returns the cover of a case study as [src, alt, caption, credit], or
     * null.
     *
     * Front matter can write a cover in two forms. `cover: 'https://…'` gives
     * only the address. A map gives `src`, `alt`, `caption` and `credit`. The
     * templates read the map form only. This function converts the string form
     * to a map. Without it, a study with the string form shows no image and no
     * error.
     *
     * `credit` is the address of the source of the image. The layout shows a
     * credit on the image when this key has a value.
     */
    'getCover' => function ($page) {
        $cover = $page->cover;

        if (is_string($cover)) {
            return trim($cover) === ''
                ? null
                : ['src' => trim($cover), 'alt' => '', 'caption' => '', 'credit' => ''];
        }

        // An IterableObject on a page, a plain array on a collection item.
        if (is_array($cover) || $cover instanceof Traversable) {
            $cover = collect($cover)->all();
            $src = trim((string)($cover['src'] ?? ''));

            // A map without a `src` is the marker for a picture that does not
            // exist yet. The template then shows the placeholder.
            return [
                'src' => $src === '' ? null : $src,
                'alt' => (string)($cover['alt'] ?? ''),
                'caption' => (string)($cover['caption'] ?? ''),
                'credit' => trim((string)($cover['credit'] ?? '')),
            ];
        }

        return null;
    },

    /**
     * Returns the trail from the home page to this page, as [name, url,
     * current]. The trail is empty on the home page.
     *
     * The visible breadcrumbs and the BreadcrumbList in the JSON-LD must both
     * come from this function. Google ignores the markup when the two lists
     * disagree.
     *
     * $labels translates the fixed words. A Persian post needs a Persian
     * "Home".
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

        // Use the section name and not the slug. "Open source" is the name of
        // the /projects/ section. A URL segment is an address and not a label.
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
