@extends('layouts.app')

@section('title', $article->title)

@section('breadcrumb')
    <a href="{{ route('kb.index') }}" class="hover:text-[#18181B] transition">Knowledge Base</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium truncate">{{ Str::limit($article->title, 40) }}</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('kb.index') }}"
       class="inline-flex items-center gap-1.5 text-[13px] text-[#71717A] 
              hover:text-[#18181B] transition mb-4">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Knowledge Base
    </a>

    {{-- Article Header --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-8 mb-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-start justify-between gap-4 flex-wrap mb-4">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-2.5 py-1 
                             bg-[#FDECEF] text-[#C8102E] rounded-full 
                             text-[11px] font-medium">
                    {{ $article->category?->name ?? 'Umum' }}
                </span>
                @if($article->is_published)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 
                                 bg-[#ECFDF3] text-[#166534] rounded-full 
                                 text-[11px] font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                        Published
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 
                                 bg-[#F4F4F5] text-[#52525B] rounded-full 
                                 text-[11px] font-medium">
                        Draft
                    </span>
                @endif
            </div>

            @hasanyrole('Super Admin|IT Support')
                @if(auth()->id() === $article->author_id || auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('kb.edit', $article) }}"
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
                @endif
            @endhasanyrole
        </div>

        <h1 class="text-[24px] lg:text-[32px] font-bold text-[#18181B] 
                   tracking-[-0.02em] leading-tight mb-4">
            {{ $article->title }}
        </h1>

        <div class="flex items-center gap-3 text-[12px] text-[#71717A] flex-wrap">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ $article->author->name }}
            </span>
            <span class="w-1 h-1 rounded-full bg-[#D4D4D8]"></span>
            <span>{{ $article->created_at->format('d M Y') }}</span>
            <span class="w-1 h-1 rounded-full bg-[#D4D4D8]"></span>
            <span>{{ $article->views }} views</span>
        </div>
    </div>

    {{-- Summary --}}
    @if($article->summary)
        <div class="bg-[#FDECEF] border-l-4 border-[#C8102E] rounded-r-2xl p-4 lg:p-5 mb-6">
            <p class="text-[14px] text-[#18181B] italic leading-relaxed">
                {{ $article->summary }}
            </p>
        </div>
    @endif

    {{-- Content --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-8 mb-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <div class="prose max-w-none text-[15px] text-[#52525B] 
                    whitespace-pre-line leading-relaxed">
            {{ $article->content }}
        </div>

        @if($article->tags->count() > 0)
            <div class="mt-8 pt-6 border-t border-[#F0F0F1]">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[12px] text-[#71717A]">Tags:</span>
                    @foreach($article->tags as $tag)
                        <a href="{{ route('kb.index', ['tag' => $tag->slug]) }}"
                           class="inline-flex items-center px-2.5 py-1 
                                  bg-[#F4F4F5] text-[#52525B] rounded-full 
                                  text-[11px] font-medium
                                  hover:bg-[#FDECEF] hover:text-[#C8102E] transition">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Rating --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8 mb-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)] text-center">
        <h3 class="text-[15px] font-semibold text-[#18181B] mb-1">
            Apakah artikel ini membantu?
        </h3>
        <p class="text-[13px] text-[#71717A] mb-5">
            Feedback Anda membantu kami meningkatkan kualitas.
        </p>

        @php $userRating = $article->user_rating; @endphp

        <div class="flex justify-center gap-3">
            <form action="{{ route('kb.rate', $article) }}" method="POST">
                @csrf
                <input type="hidden" name="is_helpful" value="1">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 
                               rounded-xl font-medium text-[14px] transition
                               {{ $userRating === true 
                                  ? 'bg-[#16A34A] text-white ring-4 ring-[#ECFDF3]' 
                                  : 'bg-white text-[#18181B] border border-[#E4E4E7] hover:bg-[#FAFAFA]' }}">
                    <span>👍</span>
                    <span>Ya ({{ $article->helpful_count }})</span>
                </button>
            </form>

            <form action="{{ route('kb.rate', $article) }}" method="POST">
                @csrf
                <input type="hidden" name="is_helpful" value="0">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 
                               rounded-xl font-medium text-[14px] transition
                               {{ $userRating === false 
                                  ? 'bg-[#DC2626] text-white ring-4 ring-[#FEF2F2]' 
                                  : 'bg-white text-[#18181B] border border-[#E4E4E7] hover:bg-[#FAFAFA]' }}">
                    <span>👎</span>
                    <span>Tidak ({{ $article->not_helpful_count }})</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Related --}}
    @if($relatedArticles->count() > 0)
        <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                    shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <h3 class="text-[15px] font-semibold text-[#18181B] mb-4">
                Artikel Terkait
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($relatedArticles as $related)
                    <a href="{{ route('kb.show', $related) }}"
                       class="block p-4 bg-[#FAFAFA] hover:bg-[#F0F0F1] 
                              rounded-xl border border-[#E4E4E7] transition">
                        <p class="text-[14px] font-medium text-[#18181B] line-clamp-2">
                            {{ $related->title }}
                        </p>
                        <p class="text-[12px] text-[#71717A] mt-1.5">
                            {{ $related->views }} views
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection