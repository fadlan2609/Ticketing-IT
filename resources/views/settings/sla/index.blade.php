@extends('layouts.app')

@section('title', 'Konfigurasi SLA')

@section('breadcrumb')
    <span class="text-[#18181B] font-medium">Pengaturan SLA</span>
@endsection

@section('content')
<x-ui.page-header 
    title="Konfigurasi SLA"
    subtitle="Atur Service Level Agreement, jam kerja, dan hari libur." />

<div class="max-w-4xl mx-auto space-y-6">

    {{-- SLA per Priority --}}
    <form action="{{ route('settings.sla.update') }}" method="POST"
          class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        @csrf @method('PUT')

        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#FDECEF] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">SLA per Prioritas</h2>
                <p class="text-[12px] text-[#71717A]">
                    Dalam menit. Perhitungan hanya menghitung jam kerja.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#E4E4E7]">
                        <th class="text-left text-[11px] font-semibold text-[#71717A] 
                                   uppercase tracking-wider py-2 pr-3">Prioritas</th>
                        <th class="text-left text-[11px] font-semibold text-[#71717A] 
                                   uppercase tracking-wider py-2 pr-3">Response</th>
                        <th class="text-left text-[11px] font-semibold text-[#71717A] 
                                   uppercase tracking-wider py-2">Resolution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($configs as $i => $config)
                        <tr class="border-b border-[#F0F0F1] last:border-0">
                            <td class="py-3 pr-3">
                                @php
                                    $labels = [
                                        'critical' => ['Critical', '#DC2626', '#FEF2F2'],
                                        'high' => ['High', '#D97706', '#FFF7ED'],
                                        'medium' => ['Normal', '#2563EB', '#EFF6FF'],
                                        'low' => ['Low', '#52525B', '#F4F4F5'],
                                    ];
                                    $l = $labels[$config->priority] ?? ['-', '#52525B', '#F4F4F5'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 
                                             rounded-full text-[11px] font-medium"
                                      style="background: {{ $l[2] }}; color: {{ $l[1] }};">
                                    <span class="w-1.5 h-1.5 rounded-full" 
                                          style="background: {{ $l[1] }};"></span>
                                    {{ $l[0] }}
                                </span>
                                <input type="hidden" name="configs[{{ $i }}][id]" value="{{ $config->id }}">
                            </td>
                            <td class="py-3 pr-3">
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           name="configs[{{ $i }}][response_minutes]"
                                           value="{{ $config->response_minutes }}" 
                                           min="1" required
                                           class="w-20 px-2.5 py-1.5 text-[13px] bg-white 
                                                  border border-[#E4E4E7] rounded-lg outline-none 
                                                  focus:border-[#C8102E] focus:ring-2 
                                                  focus:ring-[#FDECEF] transition">
                                    <span class="text-[11px] text-[#71717A]">
                                        menit · {{ $config->response_human }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           name="configs[{{ $i }}][resolution_minutes]"
                                           value="{{ $config->resolution_minutes }}" 
                                           min="1" required
                                           class="w-24 px-2.5 py-1.5 text-[13px] bg-white 
                                                  border border-[#E4E4E7] rounded-lg outline-none 
                                                  focus:border-[#C8102E] focus:ring-2 
                                                  focus:ring-[#FDECEF] transition">
                                    <span class="text-[11px] text-[#71717A]">
                                        menit · {{ $config->resolution_human }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-5 pt-4 border-t border-[#F0F0F1]">
            <button type="submit" class="app-btn app-btn-primary">
                Simpan SLA
            </button>
        </div>
    </form>

    {{-- Working Hours --}}
    <form action="{{ route('settings.sla.working-hours') }}" method="POST"
          class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        @csrf @method('PUT')

        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#FFF8D8] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#A16207]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Jam Kerja</h2>
                <p class="text-[12px] text-[#71717A]">
                    SLA hanya dihitung dalam jam kerja ini.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-[#E4E4E7]">
                        <th class="text-left text-[11px] font-semibold text-[#71717A] 
                                   uppercase tracking-wider py-2 pr-3">Hari</th>
                        <th class="text-left text-[11px] font-semibold text-[#71717A] 
                                   uppercase tracking-wider py-2 pr-3">Jam Buka</th>
                        <th class="text-left text-[11px] font-semibold text-[#71717A] 
                                   uppercase tracking-wider py-2 pr-3">Jam Tutup</th>
                        <th class="text-left text-[11px] font-semibold text-[#71717A] 
                                   uppercase tracking-wider py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workingHours as $i => $wh)
                        <tr class="border-b border-[#F0F0F1] last:border-0">
                            <td class="py-3 pr-3 font-medium text-[#18181B]">
                                {{ $wh->day_name }}
                                <input type="hidden" name="working_hours[{{ $i }}][id]" value="{{ $wh->id }}">
                            </td>
                            <td class="py-3 pr-3">
                                <input type="time" 
                                       name="working_hours[{{ $i }}][start_time]"
                                       value="{{ \Carbon\Carbon::parse($wh->start_time)->format('H:i') }}"
                                       class="px-2.5 py-1.5 text-[13px] bg-white 
                                              border border-[#E4E4E7] rounded-lg outline-none 
                                              focus:border-[#C8102E] focus:ring-2 
                                              focus:ring-[#FDECEF] transition">
                            </td>
                            <td class="py-3 pr-3">
                                <input type="time" 
                                       name="working_hours[{{ $i }}][end_time]"
                                       value="{{ \Carbon\Carbon::parse($wh->end_time)->format('H:i') }}"
                                       class="px-2.5 py-1.5 text-[13px] bg-white 
                                              border border-[#E4E4E7] rounded-lg outline-none 
                                              focus:border-[#C8102E] focus:ring-2 
                                              focus:ring-[#FDECEF] transition">
                            </td>
                            <td class="py-3">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" 
                                           name="working_hours[{{ $i }}][is_working_day]"
                                           value="1" @checked($wh->is_working_day)
                                           class="rounded border-[#E4E4E7] text-[#C8102E] 
                                                  focus:ring-[#FDECEF]">
                                    <span class="text-[12px] text-[#52525B]">Hari kerja</span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-5 pt-4 border-t border-[#F0F0F1]">
            <button type="submit" class="app-btn app-btn-primary">
                Simpan Jam Kerja
            </button>
        </div>
    </form>

    {{-- Holidays --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#ECFDF3] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#166534]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Hari Libur Nasional</h2>
                <p class="text-[12px] text-[#71717A]">
                    SLA tidak dihitung pada hari-hari ini.
                </p>
            </div>
        </div>

        {{-- Add Holiday Form --}}
        <form action="{{ route('settings.sla.holidays.store') }}" method="POST"
              class="flex flex-col sm:flex-row gap-2 mb-5 pb-5 border-b border-[#F0F0F1]">
            @csrf
            <input type="date" name="date" required
                   class="w-full sm:w-auto px-3 py-2 text-[13px] bg-white 
                          border border-[#E4E4E7] rounded-lg outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            <input type="text" name="name" required placeholder="Nama hari libur"
                   class="flex-1 px-3 py-2 text-[13px] bg-white 
                          border border-[#E4E4E7] rounded-lg outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            <button type="submit" class="app-btn app-btn-primary text-[13px] py-2 px-4">
                Tambah
            </button>
        </form>

        {{-- Holiday List --}}
        @if($holidays->count() > 0)
            <div class="space-y-2">
                @foreach($holidays as $holiday)
                    <div class="flex items-center justify-between gap-3 py-2 
                                border-b border-[#F0F0F1] last:border-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-lg bg-[#FAFAFA] flex flex-col 
                                        items-center justify-center flex-shrink-0 
                                        border border-[#F0F0F1]">
                                <span class="text-[10px] font-semibold text-[#C8102E] uppercase leading-none">
                                    {{ $holiday->date->format('M') }}
                                </span>
                                <span class="text-[13px] font-bold text-[#18181B] leading-none">
                                    {{ $holiday->date->format('d') }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-medium text-[#18181B] truncate">
                                    {{ $holiday->name }}
                                </p>
                                <p class="text-[11px] text-[#71717A]">
                                    {{ $holiday->date->translatedFormat('l, d F Y') }}
                                </p>
                            </div>
                        </div>
                        <form action="{{ route('settings.sla.holidays.destroy', $holiday) }}"
                              method="POST" onsubmit="return confirm('Yakin hapus hari libur ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="p-1.5 text-[#71717A] hover:text-[#DC2626] 
                                           hover:bg-[#FEF2F2] rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-[13px] text-[#71717A] text-center py-6 italic">
                Belum ada hari libur yang ditambahkan
            </p>
        @endif
    </div>
</div>
@endsection