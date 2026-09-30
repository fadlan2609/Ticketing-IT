@extends('layouts.app')

@section('title', 'Informasi')

@section('content')
<x-ui.page-header 
    title="Informasi"
    subtitle="Pengumuman, kebijakan, kontak, dan informasi kantor lainnya.">
    @role('Super Admin')
        <a href="{{ route('office-info.create') }}" class="app-btn app-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Informasi
        </a>
    @endrole
</x-ui.page-header>

{{-- Search & Filter --}}
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
                   placeholder="Cari informasi..."
                   class="w-full pl-10 pr-3.5 py-2.5 text-[14px] bg-white 
                          border border-[#E4E4E7] rounded-xl outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
        </div>

        <select name="category"
                class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                       border border-[#E4E4E7] rounded-xl outline-none 
                       focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                       transition appearance-none cursor-pointer">
            <option value="">Semua Kategori</option>
            @foreach([
                'announcement' => 'Pengumuman',
                'policy' => 'Kebijakan',
                'contact' => 'Kontak',
                'hours' => 'Jam Operasional',
                'event' => 'Acara',
                'general' => 'Umum',
            ] as $val => $label)
                <option value="{{ $val }}" @selected(request('category') === $val)>
                    {{ $label }} ({{ $categoryCounts[$val] ?? 0 }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="flex items-center gap-2 mt-3">
        <button type="submit" class="app-btn app-btn-primary text-[13px] px-3 py-2">
            Terapkan
        </button>
        @if(request()->hasAny(['search', 'category']))
            <a href="{{ route('office-info.index') }}" 
               class="app-btn app-btn-ghost text-[13px] px-3 py-2">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- Grid Informasi --}}
@if($infos->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($infos as $info)
            @php $catColor = $info->category_color; @endphp
            <a href="{{ route('office-info.show', $info) }}"
               class="bg-white border rounded-2xl p-5 
                      hover:border-[#C8102E] hover:shadow-[0_4px_12px_rgba(0,0,0,0.04)]
                      transition-all duration-150 group
                      {{ $info->is_pinned ? 'border-l-4 border-l-[#C8102E] border-[#E4E4E7]' : 'border-[#E4E4E7]' }}">

                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">{{ $info->icon_display }}</span>
                        <span class="inline-flex items-center px-2.5 py-1 
                                     rounded-full text-[11px] font-medium"
                              style="background: {{ $catColor['bg'] }}; color: {{ $catColor['text'] }};">
                            {{ $info->category_label }}
                        </span>
                    </div>
                    @if($info->is_pinned)
                        <svg class="w-4 h-4 text-[#C8102E]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/>
                        </svg>
                    @endif
                </div>

                @if(!$info->is_published)
                    <span class="inline-block mb-2 text-[10px] font-semibold 
                                 text-[#71717A] bg-[#F4F4F5] px-2 py-0.5 rounded">
                        DRAFT
                    </span>
                @endif

                <h3 class="text-[15px] font-semibold text-[#18181B] mb-2 
                           line-clamp-2 group-hover:text-[#C8102E] transition">
                    {{ $info->title }}
                </h3>

                <p class="text-[13px] text-[#71717A] line-clamp-2 mb-3">
                    {{ $info->summary ?? Str::limit(strip_tags($info->content), 100) }}
                </p>

                <div class="flex items-center justify-between text-[11px] text-[#A1A1AA]">
                    <span>{{ $info->created_at->diffForHumans() }}</span>
                    <span>{{ $info->views }} views</span>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $infos->links() }}
    </div>
@else
    <div class="bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <x-ui.empty-state 
            icon="search"
            title="Belum ada informasi"
            description="Tidak ada informasi yang sesuai filter.">
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('office-info.index') }}" class="app-btn app-btn-secondary">
                    Reset Filter
                </a>
            @endif
        </x-ui.empty-state>
    </div>
@endif
@endsection