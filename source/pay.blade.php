---
title: Pay
robots: noindex,nofollow
disableContact: true
---

@extends('_layouts.main')

{{--
    How a client pays an invoice.

    The page is noindex,nofollow, which also keeps it out of the sitemap. It is
    not in the nav and nothing on the site links to it. That makes it unlisted
    and not secret: anybody who has the URL can open it, and a URL travels
    through browser history, a forwarded email and a chat app's link preview.
    Nothing on this page is a secret, so unlisted is the right level. Do not add
    a Disallow line to robots.txt for it. That file is public, therefore the line
    would publish the address it is meant to hide.

    The address comes from `payment` in config.php, and build_qr.py reads the
    same key to draw the QR code. There is one copy of the address in this
    repository. Never type it into a template.

    The reader is a business owner and not an engineer. Two sentences on this
    page carry almost all of the risk, and both are about the network: money sent
    on a chain I cannot reach is gone, and no wording later undoes it. That is
    why the network is stated three times, in the card, in the steps and in the
    beginner section, and why it is the only thing on the page in bold.

    No contact form. `disableContact` is true because the block at the end of
    every other page asks what somebody wants to build, which is the wrong
    question for a person halfway through paying. The page closes with the email
    address instead.
--}}

@section('title', 'How to Pay Your Invoice in USDT')

@section('description', 'Pay your invoice in USDT on the Tron network. The address, a QR code, and step by step instructions whether or not you have used crypto before.')

@section('body')
    @php
        $payment = $page->payment;
        $qr = $page->baseUrl . '/assets/build/images/' . $payment['qr'];

        /*
         * Only the official site of each company, and never a direct download or
         * an app store ID.
         *
         * A fake wallet app is one of the most common ways people lose crypto,
         * and a page that links a download is a page that teaches a client to
         * trust a link. The official site carries the real store links for every
         * platform, keeps them current, and is a domain the reader can check
         * against a search of the company's name.
         *
         * These are examples and the page says so. Availability differs by
         * country, and a recommendation that turns out to be unavailable, or
         * unlawful, where the client lives is worse than no name at all. The
         * instruction that saves the money is the network, not the brand.
         */
        $apps = [
            ['name' => 'Coinbase', 'href' => 'https://www.coinbase.com', 'note' => 'US, Canada, UK, most of Europe'],
            ['name' => 'Kraken', 'href' => 'https://www.kraken.com', 'note' => 'US, Canada, UK, Europe'],
            ['name' => 'Binance', 'href' => 'https://www.binance.com', 'note' => 'Widely available outside the US'],
            ['name' => 'OKX', 'href' => 'https://www.okx.com', 'note' => 'Middle East and Asia'],
        ];

        /*
         * The two ends of the address, cut from the address itself.
         *
         * A reader cannot check 34 random characters against a wallet screen and
         * will not try. Four at each end is a check they will actually make, and
         * it is the check that catches a clipboard that pasted something else.
         *
         * Cut and not typed. A second copy of these eight characters is a second
         * thing that can disagree with the address above them.
         */
        $addressHead = substr($payment['address'], 0, 4);
        $addressTail = substr($payment['address'], -4);
    @endphp

    <div class="shell section page-head">
        <h1>How to pay your invoice.</h1>

        {{-- The reason comes first and takes one sentence. A business owner who
             has never been asked for crypto is deciding whether this is real,
             and a page that opens with instructions answers the wrong question.
             A paragraph of justification reads worse than a line, so keep it at
             a line.

             It does not name the two paths. The chooser under it does that, and
             a lead that describes the chooser is a paragraph the reader has to
             get through before they can use it. On a phone that paragraph was
             nine lines.

             The last sentence is the one that matters most to somebody who has
             never done this. It says a person is on the other end. --}}
        <p class="lead prose">Card and bank transfers don't reach me reliably, so I settle invoices in USDT. It lands
            in a few minutes and costs you about a dollar. None of this is automated, so if anything here looks wrong
            to you, call me before you send a thing.</p>

        {{-- The page holds two different jobs of unequal length, and the reader
             knows which one is theirs before they know anything else. Asking
             them first turns a long page into whichever half they needed.

             Each one states its length. A reader deciding between two paths is
             really deciding how much of their afternoon this takes, and a
             walkthrough that says "a day or two" up front does not feel like a
             bait once they are inside it.

             Plain links and not buttons. Both go to a place on this page, and a
             link is what the back button understands. --}}
        <div class="pay-choose">
            <a class="pay-tile" href="#already-hold">
                {{-- The arrow sits on the name row and not under the note. On
                     its own line it reads as a stray mark rather than as the
                     direction the link goes. --}}
                <span class="pay-tile__row">
                    <span class="pay-tile__name">I already hold USDT</span>
                    @include('_components.icon', ['name' => 'arrow-down'])
                </span>
                <span class="pay-tile__note">Four steps, about two minutes</span>
            </a>
            <a class="pay-tile" href="#never-used-crypto">
                <span class="pay-tile__row">
                    <span class="pay-tile__name">I've never used crypto</span>
                    @include('_components.icon', ['name' => 'arrow-down'])
                </span>
                <span class="pay-tile__note">Five steps, and allow a day or two</span>
            </a>
        </div>
    </div>

    <section class="shell section">
        <div class="section-head">
            <h2>Where to send it.</h2>
            <p class="dim">Scan the code, or copy the address.</p>
        </div>

        <div class="pay-card">
            {{-- The code is an <img> and not inlined SVG. It must stay on its
                 white plate in dark mode, and an inlined file invites a later
                 stylesheet to recolor it. A recolored QR code is an unreadable
                 one.

                 The alt text does not repeat the address. A screen reader
                 reading 34 random characters aloud helps nobody, and the address
                 is already on the page as text, where it can be copied. --}}
            {{-- The caption says what the code is for. A QR code with nothing
                 under it is obvious to anybody who has used one to pay and is a
                 decorated square to anybody who has not, which on this page is
                 the reader the caption is written for. --}}
            <figure class="pay-card__code">
                <img src="{{ $qr }}" width="220" height="220" alt="QR code for the payment address below.">
                <figcaption>Scan with your wallet app</figcaption>
            </figure>

            <div class="pay-card__detail">
                <dl class="rows">
                    <div class="row">
                        <dt class="row__key">Amount</dt>
                        <dd class="row__value">
                            <p>The amount on your invoice. Send it exactly, and don't add anything for fees.</p>
                        </dd>
                    </div>
                    <div class="row">
                        <dt class="row__key">Asset</dt>
                        <dd class="row__value">
                            <p>{{ $payment['asset'] }}</p>
                        </dd>
                    </div>
                    {{-- The one fact on the page that is bold. Every other way
                         of emphasising it, a colored panel or a warning icon,
                         reads as decoration on a page a person skims. --}}
                    <div class="row">
                        <dt class="row__key">Network</dt>
                        <dd class="row__value">
                            <p><strong>{{ $payment['network'] }}</strong>. Not Ethereum, not BNB, not anything else.</p>
                        </dd>
                    </div>
                    <div class="row">
                        <dt class="row__key">Address</dt>
                        <dd class="row__value">
                            {{-- `user-select: all` makes one click select the
                                 whole string, for a reader who would rather
                                 highlight and copy by hand than trust a button.
                                 The string stays unbroken: no spaces and no
                                 grouping, because a space that survives a manual
                                 copy is an address a wallet rejects. --}}
                            <p class="pay-address">{{ $payment['address'] }}</p>

                            @include('_components.copy-url-button', [
                                'copyText' => $payment['address'],
                                'copyLabel' => 'Copy address',
                                'copiedLabel' => 'Address copied',
                            ])

                            {{-- The check a reader will actually perform. Both
                                 ends are cut from the address above, so they
                                 cannot drift from it.

                                 It is under the button and not over it, because
                                 it is what to do after pasting and not before
                                 copying. --}}
                            <p class="pay-check">After you paste it, check that it starts
                                <b>{{ $addressHead }}</b> and ends <b>{{ $addressTail }}</b>.</p>
                        </dd>
                    </div>
                </dl>

                {{-- Two facts, at the foot of the card, in the order they bite.

                     The first is stated once and plainly. A client who believes
                     a payment can be reversed makes a careless mistake and then
                     brings it to me.

                     The second is about somebody else. A payment page is worth
                     impersonating: the attack is a message that looks like it
                     came from me, carrying a different address, timed to an
                     invoice the client is expecting. The defense costs a
                     sentence, and the sentence has to be here, because this page
                     is the thing the client can come back and check. --}}
                <div class="pay-card__final">
                    <p>A USDT transfer is final. Nobody can call it back, including me. Check the network and the
                        address before you confirm.</p>
                    <p>My address only ever appears on this page. If a message gives you a different one, it isn't
                        from me, whatever name is on it. Come back here, or call me.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- The id is on the section and not on the heading. The chooser above
         sends the reader to the whole block, and `scroll-padding-top` in
         base.css is what keeps the heading clear of the fixed header. --}}
    <section class="shell section" id="already-hold">
        <div class="section-head">
            <h2>If you already hold USDT.</h2>
            <p class="dim">Four steps, about two minutes.</p>
        </div>

        <ol class="steps">
            <li>
                <h3>Open your wallet or exchange and choose to send USDT</h3>
                <p>On an exchange this is called Withdraw rather than Send. Either one is the same thing here.</p>
            </li>
            <li>
                <h3>Pick the {{ $payment['network'] }} network</h3>
                <p>Your app asks which network before it asks for anything else, and it's the one choice on this page
                    that can't be undone. It may be written TRC20, Tron, or TRON (TRC20). Any of those is right.
                    Ethereum, ERC20, BEP20, BSC, Arbitrum, Polygon and Solana are all wrong, and USDT sent on one of
                    them doesn't reach me and can't be recovered by either of us.</p>
            </li>
            <li>
                <h3>Paste the address and enter the amount</h3>
                <p>Use the copy button above rather than typing it out. After pasting, check that what's on your
                    screen starts {{ $addressHead }} and ends {{ $addressTail }}. The amount is the figure on your
                    invoice, with nothing added.</p>
            </li>
            <li>
                <h3>Send it, then send me the transaction ID</h3>
                <p>Your app shows a transaction ID, sometimes called a hash or a TxID, as soon as the payment goes
                    out. Email it to me at <a href="mailto:{{ $page->email }}">{{ $page->email }}</a> and I'll confirm
                    receipt the same day. It's how I match the payment to your invoice, so don't skip it.</p>
            </li>
        </ol>
    </section>

    <section class="shell section" id="never-used-crypto">
        {{-- The slow part is named in the subhead and not in a paragraph under
             it. The section above is a centered head and then the steps, and one
             extra left-aligned paragraph under a centered head is the whole
             difference between the two sections looking like one page. --}}
        <div class="section-head">
            <h2>If you've never used crypto.</h2>
            <p class="dim">Five steps. Allow a couple of days the first time, because an exchange has to verify who
                you are before it will let you withdraw.</p>
        </div>

        {{-- The same shape as the section above: a lede, then `.steps`, at the
             same heading level. It was a <details> styled as an FAQ item, which
             put a rule, a chevron and a summary between the reader and the steps
             and made the two halves of the page look like two different kinds of
             thing.

             Nothing is hidden now. The chooser at the top of the page is what
             keeps this section out of the way of a reader who does not need it,
             and that is a better job than a toggle did: it routes them before
             they scroll rather than asking them to fold this away after they
             arrive.

             The step headings run at h3, level with the ones above, because
             these steps sit directly under the section's h2 as those do. --}}
        <ol class="steps">
            <li>
                <h3>Open an account with an exchange</h3>
                <p>An exchange is where you turn ordinary money into USDT. It works like opening an online bank
                    account: an email address, a password, then identity checks.</p>
            </li>
            <li>
                <h3>Verify your identity</h3>
                <p>Photo ID and usually a selfie. This is the slow part, anywhere from ten minutes to a couple of
                    days, and there's no way around it. Start it early if the invoice has a date on it.</p>
            </li>
            <li>
                <h3>Add money and buy USDT</h3>
                <p>A bank transfer or a debit card. Buy USDT specifically, sometimes listed as Tether. Buy a few
                    dollars more than the invoice, because the withdrawal in the next step has a small fee of its
                    own.</p>
            </li>
            <li>
                <h3>Withdraw the USDT to my address</h3>
                <p>Choose Withdraw, then USDT, then the <strong>{{ $payment['network'] }}</strong> network. Paste my
                    address, enter the amount on your invoice, and confirm. Your exchange takes around a dollar for
                    the withdrawal and that's mine to absorb, so there's nothing to add on top.</p>
            </li>
            <li>
                <h3>Send me the transaction ID</h3>
                <p>Same as above. Email it to <a href="mailto:{{ $page->email }}">{{ $page->email }}</a> and I'll
                    confirm. That's the whole thing done. Every payment after this one takes two minutes, because the
                    slow part was the account and you only open it once.</p>
            </li>
        </ol>

        {{-- Indented to the text column of the steps above. A step's words start
             clear of the numbered rail, and a heading that follows the list from
             the edge of the section reads as belonging to something else. --}}
        <div class="pay-after-steps">
            <h3 class="pay-apps__head">Which exchange?</h3>

            <p class="prose">Whichever one operates where you live. These four are the ones most people end up with,
                and all four can send USDT over {{ $payment['network'] }}. I'm naming them as examples and not
                recommending one: availability and the rules differ by country, so check what's offered in yours.
                Each link goes to the company's own site, where the real app downloads are.</p>

            {{-- The same tile as the chooser at the top of the page. As a list
                 of underlined links these four read as a footnote, which is the
                 wrong weight for the one decision in this section that sends the
                 reader off the site.

                 Every one leaves the site, therefore every one gets target and
                 rel. The icon carries `iconTitle`, so the link says where it
                 goes to a reader who cannot see the mark. Search the company's
                 name and compare the domain if in doubt: a fake wallet or
                 exchange app is one of the few ways to lose money that no amount
                 of care with the network protects against. --}}
            <ul class="pay-apps">
                @foreach ($apps as $app)
                    <li>
                        <a class="pay-tile" href="{{ $app['href'] }}" target="_blank" rel="noopener noreferrer">
                            <span class="pay-tile__row">
                                <span class="pay-tile__name">{{ $app['name'] }}</span>
                                @include('_components.icon', [
                                    'name' => 'external',
                                    'iconTitle' => 'Opens in a new tab',
                                ])
                            </span>
                            <span class="pay-tile__note">{{ $app['note'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- The id is here so I can send a client straight to this block. A person
         who thinks they have sent money to the wrong place does not want to
         arrive at the top of a page about how to send money. --}}
    <section class="shell section" id="something-went-wrong">
        <div class="callout">
            <h2>Something went wrong.</h2>

            <p>Payments on this network arrive in under a minute and almost never fail. If yours hasn't shown up, or
                you think you sent it on the wrong network, or you sent the wrong amount, tell me straight away at
                <a href="mailto:{{ $page->email }}">{{ $page->email }}</a>. Bring the transaction ID. Some mistakes
                can be sorted out and some can't, and I'll tell you honestly which one you're looking at.</p>

            {{-- A client can check their own payment without waiting on me,
                 which is worth more at that moment than any reassurance I could
                 write. The explorer belongs to Tronscan and opens in a new tab.
                 The URL wants the transaction ID pasted on the end, so the link
                 goes to the explorer's front page and the reader searches
                 there. --}}
            <p>You can also check it yourself. Paste your transaction ID into
                <a href="https://tronscan.org" target="_blank" rel="noopener noreferrer">Tronscan</a> and it will
                show you whether the transfer completed and where it went.</p>

            <div class="btn-row">
                <a class="btn btn--primary" href="mailto:{{ $page->email }}">
                    @include('_components.icon', ['name' => 'mail', 'class' => 'btn__icon'])
                    <span>Email me</span>
                </a>
                <a class="btn btn--ghost" href="{{ $page->bookingUrl }}" target="_blank" rel="noopener noreferrer">
                    <span>Book a call instead</span>
                </a>
            </div>
        </div>
    </section>
@endsection
