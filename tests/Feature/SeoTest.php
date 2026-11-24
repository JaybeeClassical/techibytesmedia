<?php

/*
 *
 *   Created by Techibytes Media Development Team
 *   Copyright Ⓒ 2026. All rights reserved, https://techibytesmedia.com/
 *   Project: techibytesmedia
 *   Last modified: 8/28/26, 10:12 AM
 *   Modified or Created by: erigb
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

test('public pages include canonical and social metadata', function (string $routeName, string $path): void {
    config()->set('app.url', 'https://techibytesmedia.test');

    $canonical_url = 'https://techibytesmedia.test' . $path;
    $social_image_url = 'https://techibytesmedia.test/images/logo/logo-background.png';

    $this->get(route($routeName))
        ->assertSuccessful()
        ->assertSee('<link rel="canonical" href="' . $canonical_url . '">', false)
        ->assertSee('<meta property="og:url" content="' . $canonical_url . '">', false)
        ->assertSee('<meta property="og:image" content="' . $social_image_url . '">', false)
        ->assertSee('<meta name="twitter:card" content="summary">', false)
        ->assertSee('<meta name="twitter:image" content="' . $social_image_url . '">', false);
})->with([
    'home' => ['home', '/'],
    'services' => ['services', '/services'],
    'portfolio' => ['portfolio', '/portfolio'],
    'graphics' => ['graphics', '/graphics'],
    'contact' => ['contact', '/contact'],
    'blog' => ['blog', '/blog'],
    'terms' => ['terms', '/terms'],
    'privacy' => ['privacy', '/privacy'],
    'payment disclosure' => ['payment-services-disclosure', '/payment-services-disclosure'],
]);

test('home page includes organization and website structured data', function (): void {
    config()->set('app.url', 'https://techibytesmedia.test');

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('<script type="application/ld+json">', false)
        ->assertSee('"@type":"Organization"', false)
        ->assertSee('"@type":"WebSite"', false)
        ->assertSee('"legalName":"Techibytes Media LLC"', false)
        ->assertSee('"addressLocality":"Abuja"', false)
        ->assertSee('"addressLocality":"Seattle"', false)
        ->assertSee('"telephone":"+2349031807262"', false)
        ->assertDontSee('+12065786373', false);
});

test('sitemap lists every indexable public page', function (): void {
    config()->set([
        'app.url' => 'https://techibytesmedia.test',
        'services.wordpress.url' => null,
    ]);

    $response = $this->get(route('sitemap'))
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'application/xml');

    $paths = ['/', '/services', '/portfolio', '/graphics', '/blog', '/contact', '/terms', '/privacy', '/payment-services-disclosure'];

    foreach (array_keys(config('service-pages.items')) as $service) {
        $paths[] = "/services/{$service}";
    }

    foreach (array_keys(config('projects.items')) as $project) {
        $paths[] = "/portfolio/{$project}";
    }

    foreach ($paths as $path) {
        $response->assertSee('<loc>https://techibytesmedia.test' . $path . '</loc>', false);
    }

    expect(mb_substr_count($response->getContent(), '<url>'))->toBe(count($paths));
});

test('robots file advertises the sitemap', function (): void {
    expect(file_get_contents(public_path('robots.txt')))
        ->toContain('User-agent: *')
        ->toContain('Sitemap: https://techibytesmedia.com/sitemap.xml');
});

test('every page outputs one connected structured data graph', function (string $routeName, array $parameters, string $pageType, bool $hasBreadcrumbs): void {
    config()->set('app.url', 'https://techibytesmedia.test');

    $content = (string) $this->get(route($routeName, $parameters))->assertSuccessful()->getContent();

    expect(mb_substr_count($content, '<script type="application/ld+json">'))->toBe(1);

    preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $content, $matches);
    $graph = collect(json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR)['@graph']);
    $types = $graph->pluck('@type');

    expect($types)->toContain('Organization', 'WebSite', $pageType);

    $page = $graph->firstWhere('@type', $pageType);
    expect($page['isPartOf']['@id'])->toBe('https://techibytesmedia.test/#website')
        ->and($page['publisher']['@id'])->toBe('https://techibytesmedia.test/#organization');

    if ($hasBreadcrumbs) {
        $breadcrumb = $graph->firstWhere('@type', 'BreadcrumbList');

        expect($breadcrumb)->not->toBeNull()
            ->and($page['breadcrumb']['@id'])->toBe($breadcrumb['@id'])
            ->and($breadcrumb['itemListElement'][0]['item'])->toBe('https://techibytesmedia.test/');
    } else {
        expect($types)->not->toContain('BreadcrumbList');
    }
})->with([
    'home' => ['home', [], 'WebPage', false],
    'services' => ['services', [], 'CollectionPage', true],
    'service page' => ['services.show', ['web-development'], 'WebPage', true],
    'graphics' => ['graphics', [], 'WebPage', true],
    'portfolio' => ['portfolio', [], 'CollectionPage', true],
    'case study' => ['portfolio.show', ['topfreshcuts'], 'WebPage', true],
    'blog' => ['blog', [], 'CollectionPage', true],
    'contact' => ['contact', [], 'ContactPage', true],
    'terms' => ['terms', [], 'WebPage', true],
]);

test('the organization describes the business for every market it serves', function (): void {
    config()->set('app.url', 'https://techibytesmedia.test');

    $this->get(route('services'))
        ->assertSuccessful()
        ->assertSee('"foundingDate":"2020"', false)
        ->assertSee('"name":"Elijah Erigbemi"', false)
        ->assertSee('"name":"Texas"', false)
        ->assertSee('"name":"Seattle"', false)
        ->assertSee('"hasOfferCatalog"', false)
        ->assertSee('"url":"https://techibytesmedia.test/services/media-ad"', false);
});

test('the services overview marks up its service list and FAQ', function (): void {
    $this->get(route('services'))
        ->assertSuccessful()
        ->assertSee('"@type":"ItemList"', false)
        ->assertSee('"@type":"FAQPage"', false)
        ->assertSee('"name":"How much does a project cost?"', false);
});

test('the work page lists every case study in its structured data', function (): void {
    config()->set('app.url', 'https://techibytesmedia.test');

    $response = $this->get(route('portfolio'))->assertSuccessful();

    foreach (array_keys(config('projects.items')) as $project_key) {
        $response->assertSee('"url":"https://techibytesmedia.test/portfolio/' . $project_key . '"', false);
    }
});

test('pages never show raw internal link markup', function (string $path): void {
    $this->get($path)
        ->assertSuccessful()
        ->assertDontSee('[[', false)
        ->assertDontSee(']]', false);
})->with([
    '/services/web-development',
    '/services/software-development',
    '/services/mobile-app-development',
    '/services/brand-strategy',
    '/services/media-ad',
    '/services/seo',
    '/portfolio/billswaka',
    '/portfolio/scholarly',
    '/portfolio/sendbit',
    '/portfolio/topfreshcuts',
    '/portfolio/hofashionhub',
    '/portfolio/st-michaels-afh',
    '/portfolio/ibuildinitiative',
    '/portfolio/senator-osita-izunaso',
]);

test('pages with a custom image use the large social card', function (): void {
    $this->get(route('portfolio.show', 'topfreshcuts'))
        ->assertSuccessful()
        ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
});
