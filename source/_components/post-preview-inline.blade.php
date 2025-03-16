@php
    $link = $post->source ?? $post->getUrl();
@endphp

<article>
    <a 
        href="{{ $link }}" 
        title="{{ $post->title }}" 
        target="{{ $post->isExternal ? '_blank' : '_self' }}"
        class="no-decoration"
    >
        <header>
            <h3>{{ $post->title }}</h3>
        </header>
        <p class="description">{{ $post->description }}</p>
        <small>
            @if ($post->isExternal)
                <small class="badge">بازنشر</small> - 
            @endif
            <time>{{ $post->getJalaliDate() }}</time>
        </small>
    </a>
</article>
