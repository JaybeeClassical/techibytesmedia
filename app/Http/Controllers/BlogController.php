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

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Services\WordPress\WordPressBlog;
use App\Services\WordPress\WordPressUnavailableException;

class BlogController extends Controller
{
    public function __construct(private readonly WordPressBlog $blog) {}

    public function index(Request $request): View
    {
        $page = max(1, (int) $request->query('page', '1'));
        $unavailable = false;

        try {
            $result = $this->blog->posts($page);
        } catch (WordPressUnavailableException $exception) {
            report($exception);
            $result = ['posts' => [], 'total_pages' => 0];
            $unavailable = true;
        }

        abort_if($page > 1 && [] === $result['posts'] && ! $unavailable, 404);

        return view('pages.blog.index', [
            'posts' => $result['posts'],
            'page' => $page,
            'totalPages' => $result['total_pages'],
            'unavailable' => $unavailable,
        ]);
    }

    public function show(string $slug): View
    {
        try {
            $post = $this->blog->post($slug);
        } catch (WordPressUnavailableException $exception) {
            report($exception);
            abort(503, 'The blog is temporarily unavailable. Please try again shortly.');
        }

        abort_if(null === $post, 404);

        return view('pages.blog.show', ['post' => $post]);
    }

    /**
     * Called by WordPress when a post is published, updated or deleted.
     */
    public function refresh(Request $request): JsonResponse
    {
        $secret = (string) config('services.wordpress.webhook_secret');
        $provided = (string) $request->header('X-Webhook-Secret');

        abort_if('' === $secret || ! hash_equals($secret, $provided), 403);

        $this->blog->flush();

        return response()->json(['refreshed' => true]);
    }
}
