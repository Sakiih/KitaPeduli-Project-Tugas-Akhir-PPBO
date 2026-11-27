@extends('layouts.admin')

@section('title', 'Detail Pengguna | Panel Admin KitaPeduli')
@section('page_title', 'Detail Pengguna: ' . $user->name)
@section('page_subtitle', 'Informasi akun pengguna terdaftar beserta riwayat donasinya')

@section('content')
    <div class="space-y-6">
        <div class="kp-card p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-extrabold text-[#122620]">{{ $user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->isAdmin() ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ $user->role }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $user->status }}
                    </span>
                </div>
                <p class="text-xs text-[#647770]">Email: {{ $user->email }} &bull; Telepon: {{ $user->phone ?? '-' }}</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="kp-btn-secondary px-4 py-2 text-xs">
                Kembali ke Daftar Pengguna
            </a>
        </div>

        <div class="kp-card p-6">
            <h3 class="text-base font-extrabold text-[#122620] mb-4">Riwayat Donasi Pengguna ({{ $user->donations_count }})</h3>

            @if($user->donations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-[#e3eae4] text-[#647770] uppercase">
                                <th class="py-2.5 px-3">Tanggal</th>
                                <th class="py-2.5 px-3">Referensi</th>
                                <th class="py-2.5 px-3">Kampanye</th>
                                <th class="py-2.5 px-3">Nominal</th>
                                <th class="py-2.5 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#edf2ee]">
                            @foreach($user->donations as $don)
                                <tr class="hover:bg-[#f7faf8]">
                                    <td class="py-3 px-3 text-[#647770]">{{ $don->created_at?->format('d M Y, H:i') }}</td>
                                    <td class="py-3 px-3 font-mono font-bold text-[#122620]">{{ $don->payment?->reference ?? '-' }}</td>
                                    <td class="py-3 px-3 font-semibold text-[#122620]">{{ $don->campaign?->title ?? '-' }}</td>
                                    <td class="py-3 px-3 font-extrabold text-[#122620]">Rp {{ number_format((float) $don->amount, 0, ',', '.') }}</td>
                                    <td class="py-3 px-3">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $don->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $don->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-[#647770]">Pengguna ini belum memiliki transaksi donasi.</p>
            @endif
        </div>
    </div>
@endsection
