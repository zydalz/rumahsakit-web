<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\User;
use App\Models\GaleriAset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MasterDataDanAsetWebTest extends TestCase
{
    public function test_admin_dapat_melihat_dan_mengelola_master_data_poli(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 1. Akses halaman index master poli
        $responseIndex = $this->actingAs($admin)->get(route('admin.poli.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Manajemen Data Poli');

        // 2. Tambah poli baru
        $responseStore = $this->actingAs($admin)->post(route('admin.poli.store'), [
            'nama_poli' => 'Poli Radiologi Uji',
            'deskripsi' => 'Pemeriksaan rontgen dan USG diagnostik.',
            'is_active' => 1,
        ]);
        $responseStore->assertRedirect(route('admin.poli.index'));
        $this->assertDatabaseHas('poli', [
            'nama_poli' => 'Poli Radiologi Uji',
        ]);

        // 3. Edit poli
        $poli = Poli::where('nama_poli', 'Poli Radiologi Uji')->firstOrFail();
        $responseEdit = $this->actingAs($admin)->get(route('admin.poli.edit', $poli->id));
        $responseEdit->assertStatus(200);

        $responseUpdate = $this->actingAs($admin)->put(route('admin.poli.update', $poli->id), [
            'nama_poli' => 'Poli Radiologi Update',
            'deskripsi' => 'Deskripsi diperbarui.',
            'is_active' => 1,
        ]);
        $responseUpdate->assertRedirect(route('admin.poli.index'));
        $this->assertDatabaseHas('poli', [
            'nama_poli' => 'Poli Radiologi Update',
        ]);

        // 4. Hapus poli yang belum punya kunjungan
        $responseDelete = $this->actingAs($admin)->delete(route('admin.poli.destroy', $poli->id));
        $responseDelete->assertRedirect(route('admin.poli.index'));
        $this->assertDatabaseMissing('poli', [
            'id' => $poli->id,
        ]);
    }

    public function test_admin_dapat_mengelola_jadwal_praktik_dokter(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $poli = Poli::firstOrCreate(
            ['nama_poli' => 'Poli Test Jadwal'],
            ['deskripsi' => 'Poli Khusus Testing', 'is_active' => true]
        );
        $userDokter = User::factory()->create(['role' => 'dokter', 'is_active' => true]);
        $dokter = Dokter::create([
            'user_id' => $userDokter->id,
            'poli_id' => $poli->id,
            'nomor_sip' => 'SIP-TEST-' . uniqid(),
            'no_telepon' => '081299999999',
            'is_active' => true,
        ]);

        // Akses index jadwal
        $responseIndex = $this->actingAs($admin)->get(route('admin.jadwal.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Manajemen Jadwal Praktik Dokter');

        // Tambah jadwal
        $responseStore = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '12:00',
            'kuota' => 25,
            'is_active' => 1,
        ]);
        $responseStore->assertRedirect(route('admin.jadwal.index'));
        $this->assertDatabaseHas('jadwal_dokter', [
            'dokter_id' => $dokter->id,
            'hari' => 'Senin',
            'kuota' => 25,
        ]);
    }

    public function test_admin_dapat_mengunggah_dan_mengelola_galeri_aset_web(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 1. Akses halaman kelola galeri
        $responseIndex = $this->actingAs($admin)->get(route('admin.galeri.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Kelola Aset Visual');

        // 2. Unggah gambar aset baru
        $fakeImage = UploadedFile::fake()->image('gedung_vip.jpg', 600, 400);

        $responseStore = $this->actingAs($admin)->post(route('admin.galeri.store'), [
            'judul' => 'Ruang Perawatan VVIP Oasis Husada',
            'kategori' => 'fasilitas',
            'gambar' => $fakeImage,
            'deskripsi' => 'Kamar perawatan kelas utama dengan fasilitas terlengkap.',
            'urutan' => 1,
            'is_active' => 1,
        ]);

        $responseStore->assertRedirect(route('admin.galeri.index'));
        $this->assertDatabaseHas('galeri_asets', [
            'judul' => 'Ruang Perawatan VVIP Oasis Husada',
            'kategori' => 'fasilitas',
        ]);

        // Bersihkan file yang terunggah dalam tes
        $uploaded = GaleriAset::where('judul', 'Ruang Perawatan VVIP Oasis Husada')->first();
        if ($uploaded && File::exists(public_path('uploads/galeri/' . $uploaded->file_path))) {
            File::delete(public_path('uploads/galeri/' . $uploaded->file_path));
        }
    }

    public function test_detail_pasien_menampilkan_golongan_darah_dan_alergi(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'pasien']);
        $pasien = Pasien::create([
            'user_id' => $user->id,
            'nama' => 'Pasien Riil Test',
            'nomor_rm' => 'RM-' . mt_rand(100000, 999999),
            'nik' => (string) mt_rand(1000000000000000, 9999999999999999),
            'tanggal_lahir' => '1996-04-15',
            'jenis_kelamin' => 'L',
            'golongan_darah' => 'B',
            'riwayat_alergi' => 'Alergi Paracetamol dan Seafood',
            'no_telepon' => '081288776655',
            'kontak_darurat' => '081299881122',
            'hubungan_darurat' => 'Istri',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.pasien.show', $pasien->id));
        $response->assertStatus(200);
        $response->assertSee('Pasien Riil Test');
        $response->assertSee('Golongan Darah');
        $response->assertSee('Alergi Paracetamol dan Seafood');
        $response->assertSee('081299881122');
        $response->assertSee('Istri');
    }

    public function test_beranda_publik_menampilkan_ugd_24_jam_dan_direktori_layanan(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee('RS Oasis Husada');
        $response->assertSee('Instalasi Gawat Darurat (UGD 24 Jam Siaga)');
        $response->assertSee('(021) 8899-7722');
        $response->assertSee('Poliklinik Spesialis');
    }
}
