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

use Illuminate\Support\Arr;
use Carbon\CarbonImmutable;

/**
 * A published WordPress post, mapped from the WP REST API (with _embed).
 */
final readonly class BlogPost
{
    /**
     * @param  list<string>  $categories
     */
    public function __construct(
        public string $slug,
        public string $title,
        public string $excerpt,
        public string $content,
        public CarbonImmutable $publishedAt,
        public CarbonImmutable $modifiedAt,
        public ?string $author,
        public ?string $imageUrl,
        public ?string $imageAlt,
        public array $categories,
        public ?string $seoTitle,
        public ?string $seoDescription,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromApi(array $data): self
    {
        $media = Arr::get($data, '_embedded.wp:featuredmedia.0');
        $image_url = is_array($media)
            ? (Arr::get($media, 'media_details.sizes.large.source_url') ?? Arr::get($media, 'source_url'))
            : null;

        $categories = collect(Arr::get($data, '_embedded.wp:term', []))
            ->flatten(1)
            ->filter(fn (mixed $term): bool => is_array($term) && 'category' === ($term['taxonomy'] ?? null))
            ->map(fn (array $term): string => self::plainText((string) ($term['name'] ?? '')))
            ->reject(fn (string $name): bool => '' === $name || 'Uncategorized' === $name)
            ->values()
            ->all();

        return new self(
            slug: (string) $data['slug'],
            title: self::plainText((string) Arr::get($data, 'title.rendered', '')),
            excerpt: mb_rtrim(self::plainText((string) Arr::get($data, 'excerpt.rendered', '')), ' […]'),
            content: (string) Arr::get($data, 'content.rendered', ''),
            publishedAt: self::utcDate((string) ($data['date_gmt'] ?? $data['date'] ?? 'now')),
            modifiedAt: self::utcDate((string) ($data['modified_gmt'] ?? $data['date_gmt'] ?? 'now')),
            author: self::nullableText(Arr::get($data, '_embedded.author.0.name')),
            imageUrl: is_string($image_url) && '' !== $image_url ? $image_url : null,
            imageAlt: is_array($media) ? self::nullableText(Arr::get($media, 'alt_text')) : null,
            categories: $categories,
            seoTitle: self::nullableText(Arr::get($data, 'yoast_head_json.title')),
            seoDescription: self::nullableText(Arr::get($data, 'yoast_head_json.description')),
        );
    }

    public function readingMinutes(): int
    {
        $words = str_word_count(self::plainText($this->content));

        return max(1, (int) ceil($words / 220));
    }

    public function metaDescription(): string
    {
        return $this->seoDescription ?? str($this->excerpt)->limit(155)->toString();
    }

    private static function plainText(string $html): string
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return mb_trim((string) preg_replace('/\s+/u', ' ', $text));
    }

    private static function nullableText(mixed $value): ?string
    {
        if ( ! is_string($value)) {
            return null;
        }

        $text = self::plainText($value);

        return '' === $text ? null : $text;
    }

    private static function utcDate(string $value): CarbonImmutable
    {
        return CarbonImmutable::parse($value, 'UTC');
    }
}
