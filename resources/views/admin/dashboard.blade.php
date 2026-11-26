@extends('layouts.admin')

@section('title', 'Dashboard Admin | KitaPeduli')
@section('page_title', 'Dashboard Administrator')
@section('page_subtitle', 'Ringkasan aktual kampanye, transaksi donasi, dan transparansi KitaPeduli')

@section('content')
    <div class="space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Dana Terkumpul</p>
                <p class="text-2xl font-black text-teal-600 mt-2">
                    Rp {{ number_format($stats['total_collected_funds'], 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Dari <strong>{{ $stats['successful_donations'] }}</strong> donasi berhasil (<code>success</code>)
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kampanye Donasi</p>
                <p class="text-2xl font-black text-slate-900 mt-2">
                    {{ $stats['active_campaigns'] }} Aktif <span class="text-sm font-semibold text-slate-400">/ {{ $stats['total_campaigns'] }} Total</span>
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Tersebar di <strong>{{ $stats['total_categories'] }}</strong> kategori kampanye
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Transaksi</p>
                <p class="text-2xl font-black text-slate-900 mt-2">
                    {{ $stats['total_transactions'] }} Transaksi
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    <strong>{{ $stats['successful_donations'] }}</strong> Sukses &bull; <strong>{{ $stats['pending_donations'] }}</strong> Pending
                </p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Donatur dan Guest</p>
                <p class="text-2xl font-black text-slate-900 mt-2">
                    {{ $stats['registered_donors'] }} Akun Donatur
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Ditambah <strong>{{ $stats['guest_transactions'] }}</strong> transaksi Guest (<code>user_id = NULL</code>)
                </p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Aksi Cepat Pengelolaan</h2>
                <p class="text-xs text-slate-500">Tambahkan kampanye baru atau publikasikan kabar transparansi</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.campaigns.create') }}"
                   class="px-4 py-2.5 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-bold transition-colors">
                    Buat Kampanye Baru
                </a>
                <a href="{{ route('admin.updates.index') }}"
                   class="px-4 py-2.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition-colors">
                    Publikasikan Kabar Terbaru
                </a>
                <a href="{{ route('admin.disbursements.index') }}"
                   class="px-4 py-2.5 rounded-full bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-bold transition-colors">
                    Catat Penyaluran Dana
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <div class="xl:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-extrabold text-slate-900">Transaksi Donasi Terbaru</h2>
                    <a href="{{ route('admin.donations.index') }}" class="text-xs font-bold text-teal-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 uppercase">
                                <th class="py-2.5 px-3">Referensi</th>
                                <th class="py-2.5 px-3">Donatur</th>
                                <th class="py-2.5 px-3">Kampanye</th>
                                <th class="py-2.5 px-3">Nominal</th>
                                <th class="py-2.5 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentDonations as $don)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-3 font-mono font-bold text-slate-900">
                                        {{ $don->payment?->reference ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="font-bold text-slate-800 block">{{ $don->donor_name }}</span>
                                        <span class="text-[10px] text-slate-400">
                                            {{ $don->isGuest() ? 'Guest (Tanpa Akun)' : 'Donatur Terdaftar' }}
                                            {{ $don->is_anonymous ? '• Anonim Publik' : '' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-slate-700 font-medium truncate max-w-[180px]">
                                        {{ $don->campaign?->title ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3 font-extrabold text-slate-900">
                                        Rp {{ number_format((float) $don->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $don->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $don->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="xl:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-extrabold text-slate-900">Progres Kampanye Teratas</h2>
                    <a href="{{ route('admin.campaigns.index') }}" class="text-xs font-bold text-teal-600 hover:underline">
                        Kelola Kampanye
                    </a>
                </div>

                <div class="space-y-4">
                    @foreach($topCampaigns as $camp)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-xs font-bold text-slate-900 leading-snug">{{ $camp->title }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800 shrink-0">
                                    {{ $camp->progressPercentage() }}%
                                </span>
                            </div>
                            <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-teal-600 rounded-full" style="width: {{ $camp->progressPercentage() }}%"></div>
                            </div>
                            <div class="flex justify-between text-[11px] text-slate-500">
                                <span>Terkumpul: <strong class="text-slate-900">Rp {{ number_format((float) $camp->collected_amount, 0, ',', '.') }}</strong></span>
                                <span>{{ $camp->donor_count }} Donasi</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
