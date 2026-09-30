<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $filters = $this->getFilters($request);
        $data = $this->getReportData($filters);

        $categories = Category::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();
        $technicians = User::role('IT Support')->where('is_active', true)->get();

        return view('reports.index', compact(
            'data', 'filters', 'categories', 'branches', 'technicians'
        ));
    }

    public function exportPdf(Request $request)
    {
        $this->authorizeAccess();

        $filters = $this->getFilters($request);
        $data = $this->getReportData($filters);

        $pdf = Pdf::loadView('reports.pdf', compact('data', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-tiket-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $this->authorizeAccess();

        $filters = $this->getFilters($request);
        $data = $this->getReportData($filters);

        return Excel::download(
            new \App\Exports\TicketReportExport($data, $filters),
            'laporan-tiket-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    private function authorizeAccess(): void
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Supervisor', 'Auditor'])) {
            abort(403);
        }
    }

    private function getFilters(Request $request): array
    {
        return [
            'date_from' => $request->input('date_from', now()->startOfMonth()->toDateString()),
            'date_to' => $request->input('date_to', now()->toDateString()),
            'category_id' => $request->input('category_id'),
            'branch_id' => $request->input('branch_id'),
            'assigned_to_id' => $request->input('assigned_to_id'),
            'status' => $request->input('status'),
            'priority' => $request->input('priority'),
        ];
    }

    private function getReportData(array $filters): array
    {
        $query = Ticket::with(['category', 'requester', 'assignee', 'branch'])
            ->whereBetween('created_at', [
                $filters['date_from'] . ' 00:00:00',
                $filters['date_to'] . ' 23:59:59',
            ]);

        if ($filters['category_id']) {
            $query->where('category_id', $filters['category_id']);
        }
        if ($filters['branch_id']) {
            $query->where('branch_id', $filters['branch_id']);
        }
        if ($filters['assigned_to_id']) {
            $query->where('assigned_to_id', $filters['assigned_to_id']);
        }
        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }
        if ($filters['priority']) {
            $query->where('priority', $filters['priority']);
        }

        $tickets = (clone $query)->latest()->get();

        // Summary stats
        $total = $tickets->count();
        $resolved = $tickets->whereIn('status', ['resolved', 'closed'])->count();
        $onTime = $tickets->whereIn('status', ['resolved', 'closed'])
            ->filter(fn($t) => $t->resolved_at && $t->sla_resolution_deadline && 
                              $t->resolved_at <= $t->sla_resolution_deadline)
            ->count();
        $breach = $tickets->where('is_sla_breached', true)->count();

        $slaCompliance = $resolved > 0 ? round(($onTime / $resolved) * 100, 1) : 0;

        // Avg resolution time
        $avgHours = 0;
        $resolvedTickets = $tickets->whereNotNull('resolved_at');
        if ($resolvedTickets->count() > 0) {
            $totalMinutes = $resolvedTickets->sum(fn($t) => 
                $t->created_at->diffInMinutes($t->resolved_at)
            );
            $avgHours = round($totalMinutes / $resolvedTickets->count() / 60, 1);
        }

        // Group by category
        $byCategory = $tickets->groupBy('category_id')
            ->map(fn($group) => [
                'name' => $group->first()->category?->name ?? '-',
                'icon' => $group->first()->category?->icon ?? '❓',
                'total' => $group->count(),
                'resolved' => $group->whereIn('status', ['resolved', 'closed'])->count(),
            ])->values();

        // Group by branch
        $byBranch = $tickets->groupBy('branch_id')
            ->map(fn($group) => [
                'name' => $group->first()->branch?->name ?? 'Tanpa Cabang',
                'total' => $group->count(),
                'resolved' => $group->whereIn('status', ['resolved', 'closed'])->count(),
            ])->values();

        // Group by technician
        $byTechnician = $tickets->whereNotNull('assigned_to_id')
            ->groupBy('assigned_to_id')
            ->map(fn($group) => [
                'name' => $group->first()->assignee?->name ?? '-',
                'total' => $group->count(),
                'resolved' => $group->whereIn('status', ['resolved', 'closed'])->count(),
                'avg_hours' => $this->calcAvgHours($group),
            ])->values();

        return [
            'tickets' => $tickets,
            'summary' => [
                'total' => $total,
                'resolved' => $resolved,
                'sla_compliance' => $slaCompliance,
                'breach' => $breach,
                'avg_hours' => $avgHours,
            ],
            'by_category' => $byCategory,
            'by_branch' => $byBranch,
            'by_technician' => $byTechnician,
        ];
    }

    private function calcAvgHours($group): float
    {
        $resolved = $group->whereNotNull('resolved_at');
        if ($resolved->count() === 0) return 0;

        $totalMinutes = $resolved->sum(fn($t) => $t->created_at->diffInMinutes($t->resolved_at));
        return round($totalMinutes / $resolved->count() / 60, 1);
    }
}