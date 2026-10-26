<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\DetailResep;
use App\Models\DetailTagihan;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Resep;
use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AuditLogDanAlurLengkapTest extends TestCase
{
    public function test_audit_log_tercatat_saat_admin_mengonfirmasi_pendaftaran(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin Sistem', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]
        );

        $pasienUser = User::factory()->create(['role' => 'pasien', 'is_active' => true]);
        $pasien = Pasien::create([
            'user_id' => $pasienUser->id,
            'nama' => 'Pasien Uji Log ' . mt_rand(100, 999),
            'nomor_rm' => 'RM-' . mt_rand(100000, 999999),
            'nik' => (string) mt_rand(1000000000000000, 9999999999999999),
            'tanggal_lahir' => '1995-05-10',
            'jenis_kelamin' => 'L',
            'no_telepon' => '081233445566',
        ]);

        $poli = Poli::firstOrCreate(
            ['nama_poli' => 'Poli Penyakit Dalam'],
            ['lokasi_gedung' => 'Gedung A', 'lantai' => 2, 'biaya_konsultasi' => 50000, 'is_active' => true]
        );

        $dokterUser = User::factory()->create(['role' => 'dokter', 'is_active' => true]);
        $dokter = Dokter::create([
            'user_id' => $dokterUser->id,
            'poli_id' => $poli->id,
            'nama_dokter' => 'dr. Log Tester, Sp.PD',
            'nomor_sip' => 'SIP/LOG/' . mt_rand(100, 999),
            'spesialisasi' => 'Penyakit Dalam',
            'is_active' => true,
        ]);

        $jadwal = JadwalDokter::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'kuota' => 20,
            'is_active' => true,
        ]);

        $nextNomorAntrean = (int) (Pendaftaran::where('poli_id', $poli->id)
            ->whereDate('tanggal_kunjungan', Carbon::today())
            ->max('nomor_antrean') ?? 0) + 1;

        $pendaftaran = Pendaftaran::create([
            'kode_pendaftaran' => 'RJ-LOG-' . uniqid(),
            'pasien_id' => $pasien->id,
            'poli_id' => $poli->id,
            'jadwal_dokter_id' => $jadwal->id,
            'tanggal_kunjungan' => Carbon::today()->toDateString(),
            'jam_estimasi' => '08:30 - 09:30',
            'nomor_antrean' => $nextNomorAntrean,
            'jenis_penjamin' => 'Umum',
            'status' => 'menunggu_konfirmasi',
            'status_verifikasi' => 'menunggu_konfirmasi',
        ]);

        // Admin konfirmasi pendaftaran
        $response = $this->actingAs($admin)->post(route('admin.pendaftaran.konfirmasi', $pendaftaran->id), [
            'catatan_admin' => 'Dikonfirmasi untuk pemeriksaan pagi ini',
        ]);

        $response->assertStatus(302);

        // Verifikasi audit log tersimpan
        $this->assertDatabaseHas('audit_logs', [
            'modul' => 'Pendaftaran',
            'aksi' => 'Konfirmasi Pendaftaran',
        ]);
    }

    public function test_audit_log_tercatat_saat_kasir_mencatat_pembayaran(): void
    {
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@gmail.com'],
            ['name' => 'Kasir Utama', 'password' => bcrypt('password'), 'role' => 'kasir', 'is_active' => true]
        );

        $pasienUser = User::factory()->create(['role' => 'pasien', 'is_active' => true]);
        $pasien = Pasien::create([
            'user_id' => $pasienUser->id,
            'nama' => 'Pasien Tagihan Log',
            'nomor_rm' => 'RM-' . mt_rand(100000, 999999),
            'nik' => (string) mt_rand(1000000000000000, 9999999999999999),
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'P',
        ]);

        $poli = Poli::firstOrCreate(['nama_poli' => 'Poli Anak'], ['is_active' => true]);
        $dokterUser = User::factory()->create(['role' => 'dokter', 'is_active' => true]);
        $dokter = Dokter::create([
            'user_id' => $dokterUser->id,
            'poli_id' => $poli->id,
            'nama_dokter' => 'dr. Anak Log',
            'nomor_sip' => 'SIP/LOG/' . mt_rand(100, 999),
            'is_active' => true,
        ]);
        $jadwal = JadwalDokter::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '12:00',
            'kuota' => 10,
            'is_active' => true,
        ]);

        $nextAntreanKasir = (int) (Pendaftaran::where('poli_id', $poli->id)
            ->whereDate('tanggal_kunjungan', Carbon::today())
            ->max('nomor_antrean') ?? 0) + 1;

        $pendaftaran = Pendaftaran::create([
            'kode_pendaftaran' => 'RJ-TAG-' . uniqid(),
            'pasien_id' => $pasien->id,
            'poli_id' => $poli->id,
            'jadwal_dokter_id' => $jadwal->id,
            'tanggal_kunjungan' => Carbon::today()->toDateString(),
            'jam_estimasi' => '09:00 - 10:00',
            'nomor_antrean' => $nextAntreanKasir,
            'jenis_penjamin' => 'Umum',
            'status_verifikasi' => 'terverifikasi',
            'status' => 'selesai',
        ]);

        $tagihan = Tagihan::create([
            'nomor_tagihan' => 'TAG-TEST-' . mt_rand(10000, 99999),
            'pendaftaran_id' => $pendaftaran->id,
            'total_tagihan' => 75000,
            'status' => 'belum_lunas',
        ]);

        $response = $this->actingAs($kasir)->post(route('tagihan.pembayaran.store', $tagihan->id), [
            'jumlah_bayar' => 75000,
            'metode' => 'tunai',
            'referensi' => 'KASIR-01',
        ]);

        $response->assertStatus(302);

        // Verifikasi audit log pembayaran kasir
        $this->assertDatabaseHas('audit_logs', [
            'modul' => 'Kasir & Tagihan',
            'aksi' => 'Pencatatan Pembayaran',
        ]);
    }

    public function test_audit_log_tercatat_saat_farmasi_menyerahkan_obat(): void
    {
        $farmasi = User::firstOrCreate(
            ['email' => 'apoteker@gmail.com'],
            ['name' => 'Apoteker', 'password' => bcrypt('password'), 'role' => 'farmasi', 'is_active' => true]
        );

        $pasienUser = User::factory()->create(['role' => 'pasien', 'is_active' => true]);
        $pasien = Pasien::create([
            'user_id' => $pasienUser->id,
            'nama' => 'Pasien Farmasi Log',
            'nomor_rm' => 'RM-' . mt_rand(100000, 999999),
            'nik' => (string) mt_rand(1000000000000000, 9999999999999999),
            'tanggal_lahir' => '1992-02-02',
            'jenis_kelamin' => 'L',
        ]);

        $poli = Poli::firstOrCreate(['nama_poli' => 'Poli THT'], ['is_active' => true]);
        $dokterUser = User::factory()->create(['role' => 'dokter', 'is_active' => true]);
        $dokter = Dokter::create([
            'user_id' => $dokterUser->id,
            'poli_id' => $poli->id,
            'nama_dokter' => 'dr. THT Log',
            'nomor_sip' => 'SIP/LOG/' . mt_rand(100, 999),
            'is_active' => true,
        ]);
        $jadwal = JadwalDokter::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Rabu',
            'jam_mulai' => '10:00',
            'jam_selesai' => '13:00',
            'kuota' => 10,
            'is_active' => true,
        ]);

        $nextAntreanFarmasi = (int) (Pendaftaran::where('poli_id', $poli->id)
            ->whereDate('tanggal_kunjungan', Carbon::today())
            ->max('nomor_antrean') ?? 0) + 1;

        $pendaftaran = Pendaftaran::create([
            'kode_pendaftaran' => 'RJ-FAR-' . uniqid(),
            'pasien_id' => $pasien->id,
            'poli_id' => $poli->id,
            'jadwal_dokter_id' => $jadwal->id,
            'tanggal_kunjungan' => Carbon::today()->toDateString(),
            'jam_estimasi' => '10:00 - 11:00',
            'nomor_antrean' => $nextAntreanFarmasi,
            'jenis_penjamin' => 'Umum',
            'status_verifikasi' => 'terverifikasi',
            'status' => 'selesai',
        ]);

        $tagihan = Tagihan::create([
            'nomor_tagihan' => 'TAG-FAR-' . mt_rand(10000, 99999),
            'pendaftaran_id' => $pendaftaran->id,
            'total_tagihan' => 30000,
            'status' => 'lunas',
        ]);

        $obat = Obat::firstOrCreate(
            ['kode_obat' => 'OBT-LOG-01'],
            ['nama_obat' => 'Amoxicillin 500mg', 'satuan' => 'tablet', 'harga' => 15000, 'stok' => 50, 'stok_minimum' => 10, 'is_active' => true]
        );

        $resep = Resep::create([
            'pendaftaran_id' => $pendaftaran->id,
            'kode_resep' => 'RSP-LOG-' . uniqid(),
            'status' => 'siap_diserahkan',
        ]);

        DetailResep::create([
            'resep_id' => $resep->id,
            'obat_id' => $obat->id,
            'jumlah' => 2,
            'dosis' => '3x1 tablet',
        ]);

        $response = $this->actingAs($farmasi)->put(route('farmasi.updateStatus', $resep->id), [
            'status' => 'selesai',
        ]);

        $response->assertStatus(302);

        // Verifikasi audit log penyerahan obat
        $this->assertDatabaseHas('audit_logs', [
            'modul' => 'Farmasi',
            'aksi' => 'Penyerahan Obat',
        ]);
    }

    public function test_admin_dan_kepala_rs_dapat_melihat_dan_memfilter_halaman_audit_log(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin Sistem', 'password' => bcrypt('password'), 'role' => 'admin', 'is_active' => true]
        );

        $kepalaRs = User::firstOrCreate(
            ['email' => 'kepala.rs@gmail.com'],
            ['name' => 'Dr. dr. H. Soebroto, Sp.B, FICS', 'password' => bcrypt('password'), 'role' => 'kepala_rs', 'is_active' => true]
        );

        // Buat satu log dummy
        AuditLog::catat('Autentikasi', 'Uji Coba Filter', 'Pencatatan uji coba halaman audit trail');

        // Admin akses
        $responseAdmin = $this->actingAs($admin)->get(route('admin.audit-log.index'));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Audit Log');
        $responseAdmin->assertSee('Catatan Aktivitas Operasional');

        // Filter modul
        $responseFilter = $this->actingAs($admin)->get(route('admin.audit-log.index', ['modul' => 'Autentikasi']));
        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('Autentikasi');

        // Kepala RS akses
        $responseKepala = $this->actingAs($kepalaRs)->get(route('kepala-rs.audit-log.index'));
        $responseKepala->assertStatus(200);
        $responseKepala->assertSee('Audit Log');

        // Pasien tidak boleh akses (403 Forbidden)
        $pasien = User::factory()->create(['role' => 'pasien', 'is_active' => true]);
        $responsePasien = $this->actingAs($pasien)->get(route('admin.audit-log.index'));
        $responsePasien->assertStatus(403);
    }
}
