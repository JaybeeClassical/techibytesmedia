@use('App\Support\InternalLinks')
@use('App\Support\StructuredData')

@php
    $pageUrl = StructuredData::url(route('portfolio.show', $key, absolute: false));
    $location = $caseStudy['location'] ?? null;
    $summary = $caseStudy['overview'] ?? $project['result'];
    $imageUrl = StructuredData::url(asset($project['screenshot']));

    $metaTitle = $project['name'].' Case Study — '.$project['sector'].($location ? ' in '.$location : '').' | Techibytes Media';
    $metaDescription = \Illuminate\Support\Str::limit($summary.' See how Techibytes Media delivered '.\Illuminate\Support\Str::lower($project['tags']).'.', 158);

    $breadcrumbs = [
        ['name' => 'Work', 'url' => route('portfolio')],
        ['name' => $project['name'], 'url' => route('portfolio.show', $key)],
    ];

    $creativeWork = [
        '@type' => 'CreativeWork',
        '@id' => $pageUrl.'#case-study',
        'name' => $project['title'],
        'headline' => $project['title'].' case study',
        'description' => $summary,
        'abstract' => $project['result'],
        'url' => $pageUrl,
        'image' => $imageUrl,
        'genre' => 'Case study',
        'inLanguage' => 'en',
        'creator' => ['@id' => StructuredData::organizationId()],
        'publisher' => ['@id' => StructuredData::organizationId()],
        'mainEntityOfPage' => ['@id' => $pageUrl.'#webpage'],
        'keywords' => $project['tags'].', '.$project['sector'],
        'about' => array_filter([
            '@type' => 'Organization',
            'name' => $project['name'],
            'url' => $project['url'],
            'sameAs' => $project['url'],
            'location' => $location ? ['@type' => 'Place', 'name' => $location] : null,
        ]),
        'isBasedOn' => ['@type' => 'WebSite', 'name' => $project['name'], 'url' => $project['url']],
    ];

    if ($serviceLinks->isNotEmpty()) {
        $creativeWork['mentions'] = $serviceLinks->map(fn (array $link): array => [
            '@type' => 'Service',
            '@id' => StructuredData::url($link['url']).'#service',
            'name' => $link['label'],
            'url' => StructuredData::url($link['url']),
        ])->all();
    }

    $liveHost = preg_replace('/^www\./', '', (string) parse_url($project['url'], PHP_URL_HOST));
@endphp

<x-layout
    :title="$metaTitle"
    :description="$metaDescription"
    :image="$imageUrl"
    :breadcrumbs="$breadcrumbs"
    :schema="[$creativeWork]"
    :main-entity="$pageUrl.'#case-study'"
>
    {{-- Hero --}}
    <section class="relative overflow-hidden pt-36 pb-12 sm:pt-44 sm:pb-16">
        <x-hero-grid />

        <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
            <x-breadcrumbs :items="$breadcrumbs" class="reveal" />

            <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-accent">
                Case study &middot; {{ $project['sector'] }}@if ($location) &middot; {{ $location }}@endif
            </p>
            <h1 class="reveal mt-5 max-w-5xl font-display text-[clamp(2.25rem,6vw,5rem)] font-extrabold leading-[1.02]" style="--reveal-delay: .1s">
                {{ $project['title'] }}
            </h1>
            <p class="reveal mt-8 max-w-2xl text-lg leading-relaxed text-muted" style="--reveal-delay: .2s">{{ $summary }}</p>

            <div class="reveal mt-10 flex flex-wrap items-center gap-4" style="--reveal-delay: .3s">
                <a
                    href="{{ $project['url'] }}"
                    target="_blank"
                    rel="noopener"
                    data-testid="live-site-link"
                    class="group inline-flex items-center gap-2 rounded-full bg-primary px-7 py-4 font-semibold text-white transition-transform hover:scale-105"
                >
                    Visit {{ $liveHost }}
                    <span class="transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" aria-hidden="true">&nearr;</span>
                    <span class="sr-only">(opens in a new tab)</span>
                </a>
                <a href="{{ route('contact') }}" class="link-draw inline-flex items-center gap-2 py-4 font-semibold text-bone">Start a similar project</a>
            </div>
        </div>
    </section>

    {{-- Screenshot --}}
    <section class="mx-auto max-w-7xl px-5 sm:px-8">
        <a href="{{ $project['url'] }}" target="_blank" rel="noopener" class="group reveal block" aria-label="Open the live {{ $project['name'] }} website in a new tab">
            <div class="relative aspect-[4/3] overflow-hidden rounded-2xl border border-line bg-panel sm:aspect-[16/10]">
                <img
                    src="{{ asset($project['screenshot']) }}"
                    alt="{{ $project['name'] }} website homepage designed and built by Techibytes Media"
                    width="1440"
                    height="1080"
                    decoding="async"
                    fetchpriority="high"
                    class="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-500 ease-out group-hover:scale-[1.01] motion-reduce:transform-none motion-reduce:transition-none"
                >
            </div>
        </a>
    </section>

    {{-- Story + facts --}}
    <section class="py-20 sm:py-28">
        <div class="mx-auto grid max-w-7xl gap-14 px-5 sm:px-8 lg:grid-cols-12">
            <div class="min-w-0 space-y-14 lg:col-span-8">
                @if (! empty($caseStudy['challenge']))
                    <div class="reveal">
                        <h2 class="font-display text-3xl font-extrabold sm:text-4xl">The challenge</h2>
                        <p class="mt-5 text-lg leading-relaxed text-muted">{{ $caseStudy['challenge'] }}</p>
                    </div>
                @endif

                @if (! empty($caseStudy['solution']))
                    <div class="reveal">
                        <h2 class="font-display text-3xl font-extrabold sm:text-4xl">What we built</h2>
                        <p class="mt-5 text-lg leading-relaxed text-muted">{{ InternalLinks::render($caseStudy['solution']) }}</p>

                        @if (! empty($caseStudy['deliverables']))
                            <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                                @foreach ($caseStudy['deliverables'] as $deliverable)
                                    <li class="flex gap-3 border-b border-line pb-3 text-sm">
                                        <span class="text-accent" aria-hidden="true">&#10022;</span>
                                        <span class="text-bone/85">{{ $deliverable }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="reveal">
                    <h2 class="font-display text-3xl font-extrabold sm:text-4xl">The result</h2>
                    <p class="mt-5 text-lg leading-relaxed text-muted">{{ $project['result'] }}</p>
                </div>

                @if (! empty($caseStudy['testimonial']))
                    <figure class="reveal rounded-2xl border border-line bg-panel p-8 sm:p-10">
                        <span class="font-display text-5xl font-extrabold leading-none text-accent" aria-hidden="true">&ldquo;</span>
                        <blockquote class="mt-2 text-lg leading-relaxed text-bone/90">{{ $caseStudy['testimonial']['quote'] }}</blockquote>
                        <figcaption class="mt-6 border-t border-line pt-5">
                            <p class="font-semibold">{{ $caseStudy['testimonial']['name'] }}</p>
                            <p class="text-sm text-muted">{{ $caseStudy['testimonial']['role'] }}</p>
                        </figcaption>
                    </figure>
                @endif
            </div>

            <aside class="reveal lg:col-span-4" style="--reveal-delay: .1s">
                <dl class="space-y-6 rounded-2xl border border-line bg-panel p-8 lg:sticky lg:top-28">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-muted">Client</dt>
                        <dd class="mt-2 font-display text-lg font-bold">{{ $project['name'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-muted">Sector</dt>
                        <dd class="mt-2">{{ $project['sector'] }}</dd>
                    </div>
                    @if ($location)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-muted">Location</dt>
                            <dd class="mt-2">{{ $location }}</dd>
                        </div>
                    @endif
                    @if ($serviceLinks->isNotEmpty())
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-muted">Services</dt>
                            <dd class="mt-3 flex flex-wrap gap-2">
                                @foreach ($serviceLinks as $serviceLink)
                                    <a href="{{ $serviceLink['url'] }}" class="rounded-full border border-line bg-ink px-3 py-1.5 text-xs font-medium text-bone/85 transition-colors hover:border-accent/50 hover:text-accent">{{ $serviceLink['label'] }}</a>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-muted">Live website</dt>
                        <dd class="mt-2">
                            <a href="{{ $project['url'] }}" target="_blank" rel="noopener" class="link-draw break-all font-semibold text-accent">{{ $liveHost }} &nearr;</a>
                        </dd>
                    </div>
                </dl>
            </aside>
        </div>
    </section>

    {{-- More work like this: case studies that share a service --}}
    @if ($relatedCaseStudies->isNotEmpty())
        <section class="border-t border-line">
            <div class="mx-auto max-w-7xl px-5 py-20 sm:px-8 sm:py-24">
                <div class="mb-12 flex flex-wrap items-end justify-between gap-6">
                    <h2 class="font-display text-[clamp(1.75rem,4vw,3rem)] font-extrabold">More work like this</h2>
                    <a href="{{ route('portfolio') }}" class="link-draw pb-1 font-semibold text-bone">View all work &rarr;</a>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($relatedCaseStudies as $relatedKey => $related)
                        <a href="{{ route('portfolio.show', $relatedKey) }}" data-testid="related-case-study" class="group block">
                            <div class="relative aspect-[4/3] overflow-hidden rounded-2xl border border-line bg-panel">
                                <img
                                    src="{{ asset($related['screenshot']) }}"
                                    alt="{{ $related['name'] }} website homepage"
                                    width="1440"
                                    height="1080"
                                    loading="lazy"
                                    decoding="async"
                                    class="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-500 ease-out group-hover:scale-[1.02] motion-reduce:transform-none motion-reduce:transition-none"
                                >
                            </div>
                            <p class="mt-4 text-xs uppercase tracking-widest text-muted">{{ $related['sector'] }}</p>
                            <h3 class="mt-2 font-display text-lg font-bold transition-colors group-hover:text-accent">{{ $related['title'] }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Next project --}}
    @if ($nextProject)
        <section class="border-t border-line bg-panel">
            <a href="{{ route('portfolio.show', $nextKey) }}" class="group mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-6 px-5 py-16 sm:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-muted">Next case study</p>
                    <p class="mt-3 font-display text-[clamp(1.75rem,4vw,3rem)] font-extrabold transition-colors group-hover:text-accent">{{ $nextProject['title'] }}</p>
                </div>
                <span class="text-4xl text-muted transition-all group-hover:translate-x-2 group-hover:text-accent" aria-hidden="true">&rarr;</span>
            </a>
        </section>
    @endif

    {{-- CTA --}}
    <section class="border-t border-line">
        <div class="mx-auto max-w-7xl px-5 py-24 text-center sm:px-8 sm:py-32">
            <h2 class="reveal font-display text-[clamp(2rem,5vw,4rem)] font-extrabold">
                Want results like <span class="text-accent">{{ $project['name'] }}?</span>
            </h2>
            <div class="reveal mt-8" style="--reveal-delay: .1s">
                <a href="{{ route('contact') }}" class="group inline-flex items-center gap-3 rounded-full bg-primary px-8 py-4 font-semibold text-white transition-transform hover:scale-105">
                    Start a project
                    <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

</x-layout>
