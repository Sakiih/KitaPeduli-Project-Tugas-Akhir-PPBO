@extends('layouts.app')

@section('title', 'Registrasi Donatur | KitaPeduli')

@section('content')
    <div class="kp-container py-12">
        <div class="max-w-md mx-auto bg-white rounded-3xl border border-slate-200/80 p-7 sm:p-9 shadow-sm">
            <div class="text-center mb-6">
                <span class="inline-block px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-[11px] font-bold mb-2">
                    Akun Donatur Terdaftar
                </span>
                <h1 class="text-2xl font-extrabold text-slate-900">Daftar Akun Donatur</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Simpan riwayat donasi pribadi Anda dan berikan Aamiin pada doa-doa Orang Baik
                </p>
            </div>

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-600">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Contoh: Andi Pratama"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:border-slate-900">
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-600">*</span></label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="andi@example.com"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:border-slate-900">
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp (Opsional)</label>
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="0812xxxxxxxx"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:border-slate-900">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi <span class="text-rose-600">*</span></label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:border-slate-900">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi <span class="text-rose-600">*</span></label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           placeholder="Ulangi kata sandi"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:border-slate-900">
                </div>

                <button type="submit"
                        class="w-full py-3 px-6 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-sm font-extrabold transition-colors cursor-pointer">
                    Buat Akun Donatur
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-slate-500">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:underline">Login di sini</a>
            </p>
        </div>
    </div>
@endsection
