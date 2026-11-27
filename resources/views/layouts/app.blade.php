<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KitaPeduli | Platform Crowdfunding dan Donasi Sosial')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-[#f6f7f4] text-[#122620] antialiased selection:bg-emerald-600 selection:text-white">

    <header class="sticky top-0 z-40 bg-[#f6f7f4]/90 backdrop-blur-md border-b border-[#e3eae4]">
        <div class="kp-container py-3.5">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-600 via-teal-600 to-sky-600 text-white flex items-center justify-center font-extrabold text-lg shadow-xs group-hover:scale-105 transition-transform">
                        K
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-lg tracking-tight text-[#122620] leading-none">KitaPeduli</span>
                        <span class="text-[11px] font-semibold text-teal-700 tracking-wide">Crowdfunding Sosial</span>
                    </div>
                </a>

                <nav class="hidden lg:flex items-center gap-7 text-sm font-medium whitespace-nowrap">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? 'text-emerald-700 font-bold' : 'text-[#52665f] hover:text-[#122620]' }} transition-colors">
                        Beranda
                    </a>
                    <a href="{{ route('campaigns.index') }}"
                       class="{{ request()->routeIs('campaigns.*') ? 'text-emerald-700 font-bold' : 'text-[#52665f] hover:text-[#122620]' }} transition-colors">
                        Jelajahi Kampanye
                    </a>
                    <a href="{{ route('about') }}"
                       class="{{ request()->routeIs('about') ? 'text-emerald-700 font-bold' : 'text-[#52665f] hover:text-[#122620]' }} transition-colors">
                        Tentang Kami
                    </a>
                    <a href="{{ route('about') }}#pedoman-kebaikan"
                       class="text-[#52665f] hover:text-[#122620] transition-colors">
                        Pedoman Kebaikan
                    </a>
                    <a href="{{ route('about') }}#faq"
                       class="text-[#52665f] hover:text-[#122620] transition-colors">
                        FAQ
                    </a>
                </nav>

                <div class="hidden md:flex items-center gap-2.5 shrink-0 whitespace-nowrap">
                    <a href="{{ route('campaigns.index') }}"
                       aria-label="Cari Kampanye"
                       title="Cari Kampanye"
                       class="w-9 h-9 rounded-full bg-white border border-[#dce6df] hover:border-emerald-300 hover:bg-emerald-50/60 text-[#3c524b] flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 hover:bg-emerald-100 text-xs font-bold transition-colors">
                                Panel Admin
                            </a>
                        @else
                            <a href="{{ route('donor.dashboard') }}"
                               class="px-4 py-2 rounded-full bg-white border border-[#dce6df] text-[#122620] hover:border-emerald-300 text-xs font-semibold transition-colors">
                                Dashboard Donatur
                            </a>
                            <a href="{{ route('donor.donations') }}"
                               class="px-4 py-2 rounded-full bg-sky-50 border border-sky-200 text-sky-800 hover:bg-sky-100 text-xs font-semibold transition-colors">
                                Riwayat Donasi
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 rounded-full bg-[#122620] text-white hover:bg-emerald-700 text-xs font-semibold transition-colors cursor-pointer">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 rounded-full text-[#3c524b] hover:text-[#122620] hover:bg-white/80 text-xs font-semibold transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 rounded-full bg-white border border-emerald-500/70 text-emerald-800 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 text-xs font-bold transition-all shadow-2xs">
                            Daftar Donatur
                        </a>
                    @endauth
                </div>

                <button id="kp-mobile-menu-btn" type="button"
                        aria-label="Buka menu navigasi"
                        aria-expanded="false"
                        aria-controls="kp-mobile-menu-panel"
                        class="lg:hidden p-2 rounded-xl bg-white border border-[#dce6df] text-[#122620] hover:bg-emerald-50 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <div id="kp-mobile-menu-panel" class="hidden lg:hidden mt-3 pt-3 border-t border-[#e3eae4] space-y-1.5 bg-white rounded-2xl p-3 shadow-sm">
                <a href="{{ route('home') }}" class="block px-3.5 py-2 rounded-xl text-sm font-medium text-[#122620] hover:bg-emerald-50">Beranda</a>
                <a href="{{ route('campaigns.index') }}" class="block px-3.5 py-2 rounded-xl text-sm font-medium text-[#122620] hover:bg-emerald-50">Jelajahi Kampanye</a>
                <a href="{{ route('about') }}" class="block px-3.5 py-2 rounded-xl text-sm font-medium text-[#122620] hover:bg-emerald-50">Tentang Kami</a>
                <a href="{{ route('about') }}#pedoman-kebaikan" class="block px-3.5 py-2 rounded-xl text-sm font-medium text-[#122620] hover:bg-emerald-50">Pedoman Kebaikan</a>
                <a href="{{ route('about') }}#faq" class="block px-3.5 py-2 rounded-xl text-sm font-medium text-[#122620] hover:bg-emerald-50">FAQ</a>
                <div class="pt-2.5 mt-2 border-t border-[#e3eae4] flex flex-wrap gap-2 md:hidden">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center px-4 py-2.5 rounded-full bg-emerald-600 text-white text-xs font-bold">Panel Admin</a>
                        @else
                            <a href="{{ route('donor.dashboard') }}" class="flex-1 text-center px-4 py-2.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold">Dashboard</a>
                            <a href="{{ route('donor.donations') }}" class="flex-1 text-center px-4 py-2.5 rounded-full border border-[#dce6df] text-[#122620] text-xs font-semibold">Riwayat</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2.5 rounded-full bg-[#122620] text-white text-xs font-semibold">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex-1 text-center px-4 py-2.5 rounded-full border border-[#dce6df] text-[#122620] text-xs font-semibold">Login</a>
                        <a href="{{ route('register') }}" class="flex-1 text-center px-4 py-2.5 rounded-full bg-emerald-600 text-white text-xs font-bold">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    @if(session('success') || session('error') || $errors->any())
        <div class="kp-container mt-4 w-full">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-3 shadow-2xs">
                    <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-3 shadow-2xs mt-2">
                    <span class="w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center shrink-0 text-xs font-bold">!</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3.5 rounded-2xl text-sm mt-2">
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

    <footer class="bg-white border-t border-[#e3eae4] mt-16">
        <div class="kp-container py-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pb-10 border-b border-[#edf2ee]">
                <div class="md:col-span-5 space-y-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-600 via-teal-600 to-sky-600 text-white flex items-center justify-center font-extrabold text-base">
                            K
                        </div>
                        <span class="font-extrabold text-lg text-[#122620]">KitaPeduli</span>
                    </div>
                    <p class="text-sm text-[#52665f] leading-relaxed max-w-md">
                        Platform crowdfunding dan donasi sosial dengan transparansi perkembangan kampanye, laporan penyaluran dana, dan doa-doa orang baik.
                    </p>
                    <p class="text-xs text-amber-800 bg-amber-50 border border-amber-200/80 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Proyek Tugas Akhir PPBO (Laravel 13): seluruh transaksi menggunakan simulasi pembayaran demo.
                    </p>
                </div>

                <div class="md:col-span-2 space-y-2.5">
                    <h4 class="text-sm font-bold text-[#122620]">Tentang</h4>
                    <ul class="space-y-2 text-sm text-[#52665f]">
                        <li><a href="{{ route('about') }}" class="hover:text-emerald-700 transition-colors">Tentang KitaPeduli</a></li>
                        <li><a href="{{ route('campaigns.index') }}" class="hover:text-emerald-700 transition-colors">Jelajahi Kampanye</a></li>
                        <li><a href="{{ route('about') }}#transparansi" class="hover:text-emerald-700 transition-colors">Transparansi Dana</a></li>
                    </ul>
                </div>

                <div class="md:col-span-2 space-y-2.5">
                    <h4 class="text-sm font-bold text-[#122620]">Panduan</h4>
                    <ul class="space-y-2 text-sm text-[#52665f]">
                        <li><a href="{{ route('about') }}#pedoman-kebaikan" class="hover:text-emerald-700 transition-colors">Pedoman Kebaikan</a></li>
                        <li><a href="{{ route('about') }}#faq" class="hover:text-emerald-700 transition-colors">FAQ (Tanya Jawab)</a></li>
                        <li><a href="{{ route('about') }}#cara-donasi" class="hover:text-emerald-700 transition-colors">Cara Berdonasi</a></li>
                    </ul>
                </div>

                <div class="md:col-span-3 space-y-2.5">
                    <h4 class="text-sm font-bold text-[#122620]">Informasi Proyek</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Email demo: layanan@kitapeduli.test<br>
                        Aplikasi demonstrasi Tugas Akhir PPBO<br>
                        Program Studi Sistem Informasi, FMIPA Universitas Tanjungpura
                    </p>
                    <div class="flex items-center gap-2 pt-1.5">
                        <span class="w-8 h-8 rounded-full bg-[#122620] text-white flex items-center justify-center text-xs font-bold">KP</span>
                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-600 to-sky-600 text-white flex items-center justify-center text-xs font-bold">ID</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#6a7d76]">
                <p>&copy; {{ date('Y') }} KitaPeduli. Proyek Tugas Akhir PPBO.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ route('about') }}#pedoman-kebaikan" class="hover:text-emerald-700 transition-colors">Pedoman Kebaikan</a>
                    <a href="{{ route('about') }}#faq" class="hover:text-emerald-700 transition-colors">FAQ</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
