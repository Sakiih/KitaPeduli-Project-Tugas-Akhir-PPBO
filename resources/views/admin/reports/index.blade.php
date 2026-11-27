@extends('layouts.admin')

@section('title', 'Laporan Donasi | Panel Admin KitaPeduli')
@section('page_title', 'Laporan dan Statistik Donasi')
@section('page_subtitle', 'Rekapitulasi transaksi donasi berhasil berdasarkan periode, kampanye, dan metode pembayaran')

@section('content')
    <div class="space-y-6">
        <div class="kp-card p-5">
            <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-[#52665f] mb-1">Kampanye</label>
                    <select name="campaign_id" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                        <option value="">Semua Kampanye</option>
                        @foreach($campaigns as $camp)
                            <option value="{{ $camp->id }}" {{ (int) request('campaign_id') === $camp->id ? 'selected' : '' }}>
                                {{ $camp->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#52665f] mb-1">Metode Pembayaran</label>
                    <select name="payment_method" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                        <option value="">Semua Metode</option>
                        <option value="qris" {{ request('payment_method') === 'qris' ? 'selected' : '' }}>QRIS Simulasi</option>
                        <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Transfer Bank Simulasi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#52665f] mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="px-3.5 py-2 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-[#52665f] mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="px-3.5 py-2 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                </div>

                <button type="submit" class="kp-btn-primary px-5 py-2.5 text-xs">
                    Tampilkan Laporan
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="kp-card p-5 bg-gradient-to-br from-white to-emerald-50/60">
                <p class="text-[11px] font-bold uppercase text-[#647770]">Total Dana Donasi Sukses</p>
                <p class="text-xl font-extrabold text-emerald-700 mt-1">
                    Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-[#52665f] mt-1">{{ $summary['total_count'] }} transaksi berhasil</p>
            </div>

            <div class="kp-card p-5 bg-gradient-to-br from-white to-sky-50/50">
                <p class="text-[11px] font-bold uppercase text-[#647770]">Komposisi Donatur</p>
                <p class="text-xl font-extrabold text-[#122620] mt-1">
                    {{ $summary['registered_count'] }} Terdaftar / {{ $summary['guest_count'] }} Guest
                </p>
                <p class="text-[11px] text-slate-500 mt-1">Seluruh transaksi tercatat di MySQL</p>
                <p class="text-[11px] text-[#52665f] mt-1">Seluruh transaksi tercatat di database</p>
                <p class="text-[11px] text-slate-500 mt-1">Seluruh transaksi tercatat di sistem</p>
            </div>

            <div class="kp-card p-5">
                <p class="text-[11px] font-bold uppercase text-[#647770]">Total via QRIS Simulasi</p>
                <p class="text-xl font-extrabold text-[#122620] mt-1">
                    Rp {{ number_format($summary['qris_amount'], 0, ',', '.') }}
                </p>
            </div>

            <div class="kp-card p-5">
                <p class="text-[11px] font-bold uppercase text-[#647770]">Total via Transfer Bank Simulasi</p>
                <p class="text-xl font-extrabold text-sky-700 mt-1">
                    Rp {{ number_format($summary['bank_amount'], 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="kp-card p-6">
            <h2 class="text-base font-extrabold text-[#122620] mb-4">Rincian Transaksi Donasi Berhasil</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#e3eae4] text-[#647770] uppercase">
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Referensi</th>
                            <th class="py-3 px-3">Donatur</th>
                            <th class="py-3 px-3">Tipe</th>
                            <th class="py-3 px-3">Kampanye</th>
                            <th class="py-3 px-3">Metode</th>
                            <th class="py-3 px-3">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($donations as $don)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-3 text-slate-500">{{ $don->created_at?->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-3 font-mono font-bold text-slate-900">{{ $don->payment?->reference ?? '-' }}</td>
                                <td class="py-3 px-3 font-bold text-slate-900">{{ $don->donor_name }}</td>
                                <td class="py-3 px-3">{{ $don->isGuest() ? 'Guest' : 'Terdaftar' }}</td>
                                <td class="py-3 px-3 text-slate-700">{{ $don->campaign?->title ?? '-' }}</td>
                                <td class="py-3 px-3 uppercase font-semibold text-slate-600">{{ $don->payment?->payment_method ?? '-' }}</td>
                                <td class="py-3 px-3 font-extrabold text-teal-700">Rp {{ number_format((float) $don->amount, 0, ',', '.') }}</td>
                    <tbody class="divide-y divide-[#edf2ee]">
                        @foreach($donations as $don)
                            <tr class="hover:bg-[#f7faf8] transition-colors">
                                <td class="py-3 px-3 text-[#647770]">{{ $don->created_at?->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-3 font-mono font-bold text-[#122620]">{{ $don->payment?->reference ?? '-' }}</td>
                                <td class="py-3 px-3 font-bold text-[#122620]">{{ $don->donor_name }}</td>
                                <td class="py-3 px-3 text-[#52665f]">{{ $don->isGuest() ? 'Guest' : 'Terdaftar' }}</td>
                                <td class="py-3 px-3 text-[#3c524b]">{{ $don->campaign?->title ?? '-' }}</td>
                                <td class="py-3 px-3 uppercase font-semibold text-[#52665f]">{{ $don->payment?->payment_method ?? '-' }}</td>
                                <td class="py-3 px-3 font-extrabold text-emerald-700">Rp {{ number_format((float) $don->amount, 0, ',', '.') }}</td>s
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center text-xs text-slate-500">
                                    Belum ada transaksi donasi berhasil pada rentang filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $donations->links() }}
            </div>
        </div>
    </div>
@endsection
