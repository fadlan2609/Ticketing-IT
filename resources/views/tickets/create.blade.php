@extends('layouts.app')

@section('title', 'Buat Tiket Baru')

@section('breadcrumb')
    <a href="{{ route('tickets.index') }}" class="hover:text-[#18181B] transition">Tiket</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">Buat Baru</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-[24px] lg:text-[28px] font-bold text-[#18181B] tracking-[-0.02em]">
            Buat Tiket Baru
        </h1>
        <p class="text-[14px] text-[#52525B] mt-1">
            Jelaskan masalah Anda dengan detail agar tim IT dapat membantu lebih cepat.
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

    <form action="{{ route('tickets.store') }}" method="POST" 
          enctype="multipart/form-data"
          class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8
                 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-5">
        @csrf

        <x-ui.input 
            name="title" 
            label="Subjek"
            :value="old('title')"
            placeholder="Contoh: Printer tidak bisa print"
            required />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-ui.select 
                name="category_id"
                label="Kategori"
                :selected="old('category_id')"
                required
                :options="$categories->mapWithKeys(fn($c) => [$c->id => $c->name])"
                placeholder="Pilih Kategori" />

            <x-ui.select 
                name="priority"
                label="Prioritas"
                :selected="old('priority', 'medium')"
                required
                :options="[
                    'low' => 'Low',
                    'medium' => 'Normal',
                    'high' => 'High',
                    'critical' => 'Critical',
                ]"
                placeholder="" />
        </div>

        <x-ui.select 
            name="branch_id"
            label="Lokasi / Cabang"
            :selected="old('branch_id', auth()->user()->branch_id)"
            :options="$branches->mapWithKeys(fn($b) => [$b->id => $b->name])"
            placeholder="Pilih Cabang (opsional)" />

        <div>
            <label for="description" class="block text-[13px] font-medium text-[#18181B] mb-1.5">
                Deskripsi <span class="text-[#DC2626]">*</span>
            </label>
            <textarea name="description" id="description" rows="6" required
                      placeholder="Jelaskan detail masalah yang Anda alami..."
                      class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                             border rounded-xl outline-none transition resize-y
                             {{ $errors->has('description') 
                                ? 'border-[#DC2626] focus:border-[#DC2626] focus:ring-2 focus:ring-[#FEF2F2]' 
                                : 'border-[#E4E4E7] focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF]' }}">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-[12px] text-[#DC2626] mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="attachments" class="block text-[13px] font-medium text-[#18181B] mb-1.5">
                Lampiran
            </label>
            <input type="file" name="attachments[]" id="attachments" multiple
                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt,.log,.zip"
                   class="w-full text-[13px] text-[#52525B] file:mr-3 
                          file:py-2 file:px-4 file:rounded-lg file:border-0 
                          file:text-[13px] file:font-medium file:bg-[#F4F4F5] 
                          file:text-[#18181B] hover:file:bg-[#E4E4E7] 
                          file:transition file:cursor-pointer cursor-pointer">
            <p class="text-[12px] text-[#71717A] mt-1.5">
                Maks 5 file. Format: JPG, PNG, PDF, DOC, DOCX, TXT, LOG, ZIP. Maks 5 MB/file.
            </p>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-4 border-t border-[#F0F0F1]">
            <x-ui.button href="{{ route('tickets.index') }}" variant="secondary">
                Batal
            </x-ui.button>
            <x-ui.button type="submit">
                Kirim Tiket
            </x-ui.button>
        </div>
    </form>
</div>
@endsection