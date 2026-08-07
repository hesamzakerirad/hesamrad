{{-- The listing page is English but the posts it lists may not be, so each
     preview declares its own language and direction. --}}
<article class="post-preview" lang="{{ $post->getLanguage() }}" dir="{{ $post->getDirection() }}">
    <a href="{{ $post->getCanonicalUrl() }}" title="{{ $post->title }}">
        <header>
            <h2>{{ $post->title }}</h2>
        </header>
    </a>
    <p>{{ $post->getSummary(160) }}</p>
    <small>
        <time datetime="{{ $post->getUpdatedAtDate() }}">{{ $post->getUpdatedJalaliDate() }}</time>
    </small>
</article>
