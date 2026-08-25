---
extends: _layouts.case-study
section: content
title: 'From Static to Dynamic: The Living Menu'
description: 'Top Menu: a digital menu and ordering platform for cafés and restaurants, built and run over five years as lead engineer.'
contactHeading: 'Got something like this?'
contactIntro: "Describe it in a paragraph. You will get an honest answer about whether I am the right person for it, including the times when I am not."
published: true
sample: false
client: 'Mahdi Rasaei'
clientUrl: 'https://topmenumarket.com'
sector: 'Hospitality software'
year: '2020–2025'
duration: 'Five years, as lead engineer'
summary: 'Cafés and restaurants kept their menus on paper, so changing a price meant reprinting everything and living with whatever was on the table until the next print run. Top Menu put the menu behind a QR code on the table, and behind that, a system that made everything work flawlessly.'
role: 'Lead engineer. The client already had a designer on the front end, so the split was straightforward: the back end, the API architecture, the infrastructure, the deployments and the reliability work were mine, along with mentoring the client''s own developers so the product could carry on without me. On a project without that cover I''d have built the front end too. Here it would have meant duplicating somebody who was already doing it well.'
problem: 'A printed menu is fixed the moment it leaves the printer. A price rises, a supplier stops delivering, a dish sells out, and the menu on the table still says otherwise until somebody reprints the lot or goes around correcting prices by hand, which looks exactly as bad as it sounds. For a café changing things weekly that''s a standing cost, a standing source of small embarrassments with customers, and a quantity of wasted paper nobody felt good about. The alternative most venues had was a PDF on a phone, which solves the printing and none of the rest.'
constraints:
    - 'Three audiences, one system. A diner wants a menu that opens instantly on a phone with bad signal. Staff want a till-side tool that never gets in the way at a rush. The client wanted to run hundreds of venues from above. Each would have been a reasonable product on its own.'
    - 'No two venues wanted the same thing. Some wanted online ordering and payment; some wanted the menu and nothing else, and were adamant about it. A single fixed feature set would have lost half the market either way.'
    - 'The client isn''t technical, and running a platform of this size can''t depend on a developer being reachable. Anything routine had to be something he could do himself.'
    - 'It was live and earning from early on. Five years of change had to land underneath a product that venues were using during service.'
built:
    - 'A menu a diner opens by scanning the QR code on their table. No app to install, no account to make, and fast enough to be usable on a phone on café wifi.'
    - 'Ordering and payment on the same screen, so a table can order without waiting to catch someone''s eye. Switched on per venue, because plenty of them didn''t want it.'
    - 'A dashboard each business runs itself: menu and prices, staff accounts with access levels so a floor employee sees less than a manager, and reports on income, sales and walk-ins.'
    - 'A platform dashboard above all of it, so the client can onboard a venue, change what it is allowed to use, and see across the whole estate without opening a terminal or asking me.'
    - 'The infrastructure, deployment and monitoring underneath: the part nobody sees until the menus stop loading during a Friday dinner service.'
    - 'Documentation and mentoring for the client''s own developers, so the product had more than one person who understood it.'
decisions:
    - choice: 'The web, not a native app'
      why: 'Nobody installs an app to read a menu they''ll look at for four minutes. A QR code that opens a page is the only version a diner will use, and it removed the app stores from the release path entirely.'
      cost: 'Gave up push notifications and native payment sheets, both of which came up more than once.'
    - choice: 'Features switched on per venue, rather than one product for everyone'
      why: 'A restaurant taking online orders and a café that just wants its menu readable are different businesses with different fears. Letting each turn things on kept both, instead of building for the average of the two and suiting neither of them.'
      cost: 'Every feature has to work with the others absent, which makes the system harder to reason about and considerably harder to test.'
    - choice: 'A platform dashboard the client operates himself'
      why: 'A platform where the owner needs a developer to onboard a customer has a bottleneck built into it, and the bottleneck was me. Building the boring administrative screens meant he could run the business at his own pace.'
      cost: 'Months of work that produced nothing a diner would ever see, argued for on the grounds that it would stop mattering who I was.'
    - choice: 'Mentoring their developers instead of staying the only one who knew it'
      why: 'Five years is long enough that being irreplaceable stops being an asset to the client and becomes a risk to them. Bringing their people up on the codebase was how the engagement was supposed to end.'
      cost: 'Slower delivery while it was happening, and it deliberately made me easier to replace.'
timeline:
    - phase: 'First months'
      detail: 'The diner-facing menu and the QR flow: the piece that had to be right before anything else was worth building.'
    - phase: 'Year one'
      detail: 'The business dashboard: menu management, staff accounts and access levels, and the first reports.'
    - phase: 'Years two and three'
      detail: 'Ordering and payment, per-venue feature control, and the platform dashboard that took day-to-day administration off me.'
    - phase: 'Years four and five'
      detail: 'Scale and handover. Infrastructure and reliability work as the estate grew, then documentation and mentoring so the client''s developers could take it forward.'
sections:
    - heading: 'Holding up as it grew'
      intro: 'Five hundred venues is a different problem from five, and most of what changed in the last two years was invisible to anyone using it. Two pieces of work carried the weight.'
      rows:
          - key: 'Images off the app server'
            value: 'A menu carries at least a hundred photos, and every photo is its own request. Add the twenty or so requests for everything that isn''t a picture, and one diner reading a menu through to an order asks the server about a hundred and twenty times. Diners read the whole menu, so that''s the normal session, not the worst one. I moved the photos onto a separate server I set up and configured myself, which took the machine running the application from a hundred and twenty requests a session to twenty. That is 83% fewer requests landing on the part of the system that can''t afford to be busy at seven on a Friday. The image server then got HTTP/2 and proper caching, so the photos arrive together over one connection instead of queueing up, and a phone that has seen the menu before stops asking for most of them at all.'
          - key: 'Deploying without me'
            value: 'Releasing a change used to mean me at a keyboard for about ten minutes, running the same steps in the same order, and nobody else could do it. The pipeline I built does it in one minute and doesn''t care where I am. The point was never the nine minutes. It was that a fix to a live problem stopped waiting on one person being free.'
results:
    - figure: '83%'
      caption: 'fewer requests on the main server'
    - figure: '~1 min'
      caption: 'to release a change, from ten'
achievements:
    heading: 'What it added up to'
    stats:
        - figure: '500+'
          caption: 'businesses running on it'
        - figure: '48,000'
          caption: 'sheets of paper not printed each year'
    note: 'The paper figure is arithmetic rather than a measurement, so here it is. A venue keeps about ten menus, each one two sheets, and reprints them roughly every two months as prices move, which comes to 120 sheets a year. Around a fifth of venues kept a printed copy going anyway, so the saving covers the other four hundred: near enough 48,000 sheets a year. That is a figure for one year, not a total, and it goes up every time another venue signs up. Venues are still signing up.'
review:
    quote: 'After several disappointing experiences with other agencies, I met Hesam in another attempt to work on my idea. He designed the entire system architecture from the ground up and built a robust, scalable backend that powered all our front-end applications seamlessly. After five years of hard work, he turned my concept into the city’s #1 best-selling menu application.'
    name: 'Mahdi Rasaei'
    role: 'Founder, Top Menu'
    # A year, a YYYY-MM, or a YYYY-MM-DD. The value goes in the `datetime`
    # attribute, therefore it must stay in one of those three shapes.
    date: '2025'
differently: 'I built the venue dashboard before the platform dashboard, which meant that for the first couple of years every new venue came through me. It felt like the right order, since it was the thing customers touch first, but it made me the bottleneck in someone else''s growth and I stayed that way longer than I should have. The administrative screens were the least interesting work in the project and would have been worth doing a year earlier than I did them.'
cover:
    src: /assets/build/images/top-menu.jpg
    alt: 'printed menu boards mounted on the wall above a café counter'
    caption: 'What a diner gets: scan the code on the table, and the menu opens. No app, no account.'
    credit: 'https://unsplash.com/photos/black-and-brown-menu-display-5AMSZcgN_cM'
gallery:
    # - src: null
    #   ratio: mobile
    #   alt: 'the menu on a phone'
    #   caption: 'The menu as customers see it, which is on a phone, on café wifi, almost always.'
stack:
    - Laravel
    - PHP
    - MySQL
    - Redis
    - Docker
    - CI/CD
---
