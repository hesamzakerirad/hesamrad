<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use samdark\sitemap\Sitemap;
use TightenCo\Jigsaw\Jigsaw;

class GenerateSitemap
{
    protected $exclude = [
        '/assets/*',
        '*/favicon.ico',
        '*/404*',
    ];

    public function handle(Jigsaw $jigsaw)
    {
        $baseUrl = $jigsaw->getConfig('baseUrl');

        if (! $baseUrl) {
            echo "\nTo generate a sitemap.xml file, please specify a 'baseUrl' in config.php.\n\n";

            return;
        }

        $sitemap = new Sitemap($jigsaw->getDestinationPath().'/sitemap.xml');

        collect($jigsaw->getOutputPaths())
            ->reject(function ($path) {
                return $this->isExcluded($path);
            })
            ->each(function ($path) use ($baseUrl, $sitemap) {
                // Homepage
                if ($path === '') {
                    $url = $baseUrl;
                } else {
                    $url = $baseUrl.$path;

                    // Don't add slash if it's a file (has extension like .xml, .txt, .html, etc.)
                    if (! preg_match('/\.\w+$/', $path)) {
                        // Add trailing slash if missing
                        if (substr($url, -1) !== '/') {
                            $url .= '/';
                        }
                    }
                }

                $sitemap->addItem($url, time(), Sitemap::DAILY);
            });

        $sitemap->write();
    }

    public function isExcluded($path)
    {
        return Str::is($this->exclude, $path);
    }
}
