@extends('layouts.app')

@section('title', 'Dashboard Donatur | KitaPeduli')

@section('content')
    <div class="kp-container py-8 space-y-7">
        <div class="kp-card p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="kp-pill bg-emerald-50 text-emerald-800 border border-emerald-200/70 mb-2">
                    Akun Donatur Terdaftar
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#122620] tracking-tight">Halo, {{ $user->name }}</h1>
                <p class="text-xs sm:text-sm text-[#52665f] mt-0.5">
                    Pantau kontribusi donasi pribadi dan doa yang telah Anda bagikan di KitaPeduli.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('donor.dashboard') }}"
                   class="px-4 py-2 rounded-full bg-[#122620] text-white text-xs font-bold">
                    Dashboard
                </a>
                <a href="{{ route('donor.donations') }}"
                   class="kp-btn-secondary px-4 py-2 text-xs">
                    Riwayat Donasi
                </a>
                <a href="{{ route('donor.profile') }}"
                   class="kp-btn-secondary px-4 py-2 text-xs">
                    Profil Akun
                </a>
                <a href="{{ route('campaigns.index') }}"
                   class="kp-btn-accent px-4 py-2 text-xs">
                    Donasi Baru
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="kp-card p-6 bg-gradient-to-br from-white to-emerald-50/50">
                <p class="text-xs font-bold text-[#647770] uppercase tracking-wider">Total Donasi Berhasil</p>
                <p class="text-2xl font-extrabold text-emerald-700 mt-2">
                    Rp {{ number_format($totalSuccessAmount, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-[#647770] mt-1">Transaksi yang telah dikonfirmasi berhasil</p>
            </div>

            <div class="kp-card p-6 bg-gradient-to-br from-white to-sky-50/50">
                <p class="text-xs font-bold text-[#647770] uppercase tracking-wider">Donasi Berhasil</p>
                <p class="text-2xl font-extrabold text-[#122620] mt-2">{{ $totalSuccessCount }} Transaksi</p>
                <p class="text-[11px] text-[#647770] mt-1">Transaksi donasi yang telah selesai</p>
            </div>

            <div class="kp-card p-6">
                <p class="text-xs font-bold text-[#647770] uppercase tracking-wider">Menunggu Simulasi</p>
                <p class="text-2xl font-extrabold text-amber-600 mt-2">{{ $totalPendingCount }} Transaksi</p>
                <p class="text-[11px] text-[#647770] mt-1">Menunggu konfirmasi simulasi bayar</p>
            </div>

            <div class="kp-card p-6 bg-gradient-to-br from-white to-teal-50/50">
                <p class="text-xs font-bold text-[#647770] uppercase tracking-wider">Aamiin Diberikan</p>
                <p class="text-2xl font-extrabold text-sky-700 mt-2">{{ $totalAmenGiven }} Doa</p>
                <p class="text-[11px] text-[#647770] mt-1">Dukungan pada Doa-doa Orang Baik</p>
            </div>
        </div>

        <div class="kp-card p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-extrabold text-[#122620]">Transaksi Donasi Terbaru Anda</h2>
                    <p class="text-xs text-[#647770]">5 transaksi terakhir yang tercatat pada akun Anda</p>
                </div>
                <a href="{{ route('donor.donations') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                    Lihat Seluruh Riwayat &rarr;
                </a>
            </div>

            @if($recentDonations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-[#e3eae4] text-xs uppercase text-[#647770]">
                                <th class="py-3 px-4">Referensi</th>
                                <th class="py-3 px-4">Kampanye</th>
                                <th class="py-3 px-4">Metode</th>
                                <th class="py-3 px-4">Nominal</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#edf2ee] text-xs">
                            @foreach($recentDonations as $item)
                                <tr class="hover:bg-[#f7faf8] transition-colors">
                                    <td class="py-3.5 px-4 font-mono font-bold text-[#122620]">
                                        {{ $item->payment?->reference ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-[#122620]">
                                        @if($item->campaign)
                                            <a href="{{ route('campaigns.show', $item->campaign->slug) }}" class="hover:text-emerald-700">
                                                {{ $item->campaign->title }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 uppercase font-semibold text-[#52665f]">
                                        {{ $item->payment?->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-[#122620]">
                                        Rp {{ number_format((float) $item->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $item->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right space-x-2">
                                        <a href="{{ route('donor.donations.show', $item->id) }}"
                                           class="kp-btn-secondary px-3 py-1.5 text-xs">
                                            Detail
                                        </a>
                                        @if($item->isPending() && $item->payment)
                                            <a href="{{ route('payments.show', $item->payment->reference) }}"
                                               class="kp-btn-accent px-3 py-1.5 text-xs">
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
                <div class="text-center py-10 bg-[#f6f8f6] rounded-2xl border border-dashed border-[#d7e2da]">
                    <p class="text-xs text-[#647770] mb-3">Anda belum memiliki riwayat transaksi donasi.</p>
                    <a href="{{ route('campaigns.index') }}"
                       class="kp-btn-primary px-5 py-2.5 text-xs">
                        Jelajahi Kampanye
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
