@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('breadcrumb')
    <a href="{{ route('users.index') }}" class="hover:text-[#18181B] transition">Pengguna</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">{{ $user->name }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8 mb-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <div class="flex items-start justify-between gap-4 flex-wrap mb-5">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-16 h-16 rounded-full bg-[#FDECEF] 
                            flex items-center justify-center 
                            text-[24px] font-bold text-[#C8102E] flex-shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <h1 class="text-[22px] lg:text-[26px] font-bold text-[#18181B] 
                               tracking-[-0.02em] truncate">
                        {{ $user->name }}
                    </h1>
                    <p class="text-[13px] text-[#71717A] mt-1">
                        {{ $user->position ?? 'Pengguna Sistem' }}
                    </p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <span class="inline-flex items-center px-2.5 py-1 
                                     bg-[#FDECEF] text-[#C8102E] rounded-full 
                                     text-[11px] font-medium">
                            {{ $user->roles->first()?->name ?? '-' }}
                        </span>
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
                    </div>
                </div>
            </div>

            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('users.edit', $user) }}"
                   class="app-btn app-btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('users.index') }}"
                   class="app-btn app-btn-secondary">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Info Detail --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">

        {{-- Kontak & Akun --}}
        <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                    shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
                Informasi Akun
            </h2>

            <dl class="space-y-3.5 text-[13px]">
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-0.5">Email</dt>
                    <dd class="text-[#18181B]">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-0.5">NIP</dt>
                    <dd class="text-[#18181B] font-mono">{{ $user->nip ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-0.5">No. HP</dt>
                    <dd class="text-[#18181B]">{{ $user->phone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-0.5">Jabatan</dt>
                    <dd class="text-[#18181B]">{{ $user->position ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Organisasi --}}
        <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                    shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
                Organisasi
            </h2>

            <dl class="space-y-3.5 text-[13px]">
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-0.5">Role</dt>
                    <dd>
                        <span class="inline-flex items-center px-2.5 py-1 
                                     bg-[#FDECEF] text-[#C8102E] rounded-full 
                                     text-[11px] font-medium">
                            {{ $user->roles->first()?->name ?? '-' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-0.5">Cabang</dt>
                    <dd class="text-[#18181B]">{{ $user->branch?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-0.5">Divisi</dt>
                    <dd class="text-[#18181B]">{{ $user->division?->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-0.5">Terdaftar</dt>
                    <dd class="text-[#18181B]">
                        {{ $user->created_at->format('d M Y') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Danger Zone --}}
    @if($user->id !== auth()->id())
        <div class="mt-6 bg-[#FEF2F2] border border-[#DC2626]/20 rounded-2xl p-5 lg:p-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h3 class="text-[14px] font-semibold text-[#991B1B] mb-1">
                        Hapus Pengguna
                    </h3>
                    <p class="text-[12px] text-[#991B1B]/80">
                        Tindakan ini tidak dapat dibatalkan. Semua data terkait pengguna akan dihapus.
                    </p>
                </div>
                <form action="{{ route('users.destroy', $user) }}" method="POST"
                      onsubmit="return confirm('Yakin hapus pengguna {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 
                                   bg-[#DC2626] hover:bg-[#B91C1C] text-white 
                                   text-[13px] font-medium rounded-xl transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection