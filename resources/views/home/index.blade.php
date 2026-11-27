@extends('layouts.app')

@section('title', 'KitaPeduli | Platform Crowdfunding dan Donasi Sosial')

@section('content')
    {{-- Hero Section (Split Editorial + Overlapping Green-Blue Impact Cards) --}}
    <section class="kp-container pt-8 sm:pt-12 pb-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 items-center">
            <div class="lg:col-span-6 space-y-6">
                <span class="kp-pill bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Platform Donasi &amp; Solidaritas Sosial
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-[3.4rem] font-extrabold tracking-tight text-[#122620] leading-[1.08]">
                    Berbagi <span class="text-emerald-600">Harapan</span>,<br>
                    Wujudkan <span class="text-sky-600">Kepedulian</span>
                </h1>

                <p class="max-w-xl text-sm sm:text-base text-[#52665f] leading-relaxed">
                    Salurkan bantuan untuk kampanye kesehatan, pendidikan, tanggap bencana, dan kemanusiaan secara transparan. Anda bisa berdonasi sebagai Donatur Terdaftar maupun langsung tanpa login (Guest).
                </p>

                <div class="flex flex-wrap items-center gap-3.5 pt-1">
                    <a href="{{ route('campaigns.index') }}"
                       class="kp-btn-accent px-6 py-3 text-xs sm:text-sm">
                        Jelajahi Kampanye
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>

                    <a href="{{ route('about') }}#cara-donasi"
                       class="inline-flex items-center gap-2.5 text-xs sm:text-sm font-bold text-[#122620] hover:text-emerald-700 transition-colors px-2 py-2">
                        <span class="w-9 h-9 rounded-full bg-[#122620] text-white flex items-center justify-center shadow-xs">
                            <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                        </span>
                        Cara Berdonasi
                    </a>
                </div>

                <div class="pt-5 border-t border-[#e3eae4]">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#647770] mb-2.5">Kategori Kebaikan</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($categories->take(6) as $cat)
                            <a href="{{ route('home', array_filter(['category' => $cat->slug, 'search' => request('search'), 'sort' => request('sort')])) }}"
                               class="px-3.5 py-1.5 rounded-full text-xs font-semibold border transition-colors {{ request('category') === $cat->slug ? 'bg-[#122620] text-white border-[#122620]' : 'bg-white border-[#d7e2da] text-[#3c524b] hover:border-emerald-400 hover:text-emerald-800' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 relative flex items-center justify-center py-4 sm:py-8">
                {{-- Subtle Concentric Rings echoing reference --}}
                <div class="relative w-full max-w-[470px] aspect-square flex items-center justify-center">
                    <div class="absolute inset-2 rounded-full border border-[#dce6df]/80 pointer-events-none"></div>
                    <div class="absolute inset-12 rounded-full border border-[#dce6df]/60 pointer-events-none"></div>

                    {{-- Floating Mini Stat Card Top-Left --}}
                    <div class="hidden sm:flex absolute top-6 left-2 z-30 bg-white rounded-2xl border border-[#e3eae4] shadow-md px-4 py-3 items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-extrabold text-sm">
                            {{ $platformStats['active_campaigns'] }}
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-[#122620]">Kampanye Aktif</p>
                            <p class="text-[10px] text-[#647770]">Terverifikasi Admin</p>
                        </div>
                    </div>

                    {{-- Back Card: Warm Emerald Gradient (Total Platform Impact) --}}
                    <div class="relative z-10 w-[86%] sm:w-[78%] translate-x-4 -translate-y-10 rounded-3xl bg-gradient-to-br from-emerald-500 via-teal-500 to-emerald-600 text-white p-6 shadow-xl">
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-2.5 py-1 rounded-full bg-white/20 backdrop-blur-xs text-[11px] font-bold">
                                Dampak Bersama
                            </span>
                            <span class="text-xs font-bold text-emerald-50">KitaPeduli</span>
                        </div>
                        <p class="text-[11px] uppercase tracking-wider text-emerald-100 font-semibold">Total Dana Terkumpul</p>
                        <p class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-0.5">
                            Rp {{ number_format($platformStats['total_collected'], 0, ',', '.') }}
                        </p>
                        <div class="mt-4 pt-3 border-t border-white/20 flex items-center justify-between text-xs text-emerald-50">
                            <span><strong>{{ $platformStats['total_donors'] }}</strong> Donasi Berhasil</span>
                            <span>100% Transparan</span>
                        </div>
                    </div>

                    {{-- Front Overlapping Card: Deep Teal-to-Ocean-Blue Featured Campaign --}}
                    <div class="absolute bottom-6 left-2 sm:left-4 z-20 w-[88%] sm:w-[80%] rounded-3xl bg-gradient-to-br from-[#0f3d3e] via-[#0d6568] to-sky-700 text-white p-6 shadow-2xl border border-white/15">
                        @if($featuredCampaign)
                            @php
                                $featProgress = $featuredCampaign->progressPercentage();
                            @endphp
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 rounded-full bg-sky-400/25 border border-sky-300/30 text-sky-100 text-[10px] font-bold uppercase tracking-wider">
                                    Kampanye Unggulan
                                </span>
                                <span class="text-xs font-bold text-emerald-300">{{ $featProgress }}%</span>
                            </div>
                            <h3 class="font-bold text-base leading-snug line-clamp-2 mb-3">
                                {{ $featuredCampaign->title }}
                            </h3>
                            <div class="w-full h-1.5 bg-white/15 rounded-full overflow-hidden mb-3">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-sky-300 rounded-full" style="width: {{ $featProgress }}%"></div>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <div class="text-xs">
                                    <span class="text-sky-100/75 block text-[10px]">Terkumpul</span>
                                    <span class="font-extrabold">Rp {{ number_format((float) $featuredCampaign->collected_amount, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('campaigns.show', $featuredCampaign->slug) }}"
                                   class="px-4 py-2 rounded-full bg-white text-[#122620] hover:bg-emerald-50 text-xs font-extrabold transition-colors shrink-0">
                                    Donasi Sekarang
                                </a>
                            </div>
                        @else
                            <div class="space-y-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-sky-400/25 text-sky-100 text-[10px] font-bold uppercase">
                                    Solidaritas Terbuka
                                </span>
                                <h3 class="font-bold text-base leading-snug">Bantu Sesama Hari Ini</h3>
                                <p class="text-xs text-sky-100/80">Pilih kampanye pilihan dan kirimkan doa terbaik Anda.</p>
                                <a href="{{ route('campaigns.index') }}"
                                   class="inline-block mt-2 px-4 py-2 rounded-full bg-white text-[#122620] text-xs font-extrabold">
                                    Lihat Kampanye
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Search & Live Stats Bar --}}
    <section class="kp-container mt-2">
        <div class="kp-card px-5 sm:px-7 py-4 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 text-xs text-[#52665f]">
                <span class="inline-flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <strong class="text-[#122620] text-sm">{{ $platformStats['active_campaigns'] }}</strong> Kampanye Aktif
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    <strong class="text-emerald-700 text-sm">Rp {{ number_format($platformStats['total_collected'], 0, ',', '.') }}</strong> Dana Terkumpul
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <strong class="text-[#122620] text-sm">{{ $platformStats['total_donors'] }}</strong> Donasi Berhasil
                </span>
            </div>

            <form action="{{ route('home') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto md:min-w-[360px]">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari judul kampanye atau pengelola..."
                       class="kp-input rounded-full! py-2! text-xs!">
                <button type="submit"
                        class="kp-btn-primary px-5 py-2.5 text-xs shrink-0">
                    Cari
                </button>
            </form>
        </div>
    </section>

    {{-- Editorial Value Pillars (4 Warm Green-Blue Tonal Cards) --}}
    <section class="kp-container mt-14">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <span class="kp-pill bg-[#122620] text-white">Transparansi &amp; Empati</span>
            <span class="text-xs text-[#647770]">Dirancang agar kebaikan mudah tersampaikan.</span>
        </div>

        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#122620] tracking-tight leading-snug max-w-4xl mb-8">
            Salurkan <span class="text-emerald-600">dukungan nyata</span> dengan alur yang sederhana, <span class="text-sky-600">transparan</span>, dan dapat dipantau setiap saat.
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-3xl p-6 bg-gradient-to-br from-emerald-50 to-emerald-100/70 border border-emerald-200/70 flex flex-col justify-between min-h-[170px]">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[#122620] text-sm mb-1">Pengelola Terverifikasi</h3>
                    <p class="text-xs text-[#4b635b] leading-relaxed">Informasi pengelola dan tujuan penggalangan dana tercatat jelas di setiap kampanye.</p>
                </div>
            </div>

            <div class="rounded-3xl p-6 bg-gradient-to-br from-teal-50 to-teal-100/70 border border-teal-200/70 flex flex-col justify-between min-h-[170px]">
                <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[#122620] text-sm mb-1">Donasi Tanpa Login</h3>
                    <p class="text-xs text-[#4b635b] leading-relaxed">Pengunjung (Guest) bisa langsung berdonasi cepat atau masuk untuk menyimpan riwayat.</p>
                </div>
            </div>

            <div class="rounded-3xl p-6 bg-gradient-to-br from-cyan-50 to-sky-100/70 border border-sky-200/70 flex flex-col justify-between min-h-[170px]">
                <div class="w-10 h-10 rounded-2xl bg-sky-600 text-white flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[#122620] text-sm mb-1">Laporan Penyaluran Dana</h3>
                    <p class="text-xs text-[#4b635b] leading-relaxed">Catatan pencairan dana dan kabar terbaru dipublikasikan terbuka untuk publik.</p>
                </div>
            </div>

            <div class="rounded-3xl p-6 bg-gradient-to-br from-sky-50 to-emerald-50 border border-emerald-200/60 flex flex-col justify-between min-h-[170px]">
                <div class="w-10 h-10 rounded-2xl bg-[#122620] text-white flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[#122620] text-sm mb-1">Doa-doa Orang Baik</h3>
                    <p class="text-xs text-[#4b635b] leading-relaxed">Kirim pesan dukungan, tampil sebagai anonim bila diinginkan, dan beri Aamiin.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Campaign Directory Section --}}
    <section class="kp-container mt-14">
        <div class="kp-card p-5 sm:p-8 space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-[#edf2ee]">
                <div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#122620]">Kampanye Aktif KitaPeduli</h2>
                    <p class="text-xs text-[#647770] mt-0.5">Pilih kategori atau urutkan kampanye sesuai kepedulian Anda</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    @php
                        $sorts = [
                            'latest' => 'Terbaru',
                            'ending_soon' => 'Segera Berakhir',
                            'most_funded' => 'Dana Terbanyak',
                        ];
                    @endphp
                    @foreach($sorts as $sortKey => $sortLabel)
                        <a href="{{ route('home', array_filter(['category' => request('category'), 'search' => request('search'), 'sort' => $sortKey])) }}"
                           class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-colors {{ $sort === $sortKey ? 'bg-sky-600 text-white' : 'bg-[#f2f6f3] text-[#3c524b] hover:bg-emerald-50' }}">
                            {{ $sortLabel }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Horizontal Category Filter Pills --}}
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                <a href="{{ route('home', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold shrink-0 transition-colors {{ !request('category') && !request('category_id') ? 'bg-[#122620] text-white' : 'bg-[#f2f6f3] text-[#3c524b] hover:bg-emerald-50' }}">
                    <span>Semua Kampanye</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('category') && !request('category_id') ? 'bg-white/20 text-white' : 'bg-white text-[#122620]' }}">
                        {{ $totalActiveCampaigns }}
                    </span>
                </a>

                @foreach($categories as $cat)
                    @php
                        $isActiveCat = request('category') === $cat->slug || (int) request('category_id') === $cat->id;
                    @endphp
                    <a href="{{ route('home', array_filter(['category' => $cat->slug, 'search' => request('search'), 'sort' => request('sort')])) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold shrink-0 transition-colors {{ $isActiveCat ? 'bg-emerald-600 text-white' : 'bg-[#f2f6f3] text-[#3c524b] hover:bg-emerald-50' }}">
                        <span>{{ $cat->name }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] {{ $isActiveCat ? 'bg-white/20 text-white' : 'bg-white text-[#52665f]' }}">
                            {{ $cat->campaigns_count }}
                        </span>
                    </a>
                @endforeach
            </div>

            @if(request('search') || request('category'))
                <div class="flex flex-wrap items-center justify-between gap-2 bg-emerald-50/60 px-4 py-3 rounded-2xl border border-emerald-200/60">
                    <div class="text-xs text-[#3c524b]">
                        Menampilkan hasil
                        @if(request('search'))
                            pencarian <strong class="text-[#122620]">{{ request('search') }}</strong>
                        @endif
                        @if(request('category'))
                            pada kategori <strong class="text-[#122620]">{{ request('category') }}</strong>
                        @endif
                    </div>
                    <a href="{{ route('home') }}" class="text-xs font-bold text-rose-600 hover:underline">
                        Reset Filter
                    </a>
                </div>
            @endif

            @if($campaigns->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($campaigns as $campaign)
                        <x-campaign-card :campaign="$campaign" />
                    @endforeach
                </div>

                <div class="pt-4 border-t border-[#edf2ee]">
                    {{ $campaigns->links() }}
                </div>
            @else
                <div class="text-center py-14 px-4 rounded-2xl bg-[#f6f8f6] border border-dashed border-[#d7e2da]">
                    <h3 class="text-base font-bold text-[#122620] mb-1">Belum ada kampanye yang sesuai filter</h3>
                    <p class="text-xs text-[#647770] mb-4">Coba gunakan kata kunci pencarian lain atau lihat seluruh kategori kampanye.</p>
                    <a href="{{ route('home') }}" class="kp-btn-primary px-5 py-2.5 text-xs">
                        Tampilkan Semua Kampanye
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- 3 Easy Steps Band (Directly inspired by reference's green feature band) --}}
    <section class="kp-container mt-14">
        <div class="rounded-3xl bg-gradient-to-br from-emerald-500 via-teal-600 to-sky-600 text-white p-6 sm:p-10 lg:p-12 shadow-md">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-5">
                    <div class="rounded-3xl bg-[#102420] text-white p-6 sm:p-7 shadow-xl border border-white/10 space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-[11px] font-bold">
                                Ringkasan Platform
                            </span>
                            <span class="text-[11px] text-sky-300 font-semibold">Simulasi Demo</span>
                        </div>

                        <div>
                            <p class="text-xs text-[#9cb5ad]">Total Donasi Terhimpun</p>
                            <p class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-1">
                                Rp {{ number_format($platformStats['total_collected'], 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5 pt-2">
                            <a href="{{ route('campaigns.index') }}"
                               class="text-center py-2.5 px-3 rounded-full bg-emerald-500 hover:bg-emerald-400 text-[#0b1b18] text-xs font-extrabold transition-colors">
                                Donasi Sekarang
                            </a>
                            @guest
                                <a href="{{ route('register') }}"
                                   class="text-center py-2.5 px-3 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-colors">
                                    Daftar Akun
                                </a>
                            @else
                                <a href="{{ route('about') }}"
                                   class="text-center py-2.5 px-3 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-colors">
                                    Pedoman
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-5">
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight">
                        Mulai Berbagi Kebaikan dalam 3 Langkah Mudah
                    </h2>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-full bg-white/20 backdrop-blur-xs flex items-center justify-center font-extrabold text-sm shrink-0">
                                1
                            </div>
                            <div>
                                <h3 class="font-bold text-sm sm:text-base">Pilih Kampanye Kebaikan</h3>
                                <p class="text-xs sm:text-sm text-emerald-50/90 leading-relaxed">
                                    Telusuri kategori kampanye aktif, pelajari cerita penggalangan dana, dan cek profil pengelolanya.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-full bg-white/20 backdrop-blur-xs flex items-center justify-center font-extrabold text-sm shrink-0">
                                2
                            </div>
                            <div>
                                <h3 class="font-bold text-sm sm:text-base">Isi Nominal, Doa &amp; Metode Simulasi</h3>
                                <p class="text-xs sm:text-sm text-emerald-50/90 leading-relaxed">
                                    Berdonasi sebagai Donatur Terdaftar atau Guest tanpa login, pilih QRIS atau Transfer Bank simulasi.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-full bg-white/20 backdrop-blur-xs flex items-center justify-center font-extrabold text-sm shrink-0">
                                3
                            </div>
                            <div>
                                <h3 class="font-bold text-sm sm:text-base">Konfirmasi &amp; Pantau Transparansi</h3>
                                <p class="text-xs sm:text-sm text-emerald-50/90 leading-relaxed">
                                    Saldo kampanye bertambah otomatis, doa Anda tampil di Doa Orang Baik, dan penyaluran dana tercatat rapi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($recommendations->count() > 0)
        <section class="kp-container mt-14">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-6">
                <div>
                    <span class="kp-pill bg-sky-50 text-sky-800 border border-sky-200/70 mb-2">Pilihan Mendesak</span>
                    <h2 class="text-2xl font-extrabold text-[#122620]">Rekomendasi Kampanye Pilihan</h2>
                    <p class="text-xs text-[#647770] mt-0.5">Kampanye aktif yang diprioritaskan berdasarkan unggulan dan tenggat waktu terdekat</p>
                </div>
                <a href="{{ route('campaigns.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                    Lihat Semua Kampanye &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($recommendations as $recCampaign)
                    <x-campaign-card :campaign="$recCampaign" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
