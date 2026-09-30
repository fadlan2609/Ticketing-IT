<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — {{ $company->app_name }}</title>

    {{-- Favicon --}}
    @if($company->favicon_url)
        <link rel="icon" href="{{ $company->favicon_url }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23C8102E'/><text x='50' y='68' font-size='60' font-weight='bold' text-anchor='middle' fill='white' font-family='system-ui'>{{ $company->initials }}</text></svg>">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F7F7F8] flex items-center justify-center p-4">

<div class="w-full max-w-sm">

    {{-- Logo & Nama (otomatis dari pengaturan) --}}
    <div class="text-center mb-8">
        @if($company->logo_url)
            <img src="{{ $company->logo_url }}" 
                 alt="{{ $company->company_name }}"
                 class="w-16 h-16 mx-auto mb-4 object-contain">
        @else
            <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-[#C8102E] 
                        flex items-center justify-center text-white font-bold text-xl">
                {{ $company->initials }}
            </div>
        @endif

        <h1 class="text-[20px] font-bold text-[#18181B] tracking-tight">
            {{ $company->app_name }}
        </h1>
        <p class="text-[13px] text-[#71717A] mt-1">
            {{ $company->company_name }}
        </p>
    </div>

    {{-- Login Card --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-6 lg:p-8
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">

        @if(session('status'))
            <div class="mb-5 bg-[#ECFDF3] border border-[#16A34A]/20 rounded-xl p-3">
                <p class="text-[13px] text-[#166534]">{{ session('status') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 bg-[#FEF2F2] border border-[#DC2626]/20 rounded-xl p-3">
                <p class="text-[13px] text-[#991B1B]">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-[13px] font-medium text-[#18181B] mb-1.5">
                    Email
                </label>
                <input id="email" type="email" name="email" 
                       value="{{ old('email') }}" required autofocus
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition placeholder:text-[#A1A1AA]"
                       placeholder="nama@{{ parse_url($company->website ?? 'https://bprs.test', PHP_URL_HOST) }}">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-[13px] font-medium text-[#18181B]">
                        Password
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-[12px] text-[#71717A] hover:text-[#C8102E]">
                            Lupa?
                        </a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required
                       class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                              border border-[#E4E4E7] rounded-xl outline-none 
                              focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                              transition placeholder:text-[#A1A1AA]"
                       placeholder="••••••••">
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember"
                       class="rounded border-[#E4E4E7] text-[#C8102E] 
                              focus:ring-[#FDECEF]">
                <span class="text-[13px] text-[#52525B]">Ingat saya</span>
            </label>

            <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 
                           px-4 py-2.5 bg-[#C8102E] hover:bg-[#A50D25] 
                           text-white text-[14px] font-medium rounded-xl 
                           transition-all duration-150">
                Masuk
            </button>
        </form>
    </div>

    {{-- Footer (otomatis dari pengaturan) --}}
    <div class="text-center text-[12px] text-[#A1A1AA] mt-6 space-y-1">
        <p>© {{ date('Y') }} {{ $company->company_name }}</p>
        @if($company->website || $company->email)
            <div class="flex items-center justify-center gap-2">
                @if($company->email)
                    <a href="mailto:{{ $company->email }}" 
                       class="hover:text-[#C8102E] transition">
                        {{ $company->email }}
                    </a>
                @endif
                @if($company->website && $company->email)
                    <span class="text-[#D4D4D8]">·</span>
                @endif
                @if($company->website)
                    <a href="{{ $company->website }}" target="_blank"
                       class="hover:text-[#C8102E] transition">
                        {{ parse_url($company->website, PHP_URL_HOST) }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
</body>
</html>