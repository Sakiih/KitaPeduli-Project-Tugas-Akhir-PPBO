@extends('layouts.admin')

@section('title', 'Buat Kampanye Baru | Panel Admin KitaPeduli')
@section('page_title', 'Buat Kampanye Baru')
@section('page_subtitle', 'Tambahkan kampanye donasi beserta informasi pengelola kampanye')

@section('content')
    <div class="max-w-4xl kp-card p-6 sm:p-8">
        <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-bold text-[#3c524b] mb-1.5">Judul Kampanye <span class="text-rose-600">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Bantuan Sarana Air Bersih Desa Pesisir"
                           class="kp-input">
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-[#3c524b] mb-1.5">Kategori <span class="text-rose-600">*</span></label>
                    <select id="category_id" name="category_id" required class="kp-input">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (int) old('category_id') === $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="target_amount" class="block text-xs font-bold text-slate-700 mb-1.5">Target Dana (Rp) <span class="text-rose-600">*</span></label>
                    <input type="number" id="target_amount" name="target_amount" min="100000" step="10000"
                           value="{{ old('target_amount', 25000000) }}" required
                           class="kp-input">
                </div>

                <div>
                    <label for="start_date" class="block text-xs font-bold text-[#3c524b] mb-1.5">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}"
                           class="kp-input">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-bold text-[#3c524b] mb-1.5">Tanggal Berakhir <span class="text-rose-600">*</span></label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', now()->addDays(30)->toDateString()) }}" required
                           class="kp-input">
                </div>

                <div class="sm:col-span-2">
                    <label for="short_description" class="block text-xs font-bold text-[#3c524b] mb-1.5">Ringkasan Singkat</label>
                    <input type="text" id="short_description" name="short_description" value="{{ old('short_description') }}"
                           placeholder="Ringkasan 1 sampai 2 kalimat untuk kartu kampanye"
                           class="kp-input">
                </div>

                <div class="sm:col-span-2">
                    <label for="story" class="block text-xs font-bold text-[#3c524b] mb-1.5">Cerita Lengkap Penggalangan Dana <span class="text-rose-600">*</span></label>
                    <textarea id="story" name="story" rows="6" required
                              placeholder="Tuliskan latar belakang, tujuan penggunaan dana, dan penerima manfaat..."
                              class="kp-input">{{ old('story') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label for="banner_image" class="block text-xs font-bold text-[#3c524b] mb-1.5">Unggah Foto / Banner Kampanye (Opsional)</label>
                    <input type="file" id="banner_image" name="banner_image" accept="image/jpeg,image/png,image/webp"
                           class="kp-input text-xs!">
                </div>
            </div>

            <div class="pt-4 border-t border-[#edf2ee] space-y-4">
                <h3 class="text-sm font-extrabold text-[#122620]">Informasi Pengelola Kampanye</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="organizer_name" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nama Pihak / Organisasi Pengelola <span class="text-rose-600">*</span></label>
                        <input type="text" id="organizer_name" name="organizer_name" value="{{ old('organizer_name') }}" required
                               placeholder="Contoh: Yayasan Solidaritas KitaPeduli"
                               class="kp-input">
                    </div>

                    <div>
                        <label for="organizer_type" class="block text-xs font-bold text-[#3c524b] mb-1.5">Jenis Lembaga Pengelola</label>
                        <input type="text" id="organizer_type" name="organizer_type" value="{{ old('organizer_type', 'Yayasan Sosial Terverifikasi') }}"
                               class="kp-input">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="organizer_description" class="block text-xs font-bold text-[#3c524b] mb-1.5">Deskripsi Pengelola</label>
                        <textarea id="organizer_description" name="organizer_description" rows="2"
                                  class="kp-input">{{ old('organizer_description') }}</textarea>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-2">
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-[#3c524b] cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-[#c6d5cb] text-emerald-600 focus:ring-emerald-500">
                        <span>Tampilkan sebagai Kampanye Unggulan</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-[#3c524b] cursor-pointer">
                        <input type="hidden" name="is_verified_organizer" value="0">
                        <input type="checkbox" name="is_verified_organizer" value="1" {{ old('is_verified_organizer', '1') ? 'checked' : '' }} class="rounded border-[#c6d5cb] text-emerald-600 focus:ring-emerald-500">
                        <span>Tandai Pengelola Terverifikasi</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.campaigns.index') }}"
                   class="kp-btn-secondary px-5 py-2.5 text-xs">
                    Batal
                </a>
                <button type="submit"
                        class="kp-btn-accent px-6 py-2.5 text-xs">
                    Simpan dan Publikasikan Kampanye
                </button>
            </div>
        </form>
    </div>
@endsection
