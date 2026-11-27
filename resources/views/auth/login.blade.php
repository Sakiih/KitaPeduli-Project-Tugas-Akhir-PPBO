@extends('layouts.app')

@section('title', 'Login Akun | KitaPeduli')

@section('content')
    <div class="kp-container py-12">
        <div class="max-w-md mx-auto kp-card p-7 sm:p-9">
            <div class="text-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 via-teal-600 to-sky-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto mb-3 shadow-xs">
                    K
                </div>
                <h1 class="text-2xl font-extrabold text-[#122620] tracking-tight">Masuk ke KitaPeduli</h1>
                <p class="text-xs text-[#647770] mt-1">
                    Login sebagai Donatur Terdaftar atau Administrator
                </p>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-[#3c524b] mb-1.5">Alamat Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="nama@email.com"
                           class="kp-input">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-[#3c524b] mb-1.5">Kata Sandi</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="Masukkan kata sandi"
                           class="kp-input">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="inline-flex items-center gap-2 text-[#52665f] cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-[#c6d5cb] text-emerald-600 focus:ring-emerald-500">
                        <span>Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="kp-btn-accent w-full py-3 px-6 text-sm">
                    Login Sekarang
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-[#edf2ee] space-y-2 text-xs">
                <p class="font-bold text-[#3c524b]">Akun Demo Pengujian (Seeder):</p>
                <div class="kp-card-soft p-3.5 space-y-1 text-[#52665f]">
                    <p><strong class="text-[#122620]">Admin:</strong> <code class="text-emerald-800 font-semibold">admin@kitapeduli.test</code> / <code class="text-[#122620]">password123</code></p>
                    <p><strong class="text-[#122620]">Donatur:</strong> <code class="text-sky-800 font-semibold">andi@kitapeduli.test</code> / <code class="text-[#122620]">password123</code></p>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-[#647770]">
                Belum punya akun Donatur?
                <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:underline">Daftar di sini</a>
            </p>
        </div>
    </div>
@endsection
