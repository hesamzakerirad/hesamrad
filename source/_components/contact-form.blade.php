{{--
    The enquiry form.

    Posts to a Cloudflare Worker at hello.hesamrad.com, because the site itself
    is static on GitHub Pages and has no server of its own. The Worker stores
    every submission in D1 before it tries to notify anyone, so a mail outage
    costs a notification rather than a client.

    It is a real `action`/`method` form: main.js upgrades it to an in-place
    fetch so nobody loses the page they were reading, and without that script
    the browser submits it normally and the Worker answers with a redirect.

    Turnstile needs JavaScript to render, so the <noscript> block below points
    people at the email address instead of leaving them with a button that
    cannot work.
--}}
<form class="form" method="POST" action="{{ $page->formEndpoint }}" data-contact-form>
    {{-- Web3Forms' reserved honeypot name, kept because it costs nothing and
         catches the naive bots before Turnstile is even consulted. Out of
         sight, out of the accessibility tree and out of the tab order, so a
         person cannot reach it and a bot that fills every field trips it. --}}
    <input class="visually-hidden" type="checkbox" name="botcheck" tabindex="-1" autocomplete="off" aria-hidden="true">

    {{-- Which page the enquiry came from. The same form serves the home page,
         services, Zero to One and each case study, and without this every
         submission looks identical in the inbox and in D1 — so there is no way
         to tell which page is actually producing work. --}}
    <input type="hidden" name="page" value="{{ $page->getCanonicalUrl() }}">

    <noscript>
        <p class="form__note form__note--warning">This form needs JavaScript to check you are not a bot. Email me
            directly at <a href="mailto:{{ $page->email }}">{{ $page->email }}</a> and I will pick it up
            there.</p>
    </noscript>

    <p class="form__note">Three fields. No budget question &mdash; we can work that out on the call.</p>

    <div class="field">
        <label class="field__label" for="contact-name">Your name</label>
        <input class="field__input" id="contact-name" name="name" type="text" autocomplete="name" maxlength="200"
            required>
    </div>

    <div class="field">
        <label class="field__label" for="contact-email">Email</label>
        <input class="field__input" id="contact-email" name="email" type="email" autocomplete="email" maxlength="320"
            required>
    </div>

    <div class="field">
        <label class="field__label" for="contact-message">What needs building, or what is broken?</label>
        <textarea class="field__input" id="contact-message" name="message" rows="6" maxlength="5000" required
            aria-describedby="contact-message-hint"></textarea>
        <p class="field__hint" id="contact-message-hint">A paragraph is plenty. What the business does, and what you
            want to be true that is not true today.</p>
    </div>

    {{-- Turnstile renders itself into this and writes a token into a hidden
         `cf-turnstile-response` input, which the Worker verifies server-side.
         Usually invisible: most people never see a challenge at all. --}}
    <div class="field cf-turnstile" data-sitekey="{{ $page->turnstileSiteKey }}" data-theme="auto"
        data-action="contact"></div>

    <div class="btn-row">
        <button class="btn btn--primary" type="submit">Send it</button>
    </div>

    {{-- `polite`, not `assertive`: this follows the visitor's own action, so it
         should not interrupt whatever a screen reader is mid-sentence on.

         Never `hidden`. A live region has to be in the accessibility tree
         before the text arrives, or there is no change for the screen reader
         to notice — it registers the element and its content together and says
         nothing. It stays in the DOM permanently and collapses to a
         zero-height box while it is empty. --}}
    <p class="form__status" data-form-status role="status" aria-live="polite" tabindex="-1"></p>

    <p class="form__fallback dim">
        I use what you send here to reply, and nothing else &mdash; you are not added to any list. Or email me directly
        at <a href="mailto:{{ $page->email }}">{{ $page->email }}</a>.
    </p>
</form>

@once
    @push('scripts')
        {{-- Loaded only on pages that actually carry the form, and deferred so
             it never competes with the content someone came to read. --}}
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endpush
@endonce
