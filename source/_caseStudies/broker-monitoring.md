---
extends: _layouts.case-study
section: content
title: 'What a Broker Could Not Buy'
description: 'A monitoring layer that let a brokerage watch its traders on its own measures, and act on its own rules, without waiting for a platform to support either.'
contactHeading: 'Got something like this?'
contactIntro: "Describe it in a paragraph. You will get an honest answer about whether I am the right person for it, including the times when I am not."
published: true
sample: false
client: null
sector: 'Financial services'
year: '2025'
duration: 'One year, as sole back end engineer'
summary: 'A brokerage could only see what its trading platform chose to report about its traders, which was not much and not the right things. Their own analysts had worked out what they needed to watch, and the firm had rules it was enforcing by hand. This is the system that measures the first and enforces the second, sitting between the broker and its clients without either side having to change how they work.'
role: 'Sole back end engineer. Everything behind the API was mine, and the API itself. The firm had its own front end people and its own tools for presentation, so building an interface would have duplicated what they already had. The engagement was scoped to the part they couldn''t do themselves.'
problem: 'Brokers largely see their traders through whatever their trading platform surfaces, and MetaTrader surfaces a fixed and fairly shallow set of figures. That''s enough to know what happened and rarely enough to know what is happening: which trader is drifting from their stated strategy, which account is behaving unlike itself this week, where a position warrants a conversation before rather than after. The firm''s senior analyst had defined measures that would answer those questions. Nothing they could buy implemented them. Alongside that sat a second problem. The firm had house rules about how accounts could be used, including a ban on one person running a trade in one account and mirroring it in another, and every one of those rules was being checked by a person reading reports after the fact.'
constraints:
    - 'The measures themselves are the client''s intellectual property and the reason the system is worth having. They aren''t described here, and that constraint shaped the build as much as any technical one.'
    - 'It had to be invisible from both sides. Traders carry on in the platform they already use, and nothing about the broker''s existing arrangements could be disturbed to accommodate it. That had to hold even when the system acted. A suspended trader sees the suspension in their MetaTrader client, the same place they would see one a person had applied, and never learns there is a system behind it.'
    - 'Financial data, so a figure that is merely probably right is worthless. Anything the system reports has to be reproducible and explainable after the fact, because decisions get taken on the strength of it.'
    - 'The definitions were expected to change. Analysts refine what they measure, and a system where refining a measure means a developer and a release is a system that stops being refined.'
    - 'Some of the rules had to act on live accounts, which meant the system could be wrong about somebody''s trading day. Nothing it did could be allowed to stand without a person being able to undo it.'
built:
    - 'An ingestion layer that reads trading activity continuously, without traders changing anything about how they work or noticing that it exists.'
    - 'A calculation engine implementing the analysts'' measures, built so a definition is configuration rather than code. The people who own the measures can change them without waiting for me.'
    - 'A full history of every calculation, so any figure can be reproduced months later against the inputs it was derived from.'
    - 'An API the firm''s own tools read from, which is where the numbers stop being mine and become theirs to present however they like.'
    - 'Thresholds on the measures that raise something for a human to look at, so a judgment about a trader prompts a conversation rather than an action.'
    - 'A rules engine for the firm''s house rules, which do act. A trader past their loss limit is flagged and can be stopped from opening more positions. Two positions opened at the same moment for the same volume are flagged as mirrored, and accounts that keep producing them are suspended on both sides, because the firm doesn''t allow a position in one account to be copied into another.'
    - 'The rules themselves, written by the broker''s analyst rather than by me, each one a plain condition on facts.'
    - 'A manual override on everything the rules engine does, because the recovery path for a wrong action is a person reversing it, not a support ticket.'
decisions:
    - choice: 'Measures as configuration, not as code'
      why: 'The analysts own the definitions and keep refining them. Hard-code the first version and every refinement becomes a development task, which is how refinement stops.'
      cost: 'More to build and more to get wrong, and it needed validation of its own so a bad definition fails loudly instead of quietly producing a plausible number.'
    - choice: 'Rules act, measures only flag'
      why: 'The house rules are conditions on facts. A loss limit was passed or it wasn''t. Two positions match on time and volume or they don''t. A measure is closer to a judgment about how somebody trades, and a judgment is the wrong thing to put a consequence behind. Where a fact can still be a coincidence, the first one only flags and a pattern of them acts.'
      cost: 'Two mechanisms instead of one, and a boundary somebody has to keep defending. The case for wiring a measure straight to an action sounds reasonable every time it comes up.'
    - choice: 'Nothing the system does is irreversible'
      why: 'It can stop a trader opening positions and it can suspend an account, and a person can undo either in a step. It was never given the ability to close a position or touch money, because those don''t come back if the rule was wrong.'
      cost: 'No instant automatic response to anything, and a few rules would have been simpler to write without the limit.'
    - choice: 'Beside the platform, not in front of it'
      why: 'Sitting in the order path puts every trade in the firm behind my code, and one bad deployment on my side stops the business.'
      cost: 'It reacts after the fact. Cross a loss limit and send another order and that order fills, with the block landing behind it. The rules catch a pattern over a session rather than referee a single trade.'
    - choice: 'Every calculation kept, not just the current answer'
      why: 'A number that can''t be reproduced isn''t evidence. If a decision gets questioned in six months, the firm has to be able to show what was measured, when, and from what.'
      cost: 'Considerably more storage and a slower path for historical queries than storing only the latest value.'
    - choice: 'An API rather than screens'
      why: 'The firm had people and tools for presentation, and none for this. Building an interface would have duplicated what they already had and put me in the way of their own analysts.'
      cost: 'Nothing is usable until something consumes it, so there was a stretch with real output and nothing to look at.'
timeline:
    - phase: 'First weeks'
      detail: 'Working through the measures with the analyst until they were specified precisely enough to implement, which took longer than expected and was the most valuable part.'
    - phase: 'Months two to five'
      detail: 'Ingestion and the calculation engine, checked against periods the analysts already knew the right answers for. The first house rules went in alongside them, hard-coded on purpose, because the question at that stage was whether a rule could see what it needed to see and act on it at all.'
    - phase: 'Months six to nine'
      detail: 'The API, the history that makes every figure reproducible, and the configuration layer. The measures and the hard-coded rules both moved into it, which is the point where the analyst stopped needing me to change either.'
    - phase: 'Final months'
      detail: 'A long stretch of shadow mode against test accounts. The rules evaluated and logged what they would have done without doing it, which is the only way to find out that a threshold is wrong before it is wrong on somebody real. Then hardening and handover to the team who run it.'
results:
    - figure: 'Two'
      caption: 'paid platforms replaced, and their subscriptions with them'
    - figure: 'One'
      caption: 'place to look for anything about a trader, in place of several'
    - figure: 'Every'
      caption: 'measure watched continuously, where a person used to check by hand'
    - figure: 'None'
      caption: 'of my time needed when the analyst changes a rule'
review: null
differently: 'The first thresholds I implemented were wrong, and shadow mode is what told us, after weeks of watching the rules fire against test accounts where a bad call harmed nobody. I built that shadow mode after the rules. I would build it first next time, so it is the thing thresholds get tuned in rather than the thing that catches them being wrong months later.'
cover:
    src: /assets/build/images/broker-monitoring.jpg
    alt: 'A close-up of a screen at an angle, showing a list of market indexes with red and green percentage moves beside a line chart.'
    credit: 'https://unsplash.com/photos/graphical-user-interface-application-x07ELaNFt34'
coverNote: 'No screenshots. The client asked for confidentiality, and that''s worth more to them than it is to me.'
gallery: []
stack:
---
