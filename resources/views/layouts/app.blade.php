<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KitaPeduli | Platform Crowdfunding dan Donasi Sosial')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-900 antialiased selection:bg-teal-600 selection:text-white">

    <header class="sticky top-0 z-40 pt-3 px-3 sm:px-6">
        <div class="max-w-7xl mx-auto bg-white/95 backdrop-blur-md border border-slate-200/80 shadow-sm rounded-2xl px-4 sm:px-6 py-3.5">
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg shadow-xs group-hover:bg-teal-600 transition-colors">
                        K
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-lg tracking-tight text-slate-900 leading-none">KitaPeduli</span>
                        <span class="text-[11px] font-medium text-teal-600 tracking-wide">Crowdfunding Sosial</span>
                    </div>
                </a>

                <nav class="hidden lg:flex items-center gap-5 text-sm font-medium whitespace-nowrap">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? 'text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-900' }} transition-colors">
                        Beranda
                    </a>
                    <a href="{{ route('campaigns.index') }}"
                       class="{{ request()->routeIs('campaigns.*') ? 'text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-900' }} transition-colors">
                        Jelajahi Kampanye
                    </a>
                    <a href="{{ route('about') }}"
                       class="{{ request()->routeIs('about') ? 'text-slate-900 font-bold' : 'text-slate-500 hover:text-slate-900' }} transition-colors">
                        Tentang Kami
                    </a>
                    <a href="{{ route('about') }}#pedoman-kebaikan"
                       class="text-slate-500 hover:text-slate-900 transition-colors">
                        Pedoman Kebaikan
                    </a>
                    <a href="{{ route('about') }}#faq"
                       class="text-slate-500 hover:text-slate-900 transition-colors">
                        FAQ
                    </a>
                </nav>

                <div class="hidden md:flex items-center gap-2 shrink-0 whitespace-nowrap">
                    <a href="{{ route('campaigns.index') }}"
                       aria-label="Cari Kampanye"
                       title="Cari Kampanye"
                       class="w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-3.5 py-2 rounded-full bg-teal-50 text-teal-700 hover:bg-teal-100 text-xs font-bold transition-colors">
                                Panel Admin
                            </a>
                        @else
                            <a href="{{ route('donor.dashboard') }}"
                               class="px-3.5 py-2 rounded-full bg-slate-100 text-slate-800 hover:bg-slate-200 text-xs font-semibold transition-colors">
                                Dashboard Donatur
                            </a>
                            <a href="{{ route('donor.donations') }}"
                               class="px-3 py-2 rounded-full border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium transition-colors">
                                Riwayat Donasi
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="px-3.5 py-2 rounded-full bg-slate-900 text-white hover:bg-slate-800 text-xs font-semibold transition-colors cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-3.5 py-2 rounded-full border border-slate-300 text-slate-800 hover:bg-slate-100 text-xs font-semibold transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-3.5 py-2 rounded-full bg-slate-900 text-white hover:bg-teal-600 text-xs font-semibold transition-colors">
                            Daftar Donatur
                        </a>
                    @endauth
                </div>

                <button id="kp-mobile-menu-btn" type="button"
                        aria-label="Buka menu navigasi"
                        class="md:hidden p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <div id="kp-mobile-menu-panel" class="hidden md:hidden mt-3 pt-3 border-t border-slate-100 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100">Beranda</a>
                <a href="{{ route('campaigns.index') }}" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100">Jelajahi Kampanye</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100">Tentang Kami</a>
                <a href="{{ route('about') }}#pedoman-kebaikan" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100">Pedoman Kebaikan</a>
                <a href="{{ route('about') }}#faq" class="block px-3 py-2 rounded-xl text-sm font-medium hover:bg-slate-100">FAQ</a>

                <div class="pt-2 border-t border-slate-100 flex flex-wrap gap-2">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center px-4 py-2 rounded-full bg-teal-600 text-white text-xs font-semibold">Panel Admin</a>
                        @else
                            <a href="{{ route('donor.dashboard') }}" class="flex-1 text-center px-4 py-2 rounded-full bg-slate-100 text-slate-800 text-xs font-semibold">Dashboard</a>
                            <a href="{{ route('donor.donations') }}" class="flex-1 text-center px-4 py-2 rounded-full border border-slate-200 text-slate-700 text-xs font-semibold">Riwayat</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-semibold">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex-1 text-center px-4 py-2 rounded-full border border-slate-300 text-slate-800 text-xs font-semibold">Login</a>
                        <a href="{{ route('register') }}" class="flex-1 text-center px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-semibold">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    @if(session('success') || session('error') || $errors->any())
        <div class="kp-container mt-4 w-full">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-2xl text-sm font-medium flex items-center justify-between shadow-2xs">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-2xl text-sm font-medium flex items-center justify-between shadow-2xs mt-2">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-2xl text-sm mt-2">
                    <p class="font-bold mb-1">Periksa kembali input Anda:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200/80 mt-16">
        <div class="kp-container py-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-slate-100">
                <div class="md:col-span-5 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-base">
                            K
                        </div>
                        <span class="font-extrabold text-lg text-slate-900">KitaPeduli</span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-md">
                        Aplikasi web crowdfunding dan donasi sosial dengan transparansi perkembangan kampanye, catatan penyaluran dana, dan doa donatur.
                    </p>
                    <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200/80 inline-block px-3 py-1.5 rounded-xl font-medium">
                        Proyek Tugas Akhir PPBO (Laravel 13): seluruh transaksi menggunakan simulasi pembayaran demo.
                    </p>
                </div>

                <div class="md:col-span-2 space-y-2.5">
                    <h4 class="text-sm font-bold text-slate-900">Tentang</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="{{ route('about') }}" class="hover:text-slate-900 transition-colors">Tentang KitaPeduli</a></li>
                        <li><a href="{{ route('campaigns.index') }}" class="hover:text-slate-900 transition-colors">Jelajahi Kampanye</a></li>
                        <li><a href="{{ route('about') }}#transparansi" class="hover:text-slate-900 transition-colors">Transparansi Dana</a></li>
                    </ul>
                </div>

                <div class="md:col-span-2 space-y-2.5">
                    <h4 class="text-sm font-bold text-slate-900">Bantuan dan Panduan</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="{{ route('about') }}#pedoman-kebaikan" class="hover:text-slate-900 transition-colors">Pedoman Kebaikan</a></li>
                        <li><a href="{{ route('about') }}#faq" class="hover:text-slate-900 transition-colors">FAQ (Tanya Jawab)</a></li>
                        <li><a href="{{ route('about') }}#cara-donasi" class="hover:text-slate-900 transition-colors">Cara Berdonasi</a></li>
                    </ul>
                </div>

                <div class="md:col-span-3 space-y-2.5">
                    <h4 class="text-sm font-bold text-slate-900">Informasi Proyek</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Email demo: layanan@kitapeduli.test<br>
                        Aplikasi demonstrasi Tugas Akhir PPBO<br>
                        Program Studi Sistem Informasi, FMIPA Universitas Tanjungpura
                    </p>
                    <div class="flex items-center gap-2 pt-2">
                        <span class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold">KP</span>
                        <span class="w-8 h-8 rounded-full bg-teal-600 text-white flex items-center justify-center text-xs font-bold">ID</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} KitaPeduli. Proyek Tugas Akhir PPBO.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('about') }}#pedoman-kebaikan" class="hover:text-slate-600">Pedoman Kebaikan</a>
                    <a href="{{ route('about') }}#faq" class="hover:text-slate-600">FAQ</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
