<header>
    <div class="big-container">
        <div class="identity">
            <a href="{{ $page->baseUrl }}" class="no-decoration">
                {{ $page->siteName }}
            </a>
            <small class="description"> {{ $page->siteDescription }} </small>
        </div>
        <div>
            <span id="theme" class="theme-toggle">
                <svg id="theme-icon" width="24" height="24" viewBox="0 0 24 24">
                    <path id="moon-icon" fill="currentColor"
                        d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1-8.313-12.454z" />
                    <path id="sun-icon" fill="currentColor"
                        d="M12 18a6 6 0 1 1 0-12a6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8a4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z" />
                </svg>
            </span>
        </div>
    </div>
</header>