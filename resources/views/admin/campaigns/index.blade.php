@extends('layouts.admin')

@section('title', 'Kelola Kampanye | Panel Admin KitaPeduli')
@section('page_title', 'Pengelolaan Kampanye')
@section('page_subtitle', 'Buat, ubah, atur status, atau arsipkan kampanye penggalangan dana')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-2xs flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <form action="{{ route('admin.campaigns.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari judul kampanye..."
                       class="px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs w-full sm:w-64">

                <select name="category_id" class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (int) request('category_id') === $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (Arsip)</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold cursor-pointer">
                    Terapkan Filter
                </button>
            </form>

            <a href="{{ route('admin.campaigns.create') }}"
               class="px-5 py-2.5 rounded-full bg-teal-600 hover:bg-teal-500 text-white text-xs font-extrabold text-center transition-colors shrink-0">
                Buat Kampanye Baru
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 uppercase">
                            <th class="py-3 px-2">Kampanye</th>
                            <th class="py-3 px-2">Kategori</th>
                            <th class="py-3 px-2">Pengelola</th>
                            <th class="py-3 px-2">Terkumpul / Target</th>
                            <th class="py-3 px-2">Berakhir</th>
                            <th class="py-3 px-2">Status</th>
                            <th class="py-3 px-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($campaigns as $camp)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-2 max-w-[180px]">
                                    <a href="{{ route('campaigns.show', $camp->slug) }}" class="font-bold text-slate-900 hover:text-teal-600 block truncate">
                                        {{ $camp->title }}
                                    </a>
                                    <span class="text-[11px] text-slate-400">{{ $camp->donations_count }} transaksi</span>
                                </td>
                                <td class="py-3 px-2 font-semibold text-slate-700 whitespace-nowrap">
                                    {{ $camp->category?->name ?? '-' }}
                                </td>
                                <td class="py-3 px-2 text-slate-600 max-w-[120px] truncate">
                                    {{ $camp->organizer_name }}
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    <span class="font-extrabold text-slate-900 block">
                                        Rp {{ number_format((float) $camp->collected_amount, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-slate-400">
                                        dari Rp {{ number_format((float) $camp->target_amount, 0, ',', '.') }} ({{ $camp->progressPercentage() }}%)
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-slate-600 whitespace-nowrap">
                                    {{ $camp->end_date?->format('d M Y') }}
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    <form action="{{ route('admin.campaigns.status', $camp->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status"
                                                onchange="this.form.submit()"
                                                class="px-2 py-1 rounded-full text-[11px] font-bold uppercase border {{ $camp->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($camp->status === 'completed' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-rose-50 text-rose-800 border-rose-200') }}">
                                            <option value="active" {{ $camp->status === 'active' ? 'selected' : '' }}>active</option>
                                            <option value="completed" {{ $camp->status === 'completed' ? 'selected' : '' }}>completed</option>
                                            <option value="inactive" {{ $camp->status === 'inactive' ? 'selected' : '' }}>inactive</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3 px-2 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.campaigns.edit', $camp->id) }}"
                                           class="inline-block px-2.5 py-1 rounded-full border border-slate-200 hover:bg-slate-100 font-bold text-slate-700">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.campaigns.destroy', $camp->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Jika kampanye sudah memiliki transaksi, kampanye akan diarsipkan (inactive) agar histori transaksi tetap utuh. Lanjutkan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold cursor-pointer">
                                                {{ $camp->donations_count > 0 ? 'Arsipkan' : 'Hapus' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
@endsection
