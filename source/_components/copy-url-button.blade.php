<button class="btn btn--ghost" type="button" data-copy-url>
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
</button>
