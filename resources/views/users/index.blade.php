@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<x-ui.page-header 
    title="Pengguna"
    subtitle="Kelola akun, peran, dan akses pengguna sistem.">
    <a href="{{ route('users.create') }}"
       class="app-btn app-btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pengguna
    </a>
</x-ui.page-header>

{{-- Filter --}}
<form method="GET" class="bg-white border border-[#E4E4E7] rounded-2xl p-4 lg:p-5 mb-6
                          shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="md:col-span-2 relative">
            <svg class="w-4 h-4 text-[#A1A1AA] absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau NIP..."
                   class="w-full pl-10 pr-3.5 py-2.5 text-[14px] bg-white 
                          border border-[#E4E4E7] rounded-xl outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
        </div>

        <select name="role"
                class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                       border border-[#E4E4E7] rounded-xl outline-none 
                       focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                       transition appearance-none cursor-pointer">
            <option value="">Semua Role</option>
            @foreach(['Super Admin', 'IT Support', 'Supervisor', 'Requester', 'Auditor'] as $r)
                <option value="{{ $r }}" @selected(request('role') === $r)>{{ $r }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center gap-2 mt-3">
        <button type="submit" class="app-btn app-btn-primary text-[13px] px-3 py-2">
            Terapkan
        </button>
        @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('users.index') }}" 
               class="app-btn app-btn-ghost text-[13px] px-3 py-2">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- Table (Desktop) --}}
@if($users->count() > 0)
    <div class="hidden lg:block bg-white border border-[#E4E4E7] rounded-2xl overflow-hidden
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <table class="w-full">
            <thead>
                <tr class="bg-[#FAFAFA] border-b border-[#E4E4E7]">
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Nama</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Email / NIP</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Role</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Cabang</th>
                    <th class="text-left text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider px-5 py-3">Status</th>
                    <th class="w-40"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b border-[#F0F0F1] last:border-0 hover:bg-[#FAFAFA] transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[#FDECEF] 
                                            flex items-center justify-center 
                                            text-[13px] font-semibold text-[#C8102E] flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[14px] font-medium text-[#18181B] truncate">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-[12px] text-[#71717A] truncate">
                                        {{ $user->position ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-[13px] text-[#18181B]">{{ $user->email }}</p>
                            <p class="text-[12px] text-[#71717A] font-mono">
                                {{ $user->nip ?? '-' }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 
                                         bg-[#FDECEF] text-[#C8102E] rounded-full 
                                         text-[11px] font-medium">
                                {{ $user->roles->first()?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-[13px] text-[#52525B]">
                            {{ $user->branch?->name ?? '-' }}
                        </td>
                        <td class="px-5 py-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 
                                             bg-[#ECFDF3] text-[#166534] rounded-full 
                                             text-[11px] font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 
                                             bg-[#FEF2F2] text-[#991B1B] rounded-full 
                                             text-[11px] font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#DC2626]"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('users.show', $user) }}"
                                   class="p-1.5 text-[#71717A] hover:text-[#18181B] 
                                          hover:bg-[#F4F4F5] rounded-lg transition"
                                   title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('users.edit', $user) }}"
                                   class="p-1.5 text-[#71717A] hover:text-[#C8102E] 
                                          hover:bg-[#FDECEF] rounded-lg transition"
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                          class="inline" onsubmit="return confirm('Yakin hapus pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-[#71717A] hover:text-[#DC2626] 
                                                       hover:bg-[#FEF2F2] rounded-lg transition"
                                                title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="lg:hidden space-y-3">
        @foreach($users as $user)
            <div class="bg-white border border-[#E4E4E7] rounded-2xl p-4
                        shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#FDECEF] 
                                flex items-center justify-center 
                                text-[14px] font-semibold text-[#C8102E] flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <p class="text-[14px] font-semibold text-[#18181B] truncate">
                                {{ $user->name }}
                            </p>
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 
                                             bg-[#ECFDF3] text-[#166534] rounded-full 
                                             text-[10px] font-medium">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 
                                             bg-[#FEF2F2] text-[#991B1B] rounded-full 
                                             text-[10px] font-medium">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <p class="text-[12px] text-[#71717A]">{{ $user->email }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="inline-flex items-center px-2 py-0.5 
                                         bg-[#FDECEF] text-[#C8102E] rounded-full 
                                         text-[10px] font-medium">
                                {{ $user->roles->first()?->name ?? '-' }}
                            </span>
                            <span class="text-[11px] text-[#71717A]">
                                {{ $user->branch?->name ?? '-' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2 mt-3">
                            <a href="{{ route('users.show', $user) }}"
                               class="app-btn app-btn-secondary text-[12px] px-3 py-1.5">
                                Lihat
                            </a>
                            <a href="{{ route('users.edit', $user) }}"
                               class="app-btn app-btn-primary text-[12px] px-3 py-1.5">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
@else
    <div class="bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <x-ui.empty-state 
            title="Tidak ada pengguna"
            description="Belum ada pengguna yang sesuai dengan filter.">
            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('users.index') }}" 
                   class="app-btn app-btn-secondary">
                    Reset Filter
                </a>
            @else
                <a href="{{ route('users.create') }}" 
                   class="app-btn app-btn-primary">
                    Tambah Pengguna
                </a>
            @endif
        </x-ui.empty-state>
    </div>
@endif
@endsection