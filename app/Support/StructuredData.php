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

namespace App\Support;

/**
 * Builds schema.org JSON-LD for every page.
 *
 * Each page gets one connected @graph: the Organization and WebSite (shared
 * across the site through stable @ids), the page itself, its breadcrumb
 * trail and any page-specific entities (services, FAQs, case studies,
 * blog posts). Linking nodes by @id lets search engines understand that
 * every page, service and article belongs to the same business.
 */
final class StructuredData
{
    public static function siteUrl(): string
    {
        return mb_rtrim((string) config('app.url'), '/');
    }

    /**
     * Turn a path or a full URL on this site into an absolute URL on the canonical domain.
     */
    public static function url(string $pathOrUrl): string
    {
        $path = (string) (parse_url($pathOrUrl, PHP_URL_PATH) ?? '/');
        $query = parse_url($pathOrUrl, PHP_URL_QUERY);

        return self::siteUrl() . ('/' === $path || '' === $path ? '/' : '/' . mb_ltrim($path, '/')) . ($query ? '?' . $query : '');
    }

    public static function organizationId(): string
    {
        return self::siteUrl() . '/#organization';
    }

    public static function websiteId(): string
    {
        return self::siteUrl() . '/#website';
    }

    /**
     * @return list<array<string, string>>
     */
    public static function areasServed(): array
    {
        return [
            ['@type' => 'City', 'name' => 'Seattle', 'containedInPlace' => ['@type' => 'State', 'name' => 'Washington']],
            ['@type' => 'State', 'name' => 'Washington'],
            ['@type' => 'State', 'name' => 'Texas'],
            ['@type' => 'Country', 'name' => 'United States'],
            ['@type' => 'City', 'name' => 'Abuja'],
            ['@type' => 'Country', 'name' => 'Nigeria'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        $site = self::siteUrl();
        $logo = $site . '/images/logo/logo-background.png';

        /** @var array<string, array<string, mixed>> $services */
        $services = config('service-pages.items', []);

        $offers = collect($services)
            ->map(fn (array $service, string $slug): array => [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    '@id' => self::url(route('services.show', $slug, absolute: false)) . '#service',
                    'name' => $service['nav_label'],
                    'url' => self::url(route('services.show', $slug, absolute: false)),
                ],
            ])
            ->push([
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    '@id' => self::url(route('graphics', absolute: false)) . '#service',
                    'name' => 'Graphic Design & Branding',
                    'url' => self::url(route('graphics', absolute: false)),
                ],
            ])
            ->values()
            ->all();

        return [
            '@type' => 'Organization',
            '@id' => self::organizationId(),
            'name' => 'Techibytes Media',
            'legalName' => 'Techibytes Media LLC',
            'alternateName' => 'Techibytes',
            'url' => $site . '/',
            'logo' => [
                '@type' => 'ImageObject',
                '@id' => $site . '/#logo',
                'url' => $logo,
                'contentUrl' => $logo,
                'caption' => 'Techibytes Media',
            ],
            'image' => ['@id' => $site . '/#logo'],
            'description' => 'A web development, software development, branding, media and digital marketing agency serving businesses in Seattle, Texas and across the US, with a second team in Abuja, Nigeria.',
            'slogan' => 'We build digital products that move business forward.',
            'foundingDate' => '2020',
            'founder' => [
                ['@type' => 'Person', 'name' => 'Elijah Erigbemi', 'jobTitle' => 'Co-founder'],
                ['@type' => 'Person', 'name' => 'Jude Obiejesi', 'jobTitle' => 'Co-founder'],
            ],
            'email' => 'info@techibytesmedia.com',
            'telephone' => '+2349031807262',
            'contactPoint' => [
                [
                    '@type' => 'ContactPoint',
                    'telephone' => '+2349031807262',
                    'email' => 'info@techibytesmedia.com',
                    'contactType' => 'customer service',
                    'areaServed' => ['US', 'NG'],
                    'availableLanguage' => 'en',
                    'url' => self::url(route('contact', absolute: false)),
                ],
            ],
            'address' => [
                [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Suite 303, 3rd Floor, Ammah Plaza, Near NAF Conference Center',
                    'addressLocality' => 'Abuja',
                    'addressCountry' => 'NG',
                ],
                [
                    '@type' => 'PostalAddress',
                    'streetAddress' => '600 1st Ave, Ste 102 #1105',
                    'addressLocality' => 'Seattle',
                    'addressRegion' => 'WA',
                    'postalCode' => '98104',
                    'addressCountry' => 'US',
                ],
            ],
            'areaServed' => self::areasServed(),
            'knowsAbout' => [
                'Web development', 'Custom website design', 'Custom software development', 'Web app development',
                'Mobile app development', 'Brand strategy', 'Branding', 'Graphic design', 'Media buying',
                'Digital marketing', 'Search engine optimization', 'Local SEO', 'UI/UX design',
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Techibytes Media services',
                'itemListElement' => $offers,
            ],
            'sameAs' => [
                'https://www.facebook.com/techibytesmedia',
                'https://x.com/techibytes_hq',
                'https://www.instagram.com/techibytes',
                'https://www.linkedin.com/company/techibytesmedia',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => self::websiteId(),
            'url' => self::siteUrl() . '/',
            'name' => 'Techibytes Media',
            'description' => 'Web development, software, mobile apps, branding, SEO and media services for businesses in the US and Nigeria.',
            'publisher' => ['@id' => self::organizationId()],
            'inLanguage' => 'en',
        ];
    }

    /**
     * @param  list<array{name: string, url: string}>  $breadcrumbs
     * @return array<string, mixed>
     */
    public static function webPage(
        string $url,
        string $type,
        string $name,
        string $description,
        ?string $image,
        array $breadcrumbs,
        ?string $mainEntityId,
    ): array {
        $page = [
            '@type' => $type,
            '@id' => $url . '#webpage',
            'url' => $url,
            'name' => $name,
            'description' => $description,
            'inLanguage' => 'en',
            'isPartOf' => ['@id' => self::websiteId()],
            'publisher' => ['@id' => self::organizationId()],
        ];

        if ($url === self::siteUrl() . '/') {
            $page['about'] = ['@id' => self::organizationId()];
        }

        if (null !== $image) {
            $page['primaryImageOfPage'] = ['@type' => 'ImageObject', 'url' => $image];
        }

        if ([] !== $breadcrumbs) {
            $page['breadcrumb'] = ['@id' => $url . '#breadcrumb'];
        }

        if (null !== $mainEntityId) {
            $page['mainEntity'] = ['@id' => $mainEntityId];
        }

        return $page;
    }

    /**
     * @param  list<array{name: string, url: string}>  $items  Trail after "Home"; the last item is the current page
     * @return array<string, mixed>
     */
    public static function breadcrumbList(string $pageUrl, array $items): array
    {
        $trail = [['name' => 'Home', 'url' => self::siteUrl() . '/'], ...$items];

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $pageUrl . '#breadcrumb',
            'itemListElement' => collect($trail)->values()->map(fn (array $crumb, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'],
                'item' => self::url($crumb['url']),
            ])->all(),
        ];
    }

    /**
     * @param  list<array{q: string, a: string}>  $faqs
     * @return array<string, mixed>
     */
    public static function faqPage(string $pageUrl, array $faqs): array
    {
        return [
            '@type' => 'FAQPage',
            '@id' => $pageUrl . '#faq',
            'isPartOf' => ['@id' => $pageUrl . '#webpage'],
            'mainEntity' => collect($faqs)->map(fn (array $faq): array => [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => InternalLinks::plain($faq['a'])],
            ])->all(),
        ];
    }

    /**
     * @param  list<array{title: string, desc: string}>  $offerings
     * @return array<string, mixed>
     */
    public static function service(string $pageUrl, string $name, string $serviceType, string $description, array $offerings, ?string $image = null): array
    {
        return array_filter([
            '@type' => 'Service',
            '@id' => $pageUrl . '#service',
            'name' => $name,
            'serviceType' => $serviceType,
            'description' => $description,
            'url' => $pageUrl,
            'image' => $image,
            'provider' => ['@id' => self::organizationId()],
            'brand' => ['@id' => self::organizationId()],
            'areaServed' => self::areasServed(),
            'audience' => ['@type' => 'BusinessAudience', 'audienceType' => 'Startups, small and medium businesses, and public organisations'],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => $name,
                'itemListElement' => collect($offerings)->map(fn (array $offering): array => [
                    '@type' => 'Offer',
                    'itemOffered' => ['@type' => 'Service', 'name' => $offering['title'], 'description' => $offering['desc']],
                ])->all(),
            ],
            'mainEntityOfPage' => ['@id' => $pageUrl . '#webpage'],
        ], fn (mixed $value): bool => null !== $value);
    }

    /**
     * @param  list<array{name: string, url: string}>  $items
     * @return array<string, mixed>
     */
    public static function itemList(string $id, string $name, array $items): array
    {
        return [
            '@type' => 'ItemList',
            '@id' => $id,
            'name' => $name,
            'numberOfItems' => count($items),
            'itemListElement' => collect($items)->values()->map(fn (array $item, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'url' => self::url($item['url']),
            ])->all(),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $nodes
     */
    public static function encode(array $nodes): string
    {
        return (string) json_encode(
            ['@context' => 'https://schema.org', '@graph' => array_values($nodes)],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG,
        );
    }
}
