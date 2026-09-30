<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketReportExport implements FromArray, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        protected array $data,
        protected array $filters
    ) {}

    public function array(): array
    {
        $rows = [];

        // Header info
        $rows[] = ['LAPORAN TIKET IT'];
        $rows[] = ['BPRS Amanah Bangsa'];
        $rows[] = ['Periode: ' . $this->filters['date_from'] . ' s/d ' . $this->filters['date_to']];
        $rows[] = [];

        // Summary
        $rows[] = ['RINGKASAN'];
        $rows[] = ['Total Tiket', $this->data['summary']['total']];
        $rows[] = ['Resolved/Closed', $this->data['summary']['resolved']];
        $rows[] = ['SLA Compliance', $this->data['summary']['sla_compliance'] . '%'];
        $rows[] = ['SLA Breach', $this->data['summary']['breach']];
        $rows[] = ['Avg Resolution (jam)', $this->data['summary']['avg_hours']];
        $rows[] = [];

        // Detail tiket
        $rows[] = ['DETAIL TIKET'];
        foreach ($this->data['tickets'] as $t) {
            $rows[] = [
                $t->ticket_number,
                $t->created_at->format('d/m/Y H:i'),
                $t->title,
                $t->category?->name,
                $t->requester?->name,
                $t->assignee?->name ?? '-',
                $t->branch?->name ?? '-',
                strtoupper($t->priority),
                strtoupper($t->status),
                $t->resolved_at ? $t->resolved_at->format('d/m/Y H:i') : '-',
                $t->is_sla_breached ? 'BREACH' : 'OK',
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Laporan Tiket';
    }
}