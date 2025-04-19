<article class="post-preview">
    <a href="{{ $post->getUrl() }}" title="{{ $post->title }}">
        <header>
            <h3>{{ $post->title }}</h3>
        </header>
        <p>{{ $post->description }}</p>
    </a>
</article>
