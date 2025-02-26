@php
    $link = $post->source ?? $post->getUrl();
@endphp

<div class="post-preview">
    <p>
        <a 
            href="{{ $link }}" 
            title="{{ $post->title }}" 
            target="{{ $post->isExternal ? '_blank' : '_self' }}"
        >
            {{ $post->title }}
        </a>
    </p>

    <small>
        {{ $post->getJalaliDate() }}
    </small>
</div>
