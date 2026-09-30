@props([
    'label',
    'value',
    'hint' => null,
    'color' => 'default', // default, brand, success, warning, danger, info
])

@php
    $valueColor = match($color) {
        'brand' => 'text-[#C8102E]',
        'success' => 'text-[#16A34A]',
        'warning' => 'text-[#D97706]',
        'danger' => 'text-[#DC2626]',
        'info' => 'text-[#2563EB]',
        default => 'text-[#18181B]',
    };
@endphp

<div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <p class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider">
        {{ $label }}
    </p>
    <p class="text-[32px] font-bold {{ $valueColor }} leading-none mt-2 tracking-tight">
        {{ $value }}
    </p>
    @if($hint)
        <p class="text-[12px] text-[#71717A] mt-2">{{ $hint }}</p>
    @endif
</div>