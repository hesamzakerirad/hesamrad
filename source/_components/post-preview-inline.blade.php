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
        </a>

        @if ($post->isExternal)
            <span class="smaller">(پیوند)</span>
        @endif
    </p>

    <small>
        {{ $post->getJalaliDate() }}
    </small>
</div>
