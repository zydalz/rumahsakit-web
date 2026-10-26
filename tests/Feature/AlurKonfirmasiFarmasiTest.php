<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Resep;
use App\Models\RiwayatStokObat;
use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlurKonfirmasiFarmasiTest extends TestCase
{
    public function test_alur_lengkap_konfirmasi_pemeriksaan_kasir_dan_farmasi(): void
    {
        // 1. Siapkan master data
        $poli = Poli::firstOrCreate(
            ['nama_poli' => 'Poli Pengujian Alur'],
            ['is_active' => true]
        );

        $userDokter = User::factory()->create([
            'role' => 'dokter',
            'is_active' => true,
        ]);

        $dokter = Dokter::create([
            'user_id' => $userDokter->id,
            'poli_id' => $poli->id,
            'nama' => 'dr. Budi Santoso',
            'spesialisasi' => 'Dokter Umum',
            'is_active' => true,
        ]);

        $jadwal = JadwalDokter::create([
            'dokter_id' => $dokter->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'kuota' => 20,
            'is_active' => true,
        ]);

        $userAdmin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $userKasir = User::factory()->create(['role' => 'kasir', 'is_active' => true]);
        $userFarmasi = User::factory()->create(['role' => 'farmasi', 'is_active' => true]);

        $userPasien = User::factory()->create(['role' => 'pasien', 'is_active' => true]);
        $pasien = Pasien::create([
            'user_id' => $userPasien->id,
            'nomor_rm' => 'RM-TEST-' . rand(1000, 9999),
            'nama' => 'Pasien Uji Coba',
            'nik' => '3201' . rand(100000000000, 999999999999),
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1995-05-10',
            'no_telepon' => '08123456789',
            'alamat' => 'Jl. Pengujian No. 1',
        ]);

        $obat = Obat::create([
            'kode_obat' => 'OBT-' . rand(100, 999),
            'nama_obat' => 'Paracetamol 500mg',
            'satuan' => 'tablet',
            'harga' => 5000,
            'stok' => 50,
            'stok_minimum' => 10,
            'is_active' => true,
        ]);

        // 2. Pendaftaran Pasien Mandiri
        $responseDaftar = $this->actingAs($userPasien)->post(route('pasien.pendaftaran.store'), [
            'poli_id' => $poli->id,
            'jadwal_dokter_id' => $jadwal->id,
            'tanggal_kunjungan' => now()->format('Y-m-d'),
            'jam_estimasi' => '08:00 - 09:00',
            'jenis_penjamin' => 'Umum',
            'keluhan_awal' => 'Demam dan pusing sejak kemarin',
        ]);

        $responseDaftar->assertSessionHasNoErrors();
        $responseDaftar->assertRedirect();

        $pendaftaran = Pendaftaran::where('pasien_id', $pasien->id)->latest()->first();
        $this->assertNotNull($pendaftaran);
        $this->assertEquals('menunggu_konfirmasi', $pendaftaran->status);
        $this->assertEquals('08:00 - 09:00', $pendaftaran->jam_estimasi);

        // 3. Admin Melakukan Konfirmasi Pendaftaran
        $responseKonfirmasi = $this->actingAs($userAdmin)->post(
            route('admin.pendaftaran.konfirmasi', $pendaftaran->id),
            ['catatan_admin' => 'Dokumen terverifikasi, silakan datang tepat waktu']
        );
        $responseKonfirmasi->assertRedirect();
        $pendaftaran->refresh();
        $this->assertEquals('dikonfirmasi', $pendaftaran->status);
        $this->assertStringContainsString('terverifikasi', $pendaftaran->catatan_admin);

        // 4. Dokter Melakukan Pemeriksaan & Membuat Resep
        $responsePeriksa = $this->actingAs($userDokter)->post(route('dokter.pemeriksaan.store'), [
            'pendaftaran_id' => $pendaftaran->id,
            'keluhan' => 'Demam dan sakit kepala',
            'diagnosis' => 'Febris Akut',
            'catatan' => 'Istirahat dan banyak minum air putih',
            'obat' => [
                [
                    'obat_id' => $obat->id,
                    'jumlah' => 10,
                    'dosis' => '3x1 tablet sesudah makan',
                    'instruksi_khusus' => 'Bila demam telah reda boleh dihentikan',
                ],
            ],
        ]);
        $responsePeriksa->assertRedirect();

        $pendaftaran->refresh();
        $this->assertEquals('selesai', $pendaftaran->status);

        // Cek Resep otomatis terbuat dengan status menunggu_pembayaran
        $resep = Resep::where('pendaftaran_id', $pendaftaran->id)->first();
        $this->assertNotNull($resep);
        $this->assertEquals('menunggu_pembayaran', $resep->status);

        // Cek Tagihan otomatis terbuat (Jasa dokter Rp 25.000 + Obat 10 x Rp 5.000 = Rp 75.000)
        $tagihan = Tagihan::where('pendaftaran_id', $pendaftaran->id)->first();
        $this->assertNotNull($tagihan);
        $this->assertEquals(75000, (float) $tagihan->total_tagihan);
        $this->assertEquals('belum_lunas', $tagihan->status);

        // 5. Verifikasi di Farmasi: Obat Belum Boleh Diserahkan Bila Belum Lunas
        $responseFarmasiGagal = $this->actingAs($userFarmasi)->put(
            route('farmasi.updateStatus', $resep->id),
            ['status' => 'selesai']
        );
        $responseFarmasiGagal->assertSessionHas('error');
        $resep->refresh();
        $this->assertNotEquals('selesai', $resep->status);
        $this->assertEquals(50, $obat->fresh()->stok); // Stok belum terpotong

        // 6. Kasir Menerima Pembayaran Lunas
        $responseBayar = $this->actingAs($userKasir)->post(
            route('tagihan.pembayaran.store', $tagihan->id),
            [
                'jumlah_bayar' => 75000,
                'metode' => 'tunai',
                'referensi' => 'BAYAR-LUNAS-001',
            ]
        );
        $responseBayar->assertRedirect();
        $tagihan->refresh();
        $this->assertEquals('lunas', $tagihan->status);

        // Resep otomatis berubah menjadi siap_diserahkan
        $resep->refresh();
        $this->assertEquals('siap_diserahkan', $resep->status);

        // 7. Farmasi Menyerahkan Obat (Lunas)
        $responseFarmasiSukses = $this->actingAs($userFarmasi)->put(
            route('farmasi.updateStatus', $resep->id),
            ['status' => 'selesai']
        );
        $responseFarmasiSukses->assertSessionHas('success');
        $resep->refresh();
        $this->assertEquals('selesai', $resep->status);

        // Validasi stok terpotong: 50 - 10 = 40
        $this->assertEquals(40, $obat->fresh()->stok);

        // Validasi riwayat mutasi stok obat tercatat
        $riwayat = RiwayatStokObat::where('obat_id', $obat->id)->latest()->first();
        $this->assertNotNull($riwayat);
        $this->assertEquals('keluar', $riwayat->jenis_perubahan);
        $this->assertEquals(10, $riwayat->jumlah);
        $this->assertEquals(50, $riwayat->stok_sebelum);
        $this->assertEquals(40, $riwayat->stok_sesudah);
    }
}
