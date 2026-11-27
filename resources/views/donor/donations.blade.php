@extends('layouts.app')

@section('title', 'Riwayat Donasi Pribadi | KitaPeduli')

@section('content')
    <div class="kp-container py-8 space-y-6">
        <div class="kp-card p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-[#122620] tracking-tight">Riwayat Donasi Pribadi</h1>
                <p class="text-xs text-[#647770] mt-0.5">
                    Daftar seluruh transaksi donasi milik akun <strong class="text-[#122620]">{{ auth()->user()->name }}</strong>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('donor.dashboard') }}" class="kp-btn-secondary px-4 py-2 text-xs">Dashboard</a>
                <a href="{{ route('donor.donations') }}" class="px-4 py-2 rounded-full bg-[#122620] text-white text-xs font-bold">Riwayat Donasi</a>
                <a href="{{ route('donor.profile') }}" class="kp-btn-secondary px-4 py-2 text-xs">Profil</a>
            </div>
        </div>

        <div class="kp-card p-6 sm:p-8">
            <div class="flex flex-wrap items-center gap-2 mb-6">
                <a href="{{ route('donor.donations') }}"
                   class="px-4 py-1.5 rounded-full text-xs font-bold transition-colors {{ !request('status') ? 'bg-[#122620] text-white' : 'bg-[#f2f6f3] text-[#3c524b] hover:bg-emerald-50' }}">
                    Semua Status
                </a>
                <a href="{{ route('donor.donations', ['status' => 'success']) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-bold transition-colors {{ request('status') === 'success' ? 'bg-emerald-600 text-white' : 'bg-[#f2f6f3] text-[#3c524b] hover:bg-emerald-50' }}">
                    Berhasil
                </a>
                <a href="{{ route('donor.donations', ['status' => 'pending']) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-bold transition-colors {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-[#f2f6f3] text-[#3c524b] hover:bg-amber-50' }}">
                    Menunggu Simulasi
                </a>
            </div>

            @if($donations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-[#e3eae4] text-xs uppercase text-[#647770]">
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Referensi</th>
                                <th class="py-3 px-4">Kampanye</th>
                                <th class="py-3 px-4">Metode</th>
                                <th class="py-3 px-4">Nominal</th>
                                <th class="py-3 px-4">Status Donasi</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#edf2ee] text-xs">
                            @foreach($donations as $donation)
                                <tr class="hover:bg-[#f7faf8] transition-colors">
                                    <td class="py-3.5 px-4 text-[#647770] whitespace-nowrap">
                                        {{ $donation->created_at?->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-[#122620] whitespace-nowrap">
                                        {{ $donation->payment?->reference ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-[#122620]">
                                        {{ $donation->campaign?->title ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 uppercase font-semibold text-[#52665f] whitespace-nowrap">
                                        {{ $donation->payment?->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-[#122620] whitespace-nowrap">
                                        Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $donation->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $donation->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right space-x-2 whitespace-nowrap">
                                        <a href="{{ route('donor.donations.show', $donation->id) }}"
                                           class="kp-btn-secondary px-3.5 py-1.5 text-xs">
                                            Detail
                                        </a>
                                        @if($donation->payment)
                                            <a href="{{ route('payments.show', $donation->payment->reference) }}"
                                               class="inline-flex items-center px-3.5 py-1.5 rounded-full {{ $donation->isPending() ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white' : 'bg-[#f2f6f3] text-[#3c524b] hover:bg-emerald-50' }} font-bold text-xs">
                                                {{ $donation->isPending() ? 'Simulasikan' : 'Instruksi' }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $donations->links() }}
                </div>
            @else
                <div class="text-center py-12 bg-[#f6f8f6] rounded-2xl border border-dashed border-[#d7e2da]">
                    <p class="text-xs text-[#647770]">Belum ada transaksi donasi yang ditemukan.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
