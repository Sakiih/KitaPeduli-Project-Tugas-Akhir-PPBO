@extends('layouts.admin')

@section('title', 'Kelola Pengguna | Panel Admin KitaPeduli')
@section('page_title', 'Manajemen Pengguna Terdaftar')
@section('page_subtitle', 'Daftar akun Admin dan Donatur Terdaftar (Guest tidak disimpan pada tabel users)')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-2xs">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau email..."
                       class="px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs w-64">

                <select name="role" class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                    <option value="">Semua Role</option>
                    <option value="donor" {{ request('role') === 'donor' ? 'selected' : '' }}>Donatur Terdaftar (donor)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator (admin)</option>
                </select>

                <select name="status" class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                    <option value="">Semua Status Akun</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold cursor-pointer">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 uppercase">
                            <th class="py-3 px-3">ID</th>
                            <th class="py-3 px-3">Nama</th>
                            <th class="py-3 px-3">Email dan Telepon</th>
                            <th class="py-3 px-3">Role</th>
                            <th class="py-3 px-3">Jumlah Donasi</th>
                            <th class="py-3 px-3">Status Akun</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 px-3 font-mono font-bold text-slate-700">#{{ $u->id }}</td>
                                <td class="py-3.5 px-3 font-bold text-slate-900">{{ $u->name }}</td>
                                <td class="py-3.5 px-3 text-slate-600">
                                    {{ $u->email }}<br>
                                    <span class="text-[10px] text-slate-400">{{ $u->phone ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->isAdmin() ? 'bg-indigo-100 text-indigo-800' : 'bg-teal-100 text-teal-800' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 font-bold text-slate-800">
                                    {{ $u->donations_count }} Transaksi
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $u->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.users.show', $u->id) }}"
                                           class="inline-block px-3 py-1.5 rounded-full border border-slate-200 hover:bg-slate-100 font-bold text-slate-700">
                                            Detail
                                        </a>

                                        @if((int) $u->id !== (int) auth()->id())
                                            <form action="{{ route('admin.users.status', $u->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $u->isActive() ? 'inactive' : 'active' }}">
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-full text-[11px] font-bold cursor-pointer {{ $u->isActive() ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                                    {{ $u->isActive() ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center text-xs text-slate-500">
                                    Belum ada pengguna yang sesuai dengan filter saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
