@extends('layouts.admin')

@section('title', 'Profil Admin | Panel Admin KitaPeduli')
@section('page_title', 'Profil Administrator')
@section('page_subtitle', 'Perbarui informasi akun Administrator KitaPeduli')

@section('content')
    <div class="max-w-2xl kp-card p-6 sm:p-8">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nama Lengkap Admin</label>
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
                    Simpan Profil Admin
                </button>
            </div>
        </form>
    </div>
@endsection
