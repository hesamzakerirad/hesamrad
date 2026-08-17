@php
    /*
     * Inline SVG icons. Each icon uses `currentColor`, therefore an icon in a
     * link or a button gets the correct color in the two themes.
     *
     * Usage: @include('_components.icon', ['name' => 'arrow-right'])
     *
     * Parameters:
     *   $name      — a key of $paths          (default 'arrow-right')
     *   $class     — an extra class, for size (optional)
     *   $iconTitle — an accessible name; the icon is decorative without it
     *
     * Use the name `iconTitle` and not `title`. The Blade @include gives the
     * full scope of the parent to the partial. A variable with the name `title`
     * gets the document title from the layout, and each icon then has the name
     * of the page. A prefixed name cannot collide with a caller variable.
     */
    $paths = [
        'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'arrow-left' => '<path d="M19 12H5M11 18l-6-6 6-6"/>',
        'arrow-down' => '<path d="M12 5v14M6 13l6 6 6-6"/>',
        'external' => '<path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon' => '<path d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5Z"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'copy' => '<rect x="9" y="9" width="11" height="11" rx="1"/><path d="M5 15H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1"/>',
        'check' => '<path d="M4 12.5 9 17.5 20 6.5"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="m3 7 9 6 9-6"/>',
        'github' => '<path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>',
        'linkedin' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4V8h4v1.79A6 6 0 0 1 16 8Z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
        'shield' => '<path d="M12 3l8 3v6c0 4.5-3.2 8.3-8 9.5C7.2 20.3 4 16.5 4 12V6Z"/><path d="m9 12 2 2 4-4"/>',
        'layers' => '<path d="m12 3 9 5-9 5-9-5Z"/><path d="m3 13 9 5 9-5"/>',
        'gauge' => '<path d="M4 18a8 8 0 1 1 16 0"/><path d="m12 14 4-4"/>',
        'terminal' => '<path d="m5 8 4 4-4 4M12 16h7"/>',
    ];

    $name = $name ?? 'arrow-right';
    $body = $paths[$name] ?? $paths['arrow-right'];
    $iconTitle = $iconTitle ?? null;
@endphp

{{-- Keep `focusable="false"` in the two conditions. Legacy IE and Edge put an
     SVG in the tab order. An icon with a name must not be a tab stop. --}}
<svg class="icon {{ $class ?? '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
    stroke-linecap="round" stroke-linejoin="round" focusable="false"
    @if ($iconTitle) role="img" @else aria-hidden="true" @endif>
    @if ($iconTitle)
        <title>{{ $iconTitle }}</title>
    @endif
    {!! $body !!}
</svg>
