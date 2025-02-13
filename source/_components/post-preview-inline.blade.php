@php
    $link = $post->source ?? $post->getUrl();
@endphp

<div class="post">
    <p>
        <a 
            href="{{ $link }}" 
            title="{{ $post->title }}" 
            target="{{ $post->isExternal ? '_blank' : '_self' }}"
            class="no-decoration"
        >
            {{ $post->title }}

            @if ($post->isExternal)
                <span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" transform="rotate(-90)">
                        <path d="M12.5 12L21.5 3V10V3H14.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9.5 3H5.5C4.395 3 3.5 3.895 3.5 5V19C3.5 20.105 4.395 21 5.5 21H19.5C20.605 21 21.5 20.105 21.5 19V15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            @endif
        </a>
    </p>

    <small>
        {{ $post->getJalaliDate() }}
    </small>
</div>
