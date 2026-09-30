@props(['items' => []])

<nav class="flex items-center gap-1.5 text-[13px]">
    <a href="{{ route('dashboard') }}" 
       class="text-[#71717A] hover:text-[#18181B] transition">
        Dashboard
    </a>
    @foreach($items as $label => $url)
        <svg class="w-3 h-3 text-[#D4D4D8]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        @if($url && !$loop->last)
            <a href="{{ $url }}" class="text-[#71717A] hover:text-[#18181B] transition">
                {{ $label }}
            </a>
        @else
            <span class="text-[#18181B] font-medium">{{ $label }}</span>
        @endif
    @endforeach
</nav>