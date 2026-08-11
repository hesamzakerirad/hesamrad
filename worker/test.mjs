/**
 * Request-path tests for the contact Worker.
 *
 * D1, Turnstile, Resend and Telegram are all mocked, so this runs in plain
 * Node with no network, no credentials and nothing deployed:
 *
 *     npm test
 *
 * It exists because the failure modes that matter here are the quiet ones — a
 * honeypot that answers 403 and teaches the bot to try again, a validation
 * path that stores the row anyway, a CORS header that echoes any origin. None
 * of those are visible from looking at the form.
 */
import worker from './src/index.js';

const ORIGIN = 'https://hesamrad.com';

let calls;
const realFetch = globalThis.fetch;

function mockNetwork({ turnstileOk = true } = {}) {
    calls = { turnstile: 0, resend: [], telegram: 0, d1: [] };
    globalThis.fetch = async (url, init) => {
        const href = typeof url === 'string' ? url : url.url;
        if (href.includes('challenges.cloudflare.com')) {
            calls.turnstile++;
            return new Response(JSON.stringify({ success: turnstileOk, 'error-codes': [] }), { status: 200 });
        }
        if (href.includes('api.resend.com')) {
            calls.resend.push(JSON.parse(init.body));
            return new Response('{"id":"x"}', { status: 200 });
        }
        if (href.includes('api.telegram.org')) {
            calls.telegram++;
            return new Response('{"ok":true}', { status: 200 });
        }
        throw new Error('unexpected fetch to ' + href);
    };
}

const env = {
    TURNSTILE_SECRET_KEY: 'sec',
    RESEND_API_KEY: 'rk',
    TELEGRAM_BOT_TOKEN: 'tok',
    TELEGRAM_CHAT_ID: '123',
    NOTIFY_TO: 'hesamrad.dev@gmail.com',
    FROM_EMAIL: 'Hesam Rad <noreply@hesamrad.com>',
    ALLOWED_ORIGINS: 'https://hesamrad.com,https://www.hesamrad.com,',
    DB: {
        prepare(sql) {
            return { bind: (...args) => ({ run: async () => { calls.d1.push({ sql, args }); return {}; } }) };
        },
    },
};

const pending = [];
const ctx = { waitUntil: (p) => pending.push(p) };

const jsonReq = (body, origin = ORIGIN) =>
    new Request('https://hello.hesamrad.com', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', Origin: origin },
        body: JSON.stringify(body),
    });

const formReq = (fields) => {
    const body = new URLSearchParams(fields);
    return new Request('https://hello.hesamrad.com', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', Origin: ORIGIN },
        body,
    });
};

const good = {
    name: 'Jane Smith',
    email: 'jane@example.com',
    message: 'We need a booking system. Currently on spreadsheets.',
    budget: '$5,000 – $15,000',
    page: 'https://hesamrad.com/zero-to-one/',
    'cf-turnstile-response': 'token',
};

let failures = 0;
const check = (label, cond, extra = '') => {
    console.log(`${cond ? '  ✓' : '  ✗'} ${label}${cond ? '' : '  ← ' + extra}`);
    if (!cond) failures++;
};

async function run() {
    // ── preflight ──
    mockNetwork();
    let res = await worker.fetch(
        new Request('https://hello.hesamrad.com', { method: 'OPTIONS', headers: { Origin: ORIGIN } }),
        env, ctx,
    );
    console.log('\nCORS preflight');
    check('204', res.status === 204, res.status);
    check('echoes our origin', res.headers.get('Access-Control-Allow-Origin') === ORIGIN);
    check('allows POST', (res.headers.get('Access-Control-Allow-Methods') || '').includes('POST'));

    // ── foreign origin ──
    mockNetwork();
    res = await worker.fetch(jsonReq(good, 'https://evil.example'), env, ctx);
    console.log('\nForeign origin');
    check('no ACAO header returned', res.headers.get('Access-Control-Allow-Origin') === null);

    // ── happy path, JSON ──
    mockNetwork();
    res = await worker.fetch(jsonReq(good), env, ctx);
    let body = await res.json();
    await Promise.all(pending.splice(0));
    console.log('\nHappy path (JSON)');
    check('200', res.status === 200, res.status);
    check('success true', body.success === true);
    check('stored in D1 exactly once', calls.d1.length === 1, calls.d1.length);
    check('turnstile verified', calls.turnstile === 1);
    check('two emails (notify + auto-reply)', calls.resend.length === 2, calls.resend.length);
    check('telegram pushed', calls.telegram === 1);
    const notify = calls.resend.find((e) => e.to === env.NOTIFY_TO);
    const reply = calls.resend.find((e) => e.to === good.email);
    check('notification reply_to is the sender', notify?.reply_to === good.email, notify?.reply_to);
    check('auto-reply reply_to is Hesam', reply?.reply_to === env.NOTIFY_TO, reply?.reply_to);
    check('notification subject carries budget', (notify?.subject || '').includes('$5,000'), notify?.subject);
    check('D1 row has the message', calls.d1[0].args.includes(good.message));
    check('D1 row records the source page', calls.d1[0].args.includes(good.page), calls.d1[0].args);
    check('notification names the source page', (notify?.text || '').includes(good.page), notify?.text?.slice(0, 80));

    // ── oversized source page ──
    mockNetwork();
    res = await worker.fetch(jsonReq({ ...good, page: 'https://hesamrad.com/' + 'x'.repeat(900) }), env, ctx);
    await Promise.all(pending.splice(0));
    console.log('\nOversized source page');
    check('still accepted', res.status === 200, res.status);
    const stored = calls.d1[0]?.args.find((a) => typeof a === 'string' && a.startsWith('https://hesamrad.com/x'));
    check('truncated to 500 chars', stored?.length === 500, stored?.length);

    // ── honeypot ──
    mockNetwork();
    res = await worker.fetch(jsonReq({ ...good, botcheck: 'on' }), env, ctx);
    body = await res.json();
    console.log('\nHoneypot tripped');
    check('answers 200 (does not tip off the bot)', res.status === 200);
    check('nothing stored', calls.d1.length === 0, calls.d1.length);
    check('nothing sent', calls.resend.length === 0 && calls.telegram === 0);

    // ── validation ──
    mockNetwork();
    res = await worker.fetch(jsonReq({ ...good, email: 'not-an-email' }), env, ctx);
    body = await res.json();
    console.log('\nBad email');
    check('400', res.status === 400, res.status);
    check('explains why', /email/i.test(body.message), body.message);
    check('turnstile not even consulted', calls.turnstile === 0);
    check('nothing stored', calls.d1.length === 0);

    mockNetwork();
    res = await worker.fetch(jsonReq({ ...good, message: 'x'.repeat(6000) }), env, ctx);
    body = await res.json();
    console.log('\nOversized message');
    check('400', res.status === 400, res.status);
    check('nothing stored', calls.d1.length === 0);

    // ── turnstile failure ──
    mockNetwork({ turnstileOk: false });
    res = await worker.fetch(jsonReq(good), env, ctx);
    body = await res.json();
    console.log('\nTurnstile rejects');
    check('403', res.status === 403, res.status);
    check('nothing stored', calls.d1.length === 0);
    check('nothing sent', calls.resend.length === 0);

    // ── missing token ──
    mockNetwork();
    const { 'cf-turnstile-response': _drop, ...noToken } = good;
    res = await worker.fetch(jsonReq(noToken), env, ctx);
    console.log('\nMissing Turnstile token');
    check('403', res.status === 403, res.status);
    check('siteverify not called with an empty token', calls.turnstile === 0);

    // ── no-JS form post ──
    mockNetwork();
    res = await worker.fetch(formReq(good), env, ctx);
    await Promise.all(pending.splice(0));
    console.log('\nNo-JS form post');
    check('303 redirect', res.status === 303, res.status);
    check('to /thank-you/', res.headers.get('Location') === 'https://hesamrad.com/thank-you/', res.headers.get('Location'));
    check('still stored', calls.d1.length === 1);

    mockNetwork({ turnstileOk: false });
    res = await worker.fetch(formReq(good), env, ctx);
    const html = await res.text();
    console.log('\nNo-JS form post, bot check fails');
    check('403', res.status === 403, res.status);
    check('returns HTML, not JSON', (res.headers.get('Content-Type') || '').includes('text/html'));
    check('offers the email address', html.includes('hesamrad.dev@gmail.com'));

    // ── D1 down ──
    mockNetwork();
    const brokenEnv = { ...env, DB: { prepare() { throw new Error('D1 unavailable'); } } };
    res = await worker.fetch(jsonReq(good), brokenEnv, ctx);
    body = await res.json();
    console.log('\nD1 unavailable');
    check('500 — the one failure worth surfacing', res.status === 500, res.status);
    check('success false', body.success === false);
    check('points at the email address', /hesamrad\.dev@gmail\.com/.test(body.message), body.message);

    // ── markdown escaping ──
    mockNetwork();
    res = await worker.fetch(jsonReq({ ...good, name: 'A. B_C [x]', message: 'Cost: $5. Really!' }), env, ctx);
    await Promise.all(pending.splice(0));
    console.log('\nTelegram MarkdownV2 escaping');
    check('telegram still sent with reserved chars present', calls.telegram === 1);

    // ── wrong method ──
    mockNetwork();
    res = await worker.fetch(new Request('https://hello.hesamrad.com', { method: 'GET', headers: { Origin: ORIGIN } }), env, ctx);
    console.log('\nGET');
    check('405', res.status === 405, res.status);

    globalThis.fetch = realFetch;
    console.log(failures === 0 ? '\nALL PASS' : `\n${failures} FAILURE(S)`);
    process.exit(failures === 0 ? 0 : 1);
}

run();
