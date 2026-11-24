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

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Response;
use App\Services\WordPress\WordPressBlog;

class SitemapController extends Controller
{
    public function __invoke(WordPressBlog $blog): Response
    {
        $site_url = mb_rtrim((string) config('app.url'), '/');
        $absolute = fn (string $path): string => $site_url . ('/' === $path ? '/' : '/' . mb_ltrim($path, '/'));

        $urls = collect(['home', 'services', 'portfolio', 'graphics', 'blog', 'contact', 'terms', 'privacy', 'payment-services-disclosure'])
            ->map(fn (string $route_name): array => ['loc' => $absolute(route($route_name, absolute: false)), 'lastmod' => null]);

        foreach (array_keys((array) config('service-pages.items', [])) as $service) {
            $urls->push(['loc' => $absolute(route('services.show', $service, absolute: false)), 'lastmod' => null]);
        }

        foreach (array_keys((array) config('projects.items', [])) as $project) {
            $urls->push(['loc' => $absolute(route('portfolio.show', $project, absolute: false)), 'lastmod' => null]);
        }

        foreach ($blog->sitemapEntries() as $entry) {
            $urls->push([
                'loc' => $absolute(route('blog.show', $entry['slug'], absolute: false)),
                'lastmod' => '' === $entry['modified'] ? null : CarbonImmutable::parse($entry['modified'], 'UTC')->toDateString(),
            ]);
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
