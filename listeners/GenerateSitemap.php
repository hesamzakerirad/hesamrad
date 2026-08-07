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
        '*.txt',
        '*.xml',
        '*.json',
    ];

    /** Cache of source file path => last commit timestamp. */
    protected $gitTimestamps = [];

    public function handle(Jigsaw $jigsaw)
    {
        $baseUrl = $jigsaw->getConfig('baseUrl');

        if (! $baseUrl) {
            echo "\nTo generate a sitemap.xml file, please specify a 'baseUrl' in config.php.\n\n";

            return;
        }

        $sitemap = new Sitemap($jigsaw->getDestinationPath().'/sitemap.xml');
        $postDates = $this->postDates($jigsaw);

        collect($jigsaw->getOutputPaths())
            ->reject(function ($path) {
                return $this->isExcluded($path);
            })
            ->each(function ($path) use ($baseUrl, $sitemap, $postDates) {
                $sitemap->addItem(
                    $this->url($baseUrl, $path),
                    $this->lastModified($path, $postDates)
                );
            });

        $sitemap->write();
    }

    public function isExcluded($path)
    {
        return Str::is($this->exclude, $path);
    }

    protected function url($baseUrl, $path)
    {
        if ($path === '') {
            return $baseUrl;
        }

        $url = $baseUrl.$path;

        // Don't add a slash if it's a file (has an extension like .xml, .txt, .html, etc.)
        if (preg_match('/\.\w+$/', $path)) {
            return $url;
        }

        return rtrim($url, '/').'/';
    }

    /**
     * Last modification time for a page, or null when it can't be determined.
     *
     * Never fall back to the build time: the site is rebuilt on a schedule, so
     * that would restamp every URL on every build and teach crawlers to ignore
     * the field. An absent lastmod is better than a false one.
     */
    protected function lastModified($path, $postDates)
    {
        $key = trim($path, '/');

        if (isset($postDates[$key])) {
            return $postDates[$key];
        }

        return $this->lastCommitTimestamp($key);
    }

    /** Post paths mapped to their `updated_at` timestamp. */
    protected function postDates(Jigsaw $jigsaw)
    {
        return collect($jigsaw->getCollection('posts'))
            ->mapWithKeys(function ($post) {
                return [trim($post->getPath(), '/') => $post->updated_at ?? $post->created_at];
            })
            ->filter()
            ->all();
    }

    /**
     * Timestamp of the last commit touching the source file behind a path.
     *
     * Requires full history; the deploy workflow checks out with fetch-depth 0.
     */
    protected function lastCommitTimestamp($key)
    {
        $source = $this->sourceFile($key);

        if (! $source) {
            return null;
        }

        if (! array_key_exists($source, $this->gitTimestamps)) {
            $output = @shell_exec('git log -1 --format=%ct -- '.escapeshellarg($source).' 2>/dev/null');
            $this->gitTimestamps[$source] = is_numeric(trim((string) $output)) ? (int) trim($output) : null;
        }

        return $this->gitTimestamps[$source];
    }

    /** Best-effort mapping of an output path back to the source file that produced it. */
    protected function sourceFile($key)
    {
        $base = $key === '' ? 'index' : $key;

        foreach (['.blade.php', '.md', '.html'] as $extension) {
            foreach (["source/{$base}{$extension}", "source/{$base}/index{$extension}"] as $candidate) {
                if (file_exists($candidate)) {
                    return $candidate;
                }
            }
        }

        return null;
    }
}
