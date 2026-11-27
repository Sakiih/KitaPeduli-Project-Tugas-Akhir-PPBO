@extends('layouts.admin')

@section('title', 'Kelola Kampanye | Panel Admin KitaPeduli')
@section('page_title', 'Pengelolaan Kampanye')
@section('page_subtitle', 'Buat, ubah, atur status, atau arsipkan kampanye penggalangan dana')

@section('content')
    <div class="space-y-6">
        <div class="kp-card p-5 sm:p-6 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <form action="{{ route('admin.campaigns.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari judul kampanye..."
                       class="kp-input sm:w-64! text-xs!">

                <select name="category_id" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (int) request('category_id') === $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-3.5 py-2.5 rounded-xl bg-[#f7faf8] border border-[#d7e2da] text-xs text-[#122620] focus:outline-none focus:border-emerald-600">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive (Arsip)</option>
                </select>

                <button type="submit" class="kp-btn-primary px-4 py-2.5 text-xs">
                    Terapkan Filter
                </button>
            </form>

            <a href="{{ route('admin.campaigns.create') }}"
               class="kp-btn-accent px-5 py-2.5 text-xs shrink-0">
                Buat Kampanye Baru
            </a>
        </div>

        <div class="kp-card p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-[#e3eae4] text-[#647770] uppercase">
                            <th class="py-3 px-2.5">Kampanye</th>
                            <th class="py-3 px-2.5">Kategori</th>
                            <th class="py-3 px-2.5">Pengelola</th>
                            <th class="py-3 px-2.5">Terkumpul / Target</th>
                            <th class="py-3 px-2.5">Berakhir</th>
                            <th class="py-3 px-2.5">Status</th>
                            <th class="py-3 px-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
<<<<<<< Updated upstream
                    <tbody class="divide-y divide-slate-100">
                        @foreach($campaigns as $camp)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-2 max-w-[180px]">
                                    <a href="{{ route('campaigns.show', $camp->slug) }}" class="font-bold text-slate-900 hover:text-teal-600 block truncate">
=======
                    <tbody class="divide-y divide-[#edf2ee]">
                        @foreach($campaigns as $camp)
                            <tr class="hover:bg-[#f7faf8] transition-colors">
                                <td class="py-3.5 px-2.5 max-w-[190px]">
                                    <a href="{{ route('campaigns.show', $camp->slug) }}" class="font-bold text-[#122620] hover:text-emerald-700 block truncate">
>>>>>>> Stashed changes
                                        {{ $camp->title }}
                                    </a>
                                    <span class="text-[11px] text-[#647770]">{{ $camp->donations_count }} transaksi</span>
                                </td>
                                <td class="py-3.5 px-2.5 font-semibold text-[#3c524b] whitespace-nowrap">
                                    {{ $camp->category?->name ?? '-' }}
                                </td>
                                <td class="py-3.5 px-2.5 text-[#52665f] max-w-[130px] truncate">
                                    {{ $camp->organizer_name }}
                                </td>
                                <td class="py-3.5 px-2.5 whitespace-nowrap">
                                    <span class="font-extrabold text-[#122620] block">
                                        Rp {{ number_format((float) $camp->collected_amount, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[11px] text-[#647770]">
                                        dari Rp {{ number_format((float) $camp->target_amount, 0, ',', '.') }} ({{ $camp->progressPercentage() }}%)
                                    </span>
                                </td>
                                <td class="py-3.5 px-2.5 text-[#52665f] whitespace-nowrap">
                                    {{ $camp->end_date?->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-2.5 whitespace-nowrap">
                                    <form action="{{ route('admin.campaigns.status', $camp->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status"
                                                onchange="this.form.submit()"
                                                class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase border cursor-pointer {{ $camp->status === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($camp->status === 'completed' ? 'bg-sky-50 text-sky-800 border-sky-200' : 'bg-rose-50 text-rose-800 border-rose-200') }}">
                                            <option value="active" {{ $camp->status === 'active' ? 'selected' : '' }}>active</option>
                                            <option value="completed" {{ $camp->status === 'completed' ? 'selected' : '' }}>completed</option>
                                            <option value="inactive" {{ $camp->status === 'inactive' ? 'selected' : '' }}>inactive</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3.5 px-2.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.campaigns.edit', $camp->id) }}"
                                           class="kp-btn-secondary px-3 py-1 text-xs">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.campaigns.destroy', $camp->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Jika kampanye sudah memiliki transaksi, kampanye akan diarsipkan (inactive) agar histori transaksi tetap utuh. Lanjutkan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold cursor-pointer">
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
