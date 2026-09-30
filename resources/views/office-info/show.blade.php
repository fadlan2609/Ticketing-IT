@extends('layouts.app')

@section('title', $officeInfo->title)

@section('breadcrumb')
    <a href="{{ route('office-info.index') }}" class="hover:text-[#18181B] transition">Informasi</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium truncate">{{ Str::limit($officeInfo->title, 40) }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('office-info.index') }}"
       class="inline-flex items-center gap-1.5 text-[13px] text-[#71717A] 
              hover:text-[#18181B] transition mb-4">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Informasi
    </a>

    {{-- Header --}}
    @php $catColor = $officeInfo->category_color; @endphp
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-8 mb-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-start justify-between gap-4 flex-wrap mb-4">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-2.5 py-1 
                             rounded-full text-[11px] font-medium"
                      style="background: {{ $catColor['bg'] }}; color: {{ $catColor['text'] }};">
                    {{ $officeInfo->category_label }}
                </span>
                @if($officeInfo->is_pinned)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 
                                 bg-[#FDECEF] text-[#C8102E] rounded-full 
                                 text-[11px] font-medium">
                        📌 Pinned
                    </span>
                @endif
                @if(!$officeInfo->is_published)
                    <span class="inline-flex items-center px-2.5 py-1 
                                 bg-[#F4F4F5] text-[#52525B] rounded-full 
                                 text-[11px] font-medium">
                        Draft
                    </span>
                @endif
            </div>

            @role('Super Admin')
                <div class="flex gap-2">
                    <a href="{{ route('office-info.edit', $officeInfo) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 
                              bg-white text-[#18181B] border border-[#E4E4E7] 
                              rounded-lg text-[13px] font-medium
                              hover:bg-[#FAFAFA] transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('office-info.destroy', $officeInfo) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus informasi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 
                                       bg-[#FEF2F2] text-[#DC2626] border border-[#FEF2F2] 
                                       rounded-lg text-[13px] font-medium
                                       hover:bg-[#FEE2E2] transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            @endrole
        </div>

        <div class="flex items-start gap-4">
            <span class="text-4xl flex-shrink-0">{{ $officeInfo->icon_display }}</span>
            <div class="min-w-0 flex-1">
                <h1 class="text-[24px] lg:text-[32px] font-bold text-[#18181B] 
                           tracking-[-0.02em] leading-tight mb-3">
                    {{ $officeInfo->title }}
                </h1>

                <div class="flex items-center gap-3 text-[12px] text-[#71717A] flex-wrap">
                    <span>{{ $officeInfo->author->name }}</span>
                    <span class="w-1 h-1 rounded-full bg-[#D4D4D8]"></span>
                    <span>{{ $officeInfo->created_at->format('d M Y') }}</span>
                    <span class="w-1 h-1 rounded-full bg-[#D4D4D8]"></span>
                    <span>{{ $officeInfo->views }} views</span>
                    @if($officeInfo->published_until)
                        <span class="w-1 h-1 rounded-full bg-[#D4D4D8]"></span>
                        <span class="text-[#D97706]">
                            Berlaku s/d {{ $officeInfo->published_until->format('d M Y') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Summary --}}
    @if($officeInfo->summary)
        <div class="bg-[#FDECEF] border-l-4 border-[#C8102E] rounded-r-2xl p-4 lg:p-5 mb-6">
            <p class="text-[14px] text-[#18181B] italic leading-relaxed">
                {{ $officeInfo->summary }}
            </p>
        </div>
    @endif

    {{-- Metadata (jika ada — untuk kontak/jam) --}}
    @if($officeInfo->metadata && count($officeInfo->metadata) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
            @foreach($officeInfo->metadata as $meta)
                <div class="bg-white border border-[#E4E4E7] rounded-2xl p-4
                            shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                    <p class="text-[11px] font-semibold text-[#71717A] 
                               uppercase tracking-wider mb-1">
                        {{ $meta['label'] }}
                    </p>
                    <p class="text-[15px] font-semibold text-[#18181B]">
                        {{ $meta['value'] }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Content --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-8
                shadow-[0_1px_2px_rgba(0,0,0,0.04)] mb-6">
        <div class="prose max-w-none text-[15px] text-[#52525B] 
                    whitespace-pre-line leading-relaxed">
            {{ $officeInfo->content }}
        </div>
    </div>

    {{-- Related --}}
    @if($relatedInfos->count() > 0)
        <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                    shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <h3 class="text-[15px] font-semibold text-[#18181B] mb-4">
                Informasi Terkait
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($relatedInfos as $related)
                    <a href="{{ route('office-info.show', $related) }}"
                       class="block p-4 bg-[#FAFAFA] hover:bg-[#F0F0F1] 
                              rounded-xl border border-[#E4E4E7] transition">
                        <p class="text-[12px] mb-1">{{ $related->icon_display }}</p>
                        <p class="text-[13px] font-medium text-[#18181B] line-clamp-2">
                            {{ $related->title }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection