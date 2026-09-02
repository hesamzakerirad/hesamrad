<?php

/*
 * A PHP array literal cannot reference its own keys. These three values are
 * therefore outside the array below. The case-studies collection filter closes
 * over $workIsPublic, the campaign questions close over $campaignIsLive, and
 * the FAQ answers read $pricing.
 */
$workIsPublic = true;

/*
 * The Zero to One campaign.
 *
 * Set this to false to remove the campaign from the site. The page at
 * /zero-to-one/ then shows a short holding page with noindex, the link leaves
 * the navigation and the footer, the campaign questions stop, the home page
 * hero shows one button, and a post gets no second button.
 *
 * Set this to true to put the campaign back.
 *
 * This switch no longer decides whether the site states a price. /pricing/
 * reads the amounts below and is always live. The campaign is one way to sell
 * a website; the price of a website is a fact about the work either way.
 */
$campaignIsLive = false;

/*
 * The website prices.
 *
 * These are the figures for a website: one amount to build it, one a month to
 * keep it running. /pricing/ states them, /zero-to-one/ states them again when
 * the campaign is live, and the FAQ answers about cost link to /pricing/
 * instead of repeating them. One number, one place.
 *
 * A web application has no figure here and must not get one. It is quoted
 * after the plan is written, and /pricing/ says so in those words.
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
    // post-specific pair below.
    'defaultLocale' => 'en_US',
    'defaultLanguage' => 'en',
    'postLocale' => 'en_US',
    'postLanguage' => 'en',

    // This value controls the nav entry, the robots directive on the listing
    // page, and the generation of sample case studies. Set it at the top of
    // this file.
    'workIsPublic' => $workIsPublic,

    // This value controls the campaign page, the nav entry, the footer entry,
    // the campaign questions, the home page hero and the button at the end of
    // a post. Set it at the top of this file.
    'campaignIsLive' => $campaignIsLive,

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
     * A group heading gets an identifier from its own text, therefore a post
     * can link to a run of questions: `/faq/#faq-what-it-costs`. A question
     * gets no identifier of its own.
     *
     * New wording for a group gives a new identifier and breaks a link that a
     * post already holds. Read the posts before a change to a group name.
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
        [
            'group' => 'General',
            'q' => 'Who are you?',
            'a' => [
                'I\'m an independent software engineer. I build web applications that work as well on a phone as on a laptop, and I take care of both halves: what your customers see, and the system running behind it.',
            ],
        ],
        [
            'group' => 'General',
            'q' => 'Where are you located?',
            'a' => [
                'I\'m Persian, and I\'m based in Turkey. The clocks here don\'t change through the year, so I\'m on the same time all year round: a couple of hours ahead of London, and seven or eight ahead of New York.',
                'The work is remote and I take clients wherever they are. Where I sit matters less than which hours you can reach me in, and that one has its own answer below.',
            ],
        ],
        // ── Before we start ──────────────────────────────────────────────
        [
            'group' => 'Before we start',
            'q' => 'How quickly will you reply?',
            'a' => [
                'Within a day, usually sooner. I read every inquiry myself, because there\'s nobody else here to read them.',
            ],
        ],
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
            'q' => 'I\'m not ready for a call. Can I just write to you?',
            'a' => [
                'Please do. There\'s a form at the end of every page on this site, and a paragraph about what you\'re trying to build is plenty to start with. Nobody should have to sit through a call to find out whether I\'m the right person.',
                'I read every message myself, and I\'ll tell you straight when it isn\'t a job for me.',
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
        [
            'group' => 'Before we start',
            'q' => 'How long will my project take?',
            'a' => [
                'It depends on the size of what you\'re building. Every job here is built for one business, so anyone who gives you a number before hearing the problem is guessing at it.',
                'What I can put a date on is the plan. You\'ll have it in writing within a week of the first call: what gets built, in what order, and how long each part takes. That\'s the point where the timeline stops being a guess.',
            ],
        ],
        [
            'group' => 'Before we start',
            'q' => 'Is there a contract, and who am I paying?',
            'a' => [
                'There\'s always a contract, signed by both of us before any work starts. It sets out what\'s being built, what it costs, when it\'s paid, and what happens if either of us wants out.',
                'You\'re hiring a person and not a company. I\'m a freelancer, I work for myself, and the contract is between you and me directly with my name on it. That\'s worth knowing up front, and anyone willing to start without putting it in writing is doing you no favors.',
            ],
        ],
        [
            'group' => 'Before we start',
            'q' => 'Can I see what other clients say about working with you?',
            'a' => [
                'Yes, and read both halves of it. The case studies show you the problem and what got built. The reviews tell you what the months in between were like to sit through.',
            ],
            'link' => ['href' => '/reviews/', 'label' => 'What past clients wrote'],
        ],

        // ── What it costs ────────────────────────────────────────────────
        [
            'group' => 'What it costs',
            'q' => 'Do you charge by the hour?',
            'a' => [
                'No. A website has a fixed price and it\'s published, so you can read it without talking to me first. A web application is quoted as one number after the plan is written, because until somebody has worked out what it involves, any figure is a guess dressed up as a quote.',
                'Either way you know the cost before you commit, instead of watching a meter run. It also means I carry the risk when something takes longer than I thought. That\'s the right way round: I\'m the one who estimated it.',
            ],
            'link' => ['href' => '/pricing/', 'label' => 'What it costs'],
        ],
        [
            'group' => 'What it costs',
            'q' => 'How do I know I can afford you?',
            'a' => [
                'The website price is on the site, so you can settle that question on your own in about ten seconds. For a web application the number comes with the written plan, and both the first call and that plan cost you nothing. You can read the figure and walk away.',
                'If it\'s more than you want to spend, say so instead of going quiet. There\'s usually a smaller first version that solves the expensive half of the problem, and I\'d rather build that than lose the work over the shape of a quote.',
            ],
            'link' => ['href' => '/pricing/', 'label' => 'See the numbers'],
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
            'q' => 'I just need a simple website, not an application. Is that you?',
            'a' => [
                'Yes. A few pages that say who you are, what you sell and how to reach you is a real piece of work, and plenty of businesses need nothing more than that for a long time.',
                'It\'s a smaller job than an application, so it\'s a smaller number and a shorter wait. Tell me what the business does and I\'ll say what it actually needs, including the times that\'s less than you came in expecting.',
            ],
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
            'q' => 'Do you also do the marketing, the SEO, and the branding?',
            'a' => [
                'No. I build the software and that\'s the whole of it. No logo, no brand guidelines, no ad campaigns, no SEO. Those are full-time trades, and you\'re better off with someone who does them all day than with me doing them badly on the side.',
                'If you already have people on that work, I\'ll build what they need me to build and stay out of their way.',
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
                'Remotely, and with clients anywhere. I\'ve worked this way for most of my career and I\'m good at it.',
                'I\'m in Turkey, and a working day here reaches most of the world at one end or the other. My mornings land in the afternoon across Asia and Australia. My afternoons and evenings land in the working day across Europe and the Americas. Tell me where you are and I\'ll tell you exactly which hours you get, before you hire me rather than after.',
                'Most people settle into a short call once a week plus email in between. If you\'d rather have more or less than that, say so.',
            ],
        ],
        [
            'group' => 'Working together',
            'q' => 'What happens if you get ill, or you\'re not around?',
            'a' => [
                'Fair question, and the answer worth anything isn\'t a promise from me. It\'s the setup.',
                'I work inside your accounts from the first day, so the code, the hosting and the domain are already yours while the work is still going on. The setup runs from written instructions and the tests say what\'s broken. If I vanished tomorrow you\'d hand the repository to another developer and they\'d carry on. That\'s true in week one, not only at the end.',
                'I don\'t plan on going anywhere. I\'d just rather you didn\'t have to take my word for it.',
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
        // Money is not here. /pricing/ owns every figure the site publishes,
        // and an answer that needs one links there instead of repeating it. Do
        // not answer cost here with a number.
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

        // ── On /pricing/ only ────────────────────────────────────────────
        // These leave /faq/ and close the pricing page, in this order: what
        // the fixed price buys, then how an application is paid for, then the
        // two questions a published price invites.
        //
        // This is the one block outside the campaign that may name a figure,
        // because /pricing/ is the page that owns them. Read the amounts from
        // `pricing` and do not write a number again.
        [
            'q' => 'Is the ' . $money($pricing['setup']) . ' website built from a template?',
            'page' => '/pricing/',
            'a' => [
                'No. It\'s built for your business, in your colors and around what you actually sell. What makes the price fixed is the scope rather than the sameness: it\'s the same defined list of work every time, so I know what it takes before I quote it.',
                'What you don\'t get for that number is a visual identity invented from nothing. If you have a logo I\'ll use it. If you don\'t, the site works fine without one.',
            ],
        ],
        [
            'q' => 'Can I pay for a web application in stages?',
            'page' => '/pricing/',
            'a' => [
                'Yes, and most people do. The work gets split into pieces that each finish something you can look at, and each piece is invoiced as it lands. You\'re never paying months ahead of what exists.',
                'The split is written into the plan before either of us signs anything, so you can see what you\'d be committing to and where you could stop.',
            ],
        ],
        [
            'q' => 'What happens if the work costs more than you quoted?',
            'page' => '/pricing/',
            'a' => [
                'Then it costs me, not you. The number is fixed once we\'ve agreed the work, and if I estimated it badly that\'s mine to carry. I\'m the one who estimated it.',
                'The number moves only when you ask for something that wasn\'t in the plan. That gets quoted on its own before anyone builds it, so a change is a decision you make rather than a surprise on an invoice.',
            ],
        ],
        [
            'q' => 'Do you take on a site or an application somebody else built?',
            'page' => '/pricing/',
            'a' => [
                'Often. Inheriting a codebase is normal work, and a lot of the time it\'s the cheaper answer than starting again. What it costs depends on the state of what\'s there, which is what the first look is for.',
                'I\'ll tell you honestly when a rebuild is genuinely the better buy, and I\'ll tell you when it isn\'t and somebody is about to sell you one.',
            ],
            'link' => ['href' => '/services/', 'label' => 'What I take on'],
        ],

        // ── On /zero-to-one/ only ────────────────────────────────────────
        // The campaign, and the only place on the site that states a price.
        // Keep it that way: an answer here can name a figure, and an answer
        // anywhere else cannot. $campaignIsLive removes this block with the
        // page, therefore no answer elsewhere needs a rewrite.
        //
        // The spread is what makes the switch possible. A `page` key alone
        // puts a question on the page, and faq-section.blade.php would show
        // these six on the holding page, prices and all.
        ...($campaignIsLive ? [
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
        ] : []),
    ],

    // The link to the full set of reviews on LinkedIn. Set it to null to remove
    // the link. Do not change the component.
    'reviewsUrl' => 'https://www.linkedin.com/in/hesamrad/details/recommendations/',

    /*
     * Reviews written by people I worked with, quoted from LinkedIn.
     *
     * Client reviews do not belong here. Each one lives in the `review` key of
     * its own case study in source/_caseStudies/, because a client review and
     * the work it describes belong together and must never drift apart. The
     * reviews component reads both sources and merges them.
     *
     * Use real reviews only. A visitor can open the profile of the author and
     * read the review there. An invented quote removes that capability.
     *
     * `relationship` is what tells a reader who wrote the review, therefore
     * every entry needs one. Write 'Colleague' here. A client review says
     * 'Client' in the front matter of its case study.
     *
     * `url` must point to the review or to the profile of the author.
     *
     * `avatar` is optional and accepts any image URL. Do not use a LinkedIn
     * URL: media.licdn.com signs its URLs, the URLs expire after some weeks,
     * and the server refuses cross-origin requests. Put the file in
     * source/_assets/images/ and use that path. If the image does not load, the
     * component shows the initials.
     */
    'reviews' => [
        [
            'quote' => 'I had the privilege of working alongside Hesam from my very first day as an intern, and I can confidently say he played a pivotal role in shaping my growth as a developer. As a backend developer, Hesam combines deep technical expertise with a rare quality—genuine patience in mentoring others. What sets Hesam apart isn\'t just his technical skill; it\'s his willingness to stop what he\'s doing to explain a concept, debug an issue together, or share the why behind a decision—not just the how. Many of the habits and best practices I rely on today were shaped by his guidance. Beyond his technical abilities, Hesam is the kind of teammate every engineering team needs: reliable, collaborative, and genuinely invested in the success of those around him. Any team would be lucky to have him.',
            'name' => 'Shahin Behzad Rad',
            'role' => 'Full-Stack Developer',
            'relationship' => 'Colleague',
            'url' => 'https://www.linkedin.com/in/shahin-behzadrad',
            'avatar' => null,
            'date' => '2026',
        ],

        [
            'quote' => 'I can confidently say that Hesam is one of the most disciplined person I have ever had the opportunity to work with. His leadership skills are excellent and he always ensures that his team is performing at its best. His commitment to delivering high-quality results shows in every project he handles. If you\'re looking for a Back-end development role, I highly recommend Hesam who makes a positive impact on any team he joins.',
            'name' => 'Amir Sorayaei',
            'role' => 'Senior Front-end Developer',
            'relationship' => 'Colleague and Co-founder of a startup',
            'url' => 'https://www.linkedin.com/in/amir-sorayaei',
            'avatar' => null,
            'date' => '2025',
        ],

        [
            'quote' => 'After working with Hesam for about 1 year, I can confidently say that you will have a compassionate friend and strong character in your team.',
            'name' => 'Ramin Kheradmand',
            'role' => 'Front-end Developer',
            'relationship' => 'Colleague',
            'url' => 'https://www.linkedin.com/in/ramin-kheradmand-5733b4199',
            'avatar' => null,
            'date' => '2023',
        ],

        [
            'quote' => 'The most disciplined coworker I’ve ever had. Hesam is extremely focused and giving up is not an option for him. He’s actually the man who gets things done no matter what it takes. His enthusiasm to learn new things is unbelievable. He’s the one you can get inspired by.',
            'name' => 'Sina Nakhaei',
            'role' => 'Android Developer',
            'relationship' => 'Colleague',
            'url' => 'https://www.linkedin.com/in/sina-nakhaei',
            'avatar' => null,
            'date' => '2023',
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

        /*
         * URLs that used to have a page and now point somewhere else.
         *
         * The site is static and GitHub Pages cannot send a 301, so each item
         * builds a stub page that redirects with a meta refresh. See
         * _layouts/redirect.blade.php.
         *
         * `redirectFrom` is the old path without the leading or trailing slash,
         * and it decides where the file is written. The filename does not, which
         * is what lets a nested URL such as blog/some-post live in a flat
         * directory. `redirectTo` is the destination, written as a site-relative
         * path with both slashes.
         *
         * Delete an item once the old URL stops getting traffic. A redirect that
         * nothing follows is a page a crawler still has to fetch.
         */
        'redirects' => [
            'path' => fn($page) => trim($page->redirectFrom, '/') . '/',
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
     * Do not use `ctype_digit`. A date before 1970 is a negative integer, and
     * `ctype_digit` rejects the leading minus sign.
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
        // multibyte character such as an em dash or a curly quote, and
        // json_encode() then fails on the invalid UTF-8.
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

    /**
     * Returns $html with every off-site link set to open in a new tab.
     *
     * The markdown parser writes a plain <a>, therefore the attributes go on
     * here. `rel` keeps the new page away from this one.
     *
     * A link to the site itself stays in the same tab. The host of `baseUrl`
     * is localhost during a local build, therefore the production host counts
     * as the same site too.
     */
    'withExternalLinksInNewTab' => function ($page, $html) {
        $siteHosts = collect([parse_url($page->baseUrl, PHP_URL_HOST), 'hesamrad.com'])
            ->map(fn($host) => preg_replace('/^www\./', '', (string)$host))
            ->filter()
            ->all();

        return preg_replace_callback('/<a\b([^>]*?)href="(https?:\/\/[^"]+)"([^>]*)>/i', function ($match) use ($siteHosts) {
            $host = preg_replace('/^www\./', '', (string)parse_url($match[2], PHP_URL_HOST));
            $attributes = $match[1] . $match[3];

            // The author can write the attributes in the markdown itself.
            if (in_array($host, $siteHosts, true) || stripos($attributes, 'target=') !== false) {
                return $match[0];
            }

            $added = ' target="_blank"';

            if (stripos($attributes, 'rel=') === false) {
                $added .= ' rel="noopener noreferrer"';
            }

            return '<a' . $match[1] . 'href="' . $match[2] . '"' . $match[3] . $added . '>';
        }, $html);
    },

    /**
     * Returns the FAQPage node of the current page as encoded JSON, or ''.
     *
     * The node is built here because more than one page needs it, not because
     * a template cannot build it. Blade compiles the word after an at sign as
     * a directive in the body of a template, therefore '@context' written
     * there becomes compiled PHP and the node loses the key that makes it
     * valid. Every FAQPage on this site carried that fault, and no page was
     * eligible for the rich result. Inside a @php block the compiler stores
     * the code before it reads directives, so a block is safe.
     *
     * $items accepts the two shapes this site holds: `a` is an array of
     * paragraphs in `siteFaq` and one string in the `faq:` block of a post.
     *
     * JSON_HEX_TAG is necessary. A question that contains `</script>` closes
     * the element, and the rest of the document then goes into the page as
     * live markup.
     */
    'faqSchema' => function ($page, $items) {
        $questions = collect($items)->map(function ($item) {
            $answer = is_array($item['a']) || $item['a'] instanceof Traversable
                ? implode(' ', collect($item['a'])->all())
                : $item['a'];

            return [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => html_entity_decode(strip_tags((string)$answer), ENT_QUOTES, 'UTF-8'),
                ],
            ];
        })->all();

        if ($questions === []) {
            return '';
        }

        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            '@id' => $page->getCanonicalUrl() . '#faq',
            'mainEntity' => $questions,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    },

    /*
     * The ItemList for a listing page, or null when the page lists nothing.
     *
     * A listing page is a hub. Without this node it is a page with links on it,
     * and a search engine has to work out from the markup which links are the
     * contents and which are the navigation.
     *
     * The caller assigns the result to `$page->schemaNodes`, and
     * _includes/structured-data.blade.php retypes the page as a CollectionPage
     * when it finds the list. One array therefore decides both the type of the
     * page and the contents of the list. They were two expressions before: the
     * template counted its raw items and this code dropped the blank ones, so a
     * post with no title gave a page that claimed four items and a list that
     * held three, and a listing of nothing but blanks gave a CollectionPage
     * pointing at a list that no node declared.
     *
     * $items is [['name' => …, 'url' => …], …], in the order the page shows.
     */
    'collectionListNode' => function ($page, $items) {
        $entries = collect($items)
            ->map(fn($item) => [
                'name' => trim((string)($item['name'] ?? '')),
                'url' => trim((string)($item['url'] ?? '')),
            ])
            ->filter(fn($item) => $item['name'] !== '' && $item['url'] !== '')
            ->values();

        if ($entries->isEmpty()) {
            return null;
        }

        return [
            '@type' => 'ItemList',
            '@id' => $page->getCanonicalUrl() . '#itemlist',
            'numberOfItems' => $entries->count(),
            'itemListElement' => $entries
                ->map(fn($item, $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'url' => $item['url'],
                ])
                ->all(),
        ];
    },

    // One test for "this page gets no search result". The breadcrumbs, the
    // case-study Article and the service nodes all suppress themselves on a
    // noindex page, and they must agree on what noindex means.
    'isNoIndex' => function ($page) {
        return str_contains(strtolower($page->getRobotsStatus()), 'noindex');
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
     */
    'getBreadcrumbs' => function ($page) {
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
        $names = [
            'home' => 'Home',
            'blog' => 'Blog',
            'work' => 'Work',
            'projects' => 'Open source',
        ];

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
