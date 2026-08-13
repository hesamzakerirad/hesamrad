---
extends: _layouts.case-study
section: content
title: 'The metrics a broker could not buy'
description: 'A monitoring layer that let a brokerage watch its traders on its own measures rather than the ones its trading platform happened to report.'
published: true
sample: false
client: null
sector: 'Financial services'
year: '2024'
duration: 'One year, as lead engineer'
summary: 'A brokerage could only see what its trading platform chose to report about its traders, which was not much and not the right things. Their own analysts had worked out what they needed to watch. This is the system that measures it, sitting between the broker and its clients without either side having to change how they work.'
role: 'Lead engineer. The back end and the API architecture were mine end to end. The firm had its own people and its own tools for presentation, so building an interface would have duplicated what they already had. The engagement was scoped to the part they couldn''t do themselves.'
problem: 'Brokers largely see their traders through whatever their trading platform surfaces, and MetaTrader surfaces a fixed and fairly shallow set of figures. That''s enough to know what happened and rarely enough to know what is happening: which trader is drifting from their stated strategy, which account is behaving unlike itself this week, where a position warrants a conversation before rather than after. The firm''s senior analyst had defined measures that would answer those questions. Nothing they could buy implemented them.'
constraints:
    - 'The measures themselves are the client''s intellectual property and the reason the system is worth having. They aren''t described here, and that constraint shaped the build as much as any technical one.'
    - 'It had to be invisible from both sides. Traders carry on in the platform they already use, and nothing about the broker''s existing arrangements could be disturbed to accommodate it.'
    - 'Financial data, so a figure that is merely probably right is worthless. Anything the system reports has to be reproducible and explainable after the fact, because decisions get taken on the strength of it.'
    - 'The definitions were expected to change. Analysts refine what they measure, and a system where refining a measure means a developer and a release is a system that stops being refined.'
built:
    - 'An ingestion layer that reads trading activity continuously, without traders changing anything about how they work or noticing that it exists.'
    - 'A calculation engine implementing the analysts'' measures, built so a definition is configuration rather than code. The people who own the measures can change them without waiting for me.'
    - 'An API the firm''s own tools read from, which is where the numbers stop being mine and become theirs to present however they like.'
    - 'Thresholds that raise something for a human to look at, so the system prompts a conversation rather than taking an action on its own.'
    - 'A full history of every calculation, so any figure can be reproduced months later against the inputs it was derived from.'
decisions:
    - choice: 'Measures as configuration, not as code'
      why: 'The analysts own the definitions and will keep refining them. Hard-coding the first version would have made every refinement a development task, and the predictable outcome of that is that refinement stops.'
      cost: 'A configuration layer is more to build and more to get wrong than a hard-coded formula, and it needed validation of its own so a bad definition fails loudly instead of quietly producing a plausible number.'
    - choice: 'Flag for a person rather than act automatically'
      why: 'These measures inform decisions about people''s accounts and livelihoods. A system that acts on its own would need to be right in a way no system is, and would put the firm behind an automated decision it couldn''t explain.'
      cost: 'It needs somebody to read what it raises. The value depends on that habit forming, and no software can guarantee that.'
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
      detail: 'Ingestion and the calculation engine, checked against periods the analysts already knew the right answers for.'
    - phase: 'Months six to nine'
      detail: 'The API, the configuration layer, and the history that makes every figure reproducible.'
    - phase: 'Final months'
      detail: 'Thresholds, hardening, and handover to the team who run it.'
results: []
quote: null
differently: 'I started building before the measures were pinned down as tightly as they needed to be, assuming the details would settle as we went. They did settle, but some of them settled differently to how I''d built for, and I rewrote work that a longer conversation at the start would have got right the first time. On a system whose entire value is the precision of its definitions, the specification was the wrong place to move quickly.'
cover:
    src: /assets/build/images/broker-monitoring.jpg
    alt: ''
    credit: 'https://images.unsplash.com/photo-1651341050677-24dba59ce0fd'
coverNote: 'No screenshots. The client asked for confidentiality, and that''s worth more to them than it is to me.'
gallery: []
stack:
    - Laravel
    - PHP
    - MySQL
    - Redis
    - Queues
---
