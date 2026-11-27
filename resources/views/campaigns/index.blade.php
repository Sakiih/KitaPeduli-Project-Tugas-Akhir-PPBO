@extends('layouts.app')

@section('title', 'Jelajahi Kampanye | KitaPeduli')

@section('content')
    <div class="kp-container py-8 space-y-7">
        <div class="kp-card p-6 sm:p-8 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-6">
            <div>
                <span class="kp-pill bg-emerald-50 text-emerald-800 border border-emerald-200/70 mb-2">
                    Daftar Kampanye Aktif
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#122620] tracking-tight">
                    Jelajahi Kampanye <span class="text-emerald-600">KitaPeduli</span>
                </h1>
                <p class="text-xs sm:text-sm text-[#52665f] mt-1">
                    Tersedia <strong>{{ $totalActiveCampaigns }}</strong> kampanye aktif yang dapat menerima dukungan dan doa Anda.
                </p>
            </div>

            <form action="{{ route('campaigns.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2.5">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari judul kampanye atau pengelola..."
                       class="kp-input rounded-full! sm:w-72! text-xs!">

                <select name="sort"
                        onchange="this.form.submit()"
                        class="px-4 py-2.5 rounded-full bg-[#f7faf8] border border-[#d7e2da] text-xs font-semibold text-[#122620] focus:outline-none focus:border-emerald-600">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru dan Unggulan</option>
                    <option value="ending_soon" {{ $sort === 'ending_soon' ? 'selected' : '' }}>Segera Berakhir</option>
                    <option value="most_funded" {{ $sort === 'most_funded' ? 'selected' : '' }}>Dana Terbanyak</option>
                </select>

                <button type="submit"
                        class="kp-btn-primary px-5 py-2.5 text-xs shrink-0">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
            <a href="{{ route('campaigns.index', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
               class="px-4 py-2 rounded-full text-xs font-bold shrink-0 transition-colors {{ !request('category') ? 'bg-[#122620] text-white' : 'bg-white border border-[#dce6df] text-[#3c524b] hover:bg-emerald-50' }}">
                Semua Kategori ({{ $totalActiveCampaigns }})
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('campaigns.index', array_filter(['category' => $cat->slug, 'search' => request('search'), 'sort' => request('sort')])) }}"
                   class="px-4 py-2 rounded-full text-xs font-bold shrink-0 transition-colors {{ request('category') === $cat->slug ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white' : 'bg-white border border-[#dce6df] text-[#3c524b] hover:bg-emerald-50' }}">
                    {{ $cat->name }} ({{ $cat->campaigns_count }})
                </a>
            @endforeach
        </div>

        @if($campaigns->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($campaigns as $campaign)
                    <x-campaign-card :campaign="$campaign" />
                @endforeach
            </div>

            <div class="pt-4">
                {{ $campaigns->links() }}
            </div>
        @else
            <div class="kp-card p-12 text-center">
                <h3 class="text-base font-bold text-[#122620] mb-1">Kampanye tidak ditemukan</h3>
                <p class="text-xs text-[#647770] mb-4">Tidak ada kampanye aktif yang cocok dengan pencarian Anda.</p>
                <a href="{{ route('campaigns.index') }}"
                   class="kp-btn-primary px-5 py-2.5 text-xs">
                    Reset Pencarian
                </a>
            </div>
        @endif
    </div>
@endsection
