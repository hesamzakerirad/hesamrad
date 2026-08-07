<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class GenerateIndex
{
    public function handle(Jigsaw $jigsaw)
    {
        $data = collect($jigsaw->getCollection('posts')->map(function ($page) {
            return [
                'title' => $page->title,
                'categories' => $page->categories,
                // The same URL and summary every other surface advertises —
                // building them here separately drifted from the canonical form
                // and ignored both `permalink` and an author-set description.
                'link' => $page->getCanonicalUrl(),
                'snippet' => $page->getSummary(),
            ];
        })->values());

        file_put_contents(
            $jigsaw->getDestinationPath().'/index.json',
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }
}
