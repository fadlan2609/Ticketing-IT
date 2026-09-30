<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ $company->app_name }}</title>

    {{-- Favicon --}}
    @if($company->favicon_url)
        <link rel="icon" href="{{ $company->favicon_url }}">
    @else
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23C8102E'/><text x='50' y='68' font-size='60' font-weight='bold' text-anchor='middle' fill='white' font-family='system-ui'>{{ $company->initials }}</text></svg>">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-[#F7F7F8] antialiased">

<div x-data="{ sidebarOpen: false }" class="min-h-screen">

    {{-- ============================================================ --}}
    {{-- MOBILE OVERLAY                                              --}}
    {{-- ============================================================ --}}
    <div x-show="sidebarOpen"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 z-40 lg:hidden"
         x-cloak></div>

    {{-- ============================================================ --}}
    {{-- SIDEBAR                                                     --}}
    {{-- ============================================================ --}}
    <aside x-show="true"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed top-0 left-0 bottom-0 w-[248px] bg-white border-r border-[#E4E4E7] z-50 
                  transition-transform duration-200 ease-out flex flex-col">

        {{-- Brand (otomatis dari pengaturan) --}}
        <div class="px-5 py-5 border-b border-[#F0F0F1]">
            <div class="flex items-center gap-3">
                @if($company->logo_url)
                    <img src="{{ $company->logo_url }}" 
                         alt="{{ $company->company_name }}"
                         class="w-9 h-9 object-contain flex-shrink-0">
                @else
                    <div class="w-9 h-9 rounded-lg bg-[#C8102E] flex items-center 
                                justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ $company->initials }}
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="text-[13px] font-bold text-[#18181B] leading-tight truncate">
                        {{ $company->app_name }}
                    </p>
                    <p class="text-[11px] text-[#71717A] leading-tight truncate">
                        {{ $company->company_name }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            {{-- OVERVIEW --}}
            <p class="app-sidebar-section">Overview</p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                      {{ request()->routeIs('dashboard') 
                         ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                         : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10"/>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- SERVICE --}}
            <p class="app-sidebar-section">Service</p>

            <a href="{{ route('tickets.index') }}"
               class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                      {{ request()->routeIs('tickets.index') || request()->routeIs('tickets.show') 
                         ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                         : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <span>Semua Tiket</span>
            </a>

            @can('ticket.create')
                <a href="{{ route('tickets.create') }}"
                   class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                          {{ request()->routeIs('tickets.create') 
                             ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                             : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Tiket</span>
                </a>
            @endcan

            {{-- INFORMATION --}}
            <p class="app-sidebar-section">Information</p>

            <a href="{{ route('office-info.index') }}"
               class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                      {{ request()->routeIs('office-info.*') 
                         ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                         : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Informasi</span>
            </a>

            {{-- KNOWLEDGE --}}
            <p class="app-sidebar-section">Knowledge</p>

            <a href="{{ route('kb.index') }}"
               class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                      {{ request()->routeIs('kb.*') 
                         ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                         : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Knowledge Base</span>
            </a>

            {{-- REPORTING --}}
            @hasanyrole('Super Admin|Supervisor|Auditor')
                <p class="app-sidebar-section">Reporting</p>

                <a href="{{ route('reports.index') }}"
                   class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                          {{ request()->routeIs('reports.*') 
                             ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                             : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Laporan</span>
                </a>
            @endhasanyrole

            {{-- MANAGEMENT --}}
            @role('Super Admin')
                <p class="app-sidebar-section">Management</p>

                {{-- Pengguna --}}
                <a href="{{ route('users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                          {{ request()->routeIs('users.*') 
                             ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                             : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Pengguna</span>
                </a>

                {{-- Cabang --}}
                <a href="{{ route('branches.index') }}"
                   class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                          {{ request()->routeIs('branches.*') 
                             ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                             : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Cabang</span>
                </a>

                {{-- Divisi --}}
                <a href="{{ route('divisions.index') }}"
                   class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                          {{ request()->routeIs('divisions.*') 
                             ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                             : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Divisi</span>
                </a>

                {{-- Konfigurasi SLA --}}
                <a href="{{ route('settings.sla.index') }}"
                   class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                          {{ request()->routeIs('settings.sla.*') 
                             ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                             : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Konfigurasi SLA</span>
                </a>

                {{-- Pengaturan Perusahaan --}}
                <a href="{{ route('settings.company.edit') }}"
                   class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium rounded-lg transition-all duration-150
                          {{ request()->routeIs('settings.company.*') 
                             ? 'bg-[#FDECEF] text-[#C8102E] font-semibold' 
                             : 'text-[#52525B] hover:bg-[#FAFAFA] hover:text-[#18181B]' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Pengaturan Perusahaan</span>
                </a>
            @endrole
        </nav>

        {{-- Logout --}}
        <div class="p-3 border-t border-[#F0F0F1]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2 text-[14px] font-medium 
                               text-[#52525B] rounded-lg transition-all duration-150
                               hover:bg-[#FEF2F2] hover:text-[#DC2626]">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ============================================================ --}}
    {{-- MAIN AREA                                                    --}}
    {{-- ============================================================ --}}
    <div class="lg:pl-[248px] min-h-screen flex flex-col">

        {{-- TOPBAR --}}
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md 
                       border-b border-[#E4E4E7] h-14">
            <div class="flex items-center justify-between h-full px-4 lg:px-6 gap-4">

                {{-- Mobile menu button --}}
                <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 -ml-2 text-[#52525B] hover:bg-[#FAFAFA] rounded-lg"
                        aria-label="Buka menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Breadcrumb --}}
                <div class="hidden lg:block min-w-0 flex-1">
                    @hasSection('breadcrumb')
                        <nav class="flex items-center gap-1.5 text-[13px] text-[#71717A]">
                            @yield('breadcrumb')
                        </nav>
                    @endif
                </div>

                {{-- Right section --}}
                <div class="flex items-center gap-1 ml-auto">

                    {{-- Global search --}}
                    <div class="hidden md:flex items-center relative">
                        <svg class="w-4 h-4 text-[#71717A] absolute left-3 pointer-events-none" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               data-global-search
                               placeholder="Cari tiket... (⌘K)"
                               class="w-56 pl-9 pr-3 py-1.5 text-[13px] 
                                      bg-[#F7F7F8] border border-transparent 
                                      rounded-lg outline-none
                                      focus:bg-white focus:border-[#C8102E] 
                                      focus:ring-2 focus:ring-[#FDECEF] 
                                      transition-all placeholder:text-[#A1A1AA]">
                    </div>

                    {{-- Notifications --}}
                    <a href="{{ route('notifications.index') }}"
                       class="relative p-2 text-[#52525B] hover:bg-[#FAFAFA] rounded-lg transition"
                       aria-label="Notifikasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php
                            $unread = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
                        @endphp
                        @if($unread > 0)
                            <span class="absolute top-1 right-1 min-w-[16px] h-4 px-1 
                                         bg-[#DC2626] text-white text-[10px] font-bold 
                                         rounded-full flex items-center justify-center">
                                {{ $unread > 9 ? '9+' : $unread }}
                            </span>
                        @endif
                    </a>

                    {{-- User dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="flex items-center gap-2 px-1.5 py-1 
                                       hover:bg-[#FAFAFA] rounded-lg transition">
                            <div class="w-8 h-8 rounded-full bg-[#C8102E] text-white 
                                        flex items-center justify-center text-[13px] font-semibold 
                                        overflow-hidden flex-shrink-0">
                                @if(auth()->user()->photo)
                                    <img src="{{ Storage::url(auth()->user()->photo) }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                                @endif
                            </div>
                            <svg class="w-3.5 h-3.5 text-[#71717A] hidden md:block" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             @click.outside="open = false"
                             x-transition.opacity
                             x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white rounded-xl 
                                    border border-[#E4E4E7] shadow-lg py-1.5 z-50">

                            <div class="px-3 py-2 border-b border-[#F0F0F1]">
                                <p class="text-[13px] font-semibold text-[#18181B] truncate">
                                    {{ auth()->user()->name }}
                                </p>
                                <p class="text-[11px] text-[#71717A] truncate">
                                    {{ auth()->user()->email }}
                                </p>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-2.5 px-3 py-2 text-[13px] 
                                      text-[#52525B] hover:bg-[#FAFAFA] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil Saya
                            </a>

                            <div class="border-t border-[#F0F0F1] my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-3 py-2 
                                               text-[13px] text-[#DC2626] 
                                               hover:bg-[#FEF2F2] transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                              d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 p-4 lg:p-8 max-w-[1440px] w-full mx-auto">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="border-t border-[#E4E4E7] bg-white/50">
            <div class="px-4 lg:px-8 py-4 max-w-[1440px] mx-auto
                        flex flex-col md:flex-row items-center justify-between gap-2
                        text-[12px] text-[#71717A]">
                <p>
                    © {{ date('Y') }} {{ $company->company_name }}
                    @if($company->tagline)
                        · {{ $company->tagline }}
                    @endif
                </p>
                <div class="flex items-center gap-3">
                    @if($company->email)
                        <a href="mailto:{{ $company->email }}" 
                           class="hover:text-[#C8102E] transition">
                            {{ $company->email }}
                        </a>
                    @endif
                    @if($company->website)
                        <span class="text-[#D4D4D8]">·</span>
                        <a href="{{ $company->website }}" target="_blank"
                           class="hover:text-[#C8102E] transition">
                            Website
                        </a>
                    @endif
                </div>
            </div>
        </footer>
    </div>

    {{-- ============================================================ --}}
    {{-- TOASTS                                                       --}}
    {{-- ============================================================ --}}
    <div class="fixed top-4 right-4 z-[100] space-y-2 max-w-sm w-full px-4 sm:px-0">
        @if(session('success'))
            <div data-toast class="app-toast border-[#16A34A]/20 bg-white app-slide-up">
                <div class="w-8 h-8 rounded-full bg-[#ECFDF3] flex items-center 
                            justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-[13px] text-[#18181B] flex-1">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div data-toast class="app-toast border-[#DC2626]/20 bg-white app-slide-up">
                <div class="w-8 h-8 rounded-full bg-[#FEF2F2] flex items-center 
                            justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-[13px] text-[#18181B] flex-1">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('warning'))
            <div data-toast class="app-toast border-[#D97706]/20 bg-white app-slide-up">
                <div class="w-8 h-8 rounded-full bg-[#FFF7ED] flex items-center 
                            justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-[#D97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <p class="text-[13px] text-[#18181B] flex-1">{{ session('warning') }}</p>
            </div>
        @endif
    </div>
</div>

@stack('scripts')
</body>
</html>