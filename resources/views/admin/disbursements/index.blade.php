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
                                $availableBalance = \App\Models\FundDisbursementReport::availableBalanceForCampaign($camp->id);
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
                    <label for="amount" class="block text-xs font-bold text-slate-700 mb-1">Nominal Penyaluran (Rp) <span class="text-rose-600">*</span></label>
                    <label for="amount" class="block text-xs font-bold text-[#3c524b] mb-1.5">Nominal Penyaluran (Rp) <span class="text-rose-600">*</span></label>
                    <input type="number" id="amount" name="amount" min="1000" step="1000" value="{{ old('amount') }}" required
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

            @foreach($reports as $report)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
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
            @endforeach

            <div class="pt-2">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
@endsection
