<?php

/*
 *
 *   Created by Techibytes Media Development Team
 *   Copyright Ⓒ 2026. All rights reserved, https://techibytesmedia.com/
 *   Project: techibytesmedia
 *
 *   Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file
 *   except in compliance with the License. You may obtain a copy of the License at
 *   https://www.apache.org/licenses/LICENSE-2.0. Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 *    either express or implied. See the License for the specific language governing permissions and
 *    limitations under the License.
 * /
 */

declare(strict_types = 1);

/*
|--------------------------------------------------------------------------
| Service landing pages
|--------------------------------------------------------------------------
|
| Each entry becomes a page at /services/{key}, an item in the Services
| dropdown and a link in the footer. Copy is written around each page's
| target search terms; keep the keyword in the title, H1 and first H2.
|
| "projects" lists keys from config/projects.php that appear as related case
| studies. Only list projects where this service was actually delivered.
| "related" lists other service keys (or "graphics") shown as related services.
|
| "detail" paragraphs and FAQ answers may contain in-text links written as
| [[target|link text]]; see App\Support\InternalLinks for the targets.
|
*/

return [
    'areas_served' => 'Seattle, Texas and businesses across the US, with a second team in Abuja, Nigeria',

    'items' => [
        'web-development' => [
            'nav_label' => 'Web Development',
            'nav_summary' => 'Custom websites and web platforms',
            'contact_option' => 'Web Development',
            'meta_title' => 'Web Development Company & Custom Website Design | Techibytes Media',
            'meta_description' => 'Techibytes Media is a web development company building fast, custom-designed websites and web platforms for businesses in Seattle, Texas and across the US.',
            'eyebrow' => 'Web development company',
            'heading' => 'A web development company for websites that',
            'heading_accent' => 'win customers.',
            'intro' => 'We design and build custom websites that load fast, rank in search and turn visitors into enquiries. No templates, no page builders fighting your brand. Every site is designed around your customers and engineered to grow with your business.',
            'offerings' => [
                ['title' => 'Custom website design', 'desc' => 'Original layouts designed around your brand, your customers and the actions you want them to take.'],
                ['title' => 'Business & marketing websites', 'desc' => 'Service, booking and lead-generation sites that explain what you do and make it easy to get in touch.'],
                ['title' => 'E-commerce development', 'desc' => 'Online stores with clear product pages, smooth checkout and payment integrations your customers trust.'],
                ['title' => 'WordPress & CMS development', 'desc' => 'Content-managed sites your team can update without a developer, built on WordPress or a headless CMS.'],
                ['title' => 'Website redesign & migration', 'desc' => 'Modern rebuilds of existing sites, with redirects and structure planned so you keep the rankings you have earned.'],
                ['title' => 'Speed, accessibility & technical SEO', 'desc' => 'Core Web Vitals, accessible markup, structured data and clean code that search engines can read.'],
            ],
            'detail_heading' => 'Custom website design built around your customers',
            'detail' => [
                'A website is often the first conversation a customer has with your business. We start by learning who those customers are, what they need to know and what makes them choose you. That research shapes the page structure, the copy and the design before a line of code is written.',
                'Then we build it properly. Our developers work in Laravel, React, Next.js and WordPress, choosing the stack that fits your goals rather than forcing one tool onto every project. When a project needs accounts, dashboards or payments, our [[software-development|custom software team]] builds them in. Sites ship with fast hosting, analytics, [[seo|search-friendly structure]] and a content editor your team can actually use.',
            ],
            'process' => [
                ['title' => 'Discover', 'desc' => 'We map your audience, competitors, pages and goals, then agree a fixed, itemised proposal.'],
                ['title' => 'Design', 'desc' => 'Wireframes and full visual designs for desktop and mobile, refined with your feedback.'],
                ['title' => 'Build', 'desc' => 'Clean, tested development with weekly demos on a private staging site.'],
                ['title' => 'Launch & support', 'desc' => 'We handle launch, redirects and analytics, then keep the site secure and up to date.'],
            ],
            'tech' => ['Laravel', 'PHP', 'React', 'Next.js', 'Node.js', 'TypeScript', 'Tailwind CSS', 'WordPress'],
            'faqs' => [
                ['q' => 'How much does a custom website cost?', 'a' => 'It depends on the number of pages, features and integrations. Every project gets a fixed, itemised proposal after a free discovery call, so you know the full cost before we begin.'],
                ['q' => 'How long does it take to build a website?', 'a' => 'A marketing or business website usually ships in 3 to 6 weeks. Larger sites with e-commerce or custom features take longer, and we agree the timeline up front.'],
                ['q' => 'Do you work with businesses in Seattle and Texas?', 'a' => 'Yes. Our Seattle team works with clients across Washington, including [[portfolio:topfreshcuts|a barbershop in Des Moines]] and [[portfolio:st-michaels-afh|an adult family home in Tacoma]]. We work with businesses in Texas and the rest of the US remotely, with meetings scheduled in your time zone.'],
                ['q' => 'Will I be able to update the website myself?', 'a' => 'Yes. We set up a content editor, usually WordPress or a simple admin panel, and show your team how to update pages, posts and images.'],
                ['q' => 'Can you redesign my site without losing search rankings?', 'a' => 'Yes. We audit your current pages, keep the URLs that rank, and set up redirects for anything that moves so search engines carry your rankings over. Our [[seo|SEO team]] checks everything before and after launch.'],
            ],
            'projects' => ['topfreshcuts', 'st-michaels-afh', 'hofashionhub', 'senator-osita-izunaso'],
            'related' => ['seo', 'software-development', 'brand-strategy'],
        ],

        'software-development' => [
            'nav_label' => 'Software Development',
            'nav_summary' => 'Custom software and web apps',
            'contact_option' => 'Custom Software Development',
            'meta_title' => 'Custom Software Development & Web App Development | Techibytes Media',
            'meta_description' => 'Custom software development and web app development for startups and growing businesses in Seattle, Texas and across the US. SaaS, portals, dashboards and APIs.',
            'eyebrow' => 'Custom software development',
            'heading' => 'Custom software development for businesses that have',
            'heading_accent' => 'outgrown off-the-shelf tools.',
            'intro' => 'When spreadsheets, plugins and generic SaaS stop fitting the way you work, we build software that does. From customer portals to full SaaS products, we design, engineer and maintain web applications that are secure, fast and ready to scale.',
            'offerings' => [
                ['title' => 'Web app development', 'desc' => 'Browser-based applications with user accounts, roles, dashboards and the workflows your business runs on.'],
                ['title' => 'SaaS product development', 'desc' => 'Multi-tenant products with subscriptions, onboarding and admin tools, from first MVP to paying customers.'],
                ['title' => 'Internal tools & dashboards', 'desc' => 'Replace manual spreadsheets with tools that track orders, clients, inventory or operations in one place.'],
                ['title' => 'API development & integrations', 'desc' => 'Connect payment providers, CRMs, accounting tools and third-party services through reliable APIs.'],
                ['title' => 'Fintech & payment systems', 'desc' => 'Payment links, wallets, invoicing and card features built with the security and audit trails money demands.'],
                ['title' => 'Modernisation & maintenance', 'desc' => 'Take over, stabilise and improve existing codebases, with tests, monitoring and a clear roadmap.'],
            ],
            'detail_heading' => 'Web app development, from MVP to scale',
            'detail' => [
                'Good software starts with the problem, not the feature list. We work with you to map the people who will use the product, the jobs they need to get done and the smallest version that proves value. That becomes a clear scope, a fixed proposal and a roadmap beyond launch.',
                'We build with proven, well-supported technology such as Laravel, Node.js, React and PostgreSQL, and we demo working software every week so there are no surprises. When you are ready to grow, the same team adds features, launches [[mobile-app-development|companion mobile apps]], improves performance and keeps everything secure.',
            ],
            'process' => [
                ['title' => 'Discovery & scope', 'desc' => 'Workshops to define users, workflows and the first release, ending in a fixed proposal.'],
                ['title' => 'Product design', 'desc' => 'User flows, wireframes and interface design tested before development starts.'],
                ['title' => 'Agile development', 'desc' => 'Weekly releases to a staging environment, with automated tests and code reviews.'],
                ['title' => 'Launch & scale', 'desc' => 'Deployment, monitoring and ongoing improvements as your users and data grow.'],
            ],
            'tech' => ['Laravel', 'PHP', 'Node.js', 'TypeScript', 'React', 'Next.js', 'PostgreSQL', 'MySQL', 'Redis', 'AWS'],
            'faqs' => [
                ['q' => 'What is the difference between a website and a web app?', 'a' => 'A website mainly presents information. A web app lets people do things: log in, manage data, make payments or run a workflow. Many projects combine both.'],
                ['q' => 'Do you build MVPs for startups?', 'a' => 'Yes. We help founders define the smallest product that proves demand, build it quickly, and then extend it once real users are on board. See our work on [[portfolio:sendbit|the Sendbit fintech platform]].'],
                ['q' => 'How long does custom software take to build?', 'a' => 'Most custom platforms run 2 to 4 months for a first release, depending on complexity. We work in weekly milestones so you see progress throughout.'],
                ['q' => 'Can you work on our existing software?', 'a' => 'Yes. We start with a technical audit, stabilise what is there and agree a roadmap for improvements. We only recommend a rebuild when the foundations genuinely require it.'],
            ],
            'projects' => ['sendbit', 'billswaka'],
            'related' => ['web-development', 'mobile-app-development', 'brand-strategy'],
        ],

        'mobile-app-development' => [
            'nav_label' => 'Mobile App Development',
            'nav_summary' => 'iOS and Android apps',
            'contact_option' => 'Mobile App Development',
            'meta_title' => 'Mobile App Developers for iOS & Android | Techibytes Media',
            'meta_description' => 'Hire experienced mobile app developers for iOS, Android and cross-platform apps. Techibytes Media builds apps for businesses in Seattle, Texas and across the US.',
            'eyebrow' => 'Mobile app developers',
            'heading' => 'Mobile app developers building apps people',
            'heading_accent' => 'keep opening.',
            'intro' => 'We design and develop iOS and Android apps that feel fast, look polished and solve a real problem for your users. One team handles the design, the app, the backend and the launch, so nothing gets lost between agencies.',
            'offerings' => [
                ['title' => 'iOS app development', 'desc' => 'iPhone and iPad apps built to Apple guidelines and ready for App Store review.'],
                ['title' => 'Android app development', 'desc' => 'Android apps that run smoothly across the wide range of devices your customers use.'],
                ['title' => 'Cross-platform apps', 'desc' => 'One codebase for iOS and Android with React Native or Flutter, so you launch on both for less.'],
                ['title' => 'App UI/UX design', 'desc' => 'Onboarding, navigation and screens designed and prototyped before development begins.'],
                ['title' => 'Backends & APIs', 'desc' => 'Secure servers, databases, authentication and push notifications that power your app.'],
                ['title' => 'Launch & app store optimisation', 'desc' => 'Store listings, screenshots and release management, then updates as your users give feedback.'],
            ],
            'detail_heading' => 'Native-quality apps for iOS and Android',
            'detail' => [
                'Users decide within seconds whether an app is worth keeping. We focus on the moments that matter: a fast first launch, simple onboarding and core features that work reliably, even on a weak connection.',
                'Most of our clients choose cross-platform development with React Native or Flutter to reach both stores from one codebase. When an app needs deep device features or peak performance, our developers build natively in Swift and Kotlin, and our [[software-development|backend engineers]] build the servers and APIs behind it.',
            ],
            'process' => [
                ['title' => 'Strategy', 'desc' => 'Define your users, core features and the platforms to launch on first.'],
                ['title' => 'Design & prototype', 'desc' => 'Clickable prototypes you can test on your own phone before we build.'],
                ['title' => 'Develop & test', 'desc' => 'Weekly builds shared through TestFlight and Google Play testing tracks.'],
                ['title' => 'Launch & grow', 'desc' => 'App Store and Google Play submission, analytics, then ongoing updates.'],
            ],
            'tech' => ['React Native', 'Flutter', 'Dart', 'Swift', 'Kotlin', 'Firebase', 'Expo'],
            'faqs' => [
                ['q' => 'Should I build a native or cross-platform app?', 'a' => 'Cross-platform suits most business apps and gets you onto iOS and Android faster. Native makes sense for apps that rely heavily on device hardware or need maximum performance. We recommend one after discovery.'],
                ['q' => 'How long does it take to build a mobile app?', 'a' => 'A first release typically takes 2 to 4 months, depending on features and integrations. We share test builds every week so you can try the app as it grows.'],
                ['q' => 'Do you publish the app to the App Store and Google Play?', 'a' => 'Yes. We prepare the store listings, handle submission and review, and manage future releases. Our [[media-ad|media team]] can also run app install campaigns after launch.'],
                ['q' => 'Do you support the app after launch?', 'a' => 'Yes. We offer ongoing support covering OS updates, bug fixes, monitoring and new features.'],
            ],
            'projects' => ['billswaka', 'sendbit'],
            'related' => ['software-development', 'web-development', 'media-ad'],
        ],

        'brand-strategy' => [
            'nav_label' => 'Brand Strategy',
            'nav_summary' => 'Positioning, messaging and identity',
            'contact_option' => 'Brand Strategy',
            'meta_title' => 'Brand Strategy Agency & Branding Agency | Techibytes Media',
            'meta_description' => 'A brand strategy and branding agency helping businesses in Seattle, Texas and across the US define their positioning, messaging and visual identity.',
            'eyebrow' => 'Brand strategy agency',
            'heading' => 'A brand strategy agency for businesses ready to',
            'heading_accent' => 'stand apart.',
            'intro' => 'Strong brands are built on clear decisions: who you serve, what you stand for and why customers should choose you. We help you make those decisions, then turn them into messaging and a visual identity that works everywhere your brand shows up.',
            'offerings' => [
                ['title' => 'Brand discovery & research', 'desc' => 'Interviews, competitor reviews and audience research that show where your brand can win.'],
                ['title' => 'Positioning & value proposition', 'desc' => 'A sharp statement of who you serve, what you offer and why you are the better choice.'],
                ['title' => 'Messaging & tone of voice', 'desc' => 'Key messages, taglines and a voice your team can use across web, sales and social.'],
                ['title' => 'Visual identity', 'desc' => 'Logos, colour, typography and imagery that express the strategy, created by our design team.'],
                ['title' => 'Brand guidelines', 'desc' => 'A practical guide that keeps every website, post, ad and document on brand.'],
                ['title' => 'Rebrands & launches', 'desc' => 'Plans to roll out a new or refreshed brand across every channel without losing recognition.'],
            ],
            'detail_heading' => 'Branding agency work grounded in strategy',
            'detail' => [
                'A logo on its own is not a brand. We begin with strategy so every creative decision has a reason behind it: research into your market, your customers and your competitors, distilled into positioning your whole team can repeat. Our [[graphics|graphic design team]] then turns it into a visual identity.',
                'Because we are also a web, software and marketing agency, your brand does not stop at a guidelines PDF. We carry it into [[web-development|your website]], [[mobile-app-development|your app]], [[media-ad|your campaigns]] and your social content, so customers meet the same brand everywhere.',
            ],
            'process' => [
                ['title' => 'Research', 'desc' => 'Stakeholder interviews, customer insight and a competitor audit.'],
                ['title' => 'Position', 'desc' => 'Positioning, value proposition and brand personality agreed with your team.'],
                ['title' => 'Express', 'desc' => 'Messaging, tone of voice and visual identity designed to match.'],
                ['title' => 'Activate', 'desc' => 'Guidelines, templates and rollout across web, social and print.'],
            ],
            'tech' => ['Figma', 'FigJam', 'Adobe Illustrator', 'Adobe InDesign', 'Miro', 'Notion'],
            'faqs' => [
                ['q' => 'What is the difference between brand strategy and branding?', 'a' => 'Brand strategy defines who you are for, what you stand for and how you are different. Branding is how that strategy is expressed through your name, logo, visuals and voice. We do both.'],
                ['q' => 'Do we need a new logo?', 'a' => 'Not always. Many businesses need clearer positioning and messaging more than a new mark. We will tell you honestly whether your current identity can be refreshed or should be replaced.'],
                ['q' => 'Do you work with startups as well as established businesses?', 'a' => 'Yes. We help startups define their brand before launch and help established companies reposition for new markets, products or audiences.'],
                ['q' => 'How long does a brand strategy project take?', 'a' => 'It depends on the scope and how many people need to be involved. We agree the timeline in a fixed proposal before we begin.'],
            ],
            'projects' => [],
            'related' => ['graphics', 'web-development', 'media-ad'],
        ],

        'media-ad' => [
            'nav_label' => 'Media & Advertising',
            'nav_summary' => 'Media buying and digital marketing',
            'contact_option' => 'Media Buying & Digital Marketing',
            'meta_title' => 'Media Agency & Media Buying Agency | Digital Marketing | Techibytes Media',
            'meta_description' => 'Techibytes Media is a media ad agency and digital marketing agency handling media buying, paid search, paid social and campaigns for businesses in Seattle, Texas and the US.',
            'eyebrow' => 'Media ad agency',
            'heading' => 'A media agency that turns ad spend into',
            'heading_accent' => 'measurable growth.',
            'intro' => 'We plan, buy and manage media across search, social and publishing platforms, then report on what it earned. Every campaign is built around a clear business goal, whether that is leads, sales, bookings or awareness in a new market.',
            'offerings' => [
                ['title' => 'Media planning & buying', 'desc' => 'Channel mix, budgets and placements chosen to reach your audience at the lowest sensible cost.'],
                ['title' => 'Paid search advertising', 'desc' => 'Google Ads campaigns targeting people already searching for what you sell, in the cities you serve.'],
                ['title' => 'Paid social advertising', 'desc' => 'Meta, Instagram, LinkedIn and TikTok campaigns with creative made for each platform.'],
                ['title' => 'Social media & content marketing', 'desc' => 'Content calendars, posts and community management that keep your brand visible between campaigns.'],
                ['title' => 'Email marketing', 'desc' => 'Campaigns and automated sequences that nurture leads and bring customers back.'],
                ['title' => 'Media publications & PR', 'desc' => 'Features and articles placed on respected blogs and media platforms to build authority.'],
            ],
            'detail_heading' => 'Media buying and digital marketing, managed end to end',
            'detail' => [
                'Media buying works best when strategy, creative and measurement sit with one team. We set up tracking first, so every campaign reports on the outcomes you care about, then build audiences and creative for each channel.',
                'Campaigns can be targeted to a single city, a state such as Washington or Texas, or the whole country. We review performance every week, move budget to what works and share plain-English reports that show where your money went and what it returned. Paid media works best alongside [[seo|SEO]] and [[web-development|landing pages built to convert]], so we can handle those too.',
            ],
            'process' => [
                ['title' => 'Audit & goals', 'desc' => 'Review past campaigns, tracking and competitors, then set targets.'],
                ['title' => 'Plan & create', 'desc' => 'Channel plan, budget split, audiences and ad creative.'],
                ['title' => 'Launch & optimise', 'desc' => 'Campaigns go live with weekly bid, audience and creative tuning.'],
                ['title' => 'Report & scale', 'desc' => 'Clear reporting on leads, sales and cost, then scale what works.'],
            ],
            'tech' => ['Google Ads', 'Meta Ads', 'LinkedIn Ads', 'TikTok Ads', 'Google Tag Manager', 'Google Analytics', 'HubSpot', 'Mailchimp'],
            'faqs' => [
                ['q' => 'Which advertising platforms do you buy media on?', 'a' => 'We run campaigns on Google Ads, Meta (Facebook and Instagram), LinkedIn and TikTok, and place sponsored features with blogs and media publications.'],
                ['q' => 'Can you target customers in a specific city or state?', 'a' => 'Yes. Campaigns can target a city such as Seattle, a state such as Texas, a radius around your location, or the whole US.'],
                ['q' => 'How do you measure results?', 'a' => 'We set up conversion tracking before launch and report on the outcomes that matter to you, such as leads, bookings, sales and cost per result.'],
                ['q' => 'Can you work alongside our in-house marketing team?', 'a' => 'Yes. Some clients hand us everything, while others use us for media buying or creative alongside their own team.'],
            ],
            'projects' => [],
            'related' => ['seo', 'brand-strategy', 'graphics'],
        ],

        'seo' => [
            'nav_label' => 'SEO Services',
            'nav_summary' => 'Technical, local and content SEO',
            'contact_option' => 'SEO',
            'meta_title' => 'SEO Services for Seattle, Texas & US Businesses | Techibytes Media',
            'meta_description' => 'SEO services that grow organic traffic and leads: technical SEO audits, local SEO, keyword strategy, content and link building for businesses in Seattle, Texas and the US.',
            'eyebrow' => 'SEO services',
            'heading' => 'SEO services that put you where your customers',
            'heading_accent' => 'are searching.',
            'intro' => 'We help businesses earn more traffic, leads and sales from Google without paying for every click. Our SEO work covers the technical foundations, the content that answers your customers\' questions and the local signals that win in map results.',
            'offerings' => [
                ['title' => 'Technical SEO audits', 'desc' => 'Crawling, indexing, site speed, structured data and the fixes that remove ranking roadblocks.'],
                ['title' => 'Local SEO', 'desc' => 'Google Business Profile, citations, reviews and location pages to win in Seattle, Texas and beyond.'],
                ['title' => 'Keyword research & strategy', 'desc' => 'The searches your buyers make, mapped to the pages that should rank for them.'],
                ['title' => 'On-page optimisation', 'desc' => 'Titles, headings, internal links and copy tuned for both search engines and readers.'],
                ['title' => 'SEO content creation', 'desc' => 'Service pages, guides and blog posts that answer real questions and attract links.'],
                ['title' => 'Link building & digital PR', 'desc' => 'Earned mentions and links from relevant publications, directories and partners.'],
            ],
            'detail_heading' => 'SEO that compounds month after month',
            'detail' => [
                'Search engine optimisation is the one channel that keeps working after you stop paying for it. We start with a full audit of your site and competitors, fix the technical issues holding you back (with help from our [[web-development|web developers]] when the site itself needs work), and build a keyword plan around the searches that bring revenue rather than vanity traffic.',
                'For local businesses we focus on the map results and city searches that drive calls and visits. For national brands we build content and authority that lets you compete for broader terms. Either way, you get monthly reporting from Search Console and Analytics showing rankings, traffic and leads.',
            ],
            'process' => [
                ['title' => 'Audit', 'desc' => 'Technical, content and backlink audit plus a competitor gap analysis.'],
                ['title' => 'Strategy', 'desc' => 'Keyword map, priority fixes and a content plan tied to your goals.'],
                ['title' => 'Optimise', 'desc' => 'Technical fixes, on-page updates, new content and local listings.'],
                ['title' => 'Grow & report', 'desc' => 'Link building, ongoing content and monthly performance reports.'],
            ],
            'tech' => ['Google Search Console', 'Google Analytics', 'SEMrush', 'Ahrefs', 'Screaming Frog', 'Google Business Profile', 'Looker Studio'],
            'faqs' => [
                ['q' => 'How long does SEO take to work?', 'a' => 'Technical fixes can show results within weeks, but most businesses see meaningful growth in rankings and traffic over several months. SEO compounds, so the gains build over time.'],
                ['q' => 'Can you help us rank in more than one city?', 'a' => 'Yes. We build location pages with genuinely local content, set up Google Business Profiles where you have a presence, and earn local links and reviews in each market.'],
                ['q' => 'Do you guarantee first-page rankings?', 'a' => 'No one can honestly guarantee rankings, because search engines control the results. We commit to the work, the reporting and the outcomes we can influence: traffic, leads and visibility for the terms that matter.'],
                ['q' => 'Do you write the content as well?', 'a' => 'Yes. Our writers create service pages, blog posts and guides, and we can also optimise content your team writes. You can see our own writing on [[blog|the Techibytes blog]].'],
            ],
            'projects' => [],
            'related' => ['web-development', 'media-ad', 'brand-strategy'],
        ],
    ],
];
