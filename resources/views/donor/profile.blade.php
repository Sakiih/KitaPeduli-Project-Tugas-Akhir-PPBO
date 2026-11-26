@extends('layouts.app')

@section('title', 'Profil Akun Donatur | KitaPeduli')

@section('content')
    <div class="kp-container py-8 space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Profil Akun Donatur</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola identitas akun Donatur Terdaftar Anda</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('donor.dashboard') }}" class="px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Dashboard</a>
                <a href="{{ route('donor.donations') }}" class="px-4 py-2 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold">Riwayat Donasi</a>
                <a href="{{ route('donor.profile') }}" class="px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-bold">Profil</a>
            </div>
        </div>

        <div class="max-w-2xl bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-2xs">
            <form action="{{ route('donor.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                </div>

                <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="px-6 py-3 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-extrabold transition-colors cursor-pointer">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
