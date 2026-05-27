<header class="mt-6">
    <div class="flex flex-col justify-center items-center gap-6">
        <div>
            <a class="text-xl font-bold text-black dark:text-white" href="{{ $page->baseUrl }}">{{ $page->siteName }}</a>
        </div>

        <div class="flex flex-row gap-6 border-t border-b dark:border-gray-700 flex-1 w-full justify-center">
            <a class="py-3 px-1 text-black dark:text-gray-400 font-light text-sm" href="{{ $page->baseUrl }}">Home</a>
            <a class="py-3 px-1 text-black dark:text-gray-400 font-light text-sm"
                href="{{ $page->baseUrl }}/projects/">Projects</a>
            <a class="py-3 px-1 text-black dark:text-gray-400 font-light text-sm"
                href="{{ $page->baseUrl }}/resume/">Resume</a>
        </div>
    </div>
</header>
