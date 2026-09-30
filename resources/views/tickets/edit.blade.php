@extends('layouts.app')

@section('title', 'Edit Tiket')

@section('breadcrumb')
    <a href="{{ route('tickets.index') }}" class="hover:text-[#18181B] transition">Tiket</a>
    <span class="text-[#D4D4D8]">/</span>
    <a href="{{ route('tickets.show', $ticket) }}" class="hover:text-[#18181B] transition">
        {{ $ticket->ticket_number }}
    </a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">Edit</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-[24px] lg:text-[28px] font-bold text-[#18181B] tracking-[-0.02em]">
            Edit Tiket
        </h1>
        <p class="text-[14px] text-[#52525B] mt-1">
            <span class="app-ticket-number">{{ $ticket->ticket_number }}</span>
            · Perbarui detail tiket Anda.
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

    <form action="{{ route('tickets.update', $ticket) }}" method="POST"
          class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8
                 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-5">
        @csrf @method('PUT')

        <div>
            <label for="title" class="app-label">
                Subjek <span class="text-[#DC2626]">*</span>
            </label>
            <input type="text" name="title" id="title"
                   value="{{ old('title', $ticket->title) }}" required
                   class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                          border border-[#E4E4E7] rounded-xl outline-none 
                          focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
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
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" 
                                @selected(old('category_id', $ticket->category_id) == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="priority" class="app-label">
                    Prioritas <span class="text-[#DC2626]">*</span>
                </label>
                <select name="priority" id="priority" required
                        class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                               border border-[#E4E4E7] rounded-xl outline-none 
                               focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                               transition appearance-none cursor-pointer">
                    @foreach(['low' => 'Low', 'medium' => 'Normal', 'high' => 'High', 'critical' => 'Critical'] as $val => $label)
                        <option value="{{ $val }}" 
                                @selected(old('priority', $ticket->priority) === $val)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label for="branch_id" class="app-label">Lokasi / Cabang</label>
            <select name="branch_id" id="branch_id"
                    class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                           border border-[#E4E4E7] rounded-xl outline-none 
                           focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                           transition appearance-none cursor-pointer">
                <option value="">Pilih Cabang...</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" 
                            @selected(old('branch_id', $ticket->branch_id) == $branch->id)>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="description" class="app-label">
                Deskripsi <span class="text-[#DC2626]">*</span>
            </label>
            <textarea name="description" id="description" rows="8" required
                      class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                             border border-[#E4E4E7] rounded-xl outline-none 
                             focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                             transition resize-y leading-relaxed">{{ old('description', $ticket->description) }}</textarea>
            @error('description')
                <p class="app-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Info Note --}}
        <div class="bg-[#FFF8D8] border border-[#F4C400]/30 rounded-xl p-4 flex gap-2">
            <svg class="w-4 h-4 text-[#A16207] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-[12px] text-[#A16207] leading-relaxed">
                <strong>Catatan:</strong> Perubahan prioritas akan menghitung ulang deadline SLA 
                berdasarkan prioritas baru.
            </p>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-4 border-t border-[#F0F0F1]">
            <a href="{{ route('tickets.show', $ticket) }}"
               class="app-btn app-btn-secondary">Batal</a>
            <button type="submit" class="app-btn app-btn-primary">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection