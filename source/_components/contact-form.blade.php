{{--
    The enquiry form.

    The form posts to a Cloudflare Worker at hello.hesamrad.com. The site is
    static on GitHub Pages and has no server. The Worker writes each submission
    to D1 before it sends a notification. Therefore a mail failure does not
    cause the loss of an enquiry.

    Keep the `action` and `method` attributes. main.js changes the form to an
    in-place fetch. If the script does not run, the browser does a usual
    submission, and the Worker replies with a redirect.

    Turnstile needs JavaScript. The <noscript> block below gives the email
    address, because the button cannot operate without JavaScript.
--}}
<form class="form" method="POST" action="{{ $page->formEndpoint }}" data-contact-form>
    {{-- The honeypot field. `botcheck` is the reserved Web3Forms name. It
         catches simple bots before the Turnstile check. Keep this field out of
         the accessibility tree and out of the tab order. A person then cannot
         find it, but a bot that fills all fields sets it. --}}
    <input class="visually-hidden" type="checkbox" name="botcheck" tabindex="-1" autocomplete="off" aria-hidden="true">

    {{-- The source page of the enquiry. Many pages show this same form. Without
         this field, the inbox and D1 cannot show which page caused the
         enquiry. --}}
    <input type="hidden" name="page" value="{{ $page->getCanonicalUrl() }}">

    <noscript>
        <p class="form__note form__note--warning">This form needs JavaScript to check you're not a bot. Email me
            directly at <a href="mailto:{{ $page->email }}">{{ $page->email }}</a> and I'll pick it up
            there.</p>
    </noscript>

    <p class="form__note">Three fields, and no budget question. We can work that out on the call.</p>

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
            want to be true that isn't true today.</p>
    </div>

    {{-- Turnstile draws itself in this element. It writes a token into a hidden
         `cf-turnstile-response` input. The Worker then verifies the token. Most
         visitors do not see a challenge. --}}
    <div class="field cf-turnstile" data-sitekey="{{ $page->turnstileSiteKey }}" data-theme="auto"
        data-action="contact"></div>

    <div class="btn-row">
        <button class="btn btn--primary" type="submit">Send it</button>
    </div>

    {{-- Use `polite` and not `assertive`. The message comes after an action of
         the visitor, therefore it must not interrupt the screen reader.

         Do not make this live region `hidden`. The live region must be in the
         accessibility tree before the text comes. If it is not, the screen
         reader finds the element and the text together, and it announces
         nothing. The element stays in the DOM, and its height is zero while it
         is empty. --}}
    <p class="form__status" data-form-status role="status" aria-live="polite" tabindex="-1"></p>

    <p class="form__fallback dim">
        I use what you send here to reply, and nothing else. You're not added to any list. Or email me directly
        at <a href="mailto:{{ $page->email }}">{{ $page->email }}</a>.
    </p>
</form>

@once
    @push('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endpush
@endonce
