@extends('layouts.admin')

@section('title', 'Detail Pengguna | Panel Admin KitaPeduli')
@section('page_title', 'Detail Pengguna: ' . $user->name)
@section('page_subtitle', 'Informasi akun pengguna terdaftar beserta riwayat donasinya')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-extrabold text-slate-900">{{ $user->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->isAdmin() ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                        {{ $user->role }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $user->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500">Email: {{ $user->email }} &bull; Telepon: {{ $user->phone ?? '-' }}</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">
                Kembali ke Daftar Pengguna
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
            <h3 class="text-base font-extrabold text-slate-900 mb-4">Riwayat Donasi Pengguna ({{ $user->donations_count }})</h3>

            @if($user->donations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 uppercase">
                                <th class="py-2.5 px-3">Tanggal</th>
                                <th class="py-2.5 px-3">Referensi</th>
                                <th class="py-2.5 px-3">Kampanye</th>
                                <th class="py-2.5 px-3">Nominal</th>
                                <th class="py-2.5 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($user->donations as $don)
                                <tr>
                                    <td class="py-3 px-3 text-slate-500">{{ $don->created_at?->format('d M Y, H:i') }}</td>
                                    <td class="py-3 px-3 font-mono font-bold text-slate-900">{{ $don->payment?->reference ?? '-' }}</td>
                                    <td class="py-3 px-3 font-semibold text-slate-800">{{ $don->campaign?->title ?? '-' }}</td>
                                    <td class="py-3 px-3 font-extrabold text-slate-900">Rp {{ number_format((float) $don->amount, 0, ',', '.') }}</td>
                                    <td class="py-3 px-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $don->isSuccessful() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $don->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-slate-500">Pengguna ini belum memiliki transaksi donasi.</p>
            @endif
        </div>
    </div>
@endsection
