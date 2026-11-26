@extends('layouts.app')

@section('title', 'Tentang Kami, Pedoman Kebaikan, dan FAQ | KitaPeduli')

@section('content')
    <div class="kp-container py-10 space-y-12">
        <section class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-2xs">
            <span class="inline-block px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold mb-3">
                Tentang Platform
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mb-4">
                KitaPeduli: Platform Crowdfunding dan Donasi Sosial
            </h1>
            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-3xl mb-6">
                <strong>KitaPeduli</strong> adalah aplikasi web crowdfunding dan donasi sosial yang dibangun menggunakan <strong>Laravel 13</strong>. Aplikasi ini menyediakan alur donasi bagi Donatur Terdaftar maupun Pengunjung (Guest), simulasi pembayaran mandiri, serta pencatatan transparansi perkembangan dan penyaluran dana kampanye.
            </p>

            <div id="transparansi" class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-4">
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <h3 class="font-bold text-slate-900 text-sm mb-1.5">1. Pengelolaan Kampanye oleh Admin</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Seluruh kampanye di KitaPeduli dibuat dan dikelola langsung oleh Administrator agar informasi pengelola dan tujuan penggalangan dana terdata rapi.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <h3 class="font-bold text-slate-900 text-sm mb-1.5">2. Donasi Terdaftar dan Guest</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pengunjung dapat berdonasi tanpa harus login (Guest), atau masuk dengan akun Donatur Terdaftar untuk menyimpan riwayat donasi pribadi dan memberikan Aamiin pada doa donatur lain.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <h3 class="font-bold text-slate-900 text-sm mb-1.5">3. Transparansi Informasi</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Setiap detail kampanye memuat cerita lengkap, kabar terbaru, catatan laporan penyaluran dana, informasi pengelola, serta Doa-doa Orang Baik dari transaksi yang berhasil.
                    </p>
                </div>
            </div>
        </section>

        <section id="pedoman-kebaikan" class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-2xs">
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold mb-3">
                Ketentuan Platform
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-3">Pedoman Kebaikan KitaPeduli</h2>
            <p class="text-xs sm:text-sm text-slate-600 mb-6">
                Pedoman berikut mengatur privasi donatur, keabsahan status transaksi, dan ketentuan simulasi pembayaran di KitaPeduli.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="p-4 rounded-2xl border border-slate-200/80">
                    <h4 class="font-bold text-slate-900 mb-1">1. Perhitungan Dana Kampanye</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Hanya kampanye berstatus aktif dan belum melewati tanggal berakhir yang dapat menerima donasi baru. Dana terkumpul dihitung hanya dari donasi yang telah berstatus berhasil (<code>success</code>) setelah pembayaran dikonfirmasi.
                    </p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-200/80">
                    <h4 class="font-bold text-slate-900 mb-1">2. Privasi dan Opsi Donasi Anonim</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Donatur dapat memilih opsi sembunyikan nama (Donasi Anonim). Sistem akan menampilkan label <strong>Orang Baik (Anonim)</strong> pada daftar publik dan tidak menampilkan email donatur ke publik.
                    </p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-200/80">
                    <h4 class="font-bold text-slate-900 mb-1">3. Etika Menulis Doa dan Memberi Aamiin</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Tuliskan pesan atau doa yang santun bagi penerima manfaat. Donatur Terdaftar dapat memberikan maksimal satu kali Aamiin pada setiap pesan doa.
                    </p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-200/80">
                    <h4 class="font-bold text-slate-900 mb-1">4. Ketentuan Simulasi Pembayaran</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Seluruh instruksi QRIS maupun nomor rekening Transfer Bank pada aplikasi ini bersifat <strong>Simulasi / Demo: bukan pembayaran nyata</strong> untuk keperluan demonstrasi akademik PPBO.
                    </p>
                </div>
            </div>
        </section>

        <section id="cara-donasi" class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-2xs">
            <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold mb-3">
                Alur Donasi
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-4">Cara Berdonasi di KitaPeduli</h2>
            <ol class="list-decimal list-inside space-y-2 text-xs sm:text-sm text-slate-600 leading-relaxed">
                <li>Pilih kampanye aktif di beranda atau halaman <a href="{{ route('campaigns.index') }}" class="font-semibold text-teal-700 hover:underline">Jelajahi Kampanye</a>.</li>
                <li>Tekan tombol Donasi Sekarang, isi nominal minimal Rp 1.000, identitas donatur, pesan doa opsional, dan pilih metode simulasi (QRIS atau Transfer Bank).</li>
                <li>Pada halaman simulasi pembayaran, periksa referensi transaksi lalu tekan tombol konfirmasi simulasi pembayaran berhasil.</li>
                <li>Saldo kampanye dan daftar Doa-doa Orang Baik akan diperbarui secara otomatis.</li>
            </ol>
        </section>

        <section id="faq" class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-2xs">
            <span class="inline-block px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold mb-3">
                Tanya Jawab
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 mb-6">Pertanyaan yang Sering Diajukan (FAQ)</h2>

            <div class="space-y-4">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <h4 class="font-bold text-slate-900 text-sm mb-1">Apakah saya harus membuat akun untuk berdonasi di KitaPeduli?</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Tidak wajib. Anda dapat berdonasi langsung sebagai <strong>Guest (Donatur Tanpa Login)</strong> dengan mengisi nama, nominal, dan memilih metode pembayaran. Jika Anda login sebagai <strong>Donatur Terdaftar</strong>, Anda akan memiliki halaman riwayat donasi pribadi dan dapat memberikan Aamiin pada doa donatur lain.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <h4 class="font-bold text-slate-900 text-sm mb-1">Bagaimana cara kerja simulasi pembayaran QRIS dan Transfer Bank?</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Setelah mengirim formulir donasi, sistem membuat transaksi berstatus <code>pending</code> dan mengarahkan Anda ke halaman instruksi pembayaran simulasi. Tekan tombol Simulasikan Pembayaran Berhasil agar backend memvalidasi transaksi secara atomik dan mengubah status pembayaran menjadi <code>paid</code> serta donasi menjadi <code>success</code>.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <h4 class="font-bold text-slate-900 text-sm mb-1">Siapa yang dapat membuat kampanye penggalangan dana?</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pada ruang lingkup KitaPeduli saat ini, hanya <strong>Admin</strong> yang dapat membuat dan mengelola kampanye beserta informasi pihak pengelola, kabar terbaru, dan catatan penyaluran dana.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <h4 class="font-bold text-slate-900 text-sm mb-1">Apakah Guest bisa melihat riwayat donasi seluruh pengunjung?</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Demi keamanan dan privasi, Guest hanya dapat melihat status transaksi yang sedang dilakukan melalui token akses transaksi, dan tidak memiliki akses ke daftar riwayat transaksi Guest lainnya.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
