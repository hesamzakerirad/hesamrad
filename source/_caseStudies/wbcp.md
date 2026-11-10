---
extends: _layouts.case-study
section: content
title: 'WBCP: Free reCAPTCHA Alternative for Iranian Websites'
description: 'WBCP is a free Iranian CAPTCHA that works like Google reCAPTCHA v2. Switch in 1 minute and keep it working when the global internet is down.'
contactHeading: 'Got something like this?'
contactIntro: "Describe it in a paragraph. You'll get a straight answer about whether I'm the right person for it, including the times when I'm not."
published: true
sample: false
kind: 'web-application'
client: 'My Own Product'
clientUrl: 
liveUrl: 'https://wbcp.ir'
sector: 'Internet tools, security'
year: '2026 to Present'
# The sort key for /work/. An integer, never rendered.
launchYear: 2026
duration: 'Active development since 2026'
summary: 'WBCP is a free Iranian CAPTCHA and a drop-in alternative to Google reCAPTCHA v2. It uses the same code, so a website can switch in about a minute. It runs on a server inside Iran and calls no outside service, so it keeps working when the global internet goes down. There are no ads, and the widget uses no cookies and no tracking. I built it and run it alone.'
role: 'Everything. The product decisions, the design, the back end, the front end, and the servers. There is nobody else.'
problem: 'Most websites use [Google reCAPTCHA](https://www.google.com/recaptcha/about/) to block spam bots on their forms. It needs a connection to Google on every visit. When Iran is cut off from the global internet, reCAPTCHA stops working. Real visitors can''t log in, sign up, or send a message, and the site loses customers. The owner has two bad choices. Turn the check off and get flooded with spam, or leave it on and lock people out. WBCP is a third choice. It runs on a server inside Iran, so it works during an outage. It also uses the same code as reCAPTCHA, so there''s nothing new to learn.'
constraints:
    - "Free, with no ads. A bot check sits on other people's forms, so it can't show ads. Every site needs one, big or small, so I didn't want to charge for it either. That means I have to keep my costs low."
    - "No outside services. If WBCP needed anything outside Iran, it would fail in the exact moment it's needed. The widget and the answer check talk only to WBCP's own server."
    - "Easy to switch to. Nobody wants to rewrite their forms. WBCP keeps the same script tag, the same HTML, and the same server check as [reCAPTCHA v2](https://www.google.com/recaptcha/about/). To move over, you change two addresses and your keys."
    - "Respect for visitors. The widget sets no cookies and builds no profile of anyone. To slow down mass requests, I keep a one-way scrambled form of the IP address for about an hour, and nothing else."
    - "A clear limit. WBCP covers reCAPTCHA v2, both the checkbox and the invisible mode. It doesn't support reCAPTCHA v3, the Enterprise version, or the image challenge. A site that uses those can't move over."
    - "One person runs it. I have to build, secure, and support every feature, so each one has to be worth it."
built:
    - 'An "I''m not a robot" checkbox. You add it with one script tag and one piece of HTML, the same ones you use for reCAPTCHA today.'
    - 'A check that runs in the visitor''s browser. It does a short calculation, so nobody has to pick pictures out of a puzzle.'
    - 'A server check in the reCAPTCHA format (/recaptcha/api/siteverify). You keep the same names too: g-recaptcha, data-sitekey, data-callback, and g-recaptcha-response.'
    - 'A dashboard where site owners sign in, add their sites, and get two keys: a site key for the page and a secret key for the server.'
    - 'A migration guide in four steps, with server code in PHP, Node.js, Python, C#, Go, and Java, and examples for Laravel, Django, and Node.js.'
decisions:
    - choice: 'A Drop-In Replacement for Google reCAPTCHA'
      why: 'Site owners already know reCAPTCHA, and their code already uses it. So WBCP keeps the same addresses and the same API. Get a key, change the script address, change the check address. That takes about a minute. If it took longer, people would put it off, and their site would still break on the next outage.'
      cost: 'I follow a design I didn''t pick, and I only cover the v2 part of it.'
    - choice: 'A Calculation Instead of a Puzzle'
      why: 'The visitor''s browser does a short calculation, and the server checks the result. Nobody has to pick pictures out of a grid. It needs no images and no outside service, so it still works in an outage.'
      cost: 'I don''t keep visitor profiles, so I can''t use them to spot bots. I limit mass requests instead.'
    - choice: 'No Cookies and No Tracking in the Widget'
      why: 'The widget sets no cookie, builds no profile, and only talks to WBCP''s server. Site owners can add it without worrying about their visitors'' privacy.'
      cost: 'This covers the widget on your site. The pages of wbcp.ir use Google Tag Manager for their own statistics, and the site says so openly.'
    - choice: 'Free for Everyone'
      why: 'Spam protection is basic for any website. A small shop should get it as easily as a big company. There is no limit on how many checks a site makes.'
      cost: 'It earns nothing today, so every hour I spend on it is my own time.'
comparison:
    heading: 'WBCP Compared With Google reCAPTCHA'
    intro: 'A check mark means the feature is there. An X means it is not. Both use the same code, so the differences show up when the internet goes down.'
    columns: ['Feature', 'Google reCAPTCHA', 'WBCP']
    rows:
        - ['Independence from Google''s servers', 'No', 'Yes']
        - ['Availability during global internet outages', 'No', 'Yes']
        - ['Hosting inside Iran', 'No', 'Yes']
        - ['Cookie-free widget', 'No', 'Yes']
        - ['Puzzle-free experience for visitors', 'No', 'Yes']
        - ['Same script tag and HTML', 'Yes', 'Yes']
        - ['Same siteverify call and g-recaptcha-response', 'Yes', 'Yes']
        - ['Checkbox mode (v2)', 'Yes', 'Yes']
        - ['Invisible mode (v2)', 'Yes', 'Yes']
        - ['Image challenge mode', 'Yes', 'No']
        - ['reCAPTCHA v3 (score)', 'Yes', 'No']
        - ['Enterprise version', 'Yes', 'No']
timeline:
    - phase: '2026'
      detail: 'The core: the checkbox, the browser check, and the server check.'
    - phase: 'Since then'
      detail: 'The site owner dashboard, the migration guide with its code examples, the guide for outages, and the privacy page.'
results:
    - figure: '1 minute'
      caption: 'to move a site from Google reCAPTCHA v2: new keys and two changed addresses'
    - figure: 'Zero'
      caption: 'outside services called by the widget or the answer check, so it keeps working when the global internet is down'
    - figure: 'Free'
      caption: 'with no ads and no limit on checks'
    - figure: 'No'
      caption: 'cookies or tracking from the widget on your site'
review:
    quote: ''
    name: ''
    role: ''
    relationship: ''
    url: null
    date: ''
differently:
cover:
    src: /assets/build/images/wbcp.jpg
    alt: 'The WBCP checkbox widget with the label I am not a robot'
    caption: 'The WBCP captcha widget'
    credit: ''
gallery: []
stack:

---
