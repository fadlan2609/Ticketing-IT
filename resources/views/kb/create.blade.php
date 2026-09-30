@extends('layouts.app')

@section('title', 'Artikel Baru')

@section('breadcrumb')
    <a href="{{ route('kb.index') }}" class="hover:text-[#18181B] transition">Knowledge Base</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">Artikel Baru</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-[24px] lg:text-[28px] font-bold text-[#18181B] tracking-[-0.02em]">
            Buat Artikel Baru
        </h1>
        <p class="text-[14px] text-[#52525B] mt-1">
            Bagikan solusi Anda untuk membantu rekan kerja menyelesaikan masalah serupa.
        </p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-[#FEF2F2] border border-[#DC2626]/20 rounded-2xl p-4">
            <div class="flex gap-2">
                <svg class="w-5 h-5 text-[#DC2626] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="flex-1">
                    <p class="text-[13px] font-semibold text-[#991B1B] mb-1">
                        Periksa kembali formulir Anda
                    </p>
                    <ul class="list-disc list-inside text-[13px] text-[#991B1B] space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('kb.store') }}" method="POST"
          class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8
                 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-5">
        @csrf

        @if($ticket)
            <div class="bg-[#FDECEF] border-l-4 border-[#C8102E] rounded-r-xl p-4">
                <p class="text-[13px] text-[#18181B]">
                    <span class="font-semibold">Dibuat dari tiket:</span>
                    <span class="app-ticket-number ml-1">{{ $ticket->ticket_number }}</span>
                    — {{ $ticket->title }}
                </p>
                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
            </div>
        @endif

        <div>
            <label for="title" class="app-label">
                Judul <span class="text-[#DC2626]">*</span>
            </label>
            <input type="text" name="title" id="title"
                   value="{{ old('title', $ticket?->title) }}"
                   placeholder="Contoh: Cara Reset Password CBS"
                   required
                   class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                          border border-[#E4E4E7] rounded-xl outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                          transition">
            @error('title')
                <p class="app-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="category_id" class="app-label">
                    Kategori <span class="text-[#DC2626]">*</span>
                </label>
                <select name="category_id" id="category_id" required
                        class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                               border border-[#E4E4E7] rounded-xl outline-none 
                               focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                               transition appearance-none cursor-pointer">
                    <option value="">Pilih kategori...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" 
                                @selected(old('category_id', $ticket?->category_id) == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="app-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tags" class="app-label">Tags</label>
                <input type="text" name="tags" id="tags"
                       value="{{ old('tags') }}"
                       placeholder="printer, epson, hardware"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition">
                <p class="app-helper">Pisahkan dengan koma</p>
            </div>
        </div>

        <div>
            <label for="summary" class="app-label">Ringkasan</label>
            <textarea name="summary" id="summary" rows="2" maxlength="500"
                      placeholder="Ringkasan singkat artikel..."
                      class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                             border border-[#E4E4E7] rounded-xl outline-none 
                             focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                             transition resize-y">{{ old('summary') }}</textarea>
            <p class="app-helper">Maksimal 500 karakter</p>
        </div>

        <div>
            <label for="content" class="app-label">
                Konten <span class="text-[#DC2626]">*</span>
            </label>
            <textarea name="content" id="content" rows="14" required
                      placeholder="Tulis langkah-langkah solusi dengan detail..."
                      class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                             border border-[#E4E4E7] rounded-xl outline-none 
                             focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                             transition resize-y font-mono leading-relaxed">{{ old('content', $ticket ? "Masalah:\n" . $ticket->description . "\n\nSolusi:\n" . $ticket->resolution_note : '') }}</textarea>
            @error('content')
                <p class="app-error">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_published" value="1" checked
                   class="rounded border-[#E4E4E7] text-[#C8102E] focus:ring-[#FDECEF]">
            <span class="text-[13px] text-[#52525B]">Publikasikan artikel ini</span>
        </label>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-4 border-t border-[#F0F0F1]">
            <a href="{{ route('kb.index') }}"
               class="app-btn app-btn-secondary">Batal</a>
            <button type="submit" class="app-btn app-btn-primary">
                Simpan Artikel
            </button>
        </div>
    </form>
</div>
@endsection