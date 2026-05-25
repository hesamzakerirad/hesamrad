@extends('_layouts.main')

@php
    $yearsOfExperience = date('Y') - 2018;
    $page->title = 'Hesam Rad - Resume';
    $page->description =
        'Software Engineer with ' . $yearsOfExperience . '+ years of experience in building products for the internet.';
    $page->robots = 'noindex,nofollow';
    $page->author = 'Hesam Rad';
    $page->language = 'en';
    $page->disableTitlePrefix = true;
    $page->author = 'Hesam Rad';
@endphp

@section('body')
    <div class="landing ltr">
        <div class="container">
            @include('_components.backlink')

            <h1>Hesam Rad - Resume</h1>

            <p>I am a back-end developer with {{ $yearsOfExperience }}+ years of professional experience in B2B, B2C,
                e-commerce, online hotel and
                flight reservation services, CRM and management systems with a strong focus on optimization and code
                quality.
                I have designed, developed and maintained products that have served 300K+ active users and are used on a
                daily
                basis. Apart from experience in agile environments, I have mentored junior developers and led small teams to
                ship
                meaningful software; and I am eager to continue doing so in the coming future.
            </p>

            <small>
                <a href="https://www.linkedin.com/in/hesamrad/details/recommendations/" target="_blank" class="underline">
                    <span>Read endorsements on LinkedIn</span>
                </a>
            </small>

            <h2 id="works" class="my-6">Work Experiences</h2>

            <div class="mb-1">
                <div class="flex lg:flex-row lg:justify-between lg:align-middle flex-col justify-start mt-1">
                    <b>Back-end Developer at TS Information Technology Ltd</b>
                    <span class="text-gray-400 text-sm">(Mar 2024 – Nov 2025)</span>
                </div>
                <ul class="ml-3 list-disc list-inside">
                    <li>Contributed to a large number of projects ranging from <a href="https://gap.im/" target="_blank">Gap
                            Messenger</a>,
                        <a href="https://virasty.com/" target="_blank">Virasty Social Platform</a>,
                        <a href="https://mihansms.com/" target="_blank">MihanSms</a>,
                        <a href="https://msgway.com/" target="_blank">MessageWay</a>, <a href="https://kingsera.com/"
                            target="_blank">KingsEra</a>, <a href="https://samandesk.com/" target="_blank">SamanDesk</a> and
                        many more.
                    </li>
                    <li>Introduced automated testing suites using PHPUnit achieving 90% reduction in code review time.
                    </li>
                    <li>Implemented onboarding solutions for new developers, creating step-by-step documentation and a
                        Dockerized development environment to streamline project setup, reducing setup time by 70%.</li>
                </ul>
            </div>

            <div class="mb-1">
                <div class="flex lg:flex-row lg:justify-between lg:align-middle flex-col justify-start mt-1">
                    <b>Lead Back-end Developer at A.P.P. Software Solutions</b>
                    <span class="text-gray-400 text-sm">(Oct 2020 – Sep 2025)</span>
                </div>
                <ul class="ml-3 list-disc list-inside">
                    <li>Designed and developed the entire back-end API in a RESTful manner to be consumed by multiple
                        front-end clients.
                    </li>
                    <li>Led a small team of developers to create the most popular digital menu platform in the city. (Over
                        500 local businesses use this product on a daily basis.)
                    </li>
                    <li>Boosted overall performance of the product by 30% by leveraging queues, query optimization and
                        caching.</li>
                    <li>Created a private CDN-like server to host static files, which improved load time by 42% and reduced
                        the traffic on the main server by another 84%.</li>
                    <li>Reduced deployment time to ~1 minute by creating CI/CD pipeline using GitHub actions.</li>
                </ul>
            </div>

            <div class="mb-1">
                <div class="flex lg:flex-row lg:justify-between lg:align-middle flex-col justify-start mt-1">
                    <b>Back-end Developer at Eghamat24</b>
                    <span class="text-gray-400 text-sm">(Apr 2022 – Jan 2023)</span>
                </div>
                <ul class="ml-3 list-disc list-inside">
                    <li>Engineered a solution to auto-update frequently changed data without the need of a human operator,
                        which stopped the ongoing search for new operators.

                    </li>
                    <li>Reduced maintenance time by providing automated tests for the first time using PHPUnit.
                    </li>
                    <li>Speed up the back-end API by 20% by optimizing database queries. (Defining necessary indexes,
                        removing n+1s and reducing the number of queries)</li>
                </ul>
            </div>

            <div class="mb-1">
                <div class="flex lg:flex-row lg:justify-between lg:align-middle flex-col justify-start mt-1">
                    <b>Back-end Developer at S.E.I. Digital Economy</b>
                    <span class="text-gray-400 text-sm">(Dec 2017 – Oct 2020)</span>
                </div>
                <ul class="ml-3 list-disc list-inside">
                    <li>Created a solution to automate manual procedures, which saved 10% of the company’s payroll.

                    </li>
                    <li>Reduced maintenance and response time by migrating legacy WordPress code to Laravel, which
                        ultimately led to faster development, and better DX.
                    </li>
                </ul>
            </div>

            <h2 id="projects" class="my-6">Projects</h2>

            <div>
                <b><a href="https://github.com/hesamzakerirad/laravel-sql-logger" target="_blank">Laravel SQL Logger</a></b>
                <p>A lightweight package to log SQL queries in your Laravel application</p>
            </div>

            <div>
                <b><a href="https://github.com/hesamzakerirad/laravel-wallet" target="_blank">Laravel Wallet</a></b>
                <p> A minimalistic wallet for any Laravel application allowing logging and concurrency</p>
            </div>

            <div>
                <b><a href="https://github.com/hesamzakerirad/laravel-flashlight" target="_blank">Laravel Flashlight</a></b>
                <p>A Highly Customizable Laravel Package to Log Requests</p>
            </div>

            <div>
                <b><a href="https://github.com/hesamzakerirad/laravel-api-debugger" target="_blank">Laravel Api
                        Debugger</a></b>
                <p>A Laravel package to ease the process of debugging JSON APIs</p>
            </div>

            <small>
                <a href="https://github.com/hesamzakerirad?tab=repositories" target="_blank" class="underline">
                    <span>See more on GitHub</span>
                </a>
            </small>

            <h2 id="education" class="my-6">Education</h2>

            <div class="flex lg:flex-row lg:justify-between lg:align-middle flex-col justify-start">
                <b>MS in English Literature at <a href="https://mashhad.iau.ir" target="_blank">Azad University</a></b>
                <span class="text-gray-400 text-sm">(Sep 2025 – On Going)</span>
            </div>

            <div class="flex lg:flex-row lg:justify-between lg:align-middle flex-col justify-start">
                <b>BS in Computer Engineering at <a href="https://www.sadjad.ac.ir" target="_blank">Sadjad
                        University</a></b>
                <span class="text-gray-400 text-sm">(Sep 2016 – Sep 2021)</span>
            </div>

            <h2 id="languages" class="my-6">Languages</h2>

            <div>
                <b>Persian:</b> Native -
                <b>English:</b> Professional -
                <b>German:</b> Beginner
            </div>

            <div class="my-10">
                <a class="bg-primary-500 text-white py-3 px-6 block text-center" href="mail:hesamrad.dev@gmail.com">
                    <span>Let's Talk More</span>
                    <i class="fa-solid fa-arrow-right ml-3"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
