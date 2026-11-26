<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin | KitaPeduli')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">

    <div class="min-h-screen flex">
        <div id="kp-admin-overlay" class="fixed inset-0 bg-slate-900/50 z-30 hidden lg:hidden"></div>

        <aside id="kp-admin-sidebar"
               class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-200 flex flex-col transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-teal-500 text-slate-950 font-extrabold flex items-center justify-center text-lg">
                        K
                    </div>
                    <div>
                        <span class="font-extrabold text-white text-base block leading-none">KitaPeduli</span>
                        <span class="text-[11px] text-teal-400 font-medium">Administrator Panel</span>
                    </div>
                </a>
            </div>

            <nav class="flex-1 p-4 space-y-1 overflow-y-auto text-sm font-medium">
                <p class="px-3 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.campaigns.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.campaigns.*') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Kampanye</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Kategori</span>
                </a>

                <p class="px-3 pt-4 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">Transparansi</p>

                <a href="{{ route('admin.updates.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.updates.*') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Kabar Terbaru</span>
                </a>

                <a href="{{ route('admin.disbursements.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.disbursements.*') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Laporan Penyaluran Dana</span>
                </a>

                <p class="px-3 pt-4 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-500">Pemantauan dan Akun</p>

                <a href="{{ route('admin.donations.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.donations.*') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Transaksi Donasi</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Pengguna</span>
                </a>

                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.reports.*') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Laporan Donasi</span>
                </a>

                <a href="{{ route('admin.profile') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl {{ request()->routeIs('admin.profile') ? 'bg-teal-600 text-white font-semibold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors">
                    <span>Profil Admin</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-800 space-y-2">
                <a href="{{ route('home') }}"
                   class="block text-center px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 transition-colors">
                    Lihat Website Publik
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-xs font-semibold text-white transition-colors cursor-pointer">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button id="kp-admin-sidebar-btn" type="button"
                            aria-label="Buka menu admin"
                            class="lg:hidden p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-slate-900">@yield('page_title', 'Dashboard Admin')</h1>
                        <p class="text-xs text-slate-500">@yield('page_subtitle', 'Kelola kampanye, kategori, dan transparansi KitaPeduli')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-teal-600 font-semibold uppercase">Administrator</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-8 overflow-x-hidden">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-2xl text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-2xl text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 rounded-2xl text-sm">
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
