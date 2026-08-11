<button class="btn btn--ghost" type="button" data-copy-url>
    <span data-copy-idle>
        @include('_components.icon', ['name' => 'copy', 'class' => 'btn__icon'])
        <span>{{ $copyLabel ?? 'Copy URL' }}</span>
    </span>
    {{-- `hidden` rather than a CSS class: the button must read correctly to
         assistive tech before the script has swapped the states. --}}
    <span data-copy-done hidden>
        @include('_components.icon', ['name' => 'check', 'class' => 'btn__icon'])
        <span>{{ $copiedLabel ?? 'Copied' }}</span>
    </span>
</button>
