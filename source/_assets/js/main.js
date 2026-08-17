/**
 * Syntax highlighting, fetched only where there is code to highlight.
 *
 * highlight.js and its nine grammars are roughly 50KB of a 66KB bundle, and
 * exactly one page on this site contains a <pre>. Importing it at the top
 * level meant every visitor to the home page — the ones this site actually
 * exists for — downloaded, parsed and executed a syntax highlighter to render
 * a page with no code on it.
 *
 * A dynamic import lets the bundler split it into its own chunk, so that cost
 * is paid on the blog post and nowhere else. Static imports inside the guard,
 * not require(): the dev server serves this file as an ES module and leaves a
 * require() call in place, where it is undefined.
 */
async function initHighlighting() {
    if (!document.querySelector('pre code')) return;

    const [{ default: hljs }, ...grammars] = await Promise.all([
        import('highlight.js/lib/core'),
        import('highlight.js/lib/languages/bash'),
        import('highlight.js/lib/languages/css'),
        import('highlight.js/lib/languages/xml'),
        import('highlight.js/lib/languages/javascript'),
        import('highlight.js/lib/languages/json'),
        import('highlight.js/lib/languages/markdown'),
        import('highlight.js/lib/languages/php'),
        import('highlight.js/lib/languages/scss'),
        import('highlight.js/lib/languages/yaml'),
    ]);

    // `xml` is registered as `html`: that is the name posts actually use in
    // their fences, and highlight.js will not resolve the alias on its own
    // when languages are registered individually.
    const names = ['bash', 'css', 'html', 'javascript', 'json', 'markdown', 'php', 'scss', 'yaml'];
    grammars.forEach((grammar, index) => hljs.registerLanguage(names[index], grammar.default));

    document.querySelectorAll('pre code').forEach((block) => hljs.highlightElement(block));
}

const root = document.documentElement;
const THEME_KEY = 'theme';
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

/**
 * Theme.
 *
 * The head script has already set the attribute before first paint; this only
 * handles the toggle and keeps following the OS while the visitor has not
 * expressed a preference of their own. Once they click, the stored choice
 * wins and OS changes are ignored — an explicit choice should not be quietly
 * undone at sunset.
 */
function initTheme() {
    const toggle = document.querySelector('[data-theme-toggle]');

    const apply = (theme) => {
        root.setAttribute('theme', theme);
        if (toggle) {
            toggle.setAttribute('aria-pressed', String(theme === 'dark'));
        }
    };

    const stored = (() => {
        try {
            return localStorage.getItem(THEME_KEY);
        } catch {
            // Safari in private mode throws on access, not just on write.
            return null;
        }
    })();

    apply(stored === 'dark' || stored === 'light' ? stored : (prefersDark.matches ? 'dark' : 'light'));

    prefersDark.addEventListener('change', (event) => {
        try {
            if (localStorage.getItem(THEME_KEY)) return;
        } catch {
            // Unreadable storage means no stored preference to respect.
        }
        apply(event.matches ? 'dark' : 'light');
    });

    if (!toggle) return;

    toggle.addEventListener('click', () => {
        const next = root.getAttribute('theme') === 'dark' ? 'light' : 'dark';
        apply(next);
        try {
            localStorage.setItem(THEME_KEY, next);
        } catch {
            // A theme that does not survive a reload still beats a dead button.
        }
    });
}

/**
 * Header: a compact/blurred state once the page has scrolled, plus the
 * mobile menu.
 */
function initHeader() {
    const header = document.querySelector('[data-header]');
    if (!header) return;

    const onScroll = () => header.classList.toggle('is-stuck', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    const toggle = header.querySelector('[data-nav-toggle]');
    if (!toggle) return;

    const nav = header.querySelector('.site-nav');
    let closing;

    /*
     * The panel sits before the toggle in the DOM, so opening it and leaving
     * focus where it was meant the next Tab moved *away* from the thing that
     * had just appeared. Moving focus to the first link makes the keyboard
     * order match what the eye sees.
     */
    const setOpen = (open, { moveFocus = false } = {}) => {
        header.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', String(open));

        // The panel fills the screen now, so the page behind it has to stop
        // scrolling while it is up.
        document.documentElement.classList.toggle('is-nav-open', open);

        /*
         * Keeps the panel visible while it fades out. visibility cannot be
         * transitioned reliably — it stays on `visible` and leaves a closed
         * menu in the tab order — so the class does that job and is dropped
         * once the fade has finished.
         */
        clearTimeout(closing);

        if (open) {
            header.classList.remove('is-closing');
        } else {
            header.classList.add('is-closing');
            // Matches the closing clip-path duration in layout.css. Shorter and
            // the panel is cut to hidden part-way through the reveal.
            closing = setTimeout(() => header.classList.remove('is-closing'), 260);
        }

        if (open && moveFocus && nav) {
            /*
             * A `visibility: hidden` element silently refuses focus, and the
             * class above has not been applied to the box tree yet. Reading a
             * layout property forces that flush synchronously — a rAF would
             * also work, but rAF never fires in a hidden document, and this
             * has to be reliable rather than merely usually right.
             */
            void nav.offsetHeight;
            nav.querySelector('.site-nav__link')?.focus();
        }
    };

    toggle.addEventListener('click', () => setOpen(!header.classList.contains('is-open'), { moveFocus: true }));

    // Following a link inside the panel navigates, but same-page anchors do
    // not, and the panel would stay over the target.
    header.querySelectorAll('.site-nav__link').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    // Tapping the page behind an open panel should dismiss it — on a phone that
    // is the first thing anyone tries, and it did nothing.
    document.addEventListener('pointerdown', (event) => {
        if (!header.classList.contains('is-open')) return;
        if (header.contains(event.target)) return;
        setOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && header.classList.contains('is-open')) {
            setOpen(false);
            toggle.focus();
        }
    });
}

/**
 * Scroll reveal.
 *
 * Deliberately coarse: whole sections fade up as a unit rather than each card
 * arriving on its own delay. A staggered cascade draws attention to the
 * animation; this should only make the page feel like it is keeping up with
 * the scroll.
 *
 * The hiding class is added here, never authored in the markup, so a visitor
 * whose JavaScript failed sees everything rather than nothing. The hero is
 * excluded — it is above the fold and has nothing to reveal from.
 */
function initReveal() {
    if (prefersReducedMotion.matches || !('IntersectionObserver' in window)) return;

    /*
     * A hidden document runs neither IntersectionObserver nor an un-throttled
     * timer, so hiding anything here would leave a page opened in a background
     * tab blank until it happened to be looked at. Nothing is hidden at all in
     * that case — the effect is worth exactly none of that risk.
     */
    if (document.visibilityState === 'hidden') return;

    /*
     * Only what is below the fold. There is no entrance to play for content the
     * visitor is already looking at, and not hiding it means no flash on load
     * and nothing above the fold that depends on a script to become visible.
     */
    const targets = [...document.querySelectorAll('.page-main .section, .page-main .case')]
        .filter((element) => element.getBoundingClientRect().top > window.innerHeight * 0.9);

    if (!targets.length) return;

    const show = (element) => {
        element.classList.add('is-visible');
        observer.unobserve(element);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => entry.isIntersecting && show(entry.target));
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.05 });

    targets.forEach((element) => {
        element.classList.add('reveal');
        observer.observe(element);
    });

    /*
     * Belt and braces: if the observer has still not fired after a few seconds
     * — a tab backgrounded right after load, a browser quirk — show everything
     * anyway. Nothing decorative should ever be the last thing standing between
     * a visitor and the content.
     */
    window.setTimeout(() => targets.forEach(show), 3000);
}

/**
 * Testimonials: collapse the long ones, and survive a dead avatar URL.
 *
 * The clamp is applied here rather than in the stylesheet so that a visitor
 * whose JavaScript failed sees every recommendation in full. These quotes are
 * the only real proof on the site — truncating them by default and relying on
 * a script to restore them would be exactly the wrong way round.
 *
 * Only quotes that genuinely overflow get a control. A "read the rest" button
 * under a three-line quote is noise.
 *
 * The cards are stacked rather than gridded, so nothing here has to reconcile
 * one card's height against another's — expanding a quote pushes the ones
 * below it down and leaves them otherwise untouched.
 */
function initQuotes() {
    const quotes = document.querySelectorAll('.quote');
    if (!quotes.length) return;

    /*
     * A LinkedIn avatar URL is signed and expires. When one dies the image is
     * removed so the initials underneath show through, rather than leaving a
     * broken-image glyph in a circle. `alt` is empty because the person's name
     * is already right beside it.
     */
    document.querySelectorAll('[data-avatar]').forEach((img) => {
        img.addEventListener('error', () => img.remove(), { once: true });
        // A cached failure can land before the listener is attached.
        if (img.complete && img.naturalWidth === 0) img.remove();
    });

    const apply = () => {
        quotes.forEach((quote) => {
            const text = quote.querySelector('.quote__text');
            const toggle = quote.querySelector('[data-quote-toggle]');
            if (!text || !toggle) return;

            // Measure against the clamp, then keep it only if it cut something
            // off. Reading scrollHeight while unclamped would always match
            // clientHeight and every quote would look short enough.
            quote.classList.add('is-clamped');

            /*
             * A full line has to be hidden before this is worth a control.
             * Narrow columns push a quote a fraction past the clamp constantly,
             * and "read the rest" that reveals seven words is worse than the
             * seven words simply not being there.
             */
            const lineHeight = parseFloat(getComputedStyle(text).lineHeight) || 0;
            const overflows = text.scrollHeight - text.clientHeight > lineHeight;

            quote.classList.toggle('is-clamped', overflows);
            toggle.hidden = !overflows;

            if (!overflows) return;

            const more = toggle.querySelector('[data-quote-more]');
            const less = toggle.querySelector('[data-quote-less]');

            toggle.onclick = () => {
                const expanded = quote.classList.toggle('is-clamped') === false;
                toggle.setAttribute('aria-expanded', String(expanded));
                if (more) more.hidden = expanded;
                if (less) less.hidden = !expanded;
            };
        });
    };

    /*
     * Line height depends on the webfont, so measuring before Inter lands
     * clamps against the fallback's metrics and gets the answer wrong. The
     * catch covers browsers without the Fonts API rather than skipping it.
     */
    if (document.fonts?.ready) {
        document.fonts.ready.then(apply).catch(apply);
    } else {
        apply();
    }
}

/** Copy-to-clipboard, used by article pages. */
function initCopyButtons() {
    document.querySelectorAll('[data-copy-url]').forEach((button) => {
        const idle = button.querySelector('[data-copy-idle]');
        const done = button.querySelector('[data-copy-done]');
        let timer;

        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href);
            } catch {
                // Denied permission or an insecure origin. Saying nothing is
                // better than a fake success state.
                return;
            }

            if (!idle || !done) return;
            idle.hidden = true;
            done.hidden = false;
            window.clearTimeout(timer);
            timer = window.setTimeout(() => {
                idle.hidden = false;
                done.hidden = true;
            }, 2000);
        });
    });
}

/**
 * Year stamp in the footer, so a cached page can never show a stale range.
 */
/*
 * Marks the section the reader is in, in the contents rail.
 *
 * Position, not visibility. An IntersectionObserver alone answers "is this
 * heading on screen", which is the wrong question — in the middle of a long
 * section no heading is on screen at all and the rail would go blank. The
 * observer is used only as a cheap trigger to recompute; the answer comes from
 * which heading was the last one to pass the top of the viewport.
 *
 * Enhancement only. Without it the links still jump, and every entry simply
 * stays the color it already was.
 */
function initContents() {
    const rail = document.querySelector('.post-toc');

    if (!rail || !('IntersectionObserver' in window)) return;

    const links = [...rail.querySelectorAll('a[href^="#"]')];
    const headings = links.map((link) => document.getElementById(decodeURIComponent(link.hash.slice(1))));

    // A rail that does not match its headings is a rail that would mark the
    // wrong one, so it marks none.
    if (!links.length || headings.some((heading) => !heading)) return;

    let current = -1;

    /*
     * When the reader is inside a subsection, two entries matter: the h3 they
     * are in and the h2 it belongs to. The h3 is marked; its parent is marked
     * more quietly, so the rail says where you are without two entries
     * competing to be the answer.
     */
    const parentOf = links.map((link) => {
        const item = link.closest('li');
        const outer = item && item.parentElement.closest('li');

        return outer ? links.indexOf(outer.querySelector('a')) : -1;
    });

    const mark = (index) => {
        if (index === current) return;

        const within = index === -1 ? -1 : parentOf[index];

        links.forEach((link, i) => {
            link.classList.toggle('is-current', i === index);
            link.classList.toggle('is-within', i === within);

            // The rail is a nav, so the entry for the section being read is
            // the current location within it.
            if (i === index) {
                link.setAttribute('aria-current', 'true');
            } else {
                link.removeAttribute('aria-current');
            }
        });

        current = index;
    };

    const update = () => {
        /*
         * The line the reader is judged to be at, and it sits a good way into
         * the viewport rather than on the header's edge.
         *
         * Level with the landing point it was a knife edge: a click leaves the
         * heading resting exactly on the boundary, and scrolling up three
         * pixels marked the section above while section six still filled the
         * screen. A quarter of the way down gives the reading position some
         * room either side of a heading, which is what stops it flickering.
         *
         * The floor keeps a click correct on a short viewport, where a
         * quarter of the height could otherwise land above where a jumped-to
         * heading comes to rest.
         */
        const landing = parseFloat(getComputedStyle(headings[0]).scrollMarginTop) || 0;
        const line = Math.max(landing + 40, window.innerHeight * 0.28);

        let index = -1;

        headings.forEach((heading, i) => {
            if (heading.getBoundingClientRect().top <= line) index = i;
        });

        mark(index);
    };

    /*
     * A click pins the entry it was on, and only the reader can unpin it.
     *
     * scroll-behavior is smooth, so a click starts an animation that can run
     * for a second or more over a long article. Holding the mark for a fixed
     * time was a guess, and it was wrong: the hold expired while the page was
     * still moving, tracking resumed mid-flight, and the rail settled on the
     * section above the one clicked. Clicking twice appeared to work only
     * because the second click had nowhere to scroll to.
     *
     * So the pin is released by the reader taking over — a wheel, a drag, a
     * key — rather than by a timer that has to outlast an animation whose
     * length depends on how far the page moved.
     */
    let pinned = -1;

    links.forEach((link, index) => {
        link.addEventListener('click', () => {
            mark(index);
            pinned = index;
        });
    });

    ['wheel', 'touchmove', 'keydown'].forEach((type) => {
        window.addEventListener(type, () => { pinned = -1; }, { passive: true });
    });

    let queued = false;
    let settling;

    const run = () => {
        if (pinned !== -1) return;
        update();
    };

    const schedule = () => {
        if (!queued) {
            queued = true;
            requestAnimationFrame(() => {
                queued = false;
                run();
            });
        }

        /*
         * The frame above is coalesced, which drops the last scroll event of a
         * movement — so the final read can be a frame stale. This catches the
         * position the page actually came to rest at.
         */
        clearTimeout(settling);
        settling = setTimeout(run, 120);
    };

    const observer = new IntersectionObserver(schedule, { threshold: [0, 1] });

    headings.forEach((heading) => observer.observe(heading));

    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule, { passive: true });

    update();
}

function initYear() {
    document.querySelectorAll('[data-current-year]').forEach((element) => {
        element.textContent = String(new Date().getFullYear());
    });
}

/**
 * Contact form.
 *
 * The form already works as a plain POST — this only replaces the navigation
 * with an in-place status message, so nobody loses the page they were reading
 * to a third-party confirmation screen.
 *
 * Every failure path lands on the same message. A visitor does not care which
 * layer broke; they care that the email address underneath still works.
 */
function initContactForm() {
    document.querySelectorAll('[data-contact-form]').forEach((form) => {
        const status = form.querySelector('[data-form-status]');
        const submit = form.querySelector('[type="submit"]');

        const say = (message, state) => {
            if (!status) return;
            status.className = `form__status ${state}`;
            status.textContent = message;
        };

        /*
         * A Turnstile token is single-use and short-lived. Without this, a
         * visitor who hits a failure — a dropped connection, a validation
         * error — would send the same spent token on their second attempt and
         * be rejected every time, with no way out but a full page reload.
         */
        const resetChallenge = () => {
            if (window.turnstile) window.turnstile.reset();
        };

        /*
         * `aria-disabled` and a guard, not the `disabled` property. Disabling
         * the button the visitor has just pressed removes it from the
         * accessibility tree, which drops focus to <body> — a keyboard or
         * screen-reader user is thrown back to the top of the document at the
         * exact moment the answer arrives underneath the button.
         */
        let inFlight = false;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (inFlight) return;

            // FormData picks up Turnstile's hidden `cf-turnstile-response`
            // input along with the visible fields, so the token needs no
            // special handling here.
            const payload = Object.fromEntries(new FormData(form));

            inFlight = true;
            submit.setAttribute('aria-disabled', 'true');
            say('Sending…', '');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                    body: JSON.stringify(payload),
                });
                const result = await response.json().catch(() => ({}));

                if (!response.ok || result.success === false) throw new Error(result.message);

                form.reset();
                resetChallenge();
                say('Thank you — that reached me. I will reply within a day.', 'is-ok');

                /*
                 * The only conversion this site has. Analytics was counting
                 * page views and nothing else, which cannot answer the one
                 * question worth asking: which page produces enquiries.
                 *
                 * `generate_lead` is the recognized GA4 event name, so it lands
                 * in the reports that already exist rather than needing a
                 * custom definition. Fired only on a confirmed success, never
                 * on submit — otherwise a bounced request counts as a lead.
                 *
                 * Guarded because gtag is a third-party script: a blocker, an
                 * offline load or a consent tool can all leave it undefined,
                 * and none of those should throw inside a submit handler.
                 */
                if (typeof window.gtag === 'function') {
                    window.gtag('event', 'generate_lead', {
                        page_location: window.location.href,
                        form_id: form.id || 'contact',
                        source_page: payload.page || '',
                    });
                }
            } catch (error) {
                resetChallenge();
                // The server's own wording when it gave one — it knows whether
                // this was a bad address or a failed bot check, and "something
                // went wrong" helps nobody fix it.
                say(
                    error?.message
                        || 'That did not send. Email hesamrad.dev@gmail.com and I will pick it up there.',
                    'is-error',
                );

                // A failure needs reading before anything else can be done
                // about it. Success does not: the message is polite, the form
                // is cleared, and moving focus would only be in the way.
                if (status) status.focus();
            } finally {
                inFlight = false;
                submit.removeAttribute('aria-disabled');
            }
        });
    });
}

initHighlighting();
initTheme();
initHeader();
initReveal();
initQuotes();
initCopyButtons();
initContents();
initYear();
initContactForm();
