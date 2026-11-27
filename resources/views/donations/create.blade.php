@extends('layouts.app')

@section('title', 'Donasi untuk ' . $campaign->title . ' | KitaPeduli')

@section('content')
    <div class="kp-container py-8">
        <div class="max-w-3xl mx-auto space-y-6">

            <div class="kp-card p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <img src="{{ $campaign->bannerUrl() }}"
                     alt="{{ $campaign->title }}"
                     class="w-full sm:w-28 h-24 rounded-2xl object-cover bg-[#eef3ef] shrink-0">
                <div class="flex-1 min-w-0">
                    <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">
                        {{ $campaign->category?->name ?? 'Kampanye Sosial' }}
                    </span>
                    <h1 class="text-base sm:text-lg font-extrabold text-[#122620] leading-snug">
                        {{ $campaign->title }}
                    </h1>
                    <p class="text-xs text-[#647770] mt-0.5">
                        Pengelola: <strong class="text-[#122620]">{{ $campaign->organizer_name }}</strong>
                    </p>
                </div>
                <a href="{{ route('campaigns.show', $campaign->slug) }}"
                   class="kp-btn-secondary px-4 py-2 text-xs shrink-0">
                    Kembali
                </a>
            </div>

            @auth
                <div class="bg-emerald-50 border border-emerald-200/80 text-emerald-900 px-5 py-3.5 rounded-2xl text-xs flex items-center justify-between gap-3">
                    <div>
                        Anda berdonasi sebagai <strong>Donatur Terdaftar ({{ auth()->user()->name }})</strong>. Transaksi ini akan tersimpan di Riwayat Donasi Pribadi Anda.
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-r from-[#0f2420] to-[#0d4d57] text-white px-5 py-4 rounded-2xl text-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-400 text-[#0b1b18] font-bold text-[10px] mr-1.5">Mode Guest</span>
                        Anda berdonasi <strong>tanpa login (Guest Donation)</strong>. Transaksi Anda tetap tercatat aman di database.
                    </div>
                    <a href="{{ route('login') }}" class="text-sky-300 hover:text-white font-bold underline shrink-0">
                        Login Akun Donatur
                    </a>
                </div>
            @endauth

            <form action="{{ route('donations.store', $campaign->slug) }}" method="POST"
                  class="kp-card p-6 sm:p-8 space-y-6">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                <div>
                    <label for="donation-amount-input" class="block text-sm font-extrabold text-[#122620] mb-2.5">
                        1. Masukkan Nominal Donasi <span class="text-rose-600">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 mb-3">
                        @foreach([25000, 50000, 100000, 250000, 500000, 1000000] as $preset)
                            <button type="button"
                                    data-quick-amount="{{ $preset }}"
                                    class="py-2.5 px-3 rounded-xl border border-[#d7e2da] bg-white hover:border-emerald-500 text-[#122620] text-xs font-bold transition-colors cursor-pointer">
                                Rp {{ number_format($preset, 0, ',', '.') }}
                            </button>
                        @endforeach
                    </div>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-sm font-extrabold text-[#52665f]">Rp</span>
                        <input type="number"
                               id="donation-amount-input"
                               name="amount"
                               min="1000"
                               step="1"
                               required
                               value="{{ old('amount', 100000) }}"
                               placeholder="Minimal 1000"
                               class="kp-input pl-12! py-3! text-base! font-extrabold!">
                    </div>
                    <p class="text-[11px] text-[#647770] mt-1">Minimal nominal donasi Rp 1.000 (Simulasi).</p>
                </div>

                <div class="pt-4 border-t border-[#edf2ee] space-y-4">
                    <h2 class="text-sm font-extrabold text-[#122620]">2. Identitas Donatur</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="donor_name" class="block text-xs font-bold text-[#3c524b] mb-1.5">
                                Nama Donatur <span class="text-rose-600">*</span>
                            </label>
                            <input type="text"
                                   id="donor_name"
                                   name="donor_name"
                                   required
                                   value="{{ old('donor_name', auth()->user()?->name) }}"
                                   placeholder="Nama lengkap Anda"
                                   class="kp-input">
                        </div>

                        <div>
                            <label for="donor_email" class="block text-xs font-bold text-[#3c524b] mb-1.5">
                                Email Donatur (Opsional)
                            </label>
                            <input type="email"
                                   id="donor_email"
                                   name="donor_email"
                                   value="{{ old('donor_email', auth()->user()?->email) }}"
                                   placeholder="email@example.com"
                                   class="kp-input">
                        </div>
                    </div>

                    <label class="kp-card-soft flex items-start gap-3 p-3.5 cursor-pointer">
                        <input type="checkbox"
                               name="is_anonymous"
                               value="1"
                               {{ old('is_anonymous') ? 'checked' : '' }}
                               class="mt-0.5 rounded border-[#c6d5cb] text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="text-xs font-bold text-[#122620] block">Sembunyikan nama saya (Donasi sebagai Orang Baik / Anonim)</span>
                            <span class="text-[11px] text-[#647770]">Nama asli Anda tidak akan ditampilkan pada daftar publik Doa-doa Orang Baik.</span>
                        </div>
                    </label>
                </div>

                <div class="pt-4 border-t border-[#edf2ee]">
                    <label for="donor_message" class="block text-sm font-extrabold text-[#122620] mb-1.5">
                        3. Tuliskan Pesan atau Doa (Opsional)
                    </label>
                    <textarea id="donor_message"
                              name="donor_message"
                              rows="3"
                              placeholder="Tulis doa atau dukungan untuk kampanye ini..."
                              class="kp-input">{{ old('donor_message') }}</textarea>
                    <p class="text-[11px] text-[#647770] mt-1">
                        Doa Anda akan ditampilkan pada bagian <strong>Doa-doa Orang Baik</strong> setelah simulasi pembayaran berhasil.
                    </p>
                </div>

                <div class="pt-4 border-t border-[#edf2ee] space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <label class="block text-sm font-extrabold text-[#122620]">
                            4. Pilih Metode Pembayaran Simulasi <span class="text-rose-600">*</span>
                        </label>
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[11px] font-bold">
                            Mode Simulasi Demo
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <label class="relative flex items-start gap-3.5 p-4 rounded-2xl border-2 border-[#dce6df] hover:border-emerald-500 cursor-pointer transition-colors has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50">
                            <input type="radio"
                                   name="payment_method"
                                   value="qris"
                                   {{ old('payment_method', 'qris') === 'qris' ? 'checked' : '' }}
                                   class="mt-1 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="text-sm font-extrabold text-[#122620] block">QRIS (Simulasi Demo)</span>
                                <span class="text-xs text-[#647770]">Menampilkan kode QRIS demo dan tombol konfirmasi simulasi.</span>
                            </div>
                        </label>

                        <label class="relative flex items-start gap-3.5 p-4 rounded-2xl border-2 border-[#dce6df] hover:border-sky-500 cursor-pointer transition-colors has-[:checked]:border-sky-600 has-[:checked]:bg-sky-50/50">
                            <input type="radio"
                                   name="payment_method"
                                   value="bank_transfer"
                                   {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}
                                   class="mt-1 text-sky-600 focus:ring-sky-500">
                            <div>
                                <span class="text-sm font-extrabold text-[#122620] block">Transfer Bank / ATM (Simulasi Demo)</span>
                                <span class="text-xs text-[#647770]">Menampilkan nomor rekening Virtual Account demo dan simulasi transfer.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="kp-btn-accent w-full py-4 px-6 text-sm">
                        Lanjutkan ke Simulasi Pembayaran
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
