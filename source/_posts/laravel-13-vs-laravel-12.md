---
extends: _layouts.post
section: content
title: 'Laravel 13 vs Laravel 12: What Actually Changed'
description: 'Laravel 13 shipped in March 2026 with a first-party AI SDK, JSON:API resources and vector search — plus a few upgrade gotchas the release notes gloss over.'
language: en
locale: en_US
tags:
    - Laravel
    - Laravel 13
    - PHP
    - Upgrade Guide
robots:
    - index
    - follow
created_at: 2026-08-07
updated_at: 2026-08-07
thumbnail:
thumbnailCopyRightSource:
readTime: 8
source: 'https://laravel.com/docs/13.x/releases'
isFeatured: false
isPublished: true
---

Laravel 13 came out on March 17th, 2026. I have had it in production on two applications for a few months now, and the honest summary is this: the upgrade itself was boring, and everything interesting about the release is optional.

That is a compliment. Laravel 12 was the release where Taylor and the team started deliberately spreading new features across minor versions instead of hoarding them for the yearly bump, and Laravel 13 is the first release where you can really feel the payoff. The official upgrade guide estimates ten minutes. For one of my apps that was accurate. For the other it took an afternoon, and I will get to why.

<!-- more -->

## The short version

If you are on a well-maintained Laravel 12 app:

- You need **PHP 8.3 or newer**. That is the only hard blocker.
- Bump five dependencies, run your test suite, ship it.
- Nothing you use every day changed. Eloquent, routing, validation, Blade — all the same.
- The new stuff (AI SDK, JSON:API resources, vector search) is opt-in. You can upgrade today and ignore all of it.

If you are still on Laravel 11 or older, upgrade to 12 first. Laravel 12 gets bug fixes until August 13th, 2026 and security patches until February 24th, 2027, so there is no emergency — but the runway is shorter than it looks.

## PHP 8.3 is the new floor

Laravel 13 supports PHP 8.3 through 8.5. Laravel 12 supported 8.2, so if you are pinned to 8.2 on some old server, that is the work item. Everything else in the upgrade is downstream of this.

Worth checking before you start: your hosting provider, your Docker base image, and your CI matrix. In my experience the framework upgrade is trivial and the PHP upgrade is where the actual bugs hide, so do them as two separate deploys if you can.

## The Laravel AI SDK is the headline

This is the feature the release is built around. Laravel now ships a first-party, provider-agnostic API for talking to AI models — text generation, tool-calling agents, embeddings, images and audio.

The part I like is that it looks like Laravel instead of looking like a wrapper around someone else's HTTP client:

```php
use App\Ai\Agents\SalesCoach;

$response = SalesCoach::make()
    ->prompt('Analyze this sales transcript...');

return (string) $response;
```

Images and audio follow the same shape:

```php
use Laravel\Ai\Image;

$image = Image::of('A donut sitting on the kitchen counter')->generate();
```

And embeddings are a string method, which is a small detail that says a lot about how deep this is wired in:

```php
use Illuminate\Support\Str;

$embeddings = Str::of('Napa Valley has great wine.')->toEmbeddings();
```

Is it a replacement for a purpose-built AI stack? No. But for the common case — "I need to summarize this, classify that, and search these documents by meaning" — it removes a dependency and a wrapper class you were going to write anyway.

## Vector search made it into the query builder

Related to the above, and easy to miss: you can now run semantic similarity queries straight from the query builder, backed by PostgreSQL and `pgvector`.

```php
$documents = DB::table('documents')
    ->whereVectorSimilarTo('embedding', 'Best wineries in Napa Valley')
    ->limit(10)
    ->get();
```

That one method covers what used to be a raw expression, a hand-rolled embedding call, and a comment apologizing for both. If you have ever bolted semantic search onto a Laravel app, you know exactly how much glue this deletes.

## JSON:API resources are first-party now

If you build APIs to the JSON:API specification, Laravel handles the tedious parts for you now: resource object serialization, relationship inclusion, sparse fieldsets, links and the compliant response headers.

I do not write JSON:API myself, so I cannot tell you how it holds up against the established community packages. But moving this into the framework means one fewer third-party dependency that goes quiet for six months after every major release, and that alone is worth something.

## CSRF protection got renamed and got stricter

This is the change most likely to touch your code. The CSRF middleware is now called `PreventRequestForgery` instead of `VerifyCsrfToken`, and it does more than check a token — it also verifies the request origin using the `Sec-Fetch-Site` header.

`VerifyCsrfToken` and `ValidateCsrfToken` still work as deprecated aliases, so nothing breaks on day one. But anywhere you reference the class by name, update it:

```php
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;

// Laravel 12 and earlier
->withoutMiddleware([VerifyCsrfToken::class]);

// Laravel 13
->withoutMiddleware([PreventRequestForgery::class]);
```

Feature tests that disable CSRF are the usual place this shows up. Grep for `VerifyCsrfToken` across your whole project, tests included, and be done with it.

## Attributes keep spreading

Laravel 13 pushes PHP attributes into a lot more of the framework. Controller middleware and authorization are the visible ones:

```php
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('auth')]
class CommentController
{
    #[Middleware('subscribed')]
    #[Authorize('create', [Comment::class, 'post'])]
    public function store(Post $post)
    {
        // ...
    }
}
```

Queued jobs got `#[Tries]`, `#[Backoff]`, `#[Timeout]` and `#[FailOnTimeout]`, and there is more scattered across Eloquent, events, notifications, validation and testing.

I am genuinely undecided on this one. Declaring middleware next to the method it guards is easier to read than hunting through a route file. But it also means the answer to "what protects this endpoint?" now lives in two possible places instead of one. My rule so far: pick one style per project and do not mix them.

## Two small things I use constantly

**Queue routing by class.** You can now declare a job's connection and queue in one central place instead of repeating `onQueue()` at every dispatch site:

```php
Queue::route(
    ProcessPodcast::class,
    connection: 'redis',
    queue: 'podcasts',
);
```

**`Cache::touch()`.** Extend a cache entry's TTL without fetching the value and writing it back. Obvious in hindsight, and it removes a read-modify-write from every sliding-expiry implementation I have ever written.

## The gotchas the release notes are quiet about

"Minimal breaking changes" is true, but it is not the same as "no behavioral changes." These are the ones that actually cost me time, roughly in order of how likely they are to bite you.

**Cache and session key prefixes changed shape.** The framework-level fallback went from underscores to hyphens:

```php
// Laravel 12:  myapp_cache_
// Laravel 13:  myapp-cache-
```

If your config files set `CACHE_PREFIX`, `REDIS_PREFIX` and `SESSION_COOKIE` explicitly, nothing happens. If you were relying on the generated default, you will effectively cold-start your cache and log every user out on deploy. Set them explicitly before you ship, not after.

**Cached objects are no longer unserialized blindly.** The default `cache` config now sets `serializable_classes` to `false`, which is a real security improvement — it closes off PHP deserialization gadget chains if your `APP_KEY` ever leaks. But if you cache actual objects, you have to allow-list them:

```php
'serializable_classes' => [
    App\Data\CachedDashboardStats::class,
],
```

This was the afternoon. A cached DTO in one of my apps came back as garbage until I found this in the guide.

**`upsert()` now validates `uniqueBy`.** Pass an empty value and you get an `InvalidArgumentException` instead of invalid SQL. Note this applies on MySQL and MariaDB too, even though those drivers ignore the value and use the table's own indexes.

**`DELETE` with `JOIN` plus `ORDER BY` or `LIMIT`.** Those clauses used to be silently dropped on joined deletes. Laravel 13 compiles them, which means MySQL and MariaDB may now throw a `QueryException` where the query previously ran — as an unbounded delete. Read that sentence twice if it applies to you: the old behavior was worse, but the new behavior is louder.

**Event property renames.** `JobAttempted` swapped the boolean `$exceptionOccurred` for the actual `$exception` object (or `null`), and `QueueBusy` renamed `$connection` to `$connectionName`. Both are a one-line fix in your listeners, but neither fails loudly.

**`Container::call()` respects nullable defaults.** A parameter like `?Carbon $date = null` with no matching binding now resolves to `null` instead of a `Carbon` instance. This brings it in line with constructor injection, which changed back in Laravel 12.

**`Str` factories reset between tests.** If you set a custom UUID or ULID factory once and relied on it persisting across test methods, move it into `setUp()`.

**`array_first()` and `array_last()`.** Laravel 13 pulls in `symfony/polyfill-php85`, which defines these globals on PHP below 8.5. If you have `laravel/helpers` or your own helpers file, you can get a conflict — and the historical `array_first()` took a callback while the polyfill just returns the first element. Use `Arr::first()` and `Arr::last()` and the problem disappears.

There are a handful more — polymorphic pivot table names are pluralized now, Bootstrap pagination view names changed, `Manager::extend()` callbacks are bound to the manager instance, the default password reset email subject is now "Reset your password" — but those are narrow enough that your test suite will find them if they apply.

## How to actually do the upgrade

Bump these in `composer.json`:

```json
"laravel/framework": "^13.0",
"laravel/tinker": "^3.0",
"laravel/boost": "^2.0",
"phpunit/phpunit": "^12.0",
"pestphp/pest": "^4.0"
```

If you use [Laravel Boost](https://github.com/laravel/boost), there is a guided path: install `^2.0` in your Laravel 12 app and run the `/upgrade-laravel-v13` command in Claude Code, Cursor, OpenCode, Gemini or VS Code. It walks the upgrade guide for you. [Shift](https://laravelshift.com) is still there if you prefer the community-maintained automated route.

Then the boring, reliable part: run your test suite, deploy to staging, and click through the parts of the app your tests do not cover. Cache and queue behavior is where the surprises live, so exercise those specifically.

## Should you upgrade?

Yes, but there is no rush. Laravel 12 has bug fixes until August 2026 and security fixes until February 2027, and Laravel 13 will collect its own quality-of-life improvements over the next year regardless of when you jump on.

What I would say is this: if you are already on PHP 8.3 or newer, do it on a quiet afternoon. The upgrade genuinely is small, and being on the current major means every new feature that lands over the next twelve months arrives with a `composer update` instead of a migration project.

If you are still on PHP 8.2, treat that as the real work and Laravel 13 as the reward at the end of it.

---

**Further reading:** the [Laravel 13 release notes](https://laravel.com/docs/13.x/releases) and the [upgrade guide](https://laravel.com/docs/13.x/upgrade) are both short and worth reading end to end before you start.

If you build things with Laravel, I have a few open-source packages on [my projects page](/projects/) that you might find useful.
