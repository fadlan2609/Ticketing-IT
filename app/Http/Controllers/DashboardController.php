<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard sesuai role user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Statistik dasar berdasarkan role
        $stats = $this->getStats($user);

        // ============ Super Admin / Supervisor ============
        if ($user->hasAnyRole(['Super Admin', 'Supervisor'])) {
            $chartData = $this->getChartData($user);
            $topIssues = $this->getTopIssues($user);
            $technicianPerformance = $this->getTechnicianPerformance();

            return view('dashboard.manager', compact(
                'stats',
                'chartData',
                'topIssues',
                'technicianPerformance'
            ));
        }

        // ============ IT Support ============
        if ($user->hasRole('IT Support')) {
            $myTickets = Ticket::where('assigned_to_id', $user->id)
                ->whereIn('status', ['assigned', 'in_progress', 'pending', 'reopened'])
                ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
                ->orderBy('sla_resolution_deadline', 'asc')
                ->limit(10)
                ->get();

            // Tiket baru yang belum di-assign (queue)
            $queueTickets = Ticket::whereNull('assigned_to_id')
                ->where('status', 'new')
                ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
                ->orderBy('created_at', 'asc')
                ->limit(5)
                ->get();

            // Performa hari ini
            $todayResolved = Ticket::where('assigned_to_id', $user->id)
                ->whereDate('resolved_at', today())
                ->count();

            return view('dashboard.it-support', compact(
                'stats',
                'myTickets',
                'queueTickets',
                'todayResolved'
            ));
        }

        // ============ Auditor ============
        if ($user->hasRole('Auditor')) {
            $recentActivities = \App\Models\TicketActivity::with(['ticket', 'user'])
                ->latest()
                ->limit(20)
                ->get();

            return view('dashboard.auditor', compact('stats', 'recentActivities'));
        }

        // ============ Requester (default) ============
        $myTickets = Ticket::where('requester_id', $user->id)
            ->with(['category', 'assignee'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.requester', compact('stats', 'myTickets'));
    }

    /**
     * Hitung statistik berdasarkan role
     */
    private function getStats(User $user): array
    {
        $query = Ticket::query();

        // Filter berdasarkan role
        if ($user->hasRole('Requester')) {
            $query->where('requester_id', $user->id);
        } elseif ($user->hasRole('IT Support')) {
            $query->where('assigned_to_id', $user->id);
        } elseif ($user->hasRole('Supervisor') && $user->branch_id) {
            $query->where('branch_id', $user->branch_id);
        }
        // Super Admin & Auditor lihat semua

        $activeStatuses = ['new', 'assigned', 'in_progress', 'pending', 'reopened'];

        $total = (clone $query)->count();
        $pending = (clone $query)->whereIn('status', $activeStatuses)->count();
        $resolved = (clone $query)->whereIn('status', ['resolved', 'closed'])->count();

        // Overdue: aktif tapi SLA sudah lewat
        $overdue = (clone $query)
            ->whereIn('status', $activeStatuses)
            ->where('sla_resolution_deadline', '<', now())
            ->count();

        // SLA compliance % (bulan ini)
        $monthQuery = (clone $query)->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year);
        $monthTotal = (clone $monthQuery)->whereIn('status', ['resolved', 'closed'])->count();
        $monthOnTime = (clone $monthQuery)
            ->whereIn('status', ['resolved', 'closed'])
            ->where(function ($q) {
                $q->whereNull('sla_resolution_deadline')
                  ->orWhereColumn('resolved_at', '<=', 'sla_resolution_deadline');
            })
            ->count();

        $slaCompliance = $monthTotal > 0 
            ? round(($monthOnTime / $monthTotal) * 100, 1) 
            : 0;

        // Avg resolution time (dalam jam)
        $resolvedTickets = (clone $query)
            ->whereNotNull('resolved_at')
            ->whereMonth('resolved_at', now()->month)
            ->get();

        $avgResolutionHours = 0;
        if ($resolvedTickets->count() > 0) {
            $totalMinutes = $resolvedTickets->sum(function ($t) {
                return $t->created_at->diffInMinutes($t->resolved_at);
            });
            $avgResolutionHours = round($totalMinutes / $resolvedTickets->count() / 60, 1);
        }

        return [
            'total_tickets' => $total,
            'pending' => $pending,
            'resolved' => $resolved,
            'overdue' => $overdue,
            'sla_compliance' => $slaCompliance,
            'avg_resolution_hours' => $avgResolutionHours,
        ];
    }

    /**
     * Data untuk chart di dashboard manager
     */
    private function getChartData(User $user): array
    {
        $query = Ticket::query();

        if ($user->hasRole('Supervisor') && $user->branch_id) {
            $query->where('branch_id', $user->branch_id);
        }

        // Tiket per kategori (bulan ini)
        $byCategory = (clone $query)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(fn($item) => [
                'label' => $item->category?->name ?? 'Unknown',
                'icon' => $item->category?->icon ?? '❓',
                'total' => $item->total,
            ]);

        // Distribusi status (bulan ini)
        $byStatus = (clone $query)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Tiket per cabang
        $byBranch = (clone $query)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('branch_id, COUNT(*) as total')
            ->groupBy('branch_id')
            ->with('branch')
            ->get()
            ->map(fn($item) => [
                'label' => $item->branch?->name ?? 'Unknown',
                'total' => $item->total,
            ]);

        // Tren 7 hari terakhir
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trend[] = [
                'date' => $date->format('d M'),
                'created' => (clone $query)->whereDate('created_at', $date)->count(),
                'resolved' => (clone $query)->whereDate('resolved_at', $date)->count(),
            ];
        }

        return [
            'by_category' => $byCategory,
            'by_status' => $byStatus,
            'by_branch' => $byBranch,
            'trend' => $trend,
        ];
    }

    /**
     * Top 10 recurring issues (bulan ini)
     */
    private function getTopIssues(User $user): array
    {
        $query = Ticket::query();

        if ($user->hasRole('Supervisor') && $user->branch_id) {
            $query->where('branch_id', $user->branch_id);
        }

        return (clone $query)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'category' => $item->category?->name ?? 'Unknown',
                'icon' => $item->category?->icon ?? '❓',
                'total' => $item->total,
            ])
            ->toArray();
    }

    /**
     * Performa teknisi (leaderboard bulan ini)
     */
    private function getTechnicianPerformance(): array
    {
        return User::role('IT Support')
            ->where('is_active', true)
            ->get()
            ->map(function ($user) {
                $resolved = Ticket::where('assigned_to_id', $user->id)
                    ->whereMonth('resolved_at', now()->month)
                    ->whereNotNull('resolved_at')
                    ->count();

                $avgHours = 0;
                $tickets = Ticket::where('assigned_to_id', $user->id)
                    ->whereMonth('resolved_at', now()->month)
                    ->whereNotNull('resolved_at')
                    ->get();

                if ($tickets->count() > 0) {
                    $totalMinutes = $tickets->sum(fn($t) => 
                        $t->created_at->diffInMinutes($t->resolved_at)
                    );
                    $avgHours = round($totalMinutes / $tickets->count() / 60, 1);
                }

                return [
                    'name' => $user->name,
                    'resolved' => $resolved,
                    'avg_hours' => $avgHours,
                ];
            })
            ->sortByDesc('resolved')
            ->take(5)
            ->values()
            ->toArray();
    }
}