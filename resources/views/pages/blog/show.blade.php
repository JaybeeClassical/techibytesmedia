@use('App\Support\StructuredData')

@php
    $pageUrl = StructuredData::url(route('blog.show', $post->slug, absolute: false));

    $breadcrumbs = [
        ['name' => 'Blog', 'url' => route('blog')],
        ['name' => $post->title, 'url' => route('blog.show', $post->slug)],
    ];

    $posting = array_filter([
        '@type' => 'BlogPosting',
        '@id' => $pageUrl.'#article',
        'headline' => \Illuminate\Support\Str::limit($post->title, 110, ''),
        'description' => $post->metaDescription(),
        'url' => $pageUrl,
        'mainEntityOfPage' => ['@id' => $pageUrl.'#webpage'],
        'isPartOf' => ['@id' => StructuredData::url(route('blog', absolute: false)).'#blog'],
        'datePublished' => $post->publishedAt->toAtomString(),
        'dateModified' => $post->modifiedAt->toAtomString(),
        'image' => $post->imageUrl ? ['@type' => 'ImageObject', 'url' => $post->imageUrl] : null,
        'author' => $post->author
            ? ['@type' => 'Person', 'name' => $post->author, 'worksFor' => ['@id' => StructuredData::organizationId()]]
            : ['@id' => StructuredData::organizationId()],
        'publisher' => ['@id' => StructuredData::organizationId()],
        'articleSection' => $post->categories[0] ?? null,
        'keywords' => $post->categories !== [] ? implode(', ', $post->categories) : null,
        'wordCount' => str_word_count(strip_tags($post->content)),
        'timeRequired' => 'PT'.$post->readingMinutes().'M',
        'inLanguage' => 'en',
    ], fn (mixed $value): bool => null !== $value);

    // Point readers to the services this article is about: match WordPress categories
    // to service names, then fall back to the core services
    $services = collect(config('service-pages.items', []));
    $categoryText = \Illuminate\Support\Str::lower(implode(' ', $post->categories).' '.$post->title);
    $relatedServices = $services
        ->filter(function (array $service) use ($categoryText): bool {
            $words = collect(preg_split('/[^a-z]+/', \Illuminate\Support\Str::lower($service['nav_label'].' '.$service['eyebrow'])))
                ->reject(fn (string $word): bool => mb_strlen($word) < 3 || in_array($word, ['and', 'services', 'company', 'agency', 'development'], true));

            return $words->contains(fn (string $word): bool => str_contains($categoryText, $word));
        })
        ->union($services->only(['web-development', 'seo', 'media-ad']))
        ->take(3);
@endphp

<x-layout
    :title="$post->seoTitle ?? $post->title.' | Techibytes Media'"
    :description="$post->metaDescription()"
    :image="$post->imageUrl"
    type="article"
    :breadcrumbs="$breadcrumbs"
    :schema="[$posting]"
    :main-entity="$pageUrl.'#article'"
>
    @push('head')
        <meta property="article:published_time" content="{{ $post->publishedAt->toAtomString() }}">
        <meta property="article:modified_time" content="{{ $post->modifiedAt->toAtomString() }}">
        @foreach ($post->categories as $category)
            <meta property="article:section" content="{{ $category }}">
        @endforeach
    @endpush

    <article>
        <header class="relative overflow-hidden pt-36 pb-12 sm:pt-44 sm:pb-16">
            <x-hero-grid />

            <div class="relative mx-auto max-w-3xl px-5 sm:px-8">
                <x-breadcrumbs :items="$breadcrumbs" />

                @if ($post->categories !== [])
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-accent">{{ implode(' · ', $post->categories) }}</p>
                @endif
                <h1 class="mt-5 font-display text-[clamp(2.25rem,5.5vw,4rem)] font-extrabold leading-[1.05]">{{ $post->title }}</h1>

                <div class="mt-8 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-muted">
                    @if ($post->author)
                        <span class="font-semibold text-bone">{{ $post->author }}</span>
                        <span aria-hidden="true">&middot;</span>
                    @endif
                    <time datetime="{{ $post->publishedAt->toAtomString() }}">{{ $post->publishedAt->format('F j, Y') }}</time>
                    <span aria-hidden="true">&middot;</span>
                    <span>{{ $post->readingMinutes() }} min read</span>
                </div>
            </div>
        </header>

        @if ($post->imageUrl)
            <div class="mx-auto max-w-5xl px-5 sm:px-8">
                <img
                    src="{{ $post->imageUrl }}"
                    alt="{{ $post->imageAlt ?? '' }}"
                    decoding="async"
                    fetchpriority="high"
                    class="aspect-[16/9] w-full rounded-2xl border border-line object-cover"
                >
            </div>
        @endif

        {{-- Post body is HTML written in WordPress --}}
        <div data-testid="blog-content" class="blog-content mx-auto max-w-3xl px-5 py-16 sm:px-8 sm:py-20">
            {!! $post->content !!}
        </div>
    </article>

    {{-- Related services: links from the article to the matching service pages --}}
    <section class="border-t border-line bg-panel">
        <div class="mx-auto max-w-7xl px-5 py-20 sm:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-muted">Need help with this?</p>
            <h2 class="mt-4 font-display text-[clamp(1.75rem,4vw,2.75rem)] font-extrabold">Services related to this article</h2>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($relatedServices as $serviceSlug => $relatedService)
                    <a href="{{ route('services.show', $serviceSlug) }}" data-testid="related-service" class="group rounded-2xl border border-line bg-ink p-7 transition-colors hover:border-accent/50">
                        <h3 class="font-display text-xl font-bold transition-colors group-hover:text-accent">{{ $relatedService['nav_label'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-muted">{{ $relatedService['nav_summary'] }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-10 flex flex-wrap items-center justify-between gap-6">
                <a href="{{ route('blog') }}" class="link-draw font-semibold text-bone">&larr; All articles</a>
                <a href="{{ route('portfolio') }}" class="link-draw font-semibold text-bone">See our work &rarr;</a>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-primary text-white">
        <div class="relative mx-auto max-w-7xl px-5 py-24 text-center sm:px-8 sm:py-28">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-accent-soft">Need a hand?</p>
            <h2 class="mt-6 font-display text-[clamp(2rem,5vw,3.75rem)] font-extrabold leading-[1.05]">
                Let's build something <span class="text-accent-soft">great together.</span>
            </h2>
            <div class="mt-10">
                <a href="{{ route('contact') }}" class="group inline-flex items-center gap-3 rounded-full bg-white px-9 py-5 text-lg font-semibold text-primary transition-transform hover:scale-105">
                    Book a free consultation
                    <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

</x-layout>
