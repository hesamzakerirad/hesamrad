{{-- A standalone call to action. It is deliberately self-contained so it can be
     dropped at the end of any page — home, projects, a post — without the page
     having to know anything about it. --}}
<section class="hero">
    <p class="hero-overline">
        <span class="hero-dot" aria-hidden="true"></span>
        Available for new projects
    </p>

    <h2 class="hero-title">Let's build something.</h2>

    <p class="hero-body">If you have a product in mind — or one that needs rescuing — tell me
        about it. I read every email and reply to the ones I can help with.</p>

    <a class="hero-cta" href="mailto:{{ $page->email }}?subject={{ rawurlencode('A new project') }}">
        Start a conversation
        {{-- Decorative: the link text already says where it goes. --}}
        <i class="fa-solid fa-arrow-right ml-05"></i>
    </a>
</section>
