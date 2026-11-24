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

class CaseStudyController extends Controller
{
    public function show(string $project): View
    {
        /** @var array<string, array<string, mixed>> $projects */
        $projects = config('projects.items', []);

        abort_unless(isset($projects[$project]), 404);

        $keys = array_keys($projects);
        $next_key = $keys[(array_search($project, $keys, true) + 1) % count($keys)];

        /** @var array<string, array<string, mixed>> $services */
        $services = config('service-pages.items', []);
        $case_study = (array) config("case-studies.{$project}", []);

        $service_links = collect($case_study['services'] ?? [])
            ->map(fn (string $key): ?array => match (true) {
                'graphics' === $key => ['label' => 'Graphic Design & Branding', 'url' => route('graphics')],
                isset($services[$key]) => ['label' => $services[$key]['nav_label'], 'url' => route('services.show', $key)],
                default => null,
            })
            ->filter()
            ->values();

        // Up to three other projects that share a service with this one, so visitors (and crawlers)
        // can move between related case studies
        $own_services = (array) ($case_study['services'] ?? []);
        $related_case_studies = collect($projects)
            ->except([$project, $next_key])
            ->filter(fn (array $item, string $key): bool => [] !== array_intersect(
                $own_services,
                (array) config("case-studies.{$key}.services", []),
            ))
            ->take(3);

        return view('pages.case-study', [
            'key' => $project,
            'project' => $projects[$project],
            'caseStudy' => $case_study,
            'serviceLinks' => $service_links,
            'relatedCaseStudies' => $related_case_studies,
            'nextKey' => $next_key === $project ? null : $next_key,
            'nextProject' => $next_key === $project ? null : $projects[$next_key],
        ]);
    }
}
