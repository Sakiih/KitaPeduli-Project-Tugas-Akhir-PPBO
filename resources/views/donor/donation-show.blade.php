@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $donation->id . ' | KitaPeduli')

@section('content')
    <div class="kp-container py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">Detail Transaksi Pribadi</span>
                    <h1 class="text-xl font-extrabold text-slate-900 mt-0.5">
                        Referensi: {{ $donation->payment?->reference ?? ('#' . $donation->id) }}
                    </h1>
                </div>
                <a href="{{ route('donor.donations') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">
                    Kembali ke Riwayat
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-slate-50 p-5 rounded-2xl border border-slate-200/70">
                <div>
                    <span class="text-slate-400 block">Kampanye:</span>
                    <a href="{{ route('campaigns.show', $donation->campaign->slug) }}" class="font-bold text-slate-900 hover:text-teal-600">
                        {{ $donation->campaign->title }}
                    </a>
                </div>
                <div>
                    <span class="text-slate-400 block">Nominal Donasi:</span>
                    <span class="text-base font-black text-slate-900">
                        Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block">Status Donasi:</span>
                    <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full font-bold uppercase {{ $donation->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $donation->status }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block">Status Pembayaran:</span>
                    <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full font-bold uppercase {{ $donation->payment?->isPaid() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $donation->payment?->status ?? 'pending' }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block">Metode Pembayaran Simulasi:</span>
                    <span class="font-bold text-slate-800 uppercase">
                        {{ $donation->payment?->payment_method === 'qris' ? 'QRIS (Simulasi)' : 'Transfer Bank (Simulasi)' }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block">Tampilan Nama Publik:</span>
                    <span class="font-bold text-slate-800">
                        {{ $donation->publicDonorName() }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 block">Waktu Dibuat:</span>
                    <span class="font-semibold text-slate-700">{{ $donation->created_at?->format('d M Y, H:i:s') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Waktu Pembayaran Simulasi:</span>
                    <span class="font-semibold text-slate-700">{{ $donation->payment?->paid_at?->format('d M Y, H:i:s') ?? 'Belum dikonfirmasi' }}</span>
                </div>
            </div>

            @if($donation->donor_message)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                    <span class="text-slate-400 block mb-1">Pesan / Doa Anda:</span>
                    <p class="text-slate-800">{{ $donation->donor_message }}</p>
                </div>
            @endif

            @if($donation->payment)
                <div class="pt-2 flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('payments.show', $donation->payment->reference) }}"
                       class="px-5 py-2.5 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-extrabold transition-colors">
                        {{ $donation->isPending() ? 'Buka Halaman Simulasi Pembayaran' : 'Lihat Bukti Simulasi Pembayaran' }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
