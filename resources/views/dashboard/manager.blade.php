@extends('layouts.app')

@section('title', 'Dashboard Manager')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<x-ui.page-header 
    title="Executive Dashboard"
    subtitle="Performa tim dan SLA bulan ini." />

{{-- KPI --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
    <x-dashboard.stat-card 
        label="Total Tiket" 
        :value="$stats['total_tickets']"
        :hint="'Bulan ' . now()->translatedFormat('F Y')" />
    <x-dashboard.stat-card 
        label="SLA Compliance" 
        :value="$stats['sla_compliance'] . '%'" 
        color="success" />
    <x-dashboard.stat-card 
        label="Avg Resolution" 
        :value="$stats['avg_resolution_hours'] . 'j'" 
        color="brand" />
    <x-dashboard.stat-card 
        label="SLA Breach" 
        :value="$stats['overdue']" 
        color="danger" />
</div>

{{-- Trend --}}
<div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6 mb-6 
            shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
        Tren 7 Hari Terakhir
    </h2>
    <div class="h-64">
        <canvas id="trendChart"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6">
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6 
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
            Tiket per Kategori
        </h2>
        <div class="h-64">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6 
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
            Distribusi Status
        </h2>
        <div class="h-64">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
    {{-- Top Issues --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6 
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
            Top Issues Bulan Ini
        </h2>
        @forelse($topIssues as $i => $issue)
            <div class="flex items-center gap-3 py-2.5 border-b border-[#F0F0F1] last:border-0">
                <span class="w-6 h-6 rounded-lg bg-[#F4F4F5] text-[#52525B]
                             flex items-center justify-center text-[11px] font-bold">
                    {{ $i + 1 }}
                </span>
                <span class="flex-1 text-[13px] text-[#18181B]">{{ $issue['category'] }}</span>
                <span class="text-[13px] font-bold text-[#C8102E]">{{ $issue['total'] }}</span>
            </div>
        @empty
            <p class="text-[13px] text-[#71717A] text-center py-4">Belum ada data</p>
        @endforelse
    </div>

    {{-- Leaderboard --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6 
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
            Performa Teknisi
        </h2>
        @forelse($technicianPerformance as $i => $tech)
            <div class="flex items-center gap-3 py-2.5 border-b border-[#F0F0F1] last:border-0">
                <span class="w-6 h-6 rounded-lg 
                             {{ $i === 0 ? 'bg-[#FEF3C7] text-[#A16207]' : 'bg-[#F4F4F5] text-[#52525B]' }}
                             flex items-center justify-center text-[11px] font-bold">
                    {{ $i + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] font-medium text-[#18181B] truncate">{{ $tech['name'] }}</p>
                    <p class="text-[11px] text-[#71717A]">Avg {{ $tech['avg_hours'] }} jam</p>
                </div>
                <span class="text-[13px] font-bold text-[#16A34A]">{{ $tech['resolved'] }}</span>
            </div>
        @empty
            <p class="text-[13px] text-[#71717A] text-center py-4">Belum ada data</p>
        @endforelse
    </div>
</div>

<script>
const trendData = @json($chartData['trend']);
const categoryData = @json($chartData['by_category']);
const statusData = @json($chartData['by_status']);

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: trendData.map(d => d.date),
        datasets: [
            {
                label: 'Dibuat',
                data: trendData.map(d => d.created),
                borderColor: '#C8102E',
                backgroundColor: 'rgba(200, 16, 46, 0.08)',
                tension: 0.35,
                fill: true,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#C8102E',
            },
            {
                label: 'Resolved',
                data: trendData.map(d => d.resolved),
                borderColor: '#16A34A',
                backgroundColor: 'rgba(22, 163, 74, 0.08)',
                tension: 0.35,
                fill: true,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#16A34A',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 8, boxHeight: 8, font: { size: 11 }, padding: 16 } }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#F0F0F1' }, ticks: { font: { size: 11 }, color: '#71717A' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#71717A' } }
        }
    }
});

new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: categoryData.map(c => c.label),
        datasets: [{
            label: 'Tiket',
            data: categoryData.map(c => c.total),
            backgroundColor: '#C8102E',
            borderRadius: 6,
            barThickness: 24,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#F0F0F1' }, ticks: { font: { size: 11 }, color: '#71717A' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#71717A' } }
        }
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(s => s.replace('_', ' ')),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: ['#2563EB', '#F59E0B', '#16A34A', '#71717A', '#DC2626', '#A1A1AA'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 8, boxHeight: 8, font: { size: 11 }, padding: 12 } }
        }
    }
});
</script>
@endsection