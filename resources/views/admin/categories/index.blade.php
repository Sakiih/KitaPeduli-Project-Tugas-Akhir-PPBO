@extends('layouts.admin')

@section('title', 'Kelola Kategori | Panel Admin KitaPeduli')
@section('page_title', 'Pengelolaan Kategori Kampanye')
@section('page_subtitle', 'Tambah, ubah, dan hapus kategori kampanye donasi')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-4 kp-card p-6">
            <h2 class="text-base font-extrabold text-[#122620] mb-4">Tambah Kategori Baru</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nama Kategori <span class="text-rose-600">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Bantuan Medis"
                           class="kp-input">
                </div>
                <div>
                    <label for="description" class="block text-xs font-bold text-[#3c524b] mb-1.5">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                              placeholder="Penjelasan singkat kategori..."
                              class="kp-input">{{ old('description') }}</textarea>
                </div>
                <button type="submit"
                        class="kp-btn-accent w-full py-2.5 px-4 text-xs">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <div class="lg:col-span-8 kp-card p-6">
            <h2 class="text-base font-extrabold text-[#122620] mb-4">Daftar Kategori ({{ $categories->count() }})</h2>
            <div class="space-y-3">
                @foreach($categories as $category)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="kp-card-soft p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="flex-1 grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-center">
                            @csrf
                            @method('PUT')
                            <div class="sm:col-span-4">
                                <input type="text" name="name" value="{{ $category->name }}" required
                                       aria-label="Nama kategori {{ $category->name }}"
                                       class="w-full px-3 py-2 rounded-xl bg-white border border-[#d7e2da] text-xs font-bold text-[#122620] focus:outline-none focus:border-emerald-600">
                            </div>
                            <div class="sm:col-span-6">
                                <input type="text" name="description" value="{{ $category->description }}"
                                       aria-label="Deskripsi kategori {{ $category->name }}"
                                       placeholder="Deskripsi..."
                                       class="w-full px-3 py-2 rounded-xl bg-white border border-[#d7e2da] text-xs text-[#3c524b] focus:outline-none focus:border-emerald-600">
                            </div>
                            <div class="sm:col-span-2 flex items-center gap-2">
                                <button type="submit"
                                        class="kp-btn-primary px-3.5 py-2 text-[11px]">
                                    Simpan
                                </button>
                            </div>
                        </form>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2.5 py-1 rounded-full bg-white border border-[#dce6df] text-[11px] font-bold text-[#52665f]">
                                {{ $category->campaigns_count }} Kampanye
                            </span>

                            @if($category->canBeDeleted())
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
                @endforeach
            </div>
        </div>
    </div>
@endsection
