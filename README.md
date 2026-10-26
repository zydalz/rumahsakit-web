# Sistem Informasi Rumah Sakit - RS Oasis Husada

Aplikasi sistem informasi rumah sakit (SIMRS) rawat jalan modern yang dibangun menggunakan **Laravel 12**, **Tailwind CSS**, dan **MySQL**. Aplikasi ini dirancang untuk memudahkan alur pelayanan rumah sakit dari pendaftaran pasien, konfirmasi jadwal, antrean poli, pemeriksaan dokter & rekam medis, kasir, farmasi, manajemen aset visual web, hingga pemantauan laporan kinerja oleh Kepala Rumah Sakit serta audit log aktivitas sistem.

---

## Fitur Utama Sistem

1. **Alur Pelayanan Pasien Terintegrasi**:
   - Pendaftaran online rawat jalan dengan pemilihan poliklinik, dokter, jadwal, dan estimasi jam kedatangan.
   - Konfirmasi verifikasi kunjungan oleh Admin/Staf untuk memastikan ketersediaan dokter sebelum pasien datang.
   - Sistem antrean poli dengan status dinamis (*menunggu*, *dipanggil*, *diperiksa*, *selesai*, *dilewati*).
   - Layar monitor panggilan antrean (*Display Monitor*) untuk layar TV ruang tunggu poliklinik dengan pemanggil suara/audio.

2. **Pemeriksaan Medis & Rekam Medis (Dokter)**:
   - Pencatatan keluhan, tanda-tanda vital (tekanan darah, suhu, berat badan, tinggi badan), dan diagnosis ICD.
   - Formulir resep obat dinamis dengan kalkulasi biaya dan ketersediaan stok obat secara otomatis.
   - Akses riwayat kunjungan dan alergi pasien sebelumnya untuk akurasi tindakan medis.

3. **Kasir & Keuangan**:
   - Pembuatan tagihan otomatis yang merangkum jasa dokter/konsultasi poli dan rincian obat resep.
   - Pencatatan pembayaran (tunai, transfer, QRIS) dengan dukungan pembayaran bertahap/lunas.
   - Cetak nota resmi pembayaran kasir untuk pasien.

4. **Farmasi & Persediaan Obat**:
   - Antrean resep obat dengan verifikasi otomatis status pelunasan tagihan kasir sebelum obat diserahkan.
   - Penyerahan obat dengan pemotongan stok otomatis dan pencatatan riwayat kartu stok inventaris.

5. **Manajemen Data Master & Galeri Visual (Admin)**:
   - Pengelolaan data Poliklinik, Dokter, dan Jadwal Praktik langsung lewat web.
   - Profil lengkap pasien riil (Golongan Darah, Riwayat Alergi, Nomor Kontak Darurat Keluarga).
   - Pengelolaan aset visual landing page (Banner Hero, Foto Fasilitas, Galeri Kegiatan) secara dinamis.
   - Informasi UGD 24 Jam Siaga dengan hotline darurat pada beranda publik.

6. **Peran Eksekutif (Kepala Rumah Sakit)**:
   - Dashboard eksekutif pimpinan: ringkasan pendapatan kasir harian/bulanan, tren kunjungan poliklinik, dan status stok obat kritis.
   - Laporan kinerja dan pelayanan: grafik tren penyakit terbanyak, dokter paling aktif, dan obat paling sering diresepkan.
   - Fitur cetak laporan berkas (*print/PDF-ready*).

7. **Keamanan & Audit Trail (Log Aktivitas)**:
   - Pencatatan otomatis setiap aksi penting dalam sistem (konfirmasi pendaftaran, pemeriksaan rekam medis, pembayaran kasir, penyerahan obat, dan autentikasi login/logout).
   - Pelacakan waktu WIB, nama pengguna, peran, alamat IP, dan perangkat (*User Agent*).
   - Halaman pemantauan dan penyaringan (*filter*) log aktivitas untuk Admin dan Kepala Rumah Sakit.

---

## Prasyarat Sistem

Sebelum menjalankan aplikasi di komputer lokal, pastikan telah terpasang:
- **PHP** >= 8.2 (ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `curl`)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM**
- **Database MySQL / MariaDB** (dapat menggunakan Laragon atau XAMPP)

---

## Langkah Instalasi di Komputer Lokal

1. **Clone Repositori**:
   ```bash
   git clone https://github.com/zydalz/rumahsakit-web.git
   cd rumahsakit-web
   ```

2. **Install Dependensi PHP (Composer)**:
   ```bash
   composer install
   ```

3. **Install Dependensi Frontend (Node/NPM)**:
   ```bash
   npm install
   ```

4. **Siapkan Berkas Konfigurasi Lingkungan (`.env`)**:
   Salin berkas contoh konfigurasi:
   ```bash
   cp .env.example .env
   ```
   *Pada Windows PowerShell:*
   ```powershell
   Copy-Item .env.example .env
   ```

5. **Generate Kunci Keamanan Aplikasi**:
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database**:
   Buka file `.env` menggunakan teks editor dan sesuaikan pengaturan database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=oasis_husada
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *Pastikan database dengan nama tersebut sudah dibuat di MySQL Anda (misal via phpMyAdmin atau HeidiSQL).*

7. **Jalankan Migrasi & Database Seeder (Sekali Jalan)**:
   Perintah ini akan membuat seluruh tabel sekaligus mengisi data awal master poli, dokter, obat, pasien, dan skenario alur pengujian lengkap:
   ```bash
   php artisan migrate --seed
   ```
   *Atau jika database sudah pernah dimigrasi sebelumnya:*
   ```bash
   php artisan db:seed
   ```

8. **Kompilasi Aset Frontend**:
   ```bash
   npm run build
   ```
   *Atau jalankan live server Vite jika sedang tahap pengembangan:*
   ```bash
   npm run dev
   ```

9. **Jalankan Server Aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka peramban (*browser*) Anda dan akses: **http://127.0.0.1:8000**

---

## Daftar Akun Pengujian (Login Demo)

Semua akun pengujian di bawah ini telah disiapkan oleh database seeder dengan kata sandi bawaan (*default password*): **`password`**

| Peran (Role) | Nama Pengguna | Email Login | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin Sistem** | Admin Utama | `admin@gmail.com` | Konfirmasi pendaftaran, kelola pasien, master data (poli, dokter, jadwal), kelola galeri web, log aktivitas sistem. |
| **Kepala Rumah Sakit** | Dr. dr. H. Soebroto, Sp.B, FICS | `kepala.rs@gmail.com` | Dashboard eksekutif, laporan pendapatan kasir, statistik tren kunjungan/penyakit, cetak laporan manajerial, audit log. |
| **Dokter Umum** | dr. Andi Pratama | `dokter.andi@gmail.com` | Antrean poli, pemanggilan pasien, pencatatan rekam medis & diagnosa, peresepan obat dinamis. |
| **Dokter Penyakit Dalam** | dr. Budi Santoso, Sp.PD | `dokter.budi@gmail.com` | Pelayanan dan rekam medis pasien Poliklinik Penyakit Dalam. |
| **Dokter Anak** | dr. Siti Rahmawati, Sp.A | `dokter.siti@gmail.com` | Pelayanan dan rekam medis pasien Poliklinik Anak. |
| **Dokter Spesialis Lainnya** | dr. Hendra Kurniawan / dr. Rina Wijaya / dr. Maya Putri / dr. Doni Prasetyo | `dokter.spesialis@gmail.com` / `dokter.rina@gmail.com` / `dokter.maya@gmail.com` / `dokter.doni@gmail.com` | Pelayanan dokter poli spesialis Bedah, Gigi, Kandungan, THT, Mata, Saraf, Jantung, Kulit, Jiwa. |
| **Petugas Kasir** | Kasir Utama | `kasir@gmail.com` | Daftar tagihan pasien, input pembayaran tunai/transfer/QRIS, cetak nota kuitansi kasir. |
| **Petugas Farmasi** | Apoteker Utama | `apoteker@gmail.com` | Verifikasi status pembayaran resep, penyerahan obat, pemotongan stok otomatis, kartu stok. |
| **Pasien Terdaftar 1** | Budi Santoso | `pasien1@gmail.com` | Pendaftaran rawat jalan online, cek riwayat kunjungan, nomor antrean, dan rekam medis mandiri. |
| **Pasien Terdaftar 2** | Siti Rahayu | `pasien2@gmail.com` | Pendaftaran rawat jalan online dan cek estimasi jam hadir. |

---

## Skenario Uji Coba Alur Layanan Lengkap (End-to-End)

Untuk menguji alur kerja sistem dari awal hingga akhir, ikuti tahapan berikut:

1. **Tahap 1: Pendaftaran Kunjungan oleh Pasien**
   - Login menggunakan akun pasien: `pasien1@gmail.com` (password: `password`).
   - Masuk ke menu **Daftar Kunjungan Poli**.
   - Pilih Poliklinik (misal: *Poli Umum*), pilih jadwal dokter yang tersedia, tentukan tanggal kunjungan, dan pilih estimasi jam kedatangan.
   - Klik **Kirim Pendaftaran**. Pasien akan mendapatkan nomor antrean dan status *Menunggu Konfirmasi Admin*.

2. **Tahap 2: Verifikasi & Konfirmasi oleh Admin**
   - Logout lalu login sebagai admin: `admin@gmail.com` (password: `password`).
   - Buka menu **Verifikasi Pasien** di sidebar.
   - Cari data pendaftaran pasien di atas, lalu klik tombol **Konfirmasi Kunjungan**.
   - Status pendaftaran berubah menjadi *Dikonfirmasi* dan siap dilayani di poliklinik.

3. **Tahap 3: Pemanggilan Antrean & Pemeriksaan oleh Dokter**
   - Buka tab baru pada peramban untuk membuka **Display Antrean**: `http://127.0.0.1:8000/antrean/display`.
   - Login sebagai dokter terkait: `dokter.andi@gmail.com` (password: `password`).
   - Pada dashboard dokter, klik **Panggil** pada nomor antrean pasien. Layar display antrean akan otomatis memutar audio panggilan nomor antrean.
   - Klik **Mulai Periksa**, input tanda vital (tekanan darah, suhu), keluhan, diagnosa pasien, dan pilih obat yang diresepkan.
   - Klik **Simpan Pemeriksaan Medis**. Tagihan kasir otomatis dibuat dan resep dikirim ke farmasi.

4. **Tahap 4: Pembayaran di Kasir**
   - Login sebagai kasir: `kasir@gmail.com` (password: `password`).
   - Buka menu **Daftar Tagihan**.
   - Pilih tagihan atas nama pasien tadi (status *Belum Lunas*).
   - Masukkan nominal pembayaran (bisa tunai/transfer/QRIS) dan klik **Simpan Pembayaran**.
   - Setelah tagihan berstatus *Lunas*, kasir dapat mencetak **Nota Kuitansi**.

5. **Tahap 5: Penyerahan Obat di Bagian Farmasi**
   - Login sebagai farmasi: `apoteker@gmail.com` (password: `password`).
   - Buka menu **Dashboard Farmasi / Resep**.
   - Resep pasien yang sudah lunas akan berada pada status *Siap Diserahkan*.
   - Petugas farmasi memeriksa etiket obat, lalu klik **Serahkan Obat ke Pasien**.
   - Status resep berubah menjadi *Selesai* dan stok obat di gudang farmasi otomatis berkurang sesuai resep.

6. **Tahap 6: Pemantauan Eksekutif & Audit Log**
   - Login sebagai Kepala RS: `kepala.rs@gmail.com` (password: `password`).
   - Buka menu **Dashboard Eksekutif** untuk melihat grafik pendapatan, kunjungan harian, dan ketersediaan obat.
   - Buka menu **Laporan Kinerja & Pelayanan** untuk melihat rekapitulasi data dan klik **Cetak Laporan**.
   - Buka menu **Log Aktivitas Sistem** untuk memeriksa jejak audit (*audit trail*) dari semua tahapan transaksi yang baru saja dilakukan.

---

## Menjalankan Pengujian Otomatis (Automated Tests)

Aplikasi dilengkapi dengan rangkaian unit & feature test lengkap yang mencakup alur pelayanan, pembatasan hak akses peran, data master, aset web, dan audit log:

```bash
php artisan test
```

Untuk menjalankan pengujian spesifik per fitur:
```bash
# Pengujian alur konfirmasi dan penyerahan obat farmasi
php artisan test --filter=AlurKonfirmasiFarmasiTest

# Pengujian pemanggilan antrean dan layar monitor TV
php artisan test --filter=AntreanDanPemeriksaanDokterTest

# Pengujian peran Kepala RS dan laporan eksekutif
php artisan test --filter=KepalaRumahSakitTest

# Pengujian master data admin dan aset galeri web
php artisan test --filter=MasterDataDanAsetWebTest

# Pengujian audit log dan alur aktivitas lengkap
php artisan test --filter=AuditLogDanAlurLengkapTest
```

---

## Lisensi & Pengembang

Aplikasi ini dikembangkan untuk keperluan sistem informasi rumah sakit PT Oasis Husada.
Hak Cipta &copy; 2026 Oasis Husada. Dilindungi Undang-Undang.
