---
extends: _layouts.case-study
section: content
title: 'Peyvand: Persian Link Shortener, QR Codes, and More'
description: 'ow I built Peyvand (pvnd.io), a free Persian link shortener with QR codes, click statistics, and a public API. Built solo since 2025.'
contactHeading: 'Got something like this?'
contactIntro: "Describe it in a paragraph. You will get an honest answer about whether I am the right person for it, including the times when I am not."
published: true
sample: false
kind: 'web-application'
client: 'My Own Product'
clientUrl: 
liveUrl: 'https://pvnd.io'
sector: 'Internet tools, nonprofit'
year: '2025 to Present'
# The sort key for /work/. An integer, never rendered.
launchYear: 2025
duration: 'Active development since 2025'
summary: 'Shortening a link, printing a QR code, and seeing who clicked should be quick and free. Peyvand is a Persian link shortener that does all three, with timed links and a public API for people who need more. Each address is checked for safety before its link exists. I built and run it alone as a nonprofit, and I’m still improving it.'
role: 'Everything. The product decisions, the design, the back end, the front end, and the infrastructure. There is nobody else.'
problem: 'Link shorteners, QR code generators, and personal page builders already exist, so the idea isn’t new. What’s missing is a Persian-first option that feels well made. The existing Persian services do useful work, and I wanted to see how much better the experience could get. Many Persian speakers live under heavy digital sanctions and can’t use the international tools everyone else relies on. Peyvand puts a link shortener, a QR code generator, and a personal page host in one place, built in Persian from the start.'
constraints:
    - "Nonprofit, with no ads. I didn't want to make money from ads, and I didn't want the project to be fully commercial either. I wanted the core features to be free for everyone, even people outside of Iran. It took me a while to find a business model that could keep the project going for the long run."
    - 'Persian first. Right-to-left text makes UI design harder, and Persian designers know it well. Most English UI designs fall apart when applied to Persian, so I designed every detail myself. It took the most time of anything on the project.'
    - "It is one person's project. Every feature is something I have to build, secure, monitor, and answer questions about, so each one has to earn its place."
built:
    - 'An ad-free link shortener you can use in less than a minute.'
    - 'An auto-updated, multi-layer safety check on every link before it is made, so users can trust that a short link is not meant to hurt them.'
    - 'Dynamic QR codes that are ready to be printed for good and can be updated whenever needed without breaking anything.'
    - 'Click statistics for each link, QR code, and profile, so users know exactly how each one is performing.'
    - 'Timed links that only work inside a window the owner sets, for campaigns that start and end on a date.'
    - 'A public API, so another product can create links with a single request.'
    - 'A one-page link-in-bio profile called Yekvand (it means "one link": yek is one, and Peyvand means link), the single address a person or business can use anywhere.'
decisions:
    - choice: 'Ad-Free From Day One'
      why: 'A product like this is not meant to make a lot of money. Most similar services I looked at chose ads to pay for the service. I did not want flashing banners and blinking pictures in front of my users.'
      cost: 'I give up one possible stream of revenue to create a better experience, and I accept that happily.'
    - choice: 'A Built-In API'
      why: 'A link shortener is most useful when it lives inside the tools people already use. So I made the whole service available through a single request. Other products can create links the same way my own dashboard does, and both follow one set of rules.'
      cost: 'Once people build on a public API, I have to keep it stable. That slows down changes that would be easy on a private one.'
    - choice: 'A Bespoke Look and Feel'
      why: 'Most products out there try to be minimalist, and I wanted a new feeling to set Peyvand apart. Over time, products start to look the same and lose their own character. So I studied different design approaches, and none of them fit what I wanted. I had to create it myself. To cut the story short, I mixed Neo-brutalism and Bauhaus to get the vibrant, clean, and nostalgic feeling you see on the product today.'
      cost: 'Some users might dislike it, but I find its character appealing.'
    - choice: 'Safety First'
      why: 'A short link hides where it leads, and that is what makes shorteners attractive to people with bad intentions. One check is easy to get around, so I look at every address in several different ways before its link exists. Refusing a harmful destination right when the link is created protects the people who click, and it protects every other link on the domain.'
      cost: 'Extra checks add a little time and cost to making a link. Keeping the checks updated and maintained also adds complexity to a simple function.'
timeline:
    - phase: '2025'
      detail: 'The core: shorten a link, redirect it quickly, and show a click count.'
    - phase: 'Since then'
      detail: 'Dynamic QR codes, the detailed statistics dashboard, timed links, the public API and its documentation, and plans for anyone who needs more than the free tier.'
    - phase: 'Next'
      detail: 'Yekvand, the link-in-bio page, and a roadmap that I publish on the site as it moves, and probably an SDK and a browser extension.'
results:
    - figure: '300,000+'
      caption: 'harmful websites blocked from being shortened, on a list that updates itself'
    - figure: 'Zero'
      caption: 'services outside Iran that it depends on, so it keeps working during an internet shutdown'
    - figure: 'Free'
      caption: 'for everyone, with tiers for people who use it more'
    - figure: 'Every'
      caption: 'address checked for safety before its link exists'
review:
    quote: ''
    name: ''
    role: ''
    relationship: ''
    url: null
    date: ''
differently:
cover:
    src: /assets/build/images/peyvand-internet-assistant.jpg
    alt: 'A Yekvand or a link-in-bio page from Peyvand'
    caption: 'Yekvand means "one link": a single address that leads to everything else.'
    credit: ''
gallery: []
stack:

---
