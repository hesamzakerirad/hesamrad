{{-- The listing page is English but the posts it lists may not be, so each
     preview declares its own language and direction — and dates each post in
     the calendar its readers actually use. --}}
<article class="post-preview" lang="{{ $post->getLanguage() }}" dir="{{ $post->getDirection() }}">
    <a href="{{ $post->getCanonicalUrl() }}" title="{{ $post->title }}">
        <header>
            <h2>{{ $post->title }}</h2>
        </header>
    </a>
    <p>{{ $post->getSummary(160) }}</p>
    <small>
        <time datetime="{{ $post->getUpdatedAtDate() }}">
            {{ $post->getBaseLanguage() === 'fa' ? $post->getUpdatedJalaliDate() : $post->getUpdatedAtDate('j F Y') }}
        </time>
    </small>
</article>
