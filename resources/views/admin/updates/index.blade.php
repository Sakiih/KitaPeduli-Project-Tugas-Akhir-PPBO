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
                <div class="kp-card-soft p-4 space-y-2">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700">{{ $update->campaign?->title }}</span>
                            <h3 class="text-sm font-bold text-[#122620]">{{ $update->title }}</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-[#647770]">{{ $update->published_at?->format('d M Y, H:i') }}</span>
                            <button type="button"
                                    data-edit-update
                                    data-action="{{ route('admin.updates.update', $update->id) }}"
                                    data-campaign-title="{{ $update->campaign?->title ?? '-' }}"
                                    data-title="{{ $update->title }}"
                                    data-content="{{ $update->content }}"
                                    data-published-at="{{ $update->published_at?->format('Y-m-d\TH:i') ?? '' }}"
                                    class="kp-btn-secondary px-3 py-1 text-[11px]">
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
                <div class="p-6 rounded-2xl bg-[#f6f8f6] border border-dashed border-[#d7e2da] text-center text-xs text-[#647770]">
                    Belum ada kabar terbaru yang dipublikasikan.
                </div>
            @endforelse

            <div class="pt-2">
                {{ $updates->links() }}
            </div>
        </div>
    </div>

    <div id="kp-edit-update-modal"
         class="hidden fixed inset-0 z-50 bg-[#0d1e1a]/55 backdrop-blur-xs items-center justify-center p-4 overflow-y-auto"
         role="dialog"
         aria-modal="true"
         aria-labelledby="kp-edit-update-modal-title">
        <div class="kp-card w-full max-w-lg p-6 shadow-xl space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 id="kp-edit-update-modal-title" class="text-base font-extrabold text-[#122620]">Edit Kabar Terbaru</h3>
                    <p id="kp-edit-update-campaign" class="text-xs font-semibold text-emerald-700 mt-0.5"></p>
                </div>
                <button type="button"
                        data-close-modal="kp-edit-update-modal"
                        aria-label="Tutup modal edit kabar terbaru"
                        class="kp-btn-secondary px-3 py-1 text-xs">
                    Tutup
                </button>
            </div>

            <form id="kp-edit-update-form" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_update_title" class="block text-xs font-bold text-[#3c524b] mb-1.5">Judul Kabar <span class="text-rose-600">*</span></label>
                    <input type="text" id="edit_update_title" name="title" required
                           class="kp-input text-xs!">
                </div>
                <div>
                    <label for="edit_update_content" class="block text-xs font-bold text-[#3c524b] mb-1.5">Isi Perkembangan <span class="text-rose-600">*</span></label>
                    <textarea id="edit_update_content" name="content" rows="4" required
                              class="kp-input text-xs!"></textarea>
                </div>
                <div>
                    <label for="edit_update_published_at" class="block text-xs font-bold text-[#3c524b] mb-1.5">Waktu Publikasi</label>
                    <input type="datetime-local" id="edit_update_published_at" name="published_at"
                           class="kp-input text-xs!">
                </div>
                <div>
                    <label for="edit_update_image" class="block text-xs font-bold text-[#3c524b] mb-1.5">Ganti Foto Dokumentasi (Opsional)</label>
                    <input type="file" id="edit_update_image" name="image" accept="image/jpeg,image/png,image/webp"
                           class="kp-input text-xs!">
                </div>
                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button"
                            data-close-modal="kp-edit-update-modal"
                            class="kp-btn-secondary px-4 py-2 text-xs">
                        Batal
                    </button>
                    <button type="submit"
                            class="kp-btn-accent px-5 py-2 text-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
