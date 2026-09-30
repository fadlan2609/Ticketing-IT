<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan | {{ $company->app_name }}</title>

    @if($company->favicon_url)
        <link rel="icon" href="{{ $company->favicon_url }}">
    @endif

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#F7F7F8] flex items-center justify-center p-4">

<div class="max-w-md w-full text-center">
    {{-- Logo --}}
    @if($company->logo_url)
        <img src="{{ $company->logo_url }}" 
             alt="{{ $company->company_name }}"
             class="w-16 h-16 mx-auto mb-6 object-contain">
    @else
        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-[#C8102E] 
                    flex items-center justify-center text-white font-bold text-2xl">
            {{ $company->initials }}
        </div>
    @endif

    <p class="text-[64px] lg:text-[96px] font-bold text-[#C8102E] 
              leading-none tracking-tight mb-2">
        404
    </p>

    <h1 class="text-[20px] font-bold text-[#18181B] mb-2">
        Halaman tidak ditemukan
    </h1>
    <p class="text-[14px] text-[#52525B] mb-8">
        Halaman yang Anda cari tidak tersedia atau sudah dipindahkan.
    </p>

    <div class="flex items-center justify-center gap-2">
        <a href="{{ url('/dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 
                  bg-[#C8102E] hover:bg-[#A50D25] text-white 
                  text-[14px] font-medium rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    <p class="text-[11px] text-[#A1A1AA] mt-8">
        © {{ date('Y') }} {{ $company->company_name }}
    </p>
</div>

</body>
</html>