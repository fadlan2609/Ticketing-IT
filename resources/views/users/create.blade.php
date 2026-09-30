@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('breadcrumb')
    <a href="{{ route('users.index') }}" class="hover:text-[#18181B] transition">Pengguna</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">Tambah</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <h1 class="text-[24px] lg:text-[28px] font-bold text-[#18181B] tracking-[-0.02em]">
            Tambah Pengguna Baru
        </h1>
        <p class="text-[14px] text-[#52525B] mt-1">
            Lengkapi data pengguna dan tentukan peran akses mereka.
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

    <form action="{{ route('users.store') }}" method="POST"
          class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8
                 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="name" class="app-label">
                    Nama Lengkap <span class="text-[#DC2626]">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name') }}" required
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition">
                @error('name')
                    <p class="app-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nip" class="app-label">NIP</label>
                <input type="text" name="nip" id="nip"
                       value="{{ old('nip') }}"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition">
                @error('nip')
                    <p class="app-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="email" class="app-label">
                    Email <span class="text-[#DC2626]">*</span>
                </label>
                <input type="email" name="email" id="email"
                       value="{{ old('email') }}" required
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition">
                @error('email')
                    <p class="app-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="app-label">No. HP</label>
                <input type="text" name="phone" id="phone"
                       value="{{ old('phone') }}"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="password" class="app-label">
                    Password <span class="text-[#DC2626]">*</span>
                </label>
                <input type="password" name="password" id="password" required
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition">
                <p class="app-helper">Minimal 8 karakter</p>
                @error('password')
                    <p class="app-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="app-label">
                    Konfirmasi Password <span class="text-[#DC2626]">*</span>
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="role" class="app-label">
                    Role <span class="text-[#DC2626]">*</span>
                </label>
                <select name="role" id="role" required
                        class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                               border border-[#E4E4E7] rounded-xl outline-none 
                               focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                               transition appearance-none cursor-pointer">
                    <option value="">Pilih Role...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>
                            {{ $role }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="app-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position" class="app-label">Jabatan</label>
                <input type="text" name="position" id="position"
                       value="{{ old('position') }}"
                       placeholder="Contoh: Teller, CS, Auditor"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="branch_id" class="app-label">Cabang</label>
                <select name="branch_id" id="branch_id"
                        class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                               border border-[#E4E4E7] rounded-xl outline-none 
                               focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                               transition appearance-none cursor-pointer">
                    <option value="">Pilih Cabang...</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="division_id" class="app-label">Divisi</label>
                <select name="division_id" id="division_id"
                        class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                               border border-[#E4E4E7] rounded-xl outline-none 
                               focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                               transition appearance-none cursor-pointer">
                    <option value="">Pilih Divisi...</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" @selected(old('division_id') == $division->id)>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1" checked
                   class="rounded border-[#E4E4E7] text-[#C8102E] focus:ring-[#FDECEF]">
            <span class="text-[13px] text-[#52525B]">Akun aktif</span>
        </label>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 pt-4 border-t border-[#F0F0F1]">
            <a href="{{ route('users.index') }}"
               class="app-btn app-btn-secondary">Batal</a>
            <button type="submit" class="app-btn app-btn-primary">
                Simpan Pengguna
            </button>
        </div>
    </form>
</div>
@endsection