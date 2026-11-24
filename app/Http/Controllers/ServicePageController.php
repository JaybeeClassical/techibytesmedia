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

use Illuminate\Contracts\View\View;

class ServicePageController extends Controller
{
    public function show(string $service): View
    {
        /** @var array<string, array<string, mixed>> $services */
        $services = config('service-pages.items', []);

        abort_unless(isset($services[$service]), 404);

        /** @var array<string, array<string, mixed>> $projects */
        $projects = config('projects.items', []);

        $related_projects = collect($services[$service]['projects'] ?? [])
            ->filter(fn (string $key): bool => isset($projects[$key]))
            ->mapWithKeys(fn (string $key): array => [$key => $projects[$key]]);

        $links = collect($services)
            ->map(fn (array $item, string $key): array => [
                'key' => $key,
                'label' => $item['nav_label'],
                'summary' => $item['nav_summary'],
                'url' => route('services.show', $key),
            ])
            ->put('graphics', [
                'key' => 'graphics',
                'label' => 'Graphic Design',
                'summary' => 'Logos, social and print design',
                'url' => route('graphics'),
            ])
            ->except($service);

        $related_keys = (array) ($services[$service]['related'] ?? []);

        return view('pages.service', [
            'slug' => $service,
            'service' => $services[$service],
            'relatedProjects' => $related_projects,
            'relatedServices' => $links->only($related_keys)->sortBy(fn (array $link): int => (int) array_search($link['key'], $related_keys, true))->values(),
            'otherServices' => $links->except($related_keys)->values(),
        ]);
    }
}
