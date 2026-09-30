@extends('layouts.app')

@section('title', 'Tambah Divisi')

@section('breadcrumb')
    <a href="{{ route('divisions.index') }}" class="hover:text-[#18181B] transition">Divisi</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">Tambah</span>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-[24px] lg:text-[28px] font-bold text-[#18181B] tracking-[-0.02em]">
            Tambah Divisi Baru
        </h1>
        <p class="text-[14px] text-[#52525B] mt-1">Lengkapi data divisi baru.</p>
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

    <form action="{{ route('divisions.store') }}" method="POST"
          class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8
                 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="code" class="app-label">
                    Kode Divisi <span class="text-[#DC2626]">*</span>
                </label>
                <input type="text" name="code" id="code"
                       value="{{ old('code') }}" required
                       placeholder="IT" maxlength="20"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition uppercase">
                <p class="app-helper">Contoh: IT, OPS, HRD</p>
            </div>

            <div class="md:col-span-2">
                <label for="name" class="app-label">
                    Nama Divisi <span class="text-[#DC2626]">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name') }}" required
                       placeholder="Teknologi Informasi"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
        </div>

        <div>
            <label for="description" class="app-label">Deskripsi</label>
            <textarea name="description" id="description" rows="3"
                      placeholder="Deskripsi singkat tentang divisi ini..."
                      class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                             border border-[#E4E4E7] rounded-xl outline-none 
                             focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                             transition resize-y">{{ old('description') }}</textarea>
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" checked
                   class="rounded border-[#E4E4E7] text-[#C8102E] focus:ring-[#FDECEF]">
            <span class="text-[13px] text-[#52525B]">Divisi aktif</span>
        </label>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-4 border-t border-[#F0F0F1]">
            <a href="{{ route('divisions.index') }}" class="app-btn app-btn-secondary">Batal</a>
            <button type="submit" class="app-btn app-btn-primary">Simpan Divisi</button>
        </div>
    </form>
</div>
@endsection