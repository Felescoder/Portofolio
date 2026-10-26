**MATCHA MUSE**
==============
Matcha Muse adalah sebuah platform e-commerce premium yang dirancang khusus untuk menghadirkan pengalaman berbelanja matcha autentik dari Uji, Kyoto. Dari sisi antarmuka (Frontend), website ini mengusung desain minimalis dan elegan dengan palet warna hijau matcha (primary), krem lembut, dan abu-abu arang, dipadukan dengan tipografi Playfair Display serta Poppins. Pengalaman pengguna (UX) dibangun melalui alur perjalanan pelanggan yang mulus: mulai dari halaman beranda yang memukau, eksplorasi menu dengan sistem filter, halaman detail produk yang memungkinkan kustomisasi pesanan (seperti suhu dan jenis susu), hingga sistem keranjang dan checkout yang transparan. Penerapan prinsip psikologi desain seperti Gestalt dan Interaction Design memastikan setiap elemen, tombol, dan umpan balik (feedback) memberikan kenyamanan, kejelasan, serta mencegah pengguna melakukan kesalahan saat bertransaksi.

Di balik layar, Matcha Muse dibangun menggunakan arsitektur decoupled (terpisah), di mana Frontend (Nuxt 4) berkomunikasi dengan Backend (Laravel 11) melalui REST API. Sistem ini mengamankan data pelanggan menggunakan autentikasi token Laravel Sanctum. Backend menangani logika bisnis yang kompleks, seperti pengurangan stok otomatis, kalkulasi harga, dan pemrosesan transaksi menggunakan database transaction untuk mencegah error data. Selain untuk pelanggan, proyek ini juga dilengkapi dengan Panel Admin yang komprehensif. Melalui dashboard khusus, pemilik toko dapat memantau total pendapatan secara real-time, mengelola katalog produk (Tambah, Edit, Hapus), serta memproses dan memperbarui status pesanan pelanggan dengan mudah.

⚙️ Keterangan Tools & Teknologi:
Frontend: Nuxt 4 (Vue 3 Composition API), Tailwind CSS v4, HTML, CSS, JavaScript.
Backend: Laravel 11 (PHP 8+), MySQL 8, Laravel Sanctum (API Auth).
Environment & Development Tools: Laragon (Local Server), Visual Studio Code, dan browser (Chrome/Edge) untuk testing.
__________


**PROYEK POINT OF SALES**
==========================
Proyek kasir POS (Point of Sale) ini dirancang menggunakan arsitektur decoupled (API-First) yang memisahkan secara tegas antara sistem backend dan antarmuka frontend. Di sisi backend, aplikasi memanfaatkan framework Laravel untuk menyediakan endpoint REST API yang mengelola logika bisnis, otentikasi admin (POST /api/login), serta pengolahan basis data MySQL. Layanan ini menangani operasi CRUD untuk master data barang—seperti kode, nama, harga, dan stok—serta mengeksekusi proses transaksi kasir secara aman menggunakan mekanisme DB::transaction guna menjamin bahwa pemotongan stok barang dan pembuatan nota transaksi berlangsung secara konsisten tanpa risiko kecacatan data.   Sementara di sisi pengguna (client-side), frontend dibangun berbasis Nuxt 3 (Vue.js 3) yang dibalut dengan Tailwind CSS untuk menyajikan antarmuka pengguna yang cepat, bersih, dan responsif. Antarmuka ini terbagi ke dalam beberapa halaman operasional, mencakup halaman autentikasi admin (index.vue), pengelolaan data barang (barang.vue), halaman kasir interaktif (kasir.vue) untuk memilih produk ke keranjang belanja dan menghitung kalkulasi harga serta kembalian secara real-time, hingga halaman rekapitulasi riwayat transaksi (riwayattransaksi.vue). Pertukaran data antara frontend dan backend dijalankan secara asinkron menggunakan pemanggilan API yang dilindungi oleh otentikasi bearer token (useCookie). 

Tools & Teknologi Pengembangan:
Backend Framework: Laravel (PHP) untuk penyedia REST API dan logika bisnis.   
Frontend Framework: Nuxt 3 / Vue.js 3 (Composition API) untuk antarmuka pengguna reaktif.   
CSS Framework: Tailwind CSS untuk penataan gaya UI secara utility-first.   
Database: MySQL untuk penyimpanan data entitas pengguna, barang, dan transaksi.   
Development Environment: Laragon (Apache, PHP, MySQL) sebagai lingkungan server lokal.   
Database Management Tool: HeidiSQL / phpMyAdmin untuk pengelolaan skema basis data.
Package Managers: Composer (pengelola dependensi PHP) dan NPM (pengelola dependensi JavaScript/Node.js).
____________

**E-KALKULATOR**
E-Kalkulator adalah kalkulator digital berbasis model Casio MX-12B dengan meja 12 digit dengan berbagai fungsi dasar dan fitur bisnis. Layarnya memiliki indikator M yang menandakan masih ada angka tersimpan di memori dan E yang menandakan error karena hasil melebihi kapasitas digit. Tombol angka 0–9, 00, dan titik desimal digunakan untuk memasukkan angka, sedangkan tombol + − × ÷ = untuk operasi hitung dasar, % untuk persentase, dan +/− untuk mengubah tanda positif/negatif. Kalkulator ini memiliki independent memory dengan tombol MC untuk menghapus memori, MR untuk menampilkan isi memori, M+ untuk menambahkan angka di layar ke memori, dan M− untuk menguranginya; penting dicatat bahwa tombol C/AC tidak menghapus memori, hanya MC yang dapat mengosongkannya. Fitur tambahan meliputi MU untuk menghitung markup/harga jual dan laba dagang, tombol C/AC untuk menghapus angka terakhir atau seluruh perhitungan, serta tombol ▶ sebagai backspace untuk menghapus satu digit terakhir. Dengan demikian, kalkulator ini cocok untuk perhitungan kasir, kantor, dan dagang karena mendukung operasi dasar, persentase, memori sementara, serta koreksi input dengan cepat.

⚙️Teknologi:
HTML
Vanilla CSS
Vanilla Javascript