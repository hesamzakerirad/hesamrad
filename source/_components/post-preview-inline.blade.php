<article class="post-preview">
    <a href="{{ $post->getUrlWithTrailingSlash() }}" title="{{ $post->title }}">
        <header>
            <h4>{{ $post->title }}</h4>
        </header>
    </a>
    <p>{{ $post->description }}</p>
    <small>
        <time datetime="{{ $post->getUpdatedAtDate() }}">{{ $post->getUpdatedJalaliDate() }}</time>
    </small>
</article>
