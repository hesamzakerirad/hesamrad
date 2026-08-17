{{--
    The contact block that closes a page.

    main.blade.php includes this last, below the questions. Do not put a copy in
    a page template. Two copies give a page two `id="contact"` anchors, and the
    buttons that point at #contact then reach the first one.

    A page opts out with `disableContact: true` in its front matter. A blog post
    opts out on its own, because a post closes with the link to the next post,
    and that link is the stronger action there.

    The wording comes from the front matter of the page and falls back to the
    `contact` pair in config.php. Read that comment before a change here.
--}}
@php
    $showsContact = ! $page->disableContact && ! $page->isPost($page);
    $contactHeading = $page->contactHeading ?: $page->contact['heading'];
    $contactIntro = $page->contactIntro ?: $page->contact['intro'];
@endphp

@if ($showsContact)
    {{-- Keep `id="contact"`. Every "Start a conversation" link on the site ends
         in this fragment, and the footer link uses it on the home page. --}}
    <section class="shell section" id="contact">
        <div class="callout">
            <h2>{{ $contactHeading }}</h2>
            <p>{{ $contactIntro }}</p>

            @include('_components.contact-form')
        </div>
    </section>
@endif
