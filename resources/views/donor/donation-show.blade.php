@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $donation->id . ' | KitaPeduli')

@section('content')
    <div class="kp-container py-8">
        <div class="max-w-2xl mx-auto kp-card p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-[#edf2ee]">
                <div>
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Detail Transaksi Pribadi</span>
                    <h1 class="text-xl font-extrabold text-[#122620] mt-0.5">
                        Referensi: {{ $donation->payment?->reference ?? ('#' . $donation->id) }}
                    </h1>
                </div>
                <a href="{{ route('donor.donations') }}" class="kp-btn-secondary px-4 py-2 text-xs">
                    Kembali ke Riwayat
                </a>
            </div>

            <div class="kp-card-soft grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs p-5">
                <div>
                    <span class="text-[#647770] block">Kampanye:</span>
                    @if($donation->campaign)
                        <a href="{{ route('campaigns.show', $donation->campaign->slug) }}" class="font-bold text-[#122620] hover:text-emerald-700">
                            {{ $donation->campaign->title }}
                        </a>
                    @else
                        <span class="font-bold text-[#647770]">-</span>
                    @endif
                </div>
                <div>
                    <span class="text-[#647770] block">Nominal Donasi:</span>
                    <span class="text-base font-extrabold text-emerald-700">
                        Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}
                    </span>
                </div>
                <div>
                    <span class="text-[#647770] block">Status Donasi:</span>
                    <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full font-bold uppercase {{ $donation->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $donation->status }}
                    </span>
                </div>
                <div>
                    <span class="text-[#647770] block">Status Pembayaran:</span>
                    <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full font-bold uppercase {{ $donation->payment?->isPaid() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $donation->payment?->status ?? 'pending' }}
                    </span>
                </div>
                <div>
                    <span class="text-[#647770] block">Metode Pembayaran Simulasi:</span>
                    <span class="font-bold text-[#122620] uppercase">
                        {{ $donation->payment?->payment_method === 'qris' ? 'QRIS (Simulasi)' : 'Transfer Bank (Simulasi)' }}
                    </span>
                </div>
                <div>
                    <span class="text-[#647770] block">Tampilan Nama Publik:</span>
                    <span class="font-bold text-[#122620]">
                        {{ $donation->publicDonorName() }}
                    </span>
                </div>
                <div>
                    <span class="text-[#647770] block">Waktu Dibuat:</span>
                    <span class="font-semibold text-[#3c524b]">{{ $donation->created_at?->format('d M Y, H:i:s') }}</span>
                </div>
                <div>
                    <span class="text-[#647770] block">Waktu Pembayaran Simulasi:</span>
                    <span class="font-semibold text-[#3c524b]">{{ $donation->payment?->paid_at?->format('d M Y, H:i:s') ?? 'Belum dikonfirmasi' }}</span>
                </div>
            </div>

            @if($donation->donor_message)
                <div class="kp-card-soft p-4 text-xs">
                    <span class="text-[#647770] block mb-1">Pesan / Doa Anda:</span>
                    <p class="text-[#122620]">{{ $donation->donor_message }}</p>
                </div>
            @endif

            @if($donation->payment)
                <div class="pt-2 flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('payments.show', $donation->payment->reference) }}"
                       class="kp-btn-accent px-5 py-2.5 text-xs">
                        {{ $donation->isPending() ? 'Buka Halaman Simulasi Pembayaran' : 'Lihat Bukti Simulasi Pembayaran' }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
