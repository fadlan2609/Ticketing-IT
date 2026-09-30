@extends('layouts.app')

@section('title', 'Pengaturan Perusahaan')

@section('breadcrumb')
    <span class="text-[#71717A]">Pengaturan</span>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">Perusahaan</span>
@endsection

@section('content')
<x-ui.page-header 
    title="Pengaturan Perusahaan"
    subtitle="Kelola informasi perusahaan yang tampil di seluruh sistem." />

<form action="{{ route('settings.company.update') }}" method="POST"
      enctype="multipart/form-data"
      class="max-w-4xl mx-auto space-y-4 lg:space-y-6">
    @csrf @method('PUT')

    {{-- ================ BRAND / IDENTITAS ================ --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#FDECEF] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#C8102E]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Identitas & Logo</h2>
                <p class="text-[12px] text-[#71717A]">Logo & nama yang tampil di sidebar, login, dan laporan.</p>
            </div>
        </div>

        {{-- Logo Upload Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            {{-- Logo Utama --}}
            <div class="bg-[#FAFAFA] border border-[#E4E4E7] rounded-xl p-4">
                <p class="text-[12px] font-semibold text-[#71717A] uppercase tracking-wider mb-3">
                    Logo Utama
                </p>
                <div class="flex flex-col items-center gap-3">
                    @if($company->logo_url)
                        <img src="{{ $company->logo_url }}" alt="Logo" 
                             class="h-16 w-auto object-contain">
                        <form action="{{ route('settings.company.logo.destroy', 'logo') }}" 
                              method="POST"
                              onsubmit="return confirm('Hapus logo ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="text-[11px] text-[#DC2626] hover:underline">
                                Hapus
                            </button>
                        </form>
                    @else
                        <div class="w-16 h-16 rounded-lg bg-[#E4E4E7] flex items-center 
                                    justify-center text-[#71717A] text-[11px]">
                            Kosong
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*"
                           class="w-full text-[11px] text-[#52525B]
                                  file:mr-2 file:py-1 file:px-2 file:rounded 
                                  file:border-0 file:text-[11px] file:font-medium 
                                  file:bg-[#FDECEF] file:text-[#C8102E] 
                                  hover:file:bg-[#FDD5DA] cursor-pointer">
                    <p class="text-[10px] text-[#A1A1AA] text-center">
                        PNG, JPG, SVG · Maks 2MB · Ideal 512×512
                    </p>
                </div>
            </div>

            {{-- Logo Dark --}}
            <div class="bg-[#FAFAFA] border border-[#E4E4E7] rounded-xl p-4">
                <p class="text-[12px] font-semibold text-[#71717A] uppercase tracking-wider mb-3">
                    Logo Terang (opsional)
                </p>
                <div class="flex flex-col items-center gap-3">
                    @if($company->logo_dark_path)
                        <img src="{{ asset($company->logo_dark_path) }}" alt="Logo Dark" 
                             class="h-16 w-auto object-contain bg-[#18181B] rounded p-1">
                        <form action="{{ route('settings.company.logo.destroy', 'logo_dark') }}" 
                              method="POST"
                              onsubmit="return confirm('Hapus logo ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="text-[11px] text-[#DC2626] hover:underline">
                                Hapus
                            </button>
                        </form>
                    @else
                        <div class="w-16 h-16 rounded-lg bg-[#E4E4E7] flex items-center 
                                    justify-center text-[#71717A] text-[11px]">
                            Kosong
                        </div>
                    @endif
                    <input type="file" name="logo_dark" accept="image/*"
                           class="w-full text-[11px] text-[#52525B]
                                  file:mr-2 file:py-1 file:px-2 file:rounded 
                                  file:border-0 file:text-[11px] file:font-medium 
                                  file:bg-[#FDECEF] file:text-[#C8102E] 
                                  hover:file:bg-[#FDD5DA] cursor-pointer">
                    <p class="text-[10px] text-[#A1A1AA] text-center">
                        Untuk background gelap
                    </p>
                </div>
            </div>

            {{-- Favicon --}}
            <div class="bg-[#FAFAFA] border border-[#E4E4E7] rounded-xl p-4">
                <p class="text-[12px] font-semibold text-[#71717A] uppercase tracking-wider mb-3">
                    Favicon
                </p>
                <div class="flex flex-col items-center gap-3">
                    @if($company->favicon_url)
                        <img src="{{ $company->favicon_url }}" alt="Favicon" 
                             class="h-16 w-16 object-contain">
                        <form action="{{ route('settings.company.logo.destroy', 'favicon') }}" 
                              method="POST"
                              onsubmit="return confirm('Hapus favicon?')">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="text-[11px] text-[#DC2626] hover:underline">
                                Hapus
                            </button>
                        </form>
                    @else
                        <div class="w-16 h-16 rounded-lg bg-[#E4E4E7] flex items-center 
                                    justify-center text-[#71717A] text-[11px]">
                            Kosong
                        </div>
                    @endif
                    <input type="file" name="favicon" accept="image/*"
                           class="w-full text-[11px] text-[#52525B]
                                  file:mr-2 file:py-1 file:px-2 file:rounded 
                                  file:border-0 file:text-[11px] file:font-medium 
                                  file:bg-[#FDECEF] file:text-[#C8102E] 
                                  hover:file:bg-[#FDD5DA] cursor-pointer">
                    <p class="text-[10px] text-[#A1A1AA] text-center">
                        ICO, PNG · 32×32 atau 64×64
                    </p>
                </div>
            </div>
        </div>

        {{-- Nama & Tagline --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="app_name" class="app-label">
                    Nama Aplikasi <span class="text-[#DC2626]">*</span>
                </label>
                <input type="text" name="app_name" id="app_name" required
                       value="{{ old('app_name', $company->app_name) }}"
                       placeholder="Amanah Service Desk"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                <p class="app-helper">Tampil di sidebar & title browser.</p>
                @error('app_name')
                    <p class="app-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="company_name" class="app-label">
                    Nama Perusahaan <span class="text-[#DC2626]">*</span>
                </label>
                <input type="text" name="company_name" id="company_name" required
                       value="{{ old('company_name', $company->company_name) }}"
                       placeholder="BPRS Amanah Bangsa"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                @error('company_name')
                    <p class="app-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="company_short_name" class="app-label">
                    Nama Singkat <span class="text-[#DC2626]">*</span>
                </label>
                <input type="text" name="company_short_name" id="company_short_name" required
                       value="{{ old('company_short_name', $company->company_short_name) }}"
                       placeholder="BPRS AB"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                <p class="app-helper">Untuk inisial & logo fallback.</p>
            </div>

            <div>
                <label for="tagline" class="app-label">Tagline</label>
                <input type="text" name="tagline" id="tagline"
                       value="{{ old('tagline', $company->tagline) }}"
                       placeholder="Amanah, Profesional, Terpercaya"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
        </div>
    </div>

    {{-- ================ KONTAK ================ --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#EFF6FF] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#2563EB]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Kontak & Alamat</h2>
                <p class="text-[12px] text-[#71717A]">Tampil di kop laporan dan halaman informasi.</p>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label for="address" class="app-label">Alamat</label>
                <input type="text" name="address" id="address"
                       value="{{ old('address', $company->address) }}"
                       placeholder="Jl. Sudirman No. 123"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="city" class="app-label">Kota</label>
                    <input type="text" name="city" id="city"
                           value="{{ old('city', $company->city) }}"
                           placeholder="Jakarta"
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                </div>
                <div>
                    <label for="province" class="app-label">Provinsi</label>
                    <input type="text" name="province" id="province"
                           value="{{ old('province', $company->province) }}"
                           placeholder="DKI Jakarta"
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                </div>
                <div>
                    <label for="postal_code" class="app-label">Kode Pos</label>
                    <input type="text" name="postal_code" id="postal_code"
                           value="{{ old('postal_code', $company->postal_code) }}"
                           placeholder="12190"
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="app-label">Telepon</label>
                    <input type="text" name="phone" id="phone"
                           value="{{ old('phone', $company->phone) }}"
                           placeholder="021-1234567"
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                </div>
                <div>
                    <label for="whatsapp" class="app-label">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp"
                           value="{{ old('whatsapp', $company->whatsapp) }}"
                           placeholder="0812-3456-7890"
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="app-label">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $company->email) }}"
                           placeholder="info@bprs.test"
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                </div>
                <div>
                    <label for="website" class="app-label">Website</label>
                    <input type="url" name="website" id="website"
                           value="{{ old('website', $company->website) }}"
                           placeholder="https://bprs-amanah.test"
                           class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                  border border-[#E4E4E7] rounded-xl outline-none 
                                  focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
                </div>
            </div>
        </div>
    </div>

    {{-- ================ LEGAL & PERBANKAN ================ --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#ECFDF3] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Legal & Perbankan</h2>
                <p class="text-[12px] text-[#71717A]">Data legal untuk keperluan compliance & laporan resmi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="npwp" class="app-label">NPWP</label>
                <input type="text" name="npwp" id="npwp"
                       value="{{ old('npwp', $company->npwp) }}"
                       placeholder="01.234.567.8-901.000"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
            <div>
                <label for="license_number" class="app-label">No. Izin Usaha</label>
                <input type="text" name="license_number" id="license_number"
                       value="{{ old('license_number', $company->license_number) }}"
                       placeholder="KEP-123/D.05/2020"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
            <div>
                <label for="established_year" class="app-label">Tahun Berdiri</label>
                <input type="text" name="established_year" id="established_year"
                       value="{{ old('established_year', $company->established_year) }}"
                       placeholder="1998" maxlength="4"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
        </div>
    </div>

    {{-- ================ SOSIAL MEDIA ================ --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#F5F3FF] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#6D28D9]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Sosial Media</h2>
                <p class="text-[12px] text-[#71717A]">Opsional — link sosial media perusahaan.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="facebook" class="app-label">Facebook</label>
                <input type="url" name="facebook" id="facebook"
                       value="{{ old('facebook', $company->facebook) }}"
                       placeholder="https://facebook.com/bprs"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
            <div>
                <label for="instagram" class="app-label">Instagram</label>
                <input type="url" name="instagram" id="instagram"
                       value="{{ old('instagram', $company->instagram) }}"
                       placeholder="https://instagram.com/bprs"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
            <div>
                <label for="twitter" class="app-label">Twitter / X</label>
                <input type="url" name="twitter" id="twitter"
                       value="{{ old('twitter', $company->twitter) }}"
                       placeholder="https://twitter.com/bprs"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
            <div>
                <label for="linkedin" class="app-label">LinkedIn</label>
                <input type="url" name="linkedin" id="linkedin"
                       value="{{ old('linkedin', $company->linkedin) }}"
                       placeholder="https://linkedin.com/company/bprs"
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] transition">
            </div>
        </div>
    </div>

    {{-- ================ KOP LAPORAN ================ --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#FFF7ED] flex items-center justify-center">
                <svg class="w-4 h-4 text-[#9A3412]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-semibold text-[#18181B]">Kop Laporan PDF</h2>
                <p class="text-[12px] text-[#71717A]">Teks yang muncul di header/footer laporan PDF.</p>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label for="report_header_text" class="app-label">Header Laporan</label>
                <textarea name="report_header_text" id="report_header_text" rows="3"
                          placeholder="Contoh: Laporan ini bersifat rahasia dan hanya untuk keperluan internal."
                          class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                 border border-[#E4E4E7] rounded-xl outline-none 
                                 focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                                 transition resize-y">{{ old('report_header_text', $company->report_header_text) }}</textarea>
            </div>
            <div>
                <label for="report_footer_text" class="app-label">Footer Laporan</label>
                <textarea name="report_footer_text" id="report_footer_text" rows="2"
                          placeholder="Contoh: Digenerate otomatis oleh sistem."
                          class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                 border border-[#E4E4E7] rounded-xl outline-none 
                                 focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                                 transition resize-y">{{ old('report_footer_text', $company->report_footer_text) }}</textarea>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex justify-end gap-2 sticky bottom-4">
        <div class="bg-white border border-[#E4E4E7] rounded-2xl p-3 
                    shadow-lg flex gap-2">
            <a href="{{ route('dashboard') }}" 
               class="app-btn app-btn-secondary">Batal</a>
            <button type="submit" class="app-btn app-btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Pengaturan
            </button>
        </div>
    </div>
</form>
@endsection