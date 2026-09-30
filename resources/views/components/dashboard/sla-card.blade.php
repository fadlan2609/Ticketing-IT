@props(['percent', 'label' => 'SLA Performance'])

@php
    $color = $percent >= 90 ? 'text-[#16A34A]' 
           : ($percent >= 75 ? 'text-[#D97706]' : 'text-[#DC2626]');
    $barColor = $percent >= 90 ? 'bg-[#16A34A]' 
              : ($percent >= 75 ? 'bg-[#D97706]' : 'bg-[#DC2626]');
@endphp

<div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <p class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider">
        {{ $label }}
    </p>
    <p class="text-[36px] font-bold {{ $color }} leading-none mt-2 tracking-tight">
        {{ $percent }}<span class="text-[20px]">%</span>
    </p>
    <p class="text-[12px] text-[#71717A] mt-1">Within SLA</p>

    <div class="w-full h-1.5 bg-[#F0F0F1] rounded-full overflow-hidden mt-4">
        <div class="h-full {{ $barColor }} transition-all duration-700" 
             style="width: {{ $percent }}%"></div>
    </div>
</div>