@extends('layouts.app')

@section('title', $branch->name)

@section('breadcrumb')
    <a href="{{ route('branches.index') }}" class="hover:text-[#18181B] transition">Cabang</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">{{ $branch->name }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8 mb-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-start justify-between gap-4 flex-wrap mb-4">
            <div>
                <span class="app-ticket-number block mb-2">{{ $branch->code }}</span>
                <h1 class="text-[22px] lg:text-[26px] font-bold text-[#18181B] tracking-[-0.02em]">
                    {{ $branch->name }}
                </h1>
                <div class="flex items-center gap-2 mt-3">
                    @if($branch->is_active)
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

            <div class="flex gap-2">
                <a href="{{ route('branches.edit', $branch) }}" class="app-btn app-btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('branches.index') }}" class="app-btn app-btn-secondary">Kembali</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
        {{-- Info --}}
        <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                    shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">Informasi Cabang</h2>
            <dl class="space-y-3.5 text-[13px]">
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">Alamat</dt>
                    <dd class="text-[#18181B]">{{ $branch->address ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">Telepon</dt>
                    <dd class="text-[#18181B]">{{ $branch->phone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">Total User</dt>
                    <dd class="text-[#18181B]">{{ $branch->users_count }} user</dd>
                </div>
                <div>
                    <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">Dibuat</dt>
                    <dd class="text-[#18181B]">{{ $branch->created_at->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>

        {{-- User List --}}
        <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                    shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
                User ({{ $branch->users_count }})
            </h2>
            @forelse($users as $user)
                <div class="flex items-center gap-3 py-2.5 border-b border-[#F0F0F1] last:border-0">
                    <div class="w-8 h-8 rounded-full bg-[#FDECEF] 
                                flex items-center justify-center 
                                text-[12px] font-semibold text-[#C8102E] flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-medium text-[#18181B] truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-[#71717A]">
                            {{ $user->roles->first()?->name ?? '-' }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-[13px] text-[#71717A] italic text-center py-4">
                    Belum ada user di cabang ini
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection