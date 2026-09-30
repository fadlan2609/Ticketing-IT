@extends('layouts.app')

@section('title', 'Dashboard IT Support')

@section('content')
<x-ui.page-header 
    title="Dashboard IT Support"
    subtitle="Ringkasan tiket dan SLA yang perlu ditangani." />

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-4 mb-6">
    <x-dashboard.stat-card label="Assigned" :value="$stats['total_tickets']" />
    <x-dashboard.stat-card label="Open" :value="$stats['pending']" color="info" />
    <x-dashboard.stat-card label="In Progress" :value="0" color="warning" />
    <x-dashboard.stat-card label="SLA At Risk" :value="$stats['overdue']" color="warning" />
    <x-dashboard.stat-card label="Overdue" :value="$stats['overdue']" color="danger" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
    {{-- SLA Performance --}}
    <x-dashboard.sla-card :percent="$stats['sla_compliance']" />

    {{-- Today --}}
    <x-dashboard.stat-card 
        label="Selesai Hari Ini" 
        :value="$todayResolved ?? 0" 
        color="success"
        hint="Resolved hari ini" />

    {{-- Avg Resolution --}}
    <x-dashboard.stat-card 
        label="Avg Resolution" 
        :value="$stats['avg_resolution_hours'] . ' jam'" 
        color="brand"
        hint="Bulan ini" />
</div>

{{-- Urgent Tickets --}}
<div class="bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)] mb-6">
    <div class="flex items-center justify-between px-5 lg:px-6 py-4 border-b border-[#F0F0F1]">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-[#DC2626] animate-pulse"></div>
            <h2 class="text-[15px] font-semibold text-[#18181B]">Tiket Perlu Aksi</h2>
        </div>
        <a href="{{ route('tickets.index') }}" 
           class="text-[13px] font-medium text-[#C8102E] hover:text-[#A50D25]">
            Lihat semua →
        </a>
    </div>

    <div class="divide-y divide-[#F0F0F1]">
        @forelse($myTickets as $ticket)
            <a href="{{ route('tickets.show', $ticket) }}"
               class="block px-5 lg:px-6 py-4 hover:bg-[#FAFAFA] transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="app-ticket-number">{{ $ticket->ticket_number }}</span>
                            <x-tickets.priority :priority="$ticket->priority" />
                            <x-tickets.status :status="$ticket->status" />
                        </div>
                        <p class="text-[14px] font-medium text-[#18181B] truncate">
                            {{ $ticket->title }}
                        </p>
                        <p class="text-[12px] text-[#71717A] mt-0.5">
                            {{ $ticket->requester->name }} · 
                            {{ $ticket->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <x-tickets.sla :ticket="$ticket" :compact="true" />
                </div>
            </a>
        @empty
            <x-ui.empty-state 
                icon="ticket"
                title="Tidak ada tiket"
                description="Tidak ada tiket yang perlu ditangani saat ini." />
        @endforelse
    </div>
</div>
@endsection