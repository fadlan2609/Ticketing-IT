@extends('layouts.app')

@section('title', 'Daftar Tiket')

@section('content')
<x-ui.page-header 
    title="Semua Tiket"
    subtitle="Kelola seluruh permintaan layanan.">
    @can('ticket.create')
        <x-ui.button href="{{ route('tickets.create') }}" icon="plus">
            Buat Tiket
        </x-ui.button>
    @endcan
</x-ui.page-header>

{{-- Filter --}}
<form method="GET" class="bg-white border border-[#E4E4E7] rounded-2xl p-4 lg:p-5 mb-6
                          shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="lg:col-span-2">
            <x-ui.input 
                name="search" 
                :value="request('search')"
                placeholder="Cari nomor tiket, judul, atau deskripsi..."
                icon="search" />
        </div>

        <x-ui.select 
            name="status"
            :selected="request('status')"
            :options="[
                'new' => 'Open',
                'assigned' => 'Assigned',
                'in_progress' => 'In Progress',
                'pending' => 'Pending',
                'resolved' => 'Resolved',
                'closed' => 'Closed',
                'reopened' => 'Reopened',
            ]"
            placeholder="Semua Status" />

        <x-ui.select 
            name="priority"
            :selected="request('priority')"
            :options="[
                'critical' => 'Critical',
                'high' => 'High',
                'medium' => 'Normal',
                'low' => 'Low',
            ]"
            placeholder="Semua Prioritas" />
    </div>

    <div class="flex items-center gap-2 mt-3">
        <x-ui.button type="submit" size="sm">Terapkan Filter</x-ui.button>
        @if(request()->hasAny(['search', 'status', 'priority', 'category_id', 'branch_id']))
            <x-ui.button href="{{ route('tickets.index') }}" variant="ghost" size="sm">
                Reset
            </x-ui.button>
        @endif
    </div>
</form>

{{-- List --}}
@if($tickets->count() > 0)
    {{-- Desktop Table --}}
    <div class="hidden lg:block bg-white border border-[#E4E4E7] rounded-2xl overflow-hidden
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <table class="w-full">
            <thead>
                <tr class="bg-[#FAFAFA] border-b border-[#E4E4E7]">
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Tiket</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Subjek</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Requester</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Prioritas</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Status</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">SLA</th>
                    <th class="w-12"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                    <tr class="border-b border-[#F0F0F1] last:border-0 hover:bg-[#FAFAFA] transition cursor-pointer"
                        onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                        <td class="px-5 py-4">
                            <span class="app-ticket-number">{{ $ticket->ticket_number }}</span>
                        </td>
                        <td class="px-5 py-4 max-w-md">
                            <p class="text-[14px] font-medium text-[#18181B] truncate">
                                {{ $ticket->title }}
                            </p>
                            <p class="text-[12px] text-[#71717A] mt-0.5">
                                {{ $ticket->category?->name ?? '-' }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-[13px] text-[#18181B]">{{ $ticket->requester->name }}</p>
                            <p class="text-[11px] text-[#71717A]">
                                {{ $ticket->created_at->diffForHumans() }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            <x-tickets.priority :priority="$ticket->priority" />
                        </td>
                        <td class="px-5 py-4">
                            <x-tickets.status :status="$ticket->status" />
                        </td>
                        <td class="px-5 py-4 min-w-[140px]">
                            <x-tickets.sla :ticket="$ticket" :compact="true" />
                        </td>
                        <td class="px-3 py-4 text-right">
                            <svg class="w-4 h-4 text-[#A1A1AA]" fill="none" 
                                 stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="lg:hidden space-y-3">
        @foreach($tickets as $ticket)
            <x-tickets.card :ticket="$ticket" />
        @endforeach
    </div>

    <div class="mt-6">
        {{ $tickets->links() }}
    </div>
@else
    <div class="bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <x-ui.empty-state 
            icon="ticket"
            title="Belum ada tiket"
            description="Tidak ada tiket yang sesuai dengan filter saat ini.">
            @if(request()->hasAny(['search', 'status', 'priority']))
                <x-ui.button href="{{ route('tickets.index') }}" variant="secondary">
                    Reset Filter
                </x-ui.button>
            @else
                @can('ticket.create')
                    <x-ui.button href="{{ route('tickets.create') }}" icon="plus">
                        Buat Tiket
                    </x-ui.button>
                @endcan
            @endif
        </x-ui.empty-state>
    </div>
@endif
@endsection