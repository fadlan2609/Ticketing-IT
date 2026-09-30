@props(['ticket'])

<a href="{{ route('tickets.show', $ticket) }}"
   class="block bg-white border border-[#E4E4E7] rounded-2xl p-4 lg:p-5
          hover:border-[#C8102E] hover:shadow-[0_4px_12px_rgba(0,0,0,0.04)]
          transition-all duration-150 group">

    <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="app-ticket-number">{{ $ticket->ticket_number }}</span>
            <x-tickets.priority :priority="$ticket->priority" />
            <x-tickets.status :status="$ticket->status" />
        </div>
        <svg class="w-4 h-4 text-[#A1A1AA] group-hover:text-[#C8102E] 
                    group-hover:translate-x-0.5 transition-all flex-shrink-0" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </div>

    <h3 class="text-[15px] font-semibold text-[#18181B] mb-2 line-clamp-2 
               group-hover:text-[#C8102E] transition">
        {{ $ticket->title }}
    </h3>

    <div class="flex items-center gap-3 text-[12px] text-[#71717A] mb-3">
        <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            {{ $ticket->requester->name }}
        </span>
        <span class="w-1 h-1 rounded-full bg-[#D4D4D8]"></span>
        <span>{{ $ticket->created_at->diffForHumans() }}</span>
        @if($ticket->category)
            <span class="w-1 h-1 rounded-full bg-[#D4D4D8]"></span>
            <span>{{ $ticket->category->name }}</span>
        @endif
    </div>

    <x-tickets.sla :ticket="$ticket" />
</a>