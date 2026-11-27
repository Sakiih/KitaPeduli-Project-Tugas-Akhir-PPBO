@extends('layouts.admin')

@section('title', 'Laporan Penyaluran Dana | Panel Admin KitaPeduli')
@section('page_title', 'Laporan Penyaluran Dana')
@section('page_subtitle', 'Catat informasi pencairan dan penyaluran dana kampanye untuk transparansi publik')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-4 kp-card p-6">
            <h2 class="text-base font-extrabold text-[#122620] mb-4">Ajukan Pencairan Dana</h2>
            <form action="{{ route('admin.disbursements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="campaign_id" class="block text-xs font-bold text-[#3c524b] mb-1.5">Pilih Kampanye <span class="text-rose-600">*</span></label>
                    <select id="campaign_id" name="campaign_id" required class="kp-input text-xs!">
                        @foreach($campaigns as $camp)
                            @php
                                $availableBalance = max(0.0, (float) ($camp->total_collected_sum ?? 0) - (float) ($camp->total_disbursed_sum ?? 0));
                            @endphp
                            <option value="{{ $camp->id }}" {{ (int) old('campaign_id') === $camp->id ? 'selected' : '' }}>
                                {{ $camp->title }} (Saldo tersedia: Rp {{ number_format($availableBalance, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="title" class="block text-xs font-bold text-[#3c524b] mb-1.5">Judul Penyaluran <span class="text-rose-600">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Pencairan Tahap 1: Pembelian Logistik"
                           class="kp-input text-xs!">
                </div>
                <div>
                    <label for="amount" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nominal Penyaluran (Rp) <span class="text-rose-600">*</span></label>
                    <input type="number" id="amount" name="amount" min="1000" step="1" value="{{ old('amount') }}" required
                           placeholder="500000"
                           class="kp-input text-xs!">
                </div>
                <div>
                    <label for="recipient_name" class="block text-xs font-bold text-[#3c524b] mb-1.5">Penerima / Pihak Tujuan <span class="text-rose-600">*</span></label>
                    <input type="text" id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}" required
                           placeholder="Contoh: Posko Relawan / Rumah Sakit"
                           class="kp-input text-xs!">
                </div>
                <div>
                    <label for="purpose" class="block text-xs font-bold text-[#3c524b] mb-1.5">Keperluan Penyaluran <span class="text-rose-600">*</span></label>
                    <input type="text" id="purpose" name="purpose" value="{{ old('purpose') }}" required
                           placeholder="Contoh: Pengadaan 50 paket pangan"
                           class="kp-input text-xs!">
                </div>
                <div>
                    <label for="disbursed_at" class="block text-xs font-bold text-[#3c524b] mb-1.5">Tanggal Penyaluran <span class="text-rose-600">*</span></label>
                    <input type="date" id="disbursed_at" name="disbursed_at" value="{{ old('disbursed_at', now()->toDateString()) }}" required
                           class="kp-input text-xs!">
                </div>
                <div>
                    <label for="description" class="block text-xs font-bold text-[#3c524b] mb-1.5">Deskripsi Lengkap <span class="text-rose-600">*</span></label>
                    <textarea id="description" name="description" rows="3" required
                              class="kp-input text-xs!">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="document_image" class="block text-xs font-bold text-[#3c524b] mb-1.5">Dokumentasi (Opsional)</label>
                    <input type="file" id="document_image" name="document_image" accept="image/jpeg,image/png,image/webp"
                           class="kp-input text-xs!">
                </div>
                <button type="submit"
                        class="kp-btn-accent w-full py-2.5 px-4 text-xs">
                    Ajukan Pencairan
                </button>
            </form>
        </div>

        <div class="lg:col-span-8 kp-card p-6 space-y-4">
            <h2 class="text-base font-extrabold text-[#122620]">Catatan Penyaluran Dana ({{ $reports->total() }})</h2>

            @forelse($reports as $report)
                <div class="kp-card-soft p-4 space-y-2">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700">{{ $report->campaign?->title }}</span>
                            <h3 class="text-sm font-bold text-[#122620]">{{ $report->title }}</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full bg-gradient-to-r from-emerald-600 to-sky-600 text-white text-xs font-extrabold">
                                Rp {{ number_format((float) $report->amount, 0, ',', '.') }}
                            </span>
                            <button type="button"
                                    data-edit-disbursement
                                    data-action="{{ route('admin.disbursements.update', $report->id) }}"
                                    data-campaign-title="{{ $report->campaign?->title ?? '-' }}"
                                    data-title="{{ $report->title }}"
                                    data-amount="{{ (int) round((float) $report->amount) }}"
                                    data-recipient-name="{{ $report->recipient_name }}"
                                    data-purpose="{{ $report->purpose }}"
                                    data-disbursed-at="{{ $report->disbursed_at?->toDateString() ?? '' }}"
                                    data-description="{{ $report->description }}"
                                    class="kp-btn-secondary px-3 py-1 text-[11px]">
                                Edit
                            </button>
                            <form action="{{ route('admin.disbursements.destroy', $report->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus catatan penyaluran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="text-xs text-[#647770]">
                        Tanggal: <strong class="text-[#122620]">{{ $report->disbursed_at?->format('d M Y') }}</strong> &bull;
                        Penerima: <strong class="text-[#122620]">{{ $report->recipient_name }}</strong> &bull;
                        Tujuan: <strong class="text-[#122620]">{{ $report->purpose }}</strong>
                    </p>
                    <p class="text-xs text-[#52665f] leading-relaxed">{{ $report->description }}</p>
                </div>
            @empty
                <div class="p-6 rounded-2xl bg-[#f6f8f6] border border-dashed border-[#d7e2da] text-center text-xs text-[#647770]">
                    Belum ada catatan laporan penyaluran dana.
                </div>
            @endforelse

            <div class="pt-2">
                {{ $reports->links() }}
            </div>
        </div>
    </div>

    <div id="kp-edit-disbursement-modal"
         class="hidden fixed inset-0 z-50 bg-[#0d1e1a]/55 backdrop-blur-xs items-center justify-center p-4 overflow-y-auto"
         role="dialog"
         aria-modal="true"
         aria-labelledby="kp-edit-disbursement-modal-title">
        <div class="kp-card w-full max-w-xl p-6 shadow-xl space-y-4 my-8">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 id="kp-edit-disbursement-modal-title" class="text-base font-extrabold text-[#122620]">Edit Laporan Penyaluran Dana</h3>
                    <p id="kp-edit-disbursement-campaign" class="text-xs font-semibold text-emerald-700 mt-0.5"></p>
                </div>
                <button type="button"
                        data-close-modal="kp-edit-disbursement-modal"
                        aria-label="Tutup modal edit laporan penyaluran dana"
                        class="kp-btn-secondary px-3 py-1 text-xs">
                    Tutup
                </button>
            </div>

            <form id="kp-edit-disbursement-form" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_disbursement_title" class="block text-xs font-bold text-[#3c524b] mb-1.5">Judul Penyaluran <span class="text-rose-600">*</span></label>
                    <input type="text" id="edit_disbursement_title" name="title" required
                           class="kp-input text-xs!">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_disbursement_amount" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nominal Penyaluran (Rp) <span class="text-rose-600">*</span></label>
                        <input type="number" id="edit_disbursement_amount" name="amount" min="1000" step="1" required
                               class="kp-input text-xs!">
                    </div>
                    <div>
                        <label for="edit_disbursement_date" class="block text-xs font-bold text-[#3c524b] mb-1.5">Tanggal Penyaluran <span class="text-rose-600">*</span></label>
                        <input type="date" id="edit_disbursement_date" name="disbursed_at" required
                               class="kp-input text-xs!">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_disbursement_recipient" class="block text-xs font-bold text-[#3c524b] mb-1.5">Penerima / Pihak Tujuan <span class="text-rose-600">*</span></label>
                        <input type="text" id="edit_disbursement_recipient" name="recipient_name" required
                               class="kp-input text-xs!">
                    </div>
                    <div>
                        <label for="edit_disbursement_purpose" class="block text-xs font-bold text-[#3c524b] mb-1.5">Keperluan Penyaluran <span class="text-rose-600">*</span></label>
                        <input type="text" id="edit_disbursement_purpose" name="purpose" required
                               class="kp-input text-xs!">
                    </div>
                </div>
                <div>
                    <label for="edit_disbursement_description" class="block text-xs font-bold text-[#3c524b] mb-1.5">Deskripsi Lengkap <span class="text-rose-600">*</span></label>
                    <textarea id="edit_disbursement_description" name="description" rows="3" required
                              class="kp-input text-xs!"></textarea>
                </div>
                <div>
                    <label for="edit_disbursement_document" class="block text-xs font-bold text-[#3c524b] mb-1.5">Ganti Dokumentasi (Opsional)</label>
                    <input type="file" id="edit_disbursement_document" name="document_image" accept="image/jpeg,image/png,image/webp"
                           class="kp-input text-xs!">
                </div>
                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button"
                            data-close-modal="kp-edit-disbursement-modal"
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
