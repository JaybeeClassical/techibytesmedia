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

test('each service has its own page targeting its search terms', function (string $slug, string $path, array $keywords): void {
    config()->set('app.url', 'https://techibytesmedia.test');

    $service = config("service-pages.items.{$slug}");

    $response = $this->get($path)
        ->assertSuccessful()
        ->assertSee('<title>' . e($service['meta_title']) . '</title>', false)
        ->assertSee('<link rel="canonical" href="https://techibytesmedia.test' . $path . '">', false)
        ->assertSee($service['heading'])
        ->assertSee('"@type":"Service"', false)
        ->assertSee('"@type":"FAQPage"', false)
        ->assertSee('"@type":"BreadcrumbList"', false);

    $content = mb_strtolower(html_entity_decode((string) $response->getContent()));

    foreach ($keywords as $keyword) {
        expect($content)->toContain(mb_strtolower($keyword));
    }

    foreach ($service['faqs'] as $faq) {
        $response->assertSee($faq['q']);
    }
})->with([
    'web development' => ['web-development', '/services/web-development', ['web development company', 'custom website design']],
    'software development' => ['software-development', '/services/software-development', ['custom software development', 'web app development']],
    'mobile apps' => ['mobile-app-development', '/services/mobile-app-development', ['mobile app developers']],
    'brand strategy' => ['brand-strategy', '/services/brand-strategy', ['brand strategy agency', 'branding agency']],
    'media' => ['media-ad', '/services/media-ad', ['media agency', 'media ad agency', 'digital marketing agency', 'media buying agency', 'media buying']],
    'seo' => ['seo', '/services/seo', ['seo services']],
]);

test('an unknown service returns not found', function (): void {
    $this->get('/services/time-travel')->assertNotFound();
});

test('service pages link to related case studies on this site', function (): void {
    $service = config('service-pages.items.web-development');

    $response = $this->get(route('services.show', 'web-development'))->assertSuccessful();

    foreach ($service['projects'] as $project_key) {
        $response->assertSee(route('portfolio.show', $project_key), false);
    }

    expect(mb_substr_count((string) $response->getContent(), 'data-testid="related-case-study"'))->toBe(count($service['projects']));
});

test('service page calls to action preselect the service on the contact form', function (): void {
    $this->get(route('services.show', 'seo'))
        ->assertSuccessful()
        ->assertSee(e(route('contact', ['service' => 'SEO'])), false);

    $this->get(route('contact', ['service' => 'SEO']))
        ->assertSuccessful()
        ->assertSee('<option value="SEO" selected>SEO</option>', false);
});

test('the services overview links to every service page', function (): void {
    $response = $this->get(route('services'))->assertSuccessful();

    foreach (array_keys(config('service-pages.items')) as $slug) {
        $response->assertSee(route('services.show', $slug), false);
    }

    $response->assertSee(route('graphics'), false);
});

test('the header services menu lists every service page and graphics', function (): void {
    $response = $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee('data-testid="services-dropdown"', false)
        ->assertSee('aria-controls="services-menu"', false)
        ->assertSee('View all services')
        ->assertSee(route('blog'), false);

    foreach (config('service-pages.items') as $slug => $service) {
        $response
            ->assertSee(route('services.show', $slug), false)
            ->assertSee($service['nav_label']);
    }

    $response->assertSee(route('graphics'), false);
});

test('the services menu highlights the current service', function (): void {
    $content = (string) $this->get(route('services.show', 'seo'))->assertSuccessful()->getContent();

    expect($content)->toMatch('/<details class="group\/services"\s+open/');

    $content = (string) $this->get(route('home'))->assertSuccessful()->getContent();

    expect($content)->not->toMatch('/<details class="group\/services"\s+open/');
});

test('service pages link to related services and other pages inside their copy', function (): void {
    $response = $this->get(route('services.show', 'web-development'))->assertSuccessful();

    foreach (config('service-pages.items.web-development.related') as $related) {
        $response->assertSee(route('services.show', $related), false);
    }

    expect(mb_substr_count((string) $response->getContent(), 'data-testid="related-service"'))->toBe(3);

    // In-text links written as [[target|text]] in config/service-pages.php
    $response
        ->assertSee('<a href="' . route('services.show', 'seo') . '" class="link-draw font-semibold text-accent">search-friendly structure</a>', false)
        ->assertSee('<a href="' . route('portfolio.show', 'topfreshcuts') . '" class="link-draw font-semibold text-accent">a barbershop in Des Moines</a>', false);
});

test('FAQ structured data uses plain text answers', function (): void {
    $this->get(route('services.show', 'web-development'))
        ->assertSuccessful()
        ->assertSee('including a barbershop in Des Moines and an adult family home in Tacoma', false);
});

test('the graphics page is marked up as a service and links to related services', function (): void {
    $response = $this->get(route('graphics'))
        ->assertSuccessful()
        ->assertSee('"@type":"Service"', false)
        ->assertSee('"name":"Graphic Design & Branding"', false);

    foreach (['brand-strategy', 'web-development', 'media-ad'] as $slug) {
        $response->assertSee(route('services.show', $slug), false);
    }
});
