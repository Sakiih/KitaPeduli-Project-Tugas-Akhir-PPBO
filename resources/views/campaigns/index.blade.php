@extends('layouts.app')

@section('title', 'Jelajahi Kampanye | KitaPeduli')

@section('content')
    <div class="kp-container py-8 space-y-8">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-2xs flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-6">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold mb-2">
                    Daftar Kampanye Aktif
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Jelajahi Kampanye KitaPeduli</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Tersedia {{ $totalActiveCampaigns }} kampanye aktif yang dapat menerima dukungan dan doa Anda.
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
                       class="w-full sm:w-72 px-4 py-2.5 rounded-full bg-slate-100 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:border-slate-900">

                <select name="sort"
                        onchange="this.form.submit()"
                        class="px-4 py-2.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700 focus:outline-none">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru dan Unggulan</option>
                    <option value="ending_soon" {{ $sort === 'ending_soon' ? 'selected' : '' }}>Segera Berakhir</option>
                    <option value="most_funded" {{ $sort === 'most_funded' ? 'selected' : '' }}>Dana Terbanyak</option>
                </select>

                <button type="submit"
                        class="px-5 py-2.5 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-bold transition-colors cursor-pointer">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <div class="flex flex-wrap items-center gap-2 pb-1">
            <a href="{{ route('campaigns.index', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
               class="px-4 py-2 rounded-full text-xs font-bold shrink-0 transition-colors {{ !request('category') ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                Semua Kategori ({{ $totalActiveCampaigns }})
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('campaigns.index', array_filter(['category' => $cat->slug, 'search' => request('search'), 'sort' => request('sort')])) }}"
                   class="px-4 py-2 rounded-full text-xs font-bold shrink-0 transition-colors {{ request('category') === $cat->slug ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }}">
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
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center">
                <h3 class="text-base font-bold text-slate-800 mb-1">Kampanye tidak ditemukan</h3>
                <p class="text-xs text-slate-500 mb-4">Tidak ada kampanye aktif yang cocok dengan pencarian Anda.</p>
                <a href="{{ route('campaigns.index') }}"
                   class="inline-block px-5 py-2.5 rounded-full bg-slate-900 text-white text-xs font-bold">
                    Reset Pencarian
                </a>
            </div>
        @endif
    </div>
@endsection
