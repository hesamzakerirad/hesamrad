@php
    $isExternal = $post->isExternal && $post->source;
    $link = $isExternal ? $post->source : $post->getUrl();
@endphp

<article class="post-preview">
    <a href="{{ $link }}" title="{{ $post->title }}" target="{{ $post->isExternal ? '_blank' : '_self' }}">
        <header>
            <h3>{{ $post->title }}</h3>
        </header>
        <p>{{ $post->description }}</p>
        <small>
            @if ($post->isExternal)
                <span class="badge">بازنشر</span> -
            @endif
            <time>{{ $post->getJalaliDate() }}</time>
        </small>
    </a>
</article>
