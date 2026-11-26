@extends('layouts.app')

@section('title', 'Login Akun | KitaPeduli')

@section('content')
    <div class="kp-container py-12">
        <div class="max-w-md mx-auto bg-white rounded-3xl border border-slate-200/80 p-7 sm:p-9 shadow-sm">
            <div class="text-center mb-6">
                <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white font-extrabold text-xl flex items-center justify-center mx-auto mb-3">
                    K
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900">Masuk ke KitaPeduli</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Login sebagai Donatur Terdaftar atau Administrator
                </p>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           placeholder="nama@email.com"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:border-slate-900">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="Masukkan kata sandi"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm focus:outline-none focus:bg-white focus:border-slate-900">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="inline-flex items-center gap-2 text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300">
                        <span>Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3 px-6 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-sm font-extrabold transition-colors cursor-pointer">
                    Login Sekarang
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 space-y-2 text-xs">
                <p class="font-bold text-slate-700">Akun Demo Pengujian (Seeder):</p>
                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70 space-y-1 text-slate-600">
                    <p><strong class="text-slate-900">Admin:</strong> <code>admin@kitapeduli.test</code> / <code>password123</code></p>
                    <p><strong class="text-slate-900">Donatur:</strong> <code>andi@kitapeduli.test</code> / <code>password123</code></p>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-slate-500">
                Belum punya akun Donatur?
                <a href="{{ route('register') }}" class="font-bold text-teal-600 hover:underline">Daftar di sini</a>
            </p>
        </div>
    </div>
@endsection
