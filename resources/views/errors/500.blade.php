<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Kesalahan Server | {{ $company->app_name }}</title>

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

    <p class="text-[64px] lg:text-[96px] font-bold text-[#D97706] 
              leading-none tracking-tight mb-2">
        500
    </p>

    <h1 class="text-[20px] font-bold text-[#18181B] mb-2">
        Terjadi kesalahan
    </h1>
    <p class="text-[14px] text-[#52525B] mb-8">
        Sistem mengalami kendala saat memproses permintaan Anda. 
        Silakan coba lagi dalam beberapa saat.
    </p>

    <div class="flex items-center justify-center gap-2">
        <button onclick="location.reload()"
                class="inline-flex items-center gap-2 px-4 py-2.5 
                       bg-white text-[#18181B] border border-[#E4E4E7] 
                       text-[14px] font-medium rounded-xl 
                       hover:bg-[#FAFAFA] transition">
            Coba Lagi
        </button>
        <a href="{{ url('/dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 
                  bg-[#C8102E] hover:bg-[#A50D25] text-white 
                  text-[14px] font-medium rounded-xl transition">
            Dashboard
        </a>
    </div>

    <p class="text-[11px] text-[#A1A1AA] mt-8">
        © {{ date('Y') }} {{ $company->company_name }}
    </p>
</div>

</body>
</html>