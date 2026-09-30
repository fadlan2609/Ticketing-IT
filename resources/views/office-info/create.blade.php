@extends('layouts.app')

@section('title', 'Tambah Informasi')

@section('breadcrumb')
    <a href="{{ route('office-info.index') }}" class="hover:text-[#18181B] transition">Informasi</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">Tambah</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-[24px] lg:text-[28px] font-bold text-[#18181B] tracking-[-0.02em]">
            Tambah Informasi
        </h1>
        <p class="text-[14px] text-[#52525B] mt-1">
            Buat informasi yang akan tampil di halaman Informasi untuk semua pengguna.
        </p>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-[#FEF2F2] border border-[#DC2626]/20 rounded-2xl p-4">
            <ul class="list-disc list-inside text-[13px] text-[#991B1B] space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('office-info.store') }}" method="POST"
          class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8
                 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-5">
        @csrf

        <div>
            <label for="title" class="app-label">
                Judul <span class="text-[#DC2626]">*</span>
            </label>
            <input type="text" name="title" id="title"
                   value="{{ old('title') }}" required
                   placeholder="Contoh: Jam Operasional Kantor"
                   class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                          border border-[#E4E4E7] rounded-xl outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="category" class="app-label">
                    Kategori <span class="text-[#DC2626]">*</span>
                </label>
                <select name="category" id="category" required
                        class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                               border border-[#E4E4E7] rounded-xl outline-none 
                               focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                               transition appearance-none cursor-pointer">
                    <option value="">Pilih Kategori</option>
                    @foreach([
                        'announcement' => '📢 Pengumuman',
                        'policy' => '📋 Kebijakan',
                        'contact' => '📞 Kontak',
                        'hours' => '🕐 Jam Operasional',
                        'event' => '🎉 Acara',
                        'general' => 'ℹ️ Umum',
                    ] as $val => $label)
                        <option value="{{ $val }}" @selected(old('category') === $val)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="icon" class="app-label">Icon (emoji)</label>
                <input type="text" name="icon" id="icon"
                       value="{{ old('icon') }}"
                       placeholder="📢"
                       maxlength="2"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white text-center
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                <p class="app-helper text-center">Kosongkan = default</p>
            </div>
        </div>

        <div>
            <label for="summary" class="app-label">Ringkasan</label>
            <textarea name="summary" id="summary" rows="2" maxlength="500"
                      placeholder="Ringkasan singkat (opsional)..."
                      class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                             border border-[#E4E4E7] rounded-xl outline-none 
                             focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                             transition resize-y">{{ old('summary') }}</textarea>
            <p class="app-helper">Maks 500 karakter</p>
        </div>

        <div>
            <label for="content" class="app-label">
                Konten <span class="text-[#DC2626]">*</span>
            </label>
            <textarea name="content" id="content" rows="12" required
                      placeholder="Tulis isi informasi secara detail..."
                      class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                             border border-[#E4E4E7] rounded-xl outline-none 
                             focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                             transition resize-y leading-relaxed">{{ old('content') }}</textarea>
        </div>

        <div>
            <label for="published_until" class="app-label">
                Berlaku Sampai (opsional)
            </label>
            <input type="datetime-local" name="published_until" id="published_until"
                   value="{{ old('published_until') }}"
                   class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                          border border-[#E4E4E7] rounded-xl outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            <p class="app-helper">
                Kosongkan jika informasi berlaku selamanya.
                Setelah tanggal ini, informasi otomatis disembunyikan.
            </p>
        </div>

        <div class="space-y-3 pt-4 border-t border-[#F0F0F1]">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_pinned" value="1"
                       @checked(old('is_pinned'))
                       class="rounded border-[#E4E4E7] text-[#C8102E] focus:ring-[#FDECEF]">
                <span class="text-[13px] text-[#52525B]">
                    📌 Pin di atas — tampil paling atas
                </span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1"
                       @checked(old('is_published', true))
                       class="rounded border-[#E4E4E7] text-[#C8102E] focus:ring-[#FDECEF]">
                <span class="text-[13px] text-[#52525B]">
                    Publikasikan — langsung tampil untuk semua user
                </span>
            </label>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-4 border-t border-[#F0F0F1]">
            <a href="{{ route('office-info.index') }}"
               class="app-btn app-btn-secondary">Batal</a>
            <button type="submit" class="app-btn app-btn-primary">
                Simpan Informasi
            </button>
        </div>
    </form>
</div>
@endsection