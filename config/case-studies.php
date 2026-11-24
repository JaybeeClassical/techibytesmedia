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
| Case study write-ups
|--------------------------------------------------------------------------
|
| Extra content for each project's page at /portfolio/{key}. Keys match
| config/projects.php, which keeps the name, URL, screenshot and tags.
| This lives in its own file because the screenshot capture command
| rewrites config/projects.php on the server.
|
| "services" lists keys from config/service-pages.php (or "graphics") and
| links the case study to those service pages. "solution" may contain
| in-text links written as [[target|link text]] (see App\Support\InternalLinks).
|
*/

return [
    'billswaka' => [
        'location' => 'Nigeria',
        'services' => ['mobile-app-development', 'software-development', 'web-development'],
        'overview' => 'Bills Waka is a bills and payments platform that lets users create virtual dollar cards and pay for everyday services from their phone.',
        'challenge' => 'Payments products live or die on trust. Bills Waka needed an app and a web presence that made virtual dollar cards and bill payments feel simple, fast and safe for everyday users.',
        'solution' => 'We worked on the Bills Waka [[mobile-app-development|mobile app]] and [[web-development|website]], focusing on clear onboarding, straightforward payment flows and a marketing site that explains the product and drives app downloads.',
        'deliverables' => ['Mobile app development', 'Payment and card flows', 'Marketing website'],
        'testimonial' => [
            'quote' => 'Working with this team was an absolute pleasure. They delivered beyond our expectations and provided valuable insights that helped us achieve a 200% increase in conversions.',
            'name' => 'Oge Destiny',
            'role' => 'CEO, Billswaka App',
        ],
    ],

    'scholarly' => [
        'location' => 'Nigeria',
        'services' => ['web-development'],
        'overview' => 'Scholarly Africa is an education technology company whose apps help students prepare for JAMB, WAEC, Post UTME, NECO, BECE and other exams.',
        'challenge' => 'With a large and growing student audience, Scholarly needed a website that explains its learning apps clearly, builds trust with students and parents, and sends visitors to the right download.',
        'solution' => 'We built a [[web-development|web presence]] that presents Scholarly\'s products, exam coverage and app downloads in a clear, mobile-first layout designed for students on phones.',
        'deliverables' => ['Website design and development', 'Mobile-first layouts', 'App download journeys'],
        'testimonial' => [
            'quote' => 'Techibytes Media transformed our online presence completely. Their attention to detail and understanding of our business goals resulted in a website that truly represents our brand.',
            'name' => 'Uche Umeh',
            'role' => 'CEO, Scholarly Africa',
        ],
    ],

    'sendbit' => [
        'location' => null,
        'services' => ['software-development', 'mobile-app-development', 'web-development'],
        'overview' => 'Sendbit is a fintech platform for converting crypto, creating payment links and receiving payments from around the world.',
        'challenge' => 'Cross-border payments involve unfamiliar concepts for many users. Sendbit needed its app and website to make invoices, international accounts and digital assets feel approachable and trustworthy.',
        'solution' => 'We worked on the Sendbit [[software-development|payments platform]], [[mobile-app-development|app]] and website, presenting each payment feature in plain language and giving users a clear path from learning about the product to getting paid.',
        'deliverables' => ['App development', 'Fintech product website', 'Payment feature pages'],
        'testimonial' => null,
    ],

    'topfreshcuts' => [
        'location' => 'Des Moines, WA',
        'services' => ['web-development'],
        'overview' => 'Top Fresh Cuts is a barbershop in Des Moines, Washington, offering haircuts, fades, beard grooming and VIP mobile barber services.',
        'challenge' => 'Local customers choose a barber on their phone in seconds. Top Fresh Cuts needed a site that shows its work and prices at a glance and makes booking an appointment effortless.',
        'solution' => 'We designed and built a modern, mobile-first [[web-development|barbershop website]] with clear service and pricing sections and an easy online booking journey.',
        'deliverables' => ['Website design and development', 'Services and pricing pages', 'Online booking journey'],
        'testimonial' => null,
    ],

    'hofashionhub' => [
        'location' => 'Akure, Nigeria',
        'services' => ['web-development'],
        'overview' => 'HO Fashion Hub is a curated thrift fashion destination for women who love affordable, one-of-one and pre-loved pieces.',
        'challenge' => 'HO Fashion Hub needed an online home that captures the feel of its curated collections, helps customers discover new styles and supports its plans for an app.',
        'solution' => 'We built a [[web-development|fashion e-commerce website]] that showcases curated styles, presents store locations and invites customers to join the app waitlist.',
        'deliverables' => ['E-commerce website', 'Product and collection pages', 'App waitlist sign-up'],
        'testimonial' => null,
    ],

    'st-michaels-afh' => [
        'location' => 'Tacoma, WA',
        'services' => ['web-development'],
        'overview' => 'St. Michael\'s Adult Family Home provides compassionate residential care for adults in Tacoma, Washington.',
        'challenge' => 'Families looking for care are making an emotional, high-stakes decision. St. Michael\'s needed a website that feels warm and reassuring, explains its services clearly and makes it easy to ask about availability or arrange a visit.',
        'solution' => 'We designed and built a [[web-development|welcoming website]] that presents the home\'s care services, answers families\' common questions and guides them to get in touch.',
        'deliverables' => ['Website design and development', 'Care services pages', 'Contact and visit enquiries'],
        'testimonial' => null,
    ],

    'ibuildinitiative' => [
        'location' => 'Abuja, Nigeria',
        'services' => ['web-development'],
        'overview' => 'Build Nigeria Initiative is a non-profit that helps young Nigerians develop innovative and creative digital skills.',
        'challenge' => 'The initiative needed a platform that explains its mission to young people, partners and supporters, and connects youth with training, resources and opportunities.',
        'solution' => 'We built a [[web-development|non-profit website]] that presents the initiative\'s programmes, resources and opportunities in a clear structure for both young learners and supporters.',
        'deliverables' => ['Non-profit website', 'Programme and resource pages', 'Supporter information'],
        'testimonial' => null,
    ],

    'senator-osita-izunaso' => [
        'location' => 'Imo West, Nigeria',
        'services' => ['web-development'],
        'overview' => 'The official website of Senator Osita Izunaso, who represents Imo West Senatorial District in the 10th Senate of Nigeria.',
        'challenge' => 'Constituents, journalists and partners needed one credible place to learn about the senator\'s biography, legislative work and projects across the district.',
        'solution' => 'We built an [[web-development|official public service platform]] presenting the senator\'s record, legislative agenda, projects and updates in a structured, easy-to-navigate format.',
        'deliverables' => ['Official website', 'Biography and legislative pages', 'Projects and updates'],
        'testimonial' => null,
    ],
];
