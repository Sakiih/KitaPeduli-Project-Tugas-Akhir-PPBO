@extends('layouts.app')

@section('title', 'Profil Akun Donatur | KitaPeduli')

@section('content')
    <div class="kp-container py-8 space-y-6">
        <div class="kp-card p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-[#122620] tracking-tight">Profil Akun Donatur</h1>
                <p class="text-xs text-[#647770] mt-0.5">Kelola identitas akun Donatur Terdaftar Anda</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('donor.dashboard') }}" class="kp-btn-secondary px-4 py-2 text-xs">Dashboard</a>
                <a href="{{ route('donor.donations') }}" class="kp-btn-secondary px-4 py-2 text-xs">Riwayat Donasi</a>
                <a href="{{ route('donor.profile') }}" class="px-4 py-2 rounded-full bg-[#122620] text-white text-xs font-bold">Profil</a>
            </div>
        </div>

        <div class="max-w-2xl kp-card p-6 sm:p-8">
            <form action="{{ route('donor.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="kp-input">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-[#3c524b] mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="kp-input">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="kp-input">
                </div>

                <div class="pt-4 border-t border-[#edf2ee] grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-bold text-[#3c524b] mb-1.5">Kata Sandi Baru (Opsional)</label>
                        <input type="password" id="password" name="password"
                               class="kp-input">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-[#3c524b] mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="kp-input">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="kp-btn-accent px-6 py-3 text-xs">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
