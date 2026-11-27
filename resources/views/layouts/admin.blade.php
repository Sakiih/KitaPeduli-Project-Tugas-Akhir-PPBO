<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin | KitaPeduli')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f7f4] text-[#122620] antialiased selection:bg-emerald-600 selection:text-white">

    <div class="min-h-screen flex">
        <div id="kp-admin-overlay" class="fixed inset-0 bg-[#0d1e1a]/55 backdrop-blur-xs z-30 hidden lg:hidden"></div>

        <aside id="kp-admin-sidebar"
               class="fixed lg:static inset-y-0 left-0 z-40 w-68 bg-[#0f2420] text-[#cfe0da] flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out border-r border-emerald-950/60">
            <div class="p-5 border-b border-white/10 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-500 text-white font-extrabold flex items-center justify-center text-lg shadow-xs">
                        K
                    </div>
                    <div>
                        <span class="font-extrabold text-white text-base block leading-none">KitaPeduli</span>
                        <span class="text-[11px] text-emerald-300 font-semibold">Administrator Panel</span>
                    </div>
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto text-sm font-medium">
                <p class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-300/60">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.campaigns.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.campaigns.*') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Kampanye</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Kategori</span>
                </a>

                <p class="px-3 pt-4 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-300/60">Transparansi</p>

                <a href="{{ route('admin.updates.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.updates.*') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>Kabar Terbaru</span>
                </a>

                <a href="{{ route('admin.disbursements.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.disbursements.*') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    <span>Penyaluran Dana</span>
                </a>

                <p class="px-3 pt-4 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-300/60">Pemantauan &amp; Akun</p>

                <a href="{{ route('admin.donations.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.donations.*') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Transaksi Donasi</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Pengguna</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Laporan Donasi</span>
                </a>

                <a href="{{ route('admin.profile') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.profile') ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white font-bold shadow-xs' : 'text-[#cfe0da] hover:bg-white/8 hover:text-white' }} transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Profil Admin</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/10 space-y-2">
                <a href="{{ route('home') }}"
                   class="block text-center px-3 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold text-white transition-colors">
                    Lihat Website Publik
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full px-3 py-2.5 rounded-xl bg-rose-500/20 hover:bg-rose-600 text-rose-200 hover:text-white border border-rose-400/20 text-xs font-semibold transition-colors cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-[#e3eae4] px-4 sm:px-8 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button id="kp-admin-sidebar-btn" type="button"
                            aria-label="Buka menu admin"
                            class="lg:hidden p-2 rounded-xl border border-[#dce6df] text-[#122620] hover:bg-emerald-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-[#122620] tracking-tight">@yield('page_title', 'Dashboard Admin')</h1>
                        <p class="text-xs text-[#52665f]">@yield('page_subtitle', 'Kelola kampanye, kategori, dan transparansi KitaPeduli')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-[#122620]">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-teal-700 font-semibold uppercase tracking-wide">Administrator</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-600 to-sky-600 text-white flex items-center justify-center font-bold text-sm shadow-2xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-8 overflow-x-hidden">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center shrink-0 text-xs font-bold">!</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3.5 rounded-2xl text-sm">
                        <p class="font-bold mb-1">Terdapat kesalahan validasi:</p>
                        <ul class="list-disc list-inside text-xs space-y-0.5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
