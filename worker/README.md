# Contact form Worker

The site is static and served from GitHub Pages, so it has no server of its own
to accept a form submission. This Worker is that server.

```
hesamrad.com                    hello.hesamrad.com
(GitHub Pages, static)          (this Worker)
        │                               │
        │  POST the form  ─────────────▶│
        │                               ├─ verify Turnstile + honeypot
        │                               ├─ INSERT into D1        ← the lead is now safe
        │                               ├─ email Hesam    (Resend)
        │                               ├─ push to phone  (Telegram)
        │                               └─ auto-reply to the sender (Resend)
```

**The write to D1 happens before any notification, and the response says
"success" once that row commits — not once the email lands.** Every email-only
form loses the enquiry if delivery fails, and it fails silently, which is the
worst way for an inbound channel to break. Here a Resend outage costs a
notification, not a client: the enquiry is on disk and can be read back.

---

## Setup

Everything below runs on your machine. **No key in this list should ever be
pasted into a file in this repository** — `wrangler secret put` prompts for the
value and stores it encrypted on Cloudflare's side.

### 1. Wrangler

```bash
cd worker
npm install
npx wrangler login
```

### 2. The database

```bash
npx wrangler d1 create hesamrad-enquiries
```

Copy the printed `database_id` into `wrangler.jsonc`, replacing
`REPLACE_WITH_D1_DATABASE_ID`. Then create the table, remotely and locally:

```bash
npx wrangler d1 execute hesamrad-enquiries --remote --file=./schema.sql
npx wrangler d1 execute hesamrad-enquiries --local  --file=./schema.sql
```

### 3. Turnstile

Cloudflare dashboard → **Turnstile** → *Add widget*. Hostname `hesamrad.com`,
widget mode **Managed**. You get two keys:

- the **site key** is public — put it in `config.php` as `turnstileSiteKey`
- the **secret key** is not:

```bash
npx wrangler secret put TURNSTILE_SECRET_KEY
```

### 4. Resend

Sign up at [resend.com](https://resend.com), then **Domains → Add domain →
`hesamrad.com`**. It gives you DKIM and SPF records to add; your DNS is already
on Cloudflare, so add them there and wait for verification.

Until the domain verifies you can only send from `onboarding@resend.dev`, and
only to your own account address — fine for a first test, useless for the
auto-reply, which goes to strangers.

```bash
npx wrangler secret put RESEND_API_KEY
```

### 5. Telegram

Message [@BotFather](https://t.me/BotFather) → `/newbot` → it returns a token.
Then send your new bot any message, and read your chat id from:

```bash
curl "https://api.telegram.org/bot<TOKEN>/getUpdates"
```

The id is `result[0].message.chat.id`. It is a plain number, negative for
groups.

```bash
npx wrangler secret put TELEGRAM_BOT_TOKEN
npx wrangler secret put TELEGRAM_CHAT_ID
```

### 6. Deploy

```bash
npx wrangler deploy
```

The `custom_domain` route in `wrangler.jsonc` makes Cloudflare create the
`hello.hesamrad.com` record and its certificate on first deploy. It does **not**
touch the apex, so the site keeps being served by GitHub Pages exactly as it is
now.

### 7. Point the site at it

`config.php` already has `formEndpoint`. Fill in the Turnstile site key beside
it and rebuild:

```php
'formEndpoint' => 'https://hello.hesamrad.com',
'turnstileSiteKey' => '0x4AAA…',
```

---

## Migrations

`schema.sql` uses `CREATE TABLE IF NOT EXISTS`, so re-running it against a
database that already exists does nothing — it will not add a column. Adding
one is a manual `ALTER TABLE`:

```bash
npx wrangler d1 execute hesamrad-enquiries --remote \
  --command "ALTER TABLE enquiries ADD COLUMN source_page TEXT"
```

**`source_page` needs exactly that**, if the table was created before it
existed. Without it every insert fails, and because the Worker treats a failed
insert as the one error worth surfacing, the visitor is told to email instead —
so it fails loudly rather than silently, but it does fail.

## Checking it works

```bash
npx wrangler tail                      # live logs while you submit the form
npx wrangler d1 execute hesamrad-enquiries --remote \
  --command "SELECT received_at, name, email, budget FROM enquiries ORDER BY id DESC LIMIT 10"
```

A real submission should produce: a row in D1, an email to `NOTIFY_TO` whose
**reply** goes to the sender, a Telegram push, and an acknowledgement in the
sender's inbox.

## Local development

Put the same four secrets in `worker/.dev.vars` (gitignored), then:

```bash
npx wrangler dev
```

Point `formEndpoint` at `http://localhost:8787` and add that origin to
`ALLOWED_ORIGINS` while you are testing. **Change both back before deploying.**

## Things worth knowing

- **CORS is an allow-list, not `*`.** `ALLOWED_ORIGINS` in `wrangler.jsonc` is
  the only set of origins that may post here. A wildcard would let any site on
  the internet submit through your endpoint under its own name.
- **Turnstile needs JavaScript.** The form carries a `<noscript>` block pointing
  at the email address, because a bot check that cannot render would otherwise
  leave a button that silently never works.
- **A Turnstile token is single-use.** `main.js` resets the widget after any
  failure; without that, a second attempt always fails as a duplicate.
- **The honeypot answers 200.** Telling a bot it was caught only teaches whoever
  wrote it to try something else.
- **Auto-replies go to arbitrary addresses.** That is why this uses Resend
  rather than Cloudflare Email Service, which requires the paid Workers plan for
  non-verified recipients and is still in beta.
- **No rate limiting yet.** The honeypot and Turnstile carry that load for now.
  If the endpoint ever gets hammered, add a KV or D1 counter keyed on
  `CF-Connecting-IP`.
