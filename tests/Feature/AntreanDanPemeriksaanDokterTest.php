<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AntreanDanPemeriksaanDokterTest extends TestCase
{
    public function test_alur_pemanggilan_antrean_dan_layar_monitor(): void
    {
        // 1. Data Poli & Dokter
        $poli = Poli::firstOrCreate(
            ['nama_poli' => 'Poli Penyakit Dalam Khusus'],
            ['is_active' => true]
        );

        $userDokter = User::factory()->create([
            'role' => 'dokter',
            'is_active' => true,
        ]);

        $dokter = Dokter::create([
            'user_id' => $userDokter->id,
            'poli_id' => $poli->id,
            'nama' => 'dr. Hendra Pratama, Sp.PD',
            'spesialisasi' => 'Penyakit Dalam',
            'is_active' => true,
        ]);

        $jadwal = JadwalDokter::create([
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'kuota' => 15,
            'is_active' => true,
        ]);

        // 2. Data Pasien 1 & Pasien 2
        $userPasien1 = User::factory()->create(['role' => 'pasien', 'is_active' => true]);
        $pasien1 = Pasien::create([
            'user_id' => $userPasien1->id,
            'nomor_rm' => 'RM-TEST-' . rand(1000, 9999),
            'nama' => 'Pasien Antrean Pertama',
            'nik' => '3201' . rand(100000000000, 999999999999),
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Jl. Rumah Sakit',
        ]);

        $userPasien2 = User::factory()->create(['role' => 'pasien', 'is_active' => true]);
        $pasien2 = Pasien::create([
            'user_id' => $userPasien2->id,
            'nomor_rm' => 'RM-TEST-' . rand(1000, 9999),
            'nama' => 'Pasien Antrean Kedua',
            'nik' => '3201' . rand(100000000000, 999999999999),
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '1992-02-02',
            'alamat' => 'Jl. Klinik Sehat',
        ]);

        $today = Carbon::today();
        $maxAntrean = (int) Pendaftaran::where('poli_id', $poli->id)->whereDate('tanggal_kunjungan', $today)->max('nomor_antrean');
        $noAntrean1 = $maxAntrean + 1;
        $noAntrean2 = $maxAntrean + 2;

        $pendaftaran1 = Pendaftaran::create([
            'kode_pendaftaran' => 'RJ-ANT-' . rand(100, 999) . '-A',
            'pasien_id' => $pasien1->id,
            'poli_id' => $poli->id,
            'jadwal_dokter_id' => $jadwal->id,
            'tanggal_kunjungan' => $today,
            'nomor_antrean' => $noAntrean1,
            'jam_estimasi' => '08:00 - 09:00',
            'jenis_penjamin' => 'Umum',
            'status_verifikasi' => 'terverifikasi',
            'status' => 'dikonfirmasi',
        ]);

        $pendaftaran2 = Pendaftaran::create([
            'kode_pendaftaran' => 'RJ-ANT-' . rand(100, 999) . '-B',
            'pasien_id' => $pasien2->id,
            'poli_id' => $poli->id,
            'jadwal_dokter_id' => $jadwal->id,
            'tanggal_kunjungan' => $today,
            'nomor_antrean' => $noAntrean2,
            'jam_estimasi' => '08:00 - 09:00',
            'jenis_penjamin' => 'Umum',
            'status_verifikasi' => 'terverifikasi',
            'status' => 'dikonfirmasi',
        ]);

        // 3. Uji Dokter Memanggil Pasien Pertama
        $responsePanggil = $this->actingAs($userDokter)->post(
            route('dokter.antrean.panggil', $pendaftaran1->id)
        );
        $responsePanggil->assertRedirect();
        $responsePanggil->assertSessionHas('audio_panggil');
        $this->assertEquals('dipanggil', $pendaftaran1->fresh()->status);

        // 4. Uji Dokter Melewati (Skip) Pasien Pertama karena belum hadir
        $responseLewati = $this->actingAs($userDokter)->post(
            route('dokter.antrean.lewati', $pendaftaran1->id)
        );
        $responseLewati->assertRedirect();
        $this->assertEquals('dilewati', $pendaftaran1->fresh()->status);

        // 5. Uji Panggil Antrean Selanjutnya (otomatis mengambil Pasien Kedua)
        $responseNext = $this->actingAs($userDokter)->post(
            route('dokter.antrean.panggilSelanjutnya')
        );
        $responseNext->assertRedirect();
        $this->assertEquals('dipanggil', $pendaftaran2->fresh()->status);

        // 6. Uji Mulai Pemeriksaan Pasien Kedua
        $responseMulai = $this->actingAs($userDokter)->post(
            route('dokter.antrean.mulaiPeriksa', $pendaftaran2->id)
        );
        $responseMulai->assertRedirect(route('dokter.pemeriksaan.create', $pendaftaran2->id));
        $this->assertEquals('sedang_diperiksa', $pendaftaran2->fresh()->status);

        // 7. Uji Buka Formulir Pemeriksaan Pasien
        $responseCreate = $this->actingAs($userDokter)->get(
            route('dokter.pemeriksaan.create', $pendaftaran2->id)
        );
        $responseCreate->assertSee('Pemeriksaan Fisik');
        $responseCreate->assertSee('Diagnosa Klinis');
        $responseCreate->assertSee('Resep Obat Pasien');

        // 8. Uji Tampilan Layar Display Monitor Ruang Tunggu
        $responseDisplay = $this->get(route('antrean.display', ['poli_id' => $poli->id]));
        $responseDisplay->assertStatus(200);
        $responseDisplay->assertSee('RS OASIS HUSADA');
        $responseDisplay->assertSee($poli->nama_poli);
    }
}
