@use('App\Support\StructuredData')

@php
    $siteUrl = rtrim((string) config('app.url'), '/');
    $title = 'Blog — Web Development, Branding & Digital Marketing Insights | Techibytes Media';
    $canonical = $siteUrl.'/blog'.($page > 1 ? '?page='.$page : '');
    $blogId = StructuredData::url(route('blog', absolute: false)).'#blog';

    $breadcrumbs = [['name' => 'Blog', 'url' => route('blog')]];

    $blogSchema = [
        '@type' => 'Blog',
        '@id' => $blogId,
        'name' => 'Techibytes Media Blog',
        'description' => 'Guides on web development, software, mobile apps, branding, SEO and media buying.',
        'url' => StructuredData::url(route('blog', absolute: false)),
        'publisher' => ['@id' => StructuredData::organizationId()],
        'inLanguage' => 'en',
        'blogPost' => collect($posts)->map(fn ($post): array => array_filter([
            '@type' => 'BlogPosting',
            '@id' => StructuredData::url(route('blog.show', $post->slug, absolute: false)).'#article',
            'headline' => $post->title,
            'url' => StructuredData::url(route('blog.show', $post->slug, absolute: false)),
            'datePublished' => $post->publishedAt->toAtomString(),
            'image' => $post->imageUrl,
        ]))->all(),
    ];
@endphp

<x-layout
    :title="$page > 1 ? 'Blog — Page '.$page.' | Techibytes Media' : $title"
    description="Practical guides on websites, software, mobile apps, branding, SEO and media buying from the Techibytes Media team in Seattle and Abuja."
    :canonical="$canonical"
    page-type="CollectionPage"
    :breadcrumbs="$breadcrumbs"
    :schema="[$blogSchema]"
    :main-entity="$blogId"
>
    @push('head')
        @if ($page > 1)
            <link rel="prev" href="{{ route('blog', $page > 2 ? ['page' => $page - 1] : []) }}">
        @endif
        @if ($page < $totalPages)
            <link rel="next" href="{{ route('blog', ['page' => $page + 1]) }}">
        @endif
    @endpush

    {{-- Hero --}}
    <section class="relative overflow-hidden pt-36 pb-16 sm:pt-44 sm:pb-20">
        <x-hero-grid />

        <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
            <x-breadcrumbs :items="$breadcrumbs" class="reveal" />

            <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-accent">Blog</p>
            <h1 class="reveal mt-5 max-w-4xl font-display text-[clamp(2.5rem,7vw,5.5rem)] font-extrabold leading-[1.02]" style="--reveal-delay: .1s">
                Ideas that help brands <span class="text-accent">grow online.</span>
            </h1>
            <p class="reveal mt-8 max-w-xl text-lg leading-relaxed text-muted" style="--reveal-delay: .2s">
                Practical guides on websites, software, apps, branding, SEO and media from the people who build them.
            </p>
        </div>
    </section>

    <section class="border-t border-line">
        <div class="mx-auto max-w-7xl px-5 py-20 sm:px-8 sm:py-28">
            @if ($unavailable)
                <div class="rounded-2xl border border-line bg-panel p-10 text-center" role="status">
                    <h2 class="font-display text-2xl font-bold">The blog is taking a short break</h2>
                    <p class="mx-auto mt-3 max-w-md leading-relaxed text-muted">We could not load the latest articles right now. Please check back in a few minutes.</p>
                </div>
            @elseif ($posts === [])
                <div class="rounded-2xl border border-line bg-panel p-10 text-center" data-testid="blog-empty">
                    <h2 class="font-display text-2xl font-bold">New articles are on the way</h2>
                    <p class="mx-auto mt-3 max-w-md leading-relaxed text-muted">
                        We are preparing guides on web development, branding, SEO and media buying.
                        In the meantime, <a href="{{ route('services') }}" class="link-draw text-accent">explore our services</a>.
                    </p>
                </div>
            @else
                <div class="grid gap-x-6 gap-y-14 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        <article data-testid="blog-card" class="group reveal flex flex-col" style="--reveal-delay: {{ ($loop->index % 3) * 0.08 }}s">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                <div class="relative aspect-[16/10] overflow-hidden rounded-2xl border border-line bg-panel">
                                    @if ($post->imageUrl)
                                        <img
                                            src="{{ $post->imageUrl }}"
                                            alt="{{ $post->imageAlt ?? '' }}"
                                            loading="lazy"
                                            decoding="async"
                                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.02] motion-reduce:transform-none motion-reduce:transition-none"
                                        >
                                    @else
                                        <div aria-hidden="true" class="absolute inset-0 flex items-end bg-primary p-6">
                                            <span class="font-display text-3xl font-extrabold leading-tight text-white/90">{{ \Illuminate\Support\Str::limit($post->title, 60) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs uppercase tracking-widest text-muted">
                                @if ($post->categories !== [])
                                    <span class="font-semibold text-accent">{{ $post->categories[0] }}</span>
                                    <span aria-hidden="true">&middot;</span>
                                @endif
                                <time datetime="{{ $post->publishedAt->toAtomString() }}">{{ $post->publishedAt->format('M j, Y') }}</time>
                                <span aria-hidden="true">&middot;</span>
                                <span>{{ $post->readingMinutes() }} min read</span>
                            </div>
                            <h2 class="mt-3 font-display text-2xl font-bold leading-snug transition-colors group-hover:text-accent">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            @if ($post->excerpt !== '')
                                <p class="mt-3 text-sm leading-relaxed text-muted">{{ \Illuminate\Support\Str::limit($post->excerpt, 180) }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>

                @if ($totalPages > 1)
                    <nav aria-label="Blog pages" class="mt-20 flex items-center justify-between gap-4 border-t border-line pt-8">
                        @if ($page > 1)
                            <a href="{{ route('blog', $page > 2 ? ['page' => $page - 1] : []) }}" class="link-draw font-semibold text-bone">&larr; Newer articles</a>
                        @else
                            <span></span>
                        @endif
                        <span class="text-sm text-muted">Page {{ $page }} of {{ $totalPages }}</span>
                        @if ($page < $totalPages)
                            <a href="{{ route('blog', ['page' => $page + 1]) }}" class="link-draw font-semibold text-bone">Older articles &rarr;</a>
                        @else
                            <span></span>
                        @endif
                    </nav>
                @endif
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="border-t border-line bg-panel">
        <div class="mx-auto max-w-7xl px-5 py-24 text-center sm:px-8 sm:py-28">
            <h2 class="reveal font-display text-[clamp(2rem,5vw,3.5rem)] font-extrabold">
                Have a project in mind? <span class="text-accent">Let's talk.</span>
            </h2>
            <div class="reveal mt-8" style="--reveal-delay: .1s">
                <a href="{{ route('contact') }}" class="group inline-flex items-center gap-3 rounded-full bg-primary px-8 py-4 font-semibold text-white transition-transform hover:scale-105">
                    Book a free consultation
                    <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

</x-layout>
