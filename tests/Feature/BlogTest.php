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

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    config()->set([
        'app.url' => 'https://techibytesmedia.test',
        'services.wordpress.url' => 'https://cms.example.com/',
        'services.wordpress.cache_seconds' => 600,
        'services.wordpress.webhook_secret' => 'webhook-secret',
    ]);

    Http::preventStrayRequests();
});

test('the blog lists posts pulled from WordPress', function (): void {
    fake_wordpress([wordpress_post()]);

    $this->get(route('blog'))
        ->assertSuccessful()
        ->assertSee('How Much Does a Website Cost in Seattle?')
        ->assertSee('A practical breakdown of website pricing.')
        ->assertSee(route('blog.show', 'website-cost-seattle'), false)
        ->assertSee('https://cms.example.com/uploads/cover-1024.jpg', false)
        ->assertSee('Web Development')
        ->assertDontSee('Uncategorized');

    Http::assertSent(fn (Request $request): bool => str_starts_with($request->url(), 'https://cms.example.com/wp-json/wp/v2/posts')
        && '1' === (string) $request['page']
        && str_contains((string) $request['_embed'], 'wp:featuredmedia'));
});

test('blog posts render on this site with article metadata', function (): void {
    fake_wordpress([wordpress_post()]);

    $this->get(route('blog.show', 'website-cost-seattle'))
        ->assertSuccessful()
        ->assertSee('<title>Website Cost in Seattle (2026) | Techibytes Media</title>', false)
        ->assertSee('<meta name="description" content="What a custom website costs in Seattle.">', false)
        ->assertSee('<link rel="canonical" href="https://techibytesmedia.test/blog/website-cost-seattle">', false)
        ->assertSee('<meta property="og:type" content="article">', false)
        ->assertSee('"@type":"BlogPosting"', false)
        ->assertSee('<h2>What affects the price</h2>', false)
        ->assertSee('Ada Writer')
        ->assertSee('"@type":"BlogPosting"', false)
        ->assertSee('"wordCount"', false)
        ->assertSee('data-testid="related-service"', false)
        ->assertSee(route('services.show', 'web-development'), false);
});

test('posts are cached so WordPress is not called on every visit', function (): void {
    fake_wordpress([wordpress_post()]);

    $this->get(route('blog'))->assertSuccessful();
    $this->get(route('blog'))->assertSuccessful();

    Http::assertSentCount(1);
});

test('WordPress can clear the blog cache with the shared secret', function (): void {
    fake_wordpress([wordpress_post()]);

    $this->get(route('blog'))->assertSuccessful();

    $this->postJson(route('blog.refresh'), [], ['X-Webhook-Secret' => 'wrong'])->assertForbidden();
    $this->postJson(route('blog.refresh'), [], ['X-Webhook-Secret' => 'webhook-secret'])
        ->assertSuccessful()
        ->assertJson(['refreshed' => true]);

    $this->get(route('blog'))->assertSuccessful();

    Http::assertSentCount(2);
});

test('the refresh endpoint is closed when no secret is configured', function (): void {
    config()->set('services.wordpress.webhook_secret', null);

    $this->postJson(route('blog.refresh'), [], ['X-Webhook-Secret' => ''])->assertForbidden();
});

test('an unknown post returns not found', function (): void {
    fake_wordpress([]);

    $this->get(route('blog.show', 'missing-post'))->assertNotFound();
});

test('the blog shows a friendly message when WordPress is down and does not cache the failure', function (): void {
    $wordpress_is_down = true;

    Http::fake(function () use (&$wordpress_is_down) {
        return $wordpress_is_down
            ? Http::failedConnection()
            : Http::response([wordpress_post()], 200, ['X-WP-TotalPages' => '1']);
    });

    $this->get(route('blog'))
        ->assertSuccessful()
        ->assertSee('The blog is taking a short break');

    $this->get(route('blog.show', 'website-cost-seattle'))->assertServiceUnavailable();

    $wordpress_is_down = false;

    $this->get(route('blog'))
        ->assertSuccessful()
        ->assertSee('How Much Does a Website Cost in Seattle?');
});

test('the blog shows an empty state when WordPress is not configured', function (): void {
    config()->set('services.wordpress.url', null);

    $this->get(route('blog'))
        ->assertSuccessful()
        ->assertSee('data-testid="blog-empty"', false);

    Http::assertNothingSent();
});

test('pages past the last page return not found', function (): void {
    Http::fake(['cms.example.com/*' => Http::response(['code' => 'rest_post_invalid_page_number'], 400)]);

    $this->get(route('blog', ['page' => 5]))->assertNotFound();
});

test('blog posts are listed in the sitemap', function (): void {
    Http::fake(['cms.example.com/*' => Http::response([
        ['slug' => 'website-cost-seattle', 'modified_gmt' => '2026-11-20T10:00:00'],
    ])]);

    $this->get(route('sitemap'))
        ->assertSuccessful()
        ->assertSee('<loc>https://techibytesmedia.test/blog/website-cost-seattle</loc>', false)
        ->assertSee('<lastmod>2026-11-20</lastmod>', false);
});

/**
 * @param  list<array<string, mixed>>  $posts
 */
function fake_wordpress(array $posts): void
{
    Http::fake(function (Request $request) use ($posts) {
        $slug = $request['slug'] ?? null;
        $matching = null === $slug
            ? $posts
            : array_values(array_filter($posts, fn (array $post): bool => $post['slug'] === $slug));

        return Http::response($matching, 200, ['X-WP-TotalPages' => '1', 'X-WP-Total' => (string) count($matching)]);
    });
}

/**
 * @return array<string, mixed>
 */
function wordpress_post(): array
{
    return [
        'slug' => 'website-cost-seattle',
        'date_gmt' => '2026-11-18T09:00:00',
        'modified_gmt' => '2026-11-20T10:00:00',
        'title' => ['rendered' => 'How Much Does a Website Cost in Seattle?'],
        'excerpt' => ['rendered' => '<p>A practical breakdown of website pricing. [&hellip;]</p>'],
        'content' => ['rendered' => '<p>Intro paragraph.</p><h2>What affects the price</h2><p>Scope, features and content.</p>'],
        'yoast_head_json' => [
            'title' => 'Website Cost in Seattle (2026) | Techibytes Media',
            'description' => 'What a custom website costs in Seattle.',
        ],
        '_embedded' => [
            'author' => [['name' => 'Ada Writer']],
            'wp:featuredmedia' => [[
                'source_url' => 'https://cms.example.com/uploads/cover.jpg',
                'alt_text' => 'Laptop showing a website',
                'media_details' => ['sizes' => ['large' => ['source_url' => 'https://cms.example.com/uploads/cover-1024.jpg']]],
            ]],
            'wp:term' => [
                [
                    ['taxonomy' => 'category', 'name' => 'Web Development'],
                    ['taxonomy' => 'category', 'name' => 'Uncategorized'],
                ],
                [['taxonomy' => 'post_tag', 'name' => 'pricing']],
            ],
        ],
    ];
}
