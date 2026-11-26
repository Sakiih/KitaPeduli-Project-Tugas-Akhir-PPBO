@extends('layouts.admin')

@section('title', 'Edit Kampanye | Panel Admin KitaPeduli')
@section('page_title', 'Edit Kampanye')
@section('page_subtitle', 'Perbarui informasi kampanye, status, dan pengelola')

@section('content')
    <div class="max-w-4xl bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-2xs">
        <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-bold text-slate-700 mb-1.5">Judul Kampanye <span class="text-rose-600">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $campaign->title) }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 mb-1.5">Kategori <span class="text-rose-600">*</span></label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (int) old('category_id', $campaign->category_id) === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="target_amount" class="block text-xs font-bold text-slate-700 mb-1.5">Target Dana (Rp) <span class="text-rose-600">*</span></label>
                    <input type="number" id="target_amount" name="target_amount" min="100000" step="1000"
                           value="{{ old('target_amount', (int) round((float) $campaign->target_amount)) }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Berakhir <span class="text-rose-600">*</span></label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $campaign->end_date?->toDateString()) }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Kampanye</label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                        <option value="active" {{ old('status', $campaign->status) === 'active' ? 'selected' : '' }}>active</option>
                        <option value="completed" {{ old('status', $campaign->status) === 'completed' ? 'selected' : '' }}>completed</option>
                        <option value="inactive" {{ old('status', $campaign->status) === 'inactive' ? 'selected' : '' }}>inactive</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="short_description" class="block text-xs font-bold text-slate-700 mb-1.5">Ringkasan Singkat</label>
                    <input type="text" id="short_description" name="short_description" value="{{ old('short_description', $campaign->short_description) }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                </div>

                <div class="sm:col-span-2">
                    <label for="story" class="block text-xs font-bold text-slate-700 mb-1.5">Cerita Lengkap Penggalangan Dana <span class="text-rose-600">*</span></label>
                    <textarea id="story" name="story" rows="6" required
                              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-sm">{{ old('story', $campaign->story) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label for="banner_image" class="block text-xs font-bold text-slate-700 mb-1.5">Ganti Foto / Banner Kampanye (Opsional)</label>
                    <input type="file" id="banner_image" name="banner_image" accept="image/jpeg,image/png,image/webp"
                           class="w-full px-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900">Informasi Pengelola Kampanye</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="organizer_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Pihak / Organisasi Pengelola <span class="text-rose-600">*</span></label>
                        <input type="text" id="organizer_name" name="organizer_name" value="{{ old('organizer_name', $campaign->organizer_name) }}" required
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                    </div>

                    <div>
                        <label for="organizer_type" class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Lembaga Pengelola</label>
                        <input type="text" id="organizer_type" name="organizer_type" value="{{ old('organizer_type', $campaign->organizer_type) }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="organizer_description" class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Pengelola</label>
                        <textarea id="organizer_description" name="organizer_description" rows="2"
                                  class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm">{{ old('organizer_description', $campaign->organizer_description) }}</textarea>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-2">
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $campaign->is_featured) ? 'checked' : '' }}>
                        <span>Kampanye Unggulan</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                        <input type="hidden" name="is_verified_organizer" value="0">
                        <input type="checkbox" name="is_verified_organizer" value="1" {{ old('is_verified_organizer', $campaign->is_verified_organizer) ? 'checked' : '' }}>
                        <span>Pengelola Terverifikasi</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.campaigns.index') }}"
                   class="px-5 py-2.5 rounded-full border border-slate-200 text-slate-700 text-xs font-bold">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-extrabold transition-colors cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
