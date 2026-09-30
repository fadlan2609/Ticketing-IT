@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
<x-ui.page-header 
    title="Laporan"
    subtitle="Analisis data tiket, SLA, dan performa tim.">
    <div class="flex items-center gap-2">
        <a href="{{ route('reports.export.pdf', request()->query()) }}" 
           target="_blank"
           class="app-btn app-btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            PDF
        </a>
        <a href="{{ route('reports.export.excel', request()->query()) }}" 
           class="app-btn app-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Excel
        </a>
    </div>
</x-ui.page-header>

{{-- Filters --}}
<form method="GET" class="bg-white border border-[#E4E4E7] rounded-2xl p-4 lg:p-5 mb-6
                          shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <div>
            <label class="app-label text-[12px]">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                   class="w-full px-3 py-2 text-[13px] bg-white 
                          border border-[#E4E4E7] rounded-xl outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
        </div>
        <div>
            <label class="app-label text-[12px]">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                   class="w-full px-3 py-2 text-[13px] bg-white 
                          border border-[#E4E4E7] rounded-xl outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
        </div>
        <div>
            <label class="app-label text-[12px]">Kategori</label>
            <select name="category_id"
                    class="w-full px-3 py-2 text-[13px] bg-white 
                           border border-[#E4E4E7] rounded-xl outline-none 
                           focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                           transition appearance-none cursor-pointer">
                <option value="">Semua</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected($filters['category_id'] == $cat->id)>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="app-label text-[12px]">Cabang</label>
            <select name="branch_id"
                    class="w-full px-3 py-2 text-[13px] bg-white 
                           border border-[#E4E4E7] rounded-xl outline-none 
                           focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                           transition appearance-none cursor-pointer">
                <option value="">Semua</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" @selected($filters['branch_id'] == $branch->id)>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="app-label text-[12px]">Teknisi</label>
            <select name="assigned_to_id"
                    class="w-full px-3 py-2 text-[13px] bg-white 
                           border border-[#E4E4E7] rounded-xl outline-none 
                           focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                           transition appearance-none cursor-pointer">
                <option value="">Semua</option>
                @foreach($technicians as $tech)
                    <option value="{{ $tech->id }}" @selected($filters['assigned_to_id'] == $tech->id)>
                        {{ $tech->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="app-label text-[12px]">Status</label>
            <select name="status"
                    class="w-full px-3 py-2 text-[13px] bg-white 
                           border border-[#E4E4E7] rounded-xl outline-none 
                           focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                           transition appearance-none cursor-pointer">
                <option value="">Semua</option>
                @foreach(['new', 'assigned', 'in_progress', 'pending', 'resolved', 'closed', 'reopened'] as $s)
                    <option value="{{ $s }}" @selected($filters['status'] === $s)>
                        {{ ucwords(str_replace('_', ' ', $s)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="app-label text-[12px]">Prioritas</label>
            <select name="priority"
                    class="w-full px-3 py-2 text-[13px] bg-white 
                           border border-[#E4E4E7] rounded-xl outline-none 
                           focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                           transition appearance-none cursor-pointer">
                <option value="">Semua</option>
                @foreach(['critical' => 'Critical', 'high' => 'High', 'medium' => 'Normal', 'low' => 'Low'] as $val => $label)
                    <option value="{{ $val }}" @selected($filters['priority'] === $val)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="app-btn app-btn-primary flex-1 text-[13px] py-2">
                Tampilkan
            </button>
            <a href="{{ route('reports.index') }}" 
               class="app-btn app-btn-secondary text-[13px] py-2">
                Reset
            </a>
        </div>
    </div>
</form>

{{-- Summary Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-4 mb-6">
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <p class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider">Total</p>
        <p class="text-[32px] font-bold text-[#18181B] leading-none mt-2 tracking-tight">
            {{ $data['summary']['total'] }}
        </p>
    </div>
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <p class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider">Resolved</p>
        <p class="text-[32px] font-bold text-[#16A34A] leading-none mt-2 tracking-tight">
            {{ $data['summary']['resolved'] }}
        </p>
    </div>
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <p class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider">SLA</p>
        <p class="text-[32px] font-bold text-[#C8102E] leading-none mt-2 tracking-tight">
            {{ $data['summary']['sla_compliance'] }}<span class="text-[18px]">%</span>
        </p>
    </div>
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <p class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider">Breach</p>
        <p class="text-[32px] font-bold text-[#DC2626] leading-none mt-2 tracking-tight">
            {{ $data['summary']['breach'] }}
        </p>
    </div>
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <p class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider">Avg</p>
        <p class="text-[32px] font-bold text-[#18181B] leading-none mt-2 tracking-tight">
            {{ $data['summary']['avg_hours'] }}<span class="text-[18px]">j</span>
        </p>
    </div>
</div>

{{-- Breakdown by Category / Branch / Technician --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">

    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <h3 class="text-[15px] font-semibold text-[#18181B] mb-4">Per Kategori</h3>
        @forelse($data['by_category'] as $cat)
            <div class="flex items-center justify-between py-2 border-b border-[#F0F0F1] last:border-0">
                <span class="text-[13px] text-[#52525B]">{{ $cat['icon'] }} {{ $cat['name'] }}</span>
                <span class="text-[13px] font-semibold text-[#18181B]">{{ $cat['total'] }}</span>
            </div>
        @empty
            <p class="text-[13px] text-[#71717A] italic">Belum ada data</p>
        @endforelse
    </div>

    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <h3 class="text-[15px] font-semibold text-[#18181B] mb-4">Per Cabang</h3>
        @forelse($data['by_branch'] as $branch)
            <div class="flex items-center justify-between py-2 border-b border-[#F0F0F1] last:border-0">
                <span class="text-[13px] text-[#52525B]">{{ $branch['name'] }}</span>
                <span class="text-[13px] font-semibold text-[#18181B]">{{ $branch['total'] }}</span>
            </div>
        @empty
            <p class="text-[13px] text-[#71717A] italic">Belum ada data</p>
        @endforelse
    </div>

    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <h3 class="text-[15px] font-semibold text-[#18181B] mb-4">Per Teknisi</h3>
        @forelse($data['by_technician'] as $tech)
            <div class="flex items-center justify-between py-2 border-b border-[#F0F0F1] last:border-0">
                <span class="text-[13px] text-[#52525B]">{{ $tech['name'] }}</span>
                <span class="text-[13px] font-semibold text-[#16A34A]">
                    {{ $tech['resolved'] }}/{{ $tech['total'] }}
                </span>
            </div>
        @empty
            <p class="text-[13px] text-[#71717A] italic">Belum ada data</p>
        @endforelse
    </div>
</div>

{{-- Detail Table --}}
<div class="bg-white border border-[#E4E4E7] rounded-2xl overflow-hidden
            shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <div class="px-5 lg:px-6 py-4 border-b border-[#F0F0F1]">
        <h3 class="text-[15px] font-semibold text-[#18181B]">
            Detail Tiket ({{ $data['tickets']->count() }})
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-[13px]">
            <thead>
                <tr class="bg-[#FAFAFA] border-b border-[#E4E4E7]">
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3 whitespace-nowrap">No. Tiket</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3 whitespace-nowrap">Tanggal</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3">Judul</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3 whitespace-nowrap">Kategori</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3 whitespace-nowrap">Requester</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3 whitespace-nowrap">Teknisi</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3 whitespace-nowrap">Prioritas</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3 whitespace-nowrap">Status</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-4 py-3 whitespace-nowrap">SLA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['tickets']->take(50) as $t)
                    <tr class="border-b border-[#F0F0F1] last:border-0 hover:bg-[#FAFAFA] transition">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="app-ticket-number">{{ $t->ticket_number }}</span>
                        </td>
                        <td class="px-4 py-3 text-[#52525B] whitespace-nowrap">
                            {{ $t->created_at->format('d/m/y H:i') }}
                        </td>
                        <td class="px-4 py-3 max-w-xs">
                            <p class="text-[#18181B] font-medium truncate">{{ $t->title }}</p>
                        </td>
                        <td class="px-4 py-3 text-[#52525B] whitespace-nowrap">
                            {{ $t->category?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-[#52525B] whitespace-nowrap">
                            {{ $t->requester?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-[#52525B] whitespace-nowrap">
                            {{ $t->assignee?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <x-tickets.priority :priority="$t->priority" />
                        </td>
                        <td class="px-4 py-3">
                            <x-tickets.status :status="$t->status" />
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($t->is_sla_breached)
                                <span class="inline-flex items-center gap-1 text-[11px] 
                                             font-semibold text-[#DC2626]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#DC2626]"></span>
                                    BREACH
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] 
                                             font-semibold text-[#16A34A]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                    OK
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-[#71717A]">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-[#D4D4D8]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                          d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-[13px]">Tidak ada data untuk filter ini</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($data['tickets']->count() > 50)
        <div class="px-5 lg:px-6 py-3 bg-[#FAFAFA] border-t border-[#F0F0F1] text-center">
            <p class="text-[12px] text-[#71717A]">
                Menampilkan 50 dari {{ $data['tickets']->count() }} tiket. 
                Export untuk data lengkap.
            </p>
        </div>
    @endif
</div>
@endsection