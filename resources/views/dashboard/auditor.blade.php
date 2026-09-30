@extends('layouts.app')

@section('title', 'Dashboard Auditor')

@section('content')
<x-ui.page-header 
    title="Audit Dashboard"
    subtitle="Traceability dan compliance sistem." />

<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
    <x-dashboard.stat-card label="Total Tiket" :value="$stats['total_tickets']" />
    <x-dashboard.stat-card label="SLA Compliance" :value="$stats['sla_compliance'] . '%'" color="success" />
    <x-dashboard.stat-card label="Breach" :value="$stats['overdue']" color="danger" />
    <x-dashboard.stat-card label="Closed" :value="$stats['resolved']" />
</div>

<div class="bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <div class="px-5 lg:px-6 py-4 border-b border-[#F0F0F1]">
        <h2 class="text-[15px] font-semibold text-[#18181B]">Audit Trail Terbaru</h2>
    </div>

    <div class="p-5 lg:p-6">
        @forelse($recentActivities ?? [] as $activity)
            <x-tickets.activity-item :activity="$activity" :last="$loop->last" />
        @empty
            <x-ui.empty-state 
                title="Belum ada aktivitas"
                description="Belum ada aktivitas yang tercatat." />
        @endforelse
    </div>
</div>
@endsection