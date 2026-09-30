@extends('layouts.app')

@section('title', 'Knowledge Base')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Hero --}}
    <div class="text-center mb-10">
        <h1 class="text-[28px] lg:text-[36px] font-bold text-[#18181B] tracking-[-0.02em] mb-2">
            Knowledge Base
        </h1>
        <p class="text-[15px] text-[#52525B] mb-6">
            Temukan solusi sebelum membuat tiket.
        </p>

        <form method="GET" class="max-w-2xl mx-auto">
            <div class="relative">
                <svg class="w-5 h-5 text-[#A1A1AA] absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Apa yang sedang Anda cari?"
                       class="w-full pl-12 pr-4 py-3.5 text-[15px] bg-white 
                              border border-[#E4E4E7] rounded-2xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            </div>
        </form>

        @hasanyrole('Super Admin|IT Support')
            <div class="mt-4">
                <x-ui.button href="{{ route('kb.create') }}" icon="plus" size="sm">
                    Buat Artikel
                </x-ui.button>
            </div>
        @endhasanyrole
    </div>

    {{-- Articles --}}
    @if($articles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($articles as $article)
                <a href="{{ route('kb.show', $article) }}"
                   class="bg-white border border-[#E4E4E7] rounded-2xl p-5 
                          hover:border-[#C8102E] hover:shadow-[0_4px_12px_rgba(0,0,0,0.04)]
                          transition-all duration-150 group">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="inline-flex items-center px-2.5 py-1 
                                     bg-[#FDECEF] text-[#C8102E] rounded-full 
                                     text-[11px] font-medium">
                            {{ $article->category?->name ?? 'Umum' }}
                        </span>
                    </div>
                    <h3 class="text-[15px] font-semibold text-[#18181B] mb-2 
                               line-clamp-2 group-hover:text-[#C8102E] transition">
                        {{ $article->title }}
                    </h3>
                    <p class="text-[13px] text-[#71717A] line-clamp-2 mb-3">
                        {{ $article->summary ?? Str::limit(strip_tags($article->content), 100) }}
                    </p>
                    <div class="flex items-center gap-3 text-[11px] text-[#A1A1AA]">
                        <span>{{ $article->views }} views</span>
                        @if($article->helpful_count + $article->not_helpful_count > 0)
                            <span>·</span>
                            <span>{{ $article->rating_percentage }}% helpful</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    @else
        <div class="bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <x-ui.empty-state 
                icon="search"
                title="Artikel tidak ditemukan"
                description="Coba gunakan kata kunci yang berbeda.">
                @if(request('search'))
                    <x-ui.button href="{{ route('kb.index') }}" variant="secondary">
                        Reset Pencarian
                    </x-ui.button>
                @endif
            </x-ui.empty-state>
        </div>
    @endif
</div>
@endsection