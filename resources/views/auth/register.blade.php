@extends('layouts.app')

@section('title', 'Registrasi Donatur | KitaPeduli')

@section('content')
    <div class="kp-container py-12">
        <div class="max-w-md mx-auto kp-card p-7 sm:p-9">
            <div class="text-center mb-6">
                <span class="kp-pill bg-emerald-50 text-emerald-800 border border-emerald-200/70 mb-2">
                    Akun Donatur Terdaftar
                </span>
                <h1 class="text-2xl font-extrabold text-[#122620] tracking-tight">Daftar Akun Donatur</h1>
                <p class="text-xs text-[#647770] mt-1">
                    Simpan riwayat donasi pribadi Anda dan berikan Aamiin pada doa-doa Orang Baik
                </p>
            </div>

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nama Lengkap <span class="text-rose-600">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Contoh: Andi Pratama"
                           class="kp-input">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-[#3c524b] mb-1.5">Alamat Email <span class="text-rose-600">*</span></label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="andi@example.com"
                           class="kp-input">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nomor Telepon / WhatsApp (Opsional)</label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="0812xxxxxxxx"
                           class="kp-input">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-[#3c524b] mb-1.5">Kata Sandi <span class="text-rose-600">*</span></label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="Minimal 8 karakter"
                           class="kp-input">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-[#3c524b] mb-1.5">Konfirmasi Kata Sandi <span class="text-rose-600">*</span></label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           placeholder="Ulangi kata sandi"
                           class="kp-input">
                </div>

                <button type="submit"
                        class="kp-btn-accent w-full py-3 px-6 text-sm">
                    Buat Akun Donatur
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-[#647770]">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:underline">Login di sini</a>
            </p>
        </div>
    </div>
@endsection
