<article class="post-preview">
    <a href="{{ $post->getUrlWithTrailingSlash() }}" title="{{ $post->title }}">
        <header>
            <h3>{{ $post->title }}</h3>
        </header>
        <p>{{ $post->description }}</p>
        <span class="color-primary">
            خواندن در {{ $post->getReadTime() }} دقیقه
            <i class="fa-solid fa-arrow-left mr-05"></i>
        </span>
    </a>
</article>
