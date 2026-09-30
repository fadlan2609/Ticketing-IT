@props([
    'variant' => 'primary', // primary, secondary, ghost, danger
    'size' => 'md',         // sm, md, lg
    'type' => 'button',
    'href' => null,
    'icon' => null,
])

@php
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-[13px] gap-1.5',
        'lg' => 'px-5 py-3 text-[15px] gap-2',
        default => 'px-4 py-2.5 text-[14px] gap-2',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-[#C8102E] text-white border-[#C8102E] hover:bg-[#A50D25] hover:border-[#A50D25]',
        'secondary' => 'bg-white text-[#18181B] border-[#E4E4E7] hover:bg-[#FAFAFA] hover:border-[#A1A1AA]',
        'ghost' => 'bg-transparent text-[#52525B] border-transparent hover:bg-[#FAFAFA] hover:text-[#18181B]',
        'danger' => 'bg-[#DC2626] text-white border-[#DC2626] hover:bg-[#B91C1C]',
        default => 'bg-[#C8102E] text-white',
    };

    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg border 
                    transition-all duration-150 ease-out whitespace-nowrap 
                    focus:outline-none focus-visible:ring-2 focus-visible:ring-[#FDECEF] 
                    focus-visible:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->class([$baseClasses, $sizeClasses, $variantClasses]) }}>
        @if($icon === 'plus')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$baseClasses, $sizeClasses, $variantClasses]) }}>
        @if($icon === 'plus')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif