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

use Illuminate\Support\HtmlString;

/**
 * In-text links for copy stored in config files.
 *
 * Write [[target|link text]] inside a sentence. Targets:
 *   - a service page key from config/service-pages.php, e.g. [[seo|SEO services]]
 *   - graphics, services, portfolio, blog or contact
 *   - portfolio:{project}, e.g. [[portfolio:topfreshcuts|a Des Moines barbershop]]
 *
 * Unknown targets render as plain text, so a typo never breaks a page.
 */
final class InternalLinks
{
    private const string PATTERN = '/\[\[([a-z0-9:-]+)\|([^\]]+)\]\]/';

    private const array PAGES = ['graphics', 'services', 'portfolio', 'blog', 'contact'];

    public static function render(string $text, string $class = 'link-draw font-semibold text-accent'): HtmlString
    {
        $parts = preg_split(self::PATTERN, $text, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [$text];
        $html = '';

        // preg_split with captures yields: text, target, anchor, text, target, anchor, ...
        for ($i = 0, $count = count($parts); $i < $count; $i += 3) {
            $html .= e($parts[$i]);

            if ( ! isset($parts[$i + 1], $parts[$i + 2])) {
                continue;
            }

            $url = self::url($parts[$i + 1]);
            $anchor = e($parts[$i + 2]);

            $html .= null === $url ? $anchor : '<a href="' . e($url) . '" class="' . e($class) . '">' . $anchor . '</a>';
        }

        return new HtmlString($html);
    }

    /**
     * The same text with the link markup removed (for meta tags and schema).
     */
    public static function plain(string $text): string
    {
        return (string) preg_replace(self::PATTERN, '$2', $text);
    }

    public static function url(string $target): ?string
    {
        if (str_starts_with($target, 'portfolio:')) {
            $project = mb_substr($target, mb_strlen('portfolio:'));

            return null !== config("projects.items.{$project}") ? route('portfolio.show', $project) : null;
        }

        if (in_array($target, self::PAGES, true)) {
            return route($target);
        }

        return null !== config("service-pages.items.{$target}") ? route('services.show', $target) : null;
    }
}
