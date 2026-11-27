@extends('layouts.app')

@section('title', 'Tentang Kami, Pedoman Kebaikan, dan FAQ | KitaPeduli')

@section('content')
    <div class="kp-container py-10 space-y-10">
        <section class="kp-card p-6 sm:p-10">
            <span class="kp-pill bg-emerald-50 text-emerald-800 border border-emerald-200/70 mb-3">
                Tentang Platform
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-[#122620] tracking-tight mb-4">
                KitaPeduli: Crowdfunding Sosial yang <span class="text-emerald-600">Transparan</span> &amp; <span class="text-sky-600">Empatik</span>
            </h1>
            <p class="text-sm sm:text-base text-[#52665f] leading-relaxed max-w-3xl mb-6">
                <strong>KitaPeduli</strong> adalah aplikasi web crowdfunding dan donasi sosial yang dibangun menggunakan <strong>Laravel 13</strong>. Aplikasi ini menyediakan alur donasi bagi Donatur Terdaftar maupun Pengunjung (Guest), simulasi pembayaran mandiri, serta pencatatan transparansi perkembangan dan penyaluran dana kampanye.
            </p>

            <div id="transparansi" class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200/70">
                    <span class="inline-block text-xs font-extrabold text-emerald-700 mb-1.5">01 &bull; Terkelola</span>
                    <h3 class="font-bold text-[#122620] text-sm mb-1.5">Pengelolaan Kampanye oleh Admin</h3>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Seluruh kampanye di KitaPeduli dibuat dan dikelola langsung oleh Administrator agar informasi pengelola dan tujuan penggalangan dana terdata rapi.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-teal-50/60 border border-teal-200/70">
                    <span class="inline-block text-xs font-extrabold text-teal-700 mb-1.5">02 &bull; Fleksibel</span>
                    <h3 class="font-bold text-[#122620] text-sm mb-1.5">Donasi Terdaftar dan Guest</h3>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Pengunjung dapat berdonasi tanpa harus login (Guest), atau masuk dengan akun Donatur Terdaftar untuk menyimpan riwayat donasi pribadi dan memberikan Aamiin pada doa donatur lain.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-sky-50/60 border border-sky-200/70">
                    <span class="inline-block text-xs font-extrabold text-sky-700 mb-1.5">03 &bull; Akuntabel</span>
                    <h3 class="font-bold text-[#122620] text-sm mb-1.5">Transparansi Informasi</h3>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Setiap detail kampanye memuat cerita lengkap, kabar terbaru, catatan laporan penyaluran dana, informasi pengelola, serta Doa-doa Orang Baik dari transaksi yang berhasil.
                    </p>
                </div>
            </div>
        </section>

        <section id="pedoman-kebaikan" class="kp-card p-6 sm:p-10">
            <span class="kp-pill bg-sky-50 text-sky-800 border border-sky-200/70 mb-3">
                Ketentuan Platform
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-[#122620] mb-2">Pedoman Kebaikan KitaPeduli</h2>
            <p class="text-xs sm:text-sm text-[#52665f] mb-6">
                Pedoman berikut mengatur privasi donatur, keabsahan status transaksi, dan ketentuan simulasi pembayaran di KitaPeduli.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="kp-card-soft p-5">
                    <h4 class="font-bold text-[#122620] mb-1">1. Perhitungan Dana Kampanye</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Hanya kampanye berstatus aktif dan belum melewati tanggal berakhir yang dapat menerima donasi baru. Dana terkumpul dihitung hanya dari donasi yang telah dikonfirmasi berhasil setelah simulasi pembayaran selesai.
                    </p>
                </div>
                <div class="kp-card-soft p-5">
                    <h4 class="font-bold text-[#122620] mb-1">2. Privasi dan Opsi Donasi Anonim</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Donatur dapat memilih opsi sembunyikan nama (Donasi Anonim). Sistem akan menampilkan label <strong>Orang Baik (Anonim)</strong> pada daftar publik dan tidak menampilkan email donatur ke publik.
                    </p>
                </div>
                <div class="kp-card-soft p-5">
                    <h4 class="font-bold text-[#122620] mb-1">3. Etika Menulis Doa dan Memberi Aamiin</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Tuliskan pesan atau doa yang santun bagi penerima manfaat. Donatur Terdaftar dapat memberikan maksimal satu kali Aamiin pada setiap pesan doa.
                    </p>
                </div>
                <div class="kp-card-soft p-5">
                    <h4 class="font-bold text-[#122620] mb-1">4. Ketentuan Simulasi Pembayaran</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Seluruh instruksi QRIS maupun nomor rekening Transfer Bank pada aplikasi ini bersifat <strong>Simulasi / Demo: bukan pembayaran nyata</strong> untuk keperluan demonstrasi akademik PPBO.
                    </p>
                </div>
            </div>
        </section>

        <section id="cara-donasi" class="rounded-3xl bg-gradient-to-br from-emerald-600 via-teal-600 to-sky-600 text-white p-6 sm:p-10 shadow-sm">
            <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold mb-3">
                Alur Donasi
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold mb-5">Cara Berdonasi di KitaPeduli</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-white/12 border border-white/15 backdrop-blur-xs">
                    <span class="text-xs font-extrabold text-emerald-200 block mb-1">Langkah 1</span>
                    <p class="text-xs sm:text-sm leading-relaxed">Pilih kampanye aktif di beranda atau halaman <a href="{{ route('campaigns.index') }}" class="font-bold underline">Jelajahi Kampanye</a>.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white/12 border border-white/15 backdrop-blur-xs">
                    <span class="text-xs font-extrabold text-emerald-200 block mb-1">Langkah 2</span>
                    <p class="text-xs sm:text-sm leading-relaxed">Tekan tombol Donasi, isi nominal minimal Rp 1.000, identitas donatur, doa opsional, dan pilih metode simulasi.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white/12 border border-white/15 backdrop-blur-xs">
                    <span class="text-xs font-extrabold text-emerald-200 block mb-1">Langkah 3</span>
                    <p class="text-xs sm:text-sm leading-relaxed">Periksa referensi transaksi di halaman instruksi lalu tekan tombol konfirmasi simulasi pembayaran berhasil.</p>
                </div>
                <div class="p-4 rounded-2xl bg-white/12 border border-white/15 backdrop-blur-xs">
                    <span class="text-xs font-extrabold text-emerald-200 block mb-1">Langkah 4</span>
                    <p class="text-xs sm:text-sm leading-relaxed">Saldo kampanye dan daftar Doa-doa Orang Baik akan diperbarui secara otomatis.</p>
                </div>
            </div>
        </section>

        <section id="faq" class="kp-card p-6 sm:p-10">
            <span class="kp-pill bg-emerald-50 text-emerald-800 border border-emerald-200/70 mb-3">
                Tanya Jawab
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-[#122620] mb-6">Pertanyaan yang Sering Diajukan (FAQ)</h2>

            <div class="space-y-4">
                <div class="kp-card-soft p-5">
                    <h4 class="font-bold text-[#122620] text-sm mb-1">Apakah saya harus membuat akun untuk berdonasi di KitaPeduli?</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Tidak wajib. Anda dapat berdonasi langsung sebagai <strong>Guest (Donatur Tanpa Login)</strong> dengan mengisi nama, nominal, dan memilih metode pembayaran. Jika Anda login sebagai <strong>Donatur Terdaftar</strong>, Anda akan memiliki halaman riwayat donasi pribadi dan dapat memberikan Aamiin pada doa donatur lain.
                    </p>
                </div>

                <div class="kp-card-soft p-5">
                    <h4 class="font-bold text-[#122620] text-sm mb-1">Bagaimana cara kerja simulasi pembayaran QRIS dan Transfer Bank?</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Setelah mengirim formulir donasi, sistem membuat transaksi menunggu konfirmasi dan mengarahkan Anda ke halaman instruksi pembayaran simulasi. Tekan tombol Simulasikan Pembayaran Berhasil agar backend memvalidasi transaksi secara atomik dan mengubah status transaksi menjadi berhasil.
                    </p>
                </div>

                <div class="kp-card-soft p-5">
                    <h4 class="font-bold text-[#122620] text-sm mb-1">Siapa yang dapat membuat kampanye penggalangan dana?</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Pada ruang lingkup KitaPeduli saat ini, hanya <strong>Admin</strong> yang dapat membuat dan mengelola kampanye beserta informasi pihak pengelola, kabar terbaru, dan catatan penyaluran dana.
                    </p>
                </div>

                <div class="kp-card-soft p-5">
                    <h4 class="font-bold text-[#122620] text-sm mb-1">Apakah Guest bisa melihat riwayat donasi seluruh pengunjung?</h4>
                    <p class="text-xs text-[#52665f] leading-relaxed">
                        Demi keamanan dan privasi, Guest hanya dapat melihat status transaksi yang sedang dilakukan melalui token akses transaksi, dan tidak memiliki akses ke daftar riwayat transaksi Guest lainnya.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
