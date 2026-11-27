@extends('layouts.app')

@section('title', 'Riwayat Donasi Pribadi | KitaPeduli')

@section('content')
    <div class="kp-container py-8 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Riwayat Donasi Pribadi</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Daftar seluruh transaksi donasi milik akun <strong>{{ auth()->user()->name }}</strong>
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('donor.dashboard') }}" class="px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Dashboard</a>
                <a href="{{ route('donor.donations') }}" class="px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-bold">Riwayat Donasi</a>
                <a href="{{ route('donor.profile') }}" class="px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Profil</a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-2xs">
            <div class="flex flex-wrap items-center gap-2 mb-6">
                <a href="{{ route('donor.donations') }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700' }}">
                    Semua Status
                </a>
                <a href="{{ route('donor.donations', ['status' => 'success']) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ request('status') === 'success' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700' }}">
                    Berhasil (Success)
                </a>
                <a href="{{ route('donor.donations', ['status' => 'pending']) }}"
                   class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-700' }}">
                    Menunggu Simulasi (Pending)
                </a>
            </div>

            @if($donations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs uppercase text-slate-400">
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Referensi</th>
                                <th class="py-3 px-4">Kampanye</th>
                                <th class="py-3 px-4">Metode</th>
                                <th class="py-3 px-4">Nominal</th>
                                <th class="py-3 px-4">Status Donasi</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @foreach($donations as $donation)
                                <tr class="hover:bg-slate-50">
                                    <td class="py-3.5 px-4 text-slate-500">
                                        {{ $donation->created_at?->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                        {{ $donation->payment?->reference ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800">
                                        {{ $donation->campaign?->title ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 uppercase font-semibold text-slate-600">
                                        {{ $donation->payment?->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-extrabold text-slate-900">
                                        Rp {{ number_format((float) $donation->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase {{ $donation->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $donation->status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right space-x-2">
                                        <a href="{{ route('donor.donations.show', $donation->id) }}"
                                           class="inline-block px-3.5 py-1.5 rounded-full border border-slate-200 hover:bg-slate-100 font-bold text-slate-700">
                                            Detail
                                        </a>
                                        @if($donation->payment)
                                            <a href="{{ route('payments.show', $donation->payment->reference) }}"
                                               class="inline-block px-3.5 py-1.5 rounded-full {{ $donation->isPending() ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-700' }} font-bold">
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
                <div class="text-center py-12 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-xs text-slate-500">Belum ada transaksi donasi yang ditemukan.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
