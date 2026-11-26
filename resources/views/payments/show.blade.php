@extends('layouts.app')

@section('title', 'Simulasi Pembayaran ' . $payment->reference . ' | KitaPeduli')

@section('content')
    <div class="kp-container py-8">
        <div class="max-w-3xl mx-auto space-y-6">

            <div class="bg-amber-50 border-2 border-amber-300 text-amber-950 p-4 sm:p-5 rounded-3xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-2xs">
                <div class="space-y-0.5">
                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-amber-400 text-slate-950 text-[11px] font-black tracking-wide">
                        {{ $instruction['simulation_notice'] ?? 'SIMULASI / DEMO: Bukan pembayaran nyata' }}
                    </span>
                    <p class="text-xs text-amber-900 font-medium pt-1">
                        Halaman ini merupakan simulasi akademik tugas akhir PPBO. Jangan melakukan transfer atau pemindaian QR finansial sungguhan.
                    </p>
                </div>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase shrink-0 {{ $payment->isPaid() ? 'bg-emerald-600 text-white' : 'bg-amber-200 text-amber-950' }}">
                    Status: {{ $payment->status }}
                </span>
            </div>

            @if($payment->isPaid())
                <div class="bg-emerald-600 text-white rounded-3xl p-6 sm:p-8 shadow-md space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-white text-emerald-700 font-black text-sm flex items-center justify-center shrink-0">
                            Lunas
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-200">Transaksi Donasi Berhasil</span>
                            <h1 class="text-xl sm:text-2xl font-extrabold">Terima Kasih, {{ $donation->publicDonorName() }}.</h1>
                        </div>
                    </div>

                    <p class="text-xs sm:text-sm text-emerald-50 leading-relaxed">
                        Simulasi pembayaran dengan kode referensi <strong>{{ $payment->reference }}</strong> berhasil dikonfirmasi pada <strong>{{ $payment->paid_at?->format('d M Y, H:i:s') }}</strong>. Dana terkumpul pada kampanye <strong>{{ $campaign->title }}</strong> diperbarui menjadi <strong>Rp {{ number_format((float) $campaign->collected_amount, 0, ',', '.') }}</strong>.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('campaigns.show', $campaign->slug) }}#doa-orang-baik"
                           class="px-5 py-2.5 rounded-full bg-white text-slate-950 hover:bg-emerald-50 text-xs font-extrabold transition-colors">
                            Lihat Kampanye dan Doa Orang Baik
                        </a>
                        @auth
                            @if(auth()->user()->isDonor())
                                <a href="{{ route('donor.donations') }}"
                                   class="px-5 py-2.5 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold transition-colors">
                                    Buka Riwayat Donasi Pribadi
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">
                            {{ $instruction['instruction_title'] ?? 'Instruksi Pembayaran Simulasi' }}
                        </span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 mt-0.5">
                            {{ $instruction['formatted_amount'] ?? ('Rp ' . number_format((float) $payment->amount, 0, ',', '.')) }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Referensi Transaksi: <code class="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">{{ $payment->reference }}</code>
                        </p>
                    </div>

                    <div class="text-left sm:text-right space-y-1">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $donation->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            Status Donasi: {{ strtoupper($donation->status) }}
                        </span>
                        <p class="text-[11px] text-slate-400">
                            Tipe Donatur: <strong class="text-slate-700">{{ $donation->isGuest() ? 'Guest (Tanpa Login)' : 'Donatur Terdaftar' }}</strong>
                        </p>
                    </div>
                </div>

                @if($payment->payment_method === 'qris')
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center bg-slate-50 p-5 sm:p-6 rounded-3xl border border-slate-200/80">
                        <div class="md:col-span-5 flex flex-col items-center">
                            <div class="bg-white p-4 rounded-2xl border-2 border-slate-900 shadow-xs w-56 text-center space-y-2">
                                <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                    <span class="font-black text-xs tracking-tight text-slate-900">QRIS Demo</span>
                                    <span class="px-2 py-0.5 rounded bg-rose-600 text-white text-[10px] font-bold">Simulasi</span>
                                </div>

                                <div class="text-[11px] font-bold text-slate-800 truncate">
                                    {{ $instruction['details']['merchant_name'] ?? 'KitaPeduli Demo' }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono">
                                    {{ $instruction['details']['nmid_demo'] ?? 'ID-DEMO-KITAPEDULI' }}
                                </div>

                                <div class="p-2 bg-slate-50 rounded-xl border border-slate-200">
                                    <svg viewBox="0 0 120 120" class="w-36 h-36 mx-auto" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Ilustrasi QRIS simulasi">
                                        <rect width="120" height="120" fill="#ffffff"/>
                                        <rect x="8" y="8" width="32" height="32" fill="none" stroke="#0f172a" stroke-width="6"/>
                                        <rect x="18" y="18" width="12" height="12" fill="#0f172a"/>
                                        <rect x="80" y="8" width="32" height="32" fill="none" stroke="#0f172a" stroke-width="6"/>
                                        <rect x="90" y="18" width="12" height="12" fill="#0f172a"/>
                                        <rect x="8" y="80" width="32" height="32" fill="none" stroke="#0f172a" stroke-width="6"/>
                                        <rect x="18" y="90" width="12" height="12" fill="#0f172a"/>
                                        <rect x="48" y="12" width="8" height="8" fill="#0f172a"/>
                                        <rect x="62" y="20" width="8" height="16" fill="#0f172a"/>
                                        <rect x="48" y="48" width="24" height="24" rx="4" fill="#0d9488"/>
                                        <text x="60" y="64" font-size="11" font-weight="bold" fill="#ffffff" text-anchor="middle">KP</text>
                                        <rect x="14" y="48" width="16" height="8" fill="#0f172a"/>
                                        <rect x="82" y="52" width="24" height="8" fill="#0f172a"/>
                                        <rect x="50" y="82" width="12" height="24" fill="#0f172a"/>
                                        <rect x="74" y="78" width="16" height="16" fill="#0f172a"/>
                                        <rect x="96" y="92" width="14" height="18" fill="#0f172a"/>
                                    </svg>
                                </div>

                                <p class="text-[10px] font-bold text-amber-700 bg-amber-50 py-1 px-2 rounded">
                                    Kode QR demo: jangan dipindai
                                </p>
                            </div>
                        </div>

                        <div class="md:col-span-7 space-y-4">
                            <div>
                                <h3 class="text-base font-extrabold text-slate-900">Detail Transaksi QRIS Simulasi</h3>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $instruction['summary'] ?? '' }}</p>
                            </div>

                            <div class="space-y-2 text-xs bg-white p-4 rounded-2xl border border-slate-200/80">
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">Merchant Simulasi:</span>
                                    <span class="font-bold text-slate-900">{{ $instruction['details']['merchant_name'] ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">NMID Demo:</span>
                                    <span class="font-mono font-semibold text-slate-700">{{ $instruction['details']['nmid_demo'] ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-slate-500">Payload Simulasi:</span>
                                    <span class="font-mono text-[11px] text-teal-700 truncate max-w-[210px]">{{ $instruction['details']['qr_payload'] ?? '-' }}</span>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Langkah Simulasi Pembayaran:</h4>
                                <ol class="list-decimal list-inside space-y-1.5 text-xs text-slate-600">
                                    @foreach(($instruction['steps'] ?? []) as $step)
                                        <li>{{ $step }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-slate-50 p-5 sm:p-6 rounded-3xl border border-slate-200/80 space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200">
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-teal-600">
                                    {{ $instruction['details']['bank_name'] ?? 'Bank Nasional Simulasi' }}
                                </span>
                                <p class="text-xs text-slate-500 mt-0.5">Nomor Virtual Account / Rekening Simulasi:</p>
                                <p class="text-xl sm:text-2xl font-black font-mono tracking-wider text-slate-900 mt-1">
                                    {{ $instruction['details']['account_number'] ?? '8800 0000 000000' }}
                                </p>
                                <p class="text-xs font-semibold text-slate-600 mt-1">
                                    a.n. {{ $instruction['details']['account_holder'] ?? 'Yayasan KitaPeduli Indonesia (Simulasi)' }}
                                </p>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-[11px] font-extrabold">
                                    Rekening Demo
                                </span>
                                <p class="text-xs text-slate-500 mt-2">
                                    Kode Bank: <strong class="text-slate-800">{{ $instruction['details']['bank_code'] ?? '999' }}</strong>
                                </p>
                                <p class="text-xs text-slate-500">
                                    Berita Transfer: <strong class="text-slate-800">{{ $instruction['details']['transfer_note'] ?? $payment->reference }}</strong>
                                </p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Langkah Simulasi Transfer ATM / Mobile Banking:</h4>
                            <ol class="list-decimal list-inside space-y-1.5 text-xs text-slate-600">
                                @foreach(($instruction['steps'] ?? []) as $step)
                                    <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-200/60">
                    <div>
                        <span class="text-slate-400 block">Kampanye Tujuan:</span>
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="font-bold text-slate-900 hover:text-teal-600">
                            {{ $campaign->title }}
                        </a>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Identitas Donatur (Tampilan Publik):</span>
                        <span class="font-bold text-slate-900">
                            {{ $donation->publicDonorName() }}
                            @if($donation->is_anonymous)
                                <span class="text-[11px] font-normal text-slate-500">(Nama asli disembunyikan dari publik)</span>
                            @endif
                        </span>
                    </div>
                    @if($donation->donor_message)
                        <div class="sm:col-span-2 pt-1">
                            <span class="text-slate-400 block">Pesan / Doa Anda:</span>
                            <p class="text-slate-700">{{ $donation->donor_message }}</p>
                        </div>
                    @endif
                </div>

                @if($payment->isPending())
                    @if(auth()->check() && auth()->user()->isAdmin())
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-center text-xs font-semibold text-amber-900">
                            Admin hanya memantau transaksi dan tidak dapat menjalankan simulasi pembayaran donatur.
                        </div>
                    @else
                        <form action="{{ route('payments.simulate', $payment->reference) }}" method="POST" class="pt-2">
                            @csrf
                            <button type="submit"
                                    class="w-full py-4 px-6 rounded-full bg-teal-600 hover:bg-teal-500 text-white text-sm font-extrabold shadow-sm transition-colors cursor-pointer">
                                {{ $instruction['confirm_button_label'] ?? 'Simulasikan Pembayaran Berhasil' }}
                            </button>
                        </form>
                    @endif
                @else
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-center text-xs font-bold text-emerald-900">
                        Transaksi ini telah selesai dikonfirmasi (dibayar / berhasil) dan terkunci dari konfirmasi ulang.
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
