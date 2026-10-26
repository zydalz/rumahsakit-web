<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Pendaftaran;
use App\Models\Pemeriksaan;
use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use App\Models\Pembayaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use RuntimeException;

class AlurPengujianSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $admin = User::where('email', 'admin@oasishusada.test')
                ->firstOrFail();

            $kasir = User::where('email', 'kasir@oasishusada.test')
                ->firstOrFail();

            $pasienBudi = Pasien::where('nomor_rm', 'RM-OAS-0001')
                ->firstOrFail();

            $pasienSiti = Pasien::where('nomor_rm', 'RM-OAS-0002')
                ->firstOrFail();

            $pasienRina = Pasien::where('nomor_rm', 'RM-OAS-0003')
                ->firstOrFail();

            $poli = Poli::where('nama_poli', 'Poli Umum')
                ->firstOrFail();

            $dokter = Dokter::whereHas('user', function ($query) {
                $query->where('email', 'dokter.andi@oasishusada.test');
            })->firstOrFail();

            $jadwal = JadwalDokter::where('dokter_id', $dokter->id)
                ->where('is_active', true)
                ->whereIn('hari', ['Senin', 'Rabu'])
                ->orderBy('hari')
                ->firstOrFail();

            $paracetamol = Obat::where('kode_obat', 'OBT-001')
                ->firstOrFail();

            $antasida = Obat::where('kode_obat', 'OBT-003')
                ->firstOrFail();

            if (
                !$poli->is_active ||
                !$dokter->is_active ||
                !$paracetamol->is_active ||
                !$antasida->is_active
            ) {
                throw new RuntimeException(
                    'Pastikan poli, dokter, dan obat pengujian aktif.'
                );
            }

            $skenario = [
                [
                    'kode' => 'A',
                    'pasien' => $pasienBudi,
                    'nominal_bayar' => 0,
                    'status_tagihan' => 'belum_lunas',
                ],
                [
                    'kode' => 'B',
                    'pasien' => $pasienSiti,
                    'nominal_bayar' => 10000,
                    'status_tagihan' => 'belum_lunas',
                ],
                [
                    'kode' => 'C',
                    'pasien' => $pasienRina,
                    'nominal_bayar' => 25000,
                    'status_tagihan' => 'lunas',
                ],
            ];

            foreach ($skenario as $item) {
                $kode = $item['kode'];

                // Tanggal kunjungan mengikuti hari jadwal dokter.
                $tanggal = $this->tanggalJadwalBerikutnya(
                    $jadwal->hari,
                    $kode === 'A' ? 0 : ($kode === 'B' ? 1 : 2)
                );

                // Cari kunjungan milik seeder ini terlebih dahulu.
                $pendaftaran = Pendaftaran::firstOrCreate(
                    ['kode_pendaftaran' => "UJI-ALUR-{$kode}"],
                    [
                        'pasien_id' => $item['pasien']->id,
                        'poli_id' => $poli->id,
                        'jadwal_dokter_id' => $jadwal->id,
                        'tanggal_kunjungan' => $tanggal,
                        'nomor_antrean' => $this->nomorAntreanBerikutnya(
                            $poli->id,
                            $tanggal
                        ),
                        'jenis_penjamin' => 'Umum',
                        'nomor_peserta' => null,
                        'status_verifikasi' => 'terverifikasi',
                        'keluhan_awal' => 'Data kunjungan untuk pengujian alur.',
                        'status' => 'selesai',
                        'created_by' => $admin->id,
                    ]
                );

                // Pemeriksaan hanya dibuat jika belum ada.
                Pemeriksaan::firstOrCreate(
                    ['pendaftaran_id' => $pendaftaran->id],
                    [
                        'dokter_id' => $dokter->id,
                        'keluhan' => 'Keluhan contoh untuk pengujian.',
                        'tekanan_darah' => '120/80',
                        'suhu' => 36.5,
                        'berat_badan' => 60,
                        'tinggi_badan' => 165,
                        'diagnosis' => 'Data diagnosis untuk pengujian.',
                        'catatan' => 'Data simulasi, bukan rekam medis pasien nyata.',
                    ]
                );

                // Resep dan detail obat.
                $resep = Resep::firstOrCreate(
                    ['kode_resep' => "UJI-RESEP-{$kode}"],
                    [
                        'pendaftaran_id' => $pendaftaran->id,
                        'catatan_dokter' => 'Resep simulasi untuk pengujian.',
                        'status' => 'menunggu',
                    ]
                );

                DetailResep::firstOrCreate(
                    [
                        'resep_id' => $resep->id,
                        'obat_id' => $paracetamol->id,
                    ],
                    [
                        'jumlah' => 2,
                        'dosis' => '3 kali sehari',
                        'instruksi_khusus' => 'Data simulasi.',
                    ]
                );

                DetailResep::firstOrCreate(
                    [
                        'resep_id' => $resep->id,
                        'obat_id' => $antasida->id,
                    ],
                    [
                        'jumlah' => 2,
                        'dosis' => '3 kali sehari',
                        'instruksi_khusus' => 'Data simulasi.',
                    ]
                );

                // Tagihan dibuat satu kali per kunjungan.
                $tagihan = Tagihan::firstOrCreate(
                    ['nomor_tagihan' => "UJI-TAG-{$kode}"],
                    [
                        'pendaftaran_id' => $pendaftaran->id,
                        'total_tagihan' => 25000,
                        'status' => 'belum_lunas',
                    ]
                );

                // Rincian tagihan dibuat hanya jika belum tersedia.
                if (!$tagihan->detailTagihan()->exists()) {
                    DetailTagihan::create([
                        'tagihan_id' => $tagihan->id,
                        'jenis_item' => 'layanan',
                        'deskripsi' => 'Biaya pemeriksaan',
                        'jumlah' => 1,
                        'harga_satuan' => 20000,
                        'subtotal' => 20000,
                    ]);

                    DetailTagihan::create([
                        'tagihan_id' => $tagihan->id,
                        'jenis_item' => 'obat',
                        'deskripsi' => 'Paracetamol 500 mg',
                        'jumlah' => 2,
                        'harga_satuan' => 1500,
                        'subtotal' => 3000,
                    ]);

                    DetailTagihan::create([
                        'tagihan_id' => $tagihan->id,
                        'jenis_item' => 'obat',
                        'deskripsi' => 'Antasida',
                        'jumlah' => 2,
                        'harga_satuan' => 1000,
                        'subtotal' => 2000,
                    ]);
                }

                // Jangan menambahkan pembayaran berulang kali.
                $nominalSudahDibayar = (float) $tagihan
                    ->pembayaran()
                    ->where('status', 'berhasil')
                    ->sum('jumlah_bayar');

                $nominalTarget = (float) $item['nominal_bayar'];

                if ($nominalSudahDibayar < $nominalTarget) {
                    $selisih = $nominalTarget - $nominalSudahDibayar;

                    Pembayaran::create([
                        'tagihan_id' => $tagihan->id,
                        'kasir_id' => $kasir->id,
                        'tanggal_bayar' => now(),
                        'metode' => 'tunai',
                        'jumlah_bayar' => $selisih,
                        'status' => 'berhasil',
                        'referensi' => "UJI-BAYAR-{$kode}",
                    ]);
                }

                // Status disesuaikan dengan jumlah pembayaran yang berhasil.
                $totalBayar = (float) $tagihan
                    ->pembayaran()
                    ->where('status', 'berhasil')
                    ->sum('jumlah_bayar');

                $tagihan->update([
                    'status' => $totalBayar >= (float) $tagihan->total_tagihan
                        ? 'lunas'
                        : 'belum_lunas',
                ]);
            }
        });

        $this->command?->info(
            'Seeder alur pengujian selesai diproses.'
        );
    }

    private function nomorAntreanBerikutnya(
        int $poliId,
        string $tanggal
    ): int {
        return ((int) Pendaftaran::where('poli_id', $poliId)
            ->whereDate('tanggal_kunjungan', $tanggal)
            ->max('nomor_antrean')) + 1;
    }

    private function tanggalJadwalBerikutnya(
        string $hari,
        int $tambahanMinggu
    ): string {
        $hariInggris = [
            'Minggu' => 0,
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
        ];

        if (!isset($hariInggris[$hari])) {
            throw new RuntimeException(
                "Nama hari jadwal tidak dikenali: {$hari}"
            );
        }

        $tanggal = Carbon::today();

        while ($tanggal->dayOfWeek !== $hariInggris[$hari]) {
            $tanggal->addDay();
        }

        return $tanggal
            ->addWeeks($tambahanMinggu)
            ->toDateString();
    }
}