<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Obat;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $poliData = [
                [
                    'nama_poli' => 'Poli Umum',
                    'deskripsi' => 'Pelayanan kesehatan umum.',
                ],
                [
                    'nama_poli' => 'Poli Gigi',
                    'deskripsi' => 'Pemeriksaan dan perawatan gigi.',
                ],
                [
                    'nama_poli' => 'Poli Anak',
                    'deskripsi' => 'Pelayanan kesehatan anak.',
                ],
                [
                    'nama_poli' => 'Poli Penyakit Dalam',
                    'deskripsi' => 'Pelayanan penyakit dalam.',
                ],
            ];

            foreach ($poliData as $data) {
                Poli::firstOrCreate(
                    ['nama_poli' => $data['nama_poli']],
                    [
                        'deskripsi' => $data['deskripsi'],
                        'is_active' => true,
                    ]
                );
            }

            $dokterData = [
                [
                    'email' => 'dokter.andi@oasishusada.test',
                    'poli' => 'Poli Umum',
                    'sip' => 'CONTOH-SIP-001',
                    'telepon' => '081200000001',
                ],
                [
                    'email' => 'dokter.siti@oasishusada.test',
                    'poli' => 'Poli Gigi',
                    'sip' => 'CONTOH-SIP-002',
                    'telepon' => '081200000002',
                ],
            ];

            foreach ($dokterData as $data) {
                $user = User::where('email', $data['email'])->first();
                $poli = Poli::where('nama_poli', $data['poli'])->first();

                if (! $user || ! $poli) {
                    continue;
                }

                Dokter::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'poli_id' => $poli->id,
                        'nomor_sip' => $data['sip'],
                        'no_telepon' => $data['telepon'],
                        'is_active' => true,
                    ]
                );
            }

            $jadwalData = [
                [
                    'email' => 'dokter.andi@oasishusada.test',
                    'hari' => 'Senin',
                    'mulai' => '08:00:00',
                    'selesai' => '12:00:00',
                    'kuota' => 20,
                ],
                [
                    'email' => 'dokter.andi@oasishusada.test',
                    'hari' => 'Rabu',
                    'mulai' => '08:00:00',
                    'selesai' => '12:00:00',
                    'kuota' => 20,
                ],
                [
                    'email' => 'dokter.siti@oasishusada.test',
                    'hari' => 'Selasa',
                    'mulai' => '09:00:00',
                    'selesai' => '13:00:00',
                    'kuota' => 15,
                ],
                [
                    'email' => 'dokter.siti@oasishusada.test',
                    'hari' => 'Kamis',
                    'mulai' => '09:00:00',
                    'selesai' => '13:00:00',
                    'kuota' => 15,
                ],
            ];

            foreach ($jadwalData as $data) {
                $user = User::where('email', $data['email'])->first();

                $dokter = $user
                    ? Dokter::where('user_id', $user->id)->first()
                    : null;

                if (! $dokter) {
                    continue;
                }

                JadwalDokter::firstOrCreate(
                    [
                        'dokter_id' => $dokter->id,
                        'hari' => $data['hari'],
                        'jam_mulai' => $data['mulai'],
                        'jam_selesai' => $data['selesai'],
                    ],
                    [
                        'kuota' => $data['kuota'],
                        'is_active' => true,
                    ]
                );
            }

            $obatData = [
                [
                    'kode_obat' => 'OBT-001',
                    'nama_obat' => 'Paracetamol 500 mg',
                    'satuan' => 'Tablet',
                    'harga' => 1500,
                    'stok' => 200,
                    'stok_minimum' => 20,
                ],
                [
                    'kode_obat' => 'OBT-002',
                    'nama_obat' => 'Amoxicillin 500 mg',
                    'satuan' => 'Kapsul',
                    'harga' => 2500,
                    'stok' => 150,
                    'stok_minimum' => 20,
                ],
                [
                    'kode_obat' => 'OBT-003',
                    'nama_obat' => 'Antasida',
                    'satuan' => 'Tablet',
                    'harga' => 1000,
                    'stok' => 120,
                    'stok_minimum' => 15,
                ],
                [
                    'kode_obat' => 'OBT-004',
                    'nama_obat' => 'Oralit',
                    'satuan' => 'Sachet',
                    'harga' => 2000,
                    'stok' => 100,
                    'stok_minimum' => 15,
                ],
                [
                    'kode_obat' => 'OBT-005',
                    'nama_obat' => 'Vitamin C 500 mg',
                    'satuan' => 'Tablet',
                    'harga' => 1200,
                    'stok' => 180,
                    'stok_minimum' => 20,
                ],
            ];

            foreach ($obatData as $data) {
                Obat::firstOrCreate(
                    ['kode_obat' => $data['kode_obat']],
                    [
                        'nama_obat' => $data['nama_obat'],
                        'satuan' => $data['satuan'],
                        'harga' => $data['harga'],
                        'stok' => $data['stok'],
                        'stok_minimum' => $data['stok_minimum'],
                        'is_active' => true,
                    ]
                );
            }
        });
    }
}
