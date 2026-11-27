@extends('layouts.app')

@section('title', 'KitaPeduli | Platform Crowdfunding dan Donasi Sosial')

@section('content')
    <section class="kp-container pt-4">
        <div class="relative rounded-3xl overflow-hidden bg-slate-900 text-white min-h-[340px] sm:min-h-[410px] flex flex-col justify-between p-6 sm:p-12 shadow-sm">
            <img src="{{ asset('images/campaigns/hero-banner.svg') }}"
                 alt="Ilustrasi platform donasi KitaPeduli"
                 class="absolute inset-0 w-full h-full object-cover opacity-90">

            <div class="relative z-10 my-auto py-6 text-center">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/10 backdrop-blur-xs border border-white/15 text-teal-300 text-xs font-semibold mb-4">
                    Platform Donasi dan Solidaritas Sosial
                </span>
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-white/95 leading-none mb-4">
                    KitaPeduli
                </h1>
                <p class="max-w-2xl mx-auto text-sm sm:text-base text-slate-200 leading-relaxed">
                    Salurkan donasi untuk kampanye kesehatan, pendidikan, tanggap bencana, dan kemanusiaan secara transparan. Anda dapat berdonasi sebagai Donatur Terdaftar maupun langsung tanpa login (Guest).
                </p>

                @if($featuredCampaign)
                    <div class="mt-6 inline-flex flex-wrap items-center justify-center gap-3 bg-slate-950/60 backdrop-blur-md border border-white/15 px-4 py-2.5 rounded-2xl text-xs">
                        <span class="px-2.5 py-0.5 rounded-full bg-teal-500 text-slate-950 font-bold">Kampanye Unggulan</span>
                        <span class="font-semibold text-white">{{ $featuredCampaign->title }}</span>
                        <a href="{{ route('campaigns.show', $featuredCampaign->slug) }}"
                           class="text-teal-300 hover:text-white font-bold underline underline-offset-4">
                            Donasi Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="relative z-20 -mt-8 mx-3 sm:mx-8 bg-white rounded-2xl shadow-md border border-slate-200/80 px-5 sm:px-8 py-5 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">Bantu Sesama Hari Ini</h2>
                <p class="text-xs text-slate-500">
                    <strong class="text-slate-800">{{ $platformStats['active_campaigns'] }}</strong> Kampanye Aktif &bull;
                    <strong class="text-teal-700">Rp {{ number_format($platformStats['total_collected'], 0, ',', '.') }}</strong> Dana Terkumpul &bull;
                    <strong class="text-slate-800">{{ $platformStats['total_donors'] }}</strong> Donasi Berhasil
                </p>
            </div>

            <form action="{{ route('home') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto md:min-w-[360px]">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative flex-1">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kampanye di KitaPeduli..."
                           class="w-full pl-4 pr-4 py-2.5 rounded-full bg-slate-100 border border-slate-200 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-slate-900 transition-colors">
                </div>
                <button type="submit"
                        class="px-5 py-2.5 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-bold transition-colors cursor-pointer shrink-0">
                    Cari Kampanye
                </button>
            </form>
        </div>
    </section>

    <section class="kp-container mt-10">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-5 sm:p-8 shadow-2xs">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <aside class="lg:col-span-3 space-y-6">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 mb-3">Kategori Kampanye</h3>
                        <ul class="space-y-1.5 text-sm">
                            <li>
                                <a href="{{ route('home', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
                                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors {{ !request('category') && !request('category_id') ? 'bg-rose-50 text-rose-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                                    <span>Semua Kampanye</span>
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold {{ !request('category') && !request('category_id') ? 'bg-rose-600 text-white' : 'bg-slate-200 text-slate-700' }}">
                                        {{ $totalActiveCampaigns }}
                                    </span>
                                </a>
                            </li>

                            @foreach($categories as $cat)
                                @php
                                    $isActiveCat = request('category') === $cat->slug || (int) request('category_id') === $cat->id;
                                @endphp
                                <li>
                                    <a href="{{ route('home', array_filter(['category' => $cat->slug, 'search' => request('search'), 'sort' => request('sort')])) }}"
                                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors {{ $isActiveCat ? 'bg-slate-900 text-white font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $isActiveCat ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $cat->campaigns_count }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2.5">Urutkan Kampanye</h4>
                        <ul class="space-y-1 text-sm">
                            @php
                                $sorts = [
                                    'latest' => 'Kampanye Terbaru',
                                    'ending_soon' => 'Segera Berakhir',
                                    'most_funded' => 'Dana Terkumpul Terbanyak',
                                ];
                            @endphp
                            @foreach($sorts as $sortKey => $sortLabel)
                                <li>
                                    <a href="{{ route('home', array_filter(['category' => request('category'), 'search' => request('search'), 'sort' => $sortKey])) }}"
                                       class="block px-3.5 py-2 rounded-xl transition-colors {{ $sort === $sortKey ? 'bg-teal-50 text-teal-700 font-bold' : 'text-slate-600 hover:bg-slate-100' }}">
                                        {{ $sortLabel }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800 text-[11px] font-bold">
                            Donasi Tanpa Akun
                        </span>
                        <h4 class="text-sm font-bold text-slate-900">Bisa Donasi Tanpa Login</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Pengunjung (Guest) dapat berdonasi langsung dengan simulasi QRIS atau Transfer Bank. Daftar akun jika ingin menyimpan riwayat donasi pribadi dan memberi Aamiin.
                        </p>
                    </div>
                </aside>

                <div class="lg:col-span-9">
                    @if(request('search') || request('category'))
                        <div class="mb-5 flex flex-wrap items-center justify-between gap-2 bg-slate-50 px-4 py-3 rounded-2xl border border-slate-200/60">
                            <div class="text-xs text-slate-600">
                                Menampilkan hasil
                                @if(request('search'))
                                    pencarian <strong class="text-slate-900">{{ request('search') }}</strong>
                                @endif
                                @if(request('category'))
                                    pada kategori <strong class="text-slate-900">{{ request('category') }}</strong>
                                @endif
                            </div>
                            <a href="{{ route('home') }}" class="text-xs font-bold text-rose-600 hover:underline">
                                Reset Filter
                            </a>
                        </div>
                    @endif

                    @if($campaigns->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                            @foreach($campaigns as $campaign)
                                <x-campaign-card :campaign="$campaign" />
                            @endforeach
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100">
                            {{ $campaigns->links() }}
                        </div>
                    @else
                        <div class="text-center py-16 px-4 rounded-3xl bg-slate-50 border border-dashed border-slate-200">
                            <h3 class="text-base font-bold text-slate-800 mb-1">Belum ada kampanye yang sesuai filter</h3>
                            <p class="text-xs text-slate-500 mb-4">Coba gunakan kata kunci pencarian lain atau lihat seluruh kategori kampanye.</p>
                            <a href="{{ route('home') }}"
                               class="inline-block px-5 py-2.5 rounded-full bg-slate-900 text-white text-xs font-bold">
                                Tampilkan Semua Kampanye
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    @if($recommendations->count() > 0)
        <section class="kp-container mt-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Rekomendasi Kampanye Pilihan</h2>
                    <p class="text-xs text-slate-500">Kampanye aktif yang diprioritaskan berdasarkan unggulan dan tenggat waktu terdekat</p>
                </div>
                <a href="{{ route('campaigns.index') }}" class="text-xs font-bold text-teal-700 hover:underline">
                    Lihat Semua Kampanye
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach($recommendations as $recCampaign)
                    <x-campaign-card :campaign="$recCampaign" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="kp-container mt-14">
        <div class="rounded-3xl bg-slate-900 text-white p-8 sm:p-12 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8 shadow-md">
            <div class="space-y-3 max-w-xl">
                <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                    Jelajahi Kampanye Aktif atau Daftar sebagai Donatur
                </h2>
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="{{ route('campaigns.index') }}"
                       class="px-6 py-3 rounded-full bg-white text-slate-950 hover:bg-teal-400 text-xs font-extrabold transition-colors">
                        Lihat Semua Kampanye
                    </a>
                    @guest
                        <a href="{{ route('register') }}"
                           class="px-6 py-3 rounded-full border border-white/25 hover:bg-white/10 text-white text-xs font-bold transition-colors">
                            Daftar Akun Donatur
                        </a>
                    @endguest
                </div>
            </div>

            <div class="max-w-md space-y-2 lg:text-right">
                <p class="text-xs font-bold uppercase tracking-wider text-teal-400">Transparansi dan Akuntabilitas</p>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Setiap kampanye memuat informasi pengelola, kabar terbaru, catatan laporan penyaluran dana, serta Doa-doa Orang Baik yang dapat dilihat terbuka oleh publik.
                </p>
            </div>
        </div>
    </section>
@endsection
