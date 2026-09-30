@props(['activity', 'last' => false])

<div class="flex gap-3">
    <div class="flex flex-col items-center flex-shrink-0">
        <div class="w-2.5 h-2.5 rounded-full bg-[#C8102E] 
                    ring-4 ring-[#FDECEF] mt-1.5"></div>
        @if(!$last)
            <div class="w-px flex-1 bg-[#E4E4E7] my-1"></div>
        @endif
    </div>

    <div class="flex-1 pb-5 min-w-0">
        <p class="text-[13px] font-medium text-[#18181B]">
            {{ $activity->description ?? $activity->action }}
        </p>
        <p class="text-[12px] text-[#71717A] mt-0.5">
            {{ $activity->user?->name ?? 'System' }}
            ·
            {{ $activity->created_at->format('d M Y · H:i') }}
        </p>
    </div>
</div>