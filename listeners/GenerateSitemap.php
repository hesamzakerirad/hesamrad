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

        $destination = $jigsaw->getDestinationPath();
        $sitemap = new Sitemap($destination.'/sitemap.xml');
        $postDates = $this->postDates($jigsaw);

        collect($jigsaw->getOutputPaths())
            ->reject(function ($path) use ($jigsaw) {
                return $this->isExcluded($path) || $this->isNoIndex($jigsaw, $path);
            })
            ->each(function ($path) use ($baseUrl, $destination, $sitemap, $postDates, $jigsaw) {
                $sitemap->addItem(
                    $this->url($baseUrl, $destination, $path),
                    $this->lastModified($jigsaw, $path, $postDates)
                );
            });

        $sitemap->write();
    }

    public function isExcluded($path)
    {
        return Str::is($this->exclude, $path);
    }

    /**
     * Whether the page asks robots not to index it.
     *
     * Listing a noindex URL in the sitemap sends crawlers two opposite
     * instructions, so the page's own robots value is the single source of
     * truth and this stays in sync with it automatically. Reading it off the
     * page object rather than out of the rendered HTML also survives pages
     * that assign `robots` at render time, which several templates do.
     */
    public function isNoIndex(Jigsaw $jigsaw, $path)
    {
        $page = $jigsaw->getPages()[$path] ?? null;

        if (! $page) {
            return false;
        }

        return str_contains(strtolower($page->getRobotsStatus()), 'noindex');
    }

    protected function url($baseUrl, $destination, $path)
    {
        if ($path === '') {
            return $baseUrl;
        }

        $url = $baseUrl.$path;

        // Only directories get a trailing slash. Testing the filesystem beats
        // sniffing for an extension, which misreads a slug such as
        // 'upgrading-to-v2.0' as a filename.
        if (! is_dir(rtrim($destination.'/'.ltrim($path, '/'), '/'))) {
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
    protected function lastModified(Jigsaw $jigsaw, $path, $postDates)
    {
        $key = trim($path, '/');

        if (isset($postDates[$key])) {
            return $postDates[$key];
        }

        return $this->lastCommitTimestamp($jigsaw, $key);
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
    protected function lastCommitTimestamp(Jigsaw $jigsaw, $key)
    {
        $source = $this->sourceFile($jigsaw, $key);

        if (! $source) {
            return null;
        }

        if (! array_key_exists($source, $this->gitTimestamps)) {
            $output = @shell_exec('git log -1 --format=%ct -- '.escapeshellarg($source).' 2>/dev/null');
            $timestamp = is_numeric(trim((string) $output)) ? (int) trim($output) : null;

            if ($timestamp === null) {
                echo "\nWarning: could not read a commit date for {$source}; its sitemap entry will have no lastmod.\n";
            }

            $this->gitTimestamps[$source] = $timestamp;
        }

        return $this->gitTimestamps[$source];
    }

    /** Best-effort mapping of an output path back to the source file that produced it. */
    protected function sourceFile(Jigsaw $jigsaw, $key)
    {
        $base = $key === '' ? 'index' : $key;
        $sourcePath = rtrim($jigsaw->getSourcePath(), '/');

        foreach (['.blade.php', '.md', '.html'] as $extension) {
            foreach (["{$sourcePath}/{$base}{$extension}", "{$sourcePath}/{$base}/index{$extension}"] as $candidate) {
                if (file_exists($candidate)) {
                    return $candidate;
                }
            }
        }

        return null;
    }
}
