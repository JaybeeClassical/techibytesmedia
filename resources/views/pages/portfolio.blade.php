@use('App\Support\StructuredData')

@php
    $pageUrl = StructuredData::url(route('portfolio', absolute: false));
    $breadcrumbs = [['name' => 'Work', 'url' => route('portfolio')]];
    $caseStudies = collect(config('projects.items', []))
        ->map(fn (array $project, string $key): array => ['name' => $project['title'], 'url' => route('portfolio.show', $key)])
        ->values()
        ->all();
    $schema = [StructuredData::itemList($pageUrl.'#case-studies', 'Techibytes Media case studies', $caseStudies)];
@endphp

<x-layout
    title="Our Work & Portfolio | Techibytes Media"
    description="Selected projects by Techibytes Media — websites, mobile apps, platforms and brands built for clients in Nigeria, the USA and beyond."
    page-type="CollectionPage"
    :breadcrumbs="$breadcrumbs"
    :schema="$schema"
    :main-entity="$pageUrl.'#case-studies'"
>

    {{-- Page hero --}}
    <section class="relative overflow-hidden pt-36 pb-16 sm:pt-44 sm:pb-20">
        <x-hero-grid />

        <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
            <x-breadcrumbs :items="$breadcrumbs" class="reveal" />

            <p class="reveal text-xs font-semibold uppercase tracking-[0.35em] text-accent">Portfolio</p>
            <h1 class="reveal mt-5 max-w-4xl font-display text-[clamp(2.5rem,7vw,5.5rem)] font-extrabold leading-[1.02]" style="--reveal-delay: .1s">
                Work that <span class="text-accent">works.</span>
            </h1>
            <p class="reveal mt-8 max-w-xl text-lg leading-relaxed text-muted" style="--reveal-delay: .2s">
                Explore our latest projects and see how we've helped businesses build their
                creative ideas into innovative products.
            </p>
        </div>
    </section>

    {{-- Projects grid --}}
    <section class="border-t border-line">
        <div class="mx-auto max-w-7xl px-5 py-20 sm:px-8 sm:py-28">
            <div class="grid gap-x-6 gap-y-16 md:grid-cols-2">
                @foreach (config('projects.items') as $projectKey => $project)
                    <article @class(['group reveal', 'md:mt-20' => $loop->index % 2 === 1, 'md:-mt-20' => $loop->index % 2 === 0 && ! $loop->first])>
                        <a href="{{ route('portfolio.show', $projectKey) }}" class="block">
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
                                <div class="absolute inset-0 bg-gradient-to-t from-ink/45 via-transparent to-transparent"></div>
                                <span class="absolute bottom-5 left-5 font-display text-6xl font-extrabold text-stroke opacity-60">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            <div class="mt-5">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <span data-testid="project-sector" class="inline-flex items-center rounded-full border border-primary/20 bg-primary/8 px-3 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-primary">
                                        {{ $project['sector'] }}
                                    </span>
                                    <p class="text-xs uppercase tracking-widest text-muted">{{ $project['tags'] }}</p>
                                </div>
                                <h2 class="mt-3 font-display text-xl font-bold transition-colors group-hover:text-accent sm:text-2xl">{{ $project['title'] }}</h2>
                            </div>
                            <p class="mt-2 text-sm text-muted"><span class="text-accent">&#10022;</span> {{ $project['result'] }}</p>
                            <p class="mt-4 text-sm font-semibold text-bone transition-colors group-hover:text-accent">Read the case study &rarr;</p>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Services behind the work --}}
    <section class="border-t border-line">
        <div class="mx-auto max-w-7xl px-5 py-16 sm:px-8">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-muted">The services behind this work</p>
            <ul class="mt-6 flex flex-wrap gap-3">
                @foreach (config('service-pages.items', []) as $serviceSlug => $serviceItem)
                    <li><a href="{{ route('services.show', $serviceSlug) }}" class="inline-flex rounded-full border border-line bg-panel px-5 py-2.5 text-sm font-semibold text-bone transition-colors hover:border-accent/50 hover:text-accent">{{ $serviceItem['nav_label'] }}</a></li>
                @endforeach
                <li><a href="{{ route('graphics') }}" class="inline-flex rounded-full border border-line bg-panel px-5 py-2.5 text-sm font-semibold text-bone transition-colors hover:border-accent/50 hover:text-accent">Graphic Design</a></li>
            </ul>
        </div>
    </section>

    {{-- CTA --}}
    <section class="border-t border-line bg-panel">
        <div class="mx-auto max-w-7xl px-5 py-24 text-center sm:px-8 sm:py-32">
            <h2 class="reveal font-display text-[clamp(2rem,5vw,4rem)] font-extrabold">
                Your project could be <span class="text-accent">next.</span>
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
