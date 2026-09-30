<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $company->company_name }} — Laporan Tiket</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #18181B;
            padding: 20px;
        }
        .header {
            border-bottom: 3px solid #C8102E;
            padding-bottom: 12px;
            margin-bottom: 16px;
            display: table;
            width: 100%;
        }
        .header-left { display: table-cell; vertical-align: middle; width: 70%; }
        .header-right { display: table-cell; text-align: right; vertical-align: middle; width: 30%; }
        .header h1 {
            font-size: 16px;
            color: #C8102E;
            margin-bottom: 2px;
            font-weight: bold;
        }
        .header p { font-size: 9px; color: #52525B; line-height: 1.4; }
        .header .logo-img {
            width: 42px;
            height: 42px;
            object-fit: contain;
            vertical-align: middle;
        }
        .header .logo {
            display: inline-block;
            width: 42px;
            height: 42px;
            background: #C8102E;
            color: white;
            border-radius: 8px;
            text-align: center;
            line-height: 42px;
            font-weight: bold;
            font-size: 14px;
            vertical-align: middle;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 16px;
            border-collapse: separate;
            border-spacing: 6px 0;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            border: 1px solid #E4E4E7;
            padding: 10px 6px;
            background: #FAFAFA;
            border-radius: 6px;
        }
        .summary-item strong {
            display: block;
            font-size: 16px;
            color: #C8102E;
            margin-bottom: 2px;
        }
        .summary-item span {
            font-size: 8px;
            color: #71717A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        table.data th {
            background: #C8102E;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: 600;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        table.data td {
            padding: 5px 4px;
            border-bottom: 1px solid #F0F0F1;
            color: #52525B;
        }
        table.data tr:nth-child(even) td { background: #FAFAFA; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: 600;
        }
        .badge-critical { background: #DC2626; color: white; }
        .badge-high { background: #FFF7ED; color: #9A3412; }
        .badge-medium { background: #EFF6FF; color: #1E40AF; }
        .badge-low { background: #F4F4F5; color: #52525B; }
        .breach { color: #DC2626; font-weight: bold; }
        .ok { color: #16A34A; font-weight: bold; }
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 2px solid #C8102E;
            text-align: center;
            font-size: 7px;
            color: #A1A1AA;
            line-height: 1.5;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #18181B;
            margin: 14px 0 6px;
            padding-bottom: 3px;
            border-bottom: 1px solid #E4E4E7;
        }
        .breakdown {
            display: table;
            width: 100%;
            margin-bottom: 16px;
            border-spacing: 6px 0;
        }
        .breakdown-cell {
            display: table-cell;
            vertical-align: top;
            background: #FAFAFA;
            border: 1px solid #E4E4E7;
            padding: 8px;
            border-radius: 6px;
            width: 33.33%;
        }
        .breakdown-title {
            font-size: 9px;
            font-weight: bold;
            color: #18181B;
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 1px solid #E4E4E7;
        }
        .breakdown-row {
            display: block;
            font-size: 8px;
            padding: 2px 0;
            color: #52525B;
        }
        .breakdown-row strong { float: right; color: #18181B; }
    </style>
</head>
<body>

    {{-- ============ HEADER (otomatis dari pengaturan) ============ --}}
    <div class="header">
        <div class="header-left">
            <div style="display: table;">
                <div style="display: table-cell; vertical-align: middle; padding-right: 10px;">
                    @if($company->logo_path && file_exists(public_path($company->logo_path)))
                        <img src="{{ public_path($company->logo_path) }}" 
                             class="logo-img" alt="Logo">
                    @else
                        <span class="logo">{{ $company->initials }}</span>
                    @endif
                </div>
                <div style="display: table-cell; vertical-align: middle;">
                    <h1>{{ $company->company_name }}</h1>
                    <p>
                        Laporan Tiket IT — {{ $company->app_name }}
                        @if($company->full_address)
                            <br>{{ $company->full_address }}
                        @endif
                        @if($company->phone || $company->email)
                            <br>
                            @if($company->phone) Telp: {{ $company->phone }} @endif
                            @if($company->phone && $company->email) · @endif
                            @if($company->email) {{ $company->email }} @endif
                            @if($company->website) · {{ $company->website }} @endif
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="header-right">
            <p style="font-size: 8px; color: #A1A1AA;">Periode Laporan</p>
            <p style="font-size: 10px; font-weight: bold; color: #18181B;">
                {{ \Carbon\Carbon::parse($filters['date_from'])->format('d M Y') }}
                —
                {{ \Carbon\Carbon::parse($filters['date_to'])->format('d M Y') }}
            </p>
            <p style="font-size: 7px; color: #A1A1AA; margin-top: 3px;">
                Dicetak: {{ now()->format('d M Y H:i') }}
            </p>
        </div>
    </div>

    {{-- Header text dari pengaturan --}}
    @if($company->report_header_text)
        <div style="background: #FFF8D8; padding: 6px 10px; border-radius: 6px; 
                    font-size: 8px; color: #713F12; margin-bottom: 12px;">
            {{ $company->report_header_text }}
        </div>
    @endif

    {{-- ============ SUMMARY ============ --}}
    <div class="summary">
        <div class="summary-item">
            <strong>{{ $data['summary']['total'] }}</strong>
            <span>Total Tiket</span>
        </div>
        <div class="summary-item">
            <strong>{{ $data['summary']['resolved'] }}</strong>
            <span>Resolved</span>
        </div>
        <div class="summary-item">
            <strong>{{ $data['summary']['sla_compliance'] }}%</strong>
            <span>SLA Compliance</span>
        </div>
        <div class="summary-item">
            <strong>{{ $data['summary']['breach'] }}</strong>
            <span>Breach</span>
        </div>
        <div class="summary-item">
            <strong>{{ $data['summary']['avg_hours'] }}j</strong>
            <span>Avg Resolution</span>
        </div>
    </div>

    {{-- ============ BREAKDOWN ============ --}}
    <div class="section-title">Breakdown Data</div>
    <div class="breakdown">
        <div class="breakdown-cell">
            <div class="breakdown-title">Per Kategori</div>
            @forelse($data['by_category'] as $cat)
                <div class="breakdown-row">
                    {{ $cat['name'] }}
                    <strong>{{ $cat['total'] }}</strong>
                </div>
            @empty
                <div class="breakdown-row" style="color:#A1A1AA;">Belum ada data</div>
            @endforelse
        </div>
        <div class="breakdown-cell">
            <div class="breakdown-title">Per Cabang</div>
            @forelse($data['by_branch'] as $branch)
                <div class="breakdown-row">
                    {{ $branch['name'] }}
                    <strong>{{ $branch['total'] }}</strong>
                </div>
            @empty
                <div class="breakdown-row" style="color:#A1A1AA;">Belum ada data</div>
            @endforelse
        </div>
        <div class="breakdown-cell">
            <div class="breakdown-title">Per Teknisi</div>
            @forelse($data['by_technician'] as $tech)
                <div class="breakdown-row">
                    {{ $tech['name'] }}
                    <strong>{{ $tech['resolved'] }}/{{ $tech['total'] }}</strong>
                </div>
            @empty
                <div class="breakdown-row" style="color:#A1A1AA;">Belum ada data</div>
            @endforelse
        </div>
    </div>

    {{-- ============ DETAIL TIKET ============ --}}
    <div class="section-title">Detail Tiket ({{ $data['tickets']->count() }})</div>
    <table class="data">
        <thead>
            <tr>
                <th style="width: 11%;">No. Tiket</th>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 22%;">Judul</th>
                <th style="width: 10%;">Kategori</th>
                <th style="width: 10%;">Requester</th>
                <th style="width: 10%;">Teknisi</th>
                <th style="width: 8%;">Prioritas</th>
                <th style="width: 9%;">Status</th>
                <th style="width: 7%;">SLA</th>
                <th style="width: 5%;">Durasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['tickets'] as $t)
                <tr>
                    <td style="font-family: monospace; font-weight: bold;">
                        {{ $t->ticket_number }}
                    </td>
                    <td>{{ $t->created_at->format('d/m/y') }}</td>
                    <td>{{ \Str::limit($t->title, 45) }}</td>
                    <td>{{ $t->category?->name ?? '-' }}</td>
                    <td>{{ \Str::limit($t->requester?->name ?? '-', 15) }}</td>
                    <td>{{ \Str::limit($t->assignee?->name ?? '—', 15) }}</td>
                    <td>
                        @php
                            $pClass = match($t->priority) {
                                'critical' => 'badge-critical',
                                'high' => 'badge-high',
                                'medium' => 'badge-medium',
                                default => 'badge-low',
                            };
                        @endphp
                        <span class="badge {{ $pClass }}">
                            {{ strtoupper($t->priority) }}
                        </span>
                    </td>
                    <td>{{ strtoupper(str_replace('_', ' ', $t->status)) }}</td>
                    <td class="{{ $t->is_sla_breached ? 'breach' : 'ok' }}">
                        {{ $t->is_sla_breached ? 'BREACH' : 'OK' }}
                    </td>
                    <td style="font-size: 7px;">
                        @if($t->resolved_at)
                            {{ round($t->created_at->diffInHours($t->resolved_at), 1) }}j
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px; color: #A1A1AA;">
                        Tidak ada data untuk filter ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ============ FOOTER (otomatis dari pengaturan) ============ --}}
    <div class="footer">
        @if($company->report_footer_text)
            {{ $company->report_footer_text }}<br>
        @endif
        {{ $company->company_name }}
        @if($company->full_address)
            · {{ $company->full_address }}
        @endif
        @if($company->phone)
            · Telp: {{ $company->phone }}
        @endif
        @if($company->email)
            · {{ $company->email }}
        @endif
        @if($company->website)
            · {{ $company->website }}
        @endif
        <br>
        Digenerate otomatis oleh {{ $company->app_name }} · 
        {{ now()->format('d M Y H:i') }} · 
        Total {{ $data['tickets']->count() }} tiket
    </div>

</body>
</html>