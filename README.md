# hesamrad.com

The website of Hesam Rad, an independent software engineer.

The website is a static site. It shows the services, the case studies and the
blog. It also has a contact form.

## How the website works

The website has two parts. The two parts are independent.

| Part | Technology | Where it runs |
| --- | --- | --- |
| The website | Jigsaw (PHP) and Vite | GitHub Pages |
| The contact form | Cloudflare Worker and D1 | Cloudflare |

**The website part.** Jigsaw reads the Blade templates and the Markdown files.
It writes plain HTML files. Vite compiles the CSS and the JavaScript. GitHub
Actions does the build. GitHub Pages serves the result.

There is no database and no server-side code in the website part. Each page is
a file.

**The contact form part.** A static site cannot receive a form submission. A
Cloudflare Worker receives it instead. The Worker writes the enquiry to a D1
database. Then the Worker sends an email and a Telegram message.

For the Worker, read [worker/README.md](worker/README.md). The remainder of
this document is about the website part.

## Requirements

Install these programs before you start:

- PHP 8.2 or later. The deploy uses 8.3.
- Composer 2
- Node 22 or later

## Set up after a fresh pull

Do these three steps in order.

**Step 1.** Install the PHP dependencies:

```bash
composer install
```

**Step 2.** Install the Node dependencies:

```bash
npm install
```

**Step 3.** Build the assets and the site:

```bash
npm run prod
```

The build writes the site to `build_production/`. This directory is not in
Git.

You do not have to make a `.env` file. The repository has one, and it holds no
secret.

## Everyday commands

To look at the site, run two commands in two terminals.

In the first terminal, start Vite:

```bash
npm run dev
```

In the second terminal, start the PHP server:

```bash
./vendor/bin/jigsaw serve --router=router.php
```

Then open `http://localhost:8000`.

Vite watches the CSS and the JavaScript. Jigsaw rebuilds the HTML when you
change a template. The browser reloads without your help.

Always use `--router=router.php`. Without it, PHP's built-in server answers an
address it cannot find with the home page and a 200 status. Every typo then
looks like the home page loading correctly, and you cannot open the 404 page
to look at it. The router does what GitHub Pages does: an address that does
not exist gets `404.html` and a 404 status. It changes nothing else.

To make a build that is the same as the deployed build:

```bash
npm run prod
```

`npm run prod` uses `config.production.php`. That file sets `baseUrl` to
`https://hesamrad.com` and sets `production` to `true`. Some content is hidden
when `production` is `true`. The sample businesses on the Zero to One page are
an example.

`npm run dev` and `npm run build` use `config.php`. That file sets `baseUrl`
to `http://localhost:8000`.

## Where the files are

```
config.php                 Site settings for local builds
config.production.php      Site settings for the deployed build
source/
  index.blade.php          One file for each page
  _layouts/                Page frames
  _includes/               The header, the footer, the structured data
  _components/             Parts that more than one page uses
  _posts/                  Blog posts, in Markdown
  _caseStudies/            Case studies, in Markdown
  _assets/css/             The design system
  _assets/js/main.js       All of the JavaScript
  _assets/fonts/           Inter and JetBrains Mono
listeners/                 Code that runs during the build
worker/                    The contact form Worker
```

The CSS has five files. Each file has one purpose:

| File | Purpose |
| --- | --- |
| `tokens.css` | Every color, size and space value |
| `base.css` | Element defaults |
| `layout.css` | The page frame, the header, the hero |
| `components.css` | Cards, forms, the FAQ and the other parts |
| `syntax.css` | Colors for code blocks |

## Add a blog post

**Step 1.** Copy `source/_posts/template.md` to a new file. Give the file a
name that reads well in a URL. The file name becomes the address of the post.

**Step 2.** Complete the front matter at the top of the new file. These fields
are necessary:

| Field | What it does |
| --- | --- |
| `title` | The heading, and the title in a search result |
| `description` | The text in a search result and on a shared card |
| `created_at` | The date of publication |
| `updated_at` | The date of the last change |
| `tags` | A list of subjects |
| `isPublished` | The post is built only when this is `true` |

**Step 3.** Write the post below the front matter, in Markdown.

**Step 4.** Build the site and read the post.

Keep `title` at 60 characters or fewer. A longer title is cut short in a
search result.

An unpublished post is never built. There is no page, and there is no address
to find.

## Add a case study

Make a new file in `source/_caseStudies/`. Use `top-menu.md` as the model.

A case study has more fields than a post. Each field holds one part of the
story. The layout puts the parts in order.

| Field | What it holds |
| --- | --- |
| `summary` | The story in one paragraph |
| `role` | The work that was yours |
| `problem` | The condition before the work |
| `constraints` | What made the work difficult |
| `built` | A list of what you delivered |
| `decisions` | A list of `choice` and `why` pairs |
| `timeline` | The order of the work |
| `results` | What changed |
| `quote` | What the client said |
| `differently` | What you would change |
| `stack` | The technologies |
| `cover` and `gallery` | The images |

Two fields control whether the case study appears:

- Set `published` to `true` to build the page.
- Set `sample` to `true` to mark the case study as an example. A sample is not
  built when `production` is `true`.

Name a client only when you have permission.

## Themes

The site has a light theme and a dark theme.

The theme is set by a `theme` attribute on the `<html>` element. A small
script in the page head sets the attribute before the first paint. This
prevents a flash of the wrong theme.

The header has a button that changes the theme. The choice is kept in the
browser.

Do not write a color value in a rule. Use a token from `tokens.css`. Each
token has a value in the light theme and a value in the dark theme. This is
what makes the dark theme possible to check.

## Deploy

Push to `main`. GitHub Actions does the remainder.

The workflow is `.github/workflows/deploy.yml`. It does these steps:

1. It installs PHP 8.3, Node 22 and the dependencies.
2. It runs `npm run prod`.
3. It runs `jigsaw build production`.
4. It writes the `CNAME` file.
5. It copies `build_production/` to the `gh-pages` branch.

GitHub Pages serves the `gh-pages` branch.

The workflow also runs at 02:00 on each weekday. This keeps the calculated
values correct. The years of experience on the home page is one of them.

## Occasional tasks

These scripts run only when you change a source file. They are not part of the
build.

Make a virtual environment first:

```bash
python3 -m venv .venv
```

**To rebuild the fonts.** Run this after you replace a `.ttf` file in
`source/_assets/fonts/`:

```bash
.venv/bin/pip install fonttools brotli
PYTHON=.venv/bin/python ./build_fonts.sh
```

The script makes the `.woff2` files from the `.ttf` files. It records a
fingerprint of each `.ttf` file in `fonts.manifest`. It rebuilds a font only
when the fingerprint changes.

Keep the `.ttf` files. The browser uses the `.woff2` files, but the script
needs the `.ttf` files to make them.

**To rebuild the mark.** This writes both `source/_assets/images/logo.svg` and
`source/favicon.ico` from one set of numbers. Run it after you change the
geometry or a color in the script:

```bash
.venv/bin/python build_mark.py
```

**To rebuild the share card.** Run this after you change the headline or a
color:

```bash
.venv/bin/pip install Pillow
.venv/bin/python build_og_image.py
```

## The contact form

The form is on the home page, the Work page, the Services page, the Zero to One
page and each case study.

The form sends to the address in `formEndpoint` in `config.php`. Turnstile
protects the form. The site key is in `turnstileSiteKey` in `config.php`. The
site key is public. The secret key is not in this repository.

To set up the Worker, or to read the stored enquiries, see
[worker/README.md](worker/README.md).

## License

`LICENSE.txt` is the MIT license of the Jigsaw blog template. It arrived with
the template in the first commit, and it is not a license for this site. The
copyright line names the template authors.

This site has no license of its own yet. Add one, or delete `LICENSE.txt`.
Until then, no person has permission to reuse the code, the written content or
the images.
