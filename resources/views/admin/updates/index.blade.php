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

            @foreach($updates as $update)
<<<<<<< Updated upstream
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
=======
                <div class="kp-card-soft p-4 space-y-2">
>>>>>>> Stashed changes
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700">{{ $update->campaign?->title }}</span>
                            <h3 class="text-sm font-bold text-[#122620]">{{ $update->title }}</h3>
                        </div>
                        <div class="flex items-center gap-2">
<<<<<<< Updated upstream
                            <span class="text-[11px] text-slate-400">{{ $update->published_at?->format('d M Y, H:i') }}</span>
=======
                            <span class="text-[11px] text-[#647770]">{{ $update->published_at?->format('d M Y, H:i') }}</span>
>>>>>>> Stashed changes
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
            @endforeach

            <div class="pt-2">
                {{ $updates->links() }}
            </div>
        </div>
    </div>
@endsection
