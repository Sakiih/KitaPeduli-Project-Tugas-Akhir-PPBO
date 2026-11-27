@extends('layouts.app')

@section('title', 'Dashboard Donatur | KitaPeduli')

@section('content')
    <div class="kp-container py-8 space-y-8">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold mb-2">
                    Akun Donatur Terdaftar
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Halo, {{ $user->name }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Pantau kontribusi donasi pribadi dan doa yang telah Anda bagikan di KitaPeduli.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('donor.dashboard') }}"
                   class="px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-bold">
                    Dashboard
                </a>
                <a href="{{ route('donor.donations') }}"
                   class="px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                    Riwayat Donasi
                </a>
                <a href="{{ route('donor.profile') }}"
                   class="px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                    Profil Akun
                </a>
                <a href="{{ route('campaigns.index') }}"
                   class="px-4 py-2 rounded-full bg-teal-600 hover:bg-teal-500 text-white text-xs font-bold transition-colors">
                    Donasi Baru
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-2xs">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Donasi Berhasil</p>
                <p class="text-2xl font-black text-teal-600 mt-2">
                    Rp {{ number_format($totalSuccessAmount, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">Hanya menghitung donasi berstatus <code>success</code></p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-2xs">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Donasi Berhasil</p>
                <p class="text-2xl font-black text-slate-900 mt-2">{{ $totalSuccessCount }} Transaksi</p>
                <p class="text-[11px] text-slate-500 mt-1">Transaksi donasi yang telah selesai</p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-2xs">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Menunggu Simulasi</p>
                <p class="text-2xl font-black text-amber-600 mt-2">{{ $totalPendingCount }} Transaksi</p>
                <p class="text-[11px] text-slate-500 mt-1">Transaksi berstatus <code>pending</code></p>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-2xs">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Aamiin Diberikan</p>
                <p class="text-2xl font-black text-slate-900 mt-2">{{ $totalAmenGiven }} Doa</p>
                <p class="text-[11px] text-slate-500 mt-1">Dukungan pada Doa-doa Orang Baik</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-2xs">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Transaksi Donasi Terbaru Anda</h2>
                    <p class="text-xs text-slate-500">5 transaksi terakhir yang tercatat pada akun Anda</p>
                </div>
                <a href="{{ route('donor.donations') }}" class="text-xs font-bold text-teal-600 hover:underline">
                    Lihat Seluruh Riwayat
                </a>
            </div>

            @if($recentDonations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                <th class="py-3 px-4">Referensi</th>
                                <th class="py-3 px-4">Kampanye</th>
                                <th class="py-3 px-4">Metode</th>
                                <th class="py-3 px-4">Nominal</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($recentDonations as $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                        {{ $item->payment?->reference ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800">
                                        @if($item->campaign)
                                            <a href="{{ route('campaigns.show', $item->campaign->slug) }}" class="hover:text-teal-600">
                                                {{ $item->campaign->title }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 uppercase font-semibold text-slate-600">
                                        {{ $item->payment?->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-slate-900">
                                        Rp {{ number_format((float) $item->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $item->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right space-x-2">
                                        <a href="{{ route('donor.donations.show', $item->id) }}"
                                           class="inline-block px-3 py-1.5 rounded-full border border-slate-200 hover:bg-slate-100 font-bold text-slate-700">
                                            Detail
                                        </a>
                                        @if($item->isPending() && $item->payment)
                                            <a href="{{ route('payments.show', $item->payment->reference) }}"
                                               class="inline-block px-3 py-1.5 rounded-full bg-teal-600 hover:bg-teal-500 font-bold text-white">
                                                Simulasikan Bayar
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-xs text-slate-500 mb-3">Anda belum memiliki riwayat transaksi donasi.</p>
                    <a href="{{ route('campaigns.index') }}"
                       class="inline-block px-5 py-2.5 rounded-full bg-slate-900 text-white text-xs font-bold">
                        Jelajahi Kampanye
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
