@props(['priority'])

@php
    $map = [
        'low' => ['label' => 'Low', 'variant' => 'neutral'],
        'medium' => ['label' => 'Normal', 'variant' => 'info'],
        'high' => ['label' => 'High', 'variant' => 'warning'],
        'critical' => ['label' => 'Critical', 'variant' => 'danger'],
    ];
    $data = $map[$priority] ?? ['label' => ucfirst($priority), 'variant' => 'neutral'];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full 
             text-[12px] font-semibold leading-none
             {{ $priority === 'critical' ? 'bg-[#DC2626] text-white' : '' }}
             {{ $priority === 'high' ? 'bg-[#FFF7ED] text-[#9A3412]' : '' }}
             {{ $priority === 'medium' ? 'bg-[#EFF6FF] text-[#1E40AF]' : '' }}
             {{ $priority === 'low' ? 'bg-[#F4F4F5] text-[#52525B]' : '' }}">
    @if($priority === 'critical')
        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
    @endif
    {{ $data['label'] }}
</span>