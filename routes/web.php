<?php

/*
 *
 *   Created by Techibytes Media Development Team
 *   Copyright Ⓒ 2026. All rights reserved, https://techibytesmedia.com/
 *   Project: techibytesmedia
 *   Last modified: 7/30/26, 1:52 PM
 *   Modified or Created by: erigb
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

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\ProtectContactForm;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ServicePageController;

Route::view('/', 'pages.home')->name('home');
Route::view('/services', 'pages.services')->name('services');
Route::get('/services/{service}', [ServicePageController::class, 'show'])
    ->where('service', '[a-z0-9-]+')
    ->name('services.show');
Route::view('/portfolio', 'pages.portfolio')->name('portfolio');
Route::get('/portfolio/{project}', [CaseStudyController::class, 'show'])
    ->where('project', '[a-z0-9-]+')
    ->name('portfolio.show');
Route::view('/graphics', 'pages.graphics')->name('graphics');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/payment-services-disclosure', 'pages.payment-services-disclosure')->name('payment-services-disclosure');

Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::post('/blog/refresh', [BlogController::class, 'refresh'])
    ->middleware('throttle:30,1')
    ->name('blog.refresh');
Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->where('slug', '[A-Za-z0-9%_-]+')
    ->name('blog.show');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware(['throttle:contact-form', ProtectContactForm::class])
    ->name('contact.submit');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
