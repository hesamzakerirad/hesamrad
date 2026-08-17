/**
 * Contact form handler for hesamrad.com.
 *
 * The site is static and served from GitHub Pages, so there is no server of
 * its own to accept a form. This Worker is that server, and nothing else.
 *
 * The order of operations is deliberate:
 *
 *   1. Verify the Turnstile token and the honeypot.
 *   2. Write the enquiry to D1.
 *   3. Only then attempt to notify.
 *
 * Storing first is the whole point. Every email-only form — including the
 * Web3Forms setup this replaces — loses the lead if delivery fails, and it
 * fails silently, which is the worst possible way for an inbound channel to
 * break. Here a notification failure is an annoyance: the enquiry is already
 * on disk and can be read back.
 *
 * That is also why the response says "success" once the row is committed,
 * not once the email lands.
 */

const ENDPOINTS = {
    turnstile: 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
    resend: 'https://api.resend.com/emails',
    telegram: (token) => `https://api.telegram.org/bot${token}/sendMessage`,
};

const MAX_FIELD = {
    name: 200,
    email: 320, // the practical maximum length of an address
    message: 5000,
    budget: 60,
};

export default {
    async fetch(request, env, ctx) {
        const origin = request.headers.get('Origin');
        const cors = corsHeaders(origin, env);

        if (request.method === 'OPTIONS') {
            return new Response(null, { status: 204, headers: cors });
        }

        if (request.method !== 'POST') {
            return new Response('Method not allowed', { status: 405, headers: { ...cors, Allow: 'POST' } });
        }

        // A browser with JavaScript submits JSON; a plain form submits
        // urlencoded. The second path exists so the form is not a dead button
        // for anyone whose script failed to load, and it wants HTML back
        // rather than a JSON blob.
        const wantsJson = (request.headers.get('Accept') || '').includes('application/json');

        let fields;
        try {
            fields = await readFields(request);
        } catch {
            return respond(wantsJson, cors, 400, 'That submission could not be read.', env);
        }

        // The honeypot is a checkbox no person can reach. FormData omits an
        // unchecked box entirely, so its mere presence is the signal.
        if (fields.botcheck) {
            // Answer as though it worked. Telling a bot it was caught only
            // teaches whoever wrote it to try something else.
            return respond(wantsJson, cors, 200, 'Thank you — that reached me.', env);
        }

        const invalid = validate(fields);
        if (invalid) {
            return respond(wantsJson, cors, 400, invalid, env);
        }

        const passedTurnstile = await verifyTurnstile(fields.turnstileToken, request, env);
        if (!passedTurnstile) {
            return respond(
                wantsJson,
                cors,
                403,
                'That check did not pass. Please reload and try again, or email hesamrad.dev@gmail.com directly.',
                env,
            );
        }

        const enquiry = {
            name: fields.name.trim(),
            email: fields.email.trim(),
            message: fields.message.trim(),
            budget: (fields.budget || '').trim(),
            receivedAt: new Date().toISOString(),
            country: request.headers.get('CF-IPCountry') || null,
            // Bounded like everything else: this arrives from the client and a
            // hidden field is trivially editable.
            sourcePage: (fields.sourcePage || '').slice(0, 500) || null,
            userAgent: (request.headers.get('User-Agent') || '').slice(0, 300),
        };

        try {
            await store(enquiry, env);
        } catch (error) {
            // The one failure worth reporting, because it is the only one that
            // means the enquiry is genuinely gone.
            console.error('D1 insert failed', error);
            return respond(
                wantsJson,
                cors,
                500,
                'Something went wrong saving that. Please email hesamrad.dev@gmail.com instead.',
                env,
            );
        }

        // Notifications run after the enquiry is safe, and their failures are
        // logged rather than surfaced — the visitor has done nothing wrong and
        // there is nothing useful for them to do about a Telegram outage.
        const notifications = Promise.allSettled([
            emailNotification(enquiry, env),
            telegramNotification(enquiry, env),
            autoReply(enquiry, env),
        ]).then((results) => {
            results.forEach((result, index) => {
                if (result.status === 'rejected') {
                    console.error(['email', 'telegram', 'auto-reply'][index], 'failed:', result.reason);
                }
            });
        });

        // waitUntil keeps the Worker alive for these without making the
        // visitor wait on three third-party round trips.
        ctx.waitUntil(notifications);

        return respond(wantsJson, cors, 200, 'Thank you — that reached me. I will reply within a day.', env);
    },
};

/* ── Request handling ──────────────────────────────────────────────── */

async function readFields(request) {
    const type = request.headers.get('Content-Type') || '';

    if (type.includes('application/json')) {
        const body = await request.json();
        return {
            name: str(body.name),
            email: str(body.email),
            message: str(body.message),
            budget: str(body.budget),
            botcheck: str(body.botcheck),
            sourcePage: str(body.page),
            turnstileToken: str(body['cf-turnstile-response']),
        };
    }

    const form = await request.formData();
    return {
        name: str(form.get('name')),
        email: str(form.get('email')),
        message: str(form.get('message')),
        budget: str(form.get('budget')),
        botcheck: str(form.get('botcheck')),
        sourcePage: str(form.get('page')),
        turnstileToken: str(form.get('cf-turnstile-response')),
    };
}

const str = (value) => (typeof value === 'string' ? value : value == null ? '' : String(value));

function validate(fields) {
    if (!fields.name.trim()) return 'Please include your name.';
    if (!fields.email.trim()) return 'Please include an email address.';
    if (!fields.message.trim()) return 'Please describe what you need.';

    // Deliberately loose. The only address that matters is one that can be
    // replied to, and every stricter pattern rejects somebody's real address.
    if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(fields.email.trim())) {
        return 'That email address does not look right.';
    }

    for (const [field, limit] of Object.entries(MAX_FIELD)) {
        if ((fields[field] || '').length > limit) return `That ${field} is longer than this form accepts.`;
    }

    return null;
}

async function verifyTurnstile(token, request, env) {
    if (!token) return false;

    const body = new FormData();
    body.append('secret', env.TURNSTILE_SECRET_KEY);
    body.append('response', token);

    const ip = request.headers.get('CF-Connecting-IP');
    if (ip) body.append('remoteip', ip);

    const response = await fetch(ENDPOINTS.turnstile, { method: 'POST', body });
    const result = await response.json();

    if (!result.success) console.error('Turnstile rejected:', result['error-codes']);

    return result.success === true;
}

/* ── Persistence ───────────────────────────────────────────────────── */

async function store(enquiry, env) {
    await env.DB.prepare(
        `INSERT INTO enquiries (name, email, message, budget, received_at, country, user_agent, source_page)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
    )
        .bind(
            enquiry.name,
            enquiry.email,
            enquiry.message,
            enquiry.budget,
            enquiry.receivedAt,
            enquiry.country,
            enquiry.userAgent,
            enquiry.sourcePage,
        )
        .run();
}

/* ── Notifications ─────────────────────────────────────────────────── */

async function sendEmail(payload, env) {
    const response = await fetch(ENDPOINTS.resend, {
        method: 'POST',
        headers: {
            Authorization: `Bearer ${env.RESEND_API_KEY}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
    });

    if (!response.ok) {
        throw new Error(`Resend ${response.status}: ${await response.text()}`);
    }
}

function emailNotification(enquiry, env) {
    return sendEmail(
        {
            from: env.FROM_EMAIL,
            to: env.NOTIFY_TO,
            // reply_to is what makes the notification useful: hitting reply in
            // the inbox answers the person, not the robot that sent it.
            reply_to: enquiry.email,
            subject: `New enquiry from ${enquiry.name}${enquiry.budget ? ` — ${enquiry.budget}` : ''}`,
            text: [
                `From:    ${enquiry.name} <${enquiry.email}>`,
                `Budget:  ${enquiry.budget || 'not given'}`,
                `Country: ${enquiry.country || 'unknown'}`,
                `Page:    ${enquiry.sourcePage || 'unknown'}`,
                `Time:    ${enquiry.receivedAt}`,
                '',
                enquiry.message,
                '',
                '—',
                'Sent by the contact form on hesamrad.com. Reply to answer them directly.',
            ].join('\n'),
        },
        env,
    );
}

function autoReply(enquiry, env) {
    return sendEmail(
        {
            from: env.FROM_EMAIL,
            to: enquiry.email,
            reply_to: env.NOTIFY_TO,
            subject: 'Thanks — I got your message',
            // Plain text on purpose. A one-paragraph acknowledgment rendered
            // as a marketing template is how a real reply gets filed as spam.
            text: [
                `Hi ${enquiry.name.split(' ')[0] || 'there'},`,
                '',
                'Thanks for getting in touch. Your message reached me and I read everything',
                'myself — you will get a real reply, from me, usually within a day.',
                '',
                'If it turns out I am not the right person for what you need, I will say so',
                'and point you somewhere better.',
                '',
                'For reference, here is what you sent:',
                '',
                enquiry.message.split('\n').map((line) => `> ${line}`).join('\n'),
                '',
                'Hesam',
                'hesamrad.com',
            ].join('\n'),
        },
        env,
    );
}

async function telegramNotification(enquiry, env) {
    const text = [
        '*New enquiry — hesamrad.com*',
        '',
        `*From:* ${escapeMarkdown(enquiry.name)}`,
        `*Email:* ${escapeMarkdown(enquiry.email)}`,
        `*Budget:* ${escapeMarkdown(enquiry.budget || 'not given')}`,
        '',
        escapeMarkdown(enquiry.message.slice(0, 900)),
    ].join('\n');

    const response = await fetch(ENDPOINTS.telegram(env.TELEGRAM_BOT_TOKEN), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            chat_id: env.TELEGRAM_CHAT_ID,
            text,
            parse_mode: 'MarkdownV2',
            disable_web_page_preview: true,
        }),
    });

    if (!response.ok) {
        throw new Error(`Telegram ${response.status}: ${await response.text()}`);
    }
}

// MarkdownV2 rejects the whole message if any of these are unescaped, which
// would mean losing the notification over a full stop in someone's sentence.
const escapeMarkdown = (value) => value.replace(/[_*[\]()~`>#+\-=|{}.!\\]/g, (char) => `\\${char}`);

/* ── Responses ─────────────────────────────────────────────────────── */

function corsHeaders(origin, env) {
    const allowed = (env.ALLOWED_ORIGINS || '').split(',').map((o) => o.trim()).filter(Boolean);
    const headers = {
        'Access-Control-Allow-Methods': 'POST, OPTIONS',
        'Access-Control-Allow-Headers': 'Content-Type',
        'Access-Control-Max-Age': '86400',
        Vary: 'Origin',
    };

    // Echo the origin only when it is one of ours. A blanket `*` would let any
    // site on the internet post through this endpoint under its own name.
    if (origin && allowed.includes(origin)) {
        headers['Access-Control-Allow-Origin'] = origin;
    }

    return headers;
}

function respond(wantsJson, cors, status, message, env) {
    const site = (env && env.SITE_URL) || 'https://hesamrad.com';

    if (wantsJson) {
        return new Response(JSON.stringify({ success: status === 200, message }), {
            status,
            headers: { ...cors, 'Content-Type': 'application/json' },
        });
    }

    // The no-JavaScript path. A bare JSON body would be shown to the visitor
    // as raw text, so this answers in the only language a plain form submit
    // understands: a redirect on success, a readable page on failure.
    if (status === 200) {
        return new Response(null, {
            status: 303,
            headers: { ...cors, Location: `${site}/thank-you/` },
        });
    }

    return new Response(errorPage(message, site), {
        status,
        headers: { ...cors, 'Content-Type': 'text/html; charset=utf-8' },
    });
}

const errorPage = (message, site) => `<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>That did not send — Hesam Rad</title>
<style>
  body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 2rem;
         background: #fff; color: #1d1d1f; font: 400 17px/1.5 -apple-system, BlinkMacSystemFont, "Segoe UI", system-ui, sans-serif; }
  main { max-width: 34rem; }
  h1 { margin: 0 0 1rem; font-size: 2rem; font-weight: 600; letter-spacing: -0.02em; }
  p { margin: 0 0 1rem; color: #424245; }
  a { color: #0066cc; }
</style>
</head>
<body>
<main>
  <h1>That did not send.</h1>
  <p>${escapeHtml(message)}</p>
  <p>Email me directly at <a href="mailto:hesamrad.dev@gmail.com">hesamrad.dev@gmail.com</a> and I will pick it up there.</p>
  <p><a href="${site}/">Back to the site</a></p>
</main>
</body>
</html>`;

const escapeHtml = (value) =>
    value.replace(/[&<>"']/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[char]);
