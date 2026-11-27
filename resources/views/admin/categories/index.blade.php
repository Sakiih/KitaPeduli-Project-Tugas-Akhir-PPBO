@extends('layouts.admin')

@section('title', 'Kelola Kategori | Panel Admin KitaPeduli')
@section('page_title', 'Pengelolaan Kategori Kampanye')
@section('page_subtitle', 'Tambah, ubah, dan hapus kategori kampanye donasi')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
            <h2 class="text-base font-extrabold text-slate-900 mb-4">Tambah Kategori Baru</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori <span class="text-rose-600">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Bantuan Medis"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                </div>
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              placeholder="Penjelasan singkat kategori..."
                              class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">{{ old('description') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full py-2.5 px-4 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-extrabold transition-colors cursor-pointer">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
            <h2 class="text-base font-extrabold text-slate-900 mb-4">Daftar Kategori ({{ $categories->count() }})</h2>
            <div class="space-y-3">
                @forelse($categories as $category)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="flex-1 grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-center">
                            @csrf
                            @method('PUT')
                            <div class="sm:col-span-4">
                                <input type="text" name="name" value="{{ $category->name }}" required
                                       aria-label="Nama kategori {{ $category->name }}"
                                       class="w-full px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold">
                            </div>
                            <div class="sm:col-span-6">
                                <input type="text" name="description" value="{{ $category->description }}"
                                       aria-label="Deskripsi kategori {{ $category->name }}"
                                       placeholder="Deskripsi..."
                                       class="w-full px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs">
                            </div>
                            <div class="sm:col-span-2 flex items-center gap-2">
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-full bg-slate-900 text-white text-[11px] font-bold cursor-pointer">
                                    Simpan
                                </button>
                            </div>
                        </form>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-600">
                                {{ $category->campaigns_count }} Kampanye
                            </span>

                            @if((int) $category->campaigns_count === 0)
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-6 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-500">
                        Belum ada kategori kampanye yang terdaftar.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
