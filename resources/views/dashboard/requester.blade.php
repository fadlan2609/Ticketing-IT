@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
{{-- Hero --}}
<div class="mb-8">
    <p class="text-[13px] font-medium text-[#C8102E] uppercase tracking-wider mb-1">
        Service Desk
    </p>
    <h1 class="text-[28px] lg:text-[32px] font-bold text-[#18181B] tracking-[-0.02em]">
        Selamat datang, {{ auth()->user()->name }}
    </h1>
    <p class="text-[15px] text-[#52525B] mt-2">
        Ada yang bisa kami bantu hari ini?
    </p>
</div>

{{-- Search / CTA --}}
<div class="bg-white border border-[#E4E4E7] rounded-2xl p-2 mb-6 
            flex flex-col sm:flex-row gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <a href="{{ route('kb.index') }}"
       class="flex-1 flex items-center gap-3 px-4 py-3 rounded-xl 
              hover:bg-[#FAFAFA] transition group">
        <svg class="w-5 h-5 text-[#A1A1AA] group-hover:text-[#C8102E]" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <span class="text-[14px] text-[#71717A]">Cari solusi di Knowledge Base...</span>
    </a>
    <a href="{{ route('tickets.create') }}"
       class="inline-flex items-center justify-center gap-2 px-5 py-3 
              bg-[#C8102E] hover:bg-[#A50D25] text-white text-[14px] font-medium 
              rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Tiket Baru
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-8">
    <x-dashboard.stat-card label="Total Tiket" :value="$stats['total_tickets']" />
    <x-dashboard.stat-card label="Diproses" :value="$stats['pending']" color="warning" />
    <x-dashboard.stat-card label="Selesai" :value="$stats['resolved']" color="success" />
    <x-dashboard.stat-card label="Overdue" :value="$stats['overdue']" color="danger" />
</div>

{{-- Recent tickets --}}
<div class="bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <div class="flex items-center justify-between px-5 lg:px-6 py-4 border-b border-[#F0F0F1]">
        <h2 class="text-[15px] font-semibold text-[#18181B]">Tiket Terbaru Saya</h2>
        <a href="{{ route('tickets.index') }}" 
           class="text-[13px] font-medium text-[#C8102E] hover:text-[#A50D25] transition">
            Lihat semua →
        </a>
    </div>

    <div class="divide-y divide-[#F0F0F1]">
        @forelse($myTickets as $ticket)
            <a href="{{ route('tickets.show', $ticket) }}"
               class="flex items-center gap-4 px-5 lg:px-6 py-4 hover:bg-[#FAFAFA] transition">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="app-ticket-number">{{ $ticket->ticket_number }}</span>
                        <x-tickets.status :status="$ticket->status" />
                    </div>
                    <p class="text-[14px] font-medium text-[#18181B] truncate">
                        {{ $ticket->title }}
                    </p>
                    <p class="text-[12px] text-[#71717A] mt-0.5">
                        {{ $ticket->created_at->diffForHumans() }}
                    </p>
                </div>
                <x-tickets.priority :priority="$ticket->priority" />
            </a>
        @empty
            <x-ui.empty-state 
                icon="ticket"
                title="Belum ada tiket"
                description="Anda belum pernah membuat tiket.">
                <x-ui.button href="{{ route('tickets.create') }}" icon="plus">
                    Buat Tiket Pertama
                </x-ui.button>
            </x-ui.empty-state>
        @endforelse
    </div>
</div>
@endsection