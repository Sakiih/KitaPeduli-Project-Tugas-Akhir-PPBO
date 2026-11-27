@extends('layouts.app')

@section('title', $campaign->title . ' | KitaPeduli')

@section('content')
    @php
        $progress = $campaign->progressPercentage();
        $remainingDays = $campaign->remainingDays();
        $isAccepting = $campaign->isAcceptingDonations();
    @endphp

    <div class="kp-container py-8">
        <nav class="text-xs text-[#647770] mb-5 flex flex-wrap items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-emerald-700 font-medium">Beranda</a>
            <span>/</span>
            <a href="{{ route('campaigns.index') }}" class="hover:text-emerald-700 font-medium">Kampanye</a>
            @if($campaign->category)
                <span>/</span>
                <a href="{{ route('campaigns.index', ['category' => $campaign->category->slug]) }}" class="hover:text-emerald-700 font-medium">{{ $campaign->category->name }}</a>
            @endif
            <span>/</span>
            <span class="text-[#122620] font-bold truncate max-w-xs">{{ $campaign->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <div class="lg:col-span-8 space-y-7">

                <div class="kp-card overflow-hidden">
                    <div class="relative bg-[#eef3ef] aspect-[16/9]">
                        <img src="{{ $campaign->bannerUrl() }}"
                             alt="{{ $campaign->title }}"
                             class="w-full h-full object-cover">
                        @if($campaign->category)
                            <span class="absolute top-4 left-4 bg-white/95 backdrop-blur-xs text-[#122620] text-xs font-bold px-3.5 py-1.5 rounded-full shadow-xs">
                                {{ $campaign->category->name }}
                            </span>
                        @endif
<<<<<<< Updated upstream
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold uppercase {{ $isAccepting ? 'bg-emerald-500 text-white' : 'bg-rose-600 text-white' }}">
=======
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold uppercase {{ $isAccepting ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
>>>>>>> Stashed changes
                            {{ $campaign->status }}
                        </span>
                    </div>

                    <div class="p-6 sm:p-8">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#122620] tracking-tight leading-tight mb-3">
                            {{ $campaign->title }}
                        </h1>
                        @if($campaign->short_description)
                            <p class="text-sm text-[#52665f] leading-relaxed">
                                {{ $campaign->short_description }}
                            </p>
                        @endif

                        <div class="mt-6 pt-5 border-t border-[#edf2ee] flex flex-wrap gap-2 text-xs font-semibold">
                            <a href="#cerita-kampanye" class="px-3.5 py-1.5 rounded-full bg-[#122620] text-white">Cerita</a>
                            <a href="#kabar-terbaru" class="px-3.5 py-1.5 rounded-full bg-[#f2f6f3] hover:bg-emerald-50 text-[#3c524b]">Kabar Terbaru ({{ $campaign->updates->count() }})</a>
                            <a href="#laporan-penyaluran" class="px-3.5 py-1.5 rounded-full bg-[#f2f6f3] hover:bg-emerald-50 text-[#3c524b]">Pencairan Dana ({{ $campaign->disbursementReports->count() }})</a>
                            <a href="#informasi-pengelola" class="px-3.5 py-1.5 rounded-full bg-[#f2f6f3] hover:bg-emerald-50 text-[#3c524b]">Pengelola</a>
                            <a href="#ringkasan-donasi" class="px-3.5 py-1.5 rounded-full bg-[#f2f6f3] hover:bg-emerald-50 text-[#3c524b]">Ringkasan</a>
                            <a href="#doa-orang-baik" class="px-3.5 py-1.5 rounded-full bg-emerald-50 border border-emerald-200/70 hover:bg-emerald-100 text-emerald-800">Doa Orang Baik ({{ $prayers->total() }})</a>
                            <a href="#pedoman-kebaikan" class="px-3.5 py-1.5 rounded-full bg-[#f2f6f3] hover:bg-emerald-50 text-[#3c524b]">Pedoman</a>
                            <a href="#faq-kampanye" class="px-3.5 py-1.5 rounded-full bg-[#f2f6f3] hover:bg-emerald-50 text-[#3c524b]">FAQ</a>
                        </div>
                    </div>
                </div>

                <section id="cerita-kampanye" class="kp-card p-6 sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-4 pb-3 border-b border-[#edf2ee]">
                        <h2 class="text-lg sm:text-xl font-extrabold text-[#122620]">Cerita Penggalangan Dana</h2>
                        <span class="px-3 py-1 rounded-full bg-sky-50 text-sky-800 text-xs font-semibold">
                            Berakhir: {{ $campaign->end_date?->format('d M Y') }}
                        </span>
                    </div>
                    <div class="prose prose-slate max-w-none text-sm text-[#3c524b] leading-relaxed whitespace-pre-line">{{ $campaign->story }}</div>
                </section>

                <section id="kabar-terbaru" class="kp-card p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-[#122620]">Kabar Terbaru</h2>
                            <p class="text-xs text-[#647770]">Perkembangan pelaksanaan kampanye yang dipublikasikan oleh Admin</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-[#f2f6f3] text-[#122620] text-xs font-bold">
                            {{ $campaign->updates->count() }} Kabar
                        </span>
                    </div>

                    @if($campaign->updates->count() > 0)
                        <div class="space-y-4">
                            @foreach($campaign->updates as $update)
                                <div class="kp-card-soft p-5 space-y-2">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h3 class="font-bold text-[#122620] text-sm sm:text-base">{{ $update->title }}</h3>
                                        <span class="text-xs text-[#647770] font-medium">
                                            {{ $update->published_at?->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-[#3c524b] leading-relaxed whitespace-pre-line">{{ $update->content }}</p>
                                    @if($update->imageUrl())
                                        <div class="pt-2">
                                            <img src="{{ $update->imageUrl() }}" alt="{{ $update->title }}" class="rounded-xl max-h-64 object-cover border border-[#dce6df]">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 rounded-2xl bg-[#f6f8f6] border border-dashed border-[#d7e2da] text-center text-xs text-[#647770]">
                            Belum ada kabar terbaru yang dipublikasikan untuk kampanye ini.
                        </div>
                    @endif
                </section>

                <section id="laporan-penyaluran" class="kp-card p-6 sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-[#122620]">Pencairan Dana &amp; Penyaluran</h2>
                            <p class="text-xs text-[#647770]">Catatan transparansi penyaluran dana kampanye</p>
                        </div>
                        <div class="text-right px-4 py-2 rounded-2xl bg-emerald-50 border border-emerald-200/70">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block">Total Disalurkan</span>
                            <span class="text-sm font-extrabold text-emerald-900">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($campaign->disbursementReports->count() > 0)
                        <div class="space-y-4">
                            @foreach($campaign->disbursementReports as $report)
                                <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-50/60 to-sky-50/50 border border-emerald-200/70 space-y-2">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div>
                                            <span class="text-[11px] font-bold text-teal-700 uppercase tracking-wider">
                                                Tanggal Penyaluran: {{ $report->disbursed_at?->format('d M Y') }}
                                            </span>
                                            <h3 class="font-bold text-[#122620] text-sm sm:text-base">{{ $report->title }}</h3>
                                        </div>
                                        <span class="px-3.5 py-1.5 rounded-full bg-gradient-to-r from-emerald-600 to-sky-600 text-white text-xs font-extrabold">
                                            Rp {{ number_format((float) $report->amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 text-xs text-[#3c524b]">
                                        <p><strong class="text-[#122620]">Penerima / Tujuan:</strong> {{ $report->recipient_name }}</p>
                                        <p><strong class="text-[#122620]">Keperluan:</strong> {{ $report->purpose }}</p>
                                    </div>
                                    <p class="text-xs text-[#52665f] leading-relaxed pt-1">{{ $report->description }}</p>
                                    @if($report->documentUrl())
                                        <div class="pt-2">
                                            <img src="{{ $report->documentUrl() }}" alt="Dokumentasi {{ $report->title }}" class="rounded-xl max-h-56 object-cover border border-emerald-200">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 rounded-2xl bg-[#f6f8f6] border border-dashed border-[#d7e2da] text-center text-xs text-[#647770]">
                            Belum ada catatan pencairan atau penyaluran dana untuk kampanye ini.
                        </div>
                    @endif
                </section>

                <section id="informasi-pengelola" class="kp-card p-6 sm:p-8">
                    <h2 class="text-lg sm:text-xl font-extrabold text-[#122620] mb-4">Informasi Pengelola Kampanye</h2>
                    <div class="kp-card-soft flex items-start gap-4 p-5">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 to-sky-600 text-white flex items-center justify-center font-extrabold text-lg shrink-0">
                            {{ strtoupper(substr($campaign->organizer_name, 0, 1)) }}
                        </div>
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-bold text-[#122620] text-base">{{ $campaign->organizer_name }}</h3>
                                @if($campaign->is_verified_organizer)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                                        Terverifikasi Admin KitaPeduli
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs font-semibold text-[#52665f]">{{ $campaign->organizer_type ?? 'Lembaga / Pengelola Kampanye' }}</p>
                            <p class="text-xs text-[#52665f] leading-relaxed pt-1">
                                {{ $campaign->organizer_description ?? 'Informasi pihak/organisasi pengelola kampanye ini dicatat dan diawasi langsung oleh Administrator KitaPeduli.' }}
                            </p>
                        </div>
                    </div>
                </section>

                <section id="doa-orang-baik" class="kp-card p-6 sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-5">
                        <div>
                            <h2 class="text-lg sm:text-xl font-extrabold text-[#122620]">Doa-doa Orang Baik ({{ $prayers->total() }})</h2>
                            <p class="text-xs text-[#647770]">Daftar donatur publik dan pesan doa dari transaksi donasi yang telah berhasil</p>
                        </div>
                        @guest
                            <a href="{{ route('login') }}" class="text-xs font-bold text-emerald-700 hover:underline">
                                Login sebagai Donatur untuk memberi Aamiin
                            </a>
                        @endguest
                    </div>

                    @if($prayers->count() > 0)
                        <div class="space-y-4">
                            @foreach($prayers as $prayerDonation)
                                @php
                                    $currentUserId = auth()->id();
                                    $hasAmened = $currentUserId && $prayerDonation->reactions->contains('user_id', $currentUserId);
                                @endphp
                                <div class="kp-card-soft p-5 space-y-3">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full {{ $prayerDonation->is_anonymous ? 'bg-[#d5dfd8] text-[#3c524b]' : 'bg-gradient-to-br from-emerald-600 to-sky-600 text-white' }} flex items-center justify-center font-bold text-sm shrink-0">
                                                {{ strtoupper(substr($prayerDonation->publicDonorName(), 0, 1)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-[#122620] text-sm">
                                                    {{ $prayerDonation->publicDonorName() }}
                                                </h4>
                                                <p class="text-[11px] text-[#647770]">
                                                    Berdonasi <strong class="text-[#122620]">Rp {{ number_format((float) $prayerDonation->amount, 0, ',', '.') }}</strong>
                                                    &bull; {{ $prayerDonation->created_at?->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>

                                        <div>
                                            @auth
                                                @if(auth()->user()->isDonor())
                                                    <form action="{{ route('donations.amen.toggle', $prayerDonation->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                                class="px-3.5 py-1.5 rounded-full text-xs font-bold transition-colors cursor-pointer flex items-center gap-1.5 {{ $hasAmened ? 'bg-gradient-to-r from-emerald-600 to-sky-600 text-white' : 'bg-white border border-[#dce6df] text-[#122620] hover:border-emerald-500 hover:text-emerald-700' }}">
                                                            <span>Aamiin</span>
                                                            <span class="px-1.5 py-0.2 rounded-full text-[11px] {{ $hasAmened ? 'bg-white/20 text-white' : 'bg-[#f2f6f3] text-[#122620]' }}">
                                                                {{ $prayerDonation->amen_count }}
                                                            </span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="px-3 py-1.5 rounded-full bg-white border border-[#dce6df] text-[#3c524b] text-xs font-semibold">
                                                        {{ $prayerDonation->amen_count }} Aamiin
                                                    </span>
                                                @endif
                                            @else
                                                <span title="Login sebagai Donatur Terdaftar untuk memberikan Aamiin"
                                                      class="px-3 py-1.5 rounded-full bg-white border border-[#dce6df] text-[#3c524b] text-xs font-semibold">
                                                    {{ $prayerDonation->amen_count }} Aamiin
                                                </span>
                                            @endauth
                                        </div>
                                    </div>

                                    @if($prayerDonation->donor_message)
                                        <p class="text-xs sm:text-sm text-[#122620] bg-white p-3.5 rounded-xl border border-[#e3eae4]">
                                            {{ $prayerDonation->donor_message }}
                                        </p>
                                    @else
                                        <p class="text-xs text-[#7a8c85] italic">
                                            Donatur tidak menyertakan pesan khusus.
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $prayers->links() }}
                        </div>
                    @else
                        <div class="p-6 rounded-2xl bg-[#f6f8f6] border border-dashed border-[#d7e2da] text-center text-xs text-[#647770]">
                            Belum ada doa atau transaksi donasi berhasil pada kampanye ini.
                        </div>
                    @endif
                </section>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <section id="pedoman-kebaikan" class="kp-card p-6 space-y-3">
                        <h2 class="text-base font-extrabold text-[#122620]">Pedoman Kebaikan</h2>
                        <ul class="text-xs text-[#52665f] space-y-2 list-disc list-inside leading-relaxed">
                            <li>Pastikan nominal dan metode simulasi pembayaran (QRIS atau Transfer Bank) dipilih dengan benar.</li>
                            <li>Gunakan fitur <strong>Donasi Anonim</strong> apabila Anda tidak ingin menampilkan nama asli di Doa-doa Orang Baik.</li>
                            <li>Seluruh pembayaran di aplikasi KitaPeduli adalah <strong>Simulasi / Demo</strong> untuk tugas akhir PPBO.</li>
                        </ul>
                    </section>

                    <section id="faq-kampanye" class="kp-card p-6 space-y-3">
                        <h2 class="text-base font-extrabold text-[#122620]">FAQ Singkat</h2>
                        <div class="text-xs text-[#52665f] space-y-2.5 leading-relaxed">
                            <p><strong class="text-[#122620]">Kapan donasi saya terhitung?</strong><br>Segera setelah Anda menekan tombol konfirmasi pada halaman simulasi pembayaran.</p>
                            <p><strong class="text-[#122620]">Apakah Guest bisa berdonasi?</strong><br>Ya, Guest dapat berdonasi tanpa login dan langsung melihat hasil transaksinya.</p>
                        </div>
                    </section>
                </div>

            </div>

            <aside id="ringkasan-donasi" class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
                <div class="kp-card p-6 sm:p-7 space-y-5">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Ringkasan Donasi</span>
                        <p class="text-2xl sm:text-3xl font-extrabold text-[#122620] mt-1">
                            Rp {{ number_format((float) $campaign->collected_amount, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-[#647770] mt-0.5">
                            terkumpul dari target <strong class="text-[#122620]">Rp {{ number_format((float) $campaign->target_amount, 0, ',', '.') }}</strong>
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <div class="w-full h-3 bg-[#eaf1ec] rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-500 to-sky-500 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-[#122620]">
                            <span class="text-emerald-700">{{ $progress }}% Tercapai</span>
                            <span>{{ $campaign->donor_count }} Donasi Berhasil</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div class="kp-card-soft p-3.5">
                            <p class="text-[11px] text-[#647770] font-medium">Jumlah Donasi</p>
                            <p class="text-base font-extrabold text-[#122620]">{{ $campaign->donor_count }} Transaksi</p>
                        </div>
                        <div class="kp-card-soft p-3.5">
                            <p class="text-[11px] text-[#647770] font-medium">Sisa Waktu</p>
                            <p class="text-base font-extrabold text-sky-700">
                                @if($isAccepting)
                                    {{ $remainingDays }} Hari
                                @else
                                    Berakhir
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($isAccepting)
                        @if(auth()->check() && auth()->user()->isAdmin())
                            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-medium text-center">
                                Anda login sebagai <strong>Admin</strong>. Gunakan akun Donatur atau mode Guest untuk menguji alur donasi.
                            </div>
                            <a href="{{ route('admin.campaigns.edit', $campaign->id) }}"
                               class="kp-btn-primary w-full py-3.5 px-6 text-sm">
                                Edit Kampanye Ini
                            </a>
                        @else
                            <a href="{{ route('donations.create', $campaign->slug) }}"
                               class="kp-btn-accent w-full py-3.5 px-6 text-sm">
                                Donasi Sekarang
                            </a>
                            <p class="text-[11px] text-center text-[#647770]">
                                Tersedia metode <strong>QRIS Simulasi</strong> dan <strong>Transfer Bank Simulasi</strong>
                            </p>
                        @endif
                    @else
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold text-center">
                            Kampanye ini sudah berakhir atau dinonaktifkan sehingga tidak menerima donasi baru.
                        </div>
                    @endif
                </div>
            </aside>

        </div>
    </div>
@endsection
