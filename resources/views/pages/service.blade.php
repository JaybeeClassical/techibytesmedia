@use('App\Support\InternalLinks')
@use('App\Support\StructuredData')

@php
    $pageUrl = StructuredData::url(route('services.show', $slug, absolute: false));
    $firstProject = $relatedProjects->first();
    $breadcrumbs = [
        ['name' => 'Services', 'url' => route('services')],
        ['name' => $service['nav_label'], 'url' => route('services.show', $slug)],
    ];

    $schema = [
        StructuredData::service(
            $pageUrl,
            $service['nav_label'],
            $service['eyebrow'],
            $service['meta_description'],
            $service['offerings'],
            $firstProject ? StructuredData::url(asset($firstProject['screenshot'])) : null,
        ),
        StructuredData::faqPage($pageUrl, $service['faqs']),
    ];

    if ($relatedProjects->isNotEmpty()) {
        $schema[] = StructuredData::itemList(
            $pageUrl.'#case-studies',
            $service['nav_label'].' case studies',
            $relatedProjects->map(fn (array $project, string $key): array => ['name' => $project['title'], 'url' => route('portfolio.show', $key)])->values()->all(),
        );
    }

    $contactUrl = route('contact', ['service' => $service['contact_option']]);
@endphp

<x-layout
    :title="$service['meta_title']"
    :description="$service['meta_description']"
    :breadcrumbs="$breadcrumbs"
    :schema="$schema"
    :main-entity="$pageUrl.'#service'"
>
    {{-- Hero --}}
    <section class="relative overflow-hidden pt-36 pb-16 sm:pt-44 sm:pb-20">
        <x-hero-grid />

        <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
            <x-breadcrumbs :items="$breadcrumbs" class="reveal" />

            <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-accent">{{ $service['eyebrow'] }}</p>
            <h1 class="reveal mt-5 max-w-5xl font-display text-[clamp(2.25rem,6vw,5rem)] font-extrabold leading-[1.02]" style="--reveal-delay: .1s">
                {{ $service['heading'] }} <span class="text-accent">{{ $service['heading_accent'] }}</span>
            </h1>
            <p class="reveal mt-8 max-w-2xl text-lg leading-relaxed text-muted" style="--reveal-delay: .2s">
                {{ $service['intro'] }}
            </p>

            <div class="reveal mt-10 flex flex-wrap items-center gap-4" style="--reveal-delay: .3s">
                <a href="{{ $contactUrl }}" class="group inline-flex items-center gap-2 rounded-full bg-primary px-7 py-4 font-semibold text-white transition-transform hover:scale-105">
                    Get a free proposal
                    <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
                </a>
                <a href="{{ route('portfolio') }}" class="link-draw inline-flex items-center gap-2 py-4 font-semibold text-bone">See our work</a>
            </div>

            <p class="reveal mt-8 text-sm text-muted" style="--reveal-delay: .35s">
                <span class="text-accent" aria-hidden="true">&#10022;</span>
                Serving {{ config('service-pages.areas_served') }}.
            </p>
        </div>
    </section>

    {{-- What we offer --}}
    <section class="border-t border-line">
        <div class="mx-auto max-w-7xl px-5 py-24 sm:px-8 sm:py-28">
            <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-muted">What we offer</p>
            <h2 class="reveal mt-4 max-w-3xl font-display text-[clamp(2rem,4.5vw,3.5rem)] font-extrabold">What's included</h2>

            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($service['offerings'] as $offering)
                    <article class="reveal rounded-2xl border border-line bg-panel p-8 transition-colors hover:border-accent/50" style="--reveal-delay: {{ ($loop->index % 3) * 0.08 }}s">
                        <h3 class="font-display text-xl font-bold">{{ $offering['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-muted">{{ $offering['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Detail --}}
    <section class="border-t border-line bg-panel">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-24 sm:px-8 sm:py-28 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <h2 class="reveal font-display text-[clamp(1.75rem,4vw,3rem)] font-extrabold leading-tight">{{ $service['detail_heading'] }}</h2>
            </div>
            <div class="reveal space-y-6 text-lg leading-relaxed text-muted lg:col-span-7" style="--reveal-delay: .1s">
                @foreach ($service['detail'] as $paragraph)
                    <p>{{ InternalLinks::render($paragraph) }}</p>
                @endforeach

                <div class="pt-4">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-muted">Technologies we use</p>
                    <ul class="mt-4 flex flex-wrap gap-2">
                        @foreach ($service['tech'] as $tool)
                            <li class="rounded-full border border-line bg-ink px-3 py-1.5 text-xs font-medium text-bone/85">{{ $tool }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Process --}}
    <section class="border-t border-line">
        <div class="mx-auto max-w-7xl px-5 py-24 sm:px-8 sm:py-28">
            <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-muted">How we work</p>
            <h2 class="reveal mt-4 max-w-2xl font-display text-[clamp(2rem,4.5vw,3.5rem)] font-extrabold">How we deliver {{ $service['nav_label'] }}</h2>

            <ol class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($service['process'] as $step)
                    <li class="reveal rounded-2xl border border-line bg-panel p-7" style="--reveal-delay: {{ $loop->index * 0.1 }}s">
                        <p class="font-display text-5xl font-extrabold text-stroke">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                        <h3 class="mt-6 font-display text-xl font-bold">{{ $step['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-muted">{{ $step['desc'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- Related case studies --}}
    <section class="border-t border-line">
        <div class="mx-auto max-w-7xl px-5 py-24 sm:px-8 sm:py-28">
            <div class="mb-14 flex flex-wrap items-end justify-between gap-6">
                <div>
                    <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-muted">Case studies</p>
                    <h2 class="reveal mt-4 font-display text-[clamp(2rem,4.5vw,3.5rem)] font-extrabold">
                        {{ $relatedProjects->isNotEmpty() ? 'Related work' : 'See what we have built' }}
                    </h2>
                </div>
                <a href="{{ route('portfolio') }}" class="reveal link-draw pb-1 font-semibold text-bone">View all work &rarr;</a>
            </div>

            @if ($relatedProjects->isNotEmpty())
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($relatedProjects as $projectKey => $project)
                        <a href="{{ route('portfolio.show', $projectKey) }}" data-testid="related-case-study" class="group reveal block">
                            <div class="relative aspect-[4/3] overflow-hidden rounded-2xl border border-line bg-panel">
                                <img
                                    src="{{ asset($project['screenshot']) }}"
                                    alt="{{ $project['name'] }} website homepage"
                                    width="1440"
                                    height="1080"
                                    loading="lazy"
                                    decoding="async"
                                    class="absolute inset-0 h-full w-full object-cover object-top transition-transform duration-500 ease-out group-hover:scale-[1.02] motion-reduce:transform-none motion-reduce:transition-none"
                                >
                            </div>
                            <div class="mt-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                                <h3 class="font-display text-xl font-bold transition-colors group-hover:text-accent sm:text-2xl">{{ $project['title'] }}</h3>
                                <p class="text-xs uppercase tracking-widest text-muted sm:shrink-0">Read case study &rarr;</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="reveal max-w-xl leading-relaxed text-muted">
                    Browse websites, apps and platforms we have delivered for businesses in fintech, education, healthcare, fashion and public service.
                </p>
            @endif
        </div>
    </section>

    {{-- FAQ --}}
    <section class="border-t border-line bg-panel">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-24 sm:px-8 sm:py-28 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-muted">FAQ</p>
                <h2 class="reveal mt-4 font-display text-[clamp(2rem,4.5vw,3.25rem)] font-extrabold">{{ $service['nav_label'] }} questions</h2>
                <p class="reveal mt-6 max-w-sm leading-relaxed text-muted">
                    Something else on your mind?
                    <a href="{{ $contactUrl }}" class="link-draw text-accent">Ask us directly</a>.
                </p>
            </div>

            <div class="reveal lg:col-span-7" style="--reveal-delay: .15s">
                <div class="divide-y divide-line border-y border-line">
                    @foreach ($service['faqs'] as $faq)
                        <details class="group py-5">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-6 font-display text-lg font-bold [&::-webkit-details-marker]:hidden">
                                {{ $faq['q'] }}
                                <span class="shrink-0 text-2xl text-accent transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                            </summary>
                            <p class="mt-4 max-w-xl leading-relaxed text-muted">{{ InternalLinks::render($faq['a']) }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Related services: descriptive links to the services that pair best with this one, then the rest --}}
    <section class="border-t border-line">
        <div class="mx-auto max-w-7xl px-5 py-24 sm:px-8 sm:py-28">
            <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-muted">Related services</p>
            <h2 class="reveal mt-4 max-w-3xl font-display text-[clamp(2rem,4.5vw,3.25rem)] font-extrabold">Pairs well with {{ $service['nav_label'] }}</h2>

            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach ($relatedServices as $related)
                    <a href="{{ $related['url'] }}" data-testid="related-service" class="group reveal flex flex-col justify-between rounded-2xl border border-line bg-panel p-8 transition-colors hover:border-accent/50" style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                        <div>
                            <h3 class="font-display text-xl font-bold transition-colors group-hover:text-accent">{{ $related['label'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-muted">{{ $related['summary'] }}</p>
                        </div>
                        <span class="mt-6 text-sm font-semibold text-bone transition-colors group-hover:text-accent">Explore {{ $related['label'] }} &rarr;</span>
                    </a>
                @endforeach
            </div>

            <ul class="mt-10 flex flex-wrap gap-3">
                @foreach ($otherServices as $other)
                    <li>
                        <a href="{{ $other['url'] }}" class="inline-flex rounded-full border border-line bg-panel px-5 py-2.5 text-sm font-semibold text-bone transition-colors hover:border-accent/50 hover:text-accent">{{ $other['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-primary text-white">
        <div class="relative mx-auto max-w-7xl px-5 py-24 text-center sm:px-8 sm:py-32">
            <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-accent-soft">Ready when you are</p>
            <h2 class="reveal mt-6 font-display text-[clamp(2rem,5vw,4rem)] font-extrabold leading-[1.05]" style="--reveal-delay: .1s">
                Let's talk about <span class="text-accent-soft">your project.</span>
            </h2>
            <div class="reveal mt-10" style="--reveal-delay: .2s">
                <a href="{{ $contactUrl }}" class="group inline-flex items-center gap-3 rounded-full bg-white px-9 py-5 text-lg font-semibold text-primary transition-transform hover:scale-105">
                    Book a free consultation
                    <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
                </a>
            </div>
        </div>
    </section>

</x-layout>
