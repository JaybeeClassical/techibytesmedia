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

namespace App\Services\WordPress;

use Closure;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\ConnectionException;

/**
 * Reads published posts from a WordPress site through its REST API.
 *
 * Responses are cached for services.wordpress.cache_seconds. WordPress can
 * clear the cache immediately by calling /blog/refresh (see flush()).
 * Failed requests are never cached, so the blog recovers as soon as
 * WordPress is reachable again.
 */
class WordPressBlog
{
    private const string VERSION_KEY = 'wordpress.blog.version';

    private const string EMBED = 'author,wp:featuredmedia,wp:term';

    public function isConfigured(): bool
    {
        return '' !== $this->baseUrl();
    }

    /**
     * @return array{posts: list<BlogPost>, total_pages: int}
     *
     * @throws WordPressUnavailableException
     */
    public function posts(int $page = 1): array
    {
        if ( ! $this->isConfigured()) {
            return ['posts' => [], 'total_pages' => 0];
        }

        $page = max(1, $page);

        /** @var array{posts: list<array<string, mixed>>, total_pages: int} $result */
        $result = $this->remember("posts.page.{$page}", function () use ($page): array {
            $response = $this->get('posts', [
                'page' => $page,
                'per_page' => (int) config('services.wordpress.per_page', 9),
                '_embed' => self::EMBED,
            ]);

            // WordPress answers 400 when the page number is past the last page
            if (400 === $response->status() && 'rest_post_invalid_page_number' === $response->json('code')) {
                return ['posts' => [], 'total_pages' => 0];
            }

            $this->ensureSuccessful($response);

            return [
                'posts' => $this->postList($response),
                'total_pages' => (int) $response->header('X-WP-TotalPages'),
            ];
        });

        return [
            'posts' => array_map(BlogPost::fromApi(...), $result['posts']),
            'total_pages' => $result['total_pages'],
        ];
    }

    /**
     * @throws WordPressUnavailableException
     */
    public function post(string $slug): ?BlogPost
    {
        if ( ! $this->isConfigured()) {
            return null;
        }

        /** @var array<string, mixed>|false $data */
        $data = $this->remember("post.{$slug}", function () use ($slug): array|false {
            $response = $this->get('posts', ['slug' => $slug, '_embed' => self::EMBED]);
            $this->ensureSuccessful($response);

            // Cache "not found" as false so unknown slugs do not hit WordPress every time
            return $this->postList($response)[0] ?? false;
        });

        return false === $data ? null : BlogPost::fromApi($data);
    }

    /**
     * Slugs and last-modified dates for the sitemap. Returns an empty list
     * when WordPress is unavailable so the sitemap still renders.
     *
     * @return list<array{slug: string, modified: string}>
     */
    public function sitemapEntries(): array
    {
        if ( ! $this->isConfigured()) {
            return [];
        }

        try {
            /** @var list<array{slug: string, modified: string}> */
            return $this->remember('sitemap', function (): array {
                $response = $this->get('posts', ['per_page' => 100, '_fields' => 'slug,modified_gmt']);
                $this->ensureSuccessful($response);

                return array_map(fn (array $post): array => [
                    'slug' => (string) $post['slug'],
                    'modified' => (string) ($post['modified_gmt'] ?? ''),
                ], $this->postList($response));
            });
        } catch (WordPressUnavailableException) {
            return [];
        }
    }

    /**
     * Invalidate every cached response so the next visit fetches fresh content.
     */
    public function flush(): void
    {
        Cache::forever(self::VERSION_KEY, $this->cacheVersion() + 1);
    }

    private function remember(string $key, Closure $callback): mixed
    {
        $version = $this->cacheVersion();
        $seconds = max(1, (int) config('services.wordpress.cache_seconds', 600));

        return Cache::remember("wordpress.blog.v{$version}.{$key}", $seconds, $callback);
    }

    private function cacheVersion(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    /**
     * @param  array<string, int|string>  $query
     *
     * @throws WordPressUnavailableException
     */
    private function get(string $endpoint, array $query): Response
    {
        try {
            return Http::acceptJson()
                ->connectTimeout(3)
                ->timeout(8)
                ->get($this->baseUrl() . '/wp-json/wp/v2/' . $endpoint, $query);
        } catch (ConnectionException $exception) {
            throw new WordPressUnavailableException('WordPress could not be reached.', previous: $exception);
        }
    }

    /**
     * @throws WordPressUnavailableException
     */
    private function ensureSuccessful(Response $response): void
    {
        if ( ! $response->successful() || ! is_array($response->json())) {
            throw new WordPressUnavailableException("WordPress returned HTTP {$response->status()}.");
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function postList(Response $response): array
    {
        return array_values(array_filter(
            (array) $response->json(),
            fn (mixed $post): bool => is_array($post) && isset($post['slug']) && is_string($post['slug']),
        ));
    }

    private function baseUrl(): string
    {
        return mb_rtrim((string) config('services.wordpress.url'), '/');
    }
}
