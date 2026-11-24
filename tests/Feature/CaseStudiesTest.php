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

test('every project has a case study page on this site that links out to the live website', function (): void {
    config()->set('app.url', 'https://techibytesmedia.test');

    foreach (config('projects.items') as $project_key => $project) {
        $path = "/portfolio/{$project_key}";

        $this->get($path)
            ->assertSuccessful()
            ->assertSee($project['title'])
            ->assertSee($project['result'])
            ->assertSee($project['screenshot'], false)
            ->assertSee('<link rel="canonical" href="https://techibytesmedia.test' . $path . '">', false)
            ->assertSee('data-testid="live-site-link"', false)
            ->assertSee('href="' . $project['url'] . '"', false)
            ->assertSee('target="_blank"', false)
            ->assertSee('"@type":"CreativeWork"', false);
    }
});

test('every project has case study content', function (): void {
    foreach (array_keys(config('projects.items')) as $project_key) {
        expect(config("case-studies.{$project_key}"))
            ->toBeArray()
            ->toHaveKeys(['overview', 'challenge', 'solution', 'services']);
    }
});

test('case studies link to the service pages that delivered them', function (): void {
    $this->get(route('portfolio.show', 'topfreshcuts'))
        ->assertSuccessful()
        ->assertSee('Des Moines, WA')
        ->assertSee(route('services.show', 'web-development'), false);
});

test('case studies show the client testimonial when there is one', function (): void {
    $this->get(route('portfolio.show', 'scholarly'))
        ->assertSuccessful()
        ->assertSee('Uche Umeh')
        ->assertSee('CEO, Scholarly Africa');
});

test('an unknown project returns not found', function (): void {
    $this->get('/portfolio/not-a-client')->assertNotFound();
});

test('case studies link to similar case studies and to services inside the write-up', function (): void {
    $response = $this->get(route('portfolio.show', 'topfreshcuts'))
        ->assertSuccessful()
        ->assertSee('More work like this')
        ->assertSee('<a href="' . route('services.show', 'web-development') . '" class="link-draw font-semibold text-accent">barbershop website</a>', false)
        ->assertSee('"@type":"CreativeWork"', false)
        ->assertSee('"mentions"', false);

    expect(mb_substr_count((string) $response->getContent(), 'data-testid="related-case-study"'))->toBeGreaterThan(0)->toBeLessThanOrEqual(3);
});

test('home page testimonials link to their case studies', function (): void {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee(route('portfolio.show', 'scholarly'), false)
        ->assertSee(route('portfolio.show', 'billswaka'), false);
});
