<div class="post">
    <p>
        <a href="{{ $post->getUrl() }}" title="{{ $post->title }}" class="no-decoration">{{ $post->title }}</a>
    </p>

    <small>
        {{ $post->getJalaliDate() }}
    </small>
</div>
