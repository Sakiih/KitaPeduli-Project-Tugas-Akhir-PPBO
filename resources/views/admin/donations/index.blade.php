@extends('layouts.admin')

@section('title', 'Transaksi Donasi | Panel Admin KitaPeduli')
@section('page_title', 'Pemantauan Transaksi Donasi')
@section('page_subtitle', 'Pantau seluruh transaksi donasi dari Donatur Terdaftar maupun Guest (user_id = NULL)')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-2xs">
            <form action="{{ route('admin.donations.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <select name="campaign_id" class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                    <option value="">Semua Kampanye</option>
                    @foreach($campaigns as $camp)
                        <option value="{{ $camp->id }}" {{ (int) request('campaign_id') === $camp->id ? 'selected' : '' }}>
                            {{ $camp->title }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                    <option value="">Semua Status Donasi</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <select name="donor_type" class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                    <option value="">Semua Tipe Donatur</option>
                    <option value="registered" {{ request('donor_type') === 'registered' ? 'selected' : '' }}>Donatur Terdaftar</option>
                    <option value="guest" {{ request('donor_type') === 'guest' ? 'selected' : '' }}>Guest (user_id = NULL)</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold cursor-pointer">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-4 sm:p-5 shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 uppercase text-[11px]">
                            <th class="py-3 px-1.5">Waktu</th>
                            <th class="py-3 px-1.5">Referensi</th>
                            <th class="py-3 px-1.5">Donatur</th>
                            <th class="py-3 px-1.5">Tipe</th>
                            <th class="py-3 px-1.5">Kampanye</th>
                            <th class="py-3 px-1.5">Metode</th>
                            <th class="py-3 px-1.5">Nominal</th>
                            <th class="py-3 px-1.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($donations as $don)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-1.5 text-slate-500 whitespace-nowrap">
                                    <span class="block">{{ $don->created_at?->format('d M Y') }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $don->created_at?->format('H:i') }}</span>
                                </td>
                                <td class="py-3 px-1.5 font-mono font-bold text-slate-900 text-[11px] whitespace-nowrap">
                                    {{ $don->payment?->reference ?? '-' }}
                                </td>
                                <td class="py-3 px-1.5 max-w-[140px]">
                                    <span class="font-bold text-slate-900 block truncate">{{ $don->donor_name }}</span>
                                    <span class="text-[10px] text-slate-400 block truncate">
                                        {{ $don->donor_email ?? 'Tanpa email' }}
                                        @if($don->is_anonymous)
                                            &bull; <strong class="text-amber-700">Anonim</strong>
                                        @endif
                                    </span>
                                </td>
                                <td class="py-3 px-1.5 whitespace-nowrap">
                                    @if($don->isGuest())
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-bold">
                                            Guest
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-teal-50 text-teal-800 text-[10px] font-bold">
                                            User #{{ $don->user_id }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-1.5 font-medium text-slate-700 max-w-[125px] truncate">
                                    {{ $don->campaign?->title ?? '-' }}
                                </td>
                                <td class="py-3 px-1.5 uppercase font-semibold text-slate-600 text-[11px] whitespace-nowrap">
                                    {{ $don->payment?->payment_method ?? '-' }}
                                </td>
                                <td class="py-3 px-1.5 font-extrabold text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format((float) $don->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-1.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $don->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $don->status }} / {{ $don->payment?->status ?? 'pending' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $donations->links() }}
            </div>
        </div>
    </div>
@endsection
