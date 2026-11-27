@extends('layouts.admin')

@section('title', 'Profil Admin | Panel Admin KitaPeduli')
@section('page_title', 'Profil Administrator')
@section('page_subtitle', 'Perbarui informasi akun Administrator KitaPeduli')

@section('content')
    <div class="max-w-2xl bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-2xs">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Admin</label>
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
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru (Opsional)</label>
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
                    Simpan Profil Admin
                </button>
            </div>
        </form>
    </div>
@endsection
