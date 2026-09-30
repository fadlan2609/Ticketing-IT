@props([
    'title' => null,
    'subtitle' => null,
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 lg:mb-8">
    <div class="min-w-0">
        <h1 class="text-[24px] lg:text-[28px] font-bold text-[#18181B] 
                   tracking-[-0.02em] leading-tight">
            {{ $title }}
        </h1>
        @if($subtitle)
            <p class="text-[14px] text-[#52525B] mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    @if($slot->isNotEmpty())
        <div class="flex items-center gap-2 flex-shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>