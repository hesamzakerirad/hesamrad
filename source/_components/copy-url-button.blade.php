{{-- Copies the address of the current page, or the string in `copyText`.

     Without `copyText` the button copies `window.location.href`, which is what
     an article page wants and what every existing caller relies on. Pass
     `copyText` to copy something else, such as the payment address on /pay/,
     where the value comes from config.php and the button must not hold a second
     copy of it. --}}
<button class="btn btn--ghost" type="button" data-copy-url
    @isset($copyText) data-copy-text="{{ $copyText }}" @endisset>
    <span data-copy-idle>
        @include('_components.icon', ['name' => 'copy', 'class' => 'btn__icon'])
        <span>{{ $copyLabel ?? 'Copy URL' }}</span>
    </span>
    {{-- Use the `hidden` attribute and not a CSS class. Assistive technology
         must read the correct state before the script starts. --}}
    <span data-copy-done hidden>
        @include('_components.icon', ['name' => 'check', 'class' => 'btn__icon'])
        <span>{{ $copiedLabel ?? 'Copied' }}</span>
    </span>
    {{-- The clipboard can refuse: an old browser, a page served over plain
         HTTP, a permission the reader denied. The button did nothing visible
         then, and a button that does nothing is the worst thing to hand a
         person who is halfway through paying an invoice.

         It does not clear on a timer, unlike the success state. A success
         message that disappears is tidy; a failure message that disappears
         leaves the reader where they started. It clears on the next press. --}}
    <span data-copy-fail hidden>
        @include('_components.icon', ['name' => 'close', 'class' => 'btn__icon'])
        <span>{{ $failedLabel ?? 'Select it by hand' }}</span>
    </span>
</button>

{{-- The swap above changes the label of the button, and a changed label is not
     announced. A reader who cannot see the check mark gets silence, and silence
     after pressing a button reads as a button that does nothing.

     The region is empty on load, so nothing is announced until a copy happens.
     It lives outside the button: a live region inside the control that changed
     is read inconsistently, and the button already has its own name.

     `polite` and not `assertive`. A copy is a success and not an interruption. --}}
<span class="visually-hidden" role="status" aria-live="polite" data-copy-status></span>
