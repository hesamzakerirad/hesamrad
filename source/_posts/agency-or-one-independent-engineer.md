---
extends: _layouts.post
section: content
title: 'Agency or One Independent Engineer: The Honest Comparison'
description: 'The comparison people make is cost. The one that decides the outcome is risk, and an agency''s risks are the opposite of an independent engineer''s.'
tags:
robots:
    - index
    - follow
created_at: 2026-08-12
updated_at: 2026-08-12
readTime: 8
isFeatured: true
isPublished: true
ctaTitle: 'Not sure which one you need?'
ctaBody: 'Describe the project in a paragraph and I''ll tell you which way to go, including the times the answer is an agency, or a developer you hire outright. No pitch attached.'
ctaAction: 'Tell me about it'
ctaSecondary: '/work/'
ctaSecondaryLabel: 'See what I have built'
faq:
    - q: 'Who writes the code, and will I meet them?'
      a: 'If the people in the pitch aren''t the people on the work, ask who is. At an agency the developer assigned to you can change between projects; with one engineer the person you meet is the person who writes it.'
    - q: 'What do I own on the last day?'
      a: 'Domain, repository, hosting accounts, analytics, everything. Get the answer in writing before money moves.'
    - q: 'What happens when the person who knows this leaves?'
      a: 'Both models have an answer. An agency absorbs it and someone else picks the work up. An independent engineer has to have handed over documentation and access as they went. Neither answer should be that it won''t happen.'
    - q: 'What does the first deliverable look like, and when?'
      a: 'If nothing is visible for two months, that''s a warning regardless of who you hired.'
    - q: 'What is explicitly not included?'
      a: 'A fixed price only stays fixed if both sides can see the edges. Ask for the exclusions in writing.'
thumbnail: /assets/build/images/agency-or-one-independent-engineer.jpg
thumbnailAlt: 'A single keyboard and trackpad on a wooden desk, lit from one side'
thumbnailCopyRightSource: "https://unsplash.com/photos/brown-wooden-plank-near-white-and-blue-textile-frZt8AVYdI0"
---

Almost everyone who asks me this frames it as a cost question. The agency quoted
$60,000, the freelancer quoted $25,000, and the question is whether the
freelancer is a bargain or a warning sign.

It's the wrong comparison. Cost matters, obviously. But the two options don't
fail in the same way, and that's the thing you're really picking between: which
kind of failure you'd rather live with. Once you see it that way, the decision
stops hanging on which quote is smaller.

I'm an independent engineer, so one of these answers pays me. You should know
that before you read the rest.

Which is why I've stuck to things you can check. Every figure here is sourced,
the one statistic that would have helped me most is the one I threw out, and the
cases where an agency is the better answer are set out in full. You should
finish this knowing what you're choosing, rather than knowing what I'd prefer.

## Where the rate actually goes

Start with the thing nobody explains: why an agency developer costs a multiple
of what the same developer costs as an employee.

It isn't greed, and it's mostly not profit. It's arithmetic about time.

A developer on $75,000 a year is available for about 2,080 working hours. They
won't bill 2,080 hours. They take holiday, they get ill, they sit in internal
meetings, they write timesheets, they help with a pitch that doesn't land, they
spend a week between projects with nothing assigned.

What is left is the *billable* hours. Agencies plan for that to land between 70%
and 80% of the total. [Asana's benchmark
data](https://asana.com/resources/utilization-rate) puts software and IT
services at 75–80%, and most agency finance guidance lands in the same range.

Call it 1,500 billable hours. Now the arithmetic does something uncomfortable:

> $75,000 ÷ 1,500 hours = **$50 an hour**, before anything else at all.

That's the salary alone. It doesn't include employer taxes, pension, laptop,
software licences, insurance, office, or recruitment fees. And it doesn't
include the people at the agency who bill *nothing*: the account manager, the
project manager, the finance person, the sales team. Their salaries come out of
the same place, which is the hours the developers do bill.

So the 500 unbilled hours aren't free. The 1,500 billed ones pay for them.
That's the single most useful thing to understand about an agency rate, and it
explains the shape of the invoice better than any argument about value.

None of this is a scandal. It's what it costs to run a company that can survive
losing a client. But it does mean part of every hour you buy pays for the
agency rather than for your software.

## Why I will not use the statistic everyone quotes

If you search this question you will hit the Standish Group's CHAOS report
within about two clicks. The numbers are irresistible. [Roughly 31% of software
projects succeed](https://opencommons.org/CHAOS_Report_on_IT_Project_Outcomes).
About half are "challenged" and the rest fail outright. Small projects succeed
around 90% of the time. Large ones manage under 10%.

That last pair would be a gift to someone arguing my side of this. I'm not going
to lean on it, because the report doesn't hold up.

CHAOS has been picked apart in the peer-reviewed literature for twenty years.
Jørgensen and Moløkken's review in *Communications of the ACM* asked directly
[whether it describes a software crisis at
all](https://dl.acm.org/doi/10.1145/1145287.1145301). A follow-up in *IEEE
Software*, ["The Rise and Fall of the Chaos Report
Figures"](https://dl.acm.org/doi/10.1109/MS.2009.154), concluded the numbers were
exaggerated.

The objections are basic ones. The sampling method isn't disclosed. The rule for
which projects got included isn't published. And "success" means nothing more
than hitting the original estimates of cost, time and scope.

Think about what that definition does. A project that shipped something better
than planned, two weeks late, counts as a failure. A project that delivered
exactly what was specified, when the specification was wrong, counts as a
success.

I mention it for two reasons. One, you'll see those numbers quoted at you by
somebody selling something, and you should know they're soft. Two, if I quoted a
statistic that flattered me and it turned out to be junk, that would tell you
something about how carefully I check things generally.

## What holds up without Standish

Strip out the contested survey data and one thing survives, because it was never
a survey finding. It's arithmetic.

Every person added to a project adds communication paths, and they multiply
rather than add. With *n* people involved the number of two-way channels is
n(n−1)/2:

- You and one engineer: **1 channel**
- You, an account manager and a developer: **3 channels**
- You, an account manager, a project manager and two developers: **10 channels**

Ten channels isn't ten times the talking. It's ten places where a detail can get
passed on slightly wrong. Everyone has watched this happen: you explain
something clearly to the account manager, the account manager writes a summary,
the project manager turns the summary into tickets, and what arrives three weeks
later is a reasonable interpretation of a reasonable interpretation of what you
said.

That's the real reason smaller efforts go better, and it doesn't need a disputed
report to support it. It's also why my pitch isn't "I'm better than an agency."
It's "there's less distance between you and the work."

## The risk you are taking with a freelancer

Now the other side, because a comparison that only lists the other option's
problems is an advertisement.

Hiring one engineer concentrates every risk into one human being. They can get
ill in week three. They can take a job. They can turn out to be worse than they
seemed. They can stop replying altogether. I've taken over from developers who
did exactly that, and the client's position was awful: no access to anything, no
documentation, and a system earning money that nobody could change.

An agency is, among other things, an insurance policy against that. If your
developer leaves, someone else picks it up. You may not even find out. That's a
real thing to buy and it's worth real money.

The mitigations exist, and you should demand them of anyone independent,
including me. Everything in your name from day one: domain, hosting, code
repository, accounts. Documentation written as the work happens, not promised
for the end. Nothing that only runs on one laptop. If a freelancer can't
tell you what happens to your business the week after they're hit by a bus,
you've got your answer. Mine is on [the services page](/services/), and it's a
fair thing to ask about before you commit to anything.

## The risk you are taking with an agency

The agency risk is quieter, and I think it's the one people underestimate.
You're buying a slot rather than a person, and the person filling it changes.

The developer who spent four months learning why your pricing rules are strange
gets moved to another account. Someone new starts from nothing. And nobody tells
you it happened, because the account manager is the same as last month, and
continuity is exactly what you thought you were paying for.

The churn isn't hypothetical. The US Bureau of Labor Statistics puts [median
tenure across all wage and salary
workers](https://www.bls.gov/news.release/pdf/tenure.pdf) at 3.9 years as of
January 2024, down from 4.1 two years earlier. Software is commonly reported to
run shorter still. Figures around two years get quoted a lot, though I'd treat
those with the same suspicion I applied to CHAOS. BLS publishes no
developer-specific number, and most of the two-year claims trace back to surveys
rather than payroll data.

The direction isn't really in doubt, though. Over a two-year relationship, the
chance that the person who understands your system is still the person working
on it is not high. With an agency you've bought a process that survives that.
What you haven't bought is anyone who remembers why the decision was made.

That's the part I'd point at in my own work. [Both projects I've written
up](/work/) were one engineer on one system for years, five in one case. The
value in year four was mostly knowing why year two had gone the way it did.

## When an agency is the right answer

There are cases where I'll tell you to go to an agency, and this isn't modesty.

### When you need several specialisms at once

A brand identity, a mobile app, a
web platform and a paid-media campaign, all in the same quarter. One person
can't do that, and anyone claiming they can is telling you something.

### When you have a hard external deadline and no slack

A trade show, a
regulatory date, a funding round. Capacity you can scale is worth paying for,
and one engineer with flu is a single point of failure you cannot absorb.

### When the work is simply large

Past a certain size, coordination becomes a
job in itself. Paying someone to do it full time stops being overhead and starts
being the thing that saves you.

### When your own governance requires it

Some boards, insurers and procurement
processes won't sign off on a sole supplier. That's a constraint rather than a
preference, and arguing with it wastes everybody's time.

## What to ask, either way

The questions that actually separate a good outcome from a bad one are the same
for both:

1. **Who writes the code, and will I meet them?** If the people in the pitch are
   not the people on the work, ask who is.
2. **What do I own on the last day?** Domain, repository, hosting accounts,
   analytics, everything. Get the answer in writing before money moves.
3. **What happens when the person who knows this leaves?** Both models have an
   answer. Neither answer should be "that won't happen."
4. **What does the first deliverable look like, and when?** If nothing is
   visible for two months, that's a warning regardless of who you hired.
5. **What is explicitly not included?** A fixed price only stays fixed if both
   sides can see the edges. Any fixed price I quote comes with its exclusions
   written down for exactly that reason.

It would be a poor article that asked you to interrogate everyone but me. My
answers to all five are [on the questions page](/faq/): ownership, what happens
if I'm unavailable, how the work runs, and what I won't take on.

## The two columns, side by side

Everything above, in one place. Some of these rows favor an agency and some
favor me; that's the point.

| | An agency | One independent engineer |
| --- | --- | --- |
| Who you talk to | Usually an account manager | The person writing the code |
| Who writes it | Whoever is assigned that month | The person you hired |
| Communication paths | Ten, across a team of five | One |
| If they leave | Someone else picks it up, often without telling you | The work stops until you find someone else |
| If they are ill | Absorbed by the team | Your dates move |
| Several disciplines at once | Yes: brand, app, campaign | No |
| Adding people mid-project | Yes | No |
| Who remembers why a decision was made | The process, not a person | The person |
| What the rate covers | The work, plus the hours nobody bills | The work |
| Best when | The job is large, urgent, or many-sided | One system has to be right and stay right |

## Summary

You're not choosing between expensive and cheap. You're choosing between paying
for coordination and continuity, or paying for directness and accepting that it
rests on one person.

If your project is large, spans several disciplines, or has a deadline you can't
move, buy the coordination and go with an agency. If it's one system that has to
be right and stay right, protect the distance between you and the person
building it, and hire the one person. Every layer you add is one more place
where something gets passed on slightly wrong.

Either way, ask the five questions above. The people who answer them plainly are
the ones to hire.
