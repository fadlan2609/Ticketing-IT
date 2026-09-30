@props([
    'variant' => 'neutral', // neutral, brand, success, warning, danger, info, accent
    'dot' => false,
])

@php
    $variants = match($variant) {
        'brand' => 'bg-[#FDECEF] text-[#C8102E]',
        'accent' => 'bg-[#FFF8D8] text-[#A16207]',
        'success' => 'bg-[#ECFDF3] text-[#166534]',
        'warning' => 'bg-[#FFF7ED] text-[#9A3412]',
        'danger' => 'bg-[#FEF2F2] text-[#991B1B]',
        'info' => 'bg-[#EFF6FF] text-[#1E40AF]',
        default => 'bg-[#F4F4F5] text-[#52525B]',
    };

    $dotColors = match($variant) {
        'success' => 'bg-[#16A34A]',
        'warning' => 'bg-[#D97706]',
        'danger' => 'bg-[#DC2626]',
        'info' => 'bg-[#2563EB]',
        'brand' => 'bg-[#C8102E]',
        default => 'bg-[#71717A]',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[12px] font-medium leading-none', $variants]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors }}"></span>
    @endif
    {{ $slot }}
</span>