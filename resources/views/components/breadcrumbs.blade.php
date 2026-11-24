{{-- Visible breadcrumb trail; pass the same items given to <x-layout :breadcrumbs> so it matches the BreadcrumbList schema --}}
@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'mb-6 text-sm text-muted']) }}>
    <ol class="flex flex-wrap items-center gap-2">
        <li><a href="{{ route('home') }}" class="transition-colors hover:text-accent">Home</a></li>
        @foreach ($items as $crumb)
            <li aria-hidden="true">/</li>
            @if ($loop->last)
                <li aria-current="page" class="text-bone">{{ $crumb['name'] }}</li>
            @else
                <li><a href="{{ $crumb['url'] }}" class="transition-colors hover:text-accent">{{ $crumb['name'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
