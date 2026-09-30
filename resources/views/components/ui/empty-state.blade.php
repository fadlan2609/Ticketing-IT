@props([
    'title' => 'Belum ada data',
    'description' => null,
    'icon' => 'inbox',
])

<div class="app-empty">
    <div class="w-16 h-16 mx-auto rounded-2xl bg-[#F7F7F8] flex items-center justify-center mb-4">
        @if($icon === 'ticket')
            <svg class="w-8 h-8 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        @elseif($icon === 'search')
            <svg class="w-8 h-8 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        @else
            <svg class="w-8 h-8 text-[#A1A1AA]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        @endif
    </div>

    <h3 class="text-[15px] font-semibold text-[#18181B] mb-1">{{ $title }}</h3>
    @if($description)
        <p class="text-[13px] text-[#71717A] mb-4">{{ $description }}</p>
    @endif

    @if($slot->isNotEmpty())
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>