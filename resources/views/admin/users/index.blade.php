@extends('layouts.admin')

@section('title', 'Kelola Pengguna | Panel Admin KitaPeduli')
@section('page_title', 'Manajemen Pengguna Terdaftar')
@section('page_subtitle', 'Daftar akun Admin dan Donatur Terdaftar')

@section('content')
    <div class="space-y-6">
        <div class="kp-card p-5">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau email..."
                       class="kp-input sm:w-64! text-xs!">

                <select name="role" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                    <option value="">Semua Role</option>
                    <option value="donor" {{ request('role') === 'donor' ? 'selected' : '' }}>Donatur Terdaftar (donor)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator (admin)</option>
                </select>

                <select name="status" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                    <option value="">Semua Status Akun</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="kp-btn-primary px-4 py-2.5 text-xs">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <div class="kp-card p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#e3eae4] text-[#647770] uppercase">
                            <th class="py-3 px-3">ID</th>
                            <th class="py-3 px-3">Nama</th>
                            <th class="py-3 px-3">Email dan Telepon</th>
                            <th class="py-3 px-3">Role</th>
                            <th class="py-3 px-3">Jumlah Donasi</th>
                            <th class="py-3 px-3">Status Akun</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
<<<<<<< Updated upstream
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $u)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 px-3 font-mono font-bold text-slate-700">#{{ $u->id }}</td>
                                <td class="py-3.5 px-3 font-bold text-slate-900">{{ $u->name }}</td>
                                <td class="py-3.5 px-3 text-slate-600">
=======
                    <tbody class="divide-y divide-[#edf2ee]">
                        @foreach($users as $u)
                            <tr class="hover:bg-[#f7faf8] transition-colors">
                                <td class="py-3.5 px-3 font-mono font-bold text-[#3c524b]">#{{ $u->id }}</td>
                                <td class="py-3.5 px-3 font-bold text-[#122620]">{{ $u->name }}</td>
                                <td class="py-3.5 px-3 text-[#52665f]">
>>>>>>> Stashed changes
                                    {{ $u->email }}<br>
                                    <span class="text-[10px] text-[#647770]">{{ $u->phone ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->isAdmin() ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 font-bold text-[#122620]">
                                    {{ $u->donations_count }} Transaksi
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $u->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $u->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3 text-right space-x-2">
                                    <a href="{{ route('admin.users.show', $u->id) }}"
<<<<<<< Updated upstream
                                       class="inline-block px-3 py-1.5 rounded-full border border-slate-200 hover:bg-slate-100 font-bold text-slate-700">
=======
                                       class="kp-btn-secondary px-3 py-1.5 text-xs">
>>>>>>> Stashed changes
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
