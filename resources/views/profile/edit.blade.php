@extends('layouts.app')

@section('title', 'Profil Saya')

@section('breadcrumb')
    <span class="text-[#18181B] font-medium">Profil</span>
@endsection

@section('content')
<x-ui.page-header 
    title="Profil Saya"
    subtitle="Kelola informasi pribadi dan keamanan akun Anda." />

<div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

    {{-- Profile Card --}}
    <div class="lg:col-span-1">
        <div class="bg-white border border-[#E4E4E7] rounded-2xl p-6
                    shadow-[0_1px_2px_rgba(0,0,0,0.04)] text-center
                    lg:sticky lg:top-20">

            @if($user->photo)
                <img src="{{ Storage::url($user->photo) }}" 
                     class="w-24 h-24 rounded-full mx-auto mb-4 object-cover 
                            ring-4 ring-[#FDECEF]">
            @else
                <div class="w-24 h-24 rounded-full mx-auto mb-4 bg-[#C8102E] 
                            flex items-center justify-center 
                            text-white font-bold text-3xl
                            ring-4 ring-[#FDECEF]">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            <h3 class="text-[18px] font-bold text-[#18181B]">{{ $user->name }}</h3>
            <p class="text-[12px] text-[#71717A] mt-1">{{ $user->email }}</p>

            <div class="flex items-center justify-center gap-2 mt-3">
                <span class="inline-flex items-center px-2.5 py-1 
                             bg-[#FDECEF] text-[#C8102E] rounded-full 
                             text-[11px] font-medium">
                    {{ $user->getRoleNames()->first() ?? '-' }}
                </span>
            </div>

            @if($user->position)
                <p class="text-[12px] text-[#52525B] mt-3 pt-3 border-t border-[#F0F0F1]">
                    {{ $user->position }}
                </p>
            @endif

            @if($user->branch)
                <p class="text-[11px] text-[#71717A] mt-1">
                    📍 {{ $user->branch->name }}
                </p>
            @endif
        </div>
    </div>

    {{-- Forms --}}
    <div class="lg:col-span-2 space-y-4 lg:space-y-6">

        {{-- Update Profile --}}
        <form action="{{ route('profile.update') }}" method="POST" 
              enctype="multipart/form-data"
              class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                     shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            @csrf @method('PUT')

            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 rounded-lg bg-[#FDECEF] flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Informasi Pribadi</h2>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="name" class="app-label">
                        Nama Lengkap <span class="text-[#DC2626]">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                    @error('name')
                        <p class="app-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="app-label">No. HP</label>
                        <input type="text" name="phone" id="phone"
                               value="{{ old('phone', $user->phone) }}"
                               class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                      border border-[#E4E4E7] rounded-xl outline-none 
                                      focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                    </div>
                    <div>
                        <label for="position" class="app-label">Jabatan</label>
                        <input type="text" name="position" id="position"
                               value="{{ old('position', $user->position) }}"
                               class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                      border border-[#E4E4E7] rounded-xl outline-none 
                                      focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                    </div>
                </div>

                <div>
                    <label for="photo" class="app-label">Foto Profil</label>
                    <input type="file" name="photo" id="photo" accept="image/*"
                           class="w-full text-[13px] text-[#52525B] 
                                  file:mr-3 file:py-2 file:px-4 
                                  file:rounded-lg file:border-0 
                                  file:text-[13px] file:font-medium 
                                  file:bg-[#F4F4F5] file:text-[#18181B] 
                                  hover:file:bg-[#E4E4E7] file:transition 
                                  file:cursor-pointer cursor-pointer">
                    <p class="app-helper">Format JPG, PNG. Maks 2 MB.</p>
                    @error('photo')
                        <p class="app-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-5 pt-4 border-t border-[#F0F0F1]">
                <button type="submit" class="app-btn app-btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        {{-- Change Password --}}
        <form action="{{ route('profile.password') }}" method="POST"
              class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                     shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            @csrf @method('PUT')

            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 rounded-lg bg-[#FFF7ED] flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#9A3412]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Ganti Password</h2>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="current_password" class="app-label">
                        Password Lama <span class="text-[#DC2626]">*</span>
                    </label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                    @error('current_password')
                        <p class="app-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="app-label">
                            Password Baru <span class="text-[#DC2626]">*</span>
                        </label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                      border border-[#E4E4E7] rounded-xl outline-none 
                                      focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
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
                                      focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-5 pt-4 border-t border-[#F0F0F1]">
                <button type="submit" class="app-btn app-btn-primary">
                    Ganti Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection