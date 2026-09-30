@props([
    'ticket',
    'compact' => false,
])

@php
    $sla = $ticket->sla_status;
    $colorMap = [
        'green' => 'text-[#16A34A]',
        'orange' => 'text-[#D97706]',
        'red' => 'text-[#DC2626]',
        'gray' => 'text-[#71717A]',
    ];
    $barMap = [
        'green' => 'bg-[#16A34A]',
        'orange' => 'bg-[#D97706]',
        'red' => 'bg-[#DC2626]',
        'gray' => 'bg-[#A1A1AA]',
    ];
    $color = $colorMap[$sla['color']] ?? 'text-[#71717A]';
    $bar = $barMap[$sla['color']] ?? 'bg-[#A1A1AA]';
@endphp

@if($compact)
    <div class="flex items-center gap-2">
        <svg class="w-3.5 h-3.5 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-[12px] font-semibold {{ $color }}">{{ $sla['text'] }}</span>
    </div>
@else
    <div>
        <div class="flex items-center justify-between mb-1.5">
            <span class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider">
                SLA
            </span>
            <span class="text-[12px] font-semibold {{ $color }}">{{ $sla['text'] }}</span>
        </div>
        <div class="w-full h-1.5 bg-[#F0F0F1] rounded-full overflow-hidden">
            <div class="h-full {{ $bar }} transition-all duration-500" 
                 style="width: {{ $sla['percent'] }}%"></div>
        </div>
    </div>
@endif