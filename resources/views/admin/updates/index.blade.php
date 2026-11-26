@extends('layouts.admin')

@section('title', 'Kabar Terbaru Kampanye | Panel Admin KitaPeduli')
@section('page_title', 'Kabar Terbaru Kampanye')
@section('page_subtitle', 'Publikasikan perkembangan pelaksanaan kampanye untuk transparansi publik')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs">
            <h2 class="text-base font-extrabold text-slate-900 mb-4">Tulis Kabar Terbaru</h2>
            <form action="{{ route('admin.updates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="campaign_id" class="block text-xs font-bold text-slate-700 mb-1">Pilih Kampanye <span class="text-rose-600">*</span></label>
                    <select id="campaign_id" name="campaign_id" required
                            class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        @foreach($campaigns as $camp)
                            <option value="{{ $camp->id }}" {{ (int) old('campaign_id') === $camp->id ? 'selected' : '' }}>
                                {{ $camp->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="title" class="block text-xs font-bold text-slate-700 mb-1">Judul Kabar <span class="text-rose-600">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Penyaluran Bantuan Tahap 2"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                </div>
                <div>
                    <label for="content" class="block text-xs font-bold text-slate-700 mb-1">Isi Perkembangan <span class="text-rose-600">*</span></label>
                    <textarea id="content" name="content" rows="4" required
                              placeholder="Tuliskan laporan perkembangan..."
                              class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">{{ old('content') }}</textarea>
                </div>
                <div>
                    <label for="image" class="block text-xs font-bold text-slate-700 mb-1">Foto Dokumentasi (Opsional)</label>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                </div>
                <button type="submit"
                        class="w-full py-2.5 px-4 rounded-full bg-slate-900 hover:bg-teal-600 text-white text-xs font-extrabold transition-colors cursor-pointer">
                    Publikasikan Kabar Terbaru
                </button>
            </form>
        </div>

        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs space-y-4">
            <h2 class="text-base font-extrabold text-slate-900">Daftar Kabar Terbaru ({{ $updates->total() }})</h2>

            @foreach($updates as $update)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="text-[11px] font-bold text-teal-700">{{ $update->campaign?->title }}</span>
                            <h3 class="text-sm font-bold text-slate-900">{{ $update->title }}</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-slate-400">{{ $update->published_at?->format('d M Y, H:i') }}</span>
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
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $update->content }}</p>
                </div>
            @endforeach

            <div class="pt-2">
                {{ $updates->links() }}
            </div>
        </div>
    </div>
@endsection
