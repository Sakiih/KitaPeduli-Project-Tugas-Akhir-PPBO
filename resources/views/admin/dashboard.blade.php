@extends('layouts.admin')

@section('title', 'Dashboard Admin | KitaPeduli')
@section('page_title', 'Dashboard Administrator')
@section('page_subtitle', 'Ringkasan aktual kampanye, transaksi donasi, dan transparansi KitaPeduli')

@section('content')
    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Dana Terkumpul</p>
                <p class="text-2xl font-black text-teal-600 mt-2">
    <div class="space-y-7">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <div class="kp-card p-6 bg-gradient-to-br from-white to-emerald-50/60">
                <p class="text-xs font-bold uppercase tracking-wider text-[#647770]">Total Dana Terkumpul</p>
                <p class="text-2xl font-extrabold text-emerald-700 mt-2">
                    Rp {{ number_format($stats['total_collected_funds'], 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-[#52665f] mt-1">
                    Dari <strong>{{ $stats['successful_donations'] }}</strong> donasi berhasil
                </p>
            </div>
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Dana Disalurkan</p>
                <p class="text-2xl font-black text-slate-900 mt-2">
                    Rp {{ number_format($stats['total_disbursed_funds'], 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    Akumulasi dari <a href="{{ route('admin.disbursements.index') }}" class="font-bold text-teal-600 hover:underline">laporan penyaluran dana</a>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Kampanye Donasi</p>
                <p class="text-2xl font-black text-slate-900 mt-2">
                    {{ $stats['active_campaigns'] }} Aktif <span class="text-sm font-semibold text-slate-400">/ {{ $stats['total_campaigns'] }} Total</span>
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
            <div class="kp-card p-6 bg-gradient-to-br from-white to-sky-50/50">
                <p class="text-xs font-bold uppercase tracking-wider text-[#647770]">Kampanye Donasi</p>
                <p class="text-2xl font-extrabold text-[#122620] mt-2">
                    {{ $stats['active_campaigns'] }} Aktif <span class="text-sm font-semibold text-[#647770]">/ {{ $stats['total_campaigns'] }} Total</span>
                </p>
                <p class="text-[11px] text-[#52665f] mt-1">
                    Tersebar di <strong>{{ $stats['total_categories'] }}</strong> kategori kampanye
                </p>
            </div>

            <div class="kp-card p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-[#647770]">Total Transaksi</p>
                <p class="text-2xl font-extrabold text-[#122620] mt-2">
                    {{ $stats['total_transactions'] }} Transaksi
                </p>
                <p class="text-[11px] text-[#52665f] mt-1">
                    <strong class="text-emerald-700">{{ $stats['successful_donations'] }}</strong> Sukses &bull; <strong class="text-amber-700">{{ $stats['pending_donations'] }}</strong> Pending
                </p>
            </div>

            <div class="kp-card p-6 bg-gradient-to-br from-white to-teal-50/50">
                <p class="text-xs font-bold uppercase tracking-wider text-[#647770]">Donatur &amp; Guest</p>
                <p class="text-2xl font-extrabold text-sky-700 mt-2">
                    {{ $stats['registered_donors'] }} Akun Donatur
                </p>
                <p class="text-[11px] text-[#52665f] mt-1">
                    Ditambah <strong>{{ $stats['guest_transactions'] }}</strong> transaksi Guest (tanpa akun)
                </p>
            </div>
        </div>

        <div class="kp-card p-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-extrabold text-[#122620]">Aksi Cepat Pengelolaan</h2>
                <p class="text-xs text-[#647770]">Tambahkan kampanye baru atau publikasikan kabar transparansi</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.campaigns.create') }}"
                   class="kp-btn-accent px-5 py-2.5 text-xs">
                    Buat Kampanye Baru
                </a>
                <a href="{{ route('admin.updates.index') }}"
                   class="kp-btn-secondary px-4 py-2.5 text-xs">
                    Publikasikan Kabar Terbaru
                </a>
                <a href="{{ route('admin.disbursements.index') }}"
                   class="px-4 py-2.5 rounded-full bg-sky-50 border border-sky-200/80 hover:bg-sky-100 text-sky-800 text-xs font-bold transition-colors">
                    Catat Penyaluran Dana
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <div class="xl:col-span-7 kp-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-extrabold text-[#122620]">Transaksi Donasi Terbaru</h2>
                    <a href="{{ route('admin.donations.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#e3eae4] text-[#647770] uppercase">
                                <th class="py-2.5 px-3">Referensi</th>
                                <th class="py-2.5 px-3">Donatur</th>
                                <th class="py-2.5 px-3">Kampanye</th>
                                <th class="py-2.5 px-3">Nominal</th>
                                <th class="py-2.5 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentDonations as $don)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3 px-3 font-mono font-bold text-slate-900">
                        <tbody class="divide-y divide-[#edf2ee]">
                            @foreach($recentDonations as $don)
                                <tr class="hover:bg-[#f7faf8] transition-colors">
                                    <td class="py-3 px-3 font-mono font-bold text-[#122620]">
                                        {{ $don->payment?->reference ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="font-bold text-[#122620] block">{{ $don->donor_name }}</span>
                                        <span class="text-[10px] text-[#647770]">
                                            {{ $don->isGuest() ? 'Guest (Tanpa Akun)' : 'Donatur Terdaftar' }}
                                            {{ $don->is_anonymous ? '• Anonim Publik' : '' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-[#3c524b] font-medium truncate max-w-[180px]">
                                        {{ $don->campaign?->title ?? '-' }}
                                    </td>
                                    <td class="py-3 px-3 font-extrabold text-[#122620]">
                                        Rp {{ number_format((float) $don->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $don->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $don->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-4 text-center text-xs text-slate-500">
                                        Belum ada transaksi donasi terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="xl:col-span-5 kp-card p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-extrabold text-[#122620]">Progres Kampanye Teratas</h2>
                    <a href="{{ route('admin.campaigns.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                        Kelola Kampanye &rarr;
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($topCampaigns as $camp)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-2">
                <div class="space-y-3.5">
                    @foreach($topCampaigns as $camp)
                        <div class="kp-card-soft p-4 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-xs font-bold text-[#122620] leading-snug">{{ $camp->title }}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 shrink-0">
                                    {{ $camp->progressPercentage() }}%
                                </span>
                            </div>
                            <div class="w-full h-2 bg-[#dce6df] rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-sky-500 rounded-full" style="width: {{ $camp->progressPercentage() }}%"></div>
                            </div>
                            <div class="flex justify-between text-[11px] text-[#647770]">
                                <span>Terkumpul: <strong class="text-[#122620]">Rp {{ number_format((float) $camp->collected_amount, 0, ',', '.') }}</strong></span>
                                <span>{{ $camp->donor_count }} Donasi</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-500">
                            Belum ada kampanye yang terdaftar.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
