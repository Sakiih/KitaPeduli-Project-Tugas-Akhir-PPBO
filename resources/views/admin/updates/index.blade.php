@extends('layouts.admin')

@section('title', 'Kabar Terbaru Kampanye | Panel Admin KitaPeduli')
@section('page_title', 'Kabar Terbaru Kampanye')
@section('page_subtitle', 'Publikasikan perkembangan pelaksanaan kampanye untuk transparansi publik')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-4 kp-card p-6">
            <h2 class="text-base font-extrabold text-[#122620] mb-4">Tulis Kabar Terbaru</h2>
            <form action="{{ route('admin.updates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="campaign_id" class="block text-xs font-bold text-[#3c524b] mb-1.5">Pilih Kampanye <span class="text-rose-600">*</span></label>
                    <select id="campaign_id" name="campaign_id" required class="kp-input text-xs!">
                        @foreach($campaigns as $camp)
                            <option value="{{ $camp->id }}" {{ (int) old('campaign_id') === $camp->id ? 'selected' : '' }}>
                                {{ $camp->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="title" class="block text-xs font-bold text-[#3c524b] mb-1.5">Judul Kabar <span class="text-rose-600">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Penyaluran Bantuan Tahap 2"
                           class="kp-input text-xs!">
                </div>
                <div>
                    <label for="content" class="block text-xs font-bold text-[#3c524b] mb-1.5">Isi Perkembangan <span class="text-rose-600">*</span></label>
                    <textarea id="content" name="content" rows="4" required
                              placeholder="Tuliskan laporan perkembangan..."
                              class="kp-input text-xs!">{{ old('content') }}</textarea>
                </div>
                <div>
                    <label for="image" class="block text-xs font-bold text-[#3c524b] mb-1.5">Foto Dokumentasi (Opsional)</label>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
                           class="kp-input text-xs!">
                </div>
                <button type="submit"
                        class="kp-btn-accent w-full py-2.5 px-4 text-xs">
                    Publikasikan Kabar Terbaru
                </button>
            </form>
        </div>

        <div class="lg:col-span-8 kp-card p-6 space-y-4">
            <h2 class="text-base font-extrabold text-[#122620]">Daftar Kabar Terbaru ({{ $updates->total() }})</h2>

            @forelse($updates as $update)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                <div class="kp-card-soft p-4 space-y-2">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700">{{ $update->campaign?->title }}</span>
                            <h3 class="text-sm font-bold text-[#122620]">{{ $update->title }}</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-slate-400">{{ $update->published_at?->format('d M Y, H:i') }}</span>
                            <span class="text-[11px] text-[#647770]">{{ $update->published_at?->format('d M Y, H:i') }}</span>
                            <button type="button"
                                    data-edit-update
                                    data-action="{{ route('admin.updates.update', $update->id) }}"
                                    data-campaign-title="{{ $update->campaign?->title ?? '-' }}"
                                    data-title="{{ $update->title }}"
                                    data-content="{{ $update->content }}"
                                    data-published-at="{{ $update->published_at?->format('Y-m-d\TH:i') ?? '' }}"
                                    class="px-3 py-1 rounded-full border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-[11px] font-bold cursor-pointer">
                                Edit
                            </button>
                            <form action="{{ route('admin.updates.destroy', $update->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus kabar terbaru ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="text-xs text-[#52665f] leading-relaxed">{{ $update->content }}</p>
                </div>
            @empty
                <div class="p-6 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-center text-xs text-slate-500">
                    Belum ada kabar terbaru yang dipublikasikan.
                </div>
            @endforelse

            <div class="pt-2">
                {{ $updates->links() }}
            </div>
        </div>
    </div>

    <div id="kp-edit-update-modal"
         class="hidden fixed inset-0 z-50 bg-slate-900/50 items-center justify-center p-4 overflow-y-auto"
         role="dialog"
         aria-modal="true"
         aria-labelledby="kp-edit-update-modal-title">
        <div class="bg-white rounded-3xl border border-slate-200 w-full max-w-lg p-6 shadow-xl space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 id="kp-edit-update-modal-title" class="text-base font-extrabold text-slate-900">Edit Kabar Terbaru</h3>
                    <p id="kp-edit-update-campaign" class="text-xs font-semibold text-teal-700 mt-0.5"></p>
                </div>
                <button type="button"
                        data-close-modal="kp-edit-update-modal"
                        aria-label="Tutup modal edit kabar terbaru"
                        class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold cursor-pointer">
                    Tutup
                </button>
            </div>

            <form id="kp-edit-update-form" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_update_title" class="block text-xs font-bold text-slate-700 mb-1">Judul Kabar <span class="text-rose-600">*</span></label>
                    <input type="text" id="edit_update_title" name="title" required
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                </div>
                <div>
                    <label for="edit_update_content" class="block text-xs font-bold text-slate-700 mb-1">Isi Perkembangan <span class="text-rose-600">*</span></label>
                    <textarea id="edit_update_content" name="content" rows="4" required
                              class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs"></textarea>
                </div>
                <div>
                    <label for="edit_update_published_at" class="block text-xs font-bold text-slate-700 mb-1">Waktu Publikasi</label>
                    <input type="datetime-local" id="edit_update_published_at" name="published_at"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                </div>
                <div>
                    <label for="edit_update_image" class="block text-xs font-bold text-slate-700 mb-1">Ganti Foto Dokumentasi (Opsional)</label>
                    <input type="file" id="edit_update_image" name="image" accept="image/jpeg,image/png,image/webp"
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                </div>
                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button"
                            data-close-modal="kp-edit-update-modal"
                            class="px-4 py-2 rounded-full border border-slate-200 text-slate-700 text-xs font-bold cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-extrabold transition-colors cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
