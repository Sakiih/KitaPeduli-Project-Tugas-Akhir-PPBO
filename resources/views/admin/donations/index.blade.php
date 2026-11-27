@extends('layouts.admin')

@section('title', 'Transaksi Donasi | Panel Admin KitaPeduli')
@section('page_title', 'Pemantauan Transaksi Donasi')
@section('page_subtitle', 'Pantau seluruh transaksi donasi dari Donatur Terdaftar maupun Guest')

@section('content')
    <div class="space-y-6">
        <div class="kp-card p-5">
            <form action="{{ route('admin.donations.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <select name="campaign_id" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                    <option value="">Semua Kampanye</option>
                    @foreach($campaigns as $camp)
                        <option value="{{ $camp->id }}" {{ (int) request('campaign_id') === $camp->id ? 'selected' : '' }}>
                            {{ $camp->title }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                    <option value="">Semua Status Donasi</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <select name="donor_type" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                    <option value="">Semua Tipe Donatur</option>
                    <option value="registered" {{ request('donor_type') === 'registered' ? 'selected' : '' }}>Donatur Terdaftar</option>
                    <option value="guest" {{ request('donor_type') === 'guest' ? 'selected' : '' }}>Guest (Tanpa Akun)</option>
                </select>

                <button type="submit" class="kp-btn-primary px-4 py-2.5 text-xs">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <div class="kp-card p-4 sm:p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#e3eae4] text-[#647770] uppercase text-[11px]">
                            <th class="py-3 px-2">Waktu</th>
                            <th class="py-3 px-2">Referensi</th>
                            <th class="py-3 px-2">Donatur</th>
                            <th class="py-3 px-2">Tipe</th>
                            <th class="py-3 px-2">Kampanye</th>
                            <th class="py-3 px-2">Metode</th>
                            <th class="py-3 px-2">Nominal</th>
                            <th class="py-3 px-2">Status</th>
                        </tr>
                    </thead>
<<<<<<< Updated upstream
                    <tbody class="divide-y divide-slate-100">
                        @foreach($donations as $don)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-1.5 text-slate-500 whitespace-nowrap">
=======
                    <tbody class="divide-y divide-[#edf2ee]">
                        @foreach($donations as $don)
                            <tr class="hover:bg-[#f7faf8] transition-colors">
                                <td class="py-3 px-2 text-[#647770] whitespace-nowrap">
>>>>>>> Stashed changes
                                    <span class="block">{{ $don->created_at?->format('d M Y') }}</span>
                                    <span class="text-[10px] text-[#7a8c85]">{{ $don->created_at?->format('H:i') }}</span>
                                </td>
                                <td class="py-3 px-2 font-mono font-bold text-[#122620] text-[11px] whitespace-nowrap">
                                    {{ $don->payment?->reference ?? '-' }}
                                </td>
                                <td class="py-3 px-2 max-w-[150px]">
                                    <span class="font-bold text-[#122620] block truncate">{{ $don->donor_name }}</span>
                                    <span class="text-[10px] text-[#647770] block truncate">
                                        {{ $don->donor_email ?? 'Tanpa email' }}
                                        @if($don->is_anonymous)
                                            &bull; <strong class="text-amber-700">Anonim</strong>
                                        @endif
                                    </span>
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    @if($don->isGuest())
                                        <span class="px-2.5 py-0.5 rounded-full bg-[#f2f6f3] text-[#52665f] text-[10px] font-bold">
                                            Guest
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-800 text-[10px] font-bold">
                                            User #{{ $don->user_id }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 font-medium text-[#3c524b] max-w-[135px] truncate">
                                    {{ $don->campaign?->title ?? '-' }}
                                </td>
                                <td class="py-3 px-2 uppercase font-semibold text-[#52665f] text-[11px] whitespace-nowrap">
                                    {{ $don->payment?->payment_method ?? '-' }}
                                </td>
                                <td class="py-3 px-2 font-extrabold text-[#122620] whitespace-nowrap">
                                    Rp {{ number_format((float) $don->amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $don->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
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
