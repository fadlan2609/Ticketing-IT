@props([
    'padding' => 'md', // sm, md, lg, none
])

@php
    $paddingClass = match($padding) {
        'sm' => 'p-4',
        'lg' => 'p-6 lg:p-8',
        'none' => '',
        default => 'p-5 lg:p-6',
    };
@endphp

<div {{ $attributes->class(['bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)]', $paddingClass]) }}>
    {{ $slot }}
</div>