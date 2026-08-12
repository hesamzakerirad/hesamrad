---
extends: _layouts.post
section: content
title: 'Hiring an agency or one independent engineer: the honest comparison'
description: 'The comparison people make is cost. The one that decides the outcome is risk — and an agency''s risks are the opposite of an independent engineer''s.'
tags:
    - Hiring
    - Small business
robots:
    - index
    - follow
created_at: 2026-08-12
updated_at: 2026-08-12
readTime: 8
isFeatured: true
isPublished: true
thumbnail: "https://images.unsplash.com/photo-1584670508996-c3144057a8d0?q=80&w=2371&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
thumbnailCopyRightSource: "https://unsplash.com/photos/brown-wooden-plank-near-white-and-blue-textile-frZt8AVYdI0"
---

Almost everyone who asks me this frames it as a cost question. The agency quoted
$60,000, the freelancer quoted $25,000, and the question is whether the
freelancer is a bargain or a warning sign.

It is the wrong comparison. Not because cost does not matter, but because the
two options do not fail in the same way, and you are really choosing which kind
of failure you would rather be exposed to. Once you see it that way the decision
gets easier, and it stops depending on which quote is smaller.

I am an independent engineer, so one of these answers pays me — worth knowing
before you read the rest. I have stuck to things you can check: every figure
here is sourced, the statistic that would have helped my case most is one I
threw out, and the cases where an agency is the right answer are set out in
full. You should finish this knowing exactly what you are choosing, rather than
knowing what I would prefer.

## Where the rate actually goes

Start with the thing nobody explains: why an agency developer costs a multiple
of what the same developer costs as an employee.

It is not greed, and it is mostly not profit. It is arithmetic about time.

A developer on $75,000 a year is available for about 2,080 working hours. They
will not bill 2,080 hours. They take holiday, they get ill, they sit in internal
meetings, they write timesheets, they help with a pitch that does not land, they
spend a week between projects with nothing assigned. What is left is the
*billable* hours, and across the industry agencies plan for that number to land
somewhere between 70% and 80% of the total — [Asana's benchmark
data](https://asana.com/resources/utilization-rate) puts software and IT
services at 75–80%, and most agency finance guidance uses similar figures.

Call it 1,500 billable hours. Now the arithmetic does something uncomfortable:

> $75,000 ÷ 1,500 hours = **$50 an hour**, before anything else at all.

That is the salary alone. It does not include employer taxes, pension, laptop,
software licences, insurance, office, or recruitment fees. And it does not
include the people at the agency who bill *nothing* — the account manager, the
project manager, the finance person, the sales team. Their salaries come out of
the same place: the hours the developers do bill.

So the 500 unbilled hours are not free. They are paid for by the 1,500 billed
ones. That is the single most useful thing to understand about an agency rate,
and it explains the shape of the invoice better than any argument about value.

None of this is a scandal. It is what it costs to run an organisation that can
survive one client leaving. But it does mean that when you buy agency hours, a
material share of what you pay funds the coordination of the agency rather than
the making of your software.

## The statistic everyone quotes, and why I am not going to use it

If you search this question you will hit the Standish Group's CHAOS report
within about two clicks. Its numbers are irresistible: [roughly 31% of software
projects succeed](https://opencommons.org/CHAOS_Report_on_IT_Project_Outcomes),
about half are "challenged", the rest fail outright — and small projects succeed
around 90% of the time while large ones manage under 10%.

That last pair would be a gift to someone arguing my side of this. I am not
going to lean on it, because the report does not hold up.

CHAOS has been picked apart in the peer-reviewed literature for twenty years.
Jørgensen and Moløkken's review in *Communications of the ACM* asked directly
[whether it describes a software crisis at
all](https://dl.acm.org/doi/10.1145/1145287.1145301), and a follow-up in *IEEE
Software* titled ["The Rise and Fall of the Chaos Report
Figures"](https://dl.acm.org/doi/10.1109/MS.2009.154) concluded the figures were
exaggerated. The recurring objections are basic ones: the sampling method is not
disclosed, the criteria for which projects were included are not published, and
"success" is defined purely as hitting the original estimates of cost, time and
scope. By that definition a project that shipped something better than planned,
late, counts as a failure. A project that delivered exactly what was specified —
and the specification was wrong — counts as a success.

I mention it for two reasons. One, you will see those numbers quoted at you by
somebody selling something, and you should know they are soft. Two, if I quoted
a statistic that flattered me and it turned out to be junk, that would tell you
something about how carefully I check things generally.

## What holds up without Standish

Strip out the contested survey data and one thing survives, because it is not a
survey finding at all. It is arithmetic.

Every person added to a project adds communication paths, and they multiply
rather than add. With *n* people involved the number of two-way channels is
n(n−1)/2:

- You and one engineer: **1 channel**
- You, an account manager and a developer: **3 channels**
- You, an account manager, a project manager and two developers: **10 channels**

Ten channels is not ten times the talking. It is ten places where a detail can
be passed on slightly wrong. Everyone has watched this happen: you explain
something clearly to the account manager, the account manager writes a summary,
the project manager turns the summary into tickets, and what arrives three weeks
later is a reasonable interpretation of a reasonable interpretation of what you
said.

That is the real reason smaller efforts go better, and it does not need a
disputed report to support it. It is why the honest version of my pitch is not
"I am better than an agency" but "there is less distance between you and the
work."

## The risk you are taking with one person

Now the other side, because a comparison that only lists the other option's
problems is an advertisement.

Hiring one engineer concentrates every risk into one human being. They can get
ill in week three. They can take a job. They can turn out to be worse than they
seemed. They can simply stop replying — I have taken over from developers who
did exactly that, and the client's position was genuinely awful: no access to
anything, no documentation, and a system earning money that nobody could change.

An agency is, among other things, an insurance policy against that. If your
developer leaves, someone else picks it up. You may not even find out. That is a
real thing to buy and it is worth real money.

The mitigations exist, and you should demand them of anyone independent,
including me. Everything in your name from day one — domain, hosting, code
repository, accounts. Documentation written as the work happens rather than
promised at the end. Nothing that only runs on one laptop. If a freelancer
cannot tell you what happens to your business the week after they are hit by a
bus, that is the answer to your question. Mine is on [the services
page](/services/), and it is a fair thing to ask about before you commit to
anything.

## The risk you are taking with an agency

The agency risk is quieter, and I think it is the one people underestimate.

You are not buying a person. You are buying a slot, and the person filling it
changes. The developer who spent four months learning why your pricing rules are
strange gets moved to another account, and the person who replaces them starts
from nothing — except now nobody tells you that happened, because the account
manager is still the same and continuity is the thing you were sold.

The churn is not hypothetical. The US Bureau of Labor Statistics puts [median
tenure across all wage and salary
workers](https://www.bls.gov/news.release/pdf/tenure.pdf) at 3.9 years as of
January 2024, down from 4.1 two years earlier. Software is commonly reported to
run shorter still — figures around two years get quoted a lot, though I would
treat those with the same suspicion I applied to CHAOS, since BLS does not
publish a developer-specific number and most of the two-year claims trace back
to surveys rather than payroll data.

The direction is not really in doubt, though. Over a two-year relationship, the
chance that the person who understands your system is still the person working
on it is not high. With an agency you have bought a process that survives that.
What you have not bought is anyone who remembers why the decision was made.

## When an agency is genuinely the right answer

There are cases where I will tell you to go to an agency, and this is not
modesty.

**When you need several specialisms at once.** A brand identity, a mobile app, a
web platform and a paid-media campaign, all in the same quarter. One person
cannot do that, and a person who claims they can is telling you something.

**When you have a hard external deadline and no slack.** A trade show, a
regulatory date, a funding round. Capacity you can scale is worth paying for,
and one engineer with flu is a single point of failure you cannot absorb.

**When the work is genuinely large.** Past a certain size a project needs
coordination as a discipline in its own right, and paying someone whose whole
job is that coordination stops being overhead and starts being the thing that
saves you.

**When your own governance requires it.** Some boards, insurers and procurement
processes will not sign off on a sole supplier. That is a constraint, not a
preference, and arguing with it is a waste of everybody's time.

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
   visible for two months, that is a warning regardless of who you hired.
5. **What is explicitly not included?** A fixed price only stays fixed if both
   sides can see the edges.

## The short version

You are not choosing between expensive and cheap. You are choosing between
paying for coordination and continuity, or paying for directness and accepting
that it rests on one person.

If your project is large, multi-disciplinary or deadline-critical, buy the
coordination. If it is one system that has to be right and stay right, the
distance between you and the person building it is the thing worth protecting —
and every layer you add is a layer that will, eventually, pass something on
slightly wrong.

Either way, ask the five questions. The people who answer them plainly are the
ones to hire.
